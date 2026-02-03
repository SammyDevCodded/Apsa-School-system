<?php
// Script to run migration 016

require_once 'config/config.php';
require_once 'app/Core/Database.php';

use App\Core\Database;

try {
    // Create database connection using the config
    $db = new Database([
        'host' => DB_HOST,
        'dbname' => DB_NAME,
        'username' => DB_USER,
        'password' => DB_PASS,
        'charset' => DB_CHARSET
    ]);
    
    // Load migration
    $migration = require 'database/migrations/016_add_academic_year_to_exams_table.php';
    
    // Run up migration
    if ($migration['up']($db)) {
        echo "Migration 016 executed successfully!\n";
    } else {
        echo "Failed to execute migration 016\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}