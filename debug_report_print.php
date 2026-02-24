<?php
// Define application paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('DATABASE_PATH', ROOT_PATH . '/database');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Autoload classes
require_once ROOT_PATH . '/vendor/autoload.php';
require_once CONFIG_PATH . '/config.php';

use App\Models\ExamResult;

// Mock session
$_SESSION['user'] = ['id' => 1, 'username' => 'admin', 'role' => 'admin'];

// Use strict error reporting to catch notices
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Get an exam and class ID that has results (from previous debug output, Exam ID 18 or 32)
$examId = 18; // Adjust if needed
$classId = 1; // Assuming class ID 1 exists and has results

echo "Testing ExamResult::getRankedResults with perPage=0...\n";
$examResultModel = new ExamResult();
$rankedResultsData = $examResultModel->getRankedResults(['exam_id' => $examId, 'class_id' => $classId], 1, 0);

if (isset($rankedResultsData['data'])) {
    echo "Structure is correct (contains 'data' key).\n";
    $rankedResults = $rankedResultsData['data'];
    echo "Count: " . count($rankedResults) . "\n";
    
    // Simulate the loop that caused errors
    $studentRanks = [];
    foreach ($rankedResults as $index => $row) {
        $currentRank = $index + 1;
        // Check if $row is an array and has student_id
        if (is_array($row) && isset($row['student_id'])) {
            // Success
             $studentRanks[$row['student_id']] = $currentRank;
        } else {
            echo "ERROR: Row is not an array or missing student_id!\n";
            print_r($row);
        }
    }
    echo "Loop completed successfully.\n";
} else {
    echo "ERROR: rankedResultsData does not contain 'data' key.\n";
    print_r($rankedResultsData);
}
