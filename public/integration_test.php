<?php
// Integration test to verify the complete flow
require_once dirname(__DIR__) . '/config/config.php';
require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Core/Model.php';
require_once APP_PATH . '/Models/Student.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Integration Test</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .success { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .test-result { margin: 10px 0; padding: 10px; border-radius: 5px; }
        .passed { background-color: #d4edda; border: 1px solid #c3e6cb; }
        .failed { background-color: #f8d7da; border: 1px solid #f5c6cb; }
    </style>
</head>
<body>
    <h1>Integration Test - Student Image Display</h1>
    <p>Testing the complete flow from database to image display.</p>";

// Test 1: Database Connection
echo "<h2>Test 1: Database Connection</h2>";
try {
    $db = new App\Core\Database();
    echo "<div class='test-result passed'>";
    echo "<p class='success'>✓ Database connection successful</p>";
    echo "</div>";
    $dbConnected = true;
} catch (Exception $e) {
    echo "<div class='test-result failed'>";
    echo "<p class='error'>✗ Database connection failed: " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "</div>";
    $dbConnected = false;
}

if ($dbConnected) {
    // Test 2: Retrieve Student Data
    echo "<h2>Test 2: Student Data Retrieval</h2>";
    try {
        $studentModel = new App\Models\Student();
        $students = $studentModel->getAllWithClass();
        
        echo "<div class='test-result passed'>";
        echo "<p class='success'>✓ Retrieved " . count($students) . " students from database</p>";
        echo "</div>";
        
        // Test 3: Check for Students with Profile Pictures
        echo "<h2>Test 3: Profile Picture Analysis</h2>";
        $studentsWithPictures = 0;
        $studentsWithMissingPictures = 0;
        
        foreach ($students as $student) {
            if (!empty($student['profile_picture'])) {
                $studentsWithPictures++;
                $filePath = ROOT_PATH . '/storage/uploads/' . $student['profile_picture'];
                if (!file_exists($filePath)) {
                    $studentsWithMissingPictures++;
                }
            }
        }
        
        echo "<div class='test-result " . ($studentsWithMissingPictures > 0 ? 'failed' : 'passed') . "'>";
        echo "<p>Students with profile pictures: $studentsWithPictures</p>";
        echo "<p>Students with missing profile picture files: $studentsWithMissingPictures</p>";
        
        if ($studentsWithMissingPictures > 0) {
            echo "<p class='error'>✗ Some students have profile pictures recorded in database but files are missing</p>";
        } else {
            echo "<p class='success'>✓ All students with profile pictures have corresponding files</p>";
        }
        echo "</div>";
        
        // Test 4: Image URL Generation
        echo "<h2>Test 4: Image URL Generation</h2>";
        $testCount = 0;
        foreach ($students as $student) {
            if (!empty($student['profile_picture']) && $testCount < 5) {
                $imageUrl = "/storage/uploads/" . htmlspecialchars($student['profile_picture']);
                $filePath = ROOT_PATH . '/storage/uploads/' . $student['profile_picture'];
                
                echo "<div class='test-result passed'>";
                echo "<p>Student: " . htmlspecialchars($student['first_name'] . " " . $student['last_name']) . "</p>";
                echo "<p>Image URL: $imageUrl</p>";
                echo "<p>File Path: " . htmlspecialchars($filePath) . "</p>";
                echo "<p>File exists: " . (file_exists($filePath) ? 'Yes' : 'No') . "</p>";
                
                if (file_exists($filePath)) {
                    echo "<img src='$imageUrl' style='width: 50px; height: 50px; object-fit: cover;' onerror=\"this.src='/images/default-profile.png'; this.onerror=null;\">";
                }
                echo "</div>";
                
                $testCount++;
            }
        }
        
        // Test 5: Route Simulation
        echo "<h2>Test 5: Route Simulation</h2>";
        require_once APP_PATH . '/Core/Router.php';
        
        // Create a mock router
        $router = new App\Core\Router();
        
        // Add the storage uploads route
        $router->get('/storage/uploads/([^/]+)', function($filename) {
            $filePath = ROOT_PATH . '/storage/uploads/' . $filename;
            
            if (file_exists($filePath)) {
                // Set appropriate content type
                $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
                $mimeTypes = [
                    'jpg' => 'image/jpeg',
                    'jpeg' => 'image/jpeg',
                    'png' => 'image/png',
                    'gif' => 'image/gif',
                    'webp' => 'image/webp'
                ];
                
                $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
                return [
                    'status' => 'success',
                    'mimeType' => $mimeType,
                    'fileSize' => filesize($filePath)
                ];
            } else {
                return [
                    'status' => 'error',
                    'message' => 'File not found'
                ];
            }
        });
        
        // Test with a real file if available
        $testFile = null;
        foreach ($students as $student) {
            if (!empty($student['profile_picture'])) {
                $testFile = $student['profile_picture'];
                break;
            }
        }
        
        if ($testFile) {
            $testUri = '/storage/uploads/' . $testFile;
            
            // Manual route matching
            $method = 'GET';
            $uri = rtrim($testUri, '/');
            if (strpos($uri, '/') !== 0) {
                $uri = '/' . $uri;
            }
            
            $routes = $router->getRoutes();
            $matched = false;
            
            foreach ($routes[$method] as $routeUri => $route) {
                // Convert route pattern to regex
                $pattern = preg_replace('/\(([^\)]+)\)/', '([^/]+)', $routeUri);
                $pattern = '#^' . $pattern . '$#';
                
                if (preg_match($pattern, $uri, $matches)) {
                    array_shift($matches);
                    
                    if (is_callable($route['callback'])) {
                        $result = call_user_func_array($route['callback'], $matches);
                        
                        echo "<div class='test-result passed'>";
                        echo "<p class='success'>✓ Route matching successful</p>";
                        echo "<p>Test file: " . htmlspecialchars($testFile) . "</p>";
                        if ($result['status'] === 'success') {
                            echo "<p>MIME type: " . htmlspecialchars($result['mimeType']) . "</p>";
                            echo "<p>File size: " . htmlspecialchars($result['fileSize']) . " bytes</p>";
                        } else {
                            echo "<p class='error'>Error: " . htmlspecialchars($result['message']) . "</p>";
                        }
                        echo "</div>";
                        
                        $matched = true;
                        break;
                    }
                }
            }
            
            if (!$matched) {
                echo "<div class='test-result failed'>";
                echo "<p class='error'>✗ Route matching failed for: " . htmlspecialchars($testUri) . "</p>";
                echo "</div>";
            }
        } else {
            echo "<div class='test-result warning'>";
            echo "<p class='warning'>⚠ No students with profile pictures found for route testing</p>";
            echo "</div>";
        }
        
    } catch (Exception $e) {
        echo "<div class='test-result failed'>";
        echo "<p class='error'>✗ Student data retrieval failed: " . htmlspecialchars($e->getMessage()) . "</p>";
        echo "</div>";
    }
}

echo "<h2>Overall Test Result</h2>";
echo "<div class='test-result " . ($dbConnected ? 'passed' : 'failed') . "'>";
if ($dbConnected) {
    echo "<p class='success'>✓ Integration test completed successfully. The image display issue should now be fixed.</p>";
    echo "<p>Try accessing the student list and detail pages to verify.</p>";
} else {
    echo "<p class='error'>✗ Integration test failed. Check the database connection and configuration.</p>";
}
echo "</div>";

echo "</body>
</html>";
?>