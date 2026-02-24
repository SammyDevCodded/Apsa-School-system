<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Staff;
use App\Models\ClassModel;
use App\Models\Notification;

class DashboardController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Get user data
        $user = $_SESSION['user'];
        
        // Get statistics
        $studentModel = new Student();
        $totalStudents = $studentModel->count();
        
        $userModel = new User();
        $totalUsers = $userModel->count();
        
        $classModel = new ClassModel();
        $totalClasses = $classModel->count();
        
        $staffModel = new Staff();
        $totalStaff = $staffModel->count();
        
        // Get recent students
        $recentStudents = $studentModel->getAllWithClass();
        // Limit to 5 most recent students
        $recentStudents = array_slice($recentStudents, 0, 5);
        
        // Get notifications
        $notificationModel = new Notification();
        $recentNotifications = $notificationModel->getAllForUser($user['id']);
        // Limit to 5 most recent notifications
        $recentNotifications = array_slice($recentNotifications, 0, 5);
        $unreadNotificationCount = $notificationModel->getUnreadCount($user['id']);

        // Analytics Data
        $studentStats = $studentModel->getStatistics();
        $studentsByClass = $studentModel->getCountByClass();
        
        $paymentModel = new \App\Models\Payment();
        $monthlyRevenue = $paymentModel->getMonthlyReport([], '', 1, 6); // Last 6 months
        
        $settingModel = new \App\Models\Setting();
        $currency = $settingModel->getCurrency();
        
        // Academic Year Countdown Logic
        $academicYearModel = new \App\Models\AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        $daysRemaining = null;
        
        if ($currentAcademicYear && !empty($currentAcademicYear['end_date'])) {
            $endDate = new \DateTime($currentAcademicYear['end_date']);
            $today = new \DateTime();
            
            if ($endDate > $today) {
                $interval = $today->diff($endDate);
                $daysRemaining = $interval->days;
            } else {
                $daysRemaining = 0;
            }
        }
        
        $data = [
            'user' => $user,
            'totalStudents' => $totalStudents,
            'totalUsers' => $totalUsers,
            'totalClasses' => $totalClasses,
            'totalStaff' => $totalStaff,
            'recentStudents' => $recentStudents,
            'recentNotifications' => $recentNotifications,
            'unreadNotificationCount' => $unreadNotificationCount,
            'studentStats' => $studentStats,
            'studentsByClass' => $studentsByClass,
            'monthlyRevenue' => $monthlyRevenue,
            'currency' => $currency,
            'daysRemaining' => $daysRemaining,
            'currentAcademicYear' => $currentAcademicYear,
            'currentDateTime' => $settingModel->getCurrentTime()
        ];
        
        $this->view('dashboard/index', $data);
    }
}

