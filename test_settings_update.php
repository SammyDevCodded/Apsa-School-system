<?php
// Test script to verify the settings update fix
require_once 'vendor/autoload.php';
require_once 'config/config.php';

echo "Testing settings update fix...\n";

try {
    // Load the Settings model
    $settingModel = new App\Models\Setting();
    
    // Test data
    $data = [
        'school_name' => 'APSA-ERP Test ' . time(), // Make it unique each time
        'currency_code' => 'USD',
        'currency_symbol' => '$',
        'school_logo' => '/storage/uploads/test_logo.png'
    ];
    
    echo "Updating settings with data:\n";
    print_r($data);
    
    // Update settings
    $result = $settingModel->updateSettings($data);
    
    echo "Update result (affected rows): " . $result . "\n";
    echo "Update " . ($result !== false ? "SUCCESS" : "FAILED") . "\n";
    
    // Verify the update
    $settings = $settingModel->getSettings();
    echo "Current settings in database:\n";
    print_r($settings);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}