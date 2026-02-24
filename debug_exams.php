<?php
// Define application paths
define('ROOT_PATH', __DIR__);
define('APP_PATH', ROOT_PATH . '/app');
define('CONFIG_PATH', ROOT_PATH . '/config');
define('DATABASE_PATH', ROOT_PATH . '/database');

// Autoload classes
require_once ROOT_PATH . '/vendor/autoload.php';

// Load configuration
require_once CONFIG_PATH . '/config.php';

use App\Models\Exam;
use App\Models\ExamResult;

$examModel = new Exam();
$exams = $examModel->all();

$countWithDesc = 0;
$countWithDate = 0;
$total = count($exams);

foreach ($exams as $exam) {
    if (!empty($exam['description'])) {
        $countWithDesc++;
        echo "Found Exam with Description: " . $exam['name'] . " - " . $exam['description'] . "\n";
    }
    if (!empty($exam['date'])) {
        $countWithDate++;
    }
}

echo "Total Exams: $total\n";
echo "With Date: $countWithDate\n";
echo "With Description: $countWithDesc\n";
