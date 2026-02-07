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
<body>
<div class="card auth-card">
    <div class="brand-banner">
        <h5>KSP-Peb</h5>
        <span>Portal Internal Koperasi</span>
    </div>
    <div class="card-body p-4">
        <h5 class="mb-3">Masuk</h5>
        <p class="text-muted small">Gunakan akun internal untuk melanjutkan.</p>
        <form id="loginForm" action="/ksp_mono/login_action.php<?php echo $redirectUrl ? '?redirect=' . urlencode($redirectUrl) : ''; ?>" method="post" novalidate>
            <div class="form-floating mb-3">
                <input type="text" class="form-control" name="username" id="usernameInput" required placeholder="Username">
                <div class="invalid-feedback">Username wajib diisi</div>
                <label for="usernameInput"><i class="bi bi-person"></i> Username</label>
            </div>
            <div class="form-floating mb-3">
                <input type="password" class="form-control" name="password" id="passwordInput" required placeholder="Password">
                <div class="invalid-feedback">Password wajib diisi</div>
                <label for="passwordInput"><i class="bi bi-lock"></i> Password</label>
            </div>
            <div class="d-grid gap-2">
                <button type="submit" class="btn btn-primary btn-lg"><i class="bi bi-box-arrow-in-right"></i> Login</button>
            </div>
            <div id="loginError" class="alert alert-danger mt-3 d-none"></div>
        </form>
        <script>
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
        </script>
        <div class="mt-3 d-flex flex-column gap-2">
            <a class="text-decoration-none" href="/ksp_mono/register_koperasi.php">Daftarkan Koperasi Baru</a>
            <a class="text-decoration-none" href="/ksp_mono/register_user.php">Registrasi Admin/User (pilih koperasi)</a>
        </div>
    </div>
</div>
<script src="https://code.jquery.com/jquery-3.7.1.min.js"></script>
</body>
</html>
