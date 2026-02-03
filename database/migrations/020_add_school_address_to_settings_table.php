<?php
// Migration Script to add school_address column to settings table
// Run this script to add the school_address column

// Database configuration
require_once dirname(__DIR__, 2) . '/config/config.php';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add school_address column to settings table
    $pdo->exec("ALTER TABLE settings ADD COLUMN school_address TEXT DEFAULT NULL AFTER school_name");
    
    echo "school_address column added to settings table successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}