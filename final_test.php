<?php
// Final test to verify the fix

// Start session to simulate logged-in user
session_start();

// Simulate a logged-in user
$_SESSION['user'] = [
    'id' => 1,
    'username' => 'admin',
    'role_id' => 1
];

// Simulate GET parameters
$_GET['exam_id'] = 1;

// Include necessary files
require_once 'vendor/autoload.php';
require_once 'config/config.php';

use App\Controllers\ExamResultController;

// Create a mock controller to test the method
class TestExamResultController extends ExamResultController {
    private $testOutput = '';
    
    protected function jsonResponse($data, $statusCode = 200) {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        $this->testOutput = json_encode($data);
        echo $this->testOutput;
    }
    
    public function getTestOutput() {
        return $this->testOutput;
    }
}

// Test the getClassesByExam method
echo "Testing getClassesByExam method...\n";

$controller = new TestExamResultController();
ob_start();
$controller->getClassesByExam();
$output = ob_get_clean();

echo "Response headers:\n";
foreach (headers_list() as $header) {
    echo $header . "\n";
}

echo "\nResponse body:\n";
echo $output . "\n";

// Parse the JSON to verify it's correct
$data = json_decode($output, true);
if ($data && isset($data['classes'])) {
    echo "\nSUCCESS: Response contains 'classes' array\n";
    echo "Number of classes: " . count($data['classes']) . "\n";
    if (!empty($data['classes'])) {
        echo "First class: " . $data['classes'][0]['name'] . " (" . $data['classes'][0]['level'] . ")\n";
    }
} else {
    echo "\nERROR: Response does not contain expected 'classes' array\n";
    print_r($data);
}
?>