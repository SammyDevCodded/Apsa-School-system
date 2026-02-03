<?php
require_once 'app/Core/Database.php';
require_once 'config/config.php';
require_once 'app/Models/Fee.php';
require_once 'app/Models/FeeAssignment.php';
require_once 'app/Models/AcademicYear.php';
require_once 'app/Models/Student.php';

use App\Core\Database;
use App\Models\Fee;
use App\Models\FeeAssignment;
use App\Models\AcademicYear;

echo "Starting Term Filter Verification...\n";

try {
    $db = new Database();
    $feeModel = new Fee();
    $feeAssignmentModel = new FeeAssignment();
    $academicYearModel = new AcademicYear();
    
    // 1. Get Current Academic Year and Term
    $currentAy = $academicYearModel->getCurrent();
    if (!$currentAy) {
        die("No active academic year found.\n");
    }
    echo "Current AY: " . $currentAy['name'] . ", Term: " . $currentAy['term'] . "\n";
    
    // 2. Create a test Fee with the current term
    $testFeeName = "Test Term Fee " . time();
    $feeId = $feeModel->create([
        'name' => $testFeeName,
        'amount' => 100,
        'type' => 'tuition_fee',
        'class_id' => '', // Global
        'description' => 'Test Fee',
        'academic_year_id' => $currentAy['id'],
        'term' => 'TestTerm' // Explicitly set a test term
    ]);
    
    if (!$feeId) {
        die("Failed to create test fee.\n");
    }
    echo "Created Test Fee (ID: $feeId) with Term: TestTerm\n";
    
    // Check if term was saved correctly
    $savedFee = $feeModel->find($feeId);
    if ($savedFee['term'] !== 'TestTerm') {
        die("Error: Fee term was not saved. Expected 'TestTerm', got '{$savedFee['term']}'\n");
    }
    echo "Verified: Fee term saved correctly.\n";
    
    // 3. Assign to a student (find one first)
    $studentId = $db->fetchOne("SELECT id FROM students LIMIT 1")['id'];
    if (!$studentId) {
        die("No students found.\n");
    }
    
    $feeAssignmentModel->assignFeeToStudent($feeId, $studentId);
    echo "Assigned fee to student ID: $studentId\n";
    
    // 4. Test Filtering
    
    // Test 4a: Filter by Matching Term
    echo "Testing Filter: Term = 'TestTerm'...\n";
    $resultsMatch = $feeAssignmentModel->getAllStudentBills(1, 10, ['term' => 'TestTerm']);
    $found = false;
    foreach ($resultsMatch['data'] as $bill) {
        if ($bill['fee_id'] == $feeId) {
            $found = true;
            break;
        }
    }
    
    if ($found) {
        echo "PASS: Found test fee when filtering by correct term.\n";
    } else {
        echo "FAIL: Did NOT find test fee when filtering by correct term.\n";
    }
    
    // Test 4b: Filter by Non-Matching Term
    echo "Testing Filter: Term = 'WrongTerm'...\n";
    $resultsMistmatch = $feeAssignmentModel->getAllStudentBills(1, 10, ['term' => 'WrongTerm']);
    $foundMismatch = false;
    foreach ($resultsMistmatch['data'] as $bill) {
        if ($bill['fee_id'] == $feeId) {
            $foundMismatch = true;
            break;
        }
    }
    
    if (!$foundMismatch) {
        echo "PASS: Did NOT find test fee when filtering by wrong term.\n";
    } else {
        echo "FAIL: Found test fee when filtering by wrong term!\n";
    }
    
    // Clean up
    $db->execute("DELETE FROM fee_assignments WHERE fee_id = ?", [$feeId]);
    $db->execute("DELETE FROM fees WHERE id = ?", [$feeId]);
    echo "Cleaned up test data.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
