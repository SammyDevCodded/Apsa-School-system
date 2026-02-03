<?php
// Seed script to add sample students to the database

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Sample students data
    $students = [
        [
            'admission_no' => 'STU001',
            'first_name' => 'John',
            'last_name' => 'Doe',
            'dob' => '2010-05-15',
            'gender' => 'male',
            'class_id' => 1,
            'guardian_name' => 'Robert Doe',
            'guardian_phone' => '+1234567890',
            'address' => '123 Main St, City'
        ],
        [
            'admission_no' => 'STU002',
            'first_name' => 'Jane',
            'last_name' => 'Smith',
            'dob' => '2011-08-22',
            'gender' => 'female',
            'class_id' => 2,
            'guardian_name' => 'Mary Smith',
            'guardian_phone' => '+1234567891',
            'address' => '456 Oak Ave, City'
        ],
        [
            'admission_no' => 'STU003',
            'first_name' => 'Michael',
            'last_name' => 'Johnson',
            'dob' => '2009-12-10',
            'gender' => 'male',
            'class_id' => 3,
            'guardian_name' => 'David Johnson',
            'guardian_phone' => '+1234567892',
            'address' => '789 Pine Rd, City'
        ]
    ];
    
    // Insert sample students
    foreach ($students as $student) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO students (admission_no, first_name, last_name, dob, gender, class_id, guardian_name, guardian_phone, address)
            VALUES (:admission_no, :first_name, :last_name, :dob, :gender, :class_id, :guardian_name, :guardian_phone, :address)
        ");
        
        $stmt->execute($student);
    }
    
    echo "Sample students added successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}