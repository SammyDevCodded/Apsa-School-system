<?php
require_once 'config/config.php';
require_once 'app/Core/Database.php';

try {
    $db = new App\Core\Database();
    $sql = "INSERT INTO notifications (user_id, message, type, is_read) VALUES (?, ?, ?, ?)";
    $db->execute($sql, [1, 'Third test notification (already read)', 'warning', 1]);
    echo 'Third test notification created successfully';
} catch (Exception $e) {
    echo 'Error: ' . $e->getMessage();
}