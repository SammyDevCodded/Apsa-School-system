<?php
require_once 'config/config.php';

try {
    $pdo = new PDO('mysql:host=' . DB_HOST . ';dbname=' . DB_NAME, DB_USER, DB_PASS);
    $stmt = $pdo->query('SELECT id, first_name, last_name, profile_picture FROM students WHERE profile_picture IS NOT NULL AND profile_picture != ""');
    $students = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    echo "Students with profile pictures:\n";
    foreach ($students as $student) {
        echo "- ID: " . $student['id'] . ", Name: " . $student['first_name'] . " " . $student['last_name'] . ", Profile Picture: " . $student['profile_picture'] . "\n";
    }
    
    if (empty($students)) {
        echo "No students with profile pictures found.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}