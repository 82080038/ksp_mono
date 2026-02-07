<?php
// register_user_process.php - Backend processing for user registration

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/bootstrap.php';

// Only allow POST requests
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Get database connection
$db = Database::conn();

// Initialize response
$response = ['success' => false, 'message' => ''];

// Validate required fields
$requiredFields = ['koperasi_id', 'nama', 'username', 'email', 'password', 'password_confirm', 'peran_jenis_id'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        $response['message'] = "Field {$field} is required";
        echo json_encode($response);
        exit;
    }
}

// Sanitize input data
$nama = trim($_POST['nama']);
$username = trim($_POST['username']);
$email = trim($_POST['email']);
$password = $_POST['password'];
$password_confirm = $_POST['password_confirm'];
$peran_jenis_id = intval($_POST['peran_jenis_id']);
$koperasi_id = intval($_POST['koperasi_id']);
$kecamatan_id = intval($_POST['kecamatan_id'] ?? 0);

// Validation
if (strlen($nama) < 2) {
    $response['message'] = 'Nama lengkap minimal 2 karakter';
    echo json_encode($response);
    exit;
}

$usernameRegex = '/^[a-zA-Z0-9_]{4,20}$/';
if (!preg_match($usernameRegex, $username)) {
    $response['message'] = 'Username harus 4-20 karakter, hanya huruf, angka, dan underscore';
    echo json_encode($response);
    exit;
}

$emailRegex = '/^[^\s@]+@[^\s@]+\.[^\s@]+$/';
if (!preg_match($emailRegex, $email)) {
    $response['message'] = 'Email tidak valid';
    echo json_encode($response);
    exit;
}

if (strlen($password) < 8) {
    $response['message'] = 'Password minimal 8 karakter';
    echo json_encode($response);
    exit;
}

$passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/';
if (!preg_match($passwordRegex, $password)) {
    $response['message'] = 'Password harus mengandung huruf besar, kecil, dan angka';
    echo json_encode($response);
    exit;
}

if ($password !== $password_confirm) {
    $response['message'] = 'Konfirmasi password tidak cocok';
    echo json_encode($response);
    exit;
}

// Check if cooperative exists
$stmt = $db->prepare('SELECT id, nama_koperasi FROM koperasi_tenant WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $koperasi_id]);
$cooperative = $stmt->fetch();
if (!$cooperative) {
    $response['message'] = 'Koperasi tidak ditemukan';
    echo json_encode($response);
    exit;
}

// Check if username already exists
$stmt = $db->prepare('SELECT id FROM pengguna WHERE username = :username LIMIT 1');
$stmt->execute([':username' => $username]);
if ($stmt->fetch()) {
    $response['message'] = 'Username sudah terpakai';
    echo json_encode($response);
    exit;
}

// Check if role exists
$stmt = $db->prepare('SELECT id, name FROM peran_jenis WHERE id = :id LIMIT 1');
$stmt->execute([':id' => $peran_jenis_id]);
$role = $stmt->fetch();
if (!$role) {
    $response['message'] = 'Peran tidak valid';
    echo json_encode($response);
    exit;
}

try {
    // Begin transaction
    $db->beginTransaction();

    // Hash password
    $password_hash = password_hash($password, PASSWORD_DEFAULT);

    // Insert user data
    $stmt = $db->prepare('
        INSERT INTO pengguna (
            username, sandi_hash, sumber_pengguna_id, status, dibuat_pada
        ) VALUES (
            :username, :sandi_hash, :sumber_pengguna_id, :status, NOW()
        )
    ');

    $stmt->execute([
        ':username' => $username,
        ':sandi_hash' => $password_hash,
        ':sumber_pengguna_id' => 1, // Default value, adjust if needed
        ':status' => 'active'
    ]);

    $user_id = $db->lastInsertId();

    // Assign role to user
    $stmt = $db->prepare('
        INSERT INTO pengguna_peran (
            pengguna_id, peran_jenis_id, assigned_at
        ) VALUES (
            :pengguna_id, :peran_jenis_id, NOW()
        )
    ');

    $stmt->execute([
        ':pengguna_id' => $user_id,
        ':peran_jenis_id' => $peran_jenis_id
    ]);

    // For super admin or admin roles, we might need additional setup
    // For now, basic user creation is complete

    // Commit transaction
    $db->commit();

    $response['success'] = true;
    $response['message'] = 'Berhasil daftar. Silakan login.';
    $response['redirect'] = '/ksp_mono/login.php';

} catch (Exception $e) {
    // Rollback transaction on error
    $db->rollBack();
    error_log('User registration error: ' . $e->getMessage());
    $response['message'] = 'Terjadi kesalahan dalam penyimpanan data';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
