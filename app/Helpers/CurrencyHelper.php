<?php
namespace App\Helpers;

use App\Models\Setting;

class CurrencyHelper
{
    private static $settingsModel = null;
    
    public static function getCurrencySymbol()
    {
        if (self::$settingsModel === null) {
            self::$settingsModel = new Setting();
        }
        
        $currency = self::$settingsModel->getCurrency();
        return $currency['symbol'];
    }
    
    public static function formatAmount($amount)
    {
        $symbol = self::getCurrencySymbol();
        return $symbol . number_format($amount, 2);
    }
}