<?php
require_once 'config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $stmt = $pdo->query('SELECT * FROM fees');
    $fees = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Fee Structures:\n";
    foreach ($fees as $fee) {
        echo "- ID: " . $fee['id'] . ", Name: " . $fee['name'] . ", Amount: " . $fee['amount'] . "\n";
    }
    
    if (empty($fees)) {
        echo "No fee structures found.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>