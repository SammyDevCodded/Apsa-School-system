<?php
// Test the Attendance model directly
require_once 'config/config.php';
require_once 'app/Core/Database.php';
require_once 'app/Models/Attendance.php';

use App\Models\Attendance;

try {
    // Test the getAttendanceSummaryWithFilters method
    $attendanceModel = new Attendance();
    
    echo "=== Test 1: Default report (no filters) ===\n";
    $result1 = $attendanceModel->getAttendanceSummaryWithFilters(date('Y-m-01'), date('Y-m-t'));
    echo "Records found: " . count($result1) . "\n";
    
    echo "\n=== Test 2: Report with search term ===\n";
    $result2 = $attendanceModel->getAttendanceSummaryWithFilters(date('Y-m-01'), date('Y-m-t'), 'John');
    echo "Records found: " . count($result2) . "\n";
    
    echo "\n=== Test 3: Report with class filter ===\n";
    $result3 = $attendanceModel->getAttendanceSummaryWithFilters(date('Y-m-01'), date('Y-m-t'), '', 1);
    echo "Records found: " . count($result3) . "\n";
    
    echo "\n=== Test 4: Report with both search term and class filter ===\n";
    $result4 = $attendanceModel->getAttendanceSummaryWithFilters(date('Y-m-01'), date('Y-m-t'), 'John', 1);
    echo "Records found: " . count($result4) . "\n";
    
    echo "\nAll tests completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}