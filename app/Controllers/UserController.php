<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Role;
use App\Models\AcademicYear;
use App\Helpers\AuditHelper;

class UserController extends Controller
{
    private $userModel;
    private $roleModel;

    public function __construct()
    {
        // Removed parent::__construct() call since parent Controller class doesn't have a constructor
        $this->userModel = new User();
        $this->roleModel = new Role();
    }

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

        $users = $this->userModel->all();
        $roles = $this->roleModel->all();
        
        // Add role names to users
        foreach ($users as &$user) {
            foreach ($roles as $role) {
                if ($role['id'] == $user['role_id']) {
                    $user['role_name'] = $role['name'];
                    break;
                }
            }
        }
        
        $this->view('users/index', [
            'users' => $users,
            'roles' => $roles
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

        $roles = $this->roleModel->all();
        
        // Filter roles based on user permissions
        // Only super admins can assign super_admin role
        if (!$this->hasRole('super_admin')) {
            $roles = array_filter($roles, function($role) {
                return $role['name'] !== 'super_admin';
            });
        }
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        $this->view('users/create', [
            'roles' => array_values($roles), // Re-index array after filtering
            'currentAcademicYear' => $currentAcademicYear
        ]);
    }

    public function store()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasRole('super_admin')) {
            $this->redirect('/dashboard');
        }

        // Validate input
        $username = $_POST['username'] ?? '';
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        $role_id = $_POST['role_id'] ?? '';
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';

        // Basic validation
        if (empty($username) || empty($password) || empty($role_id)) {
            $_SESSION['flash_error'] = 'Username, password, and role are required.';
            $this->redirect('/users/create');
            return;
        }

        // Check if passwords match
        if ($password !== $confirm_password) {
            $_SESSION['flash_error'] = 'Passwords do not match.';
            $this->redirect('/users/create');
            return;
        }

        // Check if username already exists
        $existingUser = $this->userModel->findByUsername($username);
        if ($existingUser) {
            $_SESSION['flash_error'] = 'Username already exists.';
            $this->redirect('/users/create');
            return;
        }

        // Check password strength
        if (strlen($password) < 6) {
            $_SESSION['flash_error'] = 'Password must be at least 6 characters long.';
            $this->redirect('/users/create');
            return;
        }

        // Verify that the role being assigned is allowed for this user
        if (!$this->hasRole('super_admin')) {
            $role = $this->roleModel->find($role_id);
            if ($role && $role['name'] === 'super_admin') {
                $_SESSION['flash_error'] = 'You do not have permission to assign the super admin role.';
                $this->redirect('/users/create');
                return;
            }
        }

        // Create user
        $data = [
            'username' => $username,
            'password' => $password,
            'role_id' => $role_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'status' => 'active'
        ];

        $result = $this->userModel->createWithPassword($data);

        if ($result) {
            // Get current academic year for audit logging
            $academicYearModel = new AcademicYear();
            $currentAcademicYear = $academicYearModel->getCurrent();
            
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'],
                'create',
                'users',
                $result, // user ID
                null,
                $data,
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            $_SESSION['flash_success'] = 'User created successfully.';
            $this->redirect('/users');
        } else {
            $_SESSION['flash_error'] = 'Failed to create user.';
            $this->redirect('/users/create');
        }
    }

    public function edit($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasRole('super_admin')) {
            $this->redirect('/dashboard');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['flash_error'] = 'User not found.';
            $this->redirect('/users');
            return;
        }

        $roles = $this->roleModel->all();
        
        // Filter roles based on user permissions
        // Only super admins can assign super_admin role
        if (!$this->hasRole('super_admin')) {
            $roles = array_filter($roles, function($role) {
                return $role['name'] !== 'super_admin';
            });
            
            // If the user being edited has super_admin role, regular admins can't edit them
            $userRoleData = $this->roleModel->find($user['role_id']);
            if ($userRoleData && $userRoleData['name'] === 'super_admin') {
                $_SESSION['flash_error'] = 'You do not have permission to edit super admin users.';
                $this->redirect('/users');
                return;
            }
        }
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        $this->view('users/edit', [
            'user' => $user,
            'roles' => array_values($roles), // Re-index array after filtering
            'currentAcademicYear' => $currentAcademicYear
        ]);
    }

    public function update($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasRole('super_admin')) {
            $this->redirect('/dashboard');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['flash_error'] = 'User not found.';
            $this->redirect('/users');
            return;
        }

        // Validate input
        $username = $_POST['username'] ?? '';
        $role_id = $_POST['role_id'] ?? '';
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $status = $_POST['status'] ?? 'active';

        // Basic validation
        if (empty($username) || empty($role_id)) {
            $_SESSION['flash_error'] = 'Username and role are required.';
            $this->redirect("/users/{$id}/edit");
            return;
        }

        // Check if username already exists for another user
        $existingUser = $this->userModel->findByUsername($username);
        if ($existingUser && $existingUser['id'] != $id) {
            $_SESSION['flash_error'] = 'Username already exists.';
            $this->redirect("/users/{$id}/edit");
            return;
        }

        // Verify that the role being assigned is allowed for this user
        if (!$this->hasRole('super_admin')) {
            $role = $this->roleModel->find($role_id);
            if ($role && $role['name'] === 'super_admin') {
                $_SESSION['flash_error'] = 'You do not have permission to assign the super admin role.';
                $this->redirect("/users/{$id}/edit");
                return;
            }
            
            // If the user being edited has super_admin role, regular admins can't edit them
            $userRoleData = $this->roleModel->find($user['role_id']);
            if ($userRoleData && $userRoleData['name'] === 'super_admin') {
                $_SESSION['flash_error'] = 'You do not have permission to edit super admin users.';
                $this->redirect('/users');
                return;
            }
        }

        // Update user
        $data = [
            'username' => $username,
            'role_id' => $role_id,
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone,
            'status' => $status
        ];

        $result = $this->userModel->update($id, $data);

        if ($result !== false) {
            // Get current academic year for audit logging
            $academicYearModel = new AcademicYear();
            $currentAcademicYear = $academicYearModel->getCurrent();
            
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'],
                'update',
                'users',
                $id,
                $user, // old values
                $data, // new values
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            $_SESSION['flash_success'] = 'User updated successfully.';
            $this->redirect('/users');
        } else {
            $_SESSION['flash_error'] = 'Failed to update user.';
            $this->redirect("/users/{$id}/edit");
        }
    }

    public function changePassword($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasRole('super_admin')) {
            $this->redirect('/dashboard');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['flash_error'] = 'User not found.';
            $this->redirect('/users');
            return;
        }
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();

        $this->view('users/change_password', [
            'user' => $user,
            'currentAcademicYear' => $currentAcademicYear
        ]);
    }

    public function updatePassword($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasRole('super_admin')) {
            $this->redirect('/dashboard');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['flash_error'] = 'User not found.';
            $this->redirect('/users');
            return;
        }

        // Validate input
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Check if passwords match
        if ($password !== $confirm_password) {
            $_SESSION['flash_error'] = 'Passwords do not match.';
            $this->redirect("/users/{$id}/change_password");
            return;
        }

        // Check password strength
        if (strlen($password) < 6) {
            $_SESSION['flash_error'] = 'Password must be at least 6 characters long.';
            $this->redirect("/users/{$id}/change_password");
            return;
        }

        // Update password using the model's updatePassword method
        $result = $this->userModel->updatePassword($id, $password);

        if ($result) {
            // Get current academic year for audit logging
            $academicYearModel = new AcademicYear();
            $currentAcademicYear = $academicYearModel->getCurrent();
            
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'],
                'update_password',
                'users',
                $id,
                $user, // old values
                ['password_changed' => true], // new values
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            $_SESSION['flash_success'] = 'Password updated successfully.';
            $this->redirect('/users');
        } else {
            $_SESSION['flash_error'] = 'Failed to update password.';
            $this->redirect("/users/{$id}/change_password");
        }
    }

    public function delete($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasRole('super_admin')) {
            $this->redirect('/dashboard');
        }

        $user = $this->userModel->find($id);
        if (!$user) {
            $_SESSION['flash_error'] = 'User not found.';
            $this->redirect('/users');
            return;
        }

        // Prevent users from deleting themselves
        if ($user['id'] == $_SESSION['user']['id']) {
            $_SESSION['flash_error'] = 'You cannot delete your own account.';
            $this->redirect('/users');
            return;
        }

        // Prevent deletion of super admin users
        $userRoleData = $this->roleModel->find($user['role_id']);
        if ($userRoleData && $userRoleData['name'] === 'super_admin') {
            $_SESSION['flash_error'] = 'You cannot delete super admin users.';
            $this->redirect('/users');
            return;
        }

        $result = $this->userModel->delete($id);

        if ($result) {
            // Get current academic year for audit logging
            $academicYearModel = new AcademicYear();
            $currentAcademicYear = $academicYearModel->getCurrent();
            
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'],
                'delete',
                'users',
                $id,
                $user, // old values
                null, // new values
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            $_SESSION['flash_success'] = 'User deleted successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to delete user.';
        }

        $this->redirect('/users');
    }
}