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
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏦</text></svg>">
    <link rel="manifest" href="/ksp_mono/public/manifest.json">
    
    <!-- Bootstrap 5 CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
</head>
<body>
    <nav class="navbar navbar-expand-lg navbar-dark" style="background-color:#4e73df; position:fixed; top:0; left:0; right:0; z-index:1040; height:60px;">
        <div class="container-fluid">
            <a class="navbar-brand fw-bold" href="/ksp_mono/">ksp_mono</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#topNavbar" aria-controls="topNavbar" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="topNavbar">
                <form class="d-none d-sm-inline-block form-inline ms-auto me-2 my-2 my-lg-0 navbar-search">
                    <div class="input-group">
                        <input type="text" class="form-control bg-light border-0 small" placeholder="Cari..." aria-label="Search" aria-describedby="btnNavbarSearch">
                        <button class="btn btn-primary" type="button" id="btnNavbarSearch">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </form>
                <ul class="navbar-nav ms-auto align-items-center">
                    <li class="nav-item dropdown no-arrow">
                        <a class="nav-link dropdown-toggle" href="#" id="topUserDropdown" role="button" data-bs-toggle="dropdown" aria-expanded="false">
                            <span class="me-2 d-none d-lg-inline text-white small"><?php echo htmlspecialchars($user['username'] ?? 'User'); ?></span>
                            <img class="img-profile rounded-circle" src="https://ui-avatars.com/api/?name=<?php echo urlencode($user['username'] ?? 'User'); ?>&background=4e73df&color=fff" width="32" height="32" alt="avatar">
                        </a>
                        <ul class="dropdown-menu dropdown-menu-end shadow" aria-labelledby="topUserDropdown">
                            <li><a class="dropdown-item" href="?modul=pengaturan"><i class="bi bi-gear me-2"></i>Pengaturan</a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item text-danger" href="/ksp_mono/logout.php"><i class="bi bi-box-arrow-right me-2"></i>Keluar</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
    </nav>

    <!-- jQuery -->
    <script src="https://code.jquery.com/jquery-3.6.0.min.js" integrity="sha256-/xUj+3OJU5yExlq6GSYGSHk7tPXikynS7ogEvDej/m4=" crossorigin="anonymous"></script>

    <!-- Theme switcher removed -->
