        </div> <!-- End of .content-wrapper -->
        
        <!-- Footer -->
        <footer class="footer mt-auto py-3 bg-white border-top">
            <div class="container-fluid">
                <div class="row align-items-center">
                    <div class="col-md-6 text-center text-md-start">
                        <span class="text-muted">
                            &copy; <?php echo date('Y'); ?> ksp_mono. All rights reserved.
                        </span>
                    </div>
                    <div class="col-md-6 text-center text-md-end">
                        <span class="text-muted small">
                            v1.0.0 | 
                            <a href="#" class="text-decoration-none" data-bs-toggle="modal" data-bs-target="#aboutModal">
                                About
                            </a>
                        </span>
                    </div>
                </div>
            </div>
        </footer>
    </div> <!-- End of .main-content -->
    
    <!-- Loading Indicator -->
    <div id="loading-indicator" class="d-none">
        <div class="progress" style="height: 3px;">
            <div class="progress-bar progress-bar-striped progress-bar-animated" role="progressbar" style="width: 0%"></div>
        </div>
    </div>

    <!-- Toast Notification -->
    <div class="position-fixed bottom-0 end-0 p-3" style="z-index: 1090">
        <div id="toast" class="toast align-items-center text-white border-0" role="alert" aria-live="assertive" aria-atomic="true">
            <div class="d-flex">
                <div class="toast-body d-flex align-items-center">
                    <i class="bi me-2"></i>
                    <span class="toast-message"></span>
                </div>
                <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
            </div>
        </div>
    </div>
    
    <!-- About Modal -->
    <div class="modal fade" id="aboutModal" tabindex="-1" aria-labelledby="aboutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="aboutModalLabel">
                        <i class="bi bi-info-circle me-2"></i>About KSP-PEB
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-4">
                        <div class="d-flex align-items-center justify-content-center mb-3">
                            <i class="bi bi-bank fs-1 text-primary me-2"></i>
                            <span class="fs-3 fw-bold">KSP-PEB</span>
                        </div>
                        <p class="text-muted mb-0">Sistem Informasi Koperasi Simpan Pinjam</p>
                        <p class="text-muted">Version 1.0.0</p>
                    </div>
                    <div class="border-top pt-3">
                        <h6 class="mb-3">Powered by:</h6>
                        <div class="d-flex flex-wrap gap-3 justify-content-center">
                            <span class="badge bg-primary bg-opacity-10 text-primary p-2">PHP 8.1+</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary p-2">MySQL 8.0+</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary p-2">Bootstrap 5.3</span>
                            <span class="badge bg-primary bg-opacity-10 text-primary p-2">jQuery 3.7</span>
                        </div>
                    </div>
                </div>
                <div class="modal-footer justify-content-center border-top-0">
                    <button type="button" class="btn btn-primary" data-bs-dismiss="modal">Tutup</button>
                </div>
            </div>
        </div>
    </div>

    <!-- Logout Confirmation Modal -->
    <div class="modal fade" id="logoutModal" tabindex="-1" aria-labelledby="logoutModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="logoutModalLabel">
                        <i class="bi bi-exclamation-triangle text-warning me-2"></i>Konfirmasi Logout
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <p class="mb-0">Apakah Anda yakin ingin keluar dari sistem?</p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle me-1"></i>Batal
                    </button>
                    <a href="/ksp_mono/logout.php" class="btn btn-danger">
                        <i class="bi bi-box-arrow-right me-1"></i>Ya, Logout
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- JavaScript -->
    <script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11.7.5/dist/sweetalert2.all.min.js"></script>

    <!-- Custom JavaScript -->
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

        // Wait for the DOM to be fully loaded
        document.addEventListener('DOMContentLoaded', function() {
            waitForJqueryAndRun(function() {
                // Initialize components
                initSidebar();
                initTooltips();
                initPopovers();
                initForms();
                initToasts();
                initAjaxForms();
                initNavbar();

                // Add active class to current nav item
                highlightActiveNav();
            });
        });
        
        // Global function for navigation highlighting
        function highlightActiveNav() {
            // Function to highlight active navigation - already handled in PHP
        }
        
        // Initialize sidebar functionality
        function initSidebar() {
            const sidebar = document.querySelector('.sidebar');
            const sidebarToggle = document.querySelector('.sidebar-toggle');
            const sidebarOverlay = document.querySelector('.sidebar-overlay');
            const sidebarCollapse = document.querySelector('.sidebar-collapse');
            
            // Toggle sidebar on mobile
            if (sidebarToggle) {
                sidebarToggle.addEventListener('click', function(e) {
                    e.preventDefault();
                    document.body.classList.toggle('sidebar-collapsed');
                    sidebar.classList.toggle('show');
                    sidebarOverlay.classList.toggle('show');
                });
            }
            
            // Close sidebar when clicking overlay
            if (sidebarOverlay) {
                sidebarOverlay.addEventListener('click', function() {
                    document.body.classList.add('sidebar-collapsed');
                    sidebar.classList.remove('show');
                    this.classList.remove('show');
                });
            }
            
            // Toggle sidebar submenus
            document.querySelectorAll('.sidebar-nav .nav-link').forEach(link => {
                if (link.nextElementSibling && link.nextElementSibling.classList.contains('submenu')) {
                    link.addEventListener('click', function(e) {
                        e.preventDefault();
                        const submenu = this.nextElementSibling;
                        const parent = this.parentElement;
                        
                        // Close other open submenus at the same level
                        if (parent.classList.contains('has-submenu')) {
                            document.querySelectorAll('.has-submenu').forEach(item => {
                                if (item !== parent) {
                                    item.classList.remove('show');
                                    const otherSubmenu = item.querySelector('.submenu');
                                    if (otherSubmenu) {
                                        otherSubmenu.style.maxHeight = '0';
                                    }
                                }
                            });
                        }
                        
                        // Toggle current submenu
                        parent.classList.toggle('show');
                        if (submenu.style.maxHeight) {
                            submenu.style.maxHeight = null;
                        } else {
                            submenu.style.maxHeight = submenu.scrollHeight + 'px';
                        }
                    });
                }
            });
        }

        // Navbar: toggler & dropdown (dengan fallback bila Bootstrap JS tidak aktif)
        function initNavbar() {
            const toggler = document.querySelector('.navbar-toggler');
            const collapseEl = document.getElementById('topNavbar');
            const dropdownToggles = document.querySelectorAll('[data-bs-toggle="dropdown"]');
            if (!toggler || !collapseEl) return;

            const hasBootstrap = typeof bootstrap !== 'undefined';
            const collapseInstance = hasBootstrap && bootstrap.Collapse
                ? new bootstrap.Collapse(collapseEl, { toggle: false })
                : null;

            // Toggle collapse
            toggler.addEventListener('click', (e) => {
                e.preventDefault();
                if (collapseInstance) {
                    collapseInstance.toggle();
                } else {
                    collapseEl.classList.toggle('show');
                }
            });

            // Auto-close collapse ketika klik nav-link (kecuali dropdown toggle)
            document.querySelectorAll('.navbar-nav .nav-link:not(.dropdown-toggle)').forEach(link => {
                link.addEventListener('click', () => {
                    if (window.innerWidth < 992) {
                        if (collapseInstance) {
                            collapseInstance.hide();
                        } else {
                            collapseEl.classList.remove('show');
                        }
                    }
                });
            });

            // Inisialisasi dropdown
            dropdownToggles.forEach(el => {
                if (hasBootstrap && bootstrap.Dropdown) {
                    new bootstrap.Dropdown(el);
                } else {
                    // fallback sederhana: toggle class show pada parent .dropdown
                    el.addEventListener('click', (ev) => {
                        ev.preventDefault();
                        const parent = el.closest('.dropdown');
                        parent?.classList.toggle('show');
                        const menu = parent?.querySelector('.dropdown-menu');
                        menu?.classList.toggle('show');
                    });
                }
            });
        }
        
        // Initialize tooltips
        function initTooltips() {
            const tooltipTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
            tooltipTriggerList.map(tooltipTriggerEl => new bootstrap.Tooltip(tooltipTriggerEl));
        }
        
        // Initialize popovers
        function initPopovers() {
            const popoverTriggerList = [].slice.call(document.querySelectorAll('[data-bs-toggle="popover"]'));
            popoverTriggerList.map(popoverTriggerEl => new bootstrap.Popover(popoverTriggerEl));
        }
        
        // Initialize form validation
        function initForms() {
            // Add custom validation for file inputs
            const fileInputs = document.querySelectorAll('input[type="file"]');
            fileInputs.forEach(input => {
                input.addEventListener('change', function() {
                    const fileName = this.files[0]?.name || 'Pilih file...';
                    const label = this.nextElementSibling;
                    if (label && label.classList.contains('custom-file-label')) {
                        label.textContent = fileName;
                    }
                });
            });
            
            // Initialize Bootstrap validation
            const forms = document.querySelectorAll('.needs-validation');
            Array.from(forms).forEach(form => {
                form.addEventListener('submit', event => {
                    if (!form.checkValidity()) {
                        event.preventDefault();
                        event.stopPropagation();
                    }
                    form.classList.add('was-validated');
                }, false);
            });
        }
        
        // Initialize toasts
        function initToasts() {
            const toastElList = [].slice.call(document.querySelectorAll('.toast:not(.no-auto-init)'));
            toastElList.map(toastEl => new bootstrap.Toast(toastEl, { autohide: true }));
        }
        
        // Initialize AJAX forms
        function initAjaxForms() {
            document.querySelectorAll('form.ajax-form').forEach(form => {
                form.addEventListener('submit', handleAjaxFormSubmit);
            });
        }
        
        // Handle AJAX form submission
        async function handleAjaxFormSubmit(e) {
            e.preventDefault();
            
            const form = e.target;
            const formData = new FormData(form);
            const submitBtn = form.querySelector('[type="submit"]');
            const originalBtnText = submitBtn?.innerHTML;
            
            try {
                // Show loading state
                if (submitBtn) {
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = '<span class="spinner-border spinner-border-sm me-2" role="status" aria-hidden="true"></span>Memproses...';
                }
                
                showLoading(true);
                
                const response = await fetch(form.action, {
                    method: form.method,
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                    }
                });
                
                const data = await response.json();
                
                if (data.success) {
                    showToast('success', data.message || 'Operasi berhasil');
                    
                    // Handle redirect if specified
                    if (data.redirect) {
                        setTimeout(() => {
                            window.location.href = data.redirect;
                        }, 1500);
                    }
                    
                    // Trigger success event
                    form.dispatchEvent(new CustomEvent('ajax:success', { detail: { data, form, response } }));
                } else {
                    showToast('danger', data.message || 'Terjadi kesalahan');
                    
                    // Show validation errors if any
                    if (data.errors) {
                        Object.entries(data.errors).forEach(([field, messages]) => {
                            const input = form.querySelector(`[name="${field}"]`);
                            if (input) {
                                const feedback = input.nextElementSibling;
                                if (feedback && feedback.classList.contains('invalid-feedback')) {
                                    feedback.textContent = Array.isArray(messages) ? messages[0] : messages;
                                    input.classList.add('is-invalid');
                                }
                            }
                        });
                    }
                    
                    // Trigger error event
                    form.dispatchEvent(new CustomEvent('ajax:error', { detail: { data, form, response } }));
                }
            } catch (error) {
                console.error('Form submission error:', error);
                showToast('danger', 'Terjadi kesalahan koneksi');
                
                // Trigger error event
                form.dispatchEvent(new CustomEvent('ajax:error', { 
                    detail: { 
                        error: error,
                        form: form 
                    } 
                }));
            } finally {
                // Reset button state
                if (submitBtn) {
                    submitBtn.disabled = false;
                    submitBtn.innerHTML = originalBtnText;
                }
                
                showLoading(false);
            }
        }
        
        // Show toast notification
        function showToast(type, message, options = {}) {
            const { duration = 3000, position = 'bottom-end' } = options;
            const toastContainer = document.querySelector('.toast-container');
            const toastId = 'toast-' + Math.random().toString(36).substr(2, 9);
            
            // Create toast element
            const toastEl = document.createElement('div');
            toastEl.id = toastId;
            toastEl.className = `toast align-items-center text-white bg-${type} border-0`;
            toastEl.role = 'alert';
            toastEl.ariaLive = 'assertive';
            toastEl.ariaAtomic = 'true';
            
            // Set toast content
            toastEl.innerHTML = `
                <div class="d-flex">
                    <div class="toast-body d-flex align-items-center">
                        <i class="bi ${getToastIcon(type)} me-2"></i>
                        <span class="toast-message">${message}</span>
                    </div>
                    <button type="button" class="btn-close btn-close-white me-2 m-auto" data-bs-dismiss="toast" aria-label="Close"></button>
                </div>
            `;
            
            // Add to container
            if (!toastContainer) {
                const container = document.createElement('div');
                container.className = `toast-container position-fixed p-3 ${position}`;
                container.style.zIndex = '1090';
                document.body.appendChild(container);
                container.appendChild(toastEl);
            } else {
                toastContainer.appendChild(toastEl);
            }
            
            // Initialize and show toast
            const toast = new bootstrap.Toast(toastEl, { autohide: duration > 0 });
            toast.show();
            
            // Auto remove after duration
            if (duration > 0) {
                setTimeout(() => {
                    toastEl.remove();
                }, duration + 500);
            }
            
            return toast;
        }
        
        // Get icon for toast based on type
        function getToastIcon(type) {
            const icons = {
                'success': 'bi-check-circle-fill',
                'danger': 'bi-x-circle-fill',
                'warning': 'bi-exclamation-triangle-fill',
                'info': 'bi-info-circle-fill',
                'primary': 'bi-info-circle-fill',
                'secondary': 'bi-info-circle-fill',
                'light': 'bi-info-circle-fill',
                'dark': 'bi-info-circle-fill'
            };
            
            return icons[type] || 'bi-info-circle-fill';
        }
        
        // Format Rupiah
        function formatRupiah(amount, prefix = 'Rp ', decimal = false) {
            if (amount === null || amount === undefined || amount === '') return '';

            // Bersihkan karakter non-digit lalu konversi ke angka
            const numeric = Number(amount.toString().replace(/[^\d]/g, ''));
            if (Number.isNaN(numeric)) return '';

            const formatter = new Intl.NumberFormat('id-ID', {
                minimumFractionDigits: decimal ? 2 : 0,
                maximumFractionDigits: decimal ? 2 : 0
            });

            return prefix + formatter.format(numeric);
        }
    </script>
    
    <!-- Debug: Session Data Display -->
    <?php if (isset($_SESSION) && !empty($_SESSION)): ?>
    <div style="position: fixed; bottom: 0; left: 0; right: 0; background: #f8f9fa; border-top: 1px solid #dee2e6; padding: 10px; font-size: 12px; max-height: 200px; overflow-y: auto; z-index: 9999;">
        <strong>Session Data (JSON):</strong>
        <pre><?php echo json_encode($_SESSION, JSON_PRETTY_PRINT | JSON_UNESCAPED_UNICODE); ?></pre>
    </div>
    <?php endif; ?>
</body>
</html>
