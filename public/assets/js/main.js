/**
 * KSP-PEB Main JavaScript
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
