<?php
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
require_once ROOT_PATH . '/vendor/autoload.php';
require_once CONFIG_PATH . '/config.php';
require_once APP_PATH . '/Core/Database.php';

$db = new \App\Core\Database();
try {
    $db->execute('ALTER TABLE settings ADD COLUMN idle_timeout_minutes INT DEFAULT 0');
    echo "Success\n";
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
