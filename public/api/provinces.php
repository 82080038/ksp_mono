<?php
// List provinces (alamat_db, read-only)
header('Content-Type: application/json');

try {
    $dsn = 'mysql:host=localhost;dbname=alamat_db;charset=utf8mb4';
    $pdo = new PDO($dsn, 'root', 'root', [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    $rows = $pdo->query('SELECT id, name AS nama FROM provinces ORDER BY name ASC')->fetchAll();
    echo json_encode(['success' => true, 'data' => $rows]);
} catch (Throwable $e) {
    error_log('provinces error: '.$e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal memuat provinsi']);
}
