<?php
// Migration Script to add new fields to report_card_settings table
// Run this script to add class score and individual signature settings

// Database configuration
require_once dirname(__DIR__, 2) . '/config/config.php';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if columns exist before adding them
    $columns = $pdo->query("SHOW COLUMNS FROM report_card_settings LIKE 'show_date_of_birth'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE report_card_settings ADD COLUMN show_date_of_birth BOOLEAN DEFAULT TRUE");
    }
    
    $columns = $pdo->query("SHOW COLUMNS FROM report_card_settings LIKE 'show_class_score'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE report_card_settings ADD COLUMN show_class_score BOOLEAN DEFAULT TRUE");
    }
    
    $columns = $pdo->query("SHOW COLUMNS FROM report_card_settings LIKE 'show_teacher_signature'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE report_card_settings ADD COLUMN show_teacher_signature BOOLEAN DEFAULT TRUE");
    }
    
    $columns = $pdo->query("SHOW COLUMNS FROM report_card_settings LIKE 'show_headteacher_signature'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE report_card_settings ADD COLUMN show_headteacher_signature BOOLEAN DEFAULT TRUE");
    }
    
    $columns = $pdo->query("SHOW COLUMNS FROM report_card_settings LIKE 'show_parent_signature'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE report_card_settings ADD COLUMN show_parent_signature BOOLEAN DEFAULT TRUE");
    }
    
    echo "New report card settings fields added successfully!\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}