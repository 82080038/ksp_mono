<?php
// Staging environment configuration

// Database Configurations
$config = [
    'koperasi_db' => [
        'host' => 'localhost',
        'name' => 'koperasi_db',
        'user' => 'root',
        'pass' => 'root',
        'charset' => 'utf8mb4'
    ],
    'alamat_db' => [
        'host' => 'localhost',
        'name' => 'alamat_db',
        'user' => 'root',
        'pass' => 'root',
        'charset' => 'utf8mb4'
    ]
];

// Validation rules
// Note: More strict than dev but can be adjusted for testing
define('MIN_USERNAME_LENGTH', 4);
define('MAX_USERNAME_LENGTH', 20);
define('MIN_PASSWORD_LENGTH', 4);
define('PASSWORD_COMPLEXITY', true);

// Other staging-specific settings...
