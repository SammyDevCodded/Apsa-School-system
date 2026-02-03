<?php
// Migration to add student category fields to the students table

require_once dirname(__DIR__, 2) . '/config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add student category columns to students table
    $sql = "
        ALTER TABLE students 
        ADD COLUMN student_category ENUM('regular_day', 'regular_boarding', 'international') NOT NULL DEFAULT 'regular_day' AFTER profile_picture,
        ADD COLUMN student_category_details TEXT AFTER student_category
    ";
    
    $pdo->exec($sql);
    
    echo "Migration completed successfully!\n";
    echo "Added student category columns to the students table.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}