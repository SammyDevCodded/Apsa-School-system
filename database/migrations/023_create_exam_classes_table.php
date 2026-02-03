<?php
// Migration to create exam_classes table for many-to-many relationship between exams and classes

return [
    'up' => function ($db) {
        try {
            // Create exam_classes table
            $sql = "CREATE TABLE IF NOT EXISTS exam_classes (
                id INT AUTO_INCREMENT PRIMARY KEY,
                exam_id INT NOT NULL,
                class_id INT NOT NULL,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                UNIQUE KEY unique_exam_class (exam_id, class_id),
                FOREIGN KEY (exam_id) REFERENCES exams(id) ON DELETE CASCADE ON UPDATE CASCADE,
                FOREIGN KEY (class_id) REFERENCES classes(id) ON DELETE CASCADE ON UPDATE CASCADE
            )";
            $db->execute($sql);
            
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            return false;
        }
    },
    'down' => function ($db) {
        try {
            // Drop exam_classes table
            $sql = "DROP TABLE IF EXISTS exam_classes";
            $db->execute($sql);
            
            return true;
        } catch (Exception $e) {
            echo "Error: " . $e->getMessage() . "\n";
            return false;
        }
    }
];
?>