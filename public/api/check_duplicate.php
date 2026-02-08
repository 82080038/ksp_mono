<?php
require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/helpers.php';
require_once __DIR__ . '/../../app/validation_constants.php';

header('Content-Type: application/json');

// Only allow GET requests
if ($_SERVER['REQUEST_METHOD'] !== 'GET') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

// Validate required parameters
$field = $_GET['field'] ?? '';
$value = trim($_GET['value'] ?? '');

if (!$field || !$value) {
    echo json_encode(['success' => false, 'message' => 'Field dan value wajib diisi']);
    exit;
}

// Validate field type
$allowedFields = ['nama_koperasi', 'admin_hp', 'admin_username'];
if (!in_array($field, $allowedFields)) {
    echo json_encode(['success' => false, 'message' => 'Field tidak valid']);
    exit;
}

try {
    $db = Database::conn();

    $exists = false;
    $message = '';

    switch ($field) {
        case 'nama_koperasi':
            // Check if cooperative name exists
            $stmt = $db->prepare('SELECT id FROM koperasi_tenant WHERE nama_koperasi = ? LIMIT 1');
            $stmt->execute([$value]);
            $exists = $stmt->fetch() ? true : false;
            $message = $exists ? 'Nama koperasi sudah terdaftar' : '';
            break;

        case 'admin_hp':
            // Clean phone number for checking
            $cleanHp = unmask_phone_number($value);
            if (!validate_indonesian_phone($cleanHp)) {
                echo json_encode(['success' => false, 'message' => 'Format nomor HP tidak valid']);
                exit;
            }
            $stmt = $db->prepare('SELECT id FROM pengguna WHERE hp = ? LIMIT 1');
            $stmt->execute([$cleanHp]);
            $exists = $stmt->fetch() ? true : false;
            $message = $exists ? 'Nomor HP sudah terdaftar' : '';
            break;

        case 'admin_username':
            // Basic validation
            if (strlen($value) < MIN_USERNAME_LENGTH || strlen($value) > MAX_USERNAME_LENGTH) {
                echo json_encode(['success' => false, 'message' => 'Panjang username tidak valid']);
                exit;
            }
            $stmt = $db->prepare('SELECT id FROM pengguna WHERE username = ? LIMIT 1');
            $stmt->execute([$value]);
            $exists = $stmt->fetch() ? true : false;
            $message = $exists ? 'Username sudah terdaftar' : '';
            break;
    }

    echo json_encode([
        'success' => true,
        'exists' => $exists,
        'message' => $message
    ]);

} catch (Exception $e) {
    error_log('Duplicate check error: ' . $e->getMessage());
    echo json_encode(['success' => false, 'message' => 'Terjadi kesalahan server']);
}
?>
