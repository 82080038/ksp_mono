<?php
// register_koperasi_process.php - Backend processing for cooperative registration

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/helpers.php';

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
$requiredFields = ['jenis_koperasi', 'nama_koperasi', 'tanggal_pendirian', 'admin_nama', 'admin_hp', 'admin_username', 'admin_password', 'admin_password_confirm'];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        $response['message'] = "Field {$field} is required";
        echo json_encode($response);
        exit;
    }
}

// Sanitize and validate input data
$nama_koperasi = trim($_POST['nama_koperasi']);
$jenis_koperasi = $_POST['jenis_koperasi'];
$tanggal_pendirian = $_POST['tanggal_pendirian'] ? indonesian_date_to_db(trim($_POST['tanggal_pendirian'])) : null;

// Admin data
$admin_nama = trim($_POST['admin_nama']);
$admin_hp = unmask_phone_number(trim($_POST['admin_hp']));
$admin_username = trim($_POST['admin_username']);
$admin_password = $_POST['admin_password'];
$admin_password_confirm = $_POST['admin_password_confirm'];

// Validation
if (strlen($nama_koperasi) < 3) {
    $response['message'] = 'Nama koperasi minimal 3 karakter';
    echo json_encode($response);
    exit;
}

if (!validate_indonesian_date(format_indonesian_date($tanggal_pendirian))) {
    $response['message'] = 'Tanggal pendirian tidak valid';
    echo json_encode($response);
    exit;
}

// Admin validation
if (strlen($admin_nama) < 2) {
    $response['message'] = 'Nama admin minimal 2 karakter';
    echo json_encode($response);
    exit;
}

if (!validate_indonesian_phone($admin_hp)) {
    $response['message'] = 'Nomor HP admin harus berupa nomor telepon Indonesia yang valid';
    echo json_encode($response);
    exit;
}

$usernameRegex = '/^[a-zA-Z0-9_]{4,20}$/';
if (!preg_match($usernameRegex, $admin_username)) {
    $response['message'] = 'Username harus 4-20 karakter, hanya huruf, angka, dan underscore';
    echo json_encode($response);
    exit;
}

if (strlen($admin_password) < 8) {
    $response['message'] = 'Password minimal 8 karakter';
    echo json_encode($response);
    exit;
}

$passwordRegex = '/^(?=.*[a-z])(?=.*[A-Z])(?=.*\d)/';
if (!preg_match($passwordRegex, $admin_password)) {
    $response['message'] = 'Password harus mengandung huruf besar, kecil, dan angka';
    echo json_encode($response);
    exit;
}

if ($admin_password !== $admin_password_confirm) {
    $response['message'] = 'Konfirmasi password tidak cocok';
    echo json_encode($response);
    exit;
}

// Check if username already exists
$stmt = $db->prepare('SELECT id FROM pengguna WHERE username = :username LIMIT 1');
$stmt->execute([':username' => $admin_username]);
if ($stmt->fetch()) {
    $response['message'] = 'Username sudah terpakai';
    echo json_encode($response);
    exit;
}

// Check if cooperative name already exists
$stmt = $db->prepare('SELECT id FROM koperasi_tenant WHERE nama_koperasi = :nama LIMIT 1');
$stmt->execute([':nama' => $nama_koperasi]);
if ($stmt->fetch()) {
    $response['message'] = 'Nama koperasi sudah terdaftar';
    echo json_encode($response);
    exit;
}

try {
    // Begin transaction
    $db->beginTransaction();

    // Prepare jenis_koperasi as JSON
    $jenis_koperasi_json = json_encode([$jenis_koperasi]);

    // Insert cooperative data
    $stmt = $db->prepare('
        INSERT INTO koperasi_tenant (
            nama_koperasi, jenis_koperasi, tanggal_pendirian,
            status_badan_hukum, dibuat_pada
        ) VALUES (
            :nama, :jenis, :tanggal_pendirian,
            \'belum_terdaftar\', NOW()
        )
    ');

    $stmt->execute([
        ':nama' => $nama_koperasi,
        ':jenis' => $jenis_koperasi_json,
        ':tanggal_pendirian' => $tanggal_pendirian
    ]);

    $cooperative_id = $db->lastInsertId();

    // Create admin user for the cooperative
    $password_hash = password_hash($admin_password, PASSWORD_DEFAULT);
    
    $stmt = $db->prepare('
        INSERT INTO pengguna (
            username, sandi_hash, sumber_pengguna_id, status, dibuat_pada
        ) VALUES (
            :username, :sandi_hash, :sumber_pengguna_id, :status, NOW()
        )
    ');
    
    $stmt->execute([
        ':username' => $admin_username,
        ':sandi_hash' => $password_hash,
        ':sumber_pengguna_id' => 1, // Default admin source
        ':status' => 'active'
    ]);
    
    $user_id = $db->lastInsertId();
    
    // Assign admin role to user
    $stmt = $db->prepare('
        INSERT INTO pengguna_peran (
            pengguna_id, peran_jenis_id, assigned_at
        ) VALUES (
            :pengguna_id, :peran_jenis_id, NOW()
        )
    ');
    
    $stmt->execute([
        ':pengguna_id' => $user_id,
        ':peran_jenis_id' => 1 // Assuming 1 is admin role
    ]);

    // Assign admin role
    $stmt = $db->prepare('INSERT INTO pengguna_peran (pengguna_id, peran_jenis_id) VALUES (?, 2)');
    $stmt->execute([$user_id]);

    // Also set admin flag in session if registering current user
    if (!isset($_SESSION['user_id'])) {
        $_SESSION['permissions'] = ['manage_cooperative'];
    }
    
    // Commit transaction
    $db->commit();

    $response['success'] = true;
    $response['message'] = 'Koperasi dan admin berhasil didaftarkan. Silakan login dengan akun admin.';
    $response['redirect'] = '/ksp_mono/login.php';

} catch (Exception $e) {
    // Rollback transaction on error
    $db->rollBack();
    error_log('Cooperative registration error: ' . $e->getMessage());
    $response['message'] = 'Terjadi kesalahan dalam penyimpanan data';
}

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
