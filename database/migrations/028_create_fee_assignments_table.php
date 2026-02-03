<?php
// Migration to create fee_assignments table for tracking which students are assigned to which fees

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create fee_assignments table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS fee_assignments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            fee_id INT NOT NULL,
            student_id INT NOT NULL,
            assigned_date DATE DEFAULT (CURRENT_DATE),
            status ENUM('active', 'cancelled') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (fee_id) REFERENCES fees(id) ON DELETE CASCADE,
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
            UNIQUE KEY unique_fee_student (fee_id, student_id)
        )
    ");
    
    echo "Fee assignments table created successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}