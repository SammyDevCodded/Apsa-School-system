<?php
// Test script to simulate the login page and verify logo display
require_once 'config/config.php';
require_once 'app/Helpers/TemplateHelper.php';

echo "Simulating login page rendering...\n";

// Simulate getting school settings
$settings = getSchoolSettings();

echo "School settings for login page:\n";
echo "- School Name: " . ($settings['school_name'] ?? 'Not set') . "\n";
echo "- Logo Path: " . ($settings['school_logo'] ?? 'Not set') . "\n";

// Simulate the HTML that would be generated
echo "\nGenerated HTML for login page:\n";

if (!empty($settings['school_logo'])) {
    echo '<div class="flex justify-center">' . "\n";
    echo '    <img src="' . htmlspecialchars($settings['school_logo']) . '" alt="' . htmlspecialchars($settings['school_name'] ?? 'School') . ' Logo" class="h-24 w-24 rounded-full">' . "\n";
    echo '</div>' . "\n";
} else {
    echo "<!-- No logo to display -->\n";
}

echo '<h2 class="mt-6 text-center text-3xl font-extrabold text-gray-900">' . "\n";
if (!empty($settings['school_name'])) {
    echo '    ' . htmlspecialchars($settings['school_name']) . "\n";
} else {
    echo '    Sign in to your account' . "\n";
}
echo '</h2>' . "\n";

echo "\nLogin page simulation completed successfully!\n";