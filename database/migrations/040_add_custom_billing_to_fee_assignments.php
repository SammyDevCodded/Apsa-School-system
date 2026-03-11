<?php
// Migration: Add custom_amount and billing_description to fee_assignments table
// This supports the "Proceed to Billing" feature for partial-fee students

$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    // Add custom_amount column if it doesn't exist
    $checkColumn = $pdo->query("SHOW COLUMNS FROM fee_assignments LIKE 'custom_amount'");
    if ($checkColumn->rowCount() === 0) {
        $pdo->exec("ALTER TABLE fee_assignments ADD COLUMN custom_amount DECIMAL(10,2) NULL DEFAULT NULL COMMENT 'Custom fee amount for partial-billing scenarios'");
        echo "Column 'custom_amount' added to fee_assignments.<br>\n";
    } else {
        echo "Column 'custom_amount' already exists.<br>\n";
    }

    // Add billing_description column if it doesn't exist
    $checkDesc = $pdo->query("SHOW COLUMNS FROM fee_assignments LIKE 'billing_description'");
    if ($checkDesc->rowCount() === 0) {
        $pdo->exec("ALTER TABLE fee_assignments ADD COLUMN billing_description TEXT NULL DEFAULT NULL COMMENT 'Reason/description for custom billing amount'");
        echo "Column 'billing_description' added to fee_assignments.<br>\n";
    } else {
        echo "Column 'billing_description' already exists.<br>\n";
    }

    echo "<strong>Migration completed successfully!</strong>\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}
