<?php
require_once 'config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $stmt = $pdo->query('DESCRIBE academic_years');
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    echo "Academic Years table structure:\n";
    print_r($columns);
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}
?>