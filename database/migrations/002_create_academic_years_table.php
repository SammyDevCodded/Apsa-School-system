<?php
// Migration Script to add academic_years table

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create academic_years table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS academic_years (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            start_date DATE NOT NULL,
            end_date DATE NOT NULL,
            is_current TINYINT(1) DEFAULT 0,
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    // Insert a default academic year
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO academic_years (name, start_date, end_date, is_current, status) 
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->execute(['2025-2026', '2025-09-01', '2026-06-30', 1, 'active']);
    
    echo "Academic years table created successfully!\n";
    echo "Default academic year 2025-2026 added.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}