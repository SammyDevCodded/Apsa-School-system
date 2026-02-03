<?php
// Simulate the AJAX endpoint for getClassesByExam

// Start session to simulate logged-in user
session_start();

// Simulate a logged-in user
$_SESSION['user'] = [
    'id' => 1,
    'username' => 'admin',
    'role_id' => 1
];

require_once 'vendor/autoload.php';
require_once 'config/config.php';

use App\Models\Exam;
use App\Models\ClassModel;

// Simulate the GET parameter
$examId = $_GET['exam_id'] ?? 1; // Default to exam ID 1

error_log("getClassesByExam called with exam_id: " . $examId);

if (!$examId) {
    error_log("No exam_id provided");
    echo json_encode(['classes' => []]);
    exit;
}

// Check if user is logged in (simulate AuthMiddleware check)
if (!isset($_SESSION['user'])) {
    error_log("User not authenticated");
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// Create model instances
$examModel = new Exam();
$classModel = new ClassModel();

// Get the exam
$exam = $examModel->find($examId);

error_log("Found exam: " . print_r($exam, true));

$classes = [];
if ($exam && !empty($exam['class_id'])) {
    $class = $classModel->find($exam['class_id']);
    error_log("Looking for class with ID: " . $exam['class_id']);
    error_log("Found class: " . print_r($class, true));
    if ($class) {
        $classes[] = $class;
    } else {
        error_log("No class found for class_id: " . $exam['class_id']);
    }
} else {
    error_log("No exam found for exam_id: " . $examId);
}

error_log("Returning classes: " . print_r($classes, true));

// Set content type to JSON
header('Content-Type: application/json');

// Return the JSON response
echo json_encode(['classes' => $classes]);
?>