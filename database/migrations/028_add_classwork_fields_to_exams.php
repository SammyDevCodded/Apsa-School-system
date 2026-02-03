<?php
// Migration Script to add classwork fields to exams table
// Run this script to add classwork functionality to exams

// Database configuration
require_once dirname(__DIR__, 2) . '/config/config.php';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if has_classwork column exists before adding it
    $columns = $pdo->query("SHOW COLUMNS FROM exams LIKE 'has_classwork'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE exams ADD COLUMN has_classwork BOOLEAN DEFAULT FALSE");
        echo "has_classwork field added to exams table successfully!\n";
    } else {
        echo "has_classwork field already exists in exams table.\n";
    }
    
    // Check if classwork_percentage column exists before adding it
    $columns = $pdo->query("SHOW COLUMNS FROM exams LIKE 'classwork_percentage'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE exams ADD COLUMN classwork_percentage DECIMAL(5, 2) DEFAULT 0.00");
        echo "classwork_percentage field added to exams table successfully!\n";
    } else {
        echo "classwork_percentage field already exists in exams table.\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}