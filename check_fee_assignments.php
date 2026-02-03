<?php
require_once 'config/config.php';
require_once 'vendor/autoload.php';

try {
    $db = new App\Core\Database();
    $stmt = $db->getConnection()->query('SELECT COUNT(*) as count FROM fee_assignments');
    $result = $stmt->fetch();
    echo 'Fee assignments count: ' . $result['count'] . "\n";
    
    // Check if there are any fees
    $stmt = $db->getConnection()->query('SELECT COUNT(*) as count FROM fees');
    $result = $stmt->fetch();
    echo 'Total fees count: ' . $result['count'] . "\n";
    
    // Check if there are any students
    $stmt = $db->getConnection()->query('SELECT COUNT(*) as count FROM students');
    $result = $stmt->fetch();
    echo 'Total students count: ' . $result['count'] . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>