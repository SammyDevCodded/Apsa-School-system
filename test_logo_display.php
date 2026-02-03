<?php
// Test script to verify logo display functionality
require_once 'config/config.php';
require_once 'app/Helpers/TemplateHelper.php';

echo "Testing logo display functionality...\n";

// Test the getSchoolSettings function
$settings = getSchoolSettings();

echo "Current school settings:\n";
print_r($settings);

if (!empty($settings['school_logo'])) {
    echo "\nLogo URL: " . $settings['school_logo'] . "\n";
    echo "Logo should display on login page.\n";
} else {
    echo "\nNo logo set in settings.\n";
    echo "Login page will show default text.\n";
}

// Test with a sample logo
$testSettings = [
    'school_name' => 'Test School',
    'school_logo' => '/storage/uploads/test_logo.png',
    'currency_code' => 'USD',
    'currency_symbol' => '$'
];

echo "\n\nTesting with sample logo:\n";
echo "School Name: " . ($testSettings['school_name'] ?? 'Not set') . "\n";
echo "Logo Path: " . ($testSettings['school_logo'] ?? 'Not set') . "\n";
echo "HTML for logo display:\n";
if (!empty($testSettings['school_logo'])) {
    echo '<img src="' . htmlspecialchars($testSettings['school_logo']) . '" alt="' . htmlspecialchars($testSettings['school_name'] ?? 'School') . ' Logo" class="h-24 w-24 rounded-full">' . "\n";
}