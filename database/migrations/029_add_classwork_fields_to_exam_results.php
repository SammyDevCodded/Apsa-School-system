<?php
// Migration Script to add classwork fields to exam_results table
// Run this script to add classwork functionality to exam results

// Database configuration
require_once dirname(__DIR__, 2) . '/config/config.php';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if exam_marks column exists before adding it
    $columns = $pdo->query("SHOW COLUMNS FROM exam_results LIKE 'exam_marks'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE exam_results ADD COLUMN exam_marks DECIMAL(5, 2) DEFAULT NULL");
        echo "exam_marks field added to exam_results table successfully!\n";
    } else {
        echo "exam_marks field already exists in exam_results table.\n";
    }
    
    // Check if classwork_marks column exists before adding it
    $columns = $pdo->query("SHOW COLUMNS FROM exam_results LIKE 'classwork_marks'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE exam_results ADD COLUMN classwork_marks DECIMAL(5, 2) DEFAULT NULL");
        echo "classwork_marks field added to exam_results table successfully!\n";
    } else {
        echo "classwork_marks field already exists in exam_results table.\n";
    }
    
    // Check if final_marks column exists before adding it
    $columns = $pdo->query("SHOW COLUMNS FROM exam_results LIKE 'final_marks'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE exam_results ADD COLUMN final_marks DECIMAL(5, 2) DEFAULT NULL");
        echo "final_marks field added to exam_results table successfully!\n";
    } else {
        echo "final_marks field already exists in exam_results table.\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}