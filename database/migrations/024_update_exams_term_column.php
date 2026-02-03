<?php
// Migration Script to update the term column in exams table
// Changes the ENUM values from ('first', 'second', 'third') to ('1st Term', '2nd Term', '3rd Term')

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Update the term column to accept the proper values
    $pdo->exec("
        ALTER TABLE exams 
        MODIFY COLUMN term ENUM('1st Term', '2nd Term', '3rd Term') NOT NULL
    ");
    
    echo "Migration completed successfully. The term column has been updated.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>