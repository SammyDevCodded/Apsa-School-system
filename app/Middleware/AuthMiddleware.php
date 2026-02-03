<?php
namespace App\Middleware;

class AuthMiddleware
{
    public function handle($next)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            // Redirect to login page
            header('Location: /login');
            exit();
            return false;
        }
        
        // Continue with the request
        return $next();
    }
}