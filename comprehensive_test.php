<?php
// Comprehensive test for attendance report functionality
require_once 'vendor/autoload.php';

// Define application paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('DATABASE_PATH', ROOT_PATH . '/database');
define('RESOURCES_PATH', ROOT_PATH . '/resources');
define('ROUTES_PATH', ROOT_PATH . '/routes');

// Load configuration
require_once CONFIG_PATH . '/config.php';

use App\Controllers\AttendanceController;

// Start session
session_start();

// Create a mock user session for testing
$_SESSION['user'] = [
    'id' => 1,
    'username' => 'admin',
    'role_id' => 1
];

// Test different scenarios
echo "=== Comprehensive Attendance Report Test ===\n";

// Test 1: Default report (current month)
echo "\n--- Test 1: Default report ---\n";
$_GET = [];
ob_start();
try {
    $controller = new AttendanceController();
    $controller->report();
    $output = ob_get_clean();
    echo "SUCCESS: Default report generated\n";
} catch (Exception $e) {
    ob_get_clean();
    echo "ERROR: " . $e->getMessage() . "\n";
}

// Test 2: Report with custom date range
echo "\n--- Test 2: Custom date range ---\n";
$_GET = [
    'start_date' => '2025-10-01',
    'end_date' => '2025-10-31'
];
ob_start();
try {
    $controller = new AttendanceController();
    $controller->report();
    $output = ob_get_clean();
    echo "SUCCESS: Custom date range report generated\n";
} catch (Exception $e) {
    ob_get_clean();
    echo "ERROR: " . $e->getMessage() . "\n";
}

// Test 3: Report with search term
echo "\n--- Test 3: Report with search term ---\n";
$_GET = [
    'start_date' => '2025-10-01',
    'end_date' => '2025-10-31',
    'search' => 'John'
];
ob_start();
try {
    $controller = new AttendanceController();
    $controller->report();
    $output = ob_get_clean();
    echo "SUCCESS: Search term report generated\n";
} catch (Exception $e) {
    ob_get_clean();
    echo "ERROR: " . $e->getMessage() . "\n";
}

// Test 4: Report with class filter
echo "\n--- Test 4: Report with class filter ---\n";
$_GET = [
    'start_date' => '2025-10-01',
    'end_date' => '2025-10-31',
    'class_id' => '1'
];
ob_start();
try {
    $controller = new AttendanceController();
    $controller->report();
    $output = ob_get_clean();
    echo "SUCCESS: Class filter report generated\n";
} catch (Exception $e) {
    ob_get_clean();
    echo "ERROR: " . $e->getMessage() . "\n";
}

// Test 5: Report with both search and class filter
echo "\n--- Test 5: Report with search and class filter ---\n";
$_GET = [
    'start_date' => '2025-10-01',
    'end_date' => '2025-10-31',
    'search' => 'John',
    'class_id' => '1'
];
ob_start();
try {
    $controller = new AttendanceController();
    $controller->report();
    $output = ob_get_clean();
    echo "SUCCESS: Combined filters report generated\n";
} catch (Exception $e) {
    ob_get_clean();
    echo "ERROR: " . $e->getMessage() . "\n";
}

echo "\n=== All tests completed ===\n";