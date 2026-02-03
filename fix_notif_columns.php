<?php
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/Core/Database.php';

use App\Core\Database;

try {
    $db = new Database();
    
    // Check if columns exist
    $columns = $db->fetchAll("SHOW COLUMNS FROM portal_notifications");
    $fields = array_column($columns, 'Field');
    
    $added = [];
    
    if (!in_array('is_archived', $fields)) {
        $db->query("ALTER TABLE portal_notifications ADD COLUMN is_archived TINYINT(1) DEFAULT 0 AFTER is_read");
        $added[] = 'is_archived';
    }
    
    if (!in_array('deleted_at', $fields)) {
        $db->query("ALTER TABLE portal_notifications ADD COLUMN deleted_at DATETIME DEFAULT NULL AFTER created_at");
        $added[] = 'deleted_at';
    }
    
    echo "Added columns: " . implode(', ', $added) . "\n";
    echo "Schema updated successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
