<?php
require_once 'config/config.php';
require_once 'vendor/autoload.php';

try {
    $db = new App\Core\Database();
    
    echo "Checking for fees with specific IDs:\n";
    $idsToCheck = [1, 8, 15, 16];
    
    foreach ($idsToCheck as $id) {
        $stmt = $db->getConnection()->prepare('SELECT id, name FROM fees WHERE id = ?');
        $stmt->execute([$id]);
        $fee = $stmt->fetch();
        if ($fee) {
            echo "Fee ID " . $fee['id'] . ": " . $fee['name'] . "\n";
        } else {
            echo "Fee ID " . $id . ": NOT FOUND\n";
        }
    }
    
    echo "\nAll fees in database:\n";
    $stmt = $db->getConnection()->query('SELECT id, name FROM fees ORDER BY id');
    $fees = $stmt->fetchAll();
    foreach ($fees as $fee) {
        echo "Fee ID " . $fee['id'] . ": " . $fee['name'] . "\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>