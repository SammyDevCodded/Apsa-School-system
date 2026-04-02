<?php
// Database Initialization Script for Futuristic School Management ERP
// Run this script to set up the database structure and initial data

require_once 'vendor/autoload.php';
require_once 'config/config.php';

echo "Futuristic School Management ERP - Database Initialization\n";
echo "==========================================================\n\n";

try {
    // Create PDO connection
    $port = defined('DB_PORT') ? DB_PORT : 3306;
    $pdo = new PDO("mysql:host=" . DB_HOST . ";port=" . $port, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS " . DB_NAME);
    $pdo->exec("USE " . DB_NAME);
    
    echo "✓ Database '" . DB_NAME . "' ready\n";
    
    // Create users table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS users (
            id INT AUTO_INCREMENT PRIMARY KEY,
            role_id INT DEFAULT 2,
            username VARCHAR(50) UNIQUE NOT NULL,
            password_hash VARCHAR(255) NOT NULL,
            first_name VARCHAR(50),
            last_name VARCHAR(50),
            email VARCHAR(100),
            phone VARCHAR(20),
            status ENUM('active', 'inactive') DEFAULT 'active',
            last_login DATETIME,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Users table created\n";
    
    // Create roles table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) UNIQUE NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Roles table created\n";
    
    // Create classes table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS classes (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) NOT NULL,
            level VARCHAR(50),
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "✓ Classes table created\n";
    
    // Insert default roles
    $stmt = $pdo->prepare("INSERT IGNORE INTO roles (id, name, description) VALUES (?, ?, ?)");
    $stmt->execute([1, 'admin', 'Administrator with full access']);
    $stmt->execute([2, 'user', 'Regular user with limited access']);
    echo "✓ Default roles inserted\n";
    
    // Insert a default admin user (password: admin123)
    $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO users (role_id, username, password_hash, first_name, last_name, email, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([1, 'admin', $passwordHash, 'Admin', 'User', 'admin@example.com', 'active']);
    echo "✓ Default admin user created (username: admin, password: admin123)\n";
    
    // Insert sample classes
    $stmt = $pdo->prepare("INSERT IGNORE INTO classes (id, name, level) VALUES (?, ?, ?)");
    $classes = [
        [1, 'Grade 1', 'Elementary'],
        [2, 'Grade 2', 'Elementary'],
        [3, 'Grade 3', 'Elementary'],
        [4, 'Grade 4', 'Elementary'],
        [5, 'Grade 5', 'Elementary'],
        [6, 'Grade 6', 'Middle School'],
        [7, 'Grade 7', 'Middle School'],
        [8, 'Grade 8', 'Middle School'],
        [9, 'Grade 9', 'High School'],
        [10, 'Grade 10', 'High School'],
        [11, 'Grade 11', 'High School'],
        [12, 'Grade 12', 'High School']
    ];
    
    foreach ($classes as $class) {
        $stmt->execute($class);
    }
    echo "✓ Sample classes added\n";
    
    echo "\nDatabase initialization completed successfully!\n";
    echo "Default admin user created:\n";
    echo "Username: admin\n";
    echo "Password: admin123\n";
    echo "Please change the password after first login.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}