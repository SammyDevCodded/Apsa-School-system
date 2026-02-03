<?php
// Migration to add academic_year_id and term columns to audit_logs table

require_once 'config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add academic_year_id column to audit_logs table
    $sql = "ALTER TABLE audit_logs ADD COLUMN academic_year_id INT NULL AFTER user_agent";
    $pdo->exec($sql);
    
    // Add term column to audit_logs table
    $sql = "ALTER TABLE audit_logs ADD COLUMN term VARCHAR(50) NULL AFTER academic_year_id";
    $pdo->exec($sql);
    
    // Add foreign key constraint for academic_year_id
    $sql = "ALTER TABLE audit_logs ADD CONSTRAINT fk_audit_logs_academic_year_id FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE SET NULL";
    $pdo->exec($sql);
    
    echo "Migration completed successfully: Added academic_year_id and term columns to audit_logs table\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>