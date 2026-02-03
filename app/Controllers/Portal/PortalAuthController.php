<?php
namespace App\Controllers\Portal;

use App\Core\Controller;
use App\Models\Student;
use App\Models\User;

class PortalAuthController extends Controller
{
    private $studentModel;
    private $userModel;

    private $sessionModel;

    public function __construct()
    {
        $this->studentModel = new Student();
        // User model needs to be updated or we use it as is?
        // Basic user model exists.
        $this->userModel = new User();
        $this->sessionModel = new \App\Models\PortalSession();
    }

    public function showLoginForm()
    {
        // If already logged in, redirect to respective dashboard
        if (isset($_SESSION['student_logged_in'])) {
            $this->redirect('/portal/student/dashboard');
        }
        if (isset($_SESSION['parent_logged_in'])) {
            $this->redirect('/portal/parent/dashboard');
        }
        if (isset($_SESSION['staff_portal_logged_in'])) {
            // Updated: Staff should have their own portal dashboard
            $this->redirect('/portal/staff/dashboard');
        }

        $this->view('portal/login');
    }

    public function login()
    {
        if ($this->requestMethod() === 'POST') {
            $identity = trim($_POST['identity'] ?? ''); // AdmNo or Username
            $password = $_POST['password'] ?? '';

            if (empty($identity) || empty($password)) {
                $_SESSION['portal_flash_error'] = 'Please enter both ID/Username and Password.';
                $this->redirect('/portal/login');
                return;
            }

            // 1. Check Student/Parent (by Admission No)
            $students = $this->studentModel->findByAdmissionNo($identity);
            // findByAdmissionNo returns an array of results (fetchAll), usually one.
            $student = !empty($students) ? $students[0] : null;

            if ($student) {
                // Check Student Password
                if (!empty($student['password']) && password_verify($password, $student['password'])) {
                    // Check Status
                    if (($student['student_portal_status'] ?? 'active') !== 'active') {
                        $_SESSION['portal_flash_error'] = 'Account is ' . ($student['student_portal_status'] ?? 'suspended') . '. Please contact the school administration to get your account activated.';
                        $this->redirect('/portal/login');
                        return;
                    }

                    $_SESSION['student_logged_in'] = true;
                    $_SESSION['student_id'] = $student['id'];
                    $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];
                    $_SESSION['portal_role'] = 'student';
                    
                    // Create Session Record
                    $token = bin2hex(random_bytes(32));
                    $this->sessionModel->createSession('student', $student['id'], $token);
                    $_SESSION['portal_session_token'] = $token;
                    
                    $_SESSION['portal_flash_success'] = 'Welcome back, ' . $student['first_name'] . '!';
                    $this->redirect('/portal/student/dashboard');
                    return;
                }

                // Check Parent Password
                if (!empty($student['parent_password']) && password_verify($password, $student['parent_password'])) {
                    // Check Status
                    if (($student['parent_portal_status'] ?? 'active') !== 'active') {
                        $_SESSION['portal_flash_error'] = 'Account is ' . ($student['parent_portal_status'] ?? 'suspended') . '. Please contact the school administration to get your account activated.';
                        $this->redirect('/portal/login');
                        return;
                    }

                    $_SESSION['parent_logged_in'] = true;
                    $_SESSION['student_id'] = $student['id']; // Parent views this student's data
                    $_SESSION['student_name'] = $student['first_name'] . ' ' . $student['last_name'];
                    $_SESSION['portal_role'] = 'parent';

                    // Create Session Record
                    $token = bin2hex(random_bytes(32));
                    $this->sessionModel->createSession('parent', $student['id'], $token);
                    $_SESSION['portal_session_token'] = $token;

                    $_SESSION['portal_flash_success'] = 'Welcome, Parent of ' . $student['first_name'];
                    $this->redirect('/portal/parent/dashboard');
                    return;
                }
            }

            // 2. Check Staff (by Username) via existing User table
            $user = $this->userModel->authenticate($identity, $password);
            if ($user) {
                // Check if user has a staff role or is linked to staff?
                // For now, any valid user login goes to Staff Portal if they use this form.
                // We might want to restrict to Non-Student roles if we had student users, but currently students are in specific table.
                
                $_SESSION['staff_portal_logged_in'] = true;
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['user_name'] = $user['username'];
                $_SESSION['portal_role'] = 'staff';
                
                // Create Session Record
                $token = bin2hex(random_bytes(32));
                $this->sessionModel->createSession('staff', $user['id'], $token);
                $_SESSION['portal_session_token'] = $token;
                
                // Copy to main user session if we want them to access main system too?
                // Or keep separate? The requirement says "seperate portal".
                // We'll keep it separate session keys to avoid Admin Panel access unless they login there.
                
                $_SESSION['portal_flash_success'] = 'Welcome back, ' . $user['username'];
                $this->redirect('/portal/staff/dashboard');
                return;
            }

            $_SESSION['portal_flash_error'] = 'Invalid credentials provided.';
            $this->redirect('/portal/login');
        }
    }

    public function logout()
    {
        if (isset($_SESSION['portal_session_token'])) {
            $this->sessionModel->terminate($_SESSION['portal_session_token']);
        }
        
        // Selective Logout: Do NOT destroy session, just unset portal keys
        $portalKeys = [
            'student_logged_in', 
            'parent_logged_in', 
            'staff_portal_logged_in', 
            'student_id', 
            'student_name', 
            'portal_role', 
            'portal_session_token',
            'portal_flash_error',
            'portal_flash_success',
            // If user_id/name are shared for staff portal but also used for admin, we need care.
            // For staff portal we used: user_id, user_name. 
            // Admin auth likely uses 'user' (array) or similar? We need to verify AuthController.
        ];
        
        // If staff portal uses user_id/user_name which might clash or be same as admin,
        // we should verify if admin uses those.
        // Let's unset them for now if they are portal specific context.
        // But wait, if I am admin logged in, and I log into portal as staff...
        // Check AuthController first?
        
        foreach ($portalKeys as $key) {
            unset($_SESSION[$key]);
        }
        
        // Also unset user specific keys if they were set by portal login
        if(isset($_SESSION['portal_role']) && $_SESSION['portal_role'] === 'staff') {
             unset($_SESSION['user_id']);
             unset($_SESSION['user_name']);
        }

        $_SESSION['portal_flash_success'] = 'You have been logged out successfully.';
        // Redirect to portal login
        $this->redirect('/portal/login');
    }
}
