<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Notification;

class NotificationController extends Controller
{
    private $notificationModel;

    public function __construct()
    {
        // Removed parent::__construct() call since parent Controller class doesn't have a constructor
        $this->notificationModel = new Notification();
    }

    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $userId = $_SESSION['user']['id'];
        $notifications = $this->notificationModel->getAllForUser($userId);
        $unreadCount = $this->notificationModel->getUnreadCount($userId);
        
        // Mark all as read when viewing
        $this->notificationModel->markAllAsRead($userId);
        
        $this->view('notifications/index', [
            'notifications' => $notifications,
            'unreadCount' => $unreadCount
        ]);
    }

    public function getNotifications()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $userId = $_SESSION['user']['id'];
        $notifications = $this->notificationModel->getAllForUser($userId);
        
        // Limit to 10 most recent notifications for the dropdown
        $notifications = array_slice($notifications, 0, 10);
        
        echo json_encode([
            'success' => true,
            'notifications' => $notifications,
            'unreadCount' => $this->notificationModel->getUnreadCount($userId)
        ]);
    }

    public function markAsRead($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $notification = $this->notificationModel->find($id);
        
        // Check if notification belongs to user
        if ($notification && $notification['user_id'] == $_SESSION['user']['id']) {
            $this->notificationModel->markAsRead($id);
        }
        
        // Redirect back to notifications
        $this->redirect('/notifications');
    }

    public function markAllAsRead()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            echo json_encode(['success' => false, 'message' => 'Not authenticated']);
            return;
        }

        $userId = $_SESSION['user']['id'];
        $this->notificationModel->markAllAsRead($userId);
        
        echo json_encode([
            'success' => true,
            'message' => 'All notifications marked as read',
            'unreadCount' => 0
        ]);
    }

    public function getUnreadCount()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            echo json_encode(['count' => 0]);
            return;
        }

        $userId = $_SESSION['user']['id'];
        $unreadCount = $this->notificationModel->getUnreadCount($userId);
        
        echo json_encode(['count' => $unreadCount]);
    }
}