<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;

class AuthController extends Controller
{
    public function showLoginForm()
    {
        // Check if user is already logged in
        if (isset($_SESSION['user'])) {
            $this->redirect('/dashboard');
        }
        
        $this->view('auth/login');
    }

    public function login()
    {
        if ($this->requestMethod() === 'POST') {
            $username = $this->post('username');
            $password = $this->post('password');
            
            if (empty($username) || empty($password)) {
                $this->flash('error', 'Username and password are required');
                $this->redirect('/login');
            }
            
            $userModel = new User();
            $user = $userModel->authenticate($username, $password);
            
            if ($user) {
                // Set user session
                $_SESSION['user'] = $user;
                
                // Update last login
                $userModel->update($user['id'], ['last_login' => date('Y-m-d H:i:s')]);
                
                $this->flash('success', 'Login successful');
                $this->redirect('/dashboard');
            } else {
                $this->flash('error', 'Invalid username or password');
                $this->redirect('/login');
            }
        } else {
            $this->showLoginForm();
        }
    }

    public function logout()
    {
        // Selective Logout: Unset only Admin keys
        unset($_SESSION['user']);
        
        // If there are other admin specific keys, unset them here.
        // For now 'user' seems to be the main one.
        
        // Do NOT destroy session if we want to keep portal session alive.

        $this->flash('success', 'You have been logged out');
        $this->redirect('/login');
    }

    public function unlock()
    {
        if ($this->requestMethod() !== 'POST') {
            http_response_code(405);
            echo json_encode(['success' => false, 'message' => 'Method not allowed']);
            exit;
        }

        header('Content-Type: application/json');

        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'No active session']);
            exit;
        }

        $password = $this->post('password');
        if (empty($password)) {
            echo json_encode(['success' => false, 'message' => 'Password is required']);
            exit;
        }

        $userModel = new User();
        $currentUser = $_SESSION['user'];

        // 1. Check if it matches the current user
        if ($userModel->authenticate($currentUser['username'], $password)) {
            echo json_encode(['success' => true]);
            exit;
        }

        // 2. If not, check if it matches ANY super_admin
        $sql = "SELECT u.password_hash 
                FROM users u 
                JOIN roles r ON u.role_id = r.id 
                WHERE r.name = 'super_admin' AND u.status = 'active'";
        $superAdmins = $userModel->db->fetchAll($sql);

        foreach ($superAdmins as $admin) {
            if (password_verify($password, $admin['password_hash'])) {
                echo json_encode(['success' => true]);
                exit;
            }
        }

        echo json_encode(['success' => false, 'message' => 'Invalid password']);
        exit;
    }

    public function showRegisterForm()
    {
        $this->view('auth/register');
    }

    public function register()
    {
        if ($this->requestMethod() === 'POST') {
            // Registration logic would go here
            $this->flash('success', 'Registration successful');
            $this->redirect('/login');
        } else {
            $this->showRegisterForm();
        }
    }
}

