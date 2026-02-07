<!DOCTYPE html>
<html lang="id" data-bs-theme="light">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0, shrink-to-fit=no">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <meta name="description" content="Sistem Informasi Koperasi Simpan Pinjam ksp_mono">
    <meta name="author" content="ksp_mono">
    <meta name="theme-color" content="#4361ee">
    
    <title><?php echo isset($pageTitle) ? htmlspecialchars($pageTitle) . ' - ksp_mono' : 'ksp_mono - Koperasi Simpan Pinjam'; ?></title>
    
    <!-- Favicon -->
    <link rel="icon" href="data:image/svg+xml,<svg xmlns='http://www.w3.org/2000/svg' viewBox='0 0 100 100'><text y='.9em' font-size='90'>🏦</text></svg>">
    <link rel="manifest" href="/ksp_mono/public/manifest.json">
    
    <!-- Bootstrap 5 CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    
    <!-- Bootstrap Icons -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.1/font/bootstrap-icons.css">
    
    <!-- Custom CSS -->
    <link rel="stylesheet" href="/ksp_mono/public/assets/css/style.css">
    
    <!-- Inline Critical CSS -->
    <style>
        :root {
            --bs-primary: #4361ee;
            --bs-primary-rgb: 67, 97, 238;
            --bs-primary-bg-subtle: #eef2ff;
            --bs-secondary: #3f37c9;
            --bs-success: #4cc9f0;
            --bs-info: #4895ef;
            --bs-warning: #f8961e;
            --bs-danger: #f72585;
            --bs-light: #f8f9fa;
            --bs-dark: #212529;
            --bs-body-font-family: 'Segoe UI', system-ui, -apple-system, sans-serif;
        }
        
        body {
            min-height: 100vh;
            display: flex;
            flex-direction: column;
            background-color: #f8fafc;
        }
        
        .content-wrapper {
            flex: 1;
            display: flex;
            flex-direction: column;
        }
        
        /* Loading indicator */
        #loading-indicator {
            position: fixed;
            top: 0;
            left: 0;
            width: 100%;
            height: 3px;
            z-index: 9999;
            display: none;
        }
        
        #loading-indicator .progress-bar {
            transition: width 0.3s ease;
        }
    </style>
    
    <style>
        :root {
            --primary-color: #4361ee;
            --secondary-color: #3f37c9;
            --success-color: #4cc9f0;
            --info-color: #4895ef;
            --warning-color: #f8961e;
            --danger-color: #f72585;
            --light-color: #f8f9fa;
            --dark-color: #212529;
            --sidebar-width: 250px;
            --topbar-height: 60px;
        }
        
        body {
            font-family: 'Segoe UI', Tahoma, Geneva, Verdana, sans-serif;
            background-color: #f5f7fb;
            overflow-x: hidden;
        }
        
        /* Main Content */
        .main-content {
            margin-left: var(--sidebar-width);
            padding: 1.5rem;
            min-height: 100vh;
            transition: all 0.3s ease;
        }
        
        .main-content.expanded {
            margin-left: 0;
        }
        
        /* Topbar */
        .topbar {
            position: fixed;
            top: 0;
            right: 0;
            left: var(--sidebar-width);
            height: var(--topbar-height);
            background: #fff;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
            z-index: 999;
            padding: 0 1.5rem;
            display: flex;
            align-items: center;
            justify-content: space-between;
            transition: all 0.3s ease;
        }
        
        .topbar.expanded {
            left: 0;
        }
        
        .topbar-left {
            display: flex;
            align-items: center;
        }
        
        .topbar-right {
            display: flex;
            align-items: center;
        }
        
        .user-dropdown {
            position: relative;
        }
        
        .user-dropdown-toggle {
            display: flex;
            align-items: center;
            background: none;
            border: none;
            color: #4a5568;
            cursor: pointer;
        }
        
        .user-avatar {
            width: 36px;
            height: 36px;
            border-radius: 50%;
            background-color: var(--primary-color);
            color: white;
            display: flex;
            align-items: center;
            justify-content: center;
            margin-right: 0.5rem;
            font-weight: 600;
            font-size: 0.875rem;
        }
        
        .user-name {
            margin-right: 0.5rem;
            font-weight: 500;
        }
        
        .dropdown-menu {
            min-width: 15rem;
            border: none;
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
            border-radius: 0.5rem;
            padding: 0.5rem 0;
            margin-top: 0.5rem;
        }
        
        .dropdown-header {
            padding: 0.5rem 1.5rem;
            font-size: 0.75rem;
            color: #718096;
            text-transform: uppercase;
            font-weight: 600;
        }
        
        .dropdown-item {
            padding: 0.5rem 1.5rem;
            color: #4a5568;
            display: flex;
            align-items: center;
            transition: all 0.2s ease;
        }
        
        .dropdown-item i {
            margin-right: 0.75rem;
            width: 20px;
            text-align: center;
        }
        
        .dropdown-item:hover {
            background-color: #f7fafc;
            color: var(--primary-color);
        }
        
        .dropdown-divider {
            margin: 0.5rem 0;
            border-top: 1px solid #e2e8f0;
        }
        
        /* Card Styles */
        .card {
            border: none;
            border-radius: 0.5rem;
            box-shadow: 0 0.125rem 0.25rem rgba(0, 0, 0, 0.05);
            margin-bottom: 1.5rem;
            transition: all 0.3s ease;
        }
        
        .card:hover {
            box-shadow: 0 0.5rem 1rem rgba(0, 0, 0, 0.1);
        }
        
        .card-header {
            background-color: #fff;
            border-bottom: 1px solid rgba(0, 0, 0, 0.05);
            padding: 1.25rem 1.5rem;
            border-radius: 0.5rem 0.5rem 0 0 !important;
        }
        
        .card-title {
            margin-bottom: 0;
            font-weight: 600;
            color: #2d3748;
        }
        
        .card-body {
            padding: 1.5rem;
        }
        
        /* Table Styles */
        .table {
            margin-bottom: 0;
        }
        
        .table thead th {
            background-color: #f8f9fa;
            border-bottom-width: 1px;
            color: #4a5568;
            font-weight: 600;
            text-transform: uppercase;
            font-size: 0.75rem;
            letter-spacing: 0.05em;
        }
        
        .table > :not(:first-child) {
            border-top: none;
        }
        
        /* Button Styles */
        .btn {
            font-weight: 500;
            padding: 0.5rem 1rem;
            border-radius: 0.375rem;
            transition: all 0.2s ease;
        }
        
        .btn-sm {
            padding: 0.25rem 0.5rem;
            font-size: 0.875rem;
        }
        
        .btn-primary {
            background-color: var(--primary-color);
            border-color: var(--primary-color);
        }
        
        .btn-primary:hover {
            background-color: var(--secondary-color);
            border-color: var(--secondary-color);
        }
        
        /* Form Styles */
        .form-control, .form-select {
            border-radius: 0.375rem;
            padding: 0.5rem 0.75rem;
            border: 1px solid #e2e8f0;
            transition: all 0.2s ease;
        }
        
        .form-control:focus, .form-select:focus {
            border-color: var(--primary-color);
            box-shadow: 0 0 0 0.25rem rgba(67, 97, 238, 0.25);
        }
        
        /* Responsive Adjustments */
        @media (max-width: 991.98px) {
            .sidebar {
                margin-left: calc(-1 * var(--sidebar-width));
            }
            
            .sidebar.show {
                margin-left: 0;
            }
            
            .main-content {
                margin-left: 0;
            }
            
            .topbar {
                left: 0;
            }
            
            .sidebar-overlay {
                position: fixed;
                top: 0;
                left: 0;
                right: 0;
                bottom: 0;
                background-color: rgba(0, 0, 0, 0.5);
                z-index: 999;
                opacity: 0;
                visibility: hidden;
                transition: all 0.3s ease;
            }
            
            .sidebar-overlay.show {
                opacity: 1;
                visibility: visible;
            }
        }
        
        /* Animation */
        @keyframes fadeIn {
            from { opacity: 0; transform: translateY(10px); }
            to { opacity: 1; transform: translateY(0); }
        }
        
        .fade-in {
            animation: fadeIn 0.3s ease forwards;
        }
    </style>
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
