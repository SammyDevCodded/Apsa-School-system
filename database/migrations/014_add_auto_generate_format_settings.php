<?php
// Migration to add auto-generate formats to settings table

return [
    'up' => function ($db) {
        $sql = "
            ALTER TABLE settings 
            ADD COLUMN student_auto_format VARCHAR(20) DEFAULT 'timestamp' AFTER student_admission_prefix,
            ADD COLUMN staff_auto_format VARCHAR(20) DEFAULT 'timestamp' AFTER staff_employee_prefix
        ";
        
        return $db->execute($sql);
    },
    'down' => function ($db) {
        $sql = "
            ALTER TABLE settings 
            DROP COLUMN student_auto_format,
            DROP COLUMN staff_auto_format
        ";
        
        return $db->execute($sql);
    }
];
