<?php
// Seed script to add sample fee structures to the database

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Sample fee structures data
    $fees = [
        [
            'name' => 'Tuition Fee',
            'amount' => 5000.00,
            'type' => 'tuition',
            'class_id' => 1,
            'description' => 'Annual tuition fee for Grade 1 students'
        ],
        [
            'name' => 'Transport Fee',
            'amount' => 1200.00,
            'type' => 'transport',
            'class_id' => 1,
            'description' => 'Annual transport fee for Grade 1 students'
        ],
        [
            'name' => 'Feeding Fee',
            'amount' => 800.00,
            'type' => 'feeding',
            'class_id' => 1,
            'description' => 'Annual feeding fee for Grade 1 students'
        ]
    ];
    
    // Insert sample fee structures
    foreach ($fees as $fee) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO fees (name, amount, type, class_id, description)
            VALUES (:name, :amount, :type, :class_id, :description)
        ");
        
        $stmt->execute($fee);
    }
    
    echo "Sample fee structures added successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}