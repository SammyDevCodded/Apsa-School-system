<?php
// Migration to add term column to academic_years table

require_once 'config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Add term column to academic_years table
    $sql = "ALTER TABLE academic_years ADD COLUMN term VARCHAR(50) NULL AFTER status";
    $pdo->exec($sql);
    
    echo "Migration completed successfully: Added term column to academic_years table\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>