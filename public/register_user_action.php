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

$koperasi_id   = isset($_POST['koperasi_id']) ? (int)$_POST['koperasi_id'] : 0;
$nama          = trim($_POST['nama'] ?? '');
$username      = trim($_POST['username'] ?? '');
$email         = trim($_POST['email'] ?? '');
$password      = $_POST['password'] ?? '';
$password_conf = $_POST['password_confirm'] ?? '';
$user_db_id    = 1; // default, sesuaikan jika ada tabel user_db di coop_db
$status        = 'active';
$peran_jenis_id = isset($_POST['peran_jenis_id']) ? (int)$_POST['peran_jenis_id'] : 0;

if ($koperasi_id <= 0 || $nama === '' || $username === '' || $email === '' || $password === '' || $peran_jenis_id <= 0) {
    echo json_encode(['success' => false, 'message' => 'Semua field wajib diisi.']);
    exit;
}

if ($password !== $password_conf) {
    echo json_encode(['success' => false, 'message' => 'Konfirmasi password tidak cocok.']);
    exit;
}

try {
    $pdo = Database::conn();
    // cek koperasi ada (tabel koperasi_tenant - skema baru)
    $stmt = $pdo->prepare('SELECT id FROM koperasi_tenant WHERE id = :id');
    $stmt->execute([':id' => $koperasi_id]);
    $kop = $stmt->fetch();
    if (!$kop) {
        echo json_encode(['success' => false, 'message' => 'Koperasi tidak ditemukan.']);
        exit;
    }

    // hash password
    $hash = password_hash($password, PASSWORD_BCRYPT);

    // simpan user (skema coop_db)
    $stmtIns = $pdo->prepare('INSERT INTO pengguna (username, password_hash, user_db_id, status) VALUES (:username, :password_hash, :user_db_id, :status)');
    $stmtIns->execute([
        ':username' => $username,
        ':password_hash' => $hash,
        ':user_db_id' => $user_db_id,
        ':status' => $status,
    ]);

    $user_id = $pdo->lastInsertId();

    // assign role
    $stmtRole = $pdo->prepare('INSERT INTO pengguna_peran (pengguna_id, peran_jenis_id) VALUES (:pengguna_id, :peran_jenis_id)');
    $stmtRole->execute([
        ':pengguna_id' => $user_id,
        ':peran_jenis_id' => $peran_jenis_id,
    ]);

    echo json_encode(['success' => true, 'message' => 'User berhasil didaftarkan dengan peran yang dipilih. Silakan login.', 'redirect' => '/ksp_mono/login.php']);
} catch (PDOException $e) {
    error_log('register_user PDO error: ' . $e->getMessage());
    if ($e->getCode() === '23000') {
        echo json_encode(['success' => false, 'message' => 'Username atau email sudah terpakai.']);
    } else {
        http_response_code(500);
        echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan database.']);
    }
} catch (Throwable $e) {
    error_log('register_user error: ' . $e->getMessage());
    http_response_code(500);
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server.']);
}
