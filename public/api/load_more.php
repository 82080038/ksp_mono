<?php
require_once __DIR__ . '/../../app/ResponsiveDataService.php';
header('Content-Type: application/json');

$response = ['success' => false, 'data' => []];

try {
    $offset = (int) ($_GET['offset'] ?? 0);
    $limit = ResponsiveDataService::getLimits()['transactions'];
    
    $response['data'] = ResponsiveDataService::getData('transactions', [
        'order' => 'date DESC',
        'limit' => "$offset, $limit"
    ]);
    
    $response['success'] = true;
} catch (Exception $e) {
    $response['message'] = $e->getMessage();
}

echo json_encode($response);
