<?php
// Test script to verify the settings update fix
require_once 'vendor/autoload.php';
require_once 'config/config.php';

echo "Testing settings update fix...\n";

try {
    // Load the Settings model
    $settingModel = new App\Models\Setting();
    
    // Get current settings
    $currentSettings = $settingModel->getSettings();
    echo "Current settings:\n";
    print_r($currentSettings);
    
    // Test data - same as current settings (should return 0 affected rows)
    $data = [
        'school_name' => $currentSettings['school_name'] ?? 'APSA-ERP',
        'currency_code' => $currentSettings['currency_code'] ?? 'GHS',
        'currency_symbol' => $currentSettings['currency_symbol'] ?? 'GH₵'
    ];
    
    echo "Updating with same data (should return 0 affected rows):\n";
    print_r($data);
    
    // Update settings
    $result = $settingModel->updateSettings($data);
    
    echo "Update result: ";
    var_dump($result);
    
    if ($result !== false) {
        echo "SUCCESS: Settings update was successful (0 rows affected is still successful)\n";
    } else {
        echo "FAILURE: Settings update failed\n";
    }
    
    // Test with different data (should return 1 affected row)
    $data['school_name'] = 'Test School ' . time();
    
    echo "\nUpdating with different data (should return 1 affected row):\n";
    print_r($data);
    
    // Update settings
    $result = $settingModel->updateSettings($data);
    
    echo "Update result: ";
    var_dump($result);
    
    if ($result !== false) {
        echo "SUCCESS: Settings update was successful\n";
    } else {
        echo "FAILURE: Settings update failed\n";
    }
    
    // Reset to original name
    $data['school_name'] = $currentSettings['school_name'] ?? 'APSA-ERP';
    $settingModel->updateSettings($data);
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}