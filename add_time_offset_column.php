<?php
require_once 'app/Core/Database.php';

use App\Core\Database;

try {
    $db = new Database();
    $conn = $db->connect();

    // Check if column exists
    $stmt = $conn->prepare("SHOW COLUMNS FROM settings LIKE 'time_offset_seconds'");
    $stmt->execute();
    $exists = $stmt->fetch();

    if (!$exists) {
        $sql = "ALTER TABLE settings ADD COLUMN time_offset_seconds INT DEFAULT 0";
        $conn->exec($sql);
        echo "Column 'time_offset_seconds' added successfully.\n";
    } else {
        echo "Column 'time_offset_seconds' already exists.\n";
    }

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
