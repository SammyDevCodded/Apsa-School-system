<?php
namespace App\Core;

use App\Models\Role;

class Controller
{
    protected function view($view, $data = [])
    {
        extract($data);
        require_once RESOURCES_PATH . "/views/{$view}.php";
    }
    
    protected function redirect($url)
    {
        header("Location: {$url}");
        exit;
    }
    
    protected function jsonResponse($data, $statusCode = 200)
    {
        http_response_code($statusCode);
        header('Content-Type: application/json');
        echo json_encode($data);
        exit;
    }
    
    protected function requestMethod()
    {
        $method = $_SERVER['REQUEST_METHOD'];
        
        // Handle method spoofing for HTML forms
        if ($method === 'POST' && isset($_POST['_method'])) {
            $spoofedMethod = strtoupper($_POST['_method']);
            if (in_array($spoofedMethod, ['PUT', 'DELETE', 'PATCH'])) {
                error_log("Method spoofing detected: $method -> $spoofedMethod");
                return $spoofedMethod;
            }
        }
        
        error_log("Request method: $method");
        return $method;
    }
    
    protected function requestBody()
    {
        return json_decode(file_get_contents('php://input'), true);
    }
    
    protected function get($key, $default = null)
    {
        return $_GET[$key] ?? $default;
    }
    
    protected function post($key, $default = null)
    {
        // First check if it's in $_POST (form submissions)
        if (isset($_POST[$key])) {
            return $_POST[$key];
        }
        
        // Then check if it's in parsed input (FormData submissions)
        $parsedInput = $this->parseInput();
        if (isset($parsedInput[$key])) {
            return $parsedInput[$key];
        }
        
        // Also check for array notation like 'class_ids[]'
        if (substr($key, -2) === '[]') {
            $baseKey = substr($key, 0, -2);
            if (isset($_POST[$baseKey]) && is_array($_POST[$baseKey])) {
                return $_POST[$baseKey];
            }
        }
        
        return $default;
    }
    
    /**
     * Parse input data from various sources
     * Handles both traditional form submissions and FormData submissions
     */
    protected function parseInput()
    {
        static $parsedData = null;
        
        if ($parsedData !== null) {
            return $parsedData;
        }
        
        $parsedData = [];
        
        // Merge GET parameters
        if (!empty($_GET)) {
            $parsedData = array_merge($parsedData, $_GET);
        }
        
        // Merge POST parameters
        if (!empty($_POST)) {
            $parsedData = array_merge($parsedData, $_POST);
        }
        
        // Parse raw input for FormData submissions
        $input = file_get_contents('php://input');
        if (!empty($input)) {
            // Check content type
            $contentType = $_SERVER['CONTENT_TYPE'] ?? '';
            
            // Handle FormData (multipart/form-data or application/x-www-form-urlencoded)
            if (strpos($contentType, 'multipart/form-data') !== false || 
                strpos($contentType, 'application/x-www-form-urlencoded') !== false) {
                
                // For multipart/form-data, we need to parse manually
                if (strpos($contentType, 'multipart/form-data') !== false) {
                    $formData = [];
                    parse_str($input, $formData);
                    $parsedData = array_merge($parsedData, $formData);
                } else {
                    // For application/x-www-form-urlencoded
                    $formData = [];
                    parse_str($input, $formData);
                    $parsedData = array_merge($parsedData, $formData);
                }
            }
        }
        
        return $parsedData;
    }
    
    protected function session($key, $default = null)
    {
        return $_SESSION[$key] ?? $default;
    }
    
    protected function setSession($key, $value)
    {
        $_SESSION[$key] = $value;
    }
    
    protected function flash($key, $message)
    {
        $_SESSION['flash_' . $key] = $message;
    }
    
    protected function getFlash($key)
    {
        $message = $_SESSION['flash_' . $key] ?? null;
        unset($_SESSION['flash_' . $key]);
        return $message;
    }
    
    /**
     * Get the role name of the currently logged in user
     * @return string Role name or empty string if not logged in
     */
    protected function getUserRole()
    {
        if (!isset($_SESSION['user']) || !isset($_SESSION['user']['role_id'])) {
            return '';
        }
        
        $roleId = $_SESSION['user']['role_id'];
        $roleModel = new Role();
        $role = $roleModel->find($roleId);
        
        return $role['name'] ?? '';
    }
    
    /**
     * Check if the current user has a specific role
     * @param string $role Role name to check
     * @return bool True if user has the specified role
     */
    protected function hasRole($role)
    {
        return $this->getUserRole() === $role;
    }
    
    /**
     * Check if the current user has one of the specified roles
     * @param array $roles Array of role names to check
     * @return bool True if user has one of the specified roles
     */
    protected function hasAnyRole($roles)
    {
        $userRole = $this->getUserRole();
        return in_array($userRole, $roles);
    }
    
    /**
     * Check if the request is an AJAX request
     * @return bool True if request is AJAX
     */
    protected function isAjaxRequest()
    {
        return !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
               strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
    }
}