<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
require_once __DIR__ . '/../app/bootstrap.php';
header('Content-Type: application/json');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Metode tidak diizinkan']);
    exit;
}

$nama     = trim($_POST['nama_koperasi'] ?? '');
$alamat   = trim($_POST['alamat_lengkap'] ?? '');
$kontak   = trim($_POST['kontak'] ?? '');
$npwp     = trim($_POST['npwp'] ?? '');
$province_id  = isset($_POST['province_id']) ? (int)$_POST['province_id'] : 0;
$regency_id   = isset($_POST['regency_id']) ? (int)$_POST['regency_id'] : 0;
$district_id  = isset($_POST['district_id']) ? (int)$_POST['district_id'] : 0;
$village_id   = isset($_POST['village_id']) ? (int)$_POST['village_id'] : 0;

if ($nama === '' || $alamat === '' || $kontak === '' || $province_id <= 0 || $regency_id <= 0 || $district_id <= 0 || $village_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi (kecuali NPWP).']);
    exit;
}

try {
    // Validasi alamat di alamat_db (read-only)
    $cfgAlamat = app_config('alamat_db');
    $dsnAlamat = sprintf('mysql:host=%s;dbname=%s;charset=%s', $cfgAlamat['host'], $cfgAlamat['name'], $cfgAlamat['charset']);
    $pdoAlamat = new PDO($dsnAlamat, $cfgAlamat['user'], $cfgAlamat['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
        PDO::ATTR_EMULATE_PREPARES => false,
    ]);

    $check = function(string $table, int $id, string $parentCol = null, $parentVal = null) use ($pdoAlamat) {
        if ($parentCol) {
            $stmt = $pdoAlamat->prepare("SELECT id FROM {$table} WHERE id = :id AND {$parentCol} = :parent LIMIT 1");
            $stmt->execute([':id' => $id, ':parent' => $parentVal]);
        } else {
            $stmt = $pdoAlamat->prepare("SELECT id FROM {$table} WHERE id = :id LIMIT 1");
            $stmt->execute([':id' => $id]);
        }
        return (bool)$stmt->fetch();
    };

    if (!$check('provinces', $province_id)) {
        echo json_encode(['success' => false, 'message' => 'Provinsi tidak valid.']);
        exit;
    }
    if (!$check('regencies', $regency_id, 'province_id', $province_id)) {
        echo json_encode(['success' => false, 'message' => 'Kabupaten/Kota tidak valid.']);
        exit;
    }
    if (!$check('districts', $district_id, 'regency_id', $regency_id)) {
        echo json_encode(['success' => false, 'message' => 'Kecamatan tidak valid.']);
        exit;
    }
    if (!$check('villages', $village_id, 'district_id', $district_id)) {
        echo json_encode(['success' => false, 'message' => 'Kelurahan/Desa tidak valid.']);
        exit;
    }

    $pdo = Database::conn();
    // coerce fields ke tabel koperasi_tenant (schema coop_db yg sudah di-rename)
    $stmt = $pdo->prepare("INSERT INTO koperasi_tenant (province_id, regency_id, district_id, village_id, nama, alamat_legal, kontak_resmi, npwp, status_badan_hukum, status_notes, modal_pokok) VALUES (:province_id, :regency_id, :district_id, :village_id, :nama, :alamat, :kontak, :npwp, 'belum_terdaftar', NULL, 0.00)");
    $stmt->execute([
        ':province_id' => $province_id,
        ':regency_id' => $regency_id,
        ':district_id' => $district_id,
        ':village_id' => $village_id,
        ':nama' => $nama,
        ':alamat' => $alamat,
        ':kontak' => $kontak,
        ':npwp' => $npwp ?: null,
    ]);
    echo json_encode(['success' => true, 'message' => 'Koperasi berhasil didaftarkan. Silakan lanjut registrasi admin/user.']);
} catch (Throwable $e) {
    error_log('register_koperasi error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server.']);
}
