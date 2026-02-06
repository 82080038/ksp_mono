<?php
// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/bootstrap.php';

// Set header untuk response JSON
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
    exit;
}

$username = trim($_POST['username'] ?? '');
$password = trim($_POST['password'] ?? '');

if ($username === '' || $password === '') {
    echo json_encode(['success' => false, 'message' => 'Username dan password wajib diisi']);
    exit;
}

try {
    $auth = new Auth();
    if ($auth->login($username, $password)) {
        // Setelah login berhasil, tentukan URL redirect
        $redirectUrl = '/ksp_mono/';
        
        // Cek apakah ada parameter redirect
        if (isset($_GET['redirect']) && !empty($_GET['redirect'])) {
            $requestedUrl = filter_var($_GET['redirect'], FILTER_SANITIZE_URL);
            
            // Validasi URL redirect untuk mencegah open redirect
            $parsedUrl = parse_url($requestedUrl);
            if (!isset($parsedUrl['host']) || $parsedUrl['host'] === $_SERVER['HTTP_HOST']) {
                $redirectUrl = $parsedUrl['path'] ?? '/';
                if (isset($parsedUrl['query'])) {
                    $redirectUrl .= '?' . $parsedUrl['query'];
                }
            }
        }
        
        echo json_encode([
            'success' => true, 
            'redirect' => $redirectUrl,
            'message' => 'Login berhasil. Mengalihkan...'
        ]);
    } else {
        echo json_encode([
            'success' => false, 
            'message' => 'Username atau password salah',
            'field' => 'password'
        ]);
    }
} catch (Throwable $e) {
    error_log('Login error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Terjadi kesalahan server. Silakan coba lagi nanti.'
    ]);
}
