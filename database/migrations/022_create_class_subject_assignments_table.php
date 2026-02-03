<?php
// Migration to create class_subject_assignments table
// This table will store assignments of subjects to classes and specific students

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create class_subject_assignments table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS class_subject_assignments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            class_id INT NOT NULL,
            subject_id INT NOT NULL,
            student_id INT NULL,  -- NULL means assigned to entire class, NOT NULL means assigned to specific student
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE,
            FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
            UNIQUE KEY unique_class_subject_student (class_id, subject_id, student_id)
        )
    ");
    
    echo "Class subject assignments table created successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}