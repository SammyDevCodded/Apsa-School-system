<?php
// Seed script to add sample exam results to the database

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all students
    $stmt = $pdo->prepare("SELECT id FROM students LIMIT 3");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Get all exams
    $stmt = $pdo->prepare("SELECT id FROM exams");
    $stmt->execute();
    $exams = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    // Get all subjects
    $stmt = $pdo->prepare("SELECT id FROM subjects");
    $stmt->execute();
    $subjects = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($students) || empty($exams) || empty($subjects)) {
        echo "Please seed students, exams, and subjects first.\n";
        exit;
    }
    
    // Generate sample exam results
    foreach ($exams as $examId) {
        foreach ($students as $studentId) {
            foreach ($subjects as $subjectId) {
                // Generate random marks between 40 and 100
                $marks = rand(40, 100);
                
                // Calculate grade based on marks
                if ($marks >= 90) {
                    $grade = 'A+';
                } elseif ($marks >= 80) {
                    $grade = 'A';
                } elseif ($marks >= 70) {
                    $grade = 'B';
                } elseif ($marks >= 60) {
                    $grade = 'C';
                } elseif ($marks >= 50) {
                    $grade = 'D';
                } else {
                    $grade = 'F';
                }
                
                $stmt = $pdo->prepare("
                    INSERT IGNORE INTO exam_results (exam_id, student_id, subject_id, marks, grade)
                    VALUES (:exam_id, :student_id, :subject_id, :marks, :grade)
                ");
                
                $stmt->execute([
                    'exam_id' => $examId,
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'marks' => $marks,
                    'grade' => $grade
                ]);
            }
        }
    }
    
    echo "Sample exam results added successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}