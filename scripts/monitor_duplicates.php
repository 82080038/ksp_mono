<?php
require_once __DIR__ . '/../app/bootstrap.php';

try {
    // Track duplicate field attempts
    $db = Database::conn('koperasi_db');
    $stmt = $db->query("SELECT 
        COUNT(*) as attempt_count, 
        input_value,
        field_type
    FROM form_validation_errors 
    WHERE error_type = 'duplicate'
    GROUP BY input_value, field_type
    HAVING attempt_count > 5
    ORDER BY attempt_count DESC");

    $results = $stmt->fetchAll();

    if (!empty($results)) {
        // Send alert
        mail(
            'admin@kspmono.com', 
            'Duplicate Field Attempt Alert', 
            print_r($results, true)
        );
    }
} catch (PDOException $e) {
    error_log("Monitoring error: " . $e->getMessage());
}
