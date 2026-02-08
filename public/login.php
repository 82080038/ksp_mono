<?php
// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/bootstrap.php';

$auth = new Auth();

// Jika sudah login, redirect ke halaman yang diminta atau dashboard
if ($auth->check()) {
    $redirectUrl = '/ksp_mono/';
    
    // Cek apakah ada parameter redirect
    if (isset($_GET['redirect'])) {
        $redirectUrl = filter_var($_GET['redirect'], FILTER_SANITIZE_URL);
        
        // Pastikan URL redirect masih dalam domain yang sama untuk keamanan
        $parsedUrl = parse_url($redirectUrl);
        if (!isset($parsedUrl['host']) || $parsedUrl['host'] === $_SERVER['HTTP_HOST']) {
            $redirectUrl = $parsedUrl['path'] ?? '/';
            if (isset($parsedUrl['query'])) {
                $redirectUrl .= '?' . $parsedUrl['query'];
            }
        } else {
            $redirectUrl = '/ksp_mono/';
        }
    }
    
    header('Location: ' . $redirectUrl);
    exit;
}

// Simpan URL redirect untuk digunakan di form
$redirectUrl = isset($_GET['redirect']) ? htmlspecialchars($_GET['redirect']) : '';
?>
<!doctype html>
<html lang="id">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Login - ksp_mono</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.css">
    <style>
        body {
            min-height: 100vh;
            background: radial-gradient(circle at 10% 20%, #d8e7ff 0, #f6f9ff 25%),
                        radial-gradient(circle at 90% 10%, #e6f7ff 0, #f6f9ff 30%),
                        linear-gradient(120deg, #eef2f7 0, #f9fbff 100%);
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 24px;
        }
        .auth-card {
            max-width: 420px;
            width: 100%;
            border: none;
            border-radius: 18px;
            box-shadow: 0 10px 35px rgba(15, 23, 42, 0.12);
            overflow: hidden;
        }
        .brand-banner {
            background: linear-gradient(135deg, #2563eb, #4f46e5);
            color: #fff;
            padding: 18px;
        }
        .brand-banner h5 {
            margin: 0;
            font-weight: 700;
            letter-spacing: 0.3px;
        }
        .brand-banner span { opacity: 0.85; font-size: 0.9rem; }
        .form-control { border-radius: 12px; }
        .btn-primary { border-radius: 12px; box-shadow: 0 6px 18px rgba(79,70,229,0.25); }
        .dev-hint { background: #f8fafc; border-radius: 12px; padding: 10px 12px; border: 1px dashed #cbd5e1; }
        @media (max-width: 576px) {
            body { padding: 16px; }
            .brand-banner h5 { font-size: 1.1rem; }
        }
    </style>
</head>
<body class="bg-light">
<div class="container d-flex justify-content-center align-items-center min-vh-100 p-4">
    <div class="card shadow-sm" style="max-width: 420px;">
        <div class="card-header bg-primary text-white text-center">
            <h5 class="mb-0">Aplikasi Koperasi</h5>
            <small>Portal Internal Koperasi</small>
        </div>
        <div class="card-body p-4">
            <h5 class="mb-3">Masuk</h5>
            <p class="text-muted small">Gunakan akun internal untuk melanjutkan.</p>
            <form id="loginForm" action="/ksp_mono/login_action.php<?php echo $redirectUrl ? '?redirect=' . urlencode($redirectUrl) : ''; ?>" method="post" novalidate>
                <div class="form-container">
                    <div class="mb-3">
                        <label for="usernameInput" class="form-label">Username</label>
                        <input type="text" class="form-control" id="usernameInput" name="username" required>
                        <div class="invalid-feedback">Username wajib diisi</div>
                    </div>
                    <div class="mb-3">
                        <label for="passwordInput" class="form-label">Password</label>
                        <input type="password" class="form-control" id="passwordInput" name="password" required>
                        <div class="invalid-feedback">Password wajib diisi</div>
                    </div>

                    <div class="form-actions">
                        <button type="submit" class="btn btn-primary w-100">
                            <i class="bi bi-box-arrow-in-right"></i> Login
                        </button>
                    </div>
                </div>
                <div id="loginError" class="alert alert-danger mt-3 d-none"></div>
            </form>
            <div class="mt-3 d-flex flex-column gap-2">
                <a class="text-decoration-none" href="/ksp_mono/register_koperasi.php">Daftarkan Koperasi Baru</a>
                <a class="text-decoration-none" href="/ksp_mono/register_user.php">Registrasi Admin/User (pilih koperasi)</a>
            </div>
        </div>
    </div>
</div>

<!-- Role Selection Modal -->
<div class="modal fade" id="roleSelectionModal" tabindex="-1" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Pilih Peran</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p>Anda memiliki multiple peran, silakan pilih:</p>
                <div class="d-grid gap-2">
                    <button type="button" class="btn btn-primary" data-role="admin">Masuk sebagai Admin</button>
                    <button type="button" class="btn btn-secondary" data-role="anggota">Masuk sebagai Anggota</button>
                </div>
            </div>
        </div>
    </div>
</div>

<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>

<script>
$(document).ready(function() {
    // Login form validation
    $('#loginForm').on('submit', function(e) {
        const form = this;
        if (!form.checkValidity()) {
            e.preventDefault();
            e.stopPropagation();
        }
        
        $(form).addClass('was-validated');
    });
    
    // Handle login errors
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.has('error')) {
        $('#loginError').text(decodeURIComponent(urlParams.get('error'))).removeClass('d-none');
    }
});
</script>

<script>
$(document).ready(function() {
    // Handle login response
    $('#loginForm').on('ajax:success', function(e, data) {
        if (data.role_choice_needed) {
            // Show role selection modal
            $('#roleSelectionModal').modal('show');
            
            // Handle role selection
            $('.btn-role').on('click', function() {
                const selectedRole = $(this).data('role');
                $.post('/ksp_mono/login_action.php', {
                    action: 'set_role',
                    role: selectedRole
                }, function(response) {
                    window.location.href = response.redirect || '/dashboard.php';
                });
            });
        } else {
            // Normal redirect
            window.location.href = data.redirect || '/dashboard.php';
        }
    });
});
</script>
</body>
</html>
