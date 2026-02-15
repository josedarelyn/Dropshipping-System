<?php
/**
 * Reseller Portal Configuration
 */

// Application Info
define('APP_NAME', 'E-Benta Reseller Portal');
define('APP_VERSION', '1.0.0');

// Site Settings
define('SITE_NAME', 'Dhendhen Beauty Products & Boutique');
define('SITE_TAGLINE', 'Reseller Dashboard');

// File Upload Settings
define('MAX_FILE_SIZE', 2097152); // 2MB
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/jpg', 'image/png', 'image/gif']);
define('UPLOAD_PATH', BASE_PATH . '/public/uploads/');

// Pagination
define('ITEMS_PER_PAGE', 10);

// Timezone
date_default_timezone_set('Asia/Manila');

// Error Reporting (Development)
error_reporting(E_ALL);
ini_set('display_errors', 1);
