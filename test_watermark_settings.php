<?php
// Test script to verify watermark settings functionality
require_once 'vendor/autoload.php';
require_once 'config/config.php';

echo "Testing Watermark Settings Functionality\n";
echo "========================================\n\n";

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

echo "3. Testing watermark options:\n";
// Define watermark options
$watermarkTypes = [
    'none' => 'None',
    'logo' => 'Logo Only',
    'name' => 'School Name Only',
    'both' => 'Logo and School Name'
];

$watermarkPositions = [
    'top-left' => 'Top Left',
    'top-center' => 'Top Center',
    'top-right' => 'Top Right',
    'middle-left' => 'Middle Left',
    'center' => 'Center',
    'middle-right' => 'Middle Right',
    'bottom-left' => 'Bottom Left',
    'bottom-center' => 'Bottom Center',
    'bottom-right' => 'Bottom Right'
];

echo "   Available Watermark Types:\n";
foreach ($watermarkTypes as $value => $label) {
    echo "     - $value: $label\n";
}

echo "\n   Available Watermark Positions:\n";
foreach ($watermarkPositions as $value => $label) {
    echo "     - $value: $label\n";
}

echo "\nWatermark Settings Test Completed!\n";