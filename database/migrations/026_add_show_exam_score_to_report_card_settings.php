<?php
// Migration Script to add show_exam_score field to report_card_settings table
// Run this script to add exam score display setting

// Database configuration
require_once dirname(__DIR__, 2) . '/config/config.php';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if column exists before adding it
    $columns = $pdo->query("SHOW COLUMNS FROM report_card_settings LIKE 'show_exam_score'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE report_card_settings ADD COLUMN show_exam_score BOOLEAN DEFAULT TRUE");
        echo "show_exam_score field added to report_card_settings table successfully!\n";
    } else {
        echo "show_exam_score field already exists in report_card_settings table.\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}