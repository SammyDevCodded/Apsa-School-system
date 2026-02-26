<?php
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
require_once ROOT_PATH . '/vendor/autoload.php';
require_once CONFIG_PATH . '/config.php';
require_once APP_PATH . '/Core/Database.php';

try {
    $db = new \App\Core\Database();
    $migration = require ROOT_PATH . '/database/migrations/014_add_auto_generate_format_settings.php';
    $migration['up']($db);
    echo "Migration 014 executed successfully.\n";
} catch (Exception $e) {
    echo "Error executing migration: " . $e->getMessage() . "\n";
}
