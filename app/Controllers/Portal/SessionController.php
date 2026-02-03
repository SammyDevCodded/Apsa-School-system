<?php
namespace App\Controllers\Portal;

use App\Core\Controller;
use App\Models\PortalSession;
use App\Models\Student;
use App\Models\User;

class SessionController extends Controller
{
    private $sessionModel;
    private $studentModel;
    private $userModel;
    private $notificationModel;

    public function __construct()
    {
        $this->sessionModel = new PortalSession();
        $this->studentModel = new Student();
        $this->userModel = new User();
        $this->notificationModel = new \App\Models\PortalNotification();
    }

    public function checkStatus()
    {
        header('Content-Type: application/json');
        
        // 1. Check if logged in
        if (!isset($_SESSION['portal_session_token'])) {
             echo json_encode(['status' => 'terminated', 'reason' => 'no_session']);
             return;
        }

        $token = $_SESSION['portal_session_token'];

        // 2. Check Session Table (Killed by Admin?)
        $isSessionActive = $this->sessionModel->checkStatus($token);

        if (!$isSessionActive) {
            echo json_encode(['status' => 'terminated', 'reason' => 'ended_by_admin']);
            return;
        }

        // 3. Heartbeat (Update Last Activity)
        $this->sessionModel->heartbeat($token);

        // 4. Check Account Status (Data Integrity)
        $role = $_SESSION['portal_role'] ?? 'guest';
        $userId = $_SESSION['student_id'] ?? ($_SESSION['user_id'] ?? null);

        if (!$userId) {
             echo json_encode(['status' => 'error', 'reason' => 'no_id']);
             return;
        }

        $accountStatus = 'active';

        if ($role === 'student') {
            $student = $this->studentModel->findById($userId);
            $accountStatus = $student['student_portal_status'] ?? 'active';
        } elseif ($role === 'parent') {
            $student = $this->studentModel->findById($userId); // Parent ID is student ID in this system context
            $accountStatus = $student['parent_portal_status'] ?? 'active';
        } elseif ($role === 'staff') {
            // Staff status in Users table usually 'active'
            $user = $this->userModel->findById($userId);
            $accountStatus = $user['status'] ?? 'active';
        }

        if ($accountStatus !== 'active') {
             echo json_encode(['status' => 'suspended', 'reason' => 'account_' . $accountStatus]);
             return;
        }

        // 5. Get Notifications Count
        $unreadCount = $this->notificationModel->getUnreadCount($role, $userId);

        echo json_encode(['status' => 'ok', 'unread_notifications' => $unreadCount]);
    }
}
