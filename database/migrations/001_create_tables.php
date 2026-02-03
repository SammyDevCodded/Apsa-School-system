<?php
// Database Migration Script for Futuristic School Management ERP
// Run this script to set up the initial database structure

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create database if it doesn't exist
    $pdo->exec("CREATE DATABASE IF NOT EXISTS $dbname");
    $pdo->exec("USE $dbname");
    
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
    
    // Create roles table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS roles (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(50) UNIQUE NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Create students table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS students (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            admission_no VARCHAR(20) UNIQUE NOT NULL,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            dob DATE,
            gender ENUM('male', 'female', 'other'),
            class_id INT,
            guardian_name VARCHAR(100),
            guardian_phone VARCHAR(20),
            address TEXT,
            medical_info TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    
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
    
    // Create staff table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS staff (
            id INT AUTO_INCREMENT PRIMARY KEY,
            user_id INT,
            employee_id VARCHAR(20) UNIQUE NOT NULL,
            first_name VARCHAR(50) NOT NULL,
            last_name VARCHAR(50) NOT NULL,
            position VARCHAR(100),
            department VARCHAR(100),
            email VARCHAR(100),
            phone VARCHAR(20),
            hire_date DATE,
            salary DECIMAL(10, 2),
            status ENUM('active', 'inactive') DEFAULT 'active',
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (user_id) REFERENCES users(id) ON DELETE SET NULL
        )
    ");
    
    // Create subjects table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS subjects (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            code VARCHAR(20) UNIQUE NOT NULL,
            class_id INT,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
        )
    ");
    
    // Create fees table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS fees (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            amount DECIMAL(10, 2) NOT NULL,
            type ENUM('tuition', 'transport', 'feeding', 'other') DEFAULT 'other',
            class_id INT,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
        )
    ");
    
    // Create payments table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS payments (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id INT NOT NULL,
            amount DECIMAL(10, 2) NOT NULL,
            method ENUM('cash', 'cheque', 'bank_transfer') DEFAULT 'cash',
            date DATE NOT NULL,
            remarks TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE
        )
    ");
    
    // Create attendance table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS attendance (
            id INT AUTO_INCREMENT PRIMARY KEY,
            student_id INT NOT NULL,
            date DATE NOT NULL,
            status ENUM('present', 'absent', 'late') DEFAULT 'present',
            remarks TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
            UNIQUE KEY unique_student_date (student_id, date)
        )
    ");
    
    // Create exams table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS exams (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) NOT NULL,
            term ENUM('first', 'second', 'third') NOT NULL,
            class_id INT NOT NULL,
            date DATE NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE
        )
    ");
    
    // Create exam_results table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS exam_results (
            id INT AUTO_INCREMENT PRIMARY KEY,
            exam_id INT NOT NULL,
            student_id INT NOT NULL,
            subject_id INT NOT NULL,
            marks DECIMAL(5, 2) NOT NULL,
            grade VARCHAR(5) NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE,
            FOREIGN KEY (student_id) REFERENCES students(id) ON DELETE CASCADE,
            FOREIGN KEY (subject_id) REFERENCES subjects(id) ON DELETE CASCADE,
            UNIQUE KEY unique_exam_student_subject (exam_id, student_id, subject_id)
        )
    ");
    
    // Insert default roles
    $stmt = $pdo->prepare("INSERT IGNORE INTO roles (id, name, description) VALUES (?, ?, ?)");
    $stmt->execute([1, 'admin', 'Administrator with full access']);
    $stmt->execute([2, 'user', 'Regular user with limited access']);
    
    // Insert a default admin user (password: admin123)
    $passwordHash = password_hash('admin123', PASSWORD_DEFAULT);
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO users (role_id, username, password_hash, first_name, last_name, email, status) 
        VALUES (?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([1, 'admin', $passwordHash, 'Admin', 'User', 'admin@example.com', 'active']);
    
    // Insert sample classes
    $stmt = $pdo->prepare("INSERT IGNORE INTO classes (id, name, level) VALUES (?, ?, ?)");
    $stmt->execute([1, 'Grade 1', 'Elementary']);
    $stmt->execute([2, 'Grade 2', 'Elementary']);
    $stmt->execute([3, 'Grade 3', 'Elementary']);
    $stmt->execute([4, 'Grade 4', 'Elementary']);
    $stmt->execute([5, 'Grade 5', 'Elementary']);
    $stmt->execute([6, 'Grade 6', 'Middle School']);
    $stmt->execute([7, 'Grade 7', 'Middle School']);
    $stmt->execute([8, 'Grade 8', 'Middle School']);
    $stmt->execute([9, 'Grade 9', 'High School']);
    $stmt->execute([10, 'Grade 10', 'High School']);
    $stmt->execute([11, 'Grade 11', 'High School']);
    $stmt->execute([12, 'Grade 12', 'High School']);
    
    echo "Database setup completed successfully!\n";
    echo "Default admin user created:\n";
    echo "Username: admin\n";
    echo "Password: admin123\n";
    echo "Sample classes added.\n";
    echo "Please change the password after first login.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}