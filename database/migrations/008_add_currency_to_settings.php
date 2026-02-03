<?php
// Migration Script to Add Currency to Settings Table
// Run this script to add currency column to settings table

// Database configuration
require_once dirname(__DIR__, 2) . '/config/config.php';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add currency column to settings table
    $pdo->exec("
        ALTER TABLE settings 
        ADD COLUMN currency_code VARCHAR(3) NOT NULL DEFAULT 'GHS' AFTER school_logo,
        ADD COLUMN currency_symbol VARCHAR(10) NOT NULL DEFAULT 'GH₵' AFTER currency_code
    ");
    
    // Update existing record with default currency
    $stmt = $pdo->prepare("UPDATE settings SET currency_code = ?, currency_symbol = ? WHERE id = 1");
    $stmt->execute(['GHS', 'GH₵']);
    
    echo "Currency columns added to settings table successfully!\n";
    echo "Default currency set to Ghanaian Cedis (GHS - GH₵).\n";
    
} catch (PDOException $e) {
    // If column already exists, that's fine
    if (strpos($e->getMessage(), 'Duplicate column name') !== false) {
        echo "Currency columns already exist in settings table.\n";
        echo "Default currency is Ghanaian Cedis (GHS - GH₵).\n";
    } else {
        echo "Error: " . $e->getMessage();
    }
}