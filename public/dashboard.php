<?php
// Auto-login handler for cooperative registration
require_once __DIR__ . '/../app/bootstrap.php';
require_once __DIR__ . '/../app/Auth.php';

session_start();

// Use Auth class for authentication
$auth = new Auth();
if (!$auth->check()) {
    // If not logged in, redirect to login page
    header('Location: /ksp_mono/public/login.php');
    exit;
}

// User is logged in, redirect to main dashboard
header('Location: /ksp_mono/public/pages/dashboard/index.php');
exit;