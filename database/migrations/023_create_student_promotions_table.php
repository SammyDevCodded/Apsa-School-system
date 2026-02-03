<?php
// Migration to create student_promotions table
// This table will store student promotion history and records

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create student_promotions table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS student_promotions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id INT NOT NULL,
            from_class_id INT NOT NULL,
            to_class_id INT NOT NULL,
            academic_year_id INT NOT NULL,
            promotion_date DATE NOT NULL,
            promoted_by INT NOT NULL,
            remarks TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
            FOREIGN KEY (from_class_id) REFERENCES classes(id) ON DELETE CASCADE,
            FOREIGN KEY (to_class_id) REFERENCES classes(id) ON DELETE CASCADE,
            FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE CASCADE,
            FOREIGN KEY (promoted_by) REFERENCES users(id) ON DELETE CASCADE,
            INDEX idx_student_promotions_student_id (student_id),
            INDEX idx_student_promotions_academic_year (academic_year_id),
            INDEX idx_student_promotions_date (promotion_date)
        )
    ");
    
    echo "Student promotions table created successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}