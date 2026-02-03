<?php
// Migration Script to add custom_school_address column to report_card_settings table
// Run this script to add the custom_school_address column

// Database configuration
require_once dirname(__DIR__, 2) . '/config/config.php';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add custom_school_address column to report_card_settings table
    $pdo->exec("ALTER TABLE report_card_settings ADD COLUMN custom_school_address TEXT DEFAULT NULL AFTER show_school_address");
    
    echo "custom_school_address column added to report_card_settings table successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}