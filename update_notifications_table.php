<?php
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/Core/Database.php';

use App\Core\Database;

try {
    $db = new Database();
    
    // Check if columns exist
    $columns = $db->fetchAll("SHOW COLUMNS FROM portal_notifications");
    $hasArchived = false;
    $hasDeleted = false;
    
    foreach ($columns as $col) {
        if ($col['Field'] === 'is_archived') $hasArchived = true;
        if ($col['Field'] === 'deleted_at') $hasDeleted = true;
    }
    
    if (!$hasArchived) {
        $db->query("ALTER TABLE portal_notifications ADD COLUMN is_archived TINYINT(1) DEFAULT 0 AFTER is_read");
        echo "Added 'is_archived' column.\n";
    }
    
    if (!$hasDeleted) {
        $db->query("ALTER TABLE portal_notifications ADD COLUMN deleted_at DATETIME DEFAULT NULL AFTER created_at");
        echo "Added 'deleted_at' column.\n";
    }
    
    echo "Database schema updated successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
