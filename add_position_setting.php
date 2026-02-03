<?php
define('ROOT_PATH', __DIR__);
require_once __DIR__ . '/config/config.php';
require_once __DIR__ . '/vendor/autoload.php';
require_once __DIR__ . '/app/Core/Database.php';

use App\Core\Database;

$db = new Database();

echo "Adding show_position column to report_card_settings...\n";

try {
    // Check if column exists
    $checkSql = "SHOW COLUMNS FROM report_card_settings LIKE 'show_position'";
    $exists = $db->fetchOne($checkSql);
    
    if (!$exists) {
        $sql = "ALTER TABLE report_card_settings ADD COLUMN show_position TINYINT(1) DEFAULT 1 AFTER show_class_score";
        $db->query($sql);
        echo "Column 'show_position' added successfully.\n";
    } else {
        echo "Column 'show_position' already exists.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
