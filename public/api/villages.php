<?php
// List villages (kelurahan/desa) by district_id from alamat_db (read-only)
header('Content-Type: application/json');
$district_id = isset($_GET['district_id']) ? (int)$_GET['district_id'] : 0;
if ($district_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'district_id wajib']);
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
    // cek apakah kolom postal_code tersedia
    $hasPostal = false;
    $cols = $pdo->query("SHOW COLUMNS FROM villages LIKE 'postal_code'")->fetch();
    if ($cols) {
        $hasPostal = true;
    }

    $sql = $hasPostal
        ? 'SELECT id, name AS nama, postal_code AS kodepos FROM villages WHERE district_id = :did ORDER BY name ASC'
        : 'SELECT id, name AS nama FROM villages WHERE district_id = :did ORDER BY name ASC';

    $stmt = $pdo->prepare($sql);
    $stmt->execute([':did' => $district_id]);
    $rows = $stmt->fetchAll();
    if ($hasPostal) {
        foreach ($rows as &$r) {
            if (!isset($r['kodepos'])) $r['kodepos'] = '';
        }
    }
    echo json_encode(['success' => true, 'data' => $rows]);
} catch (Throwable $e) {
    error_log('villages error: '.$e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Gagal memuat kelurahan/desa']);
}
