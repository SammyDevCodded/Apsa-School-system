<?php
// Migration: Create recurring_fees, recurring_fee_enrollments, and recurring_fee_entries tables

$host     = 'localhost';
$dbname   = 'school_erp';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // ── 1. recurring_fees ───────────────────────────────────────────────────────
    $pdo->exec("CREATE TABLE IF NOT EXISTS recurring_fees (
        id               INT AUTO_INCREMENT PRIMARY KEY,
        name             VARCHAR(100) NOT NULL,
        description      TEXT NULL,
        amount_per_unit  DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        billing_cycle    ENUM('daily','weekly','monthly') NOT NULL DEFAULT 'daily',
        scope            ENUM('individual','class','school') NOT NULL DEFAULT 'individual',
        academic_year_id INT NULL,
        active           TINYINT(1) NOT NULL DEFAULT 1,
        created_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        updated_at       TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
        INDEX idx_academic_year (academic_year_id),
        INDEX idx_active (active)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "Table 'recurring_fees' created or already exists.<br>\n";

    // ── 2. recurring_fee_enrollments ────────────────────────────────────────────
    $pdo->exec("CREATE TABLE IF NOT EXISTS recurring_fee_enrollments (
        id                INT AUTO_INCREMENT PRIMARY KEY,
        recurring_fee_id  INT NOT NULL,
        student_id        INT NULL,
        class_id          INT NULL,
        billing_cycle     ENUM('daily','weekly','monthly') NOT NULL DEFAULT 'daily',
        start_date        DATE NOT NULL,
        active            TINYINT(1) NOT NULL DEFAULT 1,
        created_at        TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_fee (recurring_fee_id),
        INDEX idx_student (student_id),
        INDEX idx_class (class_id)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "Table 'recurring_fee_enrollments' created or already exists.<br>\n";

    // ── 3. recurring_fee_entries ─────────────────────────────────────────────────
    $pdo->exec("CREATE TABLE IF NOT EXISTS recurring_fee_entries (
        id              INT AUTO_INCREMENT PRIMARY KEY,
        enrollment_id   INT NOT NULL,
        student_id      INT NOT NULL,
        service_date    DATE NOT NULL,
        amount          DECIMAL(10,2) NOT NULL DEFAULT 0.00,
        status          ENUM('pending','paid','waived') NOT NULL DEFAULT 'pending',
        waive_reason    TEXT NULL,
        waived_by       INT NULL,
        waived_at       TIMESTAMP NULL,
        created_at      TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
        INDEX idx_enrollment (enrollment_id),
        INDEX idx_student_date (student_id, service_date),
        INDEX idx_status (status)
    ) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4");
    echo "Table 'recurring_fee_entries' created or already exists.<br>\n";

    echo "<strong>Migration 041 completed successfully!</strong>\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
