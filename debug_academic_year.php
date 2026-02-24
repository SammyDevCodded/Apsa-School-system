<?php
require_once __DIR__ . '/vendor/autoload.php';

// Initialize the application
$app = new App\Core\Application();

// Check the database directly
$model = new App\Models\AcademicYear();
$allYears = $model->getAll();

echo "All Academic Years:\n";
print_r($allYears);

echo "\n------------------\n";

// Check using the custom SQL query
$sql = "SELECT * FROM academic_years WHERE is_current = 1 AND status = 'active' LIMIT 1";
$db = App\Core\Database::getInstance();
$result = $db->fetchOne($sql);

echo "Direct SQL Query Result (is_current=1 AND status='active'):\n";
var_dump($result);

echo "\n------------------\n";

// Check using the model method
$current = $model->getCurrentAcademicYearWithTerm();
echo "Model Method (getCurrentAcademicYearWithTerm):\n";
var_dump($current);

// Check if status might be different or casing
$sql2 = "SELECT * FROM academic_years WHERE is_current = 1";
$result2 = $db->fetchAll($sql2);
echo "\n------------------\n";
echo "Active Check (is_current=1) regardless of status:\n";
print_r($result2);
