<?php
require_once __DIR__ . '/../../app/bootstrap.php';

// Debug logging
error_log('[VILLAGES API] Request received: ' . $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI']);
error_log('[VILLAGES API] GET params: ' . json_encode($_GET));

// Konfigurasi pagination
$limit = 50;
$page = max(1, $_GET['page'] ?? 1);
$offset = ($page - 1) * $limit;

header('Content-Type: application/json');
header('Cache-Control: public, max-age=3600'); // Cache 1 jam

$district_id = isset($_GET['district_id']) ? (int)$_GET['district_id'] : 0;
error_log('[VILLAGES API] District ID: ' . $district_id);

if ($district_id <= 0) {
    error_log('[VILLAGES API] ERROR: Invalid district_id');
    echo json_encode(['success' => false, 'message' => 'district_id wajib']);
    exit;
}

try {
    error_log('[VILLAGES API] Loading database config');
    $cfg = app_config('alamat_db');
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $cfg['host'], $cfg['name'], $cfg['charset']);
    error_log('[VILLAGES API] DSN: ' . $dsn);
    
    $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    error_log('[VILLAGES API] Database connection successful');
    
    // Query dengan pagination
    $sql = 'SELECT id, name AS nama, postal_code AS kodepos FROM kelurahan WHERE district_id = ? ORDER BY name ASC LIMIT ? OFFSET ?';
    error_log('[VILLAGES API] Executing query: ' . $sql);
    error_log('[VILLAGES API] Parameters: district_id=' . $district_id . ', limit=' . $limit . ', offset=' . $offset);
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$district_id, $limit, $offset]);
    $rows = $stmt->fetchAll();
    
    error_log('[VILLAGES API] Query executed successfully, rows: ' . count($rows));
    error_log('[VILLAGES API] Sample row: ' . print_r($rows[0] ?? 'No rows', true));
    
    $response = [
        'success' => true, 
        'data' => $rows,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'district_id' => $district_id
        ],
        'debug' => [
            'query' => $sql,
            'params' => [$district_id, $limit, $offset],
            'row_count' => count($rows)
        ]
    ];
    
    error_log('[VILLAGES API] Response prepared: ' . json_encode($response));
    echo json_encode($response);
    
} catch (Throwable $e) {
    error_log('[VILLAGES API] Error occurred: ' . $e->getMessage());
    error_log('[VILLAGES API] Error trace: ' . $e->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Gagal memuat kelurahan/desa',
        'error' => $e->getMessage(),
        'debug' => [
            'district_id' => $district_id,
            'config_loaded' => isset($cfg),
            'dsn' => $dsn ?? 'not_created'
        ]
    ]);
}
