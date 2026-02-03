<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\AcademicYear;
use App\Helpers\AuditHelper;

class ProfileController extends Controller
{
    private $userModel;

    public function __construct()
    {
        // Removed parent::__construct() call since parent Controller class doesn't have a constructor
        $this->userModel = new User();
    }

    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $user = $this->userModel->find($_SESSION['user']['id']);
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        $this->view('profile/index', [
            'user' => $user,
            'currentAcademicYear' => $currentAcademicYear
        ]);
    }

    public function update()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $user = $this->userModel->find($_SESSION['user']['id']);
        if (!$user) {
            $_SESSION['flash_error'] = 'User not found.';
            $this->redirect('/profile');
            return;
        }

        // Validate input
        $first_name = $_POST['first_name'] ?? '';
        $last_name = $_POST['last_name'] ?? '';
        $email = $_POST['email'] ?? '';
        $phone = $_POST['phone'] ?? '';

        // Update database
        $data = [
            'first_name' => $first_name,
            'last_name' => $last_name,
            'email' => $email,
            'phone' => $phone
        ];

        $result = $this->userModel->update($_SESSION['user']['id'], $data);

        if ($result) {
            // Update session data
            $_SESSION['user']['first_name'] = $first_name;
            $_SESSION['user']['last_name'] = $last_name;
            $_SESSION['user']['email'] = $email;
            $_SESSION['user']['phone'] = $phone;
            
            // Get current academic year for audit logging
            $academicYearModel = new AcademicYear();
            $currentAcademicYear = $academicYearModel->getCurrent();
            
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'],
                'update',
                'profiles',
                $_SESSION['user']['id'],
                $user, // old values
                $data, // new values
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            $_SESSION['flash_success'] = 'Profile updated successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to update profile.';
        }

        $this->redirect('/profile');
    }

    public function changePassword()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();

        $this->view('profile/change_password', [
            'currentAcademicYear' => $currentAcademicYear
        ]);
    }

    public function updatePassword()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $user = $this->userModel->find($_SESSION['user']['id']);
        if (!$user) {
            $_SESSION['flash_error'] = 'User not found.';
            $this->redirect('/profile');
            return;
        }

        // Validate input
        $current_password = $_POST['current_password'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        // Check if current password is correct
        if (!password_verify($current_password, $user['password_hash'])) {
            $_SESSION['flash_error'] = 'Current password is incorrect.';
            $this->redirect('/profile/change_password');
            return;
        }

        // Check if new password and confirm password match
        if ($new_password !== $confirm_password) {
            $_SESSION['flash_error'] = 'New password and confirm password do not match.';
            $this->redirect('/profile/change_password');
            return;
        }

        // Check password strength
        if (strlen($new_password) < 6) {
            $_SESSION['flash_error'] = 'Password must be at least 6 characters long.';
            $this->redirect('/profile/change_password');
            return;
        }

        // Update password
        $data = [
            'password_hash' => password_hash($new_password, PASSWORD_DEFAULT)
        ];

        $result = $this->userModel->update($_SESSION['user']['id'], $data);

        if ($result) {
            // Get current academic year for audit logging
            $academicYearModel = new AcademicYear();
            $currentAcademicYear = $academicYearModel->getCurrent();
            
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'],
                'update_password',
                'profiles',
                $_SESSION['user']['id'],
                $user, // old values
                ['password_changed' => true], // new values
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            $_SESSION['flash_success'] = 'Password updated successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to update password.';
        }

        $this->redirect('/profile');
    }
}