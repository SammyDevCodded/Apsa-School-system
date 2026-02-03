<?php
// Migration Script for Settings Table
// Run this script to create the settings table

// Database configuration
require_once dirname(__DIR__, 2) . '/config/config.php';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create settings table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            school_name VARCHAR(255) NOT NULL DEFAULT 'Futuristic School',
            school_logo VARCHAR(255) DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    // Insert default settings
    $stmt = $pdo->prepare("INSERT IGNORE INTO settings (id, school_name) VALUES (?, ?)");
    $stmt->execute([1, 'Futuristic School']);
    
    echo "Settings table created successfully!\n";
    echo "Default settings inserted.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}