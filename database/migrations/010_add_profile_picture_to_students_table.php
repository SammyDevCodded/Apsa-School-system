<?php
// Migration to add profile_picture column to students table

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add profile_picture column to students table
    $pdo->exec("ALTER TABLE students ADD COLUMN profile_picture VARCHAR(255) DEFAULT NULL");
    
    echo "Migration completed successfully! Added profile_picture column to students table.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}