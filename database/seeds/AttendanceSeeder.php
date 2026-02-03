<?php
// Seed script to add sample attendance records to the database

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Get all students
    $stmt = $pdo->prepare("SELECT id FROM students");
    $stmt->execute();
    $students = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    if (empty($students)) {
        echo "No students found. Please add students first.\n";
        exit;
    }
    
    // Generate attendance records for the last 30 days
    $startDate = new DateTime('-30 days');
    $endDate = new DateTime();
    
    $statuses = ['present', 'absent', 'late'];
    
    foreach (new DatePeriod($startDate, new DateInterval('P1D'), $endDate->modify('+1 day')) as $date) {
        // Skip weekends (Saturday and Sunday)
        if ($date->format('N') >= 6) {
            continue;
        }
        
        $formattedDate = $date->format('Y-m-d');
        
        foreach ($students as $studentId) {
            // Randomly decide if we should record attendance for this student on this date
            if (rand(1, 100) <= 95) { // 95% chance of recording attendance
                $status = $statuses[array_rand($statuses)];
                $remarks = '';
                
                if ($status === 'late') {
                    $remarks = 'Arrived 10 minutes late';
                } elseif ($status === 'absent') {
                    $remarks = rand(1, 2) === 1 ? 'Sick leave' : 'Family emergency';
                }
                
                $stmt = $pdo->prepare("
                    INSERT IGNORE INTO attendance (student_id, date, status, remarks)
                    VALUES (:student_id, :date, :status, :remarks)
                ");
                
                $stmt->execute([
                    'student_id' => $studentId,
                    'date' => $formattedDate,
                    'status' => $status,
                    'remarks' => $remarks
                ]);
            }
        }
    }
    
    echo "Sample attendance records added successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}