<nav class="navbar navbar-expand-lg navbar-dark bg-primary">
    <div class="container-fluid">
        <a class="navbar-brand" href="/ksp_mono/public/">ksp_mono</a>
        <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarNav" aria-controls="navbarNav" aria-expanded="false" aria-label="Toggle navigation">
            <span class="navbar-toggler-icon"></span>
        </button>
        <div class="collapse navbar-collapse" id="navbarNav">
            <ul class="navbar-nav me-auto mb-2 mb-lg-0">
                <li class="nav-item">
                    <a class="nav-link <?php echo (!isset($_GET['modul']) || $_GET['modul'] === 'dashboard') ? 'active' : ''; ?>" href="/ksp_mono/public/">
                        <i class="bi bi-speedometer2 me-1"></i> Dashboard
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['modul']) && $_GET['modul'] === 'anggota') ? 'active' : ''; ?>" href="?modul=anggota">
                        <i class="bi bi-people me-1"></i> Anggota
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['modul']) && $_GET['modul'] === 'simpanan') ? 'active' : ''; ?>" href="?modul=simpanan">
                        <i class="bi bi-wallet2 me-1"></i> Simpanan
                    </a>
                </li>
                <li class="nav-item">
                    <a class="nav-link <?php echo (isset($_GET['modul']) && $_GET['modul'] === 'pinjaman') ? 'active' : ''; ?>" href="?modul=pinjaman">
                        <i class="bi bi-cash-coin me-1"></i> Pinjaman
                    </a>
                </li>
            </ul>
            <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                <li class="nav-item dropdown">
                    <a class="nav-link dropdown-toggle" href="#" id="navbarDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                        <i class="bi bi-person-circle me-1"></i>
                        <?php echo htmlspecialchars($user['username'] ?? 'User'); ?>
                    </a>
                    <ul class="dropdown-menu dropdown-menu-end" aria-labelledby="navbarDropdown">
                        <li><a class="dropdown-item" href="#"><i class="bi bi-person me-2"></i> Profil</a></li>
                        <li><a class="dropdown-item" href="#"><i class="bi bi-gear me-2"></i> Pengaturan</a></li>
                        <li><hr class="dropdown-divider"></li>
                        <li><a class="dropdown-item text-danger" href="/ksp_mono/public/logout.php"><i class="bi bi-box-arrow-right me-2"></i> Logout</a></li>
                    </ul>
                </li>
            </ul>
        </div>
    </div>
</nav>
