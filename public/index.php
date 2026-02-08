<?php
// Mulai session jika belum dimulai
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/bootstrap.php';

// Inisialisasi autentikasi
$auth = new Auth();

// Jika user sudah login, tampilkan halaman yang sesuai
if ($auth->check()) {
    // Dapatkan parameter modul dan action
    $modul = isset($_GET['modul']) ? $_GET['modul'] : 'dashboard';
    $action = isset($_GET['action']) ? $_GET['action'] : 'index';
    
    // Daftar modul yang valid - dinamis dari database
    $db = Database::conn();
    $moduls = $db->query('SELECT nama FROM modul WHERE is_active = 1')->fetchAll(PDO::FETCH_COLUMN);
    $validModuls = $moduls;
    
    // Jika modul tidak valid, gunakan dashboard sebagai default
    if (!in_array($modul, $validModuls)) {
        $modul = 'dashboard';
    }
    
    // Tentukan path file yang akan dimuat
    $filePath = __DIR__ . "/pages/{$modul}/index.php";
    
    // Jika file tidak ada, gunakan halaman dashboard
    if (!file_exists($filePath)) {
        $modul = 'dashboard';
        $filePath = __DIR__ . "/pages/dashboard/index.php";
    }
    
    // Set header untuk mencegah caching
    header('Cache-Control: no-store, no-cache, must-revalidate, max-age=0');
    header('Cache-Control: post-check=0, pre-check=0', false);
    header('Pragma: no-cache');
    
    // Include header
    include __DIR__ . '/layouts/header.php';
    
    // Include navbar
    include __DIR__ . '/partials/navbar.php';
    
    // Main layout with sidebar and content
    echo '<div class="container-fluid main-content">';
    echo '<div class="row">';
    
    // Sidebar column
    echo '<div class="col-md-3 col-lg-2 px-0">';
    include __DIR__ . '/partials/sidebar.php';
    echo '</div>';
    
    // Content column
    echo '<div class="col-md-9 col-lg-10">';
    echo '<main class="content">';
    include $filePath;
    echo '</main>';
    echo '</div>';
    
    echo '</div>';
    echo '</div>';
    
    // Debug Session Data (Admin Only)
    if (isset($_SESSION['user']['role']) && $_SESSION['user']['role'] === 'admin') {
        echo '<div class="mt-5 p-3 bg-light border rounded">';
        echo '<h6 class="mb-3">Debug: Session Data (Admin Only)</h6>';
        $debug = [];
        foreach ($_SESSION as $k => $v) {
            if (is_array($v)) {
                $debug[$k] = count($v) . ' items';
            } elseif (is_object($v)) {
                $debug[$k] = 'object';
            } else {
                $debug[$k] = $v;
            }
        }
        echo '<pre class="small">' . print_r($debug, true) . '</pre>';
        echo '</div>';
    }
    
    // Include footer
    include __DIR__ . '/layouts/footer.php';
    
    exit;
}

// Jika belum login, arahkan ke halaman login dengan path tanpa /public
$loginUrl = '/ksp_mono/public/login.php';

// Tambahkan pesan redirect jika ada
if (isset($_GET['redirect'])) {
    $loginUrl .= '?redirect=' . urlencode($_GET['redirect']);
}

// Redirect ke halaman login
header('Location: ' . $loginUrl);
http_response_code(302); // Found

exit;
