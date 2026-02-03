<?php
// Test script to verify the currency helper functionality
require_once 'vendor/autoload.php';
require_once 'config/config.php';

echo "Testing CurrencyHelper functionality...\n";

// Test the CurrencyHelper
require_once 'app/Helpers/CurrencyHelper.php';

// Test with default settings (GHS - GH₵)
echo "Testing with default currency (GHS - GH₵):\n";
$amount = 1234.56;
$formatted = \App\Helpers\CurrencyHelper::formatAmount($amount);
echo "Amount: $amount\n";
echo "Formatted: $formatted\n";

// Test the getCurrencySymbol function
$symbol = \App\Helpers\CurrencyHelper::getCurrencySymbol();
echo "Currency symbol: $symbol\n";

// Test with different amounts
echo "\nTesting with different amounts:\n";
$testAmounts = [0, 100, 1000.99, 1234567.89];
foreach ($testAmounts as $amt) {
    $formatted = \App\Helpers\CurrencyHelper::formatAmount($amt);
    echo "Amount: " . number_format($amt, 2) . " => Formatted: $formatted\n";
}

echo "\nCurrencyHelper test completed successfully!\n";