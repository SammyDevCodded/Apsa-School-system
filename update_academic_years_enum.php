<?php
// Load config
require_once __DIR__ . '/config/config.php';

try {
    $dsn = "mysql:host=" . DB_HOST . ";dbname=" . DB_NAME . ";charset=" . DB_CHARSET;
    $pdo = new PDO($dsn, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Connected to database: " . DB_NAME . "\n";

    $sql = "ALTER TABLE academic_years MODIFY COLUMN status ENUM('active', 'inactive', 'completed') DEFAULT 'active'";
    $pdo->exec($sql);

    echo "Successfully updated academic_years table schema.\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
