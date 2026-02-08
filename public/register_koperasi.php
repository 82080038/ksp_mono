<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/validation_constants.php';

// Get database connection
$db = Database::conn();

// Load koperasi jenis data
$koperasi_jenis = [];
try {
    $stmt = $db->query('SELECT id, name, code FROM koperasi_jenis WHERE is_active = 1 ORDER BY name');
    $koperasi_jenis = $stmt->fetchAll(PDO::FETCH_ASSOC);
} catch (Exception $e) {
    // Fallback to default data if database query fails
    $koperasi_jenis = [
        ['id' => 1, 'name' => 'Koperasi Simpan Pinjam (KSP)', 'code' => 'KSP'],
        ['id' => 2, 'name' => 'Koperasi Konsumsi', 'code' => 'KK'],
        ['id' => 3, 'name' => 'Koperasi Produksi', 'code' => 'KP'],
        ['id' => 4, 'name' => 'Koperasi Pemasaran', 'code' => 'KPAS'],
        ['id' => 5, 'name' => 'Koperasi Jasa', 'code' => 'KJ'],
        ['id' => 6, 'name' => 'Koperasi Serba Usaha (KSU)', 'code' => 'KSU'],
    ];
}

// NOTE: Backend penyimpanan belum diimplementasi; form ini hanya tampilan awal.
// Sesuaikan endpoint/action sesuai tabel koperasi di database ksp_mono.

?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Daftar Koperasi - ksp_mono</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-8">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white text-center">
                    <i class="bi bi-building display-4 mb-2"></i>
                    <h5 class="mb-1">Pendaftaran Koperasi Baru</h5>
                    <small>Isi data lengkap koperasi Anda</small>
                </div>
                <div class="card-body p-4">
                    <form id="formRegisterKoperasi" action="register_koperasi_process.php" method="POST">
                        <div class="row">
                            <!-- First row: Province + Regency -->
                            <div class="col-md-6 mb-3">
                                <label for="koperasiProvSelect" class="form-label"><i class="bi bi-geo-alt"></i> Provinsi</label>
                                <select class="form-select" name="province_id" id="koperasiProvSelect" required tabindex="1">
                                    <option value="">-- Pilih provinsi --</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="koperasiRegencySelect" class="form-label"><i class="bi bi-building"></i> Kabupaten/Kota</label>
                                <select class="form-select" name="regency_id" id="koperasiRegencySelect" required disabled tabindex="2">
                                    <option value="">-- Pilih kab/kota --</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Second row: District + Village -->
                            <div class="col-md-6 mb-3">
                                <label for="koperasiDistrictSelect" class="form-label"><i class="bi bi-house"></i> Kecamatan</label>
                                <select class="form-select" name="district_id" id="koperasiDistrictSelect" required disabled tabindex="3">
                                    <option value="">-- Pilih kecamatan --</option>
                                </select>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="koperasiVillageSelect" class="form-label"><i class="bi bi-house-door"></i> Kelurahan/Desa</label>
                                <select class="form-select" name="village_id" id="koperasiVillageSelect" required disabled tabindex="4">
                                    <option value="">-- Pilih kelurahan/desa --</option>
                                </select>
                            </div>
                        </div>
                        <div class="row">
                            <!-- Address details -->
                            <div class="col-md-8 mb-3">
                                <label for="namaJalanInput" class="form-label"><i class="bi bi-road"></i> Nama Jalan</label>
                                <input type="text" class="form-control" name="nama_jalan" id="namaJalanInput" placeholder="Nama Jalan" required tabindex="5">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="nomorRumahInput" class="form-label"><i class="bi bi-house-number"></i> No.</label>
                                <input type="text" class="form-control" name="nomor_rumah" id="nomorRumahInput" placeholder="No." tabindex="6">
                            </div>
                            <div class="col-md-2 mb-3">
                                <label for="koperasiPostalCode" class="form-label"><i class="bi bi-mailbox"></i> Kode Pos</label>
                                <input type="text" class="form-control" name="postal_code" id="koperasiPostalCode" readonly disabled placeholder="Kode Pos" pattern="[0-9]{5}" title="Kode pos harus 5 digit angka" tabindex="7">
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-4 mb-3">
                                <label for="jenisKoperasiSelect" class="form-label"><i class="bi bi-tags"></i> Jenis Koperasi</label>
                                <select class="form-select" name="jenis_koperasi" id="jenisKoperasiSelect" required tabindex="8">
                                    <?php foreach ($koperasi_jenis as $jenis): ?>
                                        <option value="<?= $jenis['id'] ?>" data-code="<?= htmlspecialchars($jenis['code'] ?? '') ?>"><?= $jenis['name'] ?></option>
                                    <?php endforeach ?>
                                </select>
                            </div>
                            <div class="col-md-8 mb-3">
                                <label for="namaKoperasiInput" class="form-label"><i class="bi bi-building"></i> Nama Koperasi</label>
                                <input type="text" class="form-control" id="namaKoperasiInput" name="nama_koperasi" required 
                                    minlength="<?php echo MIN_NAME_LENGTH; ?>" maxlength="<?php echo MAX_NAME_LENGTH; ?>" tabindex="9">
                                <small class="text-muted">Minimal <?php echo MIN_NAME_LENGTH; ?> karakter, maksimal <?php echo MAX_NAME_LENGTH; ?> karakter</small>
                            </div>
                        </div>
                        
                        <h6 class="text-primary mb-3 mt-4"><i class="bi bi-info-circle-fill"></i> Detail Koperasi</h6>
                       
                        <div class="mb-3">
                            <label for="adminNamaInput" class="form-label"><i class="bi bi-person"></i> Nama Admin</label>
                            <input type="text" class="form-control" id="adminNamaInput" name="admin_nama" required 
                                minlength="<?php echo MIN_NAME_LENGTH; ?>" maxlength="<?php echo MAX_NAME_LENGTH; ?>" tabindex="10">
                            <small class="text-muted">Minimal <?php echo MIN_NAME_LENGTH; ?> karakter, maksimal <?php echo MAX_NAME_LENGTH; ?> karakter</small>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="adminHpField" class="form-label"><i class="bi bi-phone"></i> Nomor HP Admin</label>
                                <input type="text" class="form-control phone-field" id="adminHpField" name="admin_hp" required placeholder="Nomor HP Admin" tabindex="11">
                                <small class="text-muted">Format: 08XX-XXXX-XXXX</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="adminUsernameField" class="form-label"><i class="bi bi-person-circle"></i> Username Admin</label>
                                <input type="text" class="form-control" id="adminUsernameField" name="admin_username" required placeholder="Username Admin" pattern="[a-zA-Z0-9_]{4,20}" title="Username harus 4-20 karakter (huruf, angka, underscore)" tabindex="12">
                                <small class="text-muted">Minimal 4 karakter (sementara untuk development)</small>
                            </div>
                        </div>

                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label for="adminPasswordField" class="form-label"><i class="bi bi-lock"></i> Password Admin</label>
                                <input type="password" class="form-control" id="adminPasswordField" name="admin_password" required placeholder="Password Admin" tabindex="13">
                                <div class="password-strength-meter mt-2">
                                    <div class="progress">
                                        <div class="progress-bar w-0" role="progressbar" aria-valuenow="0" aria-valuemin="0" aria-valuemax="100"></div>
                                    </div>
                                    <small class="text-muted">Kekuatan password: <span id="passwordStrengthText">lemah</span></small>
                                </div>
                                <small class="text-muted d-block mt-1">Minimal 4 karakter untuk development</small>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label for="adminPasswordConfirmField" class="form-label"><i class="bi bi-lock-fill"></i> Konfirmasi Password Admin</label>
                                <input type="password" class="form-control" id="adminPasswordConfirmField" name="admin_password_confirm" required tabindex="14">
                            </div>
                        </div>

                        <div class="form-actions">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-send-fill"></i> Daftarkan Koperasi
                            </button>
                        </div>
                    </form>
                </div>
                <div class="card-footer bg-white text-center">
                    <a href="/ksp_mono/login.php">Kembali ke Login</a>
                </div>
            </div>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/inputmask@5.0.8/dist/jquery.inputmask.min.js"></script>
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@11/dist/sweetalert2.all.min.js"></script>
<script src="/ksp_mono/public/assets/js/main.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.id.min.js"></script>
<script>
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
    success: function(response) {
      if (response.success && response.data?.length) {
        $dropdown.empty().append(`<option value="">-- Pilih ${placeholder} --</option>`);
        response.data.forEach(item => {
          $dropdown.append(`<option value="${item.id}">${item.nama}</option>`);
        });
        $dropdown.prop('disabled', false);
      }
    },
    error: function(xhr, status, error) {
      console.error('AJAX failed:', status, error);
    }
  });
};

// Inisialisasi dropdown cascade
$(document).ready(function() {
  // Load provinsi saat halaman dimuat
  initDropdown('#koperasiProvSelect', '/ksp_mono/public/api/provinces.php', 'provinsi', '#koperasiRegencySelect');
  
  // Province change - reset semua dropdown berikutnya
  $('#koperasiProvSelect').on('change', function() {
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
      initDropdown('#koperasiRegencySelect', 
        `/ksp_mono/public/api/regencies.php?province_id=${provinceId}`, 
        'kabupaten/kota',
        '#koperasiDistrictSelect');
    }
  });
  
  // Regency change - reset district dan villages
  $('#koperasiRegencySelect').on('change', function() {
    const regencyId = $(this).val();
    
    $('#koperasiDistrictSelect, #koperasiVillageSelect')
      .prop('disabled', true)
      .empty()
      .append('<option value="">-- Pilih --</option>');
    
    $('#koperasiPostalCode')
      .prop('disabled', true)
      .val('');
    
    if (regencyId) {
      initDropdown('#koperasiDistrictSelect',
        `/ksp_mono/public/api/districts.php?regency_id=${regencyId}`,
        'kecamatan',
        '#koperasiVillageSelect');
    }
  });
  
  // District change - reset villages
  $('#koperasiDistrictSelect').on('change', function() {
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
        url: `/ksp_mono/public/api/villages.php?district_id=${districtId}`,
        dataType: 'json',
        success: function(response) {
          if (response.success && response.data && response.data.length > 0) {
            const $vill = $('#koperasiVillageSelect');
            $vill.empty().append('<option value="">-- Pilih kelurahan/desa --</option>');
            response.data.forEach(v => {
              $vill.append(`<option value="${v.id}" data-kodepos="${v.kodepos || ''}">${v.nama}</option>`);
            });
            $vill.prop('disabled', false);
            
            // Auto-fill kode pos saat village dipilih
            $vill.off('change.villageHandler').on('change.villageHandler', function() {
              const selectedOption = $(this).find(':selected');
              const postalCode = selectedOption.data('kodepos') || '';
              
              $('#koperasiPostalCode').val(postalCode).prop('disabled', false);
              
              // Focus ke field alamat
              $('input[name="alamat_detail"]').focus();
            });
          }
        },
        error: function(xhr, status, error) {
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
  $('#adminHpField').on('input', function() {
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
  $('#namaJalanInput').on('input', function() {
    let value = $(this).val();
    
    // Convert to title case dengan pengecualian untuk Indonesian address terms
    let formatted = value.toLowerCase().replace(/\b\w/g, function(char) {
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
  $('#formRegisterKoperasi').on('submit', function(e) {
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
      success: function(response) {
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
      error: function(xhr, status, error) {
        console.error('Form submission error:', error);
        Swal.fire({
          icon: 'error',
          title: 'Error',
          text: 'Terjadi kesalahan koneksi. Silakan coba lagi.',
          confirmButtonText: 'OK'
        });
      },
      complete: function() {
        // Re-enable submit button
        $submitBtn.prop('disabled', false).html(originalText);
      }
    });
  });

  // Real-time validation
  $('#formRegisterKoperasi').on('input change', 'input[required], select[required]', function() {
    const field = $(this);
    if (field.val() && field.val().trim() !== '') {
      field.removeClass('is-invalid');
      field.next('.invalid-feedback').remove();
    }
  });
  
  // Auto-uppercase untuk nama koperasi
  $('input[name="nama_koperasi"]').on('input', function() {
    let value = $(this).val();
    // Convert to uppercase
    $(this).val(value.toUpperCase());
  });

  // Auto-fill nama koperasi dengan prefix
  $('select[name="jenis_koperasi"]').on('change', function() {
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
  $('#adminPasswordField').on('input', function() {
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
    $('#passwordStrengthText').text(strengthText[Math.floor(strength/25) - 1] || '');
  });

  // Password confirmation
  $('[name="admin_password"], [name="admin_password_confirm"]').on('input', function() {
    const pass1 = $('[name="admin_password"]').val();
    const pass2 = $('[name="admin_password_confirm"]').val();
    
    if (pass1 && pass2) {
      const match = pass1 === pass2;
      $('[name="admin_password_confirm"]').toggleClass('is-invalid', !match).toggleClass('is-valid', match);
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
  
  requiredFields.forEach(function(fieldId) {
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
</script>
</body>
</html>
