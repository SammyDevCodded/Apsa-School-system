<?php
// Migration Script to add academic_year_id column to exams table

return [
    'up' => function ($db) {
        try {
            // Add academic_year_id column to exams table
            $sql = "ALTER TABLE exams ADD COLUMN academic_year_id INT DEFAULT NULL AFTER class_id";
            $db->execute($sql);
            
            // Add foreign key constraint
            $sql2 = "ALTER TABLE exams ADD CONSTRAINT fk_exams_academic_year 
                     FOREIGN KEY (academic_year_id) REFERENCES academic_years(id) ON DELETE SET NULL";
            $db->execute($sql2);
            
            // Update existing exams to use the current academic year
            $currentAcademicYear = $db->fetchOne("SELECT id FROM academic_years WHERE is_current = 1");
            if ($currentAcademicYear) {
                $sql3 = "UPDATE exams SET academic_year_id = :academic_year_id WHERE academic_year_id IS NULL";
                $db->execute($sql3, ['academic_year_id' => $currentAcademicYear['id']]);
            }
            
            return true;
        } catch (Exception $e) {
            // Column might already exist, continue
            return true;
        }
    },
    'down' => function ($db) {
        try {
            // Remove foreign key constraint first
            $db->execute("ALTER TABLE exams DROP FOREIGN KEY fk_exams_academic_year");
        } catch (Exception $e) {
            // Constraint might not exist, continue
        }
        
        try {
            // Remove academic_year_id column
            $db->execute("ALTER TABLE exams DROP COLUMN academic_year_id");
        } catch (Exception $e) {
            // Column might not exist, continue
        }
        
        return true;
    }
];