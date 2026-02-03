<?php
// Test script to verify file upload functionality
require_once 'vendor/autoload.php';
require_once 'config/config.php';

echo "Testing file upload functionality...\n";

// Create a simple test file
$testFilePath = ROOT_PATH . '/storage/uploads/test_file.txt';
$testContent = "This is a test file for upload testing.";

if (!is_dir(ROOT_PATH . '/storage/uploads')) {
    mkdir(ROOT_PATH . '/storage/uploads', 0755, true);
}

file_put_contents($testFilePath, $testContent);

// Simulate a file upload array
$testFile = [
    'name' => 'test_file.txt',
    'type' => 'text/plain',
    'tmp_name' => $testFilePath,
    'error' => UPLOAD_ERR_OK,
    'size' => filesize($testFilePath)
];

echo "Created test file: " . $testFilePath . "\n";
echo "File size: " . filesize($testFilePath) . " bytes\n";

// Test the file handling logic from SettingsController
$uploadDir = ROOT_PATH . '/storage/uploads/';
if (!is_dir($uploadDir)) {
    mkdir($uploadDir, 0755, true);
}

$fileName = 'logo_' . time() . '_' . basename($testFile['name']);
$uploadPath = $uploadDir . $fileName;

echo "Attempting to move file to: " . $uploadPath . "\n";

// Move uploaded file
if (move_uploaded_file($testFile['tmp_name'], $uploadPath)) {
    echo "File moved successfully!\n";
    echo "Stored path for database: /storage/uploads/" . $fileName . "\n";
    
    // Clean up test files
    unlink($uploadPath);
    echo "Cleaned up test files.\n";
} else {
    echo "Failed to move file.\n";
}

echo "File upload test completed.\n";