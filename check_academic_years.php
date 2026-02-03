<?php
require_once 'config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $stmt = $pdo->query('SELECT * FROM academic_years');
    $years = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Academic Years:\n";
    foreach ($years as $year) {
        echo "- ID: " . $year['id'] . ", Name: " . $year['name'] . ", Is Current: " . $year['is_current'] . ", Term: " . ($year['term'] ?? 'N/A') . "\n";
    }
    
    if (empty($years)) {
        echo "No academic years found.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>