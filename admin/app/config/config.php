<?php
/**
 * E-Benta Configuration File
 */

// Application Settings
define('APP_NAME', 'E-Benta - Dhendhen Beauty Products & Boutique');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'development'); // development, production

// Database Configuration
define('DB_HOST', 'localhost');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_NAME', 'ebenta_database');
define('DB_CHARSET', 'utf8mb4');

// Session Configuration
define('SESSION_TIMEOUT', 3600); // 1 hour

// File Upload Settings
define('MAX_FILE_SIZE', 5242880); // 5MB
define('UPLOAD_PATH', BASE_PATH . '/public/uploads/');
define('ALLOWED_IMAGE_TYPES', ['image/jpeg', 'image/png', 'image/gif', 'image/webp']);

// Pagination
define('RECORDS_PER_PAGE', 10);

// Email Configuration (for notifications)
define('SMTP_HOST', 'smtp.gmail.com');
define('SMTP_PORT', 587);
define('SMTP_USER', 'your-email@gmail.com');
define('SMTP_PASS', 'your-password');
define('SMTP_FROM', 'noreply@ebenta.com');
define('SMTP_FROM_NAME', 'E-Benta System');

// GCash Configuration
define('GCASH_MERCHANT_ID', 'your-merchant-id');
define('GCASH_API_KEY', 'your-api-key');
define('GCASH_API_SECRET', 'your-api-secret');

// OTP Configuration
define('OTP_LENGTH', 6);
define('OTP_EXPIRY', 300); // 5 minutes

// Commission Settings
define('DEFAULT_COMMISSION_RATE', 15); // 15%

// Theme Colors
define('PRIMARY_COLOR', '#ff69b4');
define('SECONDARY_COLOR', '#ee82ee');
define('ACCENT_COLOR', '#9370db');
