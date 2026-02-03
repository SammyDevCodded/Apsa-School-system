<?php
// Test Apache configuration

echo "<h1>Apache Configuration Test</h1>";

// Check if mod_rewrite is loaded
if (in_array('mod_rewrite', apache_get_modules())) {
    echo "<p style='color: green;'>mod_rewrite is enabled</p>";
} else {
    echo "<p style='color: red;'>mod_rewrite is NOT enabled</p>";
}

// Check PHP version
echo "<p>PHP Version: " . phpversion() . "</p>";

// Check if required extensions are loaded
$required_extensions = ['pdo', 'pdo_mysql'];
foreach ($required_extensions as $ext) {
    if (extension_loaded($ext)) {
        echo "<p style='color: green;'>Extension '$ext' is loaded</p>";
    } else {
        echo "<p style='color: red;'>Extension '$ext' is NOT loaded</p>";
    }
}

// Test database connection
echo "<h2>Database Connection Test</h2>";
try {
    require_once 'config/config.php';
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET, DB_USER, DB_PASS);
    echo "<p style='color: green;'>Database connection successful</p>";
} catch (Exception $e) {
    echo "<p style='color: red;'>Database connection failed: " . $e->getMessage() . "</p>";
}