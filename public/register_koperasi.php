<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../app/bootstrap.php';

// NOTE: Backend penyimpanan belum diimplementasi; form ini hanya tampilan awal.
// Sesuaikan endpoint/action sesuai tabel koperasi di database ksp_mono.

?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Registrasi Koperasi - ksp_mono</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/css/bootstrap-datepicker.min.css">
    <style>
        .card {
            border: none;
            border-radius: 15px;
            box-shadow: 0 4px 20px rgba(0,0,0,0.1);
        }
        .card-header {
            background: linear-gradient(135deg, #007bff, #0056b3);
            color: white;
            border-radius: 15px 15px 0 0;
            padding: 1.5rem;
        }
        .form-floating > label {
            padding: 1rem 0.75rem;
        }
        .btn-primary {
            background: linear-gradient(135deg, #007bff, #0056b3);
            border: none;
            border-radius: 10px;
        }
        .btn-primary:hover {
            background: linear-gradient(135deg, #0056b3, #004085);
        }
    </style>
</head>
<body class="bg-light">
<div class="container py-4">
    <div class="row justify-content-center">
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header text-center">
                    <i class="bi bi-building display-4 text-white mb-2"></i>
                    <h5 class="mb-1">Registrasi Koperasi</h5>
                    <small class="text-white-50">Lengkapi data dasar koperasi Anda</small>
                </div>
                <div class="card-body p-4">
                    <form id="formRegisterKoperasi" action="register_koperasi_process.php" method="POST">
                    <h6 class="text-primary mb-3"><i class="bi bi-geo-alt-fill"></i> Informasi Lokasi</h6>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="province_id" id="koperasiProvSelect" required>
                                    <option value="">-- Pilih provinsi --</option>
                                </select>
                                <label for="koperasiProvSelect"><i class="bi bi-geo-alt"></i> Provinsi</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="regency_id" id="koperasiRegencySelect" required disabled>
                                    <option value="">-- Pilih kab/kota --</option>
                                </select>
                                <label for="koperasiRegencySelect"><i class="bi bi-building"></i> Kabupaten/Kota</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="district_id" id="koperasiDistrictSelect" required disabled>
                                    <option value="">-- Pilih kecamatan --</option>
                                </select>
                                <label for="koperasiDistrictSelect"><i class="bi bi-house"></i> Kecamatan</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating">
                                <select class="form-select" name="village_id" id="koperasiVillageSelect" required disabled>
                                    <option value="">-- Pilih kelurahan/desa --</option>
                                </select>
                                <label for="koperasiVillageSelect"><i class="bi bi-house-door"></i> Kelurahan/Desa</label>
                            </div>
                        </div>
                        <div class="d-flex gap-3 mt-3 align-items-start">
                            <div class="flex-fill">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="nama_jalan" id="namaJalanInput" placeholder="Nama Jalan">
                                    <label for="namaJalanInput"><i class="bi bi-road"></i> Nama Jalan</label>
                                </div>
                            </div>
                            <div style="width:120px;">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="nomor_rumah" id="nomorRumahInput" placeholder="No.">
                                    <label for="nomorRumahInput"><i class="bi bi-house-number"></i> No.</label>
                                </div>
                            </div>
                            <div style="width:120px;">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="postal_code" id="koperasiPostalCode" readonly disabled placeholder="Kode Pos">
                                    <label for="koperasiPostalCode"><i class="bi bi-mailbox"></i> K. Pos</label>
                                </div>
                            </div>
                        </div>
                    
                    <h6 class="text-primary mb-3 mt-4"><i class="bi bi-info-circle-fill"></i> Detail Koperasi</h6>
                   
                    <div class="form-floating mb-3">
                        <select class="form-select" name="jenis_koperasi" required>
                            <option value="">Pilih Jenis Koperasi</option>
                            <?php foreach ($koperasi_jenis as $jenis): ?>
                                <option value="<?= $jenis['id'] ?>"><?= $jenis['name'] ?></option>
                            <?php endforeach ?>
                        </select>
                        <label><i class="bi bi-tags"></i> Jenis Koperasi</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nama_koperasi" required placeholder="Nama Koperasi">
                        <label><i class="bi bi-building"></i> Nama Koperasi</label>
                    </div>
                    <div class="form-floating mb-3">
                        <div class="input-group">
                            <input type="text" class="form-control date-field" name="tanggal_pendirian" id="tanggalPendirianField" placeholder="DD-MM-YYYY" pattern="\d{2}-\d{2}-\d{4}" title="Format tanggal: DD-MM-YYYY">
                            <span class="input-group-text"><i class="bi bi-calendar" id="datePickerTrigger"></i></span>
                        </div>
                        <small class="text-muted d-block mt-1">Format: DD-MM-YYYY</small>
                        <label><i class="bi bi-calendar"></i> Tanggal Pendirian</label>
                    </div>
                    <h6 class="text-primary mb-3 mt-4"><i class="bi bi-person-fill"></i> Data Admin Koperasi</h6>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="admin_nama" required placeholder="Nama Lengkap Admin">
                        <label><i class="bi bi-person"></i> Nama Lengkap Admin</label>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control phone-field" name="admin_hp" id="adminHpField" required placeholder="Nomor HP Admin">
                                <small class="text-muted d-block mt-1">Format: 08XX-XXXX-XXXX</small>
                                <label><i class="bi bi-phone"></i> Nomor HP Admin</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="admin_username" id="adminUsernameField" required placeholder="Username Admin" pattern="[a-zA-Z0-9_]{4,20}" title="Username harus 4-20 karakter (huruf, angka, underscore)">
                                <small class="text-muted d-block mt-1">Format: 4-20 karakter (huruf, angka, _)</small>
                                <label><i class="bi bi-at"></i> Username Admin</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" name="admin_password" id="adminPasswordField" required placeholder="Password Admin">
                                <div class="password-strength-meter mt-2">
                                    <div class="progress">
                                        <div class="progress-bar" role="progressbar" style="width: 0%"></div>
                                    </div>
                                    <small class="text-muted">Kekuatan password: <span id="passwordStrengthText">lemah</span></small>
                                </div>
                                <small class="text-muted d-block mt-1">Minimal 8 karakter, mengandung huruf besar, kecil, dan angka</small>
                                <label><i class="bi bi-lock"></i> Password Admin</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" name="admin_password_confirm" required placeholder="Konfirmasi Password">
                                <label><i class="bi bi-lock-fill"></i> Konfirmasi Password</label>
                            </div>
                        </div>
                    </div>
                    <div id="alertKoperasi" class="alert alert-danger mt-3 d-none" role="alert"></div>
                    <!-- Form Action Buttons -->
                    <div class="d-flex justify-content-between mt-4">
                        <a href="/" class="btn btn-secondary">
                            <i class="bi bi-x-circle"></i> Batal
                        </a>
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-check-circle"></i> Simpan
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
<script src="/ksp_mono/public/assets/js/address-cache-test.js"></script>
<script src="/ksp_mono/public/assets/js/main.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/js/bootstrap-datepicker.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-datepicker/1.9.0/locales/bootstrap-datepicker.id.min.js"></script>
<script>
// Fungsi utama untuk inisialisasi dropdown dengan error handling yang aman
const initDropdown = (selector, url, placeholder, nextField = null) => {
  const $dropdown = $(selector);

  // Pastikan dropdown ada
  if (!$dropdown.length) {
    console.warn('Dropdown not found:', selector);
    return;
  }

  // Set status loading
  $dropdown.prop('disabled', false)
           .html(`<option value="">-- Memuat ${placeholder}... --</option>`);

  // Load data dengan error handling
  $.ajax({
    url: url,
    dataType: 'json',
    timeout: 10000, // Timeout 10 detik
    success: function(response) {
      if (!$dropdown.length) return; // Pastikan dropdown masih ada

      if (response.success && response.data && response.data.length > 0) {
        $dropdown.empty().append(`<option value="">-- Pilih ${placeholder} --</option>`);
        response.data.forEach(item => {
          $dropdown.append(`<option value="${item.id}">${item.nama}</option>`);
        });

        // Auto-focus dengan safe check
        try {
          $dropdown.get(0)?.focus();
        } catch(e) {
          console.warn('Focus failed for', selector, e);
        }
      } else {
        $dropdown.html(`<option value="">-- Gagal memuat data --</option>`);
      }
    },
    error: function(xhr, status, error) {
      if (!$dropdown.length) return;

      console.error('Ajax error for', selector, ':', error);
      if (status === 'timeout') {
        $dropdown.html(`<option value="">-- Timeout - Coba refresh --</option>`);
      } else {
        $dropdown.html(`<option value="">-- Error koneksi --</option>`);
      }
    }
  });

  // Auto-tab ke field berikutnya dengan safe check
  if (nextField) {
    $dropdown.off('change.autoTab').on('change.autoTab', function() {
      const $this = $(this);
      if (!$this.length || !$this.val()) return;

      const $next = $(nextField);
      if ($next.length) {
        try {
          $next.get(0)?.focus();
        } catch(e) {
          console.warn('Auto-tab failed to', nextField, e);
        }
      }
    });
  }
};

// Hapus semua event handlers lama yang konflik
$('#koperasiProvSelect, #koperasiRegencySelect, #koperasiDistrictSelect, #koperasiVillageSelect')
  .off('change focus blur click');

// Inisialisasi semua dropdown saat halaman ready
$(document).ready(function() {
  // 1. Provinsi (load pertama kali)
  initDropdown('#koperasiProvSelect', '/ksp_mono/api/provinces.php', 'provinsi', '#koperasiRegencySelect');
  
  // 2. Kabupaten/Kota (load setelah provinsi dipilih)
  $('#koperasiProvSelect').on('change', function() {
    const provinceId = $(this).val();
    if (provinceId) {
      initDropdown('#koperasiRegencySelect', 
        `/ksp_mono/api/regencies.php?province_id=${provinceId}`, 
        'kabupaten/kota',
        '#koperasiDistrictSelect');
    } else {
      $('#koperasiRegencySelect, #koperasiDistrictSelect, #koperasiVillageSelect')
        .prop('disabled', true)
        .empty()
        .append('<option value="">-- Pilih --</option>');
    }
  });
  
  // 3. Kecamatan (load setelah kabupaten dipilih)
  $('#koperasiRegencySelect').on('change', function() {
    const regencyId = $(this).val();
    if (regencyId) {
      initDropdown('#koperasiDistrictSelect',
        `/ksp_mono/api/districts.php?regency_id=${regencyId}`,
        'kecamatan',
        '#koperasiVillageSelect');
    } else {
      $('#koperasiDistrictSelect, #koperasiVillageSelect')
        .prop('disabled', true)
        .empty()
        .append('<option value="">-- Pilih --</option>');
    }
  });
  
  // 4. Kelurahan/Desa (load setelah kecamatan dipilih)
  $('#koperasiDistrictSelect').on('change', function() {
    const districtId = $(this).val();
    if (districtId) {
      $.ajax({
        url: `/ksp_mono/api/villages.php?district_id=${districtId}`,
        dataType: 'json',
        success: function(response) {
          if (response.success && response.data && response.data.length > 0) {
            const $vill = $('#koperasiVillageSelect');
            $vill.empty().append('<option value="">-- Pilih kelurahan/desa --</option>');
            response.data.forEach(v => {
              $vill.append(`<option value="${v.id}" data-kodepos="${v.kodepos || ''}">${v.nama}</option>`);
            });
            $vill.prop('disabled', false);
            
            // Setup auto-fill kode pos dan focus ke alamat detail
            $vill.off('change.villageHandler').on('change.villageHandler', function() {
              const $this = $(this);
              if (!$this.length) return;
              
              const selectedOption = $this.find(':selected');
              if (!selectedOption.length) return;
              
              const postalCode = selectedOption.data('kodepos') || '';
              
              // Auto-fill kode pos
              const $postal = $('#koperasiPostalCode');
              if ($postal.length) {
                $postal.val(postalCode);
              }
              
              // Auto-focus ke alamat detail
              const $address = $('input[name="alamat_detail"]');
              if ($address.length) {
                try {
                  $address.get(0)?.focus();
                } catch(e) {
                  console.warn('Auto-focus failed:', e);
                }
              }
            });
          } else {
            $('#koperasiVillageSelect')
              .prop('disabled', true)
              .empty()
              .append('<option value="">-- Pilih --</option>');
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
    } else {
      $('#koperasiVillageSelect')
        .prop('disabled', true)
        .empty()
        .append('<option value="">-- Pilih --</option>');
    }
  });
});

// Initialize all form field helpers
$(document).ready(function() {
    // Auto-fill nama_koperasi when jenis_koperasi is selected
    $('select[name="jenis_koperasi"]').on('change', function() {
        const selectedValue = $(this).val();
        if (selectedValue) {
            $('input[name="nama_koperasi"]').val(selectedValue + ' ').focus();
        }
    });
    
    // Show/hide nomor badan hukum field based on status badan hukum selection
    $('#statusBadanHukum').on('change', function() {
        const selectedValue = $(this).val();
        if (selectedValue === 'badan_hukum') {
            $('#nomorBadanHukumContainer').show();
        } else {
            $('#nomorBadanHukumContainer').hide();
        }
    });
});

$(document).ready(function() {
    // Initialize date picker
    $('#tanggalPendirianField').datepicker({
        format: 'dd-mm-yyyy',
        autoclose: true,
        todayHighlight: true,
        language: 'id'
    });
    
    // Make calendar icon trigger datepicker
    $('#datePickerTrigger').click(function() {
        $('#tanggalPendirianField').datepicker('show');
    });
    
    // Real-time date format validation
    $('#tanggalPendirianField').on('change', function() {
        if (!/^\d{2}-\d{2}-\d{4}$/.test(this.value)) {
            this.setCustomValidity('Format tanggal harus DD-MM-YYYY');
        } else {
            this.setCustomValidity('');
        }
    });
});

$(document).ready(function() {
    // Username validation
    $('#adminUsernameField').on('input', function() {
        const username = $(this).val();
        const isValid = /^[a-zA-Z0-9_]{4,20}$/.test(username);
        $(this).toggleClass('is-invalid', !isValid);
        $(this).toggleClass('is-valid', isValid && username.length > 0);
    });
});

$(document).ready(function() {
    // Password strength meter
    const $passwordField = $('#adminPasswordField');
    const $strengthMeter = $('.password-strength-meter');
    const $strengthText = $('#passwordStrengthText');
    const $progressBar = $strengthMeter.find('.progress-bar');

    $passwordField.on('input', function() {
        const password = $(this).val();
        let strength = 0;

        // Minimal 8 karakter
        if (password.length >= 8) {
            strength += 20;
        }

        // Mengandung huruf besar
        if (/[A-Z]/.test(password)) {
            strength += 20;
        }

        // Mengandung huruf kecil
        if (/[a-z]/.test(password)) {
            strength += 20;
        }

        // Mengandung angka
        if (/\d/.test(password)) {
            strength += 20;
        }

        // Mengandung karakter spesial
        if (/[^A-Za-z0-9]/.test(password)) {
            strength += 20;
        }

        $progressBar.css('width', `${strength}%`);
        $strengthText.text(getStrengthText(strength));
    });

    function getStrengthText(strength) {
        if (strength < 20) {
            return 'lemah';
        } else if (strength < 40) {
            return 'sedang';
        } else if (strength < 60) {
            return 'kuat';
        } else if (strength < 80) {
            return 'sangat kuat';
        } else {
            return 'ekstra kuat';
        }
    }
});

$(document).ready(function() {
    // Password validation
    $('#adminPasswordField').on('input', function() {
        const password = $(this).val();
        let strength = 0;
        
        // Length check
        if (password.length >= 8) strength += 1;
        
        // Contains lowercase
        if (/[a-z]/.test(password)) strength += 1;
        
        // Contains uppercase
        if (/[A-Z]/.test(password)) strength += 1;
        
        // Contains number
        if (/\d/.test(password)) strength += 1;
        
        // Update meter
        const percent = strength * 25;
        $('.progress-bar').css('width', percent + '%');
        
        // Update text
        const strengthText = ['Lemah', 'Cukup', 'Baik', 'Kuat'];
        $('#passwordStrengthText').text(strengthText[strength - 1] || '');
    });
    
    // Password confirmation check
    $('[name="admin_password"], [name="admin_password_confirm"]').on('input', function() {
        const pass1 = $('[name="admin_password"]').val();
        const pass2 = $('[name="admin_password_confirm"]').val();
        
        if (pass1 && pass2) {
            const match = pass1 === pass2;
            $('[name="admin_password_confirm"]').toggleClass('is-invalid', !match);
            $('[name="admin_password_confirm"]').toggleClass('is-valid', match);
        }
    });
});
</script>
</body>
</html>
