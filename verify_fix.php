<?php
// Verify that the fix for the ExamResultController is working correctly

// Simulate session
session_start();
$_SESSION['user'] = ['id' => 1, 'username' => 'admin'];

// Include necessary files
require_once 'vendor/autoload.php';
require_once 'config/config.php';

use App\Controllers\ExamResultController;

// Create a test controller
class TestExamResultController extends ExamResultController {
    public $output = '';
    public $headers = [];
    public $status_code = 200;
    
    // Override methods to capture output instead of sending headers
    protected function redirect($url) {
        // Don't actually redirect in test
    }
    
    protected function get($key, $default = null) {
        // Simulate GET parameters
        $params = [
            'exam_id' => 1,
            'class_id' => 1,
            'scale_id' => 1
        ];
        return $params[$key] ?? $default;
    }
    
    // Override header function to capture headers
    public function header($header, $replace = true, $response_code = 0) {
        $this->headers[] = $header;
        if ($response_code) {
            $this->status_code = $response_code;
        }
    }
    
    // Override echo to capture output
    public function echo($content) {
        $this->output .= $content;
    }
}

// Test getClassesByExam method
echo "Testing getClassesByExam method...\n";
$controller = new TestExamResultController();

// Override the actual methods to capture output
// We need to use reflection to test the protected methods
$reflection = new ReflectionClass($controller);
$method = $reflection->getMethod('getClassesByExam');
$method->setAccessible(true);

// Capture the output
ob_start();
$method->invoke($controller);
$output = ob_get_clean();

echo "Output: " . $output . "\n";

// Check if we got valid JSON
$data = json_decode($output, true);
if ($data && isset($data['classes'])) {
    echo "SUCCESS: getClassesByExam returns valid JSON with classes array\n";
    echo "Number of classes: " . count($data['classes']) . "\n";
} else {
    echo "ERROR: getClassesByExam does not return expected JSON structure\n";
}

echo "\nTesting getStudentsByClass method...\n";
$method2 = $reflection->getMethod('getStudentsByClass');
$method2->setAccessible(true);

ob_start();
$method2->invoke($controller);
$output2 = ob_get_clean();

echo "Output: " . $output2 . "\n";

$data2 = json_decode($output2, true);
if ($data2 && isset($data2['students'])) {
    echo "SUCCESS: getStudentsByClass returns valid JSON with students array\n";
} else {
    echo "ERROR: getStudentsByClass does not return expected JSON structure\n";
}

echo "\nAll tests completed.\n";
?>