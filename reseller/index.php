<?php
/**
 * E-Benta Reseller Portal Entry Point
 * Dhendhen Beauty Products & Boutique Dropshipping System
 */

// Session Settings (must be set before session_start)
ini_set('session.gc_maxlifetime', 3600); // 1 hour
ini_set('session.cookie_lifetime', 3600);

session_start();

// Define base paths
define('BASE_PATH', dirname(__FILE__));
define('BASE_URL', 'http://localhost/E_Benta_dropshipping_System/reseller/');
define('ADMIN_URL', 'http://localhost/E_Benta_dropshipping_System/admin/');

// Autoload classes
spl_autoload_register(function($class) {
    $paths = [
        BASE_PATH . '/app/models/' . $class . '.php',
        BASE_PATH . '/app/controllers/' . $class . '.php',
        BASE_PATH . '/app/core/' . $class . '.php',
        BASE_PATH . '/../admin/app/models/' . $class . '.php', // Shared models
    ];
    
    foreach ($paths as $path) {
        if (file_exists($path)) {
            require_once $path;
            return;
        }
    }
});

// Load configuration
require_once BASE_PATH . '/app/config/config.php';
require_once BASE_PATH . '/app/config/database.php';

// Initialize application
$app = new App();
