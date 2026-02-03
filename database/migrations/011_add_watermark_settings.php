<?php
// Migration to add watermark settings to the settings table

require_once dirname(__DIR__, 2) . '/config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add watermark columns to settings table
    $sql = "
        ALTER TABLE settings 
        ADD COLUMN watermark_type ENUM('none', 'logo', 'name', 'both') NOT NULL DEFAULT 'none' AFTER currency_symbol,
        ADD COLUMN watermark_position ENUM('top-left', 'top-center', 'top-right', 'middle-left', 'center', 'middle-right', 'bottom-left', 'bottom-center', 'bottom-right') NOT NULL DEFAULT 'center' AFTER watermark_type,
        ADD COLUMN watermark_transparency TINYINT NOT NULL DEFAULT 20 AFTER watermark_position
    ";
    
    $pdo->exec($sql);
    
    echo "Migration completed successfully!\n";
    echo "Added watermark settings columns to the settings table.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}