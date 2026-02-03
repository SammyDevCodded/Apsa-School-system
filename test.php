<?php
// Test script to verify the application is working correctly

require_once 'vendor/autoload.php';
require_once 'config/config.php';

echo "Futuristic School Management ERP - Test Script\n";
echo "==============================================\n\n";

// Test database connection
try {
    $db = new \App\Core\Database();
    echo "✓ Database connection: SUCCESS\n";
} catch (Exception $e) {
    echo "✗ Database connection: FAILED - " . $e->getMessage() . "\n";
}

// Test if required directories exist
$requiredDirs = ['app', 'config', 'database', 'public', 'resources', 'routes', 'tests'];
foreach ($requiredDirs as $dir) {
    if (is_dir($dir)) {
        echo "✓ Directory {$dir}: EXISTS\n";
    } else {
        echo "✗ Directory {$dir}: MISSING\n";
    }
}

// Test if required files exist
$requiredFiles = [
    'public/index.php',
    'app/Core/Application.php',
    'app/Core/Router.php',
    'app/Core/Database.php',
    'app/Core/Model.php',
    'app/Core/Controller.php',
    'config/config.php'
];

foreach ($requiredFiles as $file) {
    if (file_exists($file)) {
        echo "✓ File {$file}: EXISTS\n";
    } else {
        echo "✗ File {$file}: MISSING\n";
    }
}

echo "\nTest completed.\n";