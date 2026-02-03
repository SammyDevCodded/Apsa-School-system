<?php
// Seed script to add sample subjects to the database

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Sample subjects data
    $subjects = [
        [
            'name' => 'Mathematics',
            'code' => 'MATH101',
            'class_id' => 1,
            'description' => 'Basic mathematics concepts for Grade 1 students'
        ],
        [
            'name' => 'English Language',
            'code' => 'ENG101',
            'class_id' => 1,
            'description' => 'English language fundamentals for Grade 1 students'
        ],
        [
            'name' => 'Science',
            'code' => 'SCI101',
            'class_id' => 1,
            'description' => 'Introduction to science for Grade 1 students'
        ],
        [
            'name' => 'Social Studies',
            'code' => 'SOC101',
            'class_id' => 1,
            'description' => 'Social studies curriculum for Grade 1 students'
        ]
    ];
    
    // Insert sample subjects
    foreach ($subjects as $subject) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO subjects (name, code, class_id, description)
            VALUES (:name, :code, :class_id, :description)
        ");
        
        $stmt->execute($subject);
    }
    
    echo "Sample subjects added successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}