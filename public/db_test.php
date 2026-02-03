<?php
require_once dirname(__DIR__) . '/config/config.php';
require_once APP_PATH . '/Core/Database.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Database Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        table { border-collapse: collapse; width: 100%; }
        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
        th { background-color: #f2f2f2; }
    </style>
</head>
<body>
    <h1>Database Connection Test</h1>";

try {
    // Initialize database
    $db = new App\Core\Database();
    echo "<p style='color: green;'>✓ Database connection successful</p>";
    
    // Test query to get students
    $sql = "SELECT id, first_name, last_name, admission_no, profile_picture FROM students ORDER BY id LIMIT 10";
    $students = $db->fetchAll($sql);
    
    echo "<h2>Students Table (First 10 records)</h2>";
    echo "<table>
            <tr>
                <th>ID</th>
                <th>First Name</th>
                <th>Last Name</th>
                <th>Admission No</th>
                <th>Profile Picture</th>
                <th>Image Status</th>
            </tr>";
    
    foreach ($students as $student) {
        $imageUrl = !empty($student['profile_picture']) ? "/storage/uploads/" . $student['profile_picture'] : 'None';
        $filePath = !empty($student['profile_picture']) ? ROOT_PATH . '/storage/uploads/' . $student['profile_picture'] : '';
        $fileExists = !empty($filePath) && file_exists($filePath);
        $imageStatus = !empty($student['profile_picture']) ? 
            ($fileExists ? "<span style='color: green;'>✓ Found</span>" : "<span style='color: red;'>✗ Missing</span>") : 
            'N/A';
        
        echo "<tr>
                <td>" . htmlspecialchars($student['id']) . "</td>
                <td>" . htmlspecialchars($student['first_name']) . "</td>
                <td>" . htmlspecialchars($student['last_name']) . "</td>
                <td>" . htmlspecialchars($student['admission_no']) . "</td>
                <td>" . htmlspecialchars($student['profile_picture'] ?? 'None') . "</td>
                <td>$imageStatus</td>
              </tr>";
    }
    
    echo "</table>";
    
    // Test the storage/uploads directory
    echo "<h2>Storage Uploads Directory</h2>";
    $uploadsDir = ROOT_PATH . '/storage/uploads';
    if (is_dir($uploadsDir)) {
        echo "<p style='color: green;'>✓ Uploads directory exists: $uploadsDir</p>";
        
        $files = scandir($uploadsDir);
        $imageFiles = array_filter($files, function($file) {
            return $file !== '.' && $file !== '..' && preg_match('/\.(jpeg|jpg|png|gif)$/i', $file);
        });
        
        echo "<p>Found " . count($imageFiles) . " image files in uploads directory</p>";
        
        if (!empty($imageFiles)) {
            echo "<ul>";
            foreach (array_slice($imageFiles, 0, 10) as $file) {
                echo "<li>$file</li>";
            }
            if (count($imageFiles) > 10) {
                echo "<li>... and " . (count($imageFiles) - 10) . " more files</li>";
            }
            echo "</ul>";
        }
    } else {
        echo "<p style='color: red;'>✗ Uploads directory does not exist: $uploadsDir</p>";
    }
    
} catch (Exception $e) {
    echo "<p style='color: red;'>✗ Database connection failed: " . $e->getMessage() . "</p>";
}

echo "</body>
</html>";
?>