<?php
// Migration to add transaction_id column to payments table for grouping payments in a single transaction

try {
    // Get database connection
    require_once 'config/config.php';
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add transaction_id column to payments table
    $sql = "ALTER TABLE payments ADD COLUMN transaction_id VARCHAR(50) NULL AFTER id";
    $pdo->exec($sql);
    
    // Add index for better performance
    $sql = "ALTER TABLE payments ADD INDEX idx_transaction_id (transaction_id)";
    $pdo->exec($sql);
    
    echo "Migration completed successfully: Added transaction_id column to payments table\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>