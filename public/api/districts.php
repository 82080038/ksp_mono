<?php
require_once __DIR__ . '/../../app/bootstrap.php';
// List districts (kecamatan) by regency_id from alamat_db (read-only)
header('Content-Type: application/json');
$regency_id = isset($_GET['regency_id']) ? (int)$_GET['regency_id'] : 0;
if ($regency_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'regency_id wajib']);
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
    $stmt = $pdo->prepare('SELECT id, name AS nama FROM kecamatan WHERE regency_id = :regency_id ORDER BY name ASC');
    $stmt->execute([':regency_id' => $regency_id]);
    $rows = $stmt->fetchAll();
    echo json_encode(['success' => true, 'data' => $rows]);
} catch (Throwable $e) {
    error_log('districts error: '.$e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal memuat kecamatan']);
}
