<?php
// Migration Script to add permissions and role_permissions tables

// Database configuration
$host = 'localhost';
$dbname = 'school_erp';
$username = 'root';
$password = '';

try {
    // Create PDO connection
    $pdo = new PDO("mysql:host=$host;dbname=$dbname", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // Create permissions table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS permissions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            name VARCHAR(100) UNIQUE NOT NULL,
            description TEXT,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
        )
    ");
    
    // Create role_permissions table
    $pdo->exec("
        CREATE TABLE IF NOT EXISTS role_permissions (
            id INT AUTO_INCREMENT PRIMARY KEY,
            role_id INT NOT NULL,
            permission_id INT NOT NULL,
            created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP,
            FOREIGN KEY (role_id) REFERENCES roles(id) ON DELETE CASCADE,
            FOREIGN KEY (permission_id) REFERENCES permissions(id) ON DELETE CASCADE,
            UNIQUE KEY unique_role_permission (role_id, permission_id)
        )
    ");
    
    // Insert default permissions
    $permissions = [
        ['name' => 'view_students', 'description' => 'View students'],
        ['name' => 'create_students', 'description' => 'Create students'],
        ['name' => 'edit_students', 'description' => 'Edit students'],
        ['name' => 'delete_students', 'description' => 'Delete students'],
        ['name' => 'view_staff', 'description' => 'View staff'],
        ['name' => 'create_staff', 'description' => 'Create staff'],
        ['name' => 'edit_staff', 'description' => 'Edit staff'],
        ['name' => 'delete_staff', 'description' => 'Delete staff'],
        ['name' => 'view_academic_years', 'description' => 'View academic years'],
        ['name' => 'create_academic_years', 'description' => 'Create academic years'],
        ['name' => 'edit_academic_years', 'description' => 'Edit academic years'],
        ['name' => 'delete_academic_years', 'description' => 'Delete academic years'],
        ['name' => 'view_timetables', 'description' => 'View timetables'],
        ['name' => 'create_timetables', 'description' => 'Create timetables'],
        ['name' => 'edit_timetables', 'description' => 'Edit timetables'],
        ['name' => 'delete_timetables', 'description' => 'Delete timetables'],
        ['name' => 'view_reports', 'description' => 'View reports'],
        ['name' => 'export_reports', 'description' => 'Export reports'],
        ['name' => 'view_audit_logs', 'description' => 'View audit logs'],
        ['name' => 'view_backups', 'description' => 'View backups'],
        ['name' => 'create_backups', 'description' => 'Create backups'],
        ['name' => 'download_backups', 'description' => 'Download backups'],
        ['name' => 'delete_backups', 'description' => 'Delete backups']
    ];
    
    foreach ($permissions as $permission) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO permissions (name, description) 
            VALUES (?, ?)
        ");
        $stmt->execute([$permission['name'], $permission['description']]);
    }
    
    // Assign permissions to roles
    // Admin role (id=1) gets all permissions
    $stmt = $pdo->prepare("
        SELECT id FROM permissions
    ");
    $stmt->execute();
    $permissionIds = $stmt->fetchAll(PDO::FETCH_COLUMN);
    
    foreach ($permissionIds as $permissionId) {
        $stmt = $pdo->prepare("
            INSERT IGNORE INTO role_permissions (role_id, permission_id) 
            VALUES (?, ?)
        ");
        $stmt->execute([1, $permissionId]); // Role ID 1 is admin
    }
    
    echo "Permissions tables created successfully!\n";
    echo "Default permissions added.\n";
    echo "Admin role permissions assigned.\n";
    
} catch (PDOException $e) {
    echo "Error: " . $e->getMessage();
}