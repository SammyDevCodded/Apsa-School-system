<?php
// Migration to add fee_id column to payments table

try {
    // Get database connection
    require_once 'config/config.php';
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add fee_id column to payments table
    $sql = "ALTER TABLE payments ADD COLUMN fee_id INT NULL AFTER student_id";
    $pdo->exec($sql);
    
    // Add foreign key constraint
    $sql = "ALTER TABLE payments ADD CONSTRAINT fk_payments_fee_id FOREIGN KEY (fee_id) REFERENCES fees(id) ON DELETE SET NULL";
    $pdo->exec($sql);
    
    echo "Migration completed successfully: Added fee_id column to payments table\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>