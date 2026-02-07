<?php
require_once __DIR__ . '/../../app/bootstrap.php';

// Konfigurasi pagination
$limit = 50;
$page = max(1, $_GET['page'] ?? 1);
$offset = ($page - 1) * $limit;

header('Content-Type: application/json');
header('Cache-Control: public, max-age=3600'); // Cache 1 jam

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
    
    // Query dengan pagination
    $stmt = $pdo->prepare('SELECT id, name AS nama FROM kabkota WHERE province_id = ? ORDER BY name ASC LIMIT ? OFFSET ?');
    $stmt->execute([$province_id, $limit, $offset]);
    $rows = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true, 
        'data' => $rows,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'province_id' => $province_id
        ]
    ]);
} catch (Throwable $e) {
    error_log('regencies error: '.$e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Gagal memuat kabupaten/kota'
    ]);
}
