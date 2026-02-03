<?php
// Migration to add payment method specific details columns to payments table

try {
    // Get database connection
    require_once 'config/config.php';
    $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // First, update the method column to include all payment methods
    $sql = "ALTER TABLE payments MODIFY COLUMN method ENUM('cash', 'cheque', 'bank_transfer', 'mobile_money', 'other') NULL";
    $pdo->exec($sql);
    
    // Add cash payment details columns
    $sql = "ALTER TABLE payments ADD COLUMN cash_payer_name VARCHAR(100) NULL AFTER term";
    $pdo->exec($sql);
    
    $sql = "ALTER TABLE payments ADD COLUMN cash_payer_phone VARCHAR(20) NULL AFTER cash_payer_name";
    $pdo->exec($sql);
    
    // Add mobile money payment details columns
    $sql = "ALTER TABLE payments ADD COLUMN mobile_money_sender_name VARCHAR(100) NULL AFTER cash_payer_phone";
    $pdo->exec($sql);
    
    $sql = "ALTER TABLE payments ADD COLUMN mobile_money_sender_number VARCHAR(20) NULL AFTER mobile_money_sender_name";
    $pdo->exec($sql);
    
    $sql = "ALTER TABLE payments ADD COLUMN mobile_money_reference VARCHAR(100) NULL AFTER mobile_money_sender_number";
    $pdo->exec($sql);
    
    // Add bank transfer payment details columns
    $sql = "ALTER TABLE payments ADD COLUMN bank_transfer_sender_bank VARCHAR(100) NULL AFTER mobile_money_reference";
    $pdo->exec($sql);
    
    $sql = "ALTER TABLE payments ADD COLUMN bank_transfer_invoice_number VARCHAR(100) NULL AFTER bank_transfer_sender_bank";
    $pdo->exec($sql);
    
    $sql = "ALTER TABLE payments ADD COLUMN bank_transfer_details TEXT NULL AFTER bank_transfer_invoice_number";
    $pdo->exec($sql);
    
    // Add cheque payment details columns
    $sql = "ALTER TABLE payments ADD COLUMN cheque_bank VARCHAR(100) NULL AFTER bank_transfer_details";
    $pdo->exec($sql);
    
    $sql = "ALTER TABLE payments ADD COLUMN cheque_number VARCHAR(50) NULL AFTER cheque_bank";
    $pdo->exec($sql);
    
    $sql = "ALTER TABLE payments ADD COLUMN cheque_details TEXT NULL AFTER cheque_number";
    $pdo->exec($sql);
    
    echo "Migration completed successfully: Added payment method specific details columns to payments table\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
}
?>