<?php
require_once 'config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $stmt = $pdo->query('DESCRIBE students');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Students table columns:\n";
    foreach ($columns as $column) {
        echo "- " . $column['Field'] . " (" . $column['Type'] . ")\n";
        if ($column['Field'] == 'profile_picture') {
            echo "  ^ Found profile_picture column!\n";
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}