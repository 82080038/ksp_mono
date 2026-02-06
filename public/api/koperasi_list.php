<?php
// List koperasi (untuk dropdown registrasi user)
header('Content-Type: application/json');
require_once __DIR__ . '/../../app/bootstrap.php';

try {
    $pdo = Database::conn();
    $stmt = $pdo->query("SELECT id, nama, district_id FROM koperasi_tenant ORDER BY nama ASC");
    $rows = $stmt->fetchAll();

    // map district_id ke nama kecamatan (alamat_db)
    $cfgAlamat = app_config('alamat_db');
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $cfgAlamat['host'], $cfgAlamat['name'], $cfgAlamat['charset']);
    $pdoAlamat = new PDO($dsn, $cfgAlamat['user'], $cfgAlamat['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);
    $map = [];
    $stmtKec = $pdoAlamat->query("SELECT id, name AS nama FROM districts");
    foreach ($stmtKec as $r) {
        $map[$r['id']] = $r['nama'];
    }
    foreach ($rows as &$r) {
        $r['kecamatan_nama'] = $map[$r['district_id']] ?? '';
    }
    echo json_encode(['success' => true, 'data' => $rows]);
} catch (Throwable $e) {
    error_log('koperasi_list error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal memuat koperasi']);
}
