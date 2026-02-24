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
        $tab = $this->get('tab', 'audit_logs');
        $moduleId = $this->get('module_id');
        $academicYearId = $this->get('academic_year_id');
        $term = $this->get('term');
        $userId = $this->get('user_id');
        $dateFrom = $this->get('date_from');
        $dateTo = $this->get('date_to');
        $search = $this->get('search');
        $classId = $this->get('class_id');
        
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
        if ($search) {
            $filters['search'] = $search;
        }
        if ($classId) {
            $filters['class_id'] = $classId;
        }
        
        // Get paginated data based on the active tab
        $paginatedData = $this->getTabData($tab, $filters, $page, $perPage);
        
        // Get filter dropdown options
        $modules = $this->auditLogModel->getDistinctModules();
        $academicYears = $this->academicYearModel->getAll();
        $users = $this->userModel->all();
        $terms = $this->auditLogModel->getDistinctTerms();
        $classes = $this->classModel->getAll();

        $this->view('archives/index', [
            'tab' => $tab,
            'records' => $paginatedData['data'] ?? [],
            'pagination' => $paginatedData,
            'modules' => $modules,
            'academicYears' => $academicYears,
            'users' => $users,
            'terms' => $terms,
            'moduleId' => $moduleId,
            'academicYearId' => $academicYearId,
            'term' => $term,
            'userId' => $userId,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'search' => $search,
            'classId' => $classId,
            'classes' => $classes,
            'perPage' => $perPage
        ]);
    }
    
    private function getTabData($tab, $filters, $page, $perPage)
    {
        if ($tab === 'audit_logs') {
            return $this->auditLogModel->getAllWithFilters($filters, $page, $perPage);
        }

        $offset = ($page - 1) * $perPage;
        $params = [];
        $where = ["1=1"];

        $sql = "";
        $countSql = "";
        $order = "";

        switch ($tab) {
            case 'promotions':
                $sql = "SELECT sp.*, s.first_name, s.last_name, s.admission_no,
                               fc.name as from_class_name, tc.name as to_class_name,
                               ay.name as academic_year_name, u.username as actor_name
                        FROM student_promotions sp
                        LEFT JOIN students s ON sp.student_id = s.id
                        LEFT JOIN classes fc ON sp.from_class_id = fc.id
                        LEFT JOIN classes tc ON sp.to_class_id = tc.id
                        LEFT JOIN academic_years ay ON sp.academic_year_id = ay.id
                        LEFT JOIN users u ON sp.promoted_by = u.id";
                $countSql = "SELECT COUNT(*) as total FROM student_promotions sp
                             LEFT JOIN students s ON sp.student_id = s.id
                             LEFT JOIN classes fc ON sp.from_class_id = fc.id
                             LEFT JOIN classes tc ON sp.to_class_id = tc.id
                             LEFT JOIN academic_years ay ON sp.academic_year_id = ay.id
                             LEFT JOIN users u ON sp.promoted_by = u.id";
                $order = "ORDER BY sp.promotion_date DESC, sp.created_at DESC";
                break;
                
            case 'financial':
                $sql = "SELECT p.*, s.first_name, s.last_name, s.admission_no, f.name as fee_name, ay.name as academic_year_name
                        FROM payments p
                        LEFT JOIN students s ON p.student_id = s.id
                        LEFT JOIN fees f ON p.fee_id = f.id
                        LEFT JOIN academic_years ay ON p.academic_year_id = ay.id";
                $countSql = "SELECT COUNT(*) as total FROM payments p
                             LEFT JOIN students s ON p.student_id = s.id
                             LEFT JOIN fees f ON p.fee_id = f.id
                             LEFT JOIN academic_years ay ON p.academic_year_id = ay.id";
                $order = "ORDER BY p.date DESC, p.created_at DESC";
                break;
                
            case 'academic':
                $sql = "SELECT er.*, e.name as exam_name, e.date as exam_date, e.term as exam_term,
                               s.first_name, s.last_name, s.admission_no, sub.name as subject_name,
                               c.name as class_name, ay.name as academic_year_name
                        FROM exam_results er
                        LEFT JOIN exams e ON er.exam_id = e.id
                        LEFT JOIN students s ON er.student_id = s.id
                        LEFT JOIN subjects sub ON er.subject_id = sub.id
                        LEFT JOIN classes c ON e.class_id = c.id
                        LEFT JOIN academic_years ay ON e.academic_year_id = ay.id";
                $countSql = "SELECT COUNT(*) as total FROM exam_results er
                             LEFT JOIN exams e ON er.exam_id = e.id
                             LEFT JOIN students s ON er.student_id = s.id
                             LEFT JOIN subjects sub ON er.subject_id = sub.id
                             LEFT JOIN classes c ON e.class_id = c.id
                             LEFT JOIN academic_years ay ON e.academic_year_id = ay.id";
                $order = "ORDER BY e.date DESC, s.last_name, s.first_name";
                break;
                
            case 'students':
                $sql = "SELECT s.*, c.name as class_name, ay.name as academic_year_name
                        FROM students s
                        LEFT JOIN classes c ON s.class_id = c.id
                        LEFT JOIN academic_years ay ON s.academic_year_id = ay.id";
                $countSql = "SELECT COUNT(*) as total FROM students s
                             LEFT JOIN classes c ON s.class_id = c.id
                             LEFT JOIN academic_years ay ON s.academic_year_id = ay.id";
                $order = "ORDER BY s.admission_date DESC, s.created_at DESC";
                break;
                
            case 'staff':
                $sql = "SELECT * FROM staff s";
                $countSql = "SELECT COUNT(*) as total FROM staff s";
                $order = "ORDER BY s.hire_date DESC, s.created_at DESC";
                break;
                
            default:
                return ['data' => [], 'total' => 0, 'current_page' => $page, 'per_page' => $perPage, 'total_pages' => 0];
        }

        // Apply dynamic filters
        if (!empty($filters['academic_year_id'])) {
            if ($tab === 'promotions') $where[] = "sp.academic_year_id = :ay_id";
            elseif ($tab === 'financial') $where[] = "p.academic_year_id = :ay_id";
            elseif ($tab === 'academic') $where[] = "e.academic_year_id = :ay_id";
            elseif ($tab === 'students') $where[] = "s.academic_year_id = :ay_id";
            
            if (in_array($tab, ['promotions', 'financial', 'academic', 'students'])) {
                $params['ay_id'] = $filters['academic_year_id'];
            }
        }
        
        if (!empty($filters['term'])) {
            if ($tab === 'financial') $where[] = "p.term = :term";
            elseif ($tab === 'academic') $where[] = "e.term = :term";
            
            if (in_array($tab, ['financial', 'academic'])) {
                $params['term'] = $filters['term'];
            }
        }
        
        if (!empty($filters['date_from'])) {
            if ($tab === 'promotions') $where[] = "sp.promotion_date >= :date_from";
            elseif ($tab === 'financial') $where[] = "p.date >= :date_from";
            elseif ($tab === 'academic') $where[] = "e.date >= :date_from";
            elseif ($tab === 'students') $where[] = "s.admission_date >= :date_from";
            elseif ($tab === 'staff') $where[] = "s.hire_date >= :date_from";
            
            if (in_array($tab, ['promotions', 'financial', 'academic', 'students', 'staff'])) {
                $params['date_from'] = $filters['date_from'];
            }
        }
        
        if (!empty($filters['date_to'])) {
            if ($tab === 'promotions') $where[] = "sp.promotion_date <= :date_to";
            elseif ($tab === 'financial') $where[] = "p.date <= :date_to";
            elseif ($tab === 'academic') $where[] = "e.date <= :date_to";
            elseif ($tab === 'students') $where[] = "s.admission_date <= :date_to";
            elseif ($tab === 'staff') $where[] = "s.hire_date <= :date_to";
            
            if (in_array($tab, ['promotions', 'financial', 'academic', 'students', 'staff'])) {
                $params['date_to'] = $filters['date_to'];
            }
        }
        
        if (!empty($filters['user_id'])) {
            if ($tab === 'promotions') {
                $where[] = "sp.promoted_by = :user_id";
                $params['user_id'] = $filters['user_id'];
            }
        }
        
        if (!empty($filters['class_id'])) {
            if ($tab === 'financial' || $tab === 'students') {
                $where[] = "s.class_id = :class_id";
                $params['class_id'] = $filters['class_id'];
            } elseif ($tab === 'academic') {
                $where[] = "(e.class_id = :class_id1 OR s.class_id = :class_id2)";
                $params['class_id1'] = $filters['class_id'];
                $params['class_id2'] = $filters['class_id'];
            }
        }
        
        if (!empty($filters['search'])) {
            $search = '%' . $filters['search'] . '%';
            if ($tab === 'financial') {
                $where[] = "(s.first_name LIKE :search1 OR s.last_name LIKE :search2 OR s.admission_no LIKE :search3)";
                $params['search1'] = $search;
                $params['search2'] = $search;
                $params['search3'] = $search;
            } elseif ($tab === 'academic') {
                $where[] = "(s.first_name LIKE :search1 OR s.last_name LIKE :search2 OR s.admission_no LIKE :search3 OR e.name LIKE :search4)";
                $params['search1'] = $search;
                $params['search2'] = $search;
                $params['search3'] = $search;
                $params['search4'] = $search;
            }
        }

        $whereString = implode(" AND ", $where);
        $fullSql = $sql . " WHERE " . $whereString . " " . $order . " LIMIT :limit OFFSET :offset";
        $fullCountSql = $countSql . " WHERE " . $whereString;
        
        // Query for aggregate totals if on the financial tab
        $totalAmount = 0;
        if ($tab === 'financial') {
            $sumSql = "SELECT SUM(p.amount) as total_amount FROM payments p 
                       LEFT JOIN students s ON p.student_id = s.id 
                       LEFT JOIN fees f ON p.fee_id = f.id 
                       LEFT JOIN academic_years ay ON p.academic_year_id = ay.id
                       WHERE " . $whereString;
            $db = $this->auditLogModel; // Use any model to access db raw query
            $sumResult = $db->queryRawOne($sumSql, $params);
            $totalAmount = $sumResult['total_amount'] ?? 0;
        }

        // Clone params to securely bind limits natively through executeRaw if supported, or inline if native PDO string binding complains.
        // We'll just append limit/offset inline since executeRaw uses standard binding which treats them as strings sometimes.
        $fullSql = $sql . " WHERE " . $whereString . " " . $order . " LIMIT " . (int)$perPage . " OFFSET " . (int)$offset;

        $db = $this->auditLogModel; // Use any model to access db raw query
        $data = $db->queryRaw($fullSql, $params);
        $totalResult = $db->queryRawOne($fullCountSql, $params);
        $total = $totalResult['total'] ?? 0;

        return [
            'data' => $data,
            'total' => $total,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => ceil($total / $perPage),
            'total_amount' => $totalAmount
        ];
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
        
        if (isset($_GET['ajax']) && $_GET['ajax'] == 1) {
            header('Content-Type: application/json');
            echo json_encode(['success' => true, 'data' => $auditLog]);
            exit;
        }
        
        $this->view('archives/show', [
            'auditLog' => $auditLog
        ]);
    }
}