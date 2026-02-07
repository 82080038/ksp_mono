/**
 * ksp_mono Main JavaScript
 * Berkas utama untuk fungsionalitas umum aplikasi
 */

document.addEventListener('DOMContentLoaded', function() {
    // Inisialisasi tooltips
    var tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    var tooltipList = tooltipTriggerList.map(function (tooltipTriggerEl) {
        return new bootstrap.Tooltip(tooltipTriggerEl);
    });

    // Inisialisasi popovers
    var popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
    var popoverList = popoverTriggerList.map(function (popoverTriggerEl) {
        return new bootstrap.Popover(popoverTriggerEl);
    });

    // Sidebar toggle untuk mobile
    const sidebarToggler = document.querySelector('.sidebar-toggler');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    const sidebar = document.querySelector('.sidebar');
    
    if (sidebarToggler) {
        sidebarToggler.addEventListener('click', function(e) {
            e.preventDefault();
            document.body.classList.toggle('sidebar-collapsed');
            sidebar.classList.toggle('active');
            sidebarOverlay.classList.toggle('show');
        });
    }

    // Close sidebar ketika overlay diklik
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            document.body.classList.remove('sidebar-collapsed');
            sidebar.classList.remove('active');
            this.classList.remove('show');
        });
    }

    // Handle active state pada menu sidebar
    const currentPath = window.location.pathname;
    const sidebarLinks = document.querySelectorAll('.sidebar-nav .nav-link');
    
    sidebarLinks.forEach(link => {
        if (link.getAttribute('href') === currentPath) {
            link.classList.add('active');
            // Expand parent menu jika ada
            const parentMenu = link.closest('.nav-item.has-submenu');
            if (parentMenu) {
                parentMenu.classList.add('show');
                const toggle = parentMenu.querySelector('.submenu-toggle');
                if (toggle) {
                    toggle.setAttribute('aria-expanded', 'true');
                    toggle.classList.remove('collapsed');
                }
            }
        }
    });

    // Toggle submenu
    const submenuToggles = document.querySelectorAll('.submenu-toggle');
    submenuToggles.forEach(toggle => {
        toggle.addEventListener('click', function(e) {
            e.preventDefault();
            const parent = this.closest('.nav-item.has-submenu');
            parent.classList.toggle('show');
            
            const isExpanded = this.getAttribute('aria-expanded') === 'true';
            this.setAttribute('aria-expanded', !isExpanded);
            this.classList.toggle('collapsed');
        });
    });

    // Handle form submission dengan AJAX
    const ajaxForms = document.querySelectorAll('form.ajax-form');
    ajaxForms.forEach(form => {
        form.addEventListener('submit', function(e) {
            e.preventDefault();
            
            const formData = new FormData(this);
            const submitBtn = this.querySelector('[type="submit"]');
            const originalBtnText = submitBtn.innerHTML;
            
            // Tampilkan loading state
            submitBtn.disabled = true;
            submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm" role="status" aria-hidden="true"></span> Memproses...';
            
            fetch(this.action, {
                method: this.method,
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Tampilkan pesan sukses
                    Swal.fire({
                        icon: 'success',
                        title: 'Berhasil',
                        text: data.message || 'Operasi berhasil dilakukan',
                        showConfirmButton: false,
                        timer: 2000
                    }).then(() => {
                        // Redirect jika ada URL redirect
                        if (data.redirect) {
                            window.location.href = data.redirect;
                        }
                    });
                } else {
                    // Tampilkan pesan error
                    Swal.fire({
                        icon: 'error',
                        title: 'Gagal',
                        text: data.message || 'Terjadi kesalahan. Silakan coba lagi.'
                    });
                }
            })
            .catch(error => {
                console.error('Error:', error);
                Swal.fire({
                    icon: 'error',
                    title: 'Error',
                    text: 'Terjadi kesalahan koneksi. Silakan coba lagi.'
                });
            })
            .finally(() => {
                // Kembalikan state tombol
                submitBtn.disabled = false;
                submitBtn.innerHTML = originalBtnText;
            });
        });
    });

    // Fungsi untuk menampilkan toast notifikasi
    window.showToast = function(options) {
        const defaultOptions = {
            title: '',
            message: '',
            type: 'info', // 'success', 'error', 'warning', 'info'
            duration: 3000,
            position: 'top-right' // 'top-right', 'top-left', 'bottom-right', 'bottom-left'
        };
        
        const toastOptions = { ...defaultOptions, ...options };
        
        const toast = document.createElement('div');
        toast.className = `toast align-items-center text-white bg-${toastOptions.type} border-0`;
        toast.setAttribute('role', 'alert');
        toast.setAttribute('aria-live', 'assertive');
        toast.setAttribute('aria-atomic', 'true');
        
        toast.innerHTML = `
            <div class="d-flex">
                <div class="toast-body">
                    ${toastOptions.title ? `<strong>${toastOptions.title}</strong><br>` : ''}
                    ${toastOptions.message}
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        `;
        
        const toastContainer = document.querySelector(`.toast-container-${toastOptions.position}`);
        if (!toastContainer) {
            const newContainer = document.createElement('div');
            newContainer.className = `toast-container position-fixed p-3 toast-container-${toastOptions.position}`;
            newContainer.style.zIndex = '1090';
            
            switch(toastOptions.position) {
                case 'top-right':
                    newContainer.style.top = '1rem';
                    newContainer.style.right = '1rem';
                    break;
                case 'top-left':
                    newContainer.style.top = '1rem';
                    newContainer.style.left = '1rem';
                    break;
                case 'bottom-right':
                    newContainer.style.bottom = '1rem';
                    newContainer.style.right = '1rem';
                    break;
                case 'bottom-left':
                    newContainer.style.bottom = '1rem';
                    newContainer.style.left = '1rem';
                    break;
            }
            
            document.body.appendChild(newContainer);
            newContainer.appendChild(toast);
        } else {
            toastContainer.appendChild(toast);
        }
        
        const bsToast = new bootstrap.Toast(toast, { autohide: true, delay: toastOptions.duration });
        bsToast.show();
        
        // Hapus toast setelah selesai
        toast.addEventListener('hidden.bs.toast', function() {
            toast.remove();
            // Hapus container jika tidak ada toast lagi
            const container = document.querySelector(`.toast-container-${toastOptions.position}`);
            if (container && container.children.length === 0) {
                container.remove();
            }
        });
    };

    // Contoh penggunaan:
    // showToast({
    //     title: 'Berhasil',
    //     message: 'Data berhasil disimpan',
    //     type: 'success',
    //     duration: 3000
    // });

    // Helper for phone number fields
    function initPhoneField(selector) {
        $(selector).on('input', function() {
            let value = $(this).val().replace(/[^0-9]/g, '');

            // Format with dashes every 4 digits for Indonesian phone numbers
            let formatted = '';
            for (let i = 0; i < value.length; i++) {
                if (i > 0 && i % 4 === 0) {
                    formatted += '-';
                }
                formatted += value[i];
            }

            $(this).val(formatted);

            // Validate length (Indonesian phone numbers are typically 10-13 digits)
            const cleanValue = value.replace(/[^0-9]/g, '');
            if (cleanValue.length >= 10 && cleanValue.length <= 13) {
                $(this).removeClass('is-invalid').addClass('is-valid');
            } else if (cleanValue.length > 0) {
                $(this).removeClass('is-valid').addClass('is-invalid');
            } else {
                $(this).removeClass('is-valid is-invalid');
            }
        });
    }

    // Helper for badan_hukum field
    function initBadanHukumField(selector) {
        $(selector).on('blur', function() {
            const value = $(this).val().replace(/[^0-9]/g, '');

            // Server-side validation also requires exactly 12 digits (see validate_badan_hukum_koperasi in helpers.php)
            if (value && value.length !== 12) {
                $(this).addClass('is-invalid');
                console.warn('Badan Hukum validation failed: must be 12 digits, got', value.length);
            } else if (value) {
                $(this).removeClass('is-invalid').addClass('is-valid');
                console.log('Badan Hukum validation passed:', value);
            } else {
                $(this).removeClass('is-invalid is-valid');
            }
        });
    }

    // Helper for date fields
    function initDateField(selector) {
        $(selector).inputmask('99-99-9999', {
            placeholder: 'DD-MM-YYYY',
            showMaskOnHover: false,
            onBeforeMask: function(value, opts) {
                if (value) {
                    const parts = value.split('-');
                    if (parts.length === 3) {
                        const day = parseInt(parts[0]);
                        const month = parseInt(parts[1]);
                        if (day > 31 || month > 12) {
                            return ''; // Clear invalid dates
                        }
                    }
                }
                return value;
            }
        }).on('blur', function() {
            const dateValue = $(this).val();
            if (dateValue && dateValue.length === 10) {
                $.post('/ksp_mono/app/helpers.php?action=validate_date',
                    { date: dateValue },
                    function(response) {
                        if (response.valid) {
                            $(selector).removeClass('is-invalid').addClass('is-valid');
                        } else {
                            $(selector).removeClass('is-valid').addClass('is-invalid');
                        }
                    },
                    'json'
                );
            }
        });
    }

    // Helper for currency fields
    function initCurrencyField(selector) {
        $(selector)
            .addClass('uang')
            .on('focus', function() {
                const formatted = $(this).val();
                $.post('/ksp_mono/app/helpers.php?action=parse_rupiah',
                    { value: formatted },
                    function(response) {
                        if (response.success) {
                            $(selector).data('raw-value', response.value);
                            $(selector).val(response.value);
                        }
                    }.bind(this),
                    'json'
                );
            })
            .on('blur', function() {
                const rawValue = $(this).val();
                $.post('/ksp_mono/app/helpers.php?action=format_rupiah',
                    { value: rawValue },
                    function(response) {
                        if (response.success) {
                            $(selector).val(response.formatted);
                        }
                    }.bind(this),
                    'json'
                );
            });
    }

    // Helper for NPWP field
    function initNpwpField(selector) {
        $(selector)
            .inputmask('99.999.999.9-999.999', {
                placeholder: 'XX.XXX.XXX.X-XXX.XXX',
                showMaskOnHover: false,
                clearIncomplete: true
            })
            .on('blur', function() {
                const npwp = $(this).val();
                if (npwp) {
                    $.post('/ksp_mono/app/helpers.php?action=validate_npwp',
                        { npwp },
                        function(response) {
                            if (response.valid) {
                                $(selector).removeClass('is-invalid').addClass('is-valid');
                                console.log('NPWP validation passed:', npwp);
                            } else {
                                $(selector).removeClass('is-valid').addClass('is-invalid');
                                console.warn('NPWP validation failed:', response.message || '');
                            }
                        }.bind(this),
                        'json'
                    );
                } else {
                    $(selector).removeClass('is-valid is-invalid');
                }
            });
    }

    // Initialize form field helpers globally
    $(document).ready(function() {
        console.log('Initializing global form field helpers...');
        
        $('.phone-field').each(function() {
            console.log('Initializing phone field:', $(this).attr('id'));
            initPhoneField('#' + $(this).attr('id'));
        });

        $('.badan-hukum-field').each(function() {
            console.log('Initializing badan hukum field:', $(this).attr('name'));
            initBadanHukumField('[name="' + $(this).attr('name') + '"]');
        });
        
        $('.date-field').each(function() {
            console.log('Initializing date field:', $(this).attr('id'));
            initDateField('#' + $(this).attr('id'));
        });
        
        $('.currency-field').each(function() {
            console.log('Initializing currency field:', $(this).attr('id'));
            initCurrencyField('#' + $(this).attr('id'));
        });
        
        $('.npwp-field').each(function() {
            console.log('Initializing NPWP field:', $(this).attr('name'));
            initNpwpField('[name="' + $(this).attr('name') + '"]');
        });

        // Auto-capitalize road and house number inputs
        $('#namaJalanInput, #nomorRumahInput').on('input', function() {
            let value = $(this).val();
            value = value.replace(/\b\w/g, function(char) {
                return char.toUpperCase();
            });
            $(this).val(value);
        });
    });

    // Fungsi untuk format mata uang Rupiah
    function formatRupiah(angka, prefix = 'Rp ') {
        if (angka === null || angka === undefined) return '';
        
        // Hapus semua karakter non-digit
        const numberString = angka.toString().replace(/[^\d]/g, '');
        
        // Jika string kosong, kembalikan string kosong
        if (numberString === '') return '';
        
        // Konversi ke number dan format
        const number = parseInt(numberString);
        return prefix + number.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
    }

    // Fungsi untuk menghapus format Rupiah
    function unformatRupiah(rupiah) {
        if (!rupiah) return 0;
        return parseInt(rupiah.replace(/[^\d]/g, ''));
    }

    // Auto format input uang
    document.addEventListener('input', function(e) {
        if (e.target && e.target.classList.contains('uang')) {
            const value = e.target.value;
            const cursorPosition = e.target.selectionStart;
            const formattedValue = formatRupiah(value);
            
            if (formattedValue !== value) {
                e.target.value = formattedValue;
                
                // Atur posisi kursor
                const diff = formattedValue.length - value.length;
                e.target.setSelectionRange(cursorPosition + diff, cursorPosition + diff);
            }
        }
    });

    // Fungsi untuk menampilkan konfirmasi sebelum menghapus
    function confirmDelete(e) {
        e.preventDefault();
        const form = e.target.closest('form');
        
        Swal.fire({
            title: 'Apakah Anda yakin?',
            text: "Data yang dihapus tidak dapat dikembalikan!",
            icon: 'warning',
            showCancelButton: true,
            confirmButtonColor: '#3085d6',
            cancelButtonColor: '#d33',
            confirmButtonText: 'Ya, hapus!',
            cancelButtonText: 'Batal'
        }).then((result) => {
            if (result.isConfirmed) {
                if (form) {
                    form.submit();
                } else if (e.target.href) {
                    window.location.href = e.target.href;
                }
            }
        });
    }

    // Inisialisasi event listener untuk tombol hapus
    document.addEventListener('click', function(e) {
        if (e.target && (e.target.classList.contains('btn-delete') || e.target.closest('.btn-delete'))) {
            e.preventDefault();
            const target = e.target.classList.contains('btn-delete') ? e.target : e.target.closest('.btn-delete');
            confirmDelete(target);
        }
    });

    // Fungsi untuk memuat data dengan AJAX
    function loadData(url, container, options = {}) {
        const defaultOptions = {
            method: 'GET',
            data: {},
            beforeSend: null,
            success: null,
            error: null,
            complete: null
        };
        
        const { method, data, beforeSend, success, error, complete } = { ...defaultOptions, ...options };
        
        fetch(url, {
            method: method,
            headers: {
                'X-Requested-With': 'XMLHttpRequest',
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: new URLSearchParams(data).toString()
        })
        .then(response => response.json())
        .then(data => {
            if (container) {
                container.innerHTML = data.html || data;
            }
            
            if (typeof success === 'function') {
                success(data);
            }
        })
        .catch(err => {
            console.error('Error:', err);
            
            if (typeof error === 'function') {
                error(err);
            } else {
                showToast({
                    title: 'Error',
                    message: 'Terjadi kesalahan saat memuat data',
                    type: 'error'
                });
            }
        })
        .finally(() => {
            if (typeof complete === 'function') {
                complete();
            }
        });
    }
});
