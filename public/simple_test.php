<?php
// Simple test to see if the issue is with the application or web server
echo "Simple test\n";
echo "==========\n";

// Test if we can access the storage directory
$testFile = dirname(__DIR__) . '/storage/uploads/student_1760470187_68eea4abd370a.jpeg';
echo "Test file path: " . $testFile . "\n";
echo "File exists: " . (file_exists($testFile) ? 'Yes' : 'No') . "\n";

// Test if we can read the file
if (file_exists($testFile)) {
    echo "File size: " . filesize($testFile) . " bytes\n";
    
    // Try to read first few bytes
    $handle = fopen($testFile, 'rb');
    if ($handle) {
        $bytes = fread($handle, 10);
        fclose($handle);
        echo "First 10 bytes: " . bin2hex($bytes) . "\n";
    }
}

// Test the image URL
$imageUrl = '/storage/uploads/student_1760470187_68eea4abd370a.jpeg';
echo "Image URL: " . $imageUrl . "\n";