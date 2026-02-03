<?php
// Test Promotion System Integration
require_once 'config/config.php';
require_once 'vendor/autoload.php';

use App\Models\Promotion;
use App\Models\Student;
use App\Models\ClassModel;

echo "Testing Promotion System Integration\n";
echo "==================================\n\n";

try {
    // Test database connection
    $db = new \App\Core\Database();
    echo "✓ Database connection successful\n";
    
    // Test Promotion model instantiation
    $promotionModel = new Promotion();
    echo "✓ Promotion model instantiated successfully\n";
    
    // Test Student model instantiation
    $studentModel = new Student();
    echo "✓ Student model instantiated successfully\n";
    
    // Test Class model instantiation
    $classModel = new ClassModel();
    echo "✓ Class model instantiated successfully\n";
    
    // Test getting classes
    $classes = $classModel->getAll();
    echo "✓ Retrieved " . count($classes) . " classes\n";
    
    if (!empty($classes)) {
        echo "  Sample classes:\n";
        foreach (array_slice($classes, 0, 3) as $class) {
            echo "  - " . $class['name'] . " (" . $class['level'] . ")\n";
        }
    }
    
    // Test getting students
    $students = $studentModel->getAllWithClass();
    echo "✓ Retrieved " . count($students) . " students\n";
    
    if (!empty($students)) {
        echo "  Sample students:\n";
        foreach (array_slice($students, 0, 3) as $student) {
            echo "  - " . $student['first_name'] . " " . $student['last_name'] . " (" . $student['admission_no'] . ") - Class: " . ($student['class_name'] ?? 'N/A') . "\n";
        }
    }
    
    // Test promotion history for a student (if any exist)
    if (!empty($students)) {
        $firstStudent = $students[0];
        $promotionHistory = $promotionModel->getStudentPromotionHistory($firstStudent['id']);
        echo "✓ Checked promotion history for student ID " . $firstStudent['id'] . "\n";
        echo "  Found " . count($promotionHistory) . " promotion records\n";
    }
    
    echo "\n✓ All tests passed! Promotion system is ready.\n";
    echo "\nYou can now access the promotion system at: /promotions\n";
    
} catch (Exception $e) {
    echo "✗ Error: " . $e->getMessage() . "\n";
    echo "Stack trace:\n" . $e->getTraceAsString() . "\n";
}