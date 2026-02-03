<?php
// Template helper functions for checking user roles in views

if (!function_exists('getUserRole')) {
    function getUserRole() {
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role_id'])) {
            return '';
        }
        
        try {
            // Simple database connection using existing config
            // Check if constants are defined before using them to avoid notices if required multiple times
            if (!defined('DB_HOST')) {
               require_once ROOT_PATH . '/config/config.php';
            }
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->prepare("SELECT name FROM roles WHERE id = ?");
            $stmt->execute([$_SESSION['user']['role_id']]);
            $role = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $role['name'] ?? '';
        } catch (Exception $e) {
            return '';
        }
    }
}

if (!function_exists('hasRole')) {
    function hasRole($role) {
        return getUserRole() === $role;
    }
}

if (!function_exists('hasAnyRole')) {
    function hasAnyRole($roles) {
        $userRole = getUserRole();
        return in_array($userRole, $roles);
    }
}

if (!function_exists('getSchoolSettings')) {
    function getSchoolSettings() {
        try {
             if (!defined('DB_HOST')) {
                require_once ROOT_PATH . '/config/config.php';
            }
            $pdo = new PDO("mysql:host=" . DB_HOST . ";dbname=" . DB_NAME, DB_USER, DB_PASS);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
            
            $stmt = $pdo->prepare("SELECT * FROM settings WHERE id = 1");
            $stmt->execute();
            $settings = $stmt->fetch(PDO::FETCH_ASSOC);
            
            return $settings ?: [
                'school_name' => 'APSA-ERP',
                'school_logo' => null,
                'currency_code' => 'GHS',
                'currency_symbol' => 'GH₵'
            ];
        } catch (Exception $e) {
            // Return default settings if there's an error
            return [
                'school_name' => 'APSA-ERP',
                'school_logo' => null,
                'currency_code' => 'GHS',
                'currency_symbol' => 'GH₵'
            ];
        }
    }
}

if (!function_exists('isActiveRoute')) {
    function isActiveRoute($route) {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        return $currentPath === $route;
    }
}

if (!function_exists('isActiveGroup')) {
    function isActiveGroup($routes) {
        $currentPath = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
        foreach ($routes as $route) {
            if (strpos($currentPath, $route) === 0) {
                return true;
            }
        }
        return false;
    }
}
?>