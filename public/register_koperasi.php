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
                        <div class="d-flex flex-wrap gap-3 mt-3 align-items-start">
                            <div class="flex-fill" style="min-width:240px;">
                                <div class="form-floating mb-3">
                                    <textarea class="form-control" name="alamat_lengkap" rows="2" required placeholder="Alamat lengkap"></textarea>
                                    <label><i class="bi bi-map"></i> Alamat Lengkap</label>
                                </div>
                            </div>
                            <div style="width:90px;">
                                <div class="form-floating">
                                    <input type="text" class="form-control" name="postal_code" id="koperasiPostalCode" readonly disabled placeholder="Kode Pos">
                                    <label for="koperasiPostalCode"><i class="bi bi-mailbox"></i> K. Pos</label>
                                </div>
                            </div>
                        </div>
                    
                    <h6 class="text-primary mb-3 mt-4"><i class="bi bi-info-circle-fill"></i> Detail Koperasi</h6>
                   
                 
                   
                    <div class="form-floating mb-3">
                        <select class="form-select" name="jenis_koperasi" required>
                            <option value="">-- Pilih jenis koperasi --</option>
                            <option value="KSP">Koperasi Simpan Pinjam (KSP)</option>
                            <option value="KK">Koperasi Konsumsi</option>
                            <option value="KP">Koperasi Produksi</option>
                            <option value="KJ">Koperasi Jasa</option>
                            <option value="KSU">Koperasi Serba Usaha</option>
                        </select>
                        <label><i class="bi bi-tag"></i> Jenis Koperasi</label>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="nama_koperasi" required placeholder="Nama Koperasi">
                        <label><i class="bi bi-building"></i> Nama Koperasi</label>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="kontak" id="kontakField" required placeholder="Kontak (Telp/HP)">
                                <label><i class="bi bi-telephone"></i> Kontak (Telp/HP)</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="npwp" placeholder="NPWP (opsional)">
                                <label><i class="bi bi-receipt"></i> NPWP (opsional)</label>
                            </div>
                        </div>
                    </div>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="badan_hukum" placeholder="Badan Hukum">
                        <label><i class="bi bi-file-text"></i> Badan Hukum</label>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="tanggal_pendirian" id="tanggalPendirianField" placeholder="DD-MM-YYYY">
                                <label><i class="bi bi-calendar"></i> Tanggal Pendirian</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="number" class="form-control" name="modal_pokok" id="modalPokokField" step="0.01" placeholder="0.00">
                                <label><i class="bi bi-cash"></i> Modal Pokok</label>
                            </div>
                        </div>
                    <h6 class="text-primary mb-3 mt-4"><i class="bi bi-person-fill"></i> Data Admin Koperasi</h6>
                    <div class="form-floating mb-3">
                        <input type="text" class="form-control" name="admin_nama" required placeholder="Nama Lengkap Admin">
                        <label><i class="bi bi-person"></i> Nama Lengkap Admin</label>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="admin_hp" id="adminHpField" required placeholder="Nomor HP Admin">
                                <label><i class="bi bi-phone"></i> Nomor HP Admin</label>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="text" class="form-control" name="admin_username" required placeholder="Username Admin">
                                <label><i class="bi bi-at"></i> Username Admin</label>
                            </div>
                        </div>
                    </div>
                    <div class="row g-3">
                        <div class="col-md-6">
                            <div class="form-floating mb-3">
                                <input type="password" class="form-control" name="admin_password" required placeholder="Password Admin">
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
<script>
$(function(){
    // Wait for AddressCache to be available (improved detection and fallback)
    const initializeAddressSystem = () => {
        console.log('Initializing address data system...');

        // Check if localStorage is available
        const localStorageAvailable = (() => {
            try {
                const test = '__localStorage_test__';
                localStorage.setItem(test, test);
                localStorage.removeItem(test);
                return true;
            } catch (e) {
                return false;
            }
        })();

        console.log('localStorage available:', localStorageAvailable);

        // Check if AddressCacheTest is available and functional
        const addressCacheAvailable = window.AddressCacheTest &&
            typeof window.AddressCacheTest === 'object' &&
            typeof window.AddressCacheTest.getData === 'function';

        console.log('AddressCacheTest available:', addressCacheAvailable);

        if (!localStorageAvailable) {
            // Show cache disabled notification
            const cacheNotification = $(
                '<div class="alert alert-info alert-dismissible fade show" role="alert">' +
                '<i class="bi bi-info-circle"></i> ' +
                '<strong>Cache Disabled:</strong> Browser cache/localStorage is disabled. ' +
                'Address data will load from server each time. ' +
                'Enable cache for better performance.' +
                '<button type="button" class="btn-close" data-bs-dismiss="alert"></button>' +
                '</div>'
            );

            // Insert notification at the top of the form
            $('#formRegisterKoperasi').prepend(cacheNotification);

            // Auto-hide after 10 seconds
            setTimeout(() => {
                cacheNotification.fadeOut();
            }, 10000);

            console.warn('localStorage disabled - using direct API calls only');
        }

        // Initialize dropdowns
        initializeDropdowns(addressCacheAvailable && localStorageAvailable);
    };

    // Initialize dropdowns with or without cache
    const initializeDropdowns = (useCache = true) => {
        console.log('Initializing dropdowns, useCache:', useCache);

        // Declare jQuery selectors in outer scope
        const $prov = $('#koperasiProvSelect');
        const $reg = $('#koperasiRegencySelect');
        const $dist = $('#koperasiDistrictSelect');
        const $vill = $('#koperasiVillageSelect');

        // Define all helper functions
        const populateProvinceDropdown = (provinces) => {
            provinces.forEach(item => $prov.append(`<option value="${item.id}">${item.nama}</option>`));
            console.log(`Loaded ${provinces.length} provinces`);
        };

        const populateRegenciesDropdown = (regencies) => {
            regencies.forEach(item => $reg.append(`<option value="${item.id}">${item.nama}</option>`));
            $reg.prop('disabled', false);
        };

        const populateDistrictsDropdown = (districts) => {
            districts.forEach(item => $dist.append(`<option value="${item.id}">${item.nama}</option>`));
            $dist.prop('disabled', false);
        };

        const populateVillagesDropdown = (villages) => {
            villages.forEach(item => $vill.append(`<option value="${item.id}" data-kodepos="${item.postal_code || ''}">${item.nama}</option>`));
            $vill.prop('disabled', false);
        };

        const loadProvinces = (useCache = true) => {
            if (useCache && window.AddressCacheTest) {
                window.AddressCacheTest.getData('provinsi').then(provinces => {
                    populateProvinceDropdown(provinces);
                }).catch(error => {
                    console.warn('AddressCacheTest failed, falling back to direct API:', error.message);
                    loadProvincesDirect();
                });
            } else {
                loadProvincesDirect();
            }
        };

        const loadProvincesDirect = () => {
            $.getJSON('/ksp_mono/api/provinces.php')
                .done(function(res){
                    if (res.success && Array.isArray(res.data)) {
                        populateProvinceDropdown(res.data);
                    }
                })
                .fail(function(){
                    console.error('Failed to load provinces');
                });
        };

        const setupDropdownHandlers = (useCache = true) => {
            $prov.on('change', function(){
                const pid = $(this).val();
                $reg.prop('disabled', true).empty().append('<option value="">-- Pilih kab/kota --</option>');
                $dist.prop('disabled', true).empty().append('<option value="">-- Pilih kecamatan --</option>');
                $vill.prop('disabled', true).empty().append('<option value="">-- Pilih kelurahan/desa --</option>');
                if (!pid) return;

                loadRegencies(pid, useCache);
            });

            $reg.on('change', function(){
                const rid = $(this).val();
                $dist.prop('disabled', true).empty().append('<option value="">-- Pilih kecamatan --</option>');
                $vill.prop('disabled', true).empty().append('<option value="">-- Pilih kelurahan/desa --</option>');
                $('#koperasiPostalCode').val('').prop('disabled', true);
                if (!rid) return;

                loadDistricts(rid, useCache);
            });

            $dist.on('change', function(){
                const did = $(this).val();
                $vill.prop('disabled', true).empty().append('<option value="">-- Pilih kelurahan/desa --</option>');
                $('#koperasiPostalCode').val('').prop('disabled', true);
                if (!did) return;

                loadVillages(did, useCache);
            });
        };

        const loadRegencies = (provinceId, useCache = true) => {
            if (useCache && window.AddressCacheTest && window.AddressCacheTest.getData) {
                // Use sophisticated caching strategy
                window.AddressCacheTest.getData('kabkota', parseInt(provinceId))
                    .then(regencies => {
                        populateRegenciesDropdown(regencies);
                        console.log(`Loaded ${regencies.length} regencies for province ${provinceId}`);
                    })
                    .catch(error => {
                        console.warn('AddressCacheTest failed for regencies:', error.message);
                        loadRegenciesDirect(provinceId);
                    });
            } else {
                loadRegenciesDirect(provinceId);
            }
        };

        const loadRegenciesDirect = (provinceId) => {
            $.getJSON(`/ksp_mono/api/regencies.php?province_id=${provinceId}`)
                .done(function(res){
                    if (res.success && Array.isArray(res.data)) {
                        populateRegenciesDropdown(res.data);
                    }
                });
        };

        const loadDistricts = (regencyId, useCache = true) => {
            if (useCache && window.AddressCacheTest && window.AddressCacheTest.getData) {
                // Use sophisticated caching strategy
                window.AddressCacheTest.getData('kecamatan', parseInt(regencyId))
                    .then(districts => {
                        populateDistrictsDropdown(districts);
                        console.log(`Loaded ${districts.length} districts for regency ${regencyId}`);
                    })
                    .catch(error => {
                        console.warn('AddressCacheTest failed for districts:', error.message);
                        loadDistrictsDirect(regencyId);
                    });
            } else {
                loadDistrictsDirect(regencyId);
            }
        };

        const loadDistrictsDirect = (regencyId) => {
            $.getJSON(`/ksp_mono/api/districts.php?regency_id=${regencyId}`)
                .done(function(res){
                    if (res.success && Array.isArray(res.data)) {
                        populateDistrictsDropdown(res.data);
                    }
                });
        };

        const loadVillages = (districtId, useCache = true) => {
            if (useCache && window.AddressCacheTest && window.AddressCacheTest.getData) {
                // Use sophisticated caching strategy
                window.AddressCacheTest.getData('kelurahan', parseInt(districtId))
                    .then(villages => {
                        populateVillagesDropdown(villages);
                        console.log(`Loaded ${villages.length} villages for district ${districtId}`);
                    })
                    .catch(error => {
                        console.warn('AddressCacheTest failed for villages:', error.message);
                        loadVillagesDirect(districtId);
                    });
            } else {
                loadVillagesDirect(districtId);
            }
        };

        const loadVillagesDirect = (districtId) => {
            $.getJSON(`/ksp_mono/api/villages.php?district_id=${districtId}`)
                .done(function(res){
                    if (res.success && Array.isArray(res.data)) {
                        populateVillagesDropdown(res.data);
                    }
                });
        };

        // Execute initialization
        loadProvinces(useCache);
        setupDropdownHandlers(useCache);

        // Village change handler for postal code (needs to be inside scope where $vill is defined)
        $vill.on('change', function(){
            const kode = $(this).find(':selected').data('kodepos') || '';
            $('#koperasiPostalCode').val(kode).prop('disabled', false);
        });
    };

    // Initialize the address system
    initializeAddressSystem();

    // Jenis koperasi auto-fill nama_koperasi (doesn't depend on AddressCache)
    $('select[name="jenis_koperasi"]').on('change', function(){
        const selectedValue = $(this).val();
        if (selectedValue) {
            $('input[name="nama_koperasi"]').val(selectedValue).focus();
        }
    });

    // Initialize input masks
    // Phone number masking with enhanced behavior
    $('#kontakField, #adminHpField').on('keydown', function(e) {
        const $field = $(this);
        const currentValue = $field.val();

        // If user types '0' at the beginning and field is empty or starts with 0
        if (e.key === '0' && (currentValue === '' || currentValue === '0')) {
            e.preventDefault(); // Prevent the '0' from being typed

            // Show "62" flashing effect
            const originalPlaceholder = $field.attr('placeholder') || '';
            $field.attr('placeholder', '62');

            // Flash effect - change background color and text
            let flashCount = 0;
            const flashInterval = setInterval(() => {
                if (flashCount >= 6) { // 3 flashes (on-off-on-off-on-off)
                    clearInterval(flashInterval);
                    $field.attr('placeholder', originalPlaceholder);
                    $field.focus();
                    return;
                }

                if (flashCount % 2 === 0) {
                    $field.css('background-color', '#e3f2fd'); // Light blue flash
                    $field.attr('placeholder', '62');
                } else {
                    $field.css('background-color', ''); // Normal background
                    $field.attr('placeholder', '');
                }

                flashCount++;
            }, 200); // 200ms intervals for 3 flashes

            return false;
        }
    }).on('input', function() {
        const $field = $(this);
        let value = $field.val().replace(/-/g, '');

        // Remove any leading zeros and ensure 62 prefix
        if (value.length > 0) {
            // Remove leading zeros
            value = value.replace(/^0+/, '');

            // Ensure starts with 62
            if (!value.startsWith('62')) {
                value = '62' + value;
            }

            // Apply Indonesian phone number formatting (every 4 digits)
            let formatted = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formatted += '-';
                }
                formatted += value[i];
            }

            $field.val(formatted);
        }

        // Validation: Indonesian phone numbers should be 12-15 digits (including 62)
        const cleanValue = value.replace(/-/g, '');
        if (cleanValue.length >= 12 && cleanValue.length <= 15 && cleanValue.startsWith('62')) {
            $field.removeClass('is-invalid').addClass('is-valid');
        } else if (cleanValue.length > 0) {
            $field.removeClass('is-valid').addClass('is-invalid');
        } else {
            $field.removeClass('is-valid is-invalid');
        }
    }).on('blur', function() {
        const $field = $(this);
        const value = $field.val().replace(/-/g, '');

        // Final validation on blur
        if (value.length > 0 && (value.length < 12 || value.length > 15 || !value.startsWith('62'))) {
            $field.removeClass('is-valid').addClass('is-invalid');
        }
    });

    // Indonesian date masking with calendar icon
    $('#tanggalPendirianField').inputmask('99-99-9999', {
        placeholder: 'DD-MM-YYYY',
        showMaskOnHover: false,
        showMaskOnFocus: false
    }).wrap('<div class="input-group"></div>').after('<button class="btn btn-outline-secondary" type="button" id="tanggalCalendarBtn"><i class="bi bi-calendar"></i></button>');

    $('#tanggalCalendarBtn').on('click', function(){
        $('#tanggalPendirianField').focus();
    });

    // Rupiah formatting for modal pokok
    $('#modalPokokField').on('input', function(){
        let value = $(this).val().replace(/[^0-9.]/g, '');
        if (value) {
            const numValue = parseFloat(value);
            $(this).val(numValue.toLocaleString('id-ID'));
        }
    }).on('blur', function(){
        const value = $(this).val();
        if (value && !isNaN(parseFloat(value.replace(/[^0-9.]/g, '')))) {
            const numValue = parseFloat(value.replace(/[^0-9.]/g, ''));
            $(this).val(numValue.toLocaleString('id-ID', {minimumFractionDigits: 0, maximumFractionDigits: 2}));
        }
    });

    $('#formRegisterKoperasi').on('submit', function(e){
        e.preventDefault();
        $('#alertKoperasi').addClass('d-none');

        // Validasi tambahan
        const nama = $('input[name="nama_koperasi"]').val().trim();
        const alamat = $('textarea[name="alamat_lengkap"]').val().trim();
        const kontak = $('input[name="kontak"]').val().trim();
        const npwp = $('input[name="npwp"]').val().trim();

        if (nama.length < 3) {
            $('#alertKoperasi').removeClass('d-none alert-info').addClass('alert-danger').text('Nama koperasi minimal 3 karakter.');
            return;
        }
        if (alamat.length < 10) {
            $('#alertKoperasi').removeClass('d-none alert-info').addClass('alert-danger').text('Alamat lengkap minimal 10 karakter.');
            return;
        }
        const phoneRegex = /^[0-9+\-\s()]+$/;
        const cleanKontak = kontak.replace(/[\-\s()]/g, '');
        if (!phoneRegex.test(kontak) || cleanKontak.length < 12 || cleanKontak.length > 15 || !cleanKontak.startsWith('62')) {
            $('#alertKoperasi').removeClass('d-none alert-info').addClass('alert-danger').text('Kontak harus berupa nomor telepon Indonesia yang valid (12-15 digit dimulai dengan 62).');
            return;
        }
        if (npwp && !/^[0-9]{15}$/.test(npwp)) {
            $('#alertKoperasi').removeClass('d-none alert-info').addClass('alert-danger').text('NPWP harus 15 digit angka.');
            return;
        }

        const $form = $('#formRegisterKoperasi');
        const data = $form.serialize();
        const $btn = $form.find('button[type="submit"]');
        const original = $btn.html();
        $btn.prop('disabled', true).html('<span class="spinner-border spinner-border-sm me-2"></span>Memproses...');
        $.ajax({
            url: $form.attr('action'),
            method: 'POST',
            data: data,
            dataType: 'json'
        }).done(function(res){
            if (res.success) {
                $('#alertKoperasi').removeClass('d-none alert-danger').addClass('alert-info').text(res.message || 'Koperasi berhasil didaftarkan. Silakan lanjut registrasi admin.');
                $form[0].reset();
            } else {
                $('#alertKoperasi').removeClass('d-none alert-info').addClass('alert-danger').text(res.message || 'Gagal menyimpan data');
            }
        }).fail(function(){
            $('#alertKoperasi').removeClass('d-none alert-info').addClass('alert-danger').text('Terjadi kesalahan koneksi.');
        }).always(function(){
            $btn.prop('disabled', false).html(original);
        });
    });
});
</script>
</body>
</html>
