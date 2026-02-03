<?php
namespace App\Core;

class Application
{
    protected $router;
    protected $db;

    public function __construct()
    {
        // Start session first
        if (session_status() == PHP_SESSION_NONE) {
            session_start();
        }
        
        // Debug session
        /*
        echo "<pre>";
        echo "Session status after start: " . session_status() . "\n";
        echo "Session ID: " . (session_id() ?? 'Not set') . "\n";
        echo "Session data: " . print_r($_SESSION ?? 'No session', true) . "\n";
        echo "</pre>";
        */
        
        $this->router = new Router();
        $this->initializeDatabase();
        $this->loadRoutes();
    }

    protected function initializeDatabase()
    {
        try {
            $this->db = new Database();
        } catch (\Exception $e) {
            // Log error but don't stop execution for now
            error_log("Database initialization failed: " . $e->getMessage());
            $this->db = null;
        }
    }

    protected function loadRoutes()
    {
        // Make router available to routes file
        $router = $this->router;
        require_once ROUTES_PATH . '/web.php';
    }

    public function run()
    {
        // Session is already started in constructor
        
        // Handle method spoofing for PUT and DELETE requests
        if (isset($_POST['_method'])) {
            $_SERVER['REQUEST_METHOD'] = strtoupper($_POST['_method']);
        }
        
        // Get the requested URI
        $uri = $_SERVER['REQUEST_URI'] ?? '/';
        
        // Remove script path from URI if needed
        $scriptPath = dirname($_SERVER['SCRIPT_NAME'] ?? '');
        if ($scriptPath !== '/' && $scriptPath !== '\\' && $scriptPath !== '.') {
            // Make sure scriptPath ends with a slash for proper replacement
            if (substr($scriptPath, -1) !== '/') {
                $scriptPath .= '/';
            }
            
            // Only remove script path if URI starts with it
            if (strpos($uri, $scriptPath) === 0) {
                $uri = substr($uri, strlen($scriptPath));
            }
        }
        
        // Add leading slash if missing
        if (strpos($uri, '/') !== 0) {
            $uri = '/' . $uri;
        }
        
        // Remove query string
        if (($pos = strpos($uri, '?')) !== false) {
            $uri = substr($uri, 0, $pos);
        }
        
        // Remove trailing slash except for root
        if (strlen($uri) > 1) {
            $uri = rtrim($uri, '/');
        }
        
        // Route the request
        $this->router->route($uri);
    }
}