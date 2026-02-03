<?php
require 'config/config.php';

try {
    $db = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $stmt = $db->query('SELECT * FROM grading_rules');
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Found " . count($results) . " grading rules:\n";
    foreach ($results as $rule) {
        echo "ID: " . $rule['id'] . ", Scale ID: " . $rule['scale_id'] . ", Min: " . $rule['min_score'] . ", Max: " . $rule['max_score'] . ", Grade: " . $rule['grade'] . ", Remark: " . $rule['remark'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}