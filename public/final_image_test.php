<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/Model.php';
require_once APP_PATH . '/Models/Student.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Final Image Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .student-card { border: 1px solid #ddd; border-radius: 8px; padding: 15px; margin: 10px 0; }
        .profile-img { width: 100px; height: 100px; object-fit: cover; border-radius: 50%; }
        .no-image { width: 100px; height: 100px; background-color: #eee; border-radius: 50%; display: flex; align-items: center; justify-content: center; }
    </style>
</head>
<body>
    <h1>Final Image Display Test</h1>
    <p>This page tests if student images are properly displayed.</p>";

try {
    // Initialize database
    $db = new App\Core\Database();
    
    // Get all students with profile pictures
    $sql = "SELECT s.*, c.name as class_name 
            FROM students s 
            LEFT JOIN classes c ON s.class_id = c.id 
            WHERE s.profile_picture IS NOT NULL AND s.profile_picture != ''
            ORDER BY s.id";
    
    $students = $db->fetchAll($sql);
    
    echo "<h2>Students with Profile Pictures (" . count($students) . " found)</h2>";
    
    if (empty($students)) {
        echo "<p>No students with profile pictures found.</p>";
    } else {
        foreach ($students as $student) {
            $imageUrl = "/storage/uploads/" . htmlspecialchars($student['profile_picture']);
            $filePath = ROOT_PATH . '/storage/uploads/' . $student['profile_picture'];
            $fileExists = file_exists($filePath);
            
            echo "<div class='student-card'>";
            echo "<h3>" . htmlspecialchars($student['first_name'] . " " . $student['last_name']) . "</h3>";
            echo "<p>Admission No: " . htmlspecialchars($student['admission_no']) . "</p>";
            
            if ($fileExists) {
                echo "<img src='$imageUrl' alt='Profile Picture' class='profile-img' onerror=\"this.src='/images/default-profile.png'; this.onerror=null;\">";
                echo "<p style='color: green;'>✓ Image file exists</p>";
            } else {
                echo "<div class='no-image'>No Image</div>";
                echo "<p style='color: red;'>✗ Image file missing: $filePath</p>";
            }
            
            echo "<p>Image URL: $imageUrl</p>";
            echo "<p>File Path: $filePath</p>";
            echo "</div>";
        }
    }
} catch (Exception $e) {
    echo "<p style='color: red;'>Error: " . $e->getMessage() . "</p>";
}

echo "</body>
</html>";
?>