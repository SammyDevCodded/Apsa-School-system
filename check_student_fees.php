<?php
require_once 'config/config.php';
require_once 'vendor/autoload.php';

try {
    $db = new App\Core\Database();
    
    // Check fee assignments for student ID 1
    $stmt = $db->getConnection()->prepare('SELECT COUNT(*) as count FROM fee_assignments WHERE student_id = ?');
    $stmt->execute([1]);
    $result = $stmt->fetch();
    echo 'Fee assignments for student ID 1: ' . $result['count'] . "\n";
    
    // Check if student ID 1 exists
    $stmt = $db->getConnection()->prepare('SELECT id, first_name, last_name FROM students WHERE id = ?');
    $stmt->execute([1]);
    $student = $stmt->fetch();
    if ($student) {
        echo 'Student ID 1: ' . $student['first_name'] . ' ' . $student['last_name'] . "\n";
    } else {
        echo 'Student ID 1 does not exist' . "\n";
    }
    
    // Check the fee assignments with details including status
    $stmt = $db->getConnection()->prepare('SELECT fa.*, f.name as fee_name, f.amount as fee_amount FROM fee_assignments fa JOIN fees f ON fa.fee_id = f.id WHERE fa.student_id = ?');
    $stmt->execute([1]);
    $assignments = $stmt->fetchAll();
    echo 'Total fee assignments for student ID 1: ' . count($assignments) . "\n";
    
    foreach ($assignments as $assignment) {
        echo "- Fee: " . $assignment['fee_name'] . " (Amount: " . $assignment['fee_amount'] . ", Status: " . $assignment['status'] . ")\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>