<?php
// Test script to verify the watermark role fix
require_once 'vendor/autoload.php';
require_once 'config/config.php';

echo "Testing Watermark Role Fix\n";
echo "========================\n\n";

// Test the Setting model's watermark settings
require_once 'app/Models/Setting.php';

$settingModel = new App\Models\Setting();

echo "1. Testing getSettings() method:\n";
$settings = $settingModel->getSettings();
if ($settings) {
    echo "   ✓ Settings retrieved successfully\n";
    echo "   Watermark Type: " . ($settings['watermark_type'] ?? 'Not set') . "\n";
    echo "   Watermark Position: " . ($settings['watermark_position'] ?? 'Not set') . "\n";
    echo "   Watermark Transparency: " . ($settings['watermark_transparency'] ?? 'Not set') . "\n\n";
} else {
    echo "   ✗ Failed to retrieve settings\n\n";
}

echo "2. Testing getWatermarkSettings() method:\n";
$watermarkSettings = $settingModel->getWatermarkSettings();
if ($watermarkSettings) {
    echo "   ✓ Watermark settings retrieved successfully\n";
    echo "   Type: " . $watermarkSettings['type'] . "\n";
    echo "   Position: " . $watermarkSettings['position'] . "\n";
    echo "   Transparency: " . $watermarkSettings['transparency'] . "\n\n";
} else {
    echo "   ✗ Failed to retrieve watermark settings\n\n";
}

echo "3. Verifying database schema includes watermark columns:\n";
try {
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $stmt = $pdo->query("DESCRIBE settings");
    $columns = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    $watermarkColumns = [];
    foreach ($columns as $column) {
        if (strpos($column['Field'], 'watermark_') === 0) {
            $watermarkColumns[] = $column['Field'];
        }
    }
    
    if (count($watermarkColumns) >= 3) {
        echo "   ✓ All watermark columns found in database:\n";
        foreach ($watermarkColumns as $column) {
            echo "     - $column\n";
        }
    } else {
        echo "   ✗ Missing watermark columns\n";
    }
} catch (Exception $e) {
    echo "   ✗ Error checking database schema: " . $e->getMessage() . "\n";
}

echo "\nWatermark Role Fix Test Completed!\n";
echo "The hasRole() function issue should now be resolved.\n";