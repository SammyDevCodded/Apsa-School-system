<?php
// Migration Script to add grading_scale_id field to exams table
// Run this script to add grading scale relationship to exams

// Database configuration
require_once dirname(__DIR__, 2) . '/config/config.php';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Check if column exists before adding it
    $columns = $pdo->query("SHOW COLUMNS FROM exams LIKE 'grading_scale_id'")->fetchAll();
    if (empty($columns)) {
        $pdo->exec("ALTER TABLE exams ADD COLUMN grading_scale_id INT DEFAULT NULL");
        $pdo->exec("ALTER TABLE exams ADD CONSTRAINT fk_exams_grading_scale FOREIGN KEY (grading_scale_id) REFERENCES grading_scales(id) ON DELETE SET NULL");
        echo "grading_scale_id field added to exams table successfully!\n";
    } else {
        echo "grading_scale_id field already exists in exams table.\n";
    }
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}