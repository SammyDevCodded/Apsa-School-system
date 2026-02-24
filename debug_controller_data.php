<?php
// Define application paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('DATABASE_PATH', ROOT_PATH . '/database');
define('PUBLIC_PATH', ROOT_PATH . '/public'); // Mock public path

// Autoload classes
require_once ROOT_PATH . '/vendor/autoload.php';

// Load configuration
require_once CONFIG_PATH . '/config.php';

// Mock Session
$_SESSION['user'] = ['id' => 1, 'username' => 'admin', 'role' => 'admin'];

// Instantiate Controller
$controller = new \App\Controllers\ReportsController();

// Use Reflection to access private method getAnalyticsFilters
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('getAnalyticsFilters');
$method->setAccessible(true);

echo "--- Invoking getAnalyticsFilters via Reflection ---\n";
$filters = $method->invoke($controller);

if (!empty($filters['exams'])) {
    echo "First Exam Data Keys:\n";
    $exam = $filters['exams'][0];
    // Filter to show only relevant keys
    $relevant = array_intersect_key($exam, array_flip(['id', 'name', 'date', 'description']));
    var_export($relevant);
} else {
    echo "No exams found.\n";
}
