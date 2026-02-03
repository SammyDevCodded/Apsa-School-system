<?php
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/Core/Database.php';
require_once 'app/Core/Model.php';
require_once 'app/Models/PortalNotification.php';

use App\Core\Database;
use App\Models\PortalNotification;

try {
    $db = new Database();
    echo "DB Connection: OK\n";
    
    // 1. Check Columns
    $columns = $db->fetchAll("SHOW COLUMNS FROM portal_notifications");
    $fields = array_column($columns, 'Field');
    echo "Columns: " . implode(', ', $fields) . "\n";
    
    $missing = [];
    if (!in_array('is_archived', $fields)) $missing[] = 'is_archived';
    if (!in_array('deleted_at', $fields)) $missing[] = 'deleted_at';
    
    if (!empty($missing)) {
        die("ERROR: Missing columns: " . implode(', ', $missing) . "\n");
    }
    echo "Schema Check: OK\n";
    
    // 2. Test Model Query
    // Create a mock notification if empty?
    $notif = new PortalNotification();
    
    // Try to fetch (using dummy ID if needed, or just see if query executes)
    // We assume 'student' and id 1 might exist, or just use parameters that are safe
    try {
        $result = $notif->getByUser('student', 1, 5, 'all');
        echo "Query Test (All): OK. Count: " . count($result) . "\n";
    } catch (Exception $e) {
        echo "Query Test (All): FAILED. " . $e->getMessage() . "\n";
    }

} catch (Exception $e) {
    echo "Fatal Error: " . $e->getMessage() . "\n";
}
?>
