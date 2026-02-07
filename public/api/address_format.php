<?php
require_once __DIR__ . '/../../app/bootstrap.php';

// API endpoint for address formatting
header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: POST');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    echo json_encode(['success' => false, 'message' => 'Method not allowed']);
    exit;
}

$action = $_POST['action'] ?? '';

if ($action === 'format_address') {
    $components = $_POST['components'] ?? [];

    // Include helpers to get the format_indonesian_address function
    require_once __DIR__ . '/../../app/helpers.php';

    try {
        $formatted = format_indonesian_address($components);
        echo json_encode([
            'success' => true,
            'formatted_address' => $formatted
        ]);
    } catch (Exception $e) {
        echo json_encode([
            'success' => false,
            'message' => 'Error formatting address: ' . $e->getMessage()
        ]);
    }
} else {
    echo json_encode(['success' => false, 'message' => 'Invalid action']);
}

exit;
?>
