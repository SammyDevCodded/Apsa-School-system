<?php
namespace App\Controllers\Portal;

use App\Core\Controller;
use App\Models\PortalNotification;

class NotificationController extends Controller
{
    private $notificationModel;

    public function __construct()
    {
        $this->notificationModel = new PortalNotification();
    }

    private function getAuthInfo()
    {
        if (!isset($_SESSION['portal_session_token'])) return null;
        
        return [
            'role' => $_SESSION['portal_role'] ?? null,
            'id' => $_SESSION['student_id'] ?? ($_SESSION['user_id'] ?? null)
        ];
    }

    public function index()
    {
        $auth = $this->getAuthInfo();
        if (!$auth) {
            http_response_code(401);
            echo json_encode(['error' => 'Unauthorized']);
            return;
        }

        $filter = $_GET['filter'] ?? 'all';
        $notifications = $this->notificationModel->getByUser($auth['role'], $auth['id'], 50, $filter);
        echo json_encode(['notifications' => $notifications]);
    }

    public function markRead()
    {
        $auth = $this->getAuthInfo();
        if (!$auth) return;

        $id = $_POST['id'] ?? null;
        if ($id) {
            $this->notificationModel->markAsRead($id, $auth['role'], $auth['id']);
            echo json_encode(['success' => true]);
        }
    }

    public function archive()
    {
        $auth = $this->getAuthInfo();
        if (!$auth) return;

        $id = $_POST['id'] ?? null;
        if ($id) {
            $this->notificationModel->archive($id, $auth['role'], $auth['id']);
            echo json_encode(['success' => true]);
        }
    }

    public function delete()
    {
        $auth = $this->getAuthInfo();
        if (!$auth) return;

        $id = $_POST['id'] ?? null;
        if ($id) {
            $this->notificationModel->softDelete($id, $auth['role'], $auth['id']);
            echo json_encode(['success' => true]);
        }
    }
}
