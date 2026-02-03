<?php
// Migration to add original_classes field to fees table

try {
    // Get database connection
    require_once 'config/config.php';
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add original_classes column to fees table
    $sql = "ALTER TABLE fees ADD COLUMN original_classes TEXT NULL AFTER class_id";
    $pdo->exec($sql);
    
    echo "Migration completed successfully: Added original_classes column to fees table\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>