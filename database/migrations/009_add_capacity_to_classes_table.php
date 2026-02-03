<?php
// Migration to add capacity column to classes table

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add capacity column to classes table
    $pdo->exec("ALTER TABLE classes ADD COLUMN capacity INT DEFAULT 30");
    
    echo "Migration completed successfully! Added capacity column to classes table.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}