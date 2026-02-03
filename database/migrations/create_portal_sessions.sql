CREATE TABLE IF NOT EXISTS portal_sessions (
    id INT AUTO_INCREMENT PRIMARY KEY,
    user_type ENUM('student', 'parent', 'staff') NOT NULL,
    user_id INT NOT NULL,
    session_token VARCHAR(255) NOT NULL,
    ip_address VARCHAR(45),
    user_agent TEXT,
    login_time DATETIME DEFAULT CURRENT_TIMESTAMP,
    last_activity DATETIME DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
    is_active TINYINT(1) DEFAULT 1,
    INDEX (user_type, user_id),
    INDEX (session_token)
);
