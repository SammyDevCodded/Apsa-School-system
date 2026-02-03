<?php
define('ROOT_PATH', __DIR__);
require_once 'config/config.php';
require_once 'app/Core/Database.php';

use App\Core\Database;

try {
    $db = new Database();
    $sql = "CREATE TABLE IF NOT EXISTS portal_notifications (
        id INT AUTO_INCREMENT PRIMARY KEY,
        user_type ENUM('student', 'parent', 'staff') NOT NULL,
        user_id INT NOT NULL,
        sender_id INT DEFAULT NULL,
        title VARCHAR(255) NOT NULL,
        message TEXT,
        attachment_path VARCHAR(255) DEFAULT NULL,
        is_read TINYINT(1) DEFAULT 0,
        created_at DATETIME DEFAULT CURRENT_TIMESTAMP,
        INDEX (user_type, user_id),
        INDEX (is_read)
    )";
    
    $db->query($sql);
    
    echo "Table 'portal_notifications' created successfully.";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage();
}
?>
