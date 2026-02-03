<?php
namespace App\Helpers;

use App\Models\User;

class PermissionHelper
{
    public static function userHasPermission($userId, $permissionName)
    {
        // For now, let's simplify this by checking user role
        // In a full implementation, we would check the role_permissions table
        
        // Get user with role
        $userModel = new User();
        $user = $userModel->find($userId);
        
        if (!$user) {
            return false;
        }
        
        // Super admin has all permissions
        if ($user['role_id'] == 1) { // Assuming role_id 1 is super admin
            return true;
        }
        
        // For this simplified version, we'll just check if the user is an admin
        // In a full implementation, we would check the specific permission
        return $user['role_id'] == 1; // Only admins have permissions for now
    }
    
    public static function requirePermission($permissionName)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            header('Location: /login');
            exit;
        }
        
        // Check if user has the required permission
        if (!self::userHasPermission($_SESSION['user']['id'], $permissionName)) {
            // Redirect to dashboard or show error
            header('Location: /dashboard');
            exit;
        }
    }
}