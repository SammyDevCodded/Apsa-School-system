<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Promotion;
use App\Models\Student;
use App\Models\ClassModel;
use App\Models\AcademicYear;
use App\Helpers\AuditHelper;

class PromotionController extends Controller
{
    private $promotionModel;
    private $studentModel;
    private $classModel;
    private $academicYearModel;

    public function __construct()
    {
        // Removed parent::__construct() call since parent Controller class doesn't have a constructor
        $this->promotionModel = new Promotion();
        $this->studentModel = new Student();
        $this->classModel = new ClassModel();
        $this->academicYearModel = new AcademicYear();
    }

    /**
     * Show the promotion interface
     */
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
            return;
        }

        // Check authorization
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->flash('error', 'Access denied. Administrative privileges required.');
            $this->redirect('/dashboard');
            return;
        }

        $classes = $this->classModel->getAll();
        $academicYears = $this->academicYearModel->getAll();

        $this->view('promotions/index', [
            'classes' => $classes,
            'academicYears' => $academicYears
        ]);
    }

    /**
     * Get students by class for promotion selection
     */
    public function getStudentsByClass()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
            return;
        }

        // Check authorization
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->jsonResponse(['error' => 'Access denied'], 403);
            return;
        }

        $classId = $this->get('class_id');
        
        if (!$classId) {
            $this->jsonResponse(['error' => 'Class ID required'], 400);
            return;
        }

        $students = $this->studentModel->getByClassId($classId);
        
        $this->jsonResponse([
            'students' => $students,
            'count' => count($students)
        ]);
    }

    /**
     * Process bulk student promotion
     */
    public function promote()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
            return;
        }

        // Check authorization
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->jsonResponse(['error' => 'Access denied'], 403);
            return;
        }

        $postData = $_POST;
        
        // Validate required fields
        $requiredFields = ['from_class_id', 'to_class_id', 'academic_year_id', 'student_ids', 'promotion_date'];
        foreach ($requiredFields as $field) {
            if (empty($postData[$field])) {
                $this->jsonResponse(['error' => "Missing required field: {$field}"], 400);
                return;
            }
        }

        $fromClassId = (int)$postData['from_class_id'];
        $toClassId = (int)$postData['to_class_id'];
        $academicYearId = (int)$postData['academic_year_id'];
        $studentIds = $postData['student_ids'];
        $promotionDate = $postData['promotion_date'];
        $remarks = $postData['remarks'] ?? '';

        // Validate student IDs
        if (!is_array($studentIds) || empty($studentIds)) {
            $this->jsonResponse(['error' => 'No students selected for promotion'], 400);
            return;
        }

        // Validate date format
        if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $promotionDate)) {
            $this->jsonResponse(['error' => 'Invalid date format'], 400);
            return;
        }

        // Validate that from and to classes are different
        if ($fromClassId === $toClassId) {
            $this->jsonResponse(['error' => 'Source and target classes must be different'], 400);
            return;
        }

        // Validate academic year exists
        $academicYear = $this->academicYearModel->find($academicYearId);
        if (!$academicYear) {
            $this->jsonResponse(['error' => 'Invalid academic year'], 400);
            return;
        }

        // Validate classes exist
        $fromClass = $this->classModel->find($fromClassId);
        $toClass = $this->classModel->find($toClassId);
        
        if (!$fromClass || !$toClass) {
            $this->jsonResponse(['error' => 'Invalid class specified'], 400);
            return;
        }

        // Validate students belong to source class and can be promoted
        $validStudentIds = [];
        foreach ($studentIds as $studentId) {
            $studentId = (int)$studentId;
            $student = $this->studentModel->find($studentId);
            
            if (!$student) {
                continue; // Skip invalid students
            }
            
            // Check if student is in the source class
            if ($student['class_id'] != $fromClassId) {
                continue; // Skip students not in source class
            }
            
            // Check if student hasn't been promoted this academic year already
            if (!$this->promotionModel->canStudentBePromoted($studentId, $academicYearId)) {
                continue; // Skip already promoted students
            }
            
            $validStudentIds[] = $studentId;
        }

        if (empty($validStudentIds)) {
            $this->jsonResponse(['error' => 'No valid students to promote'], 400);
            return;
        }

        // Perform promotion
        $promotedBy = $_SESSION['user']['id'];
        $result = $this->promotionModel->promoteStudents(
            $validStudentIds,
            $fromClassId,
            $toClassId,
            $academicYearId,
            $promotedBy,
            $promotionDate,
            $remarks
        );

        if ($result) {
            // Log audit trail
            AuditHelper::log(
                $promotedBy,
                'create',
                'student_promotions',
                null,
                null,
                [
                    'student_count' => count($validStudentIds),
                    'from_class_id' => $fromClassId,
                    'to_class_id' => $toClassId,
                    'academic_year_id' => $academicYearId,
                    'promotion_date' => $promotionDate,
                    'student_ids' => $validStudentIds,
                    'remarks' => $remarks
                ],
                $academicYearId,
                $academicYear['term'] ?? null
            );

            $this->jsonResponse([
                'success' => true,
                'message' => sprintf('%d students successfully promoted from %s to %s', 
                    count($validStudentIds), 
                    $fromClass['name'], 
                    $toClass['name']
                ),
                'promoted_count' => count($validStudentIds)
            ]);
        } else {
            $this->jsonResponse(['error' => 'Failed to promote students'], 500);
        }
    }

    /**
     * Show promotion history
     */
    public function history()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
            return;
        }

        // Check authorization
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->flash('error', 'Access denied. Administrative privileges required.');
            $this->redirect('/dashboard');
            return;
        }

        $academicYearId = $this->get('academic_year_id');
        $startDate = $this->get('start_date');
        $endDate = $this->get('end_date');

        $promotions = [];
        $statistics = [];

        if ($academicYearId) {
            $promotions = $this->promotionModel->getPromotionsByAcademicYear($academicYearId);
            $statistics = $this->promotionModel->getPromotionStatistics($academicYearId);
        } elseif ($startDate && $endDate) {
            $promotions = $this->promotionModel->getPromotionsByDateRange($startDate, $endDate);
            $statistics = $this->promotionModel->getPromotionStatistics();
        } else {
            // Get recent promotions
            $promotions = $this->promotionModel->getRecentPromotions(50);
            $statistics = $this->promotionModel->getPromotionStatistics();
        }

        $academicYears = $this->academicYearModel->getAll();
        $classes = $this->classModel->getAll();

        $this->view('promotions/history', [
            'promotions' => $promotions,
            'statistics' => $statistics,
            'academicYears' => $academicYears,
            'classes' => $classes,
            'filters' => [
                'academic_year_id' => $academicYearId,
                'start_date' => $startDate,
                'end_date' => $endDate
            ]
        ]);
    }

    /**
     * Get promotion statistics
     */
    public function statistics()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
            return;
        }

        // Check authorization
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->jsonResponse(['error' => 'Access denied'], 403);
            return;
        }

        $academicYearId = $this->get('academic_year_id');
        $statistics = $this->promotionModel->getPromotionStatistics($academicYearId);

        $this->jsonResponse($statistics);
    }
}