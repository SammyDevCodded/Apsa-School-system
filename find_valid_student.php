<?php
require_once 'config/config.php';
require_once 'vendor/autoload.php';

try {
    $db = new App\Core\Database();
    
    // Find a student with valid fee assignments (where the fee actually exists)
    $stmt = $db->getConnection()->query('SELECT fa.student_id, COUNT(*) as fee_count FROM fee_assignments fa JOIN fees f ON fa.fee_id = f.id WHERE fa.status = "active" GROUP BY fa.student_id HAVING fee_count > 0 LIMIT 1');
    $result = $stmt->fetch();
    
    if ($result) {
        echo "Student ID with valid fee assignments: " . $result['student_id'] . " (Fee count: " . $result['fee_count'] . ")\n";
        
        // Get student details
        $stmt = $db->getConnection()->prepare('SELECT id, first_name, last_name FROM students WHERE id = ?');
        $stmt->execute([$result['student_id']]);
        $student = $stmt->fetch();
        if ($student) {
            echo "Student: " . $student['first_name'] . " " . $student['last_name'] . "\n";
        }
        
        // Test our financial records method with this student
        $studentModel = new App\Models\Student();
        $financialRecords = $studentModel->getFinancialRecords($result['student_id']);
        echo "Financial records for this student:\n";
        echo "- Fees: " . count($financialRecords['fees']) . "\n";
        echo "- Payments: " . count($financialRecords['payments']) . "\n";
    } else {
        echo "No students with valid fee assignments found\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>