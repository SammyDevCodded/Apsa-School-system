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
    // Instantiate Database directly (no Singleton pattern in this codebase apparently)
    $db = new Database();
    
    // Check if columns exist
    $columns = $db->fetchAll("SHOW COLUMNS FROM students");
    $hasPassword = false;
    $hasParentPassword = false;
    
    foreach ($columns as $column) {
        if ($column['Field'] === 'password') $hasPassword = true;
        if ($column['Field'] === 'parent_password') $hasParentPassword = true;
    }
    
    if (!$hasPassword) {
        echo "Adding password column... ";
        $db->execute("ALTER TABLE students ADD COLUMN password VARCHAR(255) NULL");
        echo "Done.\n";
    } else {
        echo "Password column already exists.\n";
    }
    
    if (!$hasParentPassword) {
        echo "Adding parent_password column... ";
        $db->execute("ALTER TABLE students ADD COLUMN parent_password VARCHAR(255) NULL");
        echo "Done.\n";
    } else {
        echo "Parent password column already exists.\n";
    }
    
    echo "Database schema updated successfully.\n";
    
} catch (Exception $e) {
    echo "Error: " . $e->getMessage() . "\n";
    echo "Trace: " . $e->getTraceAsString() . "\n";
}
