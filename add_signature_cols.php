<?php
require_once 'app/Core/Database.php';
require_once 'config/config.php';

use App\Core\Database;

$db = new Database();

try {
    $sql = "ALTER TABLE report_card_settings 
            ADD COLUMN headteacher_signature VARCHAR(255) NULL AFTER show_headteacher_signature,
            ADD COLUMN teacher_signature VARCHAR(255) NULL AFTER show_teacher_signature";
    $db->query($sql);
    echo "Columns added successfully.\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
