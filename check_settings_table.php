<?php
require_once 'config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $stmt = $pdo->query('DESCRIBE settings');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Settings table structure:\n";
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
    }
    
    // Check if there's any data in the table
    $stmt = $pdo->query('SELECT * FROM settings LIMIT 1');
    $row = $stmt->fetch(PDO::FETCH_ASSOC);
    
    if ($row) {
        echo "\nCurrent settings data:\n";
        print_r($row);
    } else {
        echo "\nNo data found in settings table.\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}