<?php
require_once __DIR__ . '/../../app/bootstrap.php';

// Debug logging
error_log('[DISTRICTS API] Request received: ' . $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI']);
error_log('[DISTRICTS API] GET params: ' . json_encode($_GET));

// Konfigurasi pagination
$limit = 50;
$page = max(1, $_GET['page'] ?? 1);
$offset = ($page - 1) * $limit;

header('Content-Type: application/json');
header('Cache-Control: public, max-age=3600'); // Cache 1 jam

$regency_id = isset($_GET['regency_id']) ? (int)$_GET['regency_id'] : 0;
error_log('[DISTRICTS API] Regency ID: ' . $regency_id);

if ($regency_id <= 0) {
    error_log('[DISTRICTS API] ERROR: Invalid regency_id');
    echo json_encode(['success' => false, 'message' => 'regency_id wajib']);
    exit;
}

try {
    error_log('[DISTRICTS API] Loading database config');
    $cfg = app_config('alamat_db');
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $cfg['host'], $cfg['name'], $cfg['charset']);
    error_log('[DISTRICTS API] DSN: ' . $dsn);
    
    $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    error_log('[DISTRICTS API] Database connection successful');
    
    // Query dengan pagination
    $sql = 'SELECT id, name AS nama FROM kecamatan WHERE regency_id = ? ORDER BY name ASC LIMIT ? OFFSET ?';
    error_log('[DISTRICTS API] Executing query: ' . $sql);
    error_log('[DISTRICTS API] Parameters: regency_id=' . $regency_id . ', limit=' . $limit . ', offset=' . $offset);
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$regency_id, $limit, $offset]);
    $rows = $stmt->fetchAll();
    
    error_log('[DISTRICTS API] Query executed successfully, rows: ' . count($rows));
    error_log('[DISTRICTS API] Sample row: ' . print_r($rows[0] ?? 'No rows', true));
    
    $response = [
        'success' => true, 
        'data' => $rows,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'regency_id' => $regency_id
        ],
        'debug' => [
            'query' => $sql,
            'params' => [$regency_id, $limit, $offset],
            'row_count' => count($rows)
        ]
    ];
    
    error_log('[DISTRICTS API] Response prepared: ' . json_encode($response));
    echo json_encode($response);
    
} catch (Throwable $e) {
    error_log('[DISTRICTS API] Error occurred: ' . $e->getMessage());
    error_log('[DISTRICTS API] Error trace: ' . $e->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Gagal memuat kecamatan',
        'error' => $e->getMessage(),
        'debug' => [
            'regency_id' => $regency_id,
            'config_loaded' => isset($cfg),
            'dsn' => $dsn ?? 'not_created'
        ]
    ]);
}
