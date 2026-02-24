<?php
// Define application paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('DATABASE_PATH', ROOT_PATH . '/database');
define('PUBLIC_PATH', ROOT_PATH . '/public');

// Autoload classes
require_once ROOT_PATH . '/vendor/autoload.php';
require_once CONFIG_PATH . '/config.php';

$_SESSION['user'] = ['id' => 1, 'username' => 'admin', 'role' => 'admin'];

$controller = new \App\Controllers\ReportsController();
$examResultModel = new \App\Models\ExamResult();

// Simulate getAvailableFilters call as done in getFilterOptions
$options = $examResultModel->getAvailableFilters([]);

if (!empty($options['exams'])) {
    echo "AJAX Exam Data Keys:\n";
    $exam = $options['exams'][0];
    $relevant = array_intersect_key($exam, array_flip(['id', 'name', 'date', 'description']));
    var_export($relevant);
} else {
    echo "No exams found via AJAX simulation.\n";
}
