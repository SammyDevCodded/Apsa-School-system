<?php
// Health check script for Futuristic School Management ERP

require_once 'vendor/autoload.php';
require_once 'config/config.php';

echo "Futuristic School Management ERP - Health Check\n";
echo "===============================================\n\n";

// Check PHP version
echo "PHP Version: " . PHP_VERSION . "\n";
echo "Required: 7.4+\n";
if (version_compare(PHP_VERSION, '7.4.0') >= 0) {
    echo "✓ PHP version OK\n\n";
} else {
    echo "✗ PHP version too old\n\n";
}

// Check required extensions
$requiredExtensions = ['pdo', 'pdo_mysql'];
foreach ($requiredExtensions as $ext) {
    if (extension_loaded($ext)) {
        echo "✓ Extension {$ext}: Loaded\n";
    } else {
        echo "✗ Extension {$ext}: Not loaded\n";
    }
}
echo "\n";

// Check database connection
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    echo "✓ Database connection: SUCCESS\n";
    
    // Check if tables exist
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    if ($stmt->rowCount() > 0) {
        echo "✓ Users table exists\n";
        
        // Check if admin user exists
        $stmt = $pdo->prepare("SELECT COUNT(*) as count FROM users WHERE username = ?");
        $stmt->execute(['admin']);
        $result = $stmt->fetch();
        if ($result['count'] > 0) {
            echo "✓ Admin user exists\n";
        } else {
            echo "? Admin user not found\n";
        }
    } else {
        echo "? Users table not found\n";
    }
} catch (Exception $e) {
    echo "✗ Database connection: FAILED - " . $e->getMessage() . "\n";
}
echo "\n";

// Check required directories
$requiredDirs = ['app', 'config', 'database', 'public', 'resources', 'routes'];
foreach ($requiredDirs as $dir) {
    if (is_dir($dir)) {
        echo "✓ Directory {$dir}: EXISTS\n";
    } else {
        echo "✗ Directory {$dir}: MISSING\n";
    }
}
echo "\n";

// Check required files
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
echo "\n";

echo "Health check completed.\n";