<?php
// Migration to add academic_year and term columns to payments table

try {
    // Get database connection
    require_once 'config/config.php';
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add academic_year_id column to payments table
    $sql = "ALTER TABLE payments ADD COLUMN academic_year_id INT NULL AFTER fee_id";
    $pdo->exec($sql);
    
    // Add term column to payments table
    $sql = "ALTER TABLE payments ADD COLUMN term VARCHAR(50) NULL AFTER academic_year_id";
    $pdo->exec($sql);
    
    // Add foreign key constraint for academic_year_id
    $sql = "ALTER TABLE payments ADD CONSTRAINT fk_payments_academic_year_id FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE SET NULL";
    $pdo->exec($sql);
    
    echo "Migration completed successfully: Added academic_year_id and term columns to payments table\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>