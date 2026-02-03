<?php
// Migration to create staff_subjects table for many-to-many relationship between staff and subjects

require_once dirname(__DIR__) . '/../config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create staff_subjects table
    $sql = "CREATE TABLE IF NOT EXISTS staff_subjects (
        id INT AUTO_INCREMENT PRIMARY KEY,
        staff_id INT NOT NULL,
        subject_id INT NOT NULL,
        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        UNIQUE KEY unique_staff_subject (staff_id, subject_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_unicode_ci";
    
    $pdo->exec($sql);
    
    // Add foreign key constraints
    $sql = "ALTER TABLE staff_subjects 
            ADD CONSTRAINT fk_staff_subjects_staff FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE CASCADE ON UPDATE CASCADE,
            ADD CONSTRAINT fk_staff_subjects_subjects FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE ON UPDATE CASCADE";
    
    $pdo->exec($sql);
    
    echo "Migration successful: Created staff_subjects table with foreign key constraints\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>