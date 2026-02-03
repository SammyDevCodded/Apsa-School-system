<?php
// Futuristic School Management ERP System - Localhost Edition
// Main Entry Point with Debugging

// Define application paths
define('ROOT_PATH', dirname(__DIR__));
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', __DIR__);
define('CONFIG_PATH', ROOT_PATH . '/config');
define('DATABASE_PATH', ROOT_PATH . '/database');
define('RESOURCES_PATH', ROOT_PATH . '/resources');
define('ROUTES_PATH', ROOT_PATH . '/routes');

// Autoload classes
require_once ROOT_PATH . '/vendor/autoload.php';

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Debug information
/*
echo "<pre>";
echo "ROOT_PATH: " . ROOT_PATH . "\n";
echo "REQUEST_URI: " . ($_SERVER['REQUEST_URI'] ?? 'Not set') . "\n";
echo "SCRIPT_NAME: " . ($_SERVER['SCRIPT_NAME'] ?? 'Not set') . "\n";
echo "SCRIPT_FILENAME: " . ($_SERVER['SCRIPT_FILENAME'] ?? 'Not set') . "\n";
echo "Session status: " . session_status() . "\n";
echo "Session ID: " . (session_id() ?? 'Not set') . "\n";
echo "Session data: " . print_r($_SESSION ?? 'No session', true) . "\n";
echo "</pre>";
*/

// Initialize the application
$app = new App\Core\Application();

// Run the application
$app->run();