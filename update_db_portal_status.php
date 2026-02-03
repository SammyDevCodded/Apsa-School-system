<?php
// Define ROOT_PATH
define('ROOT_PATH', __DIR__);

// Load Config
if (file_exists(__DIR__ . '/config/config.php')) {
    require_once __DIR__ . '/config/config.php';
} else {
    die("Config file not found.");
}

// Simple autoloader
spl_autoload_register(function ($class) {
    // Only handle App namespace
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/app/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) {
        return;
    }
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) {
        require $file;
    }
});

use App\Core\Database;

try {
    $db = new Database();
    
    // Check if columns exist
    $columns = $db->fetchAll("SHOW COLUMNS FROM students");
    $hasStudentStatus = false;
    $hasParentStatus = false;
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'student_portal_status') $hasStudentStatus = true;
        if ($column['Field'] === 'parent_portal_status') $hasParentStatus = true;
    }
    
    if (!$hasStudentStatus) {
        echo "Adding student_portal_status column... ";
        $db->execute("ALTER TABLE students ADD COLUMN student_portal_status VARCHAR(20) DEFAULT 'active'");
        echo "Done.\n";
    } else {
        echo "student_portal_status column already exists.\n";
    }
    
    if (!$hasParentStatus) {
        echo "Adding parent_portal_status column... ";
        $db->execute("ALTER TABLE students ADD COLUMN parent_portal_status VARCHAR(20) DEFAULT 'active'");
        echo "Done.\n";
    } else {
        echo "parent_portal_status column already exists.\n";
    }
    
    echo "Database schema updated successfully.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
