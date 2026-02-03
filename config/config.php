<?php
// Define ROOT_PATH if not already defined
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

// Database configuration
define('DB_HOST', 'localhost');
define('DB_NAME', 'school_erp');
define('DB_USER', 'root');
define('DB_PASS', '');
define('DB_CHARSET', 'utf8mb4');

// Application configuration
define('APP_NAME', 'APSA-ERP');
define('APP_VERSION', '1.0.0');
define('APP_ENV', 'local');
define('APP_DEBUG', true);
define('APP_URL', 'http://localhost:8000');

// Security configuration
define('SECRET_KEY', 'your-secret-key-here');
define('SESSION_TIMEOUT', 3600); // 1 hour

// File paths
define('UPLOAD_PATH', ROOT_PATH . '/storage/uploads');
define('BACKUP_PATH', ROOT_PATH . '/storage/backups');

// Date and time settings
date_default_timezone_set('UTC');