// Fungsi dropdown dengan error handling
const initDropdown = (selector, url, placeholder, nextField = null) => {
  const $dropdown = $(selector);
  if (!$dropdown.length) {
    console.error('Dropdown not found:', selector);
    return;
  }

  $.ajax({
    url: url,
    dataType: 'json',
    success: function (response) {
      if (response.success && response.data?.length) {
        $dropdown.empty().append(`<option value="">-- Pilih ${placeholder} --</option>`);
        response.data.forEach(item => {
          $dropdown.append(`<option value="${item.id}">${item.nama}</option>`);
        });
        $dropdown.prop('disabled', false);

        // Set saved value if exists
        if ($dropdown.is('#koperasiProvSelect') && savedAddress && savedAddress.province_id) {
          $dropdown.val(savedAddress.province_id);
          $dropdown.trigger('change');
        } else if ($dropdown.is('#koperasiRegencySelect') && savedAddress && savedAddress.regency_id) {
          $dropdown.val(savedAddress.regency_id);
          $dropdown.trigger('change');
        } else if ($dropdown.is('#koperasiDistrictSelect') && savedAddress && savedAddress.district_id) {
          $dropdown.val(savedAddress.district_id);
          $dropdown.trigger('change');
        } else if ($dropdown.is('#koperasiVillageSelect') && savedAddress && savedAddress.village_id) {
          $dropdown.val(savedAddress.village_id);
          $dropdown.trigger('change');
        }
      } // tambahkan closing brace di sini
    },
    error: function (xhr, status, error) {
      console.error('AJAX failed:', status, error);
    }
  });
};

// Inisialisasi dropdown cascade
$(document).ready(function () {
  // Load provinsi saat halaman dimuat
  initDropdown('#koperasiProvSelect', 'api/provinces.php', 'provinsi', '#koperasiRegencySelect');

  // Province change - reset semua dropdown berikutnya
  $('#koperasiProvSelect').on('change', function () {
    const provinceId = $(this).val();

    // Reset semua dropdown berikutnya
    $('#koperasiRegencySelect, #koperasiDistrictSelect, #koperasiVillageSelect')
      .prop('disabled', true)
      .empty()
      .append('<option value="">-- Pilih --</option>');

    $('#koperasiPostalCode')
      .prop('disabled', true)
      .val('');

    if (provinceId) {
      initDropdown('#koperasiRegencySelect', `api/regencies.php?province_id=${provinceId}`, 'kabupaten/kota', '#koperasiDistrictSelect');
    }
  });

  // Regency change - reset district dan villages
  $('#koperasiRegencySelect').on('change', function () {
    const regencyId = $(this).val();

    $('#koperasiDistrictSelect, #koperasiVillageSelect')
      .prop('disabled', true)
      .empty()
      .append('<option value="">-- Pilih --</option>');

    $('#koperasiPostalCode')
      .prop('disabled', true)
      .val('');

    if (regencyId) {
      initDropdown('#koperasiDistrictSelect', `api/districts.php?regency_id=${regencyId}`, 'kecamatan', '#koperasiVillageSelect');
    }
  });

  // District change - reset villages
  $('#koperasiDistrictSelect').on('change', function () {
    const districtId = $(this).val();

    $('#koperasiVillageSelect')
      .prop('disabled', true)
      .empty()
      .append('<option value="">-- Pilih --</option>');

    $('#koperasiPostalCode')
      .prop('disabled', true)
      .val('');

    if (districtId) {
      $.ajax({
        url: `api/villages.php?district_id=${districtId}`,
        dataType: 'json',
        success: function (response) {
          if (response.success && response.data && response.data.length > 0) {
            const $vill = $('#koperasiVillageSelect');
            $vill.empty().append('<option value="">-- Pilih kelurahan/desa --</option>');
            response.data.forEach(v => {
              $vill.append(`<option value="${v.id}" data-kodepos="${v.kodepos || ''}">${v.nama}</option>`);
            });
            $vill.prop('disabled', false);

            // Auto-fill kode pos saat village dipilih
            $vill.off('change.villageHandler').on('change.villageHandler', function () {
              const selectedOption = $(this).find(':selected');
              const postalCode = selectedOption.data('kodepos') || '';

              $('#koperasiPostalCode').val(postalCode).prop('disabled', false);

              buildFullAddress();

              // Focus ke field alamat
              $('input[name="alamat_detail"]').focus();
            });
          }
        },
        error: function (xhr, status, error) {
          console.error('Village load error:', error);
          $('#koperasiVillageSelect')
            .prop('disabled', true)
            .empty()
            .append('<option value="">-- Error memuat --</option>');
        }
      });
    }
  });

  // Phone formatting dengan helper functions
  $('#adminHpField').on('input', function () {
    let value = $(this).val().replace(/[^0-9]/g, '');

    // Format Indonesian phone number: 08XX-XXXX-XXXX
    let formatted = value;
    if (value.length > 4) {
      formatted = value.slice(0, 4) + '-' + value.slice(4);
    }
    if (value.length > 8) {
      formatted = formatted.slice(0, 9) + '-' + value.slice(8, 12);
    }

    $(this).val(formatted);

    // Validate length (Indonesian phone numbers: 10-13 digits)
    if (value.length >= 10 && value.length <= 13) {
      $(this).removeClass('is-invalid').addClass('is-valid');
    } else if (value.length > 0) {
      $(this).removeClass('is-valid').addClass('is-invalid');
    } else {
      $(this).removeClass('is-valid is-invalid');
    }
  });

  // Address case conversion untuk nama jalan
  $('#namaJalanInput').on('input', function () {
    let value = $(this).val();

    // Convert to title case dengan pengecualian untuk Indonesian address terms
    let formatted = value.toLowerCase().replace(/\b\w/g, function (char) {
      return char.toUpperCase();
    });

    // Indonesian address terms yang tetap lowercase
    const addressTerms = [
      'jalan', 'jl', 'gang', 'gg', 'komplek', 'komp', 'perumahan', 'perum',
      'blok', 'blk', 'no', 'nomor', 'rt', 'rw', 'kelurahan', 'kel', 'desa', 'ds',
      'kecamatan', 'kec', 'kabupaten', 'kab', 'kota'
    ];

    addressTerms.forEach(term => {
      const regex = new RegExp('\\b' + term + '\\b', 'gi');
      formatted = formatted.replace(regex, term.toLowerCase());
    });

    // Handle special cases like "No." -> "no."
    formatted = formatted.replace(/\bNo\./gi, 'no.');

    $(this).val(formatted);
  });

  // Form validation dengan AJAX submission
  $('#formRegisterKoperasi').on('submit', function (e) {
    e.preventDefault();

    // Disable submit button
    const $submitBtn = $(this).find('button[type="submit"]');
    const originalText = $submitBtn.html();
    $submitBtn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');

    // Submit form via AJAX
    $.ajax({
      url: $(this).attr('action'),
      method: 'POST',
      data: $(this).serialize(),
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          // Show success message
          Swal.fire({
            icon: 'success',
            title: 'Registrasi Berhasil!',
            text: response.message,
            showConfirmButton: false,
            timer: 2000
          }).then(() => {
            // Redirect to dashboard (auto-login already handled by session)
            window.location.href = response.redirect;
          });
        } else {
          // Show error message
          Swal.fire({
            icon: 'error',
            title: 'Registrasi Gagal',
            text: response.message,
            confirmButtonText: 'OK'
          });
        }
      },
      error: function (xhr, status, error) {
        console.error('Form submission error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Terjadi kesalahan koneksi. Silakan coba lagi.',
          confirmButtonText: 'OK'
        });
      },
      complete: function () {
        // Re-enable submit button
        $submitBtn.prop('disabled', false).html(originalText);
      }
    });
  });

  // Real-time validation
  $('#formRegisterKoperasi').on('input change', 'input[required], select[required]', function () {
    const field = $(this);
    if (field.val() && field.val().trim() !== '') {
      field.removeClass('is-invalid');
      field.next('.invalid-feedback').remove();
    }
  });

  // Auto-uppercase untuk nama koperasi
  $('input[name="nama_koperasi"]').on('input', function () {
    let value = $(this).val();
    // Convert to uppercase
    $(this).val(value.toUpperCase());
  });

  // Auto-fill nama koperasi dengan prefix
  $('select[name="jenis_koperasi"]').on('change', function () {
    const code = $(this).find(':selected').data('code');
    if (code) {
      $('input[name="nama_koperasi"]').val((code + ' ').toUpperCase()).focus();
    }
  });

  // Date picker
  $('#tanggalPendirianField').datepicker({
    format: 'dd-mm-yyyy',
    autoclose: true,
    todayHighlight: true,
    language: 'id'
  });

  // Password strength meter
  $('#adminPasswordField').on('input', function () {
    const password = $(this).val();
    let strength = 0;

    if (password.length >= 4) strength += 20;
    if (/[A-Z]/.test(password)) strength += 20;
    if (/[a-z]/.test(password)) strength += 20;
    if (/\d/.test(password)) strength += 20;
    if (/[^A-Za-z0-9]/.test(password)) strength += 20;

    const $progressBar = $('.password-strength-meter .progress-bar');
    $progressBar
      .removeClass('w-0 w-25 w-50 w-75 w-100')
      .addClass('w-' + strength)
      .attr('aria-valuenow', strength);

    const strengthText = ['Lemah', 'Cukup', 'Baik', 'Kuat'];
    $('#passwordStrengthText').text(strengthText[Math.floor(strength / 25) - 1] || '');
  });

  // Password confirmation
  $('[name="admin_password"], [name="admin_password_confirm"]').on('input', function () {
    const pass1 = $('[name="admin_password"]').val();
    const pass2 = $('[name="admin_password_confirm"]').val();

    if (pass1 && pass2) {
      const match = pass1 === pass2;
      $('[name="admin_password_confirm"]').toggleClass('is-invalid', !match).toggleClass('is-valid', match);
    }
  });

  // Address field changes
  $('#koperasiProvSelect, #koperasiRegencySelect, #koperasiDistrictSelect, #koperasiVillageSelect, #namaJalanInput, #nomorRumahInput, #koperasiPostalCode').on('change input', function () {
    saveAddressToStorage();
    buildFullAddress();
  });

  loadAddressFromStorage();

  // Duplicate checking
  function checkDuplicate(field, value, callback) {
    $.ajax({
      url: 'api/check_duplicate.php',
      method: 'GET',
      data: { field: field, value: value },
      dataType: 'json',
      success: function (response) {
        if (response.success) {
          callback(response.exists, response.message);
        } else {
          console.error('Duplicate check error:', response.message);
          callback(false, '');
        }
      },
      error: function (xhr, status, error) {
        console.error('AJAX error:', error);
        callback(false, '');
      }
    });
  }

  // Real-time duplicate checking
  $('#namaKoperasiInput').on('blur', function () {
    const value = $(this).val().trim().toUpperCase();
    if (value) {
      checkDuplicate('nama_koperasi', value, function (exists, message) {
        const $field = $('#namaKoperasiInput');
        $field.removeClass('is-invalid is-valid').next('.invalid-feedback').remove();
        if (exists) {
          // Reset nama koperasi dengan prefix jenis
          const code = $('select[name="jenis_koperasi"]').find(':selected').data('code');
          if (code) {
            $field.val((code + ' ').toUpperCase());
          } else {
            $field.val('');
          }
          Swal.fire({
            icon: 'warning',
            title: 'Nama Koperasi Sudah Terdaftar',
            text: message,
            confirmButtonText: 'OK'
          });
        }
      });
    }
  });

  $('#adminHpField').on('blur', function () {
    const value = $(this).val().trim();
    if (value) {
      checkDuplicate('admin_hp', value, function (exists, message) {
        const $field = $('#adminHpField');
        $field.removeClass('is-invalid is-valid').next('.invalid-feedback').remove();
        if (exists) {
          $field.val(''); // Reset field
          Swal.fire({
            icon: 'warning',
            title: 'Nomor HP Sudah Terdaftar',
            text: message,
            confirmButtonText: 'OK'
          });
        }
      });
    }
  });

  $('#adminUsernameField').on('blur', function () {
    const value = $(this).val().trim();
    if (value) {
      checkDuplicate('admin_username', value, function (exists, message) {
        const $field = $('#adminUsernameField');
        $field.removeClass('is-invalid is-valid').next('.invalid-feedback').remove();
        if (exists) {
          $field.val(''); // Reset field
          Swal.fire({
            icon: 'warning',
            title: 'Username Sudah Terdaftar',
            text: message,
            confirmButtonText: 'OK'
          });
        }
      });
    }
  });
});

// Helper functions
function validateAndSubmitForm() {
  const form = $('#formRegisterKoperasi')[0];

  if (!form.checkValidity()) {
    form.reportValidity();
    return false;
  }

  if (!validatePasswordMatch()) return false;
  if (!validateRequiredFields()) return false;

  return true;
}

function validatePasswordMatch() {
  const password = $('#adminPasswordField').val();
  const confirmPassword = $('#adminPasswordConfirmField').val();

  if (password && confirmPassword && password !== confirmPassword) {
    $('#adminPasswordConfirmField').addClass('is-invalid');
    if (!$('#adminPasswordConfirmField').next('.invalid-feedback').length) {
      $('#adminPasswordConfirmField').after('<div class="invalid-feedback">Password tidak cocok</div>');
    }
    $('#adminPasswordConfirmField').focus();
    return false;
  }

  $('#adminPasswordConfirmField').removeClass('is-invalid').next('.invalid-feedback').remove();
  return true;
}

function validateRequiredFields() {
  let isValid = true;
  const requiredFields = [
    '#koperasiProvSelect', '#koperasiRegencySelect', '#koperasiDistrictSelect',
    '#koperasiVillageSelect', '#namaJalanInput', '#jenisKoperasiSelect',
    '#namaKoperasiInput', '#adminNamaInput', '#adminHpField',
    '#adminUsernameField', '#adminPasswordField', '#adminPasswordConfirmField'
  ];

  requiredFields.forEach(function (fieldId) {
    const field = $(fieldId);
    if (!field.val() || field.val().trim() === '') {
      field.addClass('is-invalid');
      if (!field.next('.invalid-feedback').length) {
        field.after(`<div class="invalid-feedback">${field.prev('label').text()} wajib diisi</div>`);
      }
      if (isValid) field.focus();
      isValid = false;
    } else {
      field.removeClass('is-invalid').next('.invalid-feedback').remove();
    }
  });

  return isValid;
}

// Address rendering functions
let savedAddress = null;

function buildFullAddress() {
  const province = $('#koperasiProvSelect option:selected').text();
  const regency = $('#koperasiRegencySelect option:selected').text();
  const district = $('#koperasiDistrictSelect option:selected').text();
  const village = $('#koperasiVillageSelect option:selected').text();
  const jalan = $('#namaJalanInput').val().trim();
  const nomor = $('#nomorRumahInput').val().trim();
  const postal = $('#koperasiPostalCode').val().trim();

  let address = '';
  if (jalan) address += jalan;
  if (nomor) address += ' ' + nomor;
  if (village && village !== '-- Pilih kelurahan/desa --') address += ', ' + village;
  if (district && district !== '-- Pilih kecamatan --') address += ', ' + district;
  if (regency && regency !== '-- Pilih kabupaten/kota --') address += ', ' + regency;
  if (province && province !== '-- Pilih provinsi --') address += ', ' + province;
  if (postal) address += ', ' + postal;

  address = address.replace(/^,/, '').trim();
  $('#alamatLengkapDisplay').val(address);
}

function saveAddressToStorage() {
  const data = {
    province_id: $('#koperasiProvSelect').val(),
    regency_id: $('#koperasiRegencySelect').val(),
    district_id: $('#koperasiDistrictSelect').val(),
    village_id: $('#koperasiVillageSelect').val(),
    nama_jalan: $('#namaJalanInput').val(),
    nomor_rumah: $('#nomorRumahInput').val(),
    postal_code: $('#koperasiPostalCode').val()
  };
  localStorage.setItem('register_address', JSON.stringify(data));
}

function loadAddressFromStorage() {
  const data = localStorage.getItem('register_address');
  if (data) {
    savedAddress = JSON.parse(data);
    // Set inputs
    $('#namaJalanInput').val(savedAddress.nama_jalan || '');
    $('#nomorRumahInput').val(savedAddress.nomor_rumah || '');
    $('#koperasiPostalCode').val(savedAddress.postal_code || '');
    buildFullAddress();
  }
}

// Load saved address on ready
$(document).ready(function () {
  loadAddressFromStorage();
});
