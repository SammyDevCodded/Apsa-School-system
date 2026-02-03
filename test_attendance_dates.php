<?php
// Simple test for attendance dates functionality
require_once 'config/config.php';
require_once 'app/Core/Database.php';

try {
    // Create database connection
    $db = new \App\Core\Database();
    
    // Test getting all attendance dates
    $sql = "SELECT DISTINCT date FROM attendance ORDER BY date DESC";
    $attendanceDates = $db->fetchAll($sql);
    
    echo "Attendance Dates:\n";
    foreach ($attendanceDates as $date) {
        echo "- " . $date['date'] . "\n";
    }
    
    // Test getting recent attendance records
    $sql = "SELECT a.*, s.first_name, s.last_name, s.admission_no, c.name as class_name
            FROM attendance a 
            LEFT JOIN students s ON a.student_id = s.id
            LEFT JOIN classes c ON s.class_id = c.id
            ORDER BY a.date DESC, a.created_at DESC
            LIMIT 5";
    $recentAttendance = $db->fetchAll($sql);
    
    echo "\nRecent Attendance Records:\n";
    foreach ($recentAttendance as $record) {
        echo "- " . $record['date'] . " - " . $record['first_name'] . " " . $record['last_name'] . 
             " (" . ($record['class_name'] ?? 'N/A') . ") - " . $record['status'] . "\n";
    }
    
    echo "\nAll tests completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}