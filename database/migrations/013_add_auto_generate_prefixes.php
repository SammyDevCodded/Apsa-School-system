<?php
// Migration to add auto-generate prefixes to settings table

return [
    'up' => function ($db) {
        // Add columns for student admission number and staff employee ID prefixes
        $sql = "
            ALTER TABLE settings 
            ADD COLUMN student_admission_prefix VARCHAR(10) DEFAULT 'EPI' AFTER watermark_transparency,
            ADD COLUMN staff_employee_prefix VARCHAR(10) DEFAULT 'StID' AFTER student_admission_prefix
        ";
        
        return $db->exec($sql);
    },
    'down' => function ($db) {
        // Remove the columns
        $sql = "
            ALTER TABLE settings 
            DROP COLUMN student_admission_prefix,
            DROP COLUMN staff_employee_prefix
        ";
        
        return $db->exec($sql);
    }
];