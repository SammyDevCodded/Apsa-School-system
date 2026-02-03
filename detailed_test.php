<?php
// Detailed test for the Attendance model
require_once 'config/config.php';
require_once 'app/Core/Database.php';

try {
    // Create database connection
    $db = new \App\Core\Database();
    
    // Test the query directly
    $startDate = '2025-10-01';
    $endDate = '2025-10-31';
    $searchTerm = 'John';
    
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
            WHERE (s.first_name LIKE :search_term OR 
                  s.last_name LIKE :search_term OR 
                  CONCAT(s.first_name, ' ', s.last_name) LIKE :search_term_concat OR
                  s.admission_no LIKE :search_term_admission)
            GROUP BY s.id, s.first_name, s.last_name, s.admission_no, c.name
            ORDER BY c.name, s.last_name, s.first_name";
    
    $params = [
        'start_date' => $startDate,
        'end_date' => $endDate,
        'search_term' => '%' . $searchTerm . '%',
        'search_term_concat' => '%' . $searchTerm . '%',
        'search_term_admission' => '%' . $searchTerm . '%'
    ];
    
    echo "SQL Query:\n$sql\n\n";
    echo "Parameters:\n";
    print_r($params);
    
    $stmt = $db->getConnection()->prepare($sql);
    $result = $stmt->execute($params);
    
    echo "Query executed successfully!\n";
    echo "Results: " . $stmt->rowCount() . " rows\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Stack trace: " . $e->getTraceAsString() . "\n";
}