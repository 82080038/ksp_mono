<?php
// Bootstrap aplikasi sederhana

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Load helpers
require_once __DIR__ . '/helpers.php';

// Mulai session lebih awal
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pengaturan error sesuai environment
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

// Autoload classes
spl_autoload_register(function ($class) {
    $file = __DIR__ . '/' . str_replace('\\', '/', $class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

// Helper untuk akses config
function app_config(string $key, $default = null) {
    global $config;
    return $config[$key] ?? $default;
}
