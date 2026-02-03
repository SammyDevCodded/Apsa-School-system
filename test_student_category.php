<?php
// Test script to verify student category functionality
require_once 'config/config.php';

echo "Testing Student Category Functionality\n";
echo "=====================================\n\n";

// Test the students table structure
try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    
    // Check if student category columns exist
    $stmt = $pdo->query("DESCRIBE students");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "1. Checking students table structure:\n";
    $hasCategoryColumn = false;
    $hasCategoryDetailsColumn = false;
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'student_category') {
            $hasCategoryColumn = true;
            echo "   ✓ student_category column found: " . $column['Type'] . "\n";
        }
        if ($column['Field'] === 'student_category_details') {
            $hasCategoryDetailsColumn = true;
            echo "   ✓ student_category_details column found: " . $column['Type'] . "\n";
        }
    }
    
    if ($hasCategoryColumn && $hasCategoryDetailsColumn) {
        echo "   ✓ All student category columns are present\n\n";
    } else {
        echo "   ✗ Missing student category columns\n\n";
    }
    
    // Test inserting a student with category information
    echo "2. Testing student category insertion:\n";
    
    // First, let's get a class ID to use
    $stmt = $pdo->query("SELECT id FROM classes LIMIT 1");
    $class = $stmt->fetch(PDO::FETCH_ASSOC);
    $classId = $class ? $class['id'] : 1;
    
    // Generate a unique admission number
    $admissionNo = 'TEST' . time();
    
    $sql = "INSERT INTO students (admission_no, first_name, last_name, class_id, student_category, student_category_details) 
            VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = $pdo->prepare($sql);
    $result = $stmt->execute([
        $admissionNo,
        'Test',
        'Student',
        $classId,
        'international',
        'Exchange student from Germany'
    ]);
    
    if ($result) {
        $studentId = $pdo->lastInsertId();
        echo "   ✓ Student with category inserted successfully (ID: $studentId)\n";
        
        // Retrieve and verify the student data
        $stmt = $pdo->prepare("SELECT * FROM students WHERE id = ?");
        $stmt->execute([$studentId]);
        $student = $stmt->fetch(PDO::FETCH_ASSOC);
        
        if ($student && $student['student_category'] === 'international') {
            echo "   ✓ Student category correctly stored: " . $student['student_category'] . "\n";
            echo "   ✓ Category details: " . $student['student_category_details'] . "\n";
        } else {
            echo "   ✗ Student category not correctly stored\n";
        }
        
        // Clean up - delete the test student
        $stmt = $pdo->prepare("DELETE FROM students WHERE id = ?");
        $stmt->execute([$studentId]);
        echo "   ✓ Test student cleaned up\n";
    } else {
        echo "   ✗ Failed to insert student with category\n";
    }
    
} catch (Exception $e) {
    echo "   ✗ Error: " . $e->getMessage() . "\n";
}

echo "\nStudent Category Functionality Test Completed!\n";