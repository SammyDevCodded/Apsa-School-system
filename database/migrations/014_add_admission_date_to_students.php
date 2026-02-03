<?php
// Migration to add admission_date column to students table

return [
    'up' => function ($db) {
        // Add admission_date column to students table
        $sql = "ALTER TABLE students ADD COLUMN admission_date DATE AFTER profile_picture";
        return $db->exec($sql);
    },
    'down' => function ($db) {
        // Remove admission_date column from students table
        $sql = "ALTER TABLE students DROP COLUMN admission_date";
        return $db->exec($sql);
    }
];