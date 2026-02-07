<?php
require_once __DIR__ . '/../app/helpers.php';

header('Content-Type: application/json');

$action = $_GET['action'] ?? '';
$input = $_GET['input'] ?? '';
$status = $_GET['status'] ?? null;

switch ($action) {
    case 'test_npwp':
        echo json_encode(validate_npwp($input));
        break;
    case 'test_badan_hukum':
        echo json_encode([
            'valid' => validate_badan_hukum_koperasi($input, $status),
            'message' => 'Badan Hukum validation result'
        ]);
        break;
    default:
        echo json_encode(['error' => 'Invalid test action']);
}
