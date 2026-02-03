<?php
// Seed script to add sample exam records to the database

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Sample exams data
    $exams = [
        [
            'name' => 'Midterm Exam',
            'term' => 'first',
            'class_id' => 1,
            'date' => date('Y-m-15'),
            'description' => 'Midterm examination for Grade 1 students'
        ],
        [
            'name' => 'Final Exam',
            'term' => 'first',
            'class_id' => 1,
            'date' => date('Y-m-28'),
            'description' => 'Final examination for Grade 1 students'
        ],
        [
            'name' => 'Midterm Exam',
            'term' => 'second',
            'class_id' => 1,
            'date' => date('Y-m-15', strtotime('+3 months')),
            'description' => 'Midterm examination for Grade 1 students'
        ]
    ];
    
    // Insert sample exams
    foreach ($exams as $exam) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO exams (name, term, class_id, date, description)
            VALUES (:name, :term, :class_id, :date, :description)
        ");
        
        $stmt->execute($exam);
    }
    
    echo "Sample exam records added successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}