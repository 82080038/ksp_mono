<?php
require_once __DIR__ . '/../../app/bootstrap.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

// Validate session and permissions
if (!isset($_SESSION['user_id']) || !has_permission('manage_cooperative')) {
    http_response_code(403);
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$response = ['success' => false];
$coop_id = $_SESSION['cooperative_id'];

// Validate and sanitize inputs
$status_badan_hukum = $_POST['status_badan_hukum'] ?? 'belum_terdaftar';
$nomor_badan_hukum = isset($_POST['nomor_badan_hukum']) ? preg_replace('/\D/', '', $_POST['nomor_badan_hukum']) : null;
$kontak = unmask_phone_number($_POST['kontak'] ?? '');

// Validate based on status
if (($status_badan_hukum === 'terdaftar' || $status_badan_hukum === 'badan_hukum') && 
    (!validate_badan_hukum_koperasi($nomor_badan_hukum, $status_badan_hukum))) {
    $response['message'] = 'Nomor Badan Hukum harus 12 digit';
    echo json_encode($response);
    exit;
}

if (!validate_indonesian_phone($kontak)) {
    $response['message'] = 'Format kontak tidak valid';
    echo json_encode($response);
    exit;
}

try {
    $db->beginTransaction();
    
    $stmt = $db->prepare('UPDATE koperasi_tenant SET 
        status_badan_hukum = ?, 
        nomor_badan_hukum = ?, 
        kontak_resmi = ?,
        diperbarui_pada = NOW()
        WHERE id = ?');
    
    $stmt->execute([
        $status_badan_hukum,
        $nomor_badan_hukum,
        $kontak,
        $coop_id
    ]);
    
    $db->commit();
    $response['success'] = true;
    $response['message'] = 'Detail koperasi berhasil diperbarui';
} catch (Exception $e) {
    $db->rollBack();
    $response['message'] = 'Gagal menyimpan: ' . $e->getMessage();
}

echo json_encode($response);
