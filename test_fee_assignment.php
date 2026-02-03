<?php
// Test for fee assignment functionality
require_once 'config/config.php';
require_once 'app/Core/Database.php';

try {
    // Create database connection
    $db = new \App\Core\Database();
    
    // Test creating the fee_assignments table
    $sql = "
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
    ";
    
    $db->execute($sql);
    
    echo "Fee assignments table created successfully!\n";
    
    // Test inserting a sample fee assignment
    $sql = "INSERT IGNORE INTO fee_assignments (fee_id, student_id) VALUES (1, 1)";
    $db->execute($sql);
    
    echo "Sample fee assignment created successfully!\n";
    
    // Test querying fee assignments
    $sql = "SELECT fa.*, f.name as fee_name, s.first_name, s.last_name 
            FROM fee_assignments fa
            LEFT JOIN fees f ON fa.fee_id = f.id
            LEFT JOIN students s ON fa.student_id = s.id";
    
    $assignments = $db->fetchAll($sql);
    
    echo "Fee assignments found: " . count($assignments) . "\n";
    
    if (!empty($assignments)) {
        foreach ($assignments as $assignment) {
            echo "- " . $assignment['first_name'] . " " . $assignment['last_name'] . 
                 " assigned to " . $assignment['fee_name'] . "\n";
        }
    }
    
    echo "\nAll tests completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}