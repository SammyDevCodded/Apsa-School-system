<?php
define('ROOT_PATH', __DIR__);
define('APP_PATH', __DIR__ . '/app');

require_once APP_PATH . '/Core/Database.php';
require_once ROOT_PATH . '/config/config.php';

use App\Core\Database;

try {
    $db = new Database();
    
    // Check if column exists first
    $columns = $db->fetchAll("SHOW COLUMNS FROM fees LIKE 'term'");
    
    if (empty($columns)) {
        $db->execute("ALTER TABLE fees ADD COLUMN term VARCHAR(50) AFTER academic_year_id");
        echo "Column 'term' added successfully.\n";
    } else {
        echo "Column 'term' already exists.\n";
    }
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
