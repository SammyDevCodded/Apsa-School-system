<?php
namespace App\Helpers;

use App\Models\AcademicYear;

class AcademicYearHelper
{
    /**
     * Get the current academic year with term
     * Ensures that a term is always returned, defaulting to "1st Term" if not set
     * 
     * @return array|null The current academic year with term, or null if no current academic year
     */
    public static function getCurrentAcademicYearWithTerm()
    {
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        // If no current academic year, return null
        if (!$currentAcademicYear) {
            return null;
        }
        
        // If no term is set, default to "1st Term"
        if (empty($currentAcademicYear['term'])) {
            $currentAcademicYear['term'] = '1st Term';
        }
        
        return $currentAcademicYear;
    }
    
    /**
     * Get the current term from the active academic year
     * 
     * @return string The current term, or "1st Term" if not set
     */
    public static function getCurrentTerm()
    {
        $currentAcademicYear = self::getCurrentAcademicYearWithTerm();
        return $currentAcademicYear ? $currentAcademicYear['term'] : '1st Term';
    }
}