<?php
require_once __DIR__ . '/../../../app/bootstrap.php';

// Check permissions
if (!has_permission('manage_cooperative')) {
    header('Location: /dashboard.php');
    exit;
}

// Include the cooperative details component
require __DIR__ . '/../../pages/dashboard/complete_coop_details.php';
