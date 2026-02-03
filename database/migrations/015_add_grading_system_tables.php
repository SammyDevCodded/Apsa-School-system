<?php
// Migration to add grading system tables

return [
    'up' => function ($db) {
        // Create grading scales table
        $sql1 = "
            CREATE TABLE IF NOT EXISTS grading_scales (
                id INT AUTO_INCREMENT PRIMARY KEY,
                name VARCHAR(100) NOT NULL,
                grading_type ENUM('numeric', 'letter') NOT NULL DEFAULT 'numeric',
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
            )
        ";
        
        // Create grading rules table
        $sql2 = "
            CREATE TABLE IF NOT EXISTS grading_rules (
                id INT AUTO_INCREMENT PRIMARY KEY,
                scale_id INT NOT NULL,
                min_score DECIMAL(5,2) NOT NULL,
                max_score DECIMAL(5,2) NOT NULL,
                grade VARCHAR(10) NOT NULL,
                remark TEXT,
                created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
                updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
                FOREIGN KEY (scale_id) REFERENCES grading_scales(id) ON DELETE CASCADE
            )
        ";
        
        // Execute the queries
        $db->exec($sql1);
        $db->exec($sql2);
        
        return true;
    },
    'down' => function ($db) {
        // Drop the tables in reverse order
        $sql1 = "DROP TABLE IF EXISTS grading_rules";
        $sql2 = "DROP TABLE IF EXISTS grading_scales";
        
        $db->exec($sql1);
        $db->exec($sql2);
        
        return true;
    }
];