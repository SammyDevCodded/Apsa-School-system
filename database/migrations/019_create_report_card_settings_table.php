<?php
// Migration Script for Report Card Settings Table
// Run this script to create the report_card_settings table

// Database configuration
require_once dirname(__DIR__, 2) . '/config/config.php';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create report_card_settings table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS report_card_settings (
            id INT AUTO_INCREMENT PRIMARY KEY,
            logo_position ENUM('top-left', 'top-center', 'top-right', 'bottom-left', 'bottom-center', 'bottom-right') DEFAULT 'top-left',
            show_school_name BOOLEAN DEFAULT TRUE,
            show_school_address BOOLEAN DEFAULT TRUE,
            show_school_logo BOOLEAN DEFAULT TRUE,
            show_student_photo BOOLEAN DEFAULT TRUE,
            show_grading_scale BOOLEAN DEFAULT TRUE,
            show_attendance BOOLEAN DEFAULT TRUE,
            show_comments BOOLEAN DEFAULT TRUE,
            show_signatures BOOLEAN DEFAULT TRUE,
            show_date_of_birth BOOLEAN DEFAULT TRUE,
            show_class_score BOOLEAN DEFAULT TRUE,
            show_teacher_signature BOOLEAN DEFAULT TRUE,
            show_headteacher_signature BOOLEAN DEFAULT TRUE,
            show_parent_signature BOOLEAN DEFAULT TRUE,
            header_font_size INT DEFAULT 16,
            body_font_size INT DEFAULT 12,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    
    // Insert default report card settings
    $stmt = $pdo->prepare("
        INSERT IGNORE INTO report_card_settings 
        (id, logo_position, show_school_name, show_school_address, show_school_logo, 
         show_student_photo, show_grading_scale, show_attendance, show_comments, show_signatures,
         show_date_of_birth, show_class_score, show_teacher_signature, show_headteacher_signature, show_parent_signature,
         header_font_size, body_font_size) 
        VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)
    ");
    $stmt->execute([
        1, 
        'top-left', 
        true, 
        true, 
        true, 
        true, 
        true, 
        true, 
        true, 
        true, 
        true,
        true, 
        true, 
        true, 
        true, 
        16, 
        12
    ]);
    
    echo "Report card settings table created successfully!\n";
    echo "Default report card settings inserted.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}