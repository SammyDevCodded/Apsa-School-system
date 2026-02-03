<?php
// Test the AttendanceController report method
require_once 'vendor/autoload.php';
require_once 'config/config.php';

// Start session
session_start();

// Mock the controller and test the report method
use App\Controllers\AttendanceController;
use App\Core\Controller;

// Create a mock controller to test the report method
class TestAttendanceController extends AttendanceController {
    private $testGetParams = [];
    
    public function setGetParams($params) {
        $this->testGetParams = $params;
    }
    
    protected function get($key, $default = null) {
        return $this->testGetParams[$key] ?? $default;
    }
    
    // Override the view method to capture the data
    protected function view($view, $data = []) {
        echo "View: $view\n";
        echo "Data keys: " . implode(', ', array_keys($data)) . "\n";
        echo "Number of classes: " . (isset($data['summaryByClass']) ? count($data['summaryByClass']) : 0) . "\n";
        if (isset($data['summaryByClass'])) {
            foreach ($data['summaryByClass'] as $className => $students) {
                echo "Class: $className, Students: " . count($students) . "\n";
            }
        }
        return true;
    }
    
    // Override redirect to prevent actual redirect
    protected function redirect($url) {
        echo "Redirect to: $url\n";
        return true;
    }
}

// Test the report method
$controller = new TestAttendanceController();

// Test 1: Default report (current month)
echo "=== Test 1: Default report ===\n";
$controller->setGetParams([]);
$controller->report();

echo "\n=== Test 2: Report with date range ===\n";
$controller->setGetParams([
    'start_date' => date('Y-m-01'),
    'end_date' => date('Y-m-t')
]);
$controller->report();

echo "\n=== Test 3: Report with search term ===\n";
$controller->setGetParams([
    'start_date' => date('Y-m-01'),
    'end_date' => date('Y-m-t'),
    'search' => 'John'
]);
$controller->report();

echo "\n=== Test 4: Report with class filter ===\n";
$controller->setGetParams([
    'start_date' => date('Y-m-01'),
    'end_date' => date('Y-m-t'),
    'class_id' => 1
]);
$controller->report();