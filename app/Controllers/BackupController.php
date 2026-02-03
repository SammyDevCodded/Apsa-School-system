<?php
namespace App\Controllers;

use App\Core\Controller;

class BackupController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasRole('super_admin')) {
            $this->redirect('/dashboard');
        }

        // Get list of backup files
        $backupDir = ROOT_PATH . '/storage/backups';
        $backups = [];
        
        if (is_dir($backupDir)) {
            $files = scandir($backupDir);
            foreach ($files as $file) {
                if (pathinfo($file, PATHINFO_EXTENSION) === 'sql') {
                    $filePath = $backupDir . '/' . $file;
                    $backups[] = [
                        'name' => $file,
                        'size' => filesize($filePath),
                        'date' => date('Y-m-d H:i:s', filemtime($filePath))
                    ];
                }
            }
            
            // Sort by date, newest first
            usort($backups, function($a, $b) {
                return strtotime($b['date']) - strtotime($a['date']);
            });
        }

        $this->view('backups/index', [
            'backups' => $backups
        ]);
    }

    public function create()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasRole('super_admin')) {
            $this->redirect('/dashboard');
        }

        // Database configuration
        $host = 'localhost';
        $dbname = 'school_erp';
        $username = 'root';
        $password = '';

        try {
            // Create PDO connection
            $pdo = new \PDO("mysql:host=$host;dbname=$dbname", $username, $password);
            $pdo->setAttribute(\PDO::ATTR_ERRMODE, \PDO::ERRMODE_EXCEPTION);

            // Get all table names
            $tables = [];
            $stmt = $pdo->query("SHOW TABLES");
            while ($row = $stmt->fetch(\PDO::FETCH_NUM)) {
                $tables[] = $row[0];
            }

            // Generate backup content
            $backupContent = "-- School ERP Database Backup\n";
            $backupContent .= "-- Generated on: " . date('Y-m-d H:i:s') . "\n\n";

            foreach ($tables as $table) {
                // Get table structure
                $backupContent .= "\n-- Table structure for table `$table`\n";
                $stmt = $pdo->query("SHOW CREATE TABLE `$table`");
                $row = $stmt->fetch(\PDO::FETCH_NUM);
                $backupContent .= $row[1] . ";\n\n";

                // Get table data
                $backupContent .= "-- Dumping data for table `$table`\n";
                $stmt = $pdo->query("SELECT * FROM `$table`");
                while ($row = $stmt->fetch(\PDO::FETCH_ASSOC)) {
                    $backupContent .= "INSERT INTO `$table` VALUES (";
                    $values = [];
                    foreach ($row as $value) {
                        $values[] = is_null($value) ? 'NULL' : $pdo->quote($value);
                    }
                    $backupContent .= implode(', ', $values) . ");\n";
                }
                $backupContent .= "\n";
            }

            // Create backup directory if it doesn't exist
            $backupDir = ROOT_PATH . '/storage/backups';
            if (!is_dir($backupDir)) {
                mkdir($backupDir, 0755, true);
            }

            // Save backup file
            $filename = 'backup_' . date('Y-m-d_H-i-s') . '.sql';
            $filePath = $backupDir . '/' . $filename;
            file_put_contents($filePath, $backupContent);

            $_SESSION['flash_success'] = 'Backup created successfully: ' . $filename;
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Failed to create backup: ' . $e->getMessage();
        }

        $this->redirect('/backups');
    }

    public function download($filename)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasRole('super_admin')) {
            $this->redirect('/dashboard');
        }

        $backupDir = ROOT_PATH . '/storage/backups';
        $filePath = $backupDir . '/' . basename($filename);

        if (file_exists($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'sql') {
            header('Content-Type: application/sql');
            header('Content-Disposition: attachment; filename="' . basename($filePath) . '"');
            header('Content-Length: ' . filesize($filePath));
            readfile($filePath);
            exit;
        } else {
            $_SESSION['flash_error'] = 'Backup file not found.';
            $this->redirect('/backups');
        }
    }

    public function delete($filename)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasRole('super_admin')) {
            $this->redirect('/dashboard');
        }

        $backupDir = ROOT_PATH . '/storage/backups';
        $filePath = $backupDir . '/' . basename($filename);

        if (file_exists($filePath) && pathinfo($filePath, PATHINFO_EXTENSION) === 'sql') {
            if (unlink($filePath)) {
                $_SESSION['flash_success'] = 'Backup deleted successfully.';
            } else {
                $_SESSION['flash_error'] = 'Failed to delete backup.';
            }
        } else {
            $_SESSION['flash_error'] = 'Backup file not found.';
        }

        $this->redirect('/backups');
    }
}