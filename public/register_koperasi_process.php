<?php
// register_koperasi_process.php - Backend processing for cooperative registration

// Start timing
$startTime = microtime(true);

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/helpers.php';
require_once __DIR__ . '/../app/validation_constants.php';

// Ensure logs directory exists
if (!file_exists('/var/www/html/ksp_mono/logs')) {
    mkdir('/var/www/html/ksp_mono/logs', 0755, true);
}

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
$requiredFields = [
    'jenis_koperasi', 'nama_koperasi', 'admin_nama', 'admin_hp', 
    'admin_username', 'admin_password', 'admin_password_confirm',
    'province_id', 'regency_id', 'district_id', 'village_id', 'nama_jalan'
];
foreach ($requiredFields as $field) {
    if (empty($_POST[$field])) {
        $response['message'] = "Field {$field} wajib diisi";
        echo json_encode($response);
        exit;
    }
}

// Sanitize and validate input data
$nama_koperasi = format_uppercase(trim($_POST['nama_koperasi']));
$jenis_koperasi = $_POST['jenis_koperasi'];

// Address data
$province_id = $_POST['province_id'];
$regency_id = $_POST['regency_id'];
$district_id = $_POST['district_id'];
$village_id = $_POST['village_id'];
$nama_jalan = trim($_POST['nama_jalan']);
$nomor_rumah = trim($_POST['nomor_rumah'] ?? '');
$postal_code = trim($_POST['postal_code'] ?? '');

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

// Address validation
if (!is_numeric($province_id) || $province_id <= 0) {
    $response['message'] = 'Provinsi tidak valid';
    echo json_encode($response);
    exit;
}

if (!is_numeric($regency_id) || $regency_id <= 0) {
    $response['message'] = 'Kabupaten/Kota tidak valid';
    echo json_encode($response);
    exit;
}

if (!is_numeric($district_id) || $district_id <= 0) {
    $response['message'] = 'Kecamatan tidak valid';
    echo json_encode($response);
    exit;
}

if (!is_numeric($village_id) || $village_id <= 0) {
    $response['message'] = 'Kelurahan/Desa tidak valid';
    echo json_encode($response);
    exit;
}

if (strlen($nama_jalan) < 3) {
    $response['message'] = 'Nama jalan minimal 3 karakter';
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

// Check for duplicate phone number
$stmt = $db->prepare('SELECT id FROM pengguna WHERE hp = ?');
$stmt->execute([$admin_hp]);
if ($stmt->fetch()) {
    $response['message'] = 'Nomor HP sudah terdaftar';
    
    // Log the error
    $logStmt = $db->prepare("INSERT INTO form_validation_errors 
        (input_value, field_type, error_type, user_ip)
        VALUES (?, 'phone', 'duplicate', ?)");
    $logStmt->execute([
        $admin_hp,
        $_SERVER['REMOTE_ADDR']
    ]);
    
    echo json_encode($response);
    exit;
}

// Check for duplicate username
$stmt = $db->prepare('SELECT id FROM pengguna WHERE username = ?');
$stmt->execute([$admin_username]);
if ($stmt->fetch()) {
    $response['message'] = 'Username sudah terdaftar';
    
    // Log the error
    $logStmt = $db->prepare("INSERT INTO form_validation_errors 
        (input_value, field_type, error_type, user_ip)
        VALUES (?, 'username', 'duplicate', ?)");
    $logStmt->execute([
        $admin_username,
        $_SERVER['REMOTE_ADDR']
    ]);
    
    echo json_encode($response);
    exit;
}

// Temporary relaxed validation for development
if (strlen($admin_password) < MIN_PASSWORD_LENGTH) {
    $response['message'] = "Password minimal " . MIN_PASSWORD_LENGTH . " karakter (development mode)";
    echo json_encode($response);
    exit;
}

// Username validation - temporary relaxed rules
if (strlen($admin_username) < MIN_USERNAME_LENGTH) {
    $response['message'] = "Username minimal " . MIN_USERNAME_LENGTH . " karakter (development mode)";
    echo json_encode($response);
    exit;
}

if ($admin_password !== $admin_password_confirm) {
    $response['message'] = 'Konfirmasi password tidak cocok';
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

    // Get address names for storage
    $alamat_db = app_config('alamat_db');
    $dsn = sprintf('mysql:host=%s;dbname=%s;charset=%s', $alamat_db['host'], $alamat_db['name'], $alamat_db['charset']);
    $alamat_pdo = new PDO($dsn, $alamat_db['user'], $alamat_db['pass'], [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_ASSOC,
    ]);

    // Get address names
    $stmt = $alamat_pdo->prepare('SELECT name FROM provinsi WHERE id = ?');
    $stmt->execute([$province_id]);
    $province_name = $stmt->fetchColumn();

    $stmt = $alamat_pdo->prepare('SELECT name FROM kabkota WHERE id = ?');
    $stmt->execute([$regency_id]);
    $regency_name = $stmt->fetchColumn();

    $stmt = $alamat_pdo->prepare('SELECT name FROM kecamatan WHERE id = ?');
    $stmt->execute([$district_id]);
    $district_name = $stmt->fetchColumn();

    $stmt = $alamat_pdo->prepare('SELECT name, postal_code FROM kelurahan WHERE id = ?');
    $stmt->execute([$village_id]);
    $village_data = $stmt->fetch();
    $village_name = $village_data['name'];
    $village_postal = $village_data['postal_code'] ?? $postal_code;

    // Format complete address
    $alamat_lengkap = trim("{$nama_jalan} {$nomor_rumah}, {$village_name}, {$district_name}, {$regency_name}, {$province_name}, {$village_postal}");

    // Step 1: Create admin user terlebih dahulu
    $password_hash = password_hash($admin_password, PASSWORD_DEFAULT);

    $stmt = $db->prepare('INSERT INTO pengguna (username, sandi_hash, sumber_pengguna_id, status, hp, dibuat_pada) VALUES (:username, :sandi_hash, :sumber_pengguna_id, :status, :hp, NOW())');

    $stmt->execute([
        ':username' => $admin_username,
        ':sandi_hash' => $password_hash,
        ':sumber_pengguna_id' => 1, // Default admin source
        ':status' => 'active', // AUTO ACTIVATE for development
        ':hp' => $admin_hp
    ]);

    $user_id = $db->lastInsertId();

    // Step 2: Insert detailed person data ke tabel orang
    $stmt = $db->prepare('
        INSERT INTO orang (
            pengguna_id, nama_lengkap, nama_depan, nama_tengah, nama_belakang,
            hp, hp_alternatif, email, nik, kewarganegaraan, agama,
            jenis_kelamin, tempat_lahir, tanggal_lahir,
            alamat_lengkap, province_id, regency_id, district_id, village_id,
            nama_jalan, nomor_rumah, rt, rw, postal_code,
            pekerjaan, instansi, jabatan, catatan,
            dibuat_oleh, dibuat_pada
        ) VALUES (
            :pengguna_id, :nama_lengkap, :nama_depan, :nama_tengah, :nama_belakang,
            :hp, :hp_alternatif, :email, :nik, :kewarganegaraan, :agama,
            :jenis_kelamin, :tempat_lahir, :tanggal_lahir,
            :alamat_lengkap, :province_id, :regency_id, :district_id, :village_id,
            :nama_jalan, :nomor_rumah, :rt, :rw, :postal_code,
            :pekerjaan, :instansi, :jabatan, :catatan,
            :dibuat_oleh, :dibuat_pada
        )
    ');

    // Parse nama lengkap untuk breakdown
    $nama_parts = explode(' ', $admin_nama);
    $nama_depan = $nama_parts[0] ?? '';
    $nama_tengah = count($nama_parts) > 2 ? implode(' ', array_slice($nama_parts, 1, -1)) : '';
    $nama_belakang = count($nama_parts) > 1 ? end($nama_parts) : '';

    $stmt->execute([
        ':pengguna_id' => $user_id,
        ':nama_lengkap' => $admin_nama,
        ':nama_depan' => $nama_depan,
        ':nama_tengah' => $nama_tengah,
        ':nama_belakang' => $nama_belakang,
        ':hp' => $admin_hp,
        ':hp_alternatif' => null,
        ':email' => null,
        ':nik' => null,
        ':kewarganegaraan' => null,
        ':agama' => null,
        ':jenis_kelamin' => null,
        ':tempat_lahir' => null,
        ':tanggal_lahir' => null,
        ':alamat_lengkap' => $alamat_lengkap,
        ':province_id' => $province_id,
        ':regency_id' => $regency_id,
        ':district_id' => $district_id,
        ':village_id' => $village_id,
        ':nama_jalan' => $nama_jalan,
        ':nomor_rumah' => $nomor_rumah,
        ':rt' => null, // Bisa ditambahkan ke form jika diperlukan
        ':rw' => null, // Bisa ditambahkan ke form jika diperlukan
        ':postal_code' => $village_postal,
        ':pekerjaan' => 'Administrator Koperasi',
        ':instansi' => $nama_koperasi,
        ':jabatan' => 'Administrator',
        ':catatan' => 'Dibuat otomatis saat registrasi koperasi',
        ':dibuat_oleh' => $user_id,
        ':dibuat_pada' => date('Y-m-d H:i:s')
    ]);

    // Step 3: Insert cooperative data
    $jenis_koperasi_json = json_encode([$jenis_koperasi]);

    $stmt = $db->prepare('INSERT INTO koperasi_tenant (nama_koperasi, jenis_koperasi, alamat_legal, provinsi_id, kabkota_id, kecamatan_id, kelurahan_id, status_badan_hukum, dibuat_pada) VALUES (:nama, :jenis, :alamat_legal, :province_id, :regency_id, :district_id, :village_id, "belum_terdaftar", NOW())');

    $stmt->execute([
        ':nama' => $nama_koperasi,
        ':jenis' => $jenis_koperasi_json,
        ':alamat_legal' => $alamat_lengkap,
        ':province_id' => $province_id,
        ':regency_id' => $regency_id,
        ':district_id' => $district_id,
        ':village_id' => $village_id
    ]);

    $cooperative_id = $db->lastInsertId();

    // Assign admin role to user (koperasi administrator)
    $stmt = $db->prepare('
        INSERT INTO pengguna_peran (
            pengguna_id, peran_jenis_id, assigned_at
        ) VALUES (
            :pengguna_id, :peran_jenis_id, NOW()
        )
    ');

    $stmt->execute([
        ':pengguna_id' => $user_id,
        ':peran_jenis_id' => 2 // Admin role (Administrator/Pengurus)
    ]);

    // Grant appropriate permissions for admin role
    $stmt = $db->prepare('
        INSERT INTO pengguna_izin_peran (peran_jenis_id, izin_modul_id, assigned_at)
        SELECT 2, id, NOW() FROM izin_modul
        WHERE is_active = 1
    ');
    $stmt->execute();

    // Store cooperative info in session for immediate access
    $_SESSION['user'] = [
        'id' => $user_id,
        'username' => $admin_username,
        'status' => 'active'
    ];
    
    // Additional session data for registration
    $_SESSION['cooperative_id'] = $cooperative_id;
    $_SESSION['user_id'] = $user_id;
    $_SESSION['username'] = $admin_username;
    $_SESSION['nama_lengkap'] = $admin_nama;
    $_SESSION['hp'] = $admin_hp;
    $_SESSION['role'] = 'admin'; // Administrator/Pengurus role
    $_SESSION['role_id'] = 2; // Admin role ID
    $_SESSION['permissions'] = ['all_modules']; // Development mode - all permissions
    $_SESSION['alamat_lengkap'] = $alamat_lengkap;
    $_SESSION['orang_id'] = $user_id; // Same as user_id for admin registration

    // Commit transaction
    $db->commit();

    $response['success'] = true;
    $response['message'] = 'Koperasi dan admin berhasil didaftarkan. Silakan login untuk melanjutkan.';
    $response['redirect'] = '/ksp_mono/public/login.php';
    $response['cooperative_id'] = $cooperative_id;
    $response['user_id'] = $user_id;
    $response['auto_login'] = false; // Tidak auto-login, redirect ke login

} catch (Exception $e) {
    // Rollback transaction on error
    $db->rollBack();
    error_log('Cooperative registration error: ' . $e->getMessage());
    $response['message'] = 'Terjadi kesalahan dalam penyimpanan data: ';
}

// Log performance
$duration = round((microtime(true) - $startTime) * 1000, 2);
file_put_contents(
    '/var/www/html/ksp_mono/logs/form_performance.log', 
    date('Y-m-d H:i:s') . " | register_koperasi | {$duration}ms\n", 
    FILE_APPEND
);

// Return JSON response
header('Content-Type: application/json');
echo json_encode($response);
?>
