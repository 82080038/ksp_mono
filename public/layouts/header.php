<?php require_once __DIR__ . '/../../app/helpers.php'; ?>

<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistem Informasi Koperasi Simpan Pinjam ksp_mono">
    <meta name="author" content="ksp_mono">
    <meta name="theme-color" content="#4361ee">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ksp_mono' : 'ksp_mono - Koperasi Simpan Pinjam'; ?></title>
    <style>
        .main-content {
            margin-bottom: 100px; /* Spacing to footer/session section */
        }
    </style>
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏦</text></svg>">
    <link rel="manifest" href="/ksp_mono/public/manifest.json">
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <!-- Flatpickr for datepicker (dd-mm-yyyy) -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/flatpickr/dist/flatpickr.min.css">
    <style>
        .flatpickr {
            position: relative;
        }
        .flatpickr input {
            padding-right: 40px;
        }
        .input-button {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            background: none;
            border: none;
            cursor: pointer;
            color: #6c757d;
            font-size: 14px;
        }
        .input-button:hover {
            color: #495057;
        }
    </style>
</head>
<body>
    <!-- jQuery Wait Helper -->
    <script>
        // Helper global: tunggu jQuery, retry, dan fallback load jika belum tersedia
        (function() {
            const MAX_RETRY = 10;
            const RETRY_DELAY = 150;
            const FALLBACK_SRC = 'https://code.jquery.com/jquery-3.7.1.min.js';
            let retryCount = 0;
            let fallbackInjected = false;

            window.waitForJqueryAndRun = function(callback) {
                if (typeof callback !== 'function') return;

                function attempt() {
                    if (window.jQuery) {
                        callback();
                        return;
                    }

                    if (!fallbackInjected) {
                        // coba injeksi ulang jQuery jika belum
                        const script = document.createElement('script');
                        script.src = FALLBACK_SRC;
                        script.async = true;
                        script.onerror = () => console.error('Gagal memuat fallback jQuery');
                        document.head.appendChild(script);
                        fallbackInjected = true;
                    }

                    if (retryCount < MAX_RETRY) {
                        retryCount++;
                        setTimeout(attempt, RETRY_DELAY);
                    } else {
                        console.error('jQuery tidak tersedia setelah retry; beberapa fitur mungkin tidak berjalan.');
                    }
                }

                attempt();
            };
        })();
    </script>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#4e73df; position:fixed; top:0; left:0; right:0; z-index:1040; height:60px;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/ksp_mono/"><?php echo htmlspecialchars($_SESSION['cooperatives'][0]['nama_koperasi'] ?? 'Koperasi'); ?></a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="topNavbar">
            </div>
            <ul class="navbar-nav ms-auto">
                <li class="nav-item dropdown no-arrow position-relative">
                    <a class="nav-link dropdown-toggle" href="#" id="topUserDropdown" role="button" onclick="document.getElementById('userDropdownMenu').classList.toggle('show')">
                        <span class="me-2 d-none d-lg-inline text-white small"><?php echo htmlspecialchars(truncate_text(format_name_title_case($_SESSION['user']['person']['nama_lengkap'] ?? $_SESSION['user']['username'] ?? 'User'), 20) . ' (' . ($_SESSION['user']['role'] ?? 'guest') . ')'); ?></span>
                        <img class="img-profile rounded-circle" src="https://ui-avatars.com/api/?name=<?php echo urlencode($_SESSION['user']['person']['nama_lengkap'] ?? $_SESSION['user']['username'] ?? 'User'); ?>&background=fff&color=4e73df" width="32" height="32" alt="avatar">
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end shadow" id="userDropdownMenu" aria-labelledby="topUserDropdown" style="z-index: 1050;">
                        <?php if (isset($_SESSION['accessible_modules']) && is_array($_SESSION['accessible_modules'])): ?>
                        <?php foreach ($_SESSION['accessible_modules'] as $module): ?>
                        <?php if ($module['show_in_navbar']): ?>
                        <li><a class="dropdown-item" href="?modul=<?php echo $module['nama']; ?>"><i class="bi <?php echo $module['ikon']; ?> me-2"></i><?php echo $module['nama_tampil']; ?></a></li>
                        <?php endif; ?>
                        <?php endforeach; ?>
                        <?php endif; ?>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/ksp_mono/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </nav>

    <!-- Global scripts: Flatpickr init and numeric input handler -->
    <script src="https://cdn.jsdelivr.net/npm/flatpickr"></script>
    <script>
        waitForJqueryAndRun(function() {
            // Define global init function for numeric and date inputs
            window.initNumericAndDate = function() {
                // Init flatpickr for all date-picker inputs with altInput
                if (window.flatpickr) {
                    $('.date-picker').each(function() {
                        const fp = flatpickr(this, {
                            dateFormat: 'Y-m-d',
                            altInput: true,
                            altFormat: 'd-m-Y',
                            allowInput: true
                        });

                        // Manual parse ddmmyyyy on alt input blur
                        const altInput = $(this).next('.flatpickr-input');
                        altInput.on('blur', function() {
                            const raw = ($(this).val() || '').trim();
                            if (raw === '') return;
                            // Jika user mengetik 8 digit mis. 01022026
                            const digits = raw.replace(/\D/g, '');
                            if (digits.length === 8) {
                                const dd = digits.slice(0,2);
                                const mm = digits.slice(2,4);
                                const yyyy = digits.slice(4);
                                const iso = `${yyyy}-${mm}-${dd}`;
                                if (fp && fp.setDate) {
                                    fp.setDate(iso, true, 'Y-m-d');
                                }
                            }
                        });
                    });
                }

                // Numeric inputs: clear default on focus, reset to 0 on blur if empty
                function formatRupiahDisplay(number) {
                    if (number === '' || isNaN(number)) return '';
                    const num = parseFloat(number);
                    return 'Rp ' + num.toLocaleString('id-ID');
                }

                // Initial display for existing numeric values
                $('.numeric-input, .money-input').each(function() {
                    const current = ($(this).val() || '').replace(/[^0-9.,-]/g, '').replace(/,/g,'.');
                    const num = parseFloat(current) || 0;
                    $(this).data('raw', num);
                    if ($(this).hasClass('money-input')) {
                        $(this).val(formatRupiahDisplay(num));
                    } else {
                        $(this).val(num);
                    }
                });

                $(document).on('focus', '.numeric-input, .money-input', function() {
                    const raw = $(this).data('raw');
                    let val = ($(this).val() || '').trim();
                    if ($(this).hasClass('money-input') && val === 'Rp 0') {
                        $(this).val('');
                    } else if ($(this).hasClass('numeric-input') && val === '0') {
                        $(this).val('');
                    } else if (raw !== undefined) {
                        $(this).val(raw);
                    } else {
                        val = val.replace(/[^0-9.,-]/g, '').replace(/,/g,'.');
                        $(this).val(val);
                    }
                });

                $(document).on('input', '.numeric-input, .money-input', function() {
                    let val = ($(this).val() || '').replace(/[^0-9.,-]/g, '').replace(/,/g,'.');
                    // store raw numeric string
                    $(this).data('raw', val);
                });

                $(document).on('blur', '.numeric-input, .money-input', function() {
                    let val = ($(this).data('raw') || '').trim();
                    if (val === '') val = '0';
                    // normalize to float string
                    const num = isNaN(parseFloat(val)) ? 0 : parseFloat(val);
                    $(this).data('raw', num);
                    if ($(this).hasClass('money-input')) {
                        $(this).val(formatRupiahDisplay(num));
                    } else {
                        $(this).val(num);
                    }
                });

                // Before submit, restore raw numeric values
                $(document).on('submit', 'form', function() {
                    $(this).find('.numeric-input, .money-input').each(function() {
                        const raw = $(this).data('raw');
                        if (raw !== undefined) {
                            $(this).val(raw);
                        }
                    });
                });
            };

            // Call init on page load
            initNumericAndDate();
        });
    </script>

    <!-- Theme switcher removed -->
