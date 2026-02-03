<?php
require 'config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $stmt = $pdo->query('DESCRIBE exams');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "academic_year_id column exists: ";
    $found = false;
    foreach ($columns as $column) {
        if ($column['Field'] == 'academic_year_id') {
            echo "YES\n";
            $found = true;
            break;
        }
    }
    if (!$found) {
        echo "NO\n";
    }
    
    echo "All columns in exams table:\n";
    foreach ($columns as $column) {
        echo $column['Field'] . ' - ' . $column['Type'] . "\n";
    }
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}