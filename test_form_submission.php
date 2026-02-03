<?php
// Test script to simulate form submission
require_once 'config/config.php';
require_once 'vendor/autoload.php';

use App\Core\Application;

// Simulate a PUT request to update a student
$_SERVER['REQUEST_METHOD'] = 'POST';
$_POST = [
    '_method' => 'PUT',
    'admission_no' => 'STU001',
    'first_name' => 'John',
    'last_name' => 'Doe',
    'dob' => '2010-05-15',
    'gender' => 'male',
    'class_id' => '1',
    'guardian_name' => 'Robert Doe',
    'guardian_phone' => '+1234567890',
    'address' => '123 Main St, City',
    'student_category' => 'regular_day',
    'student_category_details' => '',
    'admission_date' => '2025-10-12'
];

// Start session
session_start();
$_SESSION['user'] = ['id' => 1]; // Simulate logged in user

// Capture output
ob_start();

// Include the front controller to process the request
require_once 'public/index.php';

$output = ob_get_clean();

echo "Form submission test completed\n";
echo "Output: " . substr($output, 0, 200) . "...\n";

// Check if there are any redirects
if (headers_sent()) {
    echo "Headers were sent (possible redirect)\n";
}

// Check session for flash messages
if (isset($_SESSION['flash'])) {
    echo "Flash messages:\n";
    print_r($_SESSION['flash']);
}