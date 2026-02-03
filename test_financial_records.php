<?php
require_once 'config/config.php';
require_once 'vendor/autoload.php';

// Create a simple test to check if financial records are being retrieved
try {
    $studentModel = new App\Models\Student();
    
    // Test with student ID 112 (who has valid fee assignments)
    $studentId = 112;
    echo "Testing financial records for student ID: " . $studentId . "\n";
    
    // Get financial records
    $financialRecords = $studentModel->getFinancialRecords($studentId);
    
    echo "Financial Records:\n";
    echo "==================\n";
    echo "Fees Count: " . count($financialRecords['fees']) . "\n";
    echo "Payments Count: " . count($financialRecords['payments']) . "\n";
    echo "Grouped Payments Count: " . count($financialRecords['grouped_payments']) . "\n";
    echo "Total Payments: " . $financialRecords['total_payments'] . "\n";
    
    if (!empty($financialRecords['fees'])) {
        echo "\nFee Details:\n";
        foreach ($financialRecords['fees'] as $fee) {
            echo "- " . $fee['name'] . " (" . $fee['type'] . "): " . $fee['amount'] . 
                 " | Paid: " . $fee['paid_amount'] . " | Balance: " . $fee['balance'] . 
                 " | Status: " . $fee['status'] . "\n";
        }
    } else {
        echo "\nNo fees found for this student.\n";
    }
    
    if (!empty($financialRecords['payments'])) {
        echo "\nPayment Details:\n";
        foreach ($financialRecords['payments'] as $payment) {
            echo "- Date: " . $payment['date'] . " | Amount: " . $payment['amount'] . 
                 " | Method: " . $payment['method'] . " | Fee ID: " . $payment['fee_id'] . "\n";
        }
    } else {
        echo "\nNo payments found for this student.\n";
    }
    
    // Show grouped payments
    if (!empty($financialRecords['grouped_payments'])) {
        echo "\nGrouped Payments by Academic Year:\n";
        foreach ($financialRecords['grouped_payments'] as $academicYear => $terms) {
            echo "- Academic Year: " . $academicYear . "\n";
            foreach ($terms as $term => $payments) {
                echo "  - Term: " . $term . " (" . count($payments) . " payments)\n";
            }
        }
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>