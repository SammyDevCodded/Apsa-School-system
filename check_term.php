<?php
define('ROOT_PATH', __DIR__);
define('APP_PATH', __DIR__ . '/app');

require_once APP_PATH . '/Core/Database.php';
require_once APP_PATH . '/Config/config.php';

use App\Core\Database;

$db = new Database();
$columns = $db->fetchAll("SHOW COLUMNS FROM fees");

$hasTerm = false;
foreach ($columns as $column) {
    echo "Column: " . $column['Field'] . "\n";
    if ($column['Field'] === 'term') {
        $hasTerm = true;
    }
}

if ($hasTerm) {
    echo "Term column EXISTS.\n";
} else {
    echo "Term column MISSING.\n";
}
