<?php
require_once __DIR__ . '/../../app/bootstrap.php';

// Debug logging
error_log('[PROVINCES API] Request received: ' . $_SERVER['REQUEST_METHOD'] . ' ' . $_SERVER['REQUEST_URI']);

// Konfigurasi pagination
$limit = 50;
$page = max(1, $_GET['page'] ?? 1);
$offset = ($page - 1) * $limit;

header('Content-Type: application/json');
header('Cache-Control: public, max-age=3600'); // Cache 1 jam

try {
    error_log('[PROVINCES API] Loading database config');
    $cfg = app_config('alamat_db');
    error_log('[PROVINCES API] Config loaded: ' . print_r($cfg, true));
    
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $cfg['host'], $cfg['name'], $cfg['charset']);
    error_log('[PROVINCES API] DSN: ' . $dsn);
    
    $pdo = new PDO($dsn, $cfg['user'], $cfg['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    error_log('[PROVINCES API] Database connection successful');
    
    // Query dengan pagination
    $sql = 'SELECT id, name AS nama FROM provinsi ORDER BY name ASC LIMIT ? OFFSET ?';
    error_log('[PROVINCES API] Executing query: ' . $sql);
    error_log('[PROVINCES API] Parameters: limit=' . $limit . ', offset=' . $offset);
    
    $stmt = $pdo->prepare($sql);
    $stmt->execute([$limit, $offset]);
    $rows = $stmt->fetchAll();
    
    error_log('[PROVINCES API] Query executed successfully, rows: ' . count($rows));
    error_log('[PROVINCES API] Sample row: ' . print_r($rows[0] ?? 'No rows', true));
    
    $response = [
        'success' => true, 
        'data' => $rows,
        'pagination' => [
            'page' => $page,
            'limit' => $limit,
            'total' => count($rows)
        ],
        'debug' => [
            'query' => $sql,
            'params' => [$limit, $offset],
            'row_count' => count($rows)
        ]
    ];
    
    error_log('[PROVINCES API] Response prepared: ' . json_encode($response));
    echo json_encode($response);
    
} catch (Throwable $e) {
    error_log('[PROVINCES API] Error occurred: ' . $e->getMessage());
    error_log('[PROVINCES API] Error trace: ' . $e->getTraceAsString());
    
    http_response_code(500);
    echo json_encode([
        'success' => false, 
        'message' => 'Gagal memuat provinsi',
        'error' => $e->getMessage(),
        'debug' => [
            'config_loaded' => isset($cfg),
            'dsn' => $dsn ?? 'not_created',
            'host' => $cfg['host'] ?? 'unknown',
            'database' => $cfg['name'] ?? 'unknown'
        ]
    ]);
}
