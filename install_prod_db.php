<?php
// Script to safely run init_db.php if the database is completely empty upon container start
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/config/config.php';

try {
    // Attempt connection
    $port = defined('DB_PORT') ? DB_PORT : 3306;
    $pdo = new PDO("mysql:host=" . DB_HOST . ";port=" . $port, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if database exists and select it
    $pdo->exec("CREATE DATABASE IF NOT EXISTS `" . DB_NAME . "`");
    $pdo->exec("USE `" . DB_NAME . "`");
    
    // Check if 'users' table exists as an indicator of an initialized database
    $stmt = $pdo->query("SHOW TABLES LIKE 'users'");
    $tableExists = $stmt->rowCount() > 0;

    if (!$tableExists) {
        echo "Database looks empty. Running initial database setup...\n";
        // Run init_db.php to populate schema
        shell_exec('php ' . escapeshellarg(__DIR__ . '/init_db.php'));
        echo "Setup complete.\n";
    } else {
        echo "Database already initialized. Skipping setup.\n";
    }

} catch (Exception $e) {
    echo "Database connection failed: " . $e->getMessage() . "\n";
    // We log but don't fail, allowing Apache to start anyway if it's transient
}
