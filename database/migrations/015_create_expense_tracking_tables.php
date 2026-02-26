<?php
// Migration: Create Expense Tracking Tables
// Description: Creates tables for tracking expenses, categories, payment requests, and a cash book ledger.

require_once __DIR__ . '/../../vendor/autoload.php';

// Try to load Env configuration
if (file_exists(__DIR__ . '/../../.env')) {
    $lines = file(__DIR__ . '/../../.env', FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
    foreach ($lines as $line) {
        if (strpos(trim($line), '#') === 0) continue;
        list($name, $value) = explode('=', $line, 2);
        $_ENV[trim($name)] = trim($value);
    }
}

$host = $_ENV['DB_HOST'] ?? 'localhost';
$dbname = $_ENV['DB_DATABASE'] ?? 'school_erp';
$username = $_ENV['DB_USERNAME'] ?? 'root';
$password = $_ENV['DB_PASSWORD'] ?? '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

    echo "Running migration: Create Expense Tracking Tables...\n";

    // Create Expense Categories Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS expense_categories (
            id INT AUTO_INCREMENT PRIMARY KEY,
            category_name VARCHAR(100) NOT NULL UNIQUE,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "Table 'expense_categories' created/verified.\n";
    
    // Seed default categories
    $categories = [
        ['Office Supplies', 'General office materials like pens, paper, folders, etc.'],
        ['Utility Bills', 'Electricity, water, internet, and phone bills.'],
        ['Maintenance', 'Facility repairs, cleaning supplies, and general upkeep.'],
        ['Staff Pay/allowance', 'Salaries, bonuses, and special allowances for staff members.'],
        ['Kitchen', 'Food supplies, kitchen equipment, and dining maintenance.'],
        ['School bus', 'Fuel, repairs, and maintenance for school transportation.'],
        ['Miscellaneous', 'Uncategorized or rare expense types.']
    ];
    
    $stmtCategory = $pdo->prepare("INSERT IGNORE INTO expense_categories (category_name, description) VALUES (?, ?)");
    foreach ($categories as $cat) {
        $stmtCategory->execute($cat);
    }
    echo "Default 'expense_categories' seeded.\n";

    // Create Expenses Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS expenses (
            id INT AUTO_INCREMENT PRIMARY KEY,
            expense_code VARCHAR(50) UNIQUE NOT NULL,
            category_id INT NOT NULL,
            title VARCHAR(255) NOT NULL,
            description TEXT,
            amount DECIMAL(12, 2) NOT NULL,
            payment_method ENUM('cash', 'bank', 'mobile_money') DEFAULT 'cash',
            expense_date DATE NOT NULL,
            added_by INT NOT NULL,
            status ENUM('pending', 'approved', 'rejected') DEFAULT 'approved',
            staff_id INT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (category_id) REFERENCES expense_categories(id) ON DELETE RESTRICT,
            FOREIGN KEY (added_by) REFERENCES users(id) ON DELETE RESTRICT,
            FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE SET NULL
        )
    ");
    echo "Table 'expenses' created/verified.\n";

    // Create Payment Requests Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS payment_requests (
            id INT AUTO_INCREMENT PRIMARY KEY,
            request_code VARCHAR(50) UNIQUE NOT NULL,
            requested_by INT NOT NULL,
            amount DECIMAL(12, 2) NOT NULL,
            purpose TEXT NOT NULL,
            status ENUM('pending', 'approved', 'rejected', 'paid') DEFAULT 'pending',
            approved_by INT DEFAULT NULL,
            staff_id INT DEFAULT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP,
            FOREIGN KEY (requested_by) REFERENCES users(id) ON DELETE RESTRICT,
            FOREIGN KEY (approved_by) REFERENCES users(id) ON DELETE SET NULL,
            FOREIGN KEY (staff_id) REFERENCES staff(id) ON DELETE SET NULL
        )
    ");
    echo "Table 'payment_requests' created/verified.\n";

    // Create Cash Book Table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS cash_book (
            id INT AUTO_INCREMENT PRIMARY KEY,
            transaction_code VARCHAR(50) UNIQUE NOT NULL,
            transaction_type ENUM('debit', 'credit') NOT NULL,
            reference_type ENUM('expense', 'payment_request', 'fee_payment', 'other_income') NOT NULL,
            reference_id INT NOT NULL,
            amount DECIMAL(12, 2) NOT NULL,
            balance_after DECIMAL(12, 2) NOT NULL,
            transaction_date DATE NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            updated_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        )
    ");
    echo "Table 'cash_book' created/verified.\n";

    echo "Migration completed successfully!\n";

} catch (PDOException $e) {
    echo "Error: " . $e->getMessage() . "\n";
    exit(1);
}
