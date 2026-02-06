<?php
require_once __DIR__ . '/../../app/bootstrap.php';
// List regencies by province (alamat_db, read-only)
header('Content-Type: application/json');

$province_id = isset($_GET['province_id']) ? (int)$_GET['province_id'] : 0;
if ($province_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'province_id wajib']);
    exit;
}

try {
    $cfg = app_config('alamat_db');
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $cfg['host'], $cfg['name'], $cfg['charset']);
    $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    $stmt = $pdo->prepare('SELECT id, name AS nama FROM kabkota WHERE province_id = :province_id ORDER BY name ASC');
    $stmt->execute([':province_id' => $province_id]);
    $rows = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $rows]);
} catch (Throwable $e) {
    error_log('regencies error: '.$e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal memuat kabupaten/kota']);
}
