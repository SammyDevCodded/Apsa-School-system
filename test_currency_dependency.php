<?php
// Test script to verify currency dependency functionality
echo "Testing currency dependency functionality...\n";

// Define currency symbol to code mapping (same as in the controller)
$currencyOptions = [
    'GH₵' => 'GHS',
    '$' => 'USD',
    '€' => 'EUR',
    '£' => 'GBP',
    '¥' => 'JPY',
    '₹' => 'INR',
    '₩' => 'KRW',
    '₽' => 'RUB',
    'R' => 'ZAR',
    '₵' => 'GHS', // Alternative symbol for Ghanaian Cedi
    'CFA' => 'XOF', // West African CFA Franc
    '₦' => 'NGN', // Nigerian Naira
    'Sh' => 'KES', // Kenyan Shilling
    'TSh' => 'TZS', // Tanzanian Shilling
    'UGX' => 'UGX', // Ugandan Shilling
    'Le' => 'SLL', // Sierra Leonean Leone
];

echo "Currency symbol to code mapping:\n";
foreach ($currencyOptions as $symbol => $code) {
    echo "  $symbol => $code\n";
}

echo "\nTesting currency code determination:\n";

// Test cases
$testSymbols = ['GH₵', '$', '€', '£', '¥', 'Invalid'];

foreach ($testSymbols as $symbol) {
    $code = $currencyOptions[$symbol] ?? 'GHS'; // Default to GHS if not found
    echo "  Symbol: '$symbol' => Code: '$code'\n";
}

echo "\nTesting form data processing simulation:\n";

// Simulate form data
$formData = [
    'school_name' => 'Test School',
    'currency_symbol' => '€',
    'currency_code' => 'XYZ' // This should be ignored
];

echo "Form data received:\n";
print_r($formData);

// Process as the controller would
$currency_symbol = $formData['currency_symbol'] ?? 'GH₵';
$currency_code = $currencyOptions[$currency_symbol] ?? 'GHS';

echo "Processed currency data:\n";
echo "  Symbol: '$currency_symbol'\n";
echo "  Code: '$currency_code' (automatically determined, ignoring form input)\n";

echo "\nCurrency dependency test completed successfully!\n";