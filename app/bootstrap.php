<?php
// Bootstrap aplikasi sederhana

// Load configuration
require_once __DIR__ . '/../config/config.php';

// Mulai session lebih awal
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

// Pengaturan error sesuai environment
if (($config['app']['env'] ?? 'production') === 'development' && ($config['app']['debug'] ?? false)) {
    ini_set('display_errors', 1);
    ini_set('display_startup_errors', 1);
    error_reporting(E_ALL);
} else {
    ini_set('display_errors', 0);
    error_reporting(E_ALL & ~E_NOTICE & ~E_STRICT & ~E_DEPRECATED);
}

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
