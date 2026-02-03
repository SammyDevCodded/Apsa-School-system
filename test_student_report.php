<?php
// Test the new student attendance report functionality
require_once 'vendor/autoload.php';

// Define application paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('PUBLIC_PATH', ROOT_PATH . '/public');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('DATABASE_PATH', ROOT_PATH . '/database');
define('RESOURCES_PATH', ROOT_PATH . '/resources');
define('ROUTES_PATH', ROOT_PATH . '/routes');

// Load configuration
require_once CONFIG_PATH . '/config.php';

use App\Models\Attendance;
use App\Models\Student;

try {
    // Test the new methods in the Attendance model
    $attendanceModel = new Attendance();
    
    // Test getDetailedAttendanceByStudent method
    echo "Testing getDetailedAttendanceByStudent method...\n";
    $detailedAttendance = $attendanceModel->getDetailedAttendanceByStudent(1, '2025-10-01', '2025-10-31');
    echo "Found " . count($detailedAttendance) . " detailed attendance records for student ID 1\n";
    
    // Test getAttendanceStatsByStudent method
    echo "\nTesting getAttendanceStatsByStudent method...\n";
    $attendanceStats = $attendanceModel->getAttendanceStatsByStudent(1, '2025-10-01', '2025-10-31');
    echo "Attendance stats for student ID 1:\n";
    print_r($attendanceStats);
    
    echo "\nAll tests completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}