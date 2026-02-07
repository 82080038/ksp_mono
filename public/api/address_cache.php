<?php
/**
 * Address Cache API - Check for address data changes using max_date tracking
 * More efficient than version-based approach
 */

require_once __DIR__ . '/../../app/bootstrap.php';
require_once __DIR__ . '/../../app/helpers.php';

header('Content-Type: application/json');
header('Access-Control-Allow-Origin: *');
header('Access-Control-Allow-Methods: GET, OPTIONS');
header('Access-Control-Allow-Headers: Content-Type');

if ($_SERVER['REQUEST_METHOD'] === 'OPTIONS') {
    exit(0);
}

try {
    // Get current max dates
    $max_dates = get_address_max_dates();

    // Check if specific table max dates are requested
    $check_table = isset($_GET['table']) ? $_GET['table'] : null;
    $client_max_date = isset($_GET['max_date']) ? $_GET['max_date'] : null;

    if ($check_table && isset($max_dates[$check_table])) {
        $server_max_date = $max_dates[$check_table]['max_date'];
        $has_changes = $server_max_date !== $client_max_date;

        echo json_encode([
            'success' => true,
            'table' => $check_table,
            'server_max_date' => $server_max_date,
            'client_max_date' => $client_max_date,
            'has_changes' => $has_changes,
            'record_count' => $max_dates[$check_table]['record_count'],
            'last_checked' => $max_dates[$check_table]['last_checked']
        ]);
    } else {
        // Return all max dates
        echo json_encode([
            'success' => true,
            'max_dates' => $max_dates,
            'timestamp' => date('c')
        ]);
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => 'Error checking address cache max dates',
        'error' => $e->getMessage()
    ]);
}
?>
