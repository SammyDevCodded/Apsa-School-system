<?php
require_once 'app/Core/Database.php';
require_once 'config/config.php';

use App\Core\Database;

$db = new Database();
$columns = $db->fetchAll("DESCRIBE report_card_settings");
print_r($columns);
