<?php
// Seed script to add sample staff members to the database

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Sample staff data
    $staff = [
        [
            'employee_id' => 'EMP001',
            'first_name' => 'David',
            'last_name' => 'Wilson',
            'position' => 'Principal',
            'department' => 'Administration',
            'email' => 'david.wilson@school.edu',
            'phone' => '+1234567893',
            'hire_date' => '2015-09-01',
            'salary' => 75000.00,
            'status' => 'active'
        ],
        [
            'employee_id' => 'EMP002',
            'first_name' => 'Sarah',
            'last_name' => 'Johnson',
            'position' => 'Vice Principal',
            'department' => 'Administration',
            'email' => 'sarah.johnson@school.edu',
            'phone' => '+1234567894',
            'hire_date' => '2018-09-01',
            'salary' => 65000.00,
            'status' => 'active'
        ],
        [
            'employee_id' => 'EMP003',
            'first_name' => 'Michael',
            'last_name' => 'Brown',
            'position' => 'Math Teacher',
            'department' => 'Academics',
            'email' => 'michael.brown@school.edu',
            'phone' => '+1234567895',
            'hire_date' => '2020-09-01',
            'salary' => 55000.00,
            'status' => 'active'
        ]
    ];
    
    // Insert sample staff members
    foreach ($staff as $member) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO staff (employee_id, first_name, last_name, position, department, email, phone, hire_date, salary, status)
            VALUES (:employee_id, :first_name, :last_name, :position, :department, :email, :phone, :hire_date, :salary, :status)
        ");
        
        $stmt->execute($member);
    }
    
    echo "Sample staff members added successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}