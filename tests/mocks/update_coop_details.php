<?php
require_once __DIR__ . '/../../app/bootstrap.php';

// Use test database connection
$db = Database::conn();

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    exit;
}

$response = ['success' => false];

// Validate inputs
$status_badan_hukum = $_POST['status_badan_hukum'] ?? 'belum_terdaftar';
$nomor_badan_hukum = isset($_POST['nomor_badan_hukum']) ? preg_replace('/\D/', '', $_POST['nomor_badan_hukum']) : null;

// Test validation logic
if (($status_badan_hukum === 'terdaftar' || $status_badan_hukum === 'badan_hukum') && 
    (!validate_badan_hukum_koperasi($nomor_badan_hukum, $status_badan_hukum))) {
    $response['message'] = 'Nomor Badan Hukum harus 12 digit';
    echo json_encode($response);
    exit;
}

// Simulate successful update
$response['success'] = true;
$response['message'] = 'Test update successful';
echo json_encode($response);
