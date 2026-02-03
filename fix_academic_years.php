<?php
require 'config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    
    // Set all academic years to not current first
    $stmt = $pdo->prepare("UPDATE academic_years SET is_current = 0");
    $stmt->execute();
    
    // Set the most recent one as current
    $stmt = $pdo->prepare("UPDATE academic_years SET is_current = 1 WHERE id = 1");
    $stmt->execute();
    
    echo "Fixed academic years. Only ID 1 is now current.\n";
    
    // Verify the fix
    $stmt = $pdo->query('SELECT * FROM academic_years');
    $years = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Academic years in database:\n";
    foreach ($years as $year) {
        echo "ID: " . $year['id'] . " - Name: " . $year['name'] . " - Current: " . $year['is_current'] . "\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}