<?php
namespace App\Helpers;

use App\Models\Setting;

class IdGeneratorHelper
{
    private static $settingModel = null;
    
    /**
     * Get the setting model instance
     */
    private static function getSettingModel()
    {
        if (self::$settingModel === null) {
            self::$settingModel = new Setting();
        }
        return self::$settingModel;
    }
    
    /**
     * Generate a student admission number in the format: [Prefix][HHMMSS]
     * Example: EPI-143025
     */
    public static function generateAdmissionNumber()
    {
        $settings = self::getSettingModel()->getSettings();
        $prefix = $settings['student_admission_prefix'] ?? 'EPI';
        
        // Generate timestamp in HHMMSS format
        $timestamp = date('His');
        
        return $prefix . '-' . $timestamp;
    }
    
    /**
     * Generate a staff employee ID in the format: [Prefix][HHMMSS]
     * Example: StID-123455
     */
    public static function generateEmployeeId()
    {
        $settings = self::getSettingModel()->getSettings();
        $prefix = $settings['staff_employee_prefix'] ?? 'StID';
        
        // Generate timestamp in HHMMSS format
        $timestamp = date('His');
        
        return $prefix . '-' . $timestamp;
    }
    
    /**
     * Get the current admission number prefix
     */
    public static function getAdmissionPrefix()
    {
        $settings = self::getSettingModel()->getSettings();
        return $settings['student_admission_prefix'] ?? 'EPI';
    }
    
    /**
     * Get the current employee ID prefix
     */
    public static function getEmployeePrefix()
    {
        $settings = self::getSettingModel()->getSettings();
        return $settings['staff_employee_prefix'] ?? 'StID';
    }
}