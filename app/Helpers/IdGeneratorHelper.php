<?php
namespace App\Helpers;

use App\Models\Setting;
use App\Models\AcademicYear;
use App\Core\Database;

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
     * Generate a student admission number
     */
    public static function generateAdmissionNumber()
    {
        $settings = self::getSettingModel()->getSettings();
        $prefix = $settings['student_admission_prefix'] ?? 'EPI';
        $format = $settings['student_auto_format'] ?? 'timestamp';
        
        if ($format === 'year_sequence') {
            return self::generateYearSequenceId($prefix, 'students', 'admission_no');
        }
        
        // Generate timestamp in HHMMSS format (default)
        $timestamp = date('His');
        return $prefix . '-' . $timestamp;
    }
    
    /**
     * Generate a staff employee ID
     */
    public static function generateEmployeeId()
    {
        $settings = self::getSettingModel()->getSettings();
        $prefix = $settings['staff_employee_prefix'] ?? 'StID';
        $format = $settings['staff_auto_format'] ?? 'timestamp';
        
        if ($format === 'year_sequence') {
            return self::generateYearSequenceId($prefix, 'users', 'employee_id'); // Assuming staff table is users and column is employee_id
        }
        
        // Generate timestamp in HHMMSS format (default)
        $timestamp = date('His');
        return $prefix . '-' . $timestamp;
    }

    /**
     * Generate a sequence ID based on year
     */
    private static function generateYearSequenceId($prefix, $tableName, $columnName)
    {
        $academicYearModel = new AcademicYear();
        $currentYear = $academicYearModel->getCurrent();
        
        if ($currentYear && !empty($currentYear['start_date'])) {
            $yearStr = date('Y', strtotime($currentYear['start_date']));
        } else {
            $yearStr = date('Y');
        }

        $basePattern = $prefix . '-' . $yearStr;
        
        $db = new Database();
        
        // Query to find the maximum existing sequence number for this prefix and year
        // We look for patterns like EPI-2026% and extract the highest
        $sql = "SELECT {$columnName} FROM {$tableName} WHERE {$columnName} LIKE :pattern ORDER BY LENGTH({$columnName}) DESC, {$columnName} DESC LIMIT 1";
        $result = $db->fetchOne($sql, [':pattern' => $basePattern . '%']);
        
        $nextSequence = 1;
        
        if ($result && isset($result[$columnName])) {
            $lastId = $result[$columnName];
            // Extract the sequence part (e.g., from EPI-2026001 -> 001)
            $lastSequenceStr = str_replace($basePattern, '', $lastId);
            if (is_numeric($lastSequenceStr)) {
                $nextSequence = intval($lastSequenceStr) + 1;
            }
        }
        
        // Pad the sequence with leading zeros (e.g., 001)
        return $basePattern . str_pad($nextSequence, 3, '0', STR_PAD_LEFT);
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
     * Get description of the current admission format
     */
    public static function getAdmissionFormatDescription()
    {
        $settings = self::getSettingModel()->getSettings();
        $prefix = $settings['student_admission_prefix'] ?? 'EPI';
        $format = $settings['student_auto_format'] ?? 'timestamp';
        
        if ($format === 'year_sequence') {
            return "Format: [Prefix]-[Year][Sequence] - Example: {$prefix}-" . date('Y') . "001";
        }
        
        return "Format: [Prefix]-[HHMMSS] - Example: {$prefix}-" . date('His');
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