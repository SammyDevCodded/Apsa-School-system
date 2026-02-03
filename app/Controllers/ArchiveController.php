<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\AuditLog;
use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Models\User as UserModel;

class ArchiveController extends Controller
{
    private $auditLogModel;
    private $academicYearModel;
    private $classModel;
    private $userModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLog();
        $this->academicYearModel = new AcademicYear();
        $this->classModel = new ClassModel();
        $this->userModel = new UserModel();
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

        // Get filter parameters
        $moduleId = $this->get('module_id');
        $academicYearId = $this->get('academic_year_id');
        $term = $this->get('term');
        $userId = $this->get('user_id');
        $dateFrom = $this->get('date_from');
        $dateTo = $this->get('date_to');
        
        // Get pagination parameters
        $page = (int) $this->get('page', 1);
        $perPage = (int) $this->get('per_page', 10);
        
        // Validate perPage value
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }
        
        // Prepare filters
        $filters = [];
        if ($moduleId) {
            $filters['table_name'] = $moduleId;
        }
        if ($academicYearId) {
            $filters['academic_year_id'] = $academicYearId;
        }
        if ($term) {
            $filters['term'] = $term;
        }
        if ($userId) {
            $filters['user_id'] = $userId;
        }
        if ($dateFrom) {
            $filters['date_from'] = $dateFrom;
        }
        if ($dateTo) {
            $filters['date_to'] = $dateTo;
        }
        
        // Get audit logs with filtering and pagination
        $auditLogs = $this->auditLogModel->getAllWithFilters($filters, $page, $perPage);
        
        // Get modules for filtering
        $modules = $this->auditLogModel->getDistinctModules();
        
        // Get academic years for filtering
        $academicYears = $this->academicYearModel->getAll();
        
        // Get users for filtering
        $users = $this->userModel->all();
        
        // Get distinct terms for filtering
        $terms = $this->auditLogModel->getDistinctTerms();

        $this->view('archives/index', [
            'auditLogs' => $auditLogs['data'] ?? $auditLogs,
            'modules' => $modules,
            'academicYears' => $academicYears,
            'users' => $users,
            'terms' => $terms,
            'pagination' => $auditLogs,
            'moduleId' => $moduleId,
            'academicYearId' => $academicYearId,
            'term' => $term,
            'userId' => $userId,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'perPage' => $perPage
        ]);
    }
    
    public function show($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasRole('super_admin')) {
            $this->redirect('/dashboard');
        }

        $auditLog = $this->auditLogModel->getById($id);
        
        if (!$auditLog) {
            $this->flash('error', 'Archive record not found');
            $this->redirect('/archives');
        }
        
        // Decode JSON values for display
        if (!empty($auditLog['old_values'])) {
            $auditLog['old_values'] = json_decode($auditLog['old_values'], true);
        }
        
        if (!empty($auditLog['new_values'])) {
            $auditLog['new_values'] = json_decode($auditLog['new_values'], true);
        }
        
        $this->view('archives/show', [
            'auditLog' => $auditLog
        ]);
    }
}