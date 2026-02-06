<?php
// Read-only kecamatan list from alamat_db
header('Content-Type: application/json');

require_once __DIR__ . '/../../app/bootstrap.php';

try {
    // Pakai koneksi terpisah ke alamat_db (read-only)
    $cfgAlamat = app_config('alamat_db');
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $cfgAlamat['host'], $cfgAlamat['name'], $cfgAlamat['charset']);
    $pdoAlamat = new PDO($dsn, $cfgAlamat['user'], $cfgAlamat['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $stmt = $pdoAlamat->query("SELECT id, nama FROM kecamatan ORDER BY nama ASC");
    $rows = $stmt->fetchAll();

    echo json_encode(['success' => true, 'data' => $rows]);
} catch (Throwable $e) {
    error_log('kecamatan_list error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal memuat kecamatan']);
}
