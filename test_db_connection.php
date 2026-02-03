<?php
// Test database connection and query
require_once 'config/config.php';

try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Test a simple query
    $stmt = $pdo->query("SELECT COUNT(*) FROM students");
    $count = $stmt->fetchColumn();
    
    echo "Database connection successful!\n";
    echo "Number of students: " . $count . "\n";
    
    // Test the attendance query
    $startDate = date('Y-m-01');
    $endDate = date('Y-m-t');
    
    $sql = "SELECT 
                s.id as student_id,
                s.first_name,
                s.last_name,
                s.admission_no,
                c.name as class_name,
                COUNT(CASE WHEN a.status = 'present' THEN 1 END) as present,
                COUNT(CASE WHEN a.status = 'absent' THEN 1 END) as absent,
                COUNT(CASE WHEN a.status = 'late' THEN 1 END) as late,
                COUNT(a.id) as total
            FROM students s
            LEFT JOIN attendance a ON s.id = a.student_id 
                AND a.date BETWEEN :start_date AND :end_date
            LEFT JOIN classes c ON s.class_id = c.id
            GROUP BY s.id, s.first_name, s.last_name, s.admission_no, c.name
            ORDER BY c.name, s.last_name, s.first_name";
            
    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        'start_date' => $startDate,
        'end_date' => $endDate
    ]);
    
    $results = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Attendance query executed successfully!\n";
    echo "Number of records: " . count($results) . "\n";
    
    if (!empty($results)) {
        echo "First record:\n";
        print_r($results[0]);
    }
    
} catch (PDOException $e) {
    echo "Database error: " . $e->getMessage() . "\n";
}