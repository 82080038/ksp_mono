<div class="sidebar">
    <div class="sidebar-header px-3 mb-3 d-flex align-items-center">
        <a href="/ksp_mono/" class="sidebar-brand d-flex align-items-center text-decoration-none">
            <div class="sidebar-brand-icon d-flex align-items-center justify-content-center me-2">
                <i class="bi bi-bank"></i>
            </div>
            <div class="sidebar-brand-text fw-bold">ksp_mono</div>
        </a>
        <button class="btn btn-link text-white d-lg-none ms-auto p-0 sidebar-toggle" aria-label="Toggle sidebar">
            <i class="bi bi-list fs-4"></i>
        </button>
    </div>

    <hr class="sidebar-divider my-2">

    <div class="sidebar-heading px-3 text-uppercase">Menu Utama</div>

    <div class="sidebar-menu">
        <ul class="nav flex-column">
            <li class="nav-item">
                <a href="/ksp_mono/" class="nav-link <?php echo (!isset($_GET['modul']) || $_GET['modul'] === 'dashboard') ? 'active' : ''; ?>">
                    <i class="bi bi-speedometer2 me-2"></i>
                    <span>Dashboard</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="?modul=anggota" class="nav-link <?php echo (isset($_GET['modul']) && $_GET['modul'] === 'anggota') ? 'active' : ''; ?>">
                    <i class="bi bi-people me-2"></i>
                    <span>Data Anggota</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="?modul=simpanan" class="nav-link <?php echo (isset($_GET['modul']) && $_GET['modul'] === 'simpanan') ? 'active' : ''; ?>">
                    <i class="bi bi-wallet2 me-2"></i>
                    <span>Simpanan</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="?modul=pinjaman" class="nav-link <?php echo (isset($_GET['modul']) && $_GET['modul'] === 'pinjaman') ? 'active' : ''; ?>">
                    <i class="bi bi-cash-coin me-2"></i>
                    <span>Pinjaman</span>
                </a>
            </li>
            <li class="nav-item">
                <a href="?modul=laporan" class="nav-link <?php echo (isset($_GET['modul']) && $_GET['modul'] === 'laporan') ? 'active' : ''; ?>">
                    <i class="bi bi-file-earmark-text me-2"></i>
                    <span>Laporan</span>
                </a>
            </li>

            <hr class="sidebar-divider my-3">
            <div class="sidebar-heading px-3 text-uppercase">Lainnya</div>

            <li class="nav-item">
                <a href="?modul=pengaturan" class="nav-link <?php echo (isset($_GET['modul']) && $_GET['modul'] === 'pengaturan') ? 'active' : ''; ?>">
                    <i class="bi bi-gear me-2"></i>
                    <span>Pengaturan</span>
                </a>
            </li>
        </ul>
    </div>
</div>

<!-- Overlay for mobile -->
<div class="sidebar-overlay"></div>

<!-- JavaScript for Sidebar Toggle -->
<script>
document.addEventListener('DOMContentLoaded', function() {
    const sidebar = document.querySelector('.sidebar');
    const sidebarToggle = document.querySelector('.sidebar-toggle');
    const sidebarOverlay = document.querySelector('.sidebar-overlay');
    
    // Toggle sidebar on mobile
    if (sidebarToggle) {
        sidebarToggle.addEventListener('click', function() {
            sidebar.classList.toggle('show');
            sidebarOverlay.classList.toggle('show');
        });
    }
    
    // Close sidebar when clicking overlay
    if (sidebarOverlay) {
        sidebarOverlay.addEventListener('click', function() {
            sidebar.classList.remove('show');
            this.classList.remove('show');
        });
    }
    
    // Close sidebar when clicking on a nav link (for mobile)
    const navLinks = document.querySelectorAll('.sidebar .nav-link');
    navLinks.forEach(link => {
        link.addEventListener('click', function() {
            if (window.innerWidth < 992) { // Only for mobile
                sidebar.classList.remove('show');
                sidebarOverlay.classList.remove('show');
            }
        });
    });
});
</script>
