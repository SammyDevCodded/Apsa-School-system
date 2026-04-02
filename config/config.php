<?php
// Define ROOT_PATH if not already defined
if (!defined('ROOT_PATH')) {
    define('ROOT_PATH', dirname(__DIR__));
}

// Database configuration
define('DB_HOST', getenv('DB_HOST') ?: (getenv('MYSQLHOST') ?: '127.0.0.1'));
define('DB_NAME', getenv('DB_NAME') ?: (getenv('MYSQLDATABASE') ?: 'school_erp'));
define('DB_USER', getenv('DB_USER') ?: (getenv('MYSQLUSER') ?: 'root'));
define('DB_PASS', getenv('DB_PASS') !== false ? getenv('DB_PASS') : (getenv('MYSQLPASSWORD') ?: ''));
define('DB_PORT', getenv('DB_PORT') ?: (getenv('MYSQLPORT') ?: '3306'));
define('DB_CHARSET', 'utf8mb4');

// Application configuration
define('APP_NAME', getenv('APP_NAME') ?: 'APSA-ERP');
define('APP_VERSION', '1.0.0');
define('APP_ENV', getenv('APP_ENV') ?: 'local');
define('APP_DEBUG', getenv('APP_DEBUG') !== false ? filter_var(getenv('APP_DEBUG'), FILTER_VALIDATE_BOOLEAN) : true);
define('APP_URL', getenv('APP_URL') ?: 'http://localhost:8000');

// Security configuration
define('SECRET_KEY', 'your-secret-key-here');
define('SESSION_TIMEOUT', 3600); // 1 hour

// File paths
define('UPLOAD_PATH', ROOT_PATH . '/storage/uploads');
define('BACKUP_PATH', ROOT_PATH . '/storage/backups');

// Date and time settings
date_default_timezone_set('UTC');