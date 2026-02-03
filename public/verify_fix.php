<?php
// Verification script to check if the image display issue is fixed
require_once dirname(__DIR__) . '/config/config.php';

echo "<!DOCTYPE html>
<html>
<head>
    <title>Fix Verification</title>
    <style>
        body { font-family: Arial, sans-serif; margin: 20px; }
        .check { color: green; font-weight: bold; }
        .error { color: red; font-weight: bold; }
        .warning { color: orange; font-weight: bold; }
        .info { background-color: #f0f8ff; padding: 10px; margin: 10px 0; border-left: 4px solid #007acc; }
    </style>
</head>
<body>
    <h1>Image Display Issue Fix Verification</h1>
    <p>This script verifies that the changes made to fix the image display issue are correct.</p>";

// 1. Check if storage/uploads directory exists
echo "<h2>1. Directory Structure Check</h2>";
$uploadsDir = ROOT_PATH . '/storage/uploads';
if (is_dir($uploadsDir)) {
    echo "<p class='check'>✓ storage/uploads directory exists</p>";
} else {
    echo "<p class='error'>✗ storage/uploads directory does not exist</p>";
}

// 2. Check if router.php was removed
$routerFile = ROOT_PATH . '/public/router.php';
if (!file_exists($routerFile)) {
    echo "<p class='check'>✓ public/router.php correctly removed</p>";
} else {
    echo "<p class='error'>✗ public/router.php still exists (should be removed)</p>";
}

// 3. Check .htaccess configuration
$htaccessFile = ROOT_PATH . '/public/.htaccess';
if (file_exists($htaccessFile)) {
    $htaccessContent = file_get_contents($htaccessFile);
    if (strpos($htaccessContent, 'index.php [QSA,L]') !== false) {
        echo "<p class='check'>✓ .htaccess correctly configured to route to index.php</p>";
    } else {
        echo "<p class='error'>✗ .htaccess not correctly configured</p>";
        echo "<pre>" . htmlspecialchars($htaccessContent) . "</pre>";
    }
} else {
    echo "<p class='error'>✗ .htaccess file not found</p>";
}

// 4. Check StudentController upload directory
$studentControllerFile = APP_PATH . '/Controllers/StudentController.php';
if (file_exists($studentControllerFile)) {
    $controllerContent = file_get_contents($studentControllerFile);
    if (strpos($controllerContent, "ROOT_PATH . '/storage/uploads/'") !== false) {
        echo "<p class='check'>✓ StudentController correctly configured to upload to storage/uploads</p>";
    } else {
        echo "<p class='error'>✗ StudentController not correctly configured for upload directory</p>";
    }
} else {
    echo "<p class='error'>✗ StudentController file not found</p>";
}

// 5. Check if default profile image exists
$defaultImage = ROOT_PATH . '/public/images/default-profile.png';
if (file_exists($defaultImage)) {
    echo "<p class='check'>✓ Default profile image exists</p>";
} else {
    echo "<p class='warning'>⚠ Default profile image not found (will be created on first error)</p>";
}

// 6. Check if student views have onerror handlers
$studentIndexView = RESOURCES_PATH . '/views/students/index.php';
$studentShowView = RESOURCES_PATH . '/views/students/show.php';

if (file_exists($studentIndexView)) {
    $indexContent = file_get_contents($studentIndexView);
    if (strpos($indexContent, 'onerror="this.src=\'/images/default-profile.png\';') !== false) {
        echo "<p class='check'>✓ Student index view has error handling</p>";
    } else {
        echo "<p class='error'>✗ Student index view missing error handling</p>";
    }
} else {
    echo "<p class='error'>✗ Student index view not found</p>";
}

if (file_exists($studentShowView)) {
    $showContent = file_get_contents($studentShowView);
    if (strpos($showContent, 'onerror="this.src=\'/images/default-profile.png\';') !== false) {
        echo "<p class='check'>✓ Student show view has error handling</p>";
    } else {
        echo "<p class='error'>✗ Student show view missing error handling</p>";
    }
} else {
    echo "<p class='error'>✗ Student show view not found</p>";
}

// 7. Check if storage/uploads route exists
$webRoutesFile = ROUTES_PATH . '/web.php';
if (file_exists($webRoutesFile)) {
    $routesContent = file_get_contents($webRoutesFile);
    if (strpos($routesContent, '/storage/uploads/([^/]+)') !== false) {
        echo "<p class='check'>✓ Storage uploads route exists in web.php</p>";
    } else {
        echo "<p class='error'>✗ Storage uploads route missing from web.php</p>";
    }
} else {
    echo "<p class='error'>✗ web.php routes file not found</p>";
}

echo "<h2>Summary</h2>";
echo "<div class='info'>";
echo "<p>All checks completed. If all checks show green checkmarks (✓), the image display issue should be fixed.</p>";
echo "<p>If you still experience issues:</p>";
echo "<ol>";
echo "<li>Restart your WAMP server</li>";
echo "<li>Clear your browser cache</li>";
echo "<li>Check that mod_rewrite is enabled in Apache</li>";
echo "<li>Verify that the database contains correct student data with profile_picture values</li>";
echo "</ol>";
echo "</div>";

echo "</body>
</html>";
?>