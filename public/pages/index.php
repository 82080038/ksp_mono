<?php
// Set header untuk response JSON
header('Content-Type: application/json');

require_once __DIR__ . '/../../app/bootstrap.php';
$auth = new Auth();
if (!$auth->check()) {
    http_response_code(401);
    echo json_encode(['status' => 'error', 'message' => 'Unauthorized']);
    exit;
}

// Dapatkan parameter modul dan action dari URL
$modul = isset($_GET['modul']) ? $_GET['modul'] : 'dashboard';
$action = isset($_GET['action']) ? $_GET['action'] : 'index';

// Daftar modul yang valid
$validModuls = ['anggota', 'simpanan', 'pinjaman', 'laporan', 'pengaturan', 'dashboard'];

// Periksa apakah modul yang diminta valid
if (!in_array($modul, $validModuls)) {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'Modul tidak ditemukan'
    ]);
    exit;
}

// Tentukan path file yang akan dimuat
$filePath = __DIR__ . "/{$modul}/index.php";

// Periksa apakah file ada
if (!file_exists($filePath)) {
    http_response_code(404);
    echo json_encode([
        'status' => 'error',
        'message' => 'Halaman tidak ditemukan'
    ]);
    exit;
}

// Include file modul
ob_start();
include $filePath;
$content = ob_get_clean();

echo json_encode([
    'status' => 'success',
    'content' => $content
]);
?>
