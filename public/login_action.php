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
        $userRoles = $auth->getUserRoles($username);
        // Set role in session
        $_SESSION['user']['role'] = $userRoles[0] ?? 'admin';
        
        // Set user_id for permission checks
        $_SESSION['user_id'] = $_SESSION['user']['id'];
        
        // Get cooperative data for the user
        try {
            $db = Database::conn();
            $stmt = $db->prepare('SELECT * FROM koperasi_tenant');
            $stmt->execute();
            $cooperatives = $stmt->fetchAll();
            
            // Render jenis_koperasi names from koperasi_jenis table
            foreach ($cooperatives as &$coop) {
                $ids = json_decode($coop['jenis_koperasi'] ?? '[]', true);
                $names = [];
                if (!empty($ids)) {
                    $in = str_repeat('?,', count($ids) - 1) . '?';
                    $stmt2 = $db->prepare('SELECT name FROM koperasi_jenis WHERE id IN (' . $in . ')');
                    $stmt2->execute($ids);
                    $names = $stmt2->fetchAll(PDO::FETCH_COLUMN);
                }
                $coop['jenis_koperasi'] = implode(', ', $names);
            }
            
            $_SESSION['cooperatives'] = $cooperatives;
        } catch (Exception $e) {
            error_log('Error loading cooperatives: ' . $e->getMessage());
            header('Location: /ksp_mono/public/login.php?error=' . urlencode('Gagal memuat data koperasi: ' . $e->getMessage()));
            exit;
        }
        
        error_log('Cooperatives set: ' . json_encode($cooperatives));
        error_log('Session cooperatives count: ' . count($_SESSION['cooperatives'] ?? []));
        
        // Get person data for the user
        $stmt = $db->prepare('SELECT * FROM orang WHERE pengguna_id = ?');
        $stmt->execute([$_SESSION['user']['id']]);
        $person = $stmt->fetch();
        if ($person) {
            $_SESSION['user']['person'] = $person;
        }
        
        // Get accessible modules based on user permissions
        $permissions = $_SESSION['permissions'] ?? [];
        if (!empty($permissions)) {
            $in = str_repeat('?,', count($permissions) - 1) . '?';
            $stmt = $db->prepare("SELECT * FROM modul WHERE (permission_required IN ($in) OR permission_required IS NULL) AND is_active = 1 ORDER BY urutan");
            $stmt->execute($permissions);
        } else {
            $stmt = $db->prepare("SELECT * FROM modul WHERE permission_required IS NULL AND is_active = 1 ORDER BY urutan");
            $stmt->execute();
        }
        $accessibleModules = $stmt->fetchAll();
        $_SESSION['accessible_modules'] = $accessibleModules;
        
        // Debug: Log session content
        file_put_contents('/tmp/session_debug.txt', 'Session at login: ' . print_r($_SESSION, true));
        
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
        
        // Handle role selection - for now, assume single role or default
        if (count($userRoles) > 1) {
            // If multiple roles, redirect to role selection page (not implemented yet)
            header('Location: /ksp_mono/public/login.php?error=' . urlencode('Multiple roles detected - feature not implemented'));
            exit;
        }
        
        // Redirect to dashboard
        header('Location: ' . $redirectUrl);
        exit;
    } else {
        // Login failed, redirect back to login with error
        header('Location: /ksp_mono/public/login.php?error=' . urlencode('Username atau password salah'));
        exit;
    }
} catch (Throwable $e) {
    error_log('Login error: ' . $e->getMessage());
    // On error, redirect back to login
    header('Location: /ksp_mono/public/login.php?error=' . urlencode('Terjadi kesalahan server. Silakan coba lagi nanti.'));
    exit;
}
