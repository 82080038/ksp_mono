<?php
require_once __DIR__ . '/../../app/bootstrap.php';

// Konfigurasi pagination
$limit = 50;
$page = max(1, $_GET['page'] ?? 1);
$offset = ($page - 1) * $limit;

header('Content-Type: application/json');
header('Cache-Control: public, max-age=3600'); // Cache 1 jam

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
    
    // Query dengan pagination
    $stmt = $pdo->prepare('SELECT id, name AS nama FROM kecamatan WHERE regency_id = ? ORDER BY name ASC LIMIT ? OFFSET ?');
    $stmt->execute([$regency_id, $limit, $offset]);
    $rows = $stmt->fetchAll();
    
    echo json_encode([
        'success' => true, 
        'data' => $rows,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'regency_id' => $regency_id
        ]
    ]);
} catch (Throwable $e) {
    error_log('districts error: '.$e->getMessage());
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Gagal memuat kecamatan'
    ]);
}
