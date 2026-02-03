<?php
// Test for monthly attendance functionality
require_once 'config/config.php';
require_once 'app/Core/Database.php';

try {
    // Create database connection
    $db = new \App\Core\Database();
    
    // Test getting months with attendance records
    $sql = "SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') as month, 
                   DATE_FORMAT(date, '%M %Y') as month_name
            FROM attendance 
            ORDER BY month DESC";
    $monthsWithAttendance = $db->fetchAll($sql);
    
    echo "Months with Attendance Records:\n";
    foreach ($monthsWithAttendance as $month) {
        echo "- " . $month['month_name'] . " (" . $month['month'] . ")\n";
    }
    
    // Test getting attendance dates for a specific month
    $selectedMonth = '2025-10'; // Example month
    $sql = "SELECT DISTINCT date FROM attendance 
            WHERE DATE_FORMAT(date, '%Y-%m') = :year_month
            ORDER BY date DESC";
    $attendanceDates = $db->fetchAll($sql, ['year_month' => $selectedMonth]);
    
    echo "\nAttendance Dates for " . date('F Y', strtotime($selectedMonth)) . ":\n";
    foreach ($attendanceDates as $date) {
        echo "- " . $date['date'] . "\n";
    }
    
    echo "\nAll tests completed successfully!\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}