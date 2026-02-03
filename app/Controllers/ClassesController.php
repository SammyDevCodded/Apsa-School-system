<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\Subject;
use App\Models\AcademicYear;
use App\Helpers\AuditHelper;

class ClassesController extends Controller
{
    private $classModel;
    private $studentModel;
    private $subjectModel;

    public function __construct()
    {
        $this->classModel = new ClassModel();
        $this->studentModel = new Student();
        $this->subjectModel = new Subject();
    }

    public function index()
    {
        // Get search and pagination parameters
        $search = $this->get('search', '');
        $page = (int) $this->get('page', 1);
        $perPage = (int) $this->get('per_page', 10);
        
        // Validate perPage value
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }
        
        // Get classes with search and pagination
        $result = $this->classModel->getAllWithStudentCountPaginated($search, $page, $perPage);
        
        $this->view('classes/index', [
            'classes' => $result['data'],
            'pagination' => $result['pagination'],
            'search' => $search,
            'perPage' => $perPage
        ]);
    }

    public function create()
    {
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        $this->view('classes/create', ['currentAcademicYear' => $currentAcademicYear]);
    }

    public function store()
    {
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        $name = $_POST['name'] ?? '';
        $level = $_POST['level'] ?? '';
        $customLevel = $_POST['custom_level'] ?? '';
        $capacity = $_POST['capacity'] ?? 30;

        // Use custom level if provided, otherwise use selected level
        if (!empty($customLevel)) {
            $level = $customLevel;
        }

        // Basic validation
        if (empty($name) || empty($level)) {
            $_SESSION['flash_error'] = 'Name and level are required.';
            $this->redirect('/classes/create');
            return;
        }

        // Validate capacity
        if (!is_numeric($capacity) || $capacity <= 0) {
            $_SESSION['flash_error'] = 'Capacity must be a positive number.';
            $this->redirect('/classes/create');
            return;
        }

        // Create class
        $data = [
            'name' => $name,
            'level' => $level,
            'capacity' => $capacity
        ];

        try {
            $classId = $this->classModel->create($data);
            
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'] ?? null,
                'create',
                'classes',
                $classId,
                null,
                $data,
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            $_SESSION['flash_success'] = 'Class created successfully.';
            $this->redirect('/classes');
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Failed to create class: ' . $e->getMessage();
            $this->redirect('/classes/create');
        }
    }

    public function edit($id)
    {
        $class = $this->classModel->findWithStudentCount($id);
        
        if (!$class) {
            $_SESSION['flash_error'] = 'Class not found.';
            $this->redirect('/classes');
            return;
        }
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();

        $this->view('classes/edit', [
            'class' => $class,
            'currentAcademicYear' => $currentAcademicYear
        ]);
    }

    public function update($id)
    {
        $class = $this->classModel->find($id);
        
        if (!$class) {
            $_SESSION['flash_error'] = 'Class not found.';
            $this->redirect('/classes');
            return;
        }
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();

        $name = $_POST['name'] ?? '';
        $level = $_POST['level'] ?? '';
        $customLevel = $_POST['custom_level'] ?? '';
        $capacity = $_POST['capacity'] ?? 30;

        // Use custom level if provided, otherwise use selected level
        if (!empty($customLevel)) {
            $level = $customLevel;
        }

        // Basic validation
        if (empty($name) || empty($level)) {
            $_SESSION['flash_error'] = 'Name and level are required.';
            $this->redirect('/classes/' . $id . '/edit');
            return;
        }

        // Validate capacity
        if (!is_numeric($capacity) || $capacity <= 0) {
            $_SESSION['flash_error'] = 'Capacity must be a positive number.';
            $this->redirect('/classes/' . $id . '/edit');
            return;
        }

        // Update class
        $data = [
            'name' => $name,
            'level' => $level,
            'capacity' => $capacity
        ];

        try {
            $this->classModel->update($id, $data);
            
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'] ?? null,
                'update',
                'classes',
                $id,
                $class, // old values
                $data, // new values
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            $_SESSION['flash_success'] = 'Class updated successfully.';
            $this->redirect('/classes');
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Failed to update class: ' . $e->getMessage();
            $this->redirect('/classes/' . $id . '/edit');
        }
    }

    public function delete($id)
    {
        $class = $this->classModel->find($id);
        
        if (!$class) {
            $_SESSION['flash_error'] = 'Class not found.';
            $this->redirect('/classes');
            return;
        }

        // Check if there are students in this class
        $students = $this->studentModel->where('class_id', $id);
        if (!empty($students)) {
            $_SESSION['flash_error'] = 'Cannot delete class with students. Please reassign students first.';
            $this->redirect('/classes');
            return;
        }
        
        // Get current academic year for audit logging
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();

        try {
            $this->classModel->delete($id);
            
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'] ?? null,
                'delete',
                'classes',
                $id,
                $class, // old values
                null, // new values
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            $_SESSION['flash_success'] = 'Class deleted successfully.';
        } catch (\Exception $e) {
            $_SESSION['flash_error'] = 'Failed to delete class: ' . $e->getMessage();
        }

        $this->redirect('/classes');
    }

    public function show($id)
    {
        // Get class details with student count
        $class = $this->classModel->getByIdWithStudentCount($id);
        
        if (!$class) {
            $_SESSION['flash_error'] = 'Class not found.';
            $this->redirect('/classes');
            return;
        }

        // Get students in this class with pagination
        $studentPage = (int) $this->get('student_page', 1);
        $studentPerPage = (int) $this->get('student_per_page', 10);
        $allowedStudentPerPage = [10, 25, 50, 100];
        if (!in_array($studentPerPage, $allowedStudentPerPage)) {
            $studentPerPage = 10;
        }
        
        $studentsResult = $this->studentModel->getByClassIdPaginated($id, $studentPage, $studentPerPage);
        $students = $studentsResult['data'];
        $studentPagination = $studentsResult['pagination'];

        // Get exams for this class
        $examModel = new \App\Models\Exam();
        $exams = $examModel->getByClassId($id);

        // Get exam results for this class
        $examResultModel = new \App\Models\ExamResult();
        $examResults = [];
        $classPerformance = [];
        $termPerformance = [];
        
        if (!empty($exams)) {
            // Get all exam results for this class
            $examIds = array_column($exams, 'id');
            
            if (!empty($examIds)) {
                // We need to get results for each exam individually since we can't use IN clause with parameter binding easily
                foreach ($examIds as $examId) {
                    $results = $examResultModel->getByExamId($examId);
                    $examResults = array_merge($examResults, $results);
                }
            }
            
            // Calculate term-based performance
            $termPerformance = $this->calculateTermPerformance($exams, $examResults);
        }

        // Calculate utilization percentage
        $utilization = 0;
        if (!empty($class['capacity']) && $class['capacity'] > 0) {
            $utilization = round(($class['student_count'] / $class['capacity']) * 100, 1);
        }

        // Get assigned subjects for this class
        $subjectModel = new \App\Models\Subject();
        $assignedSubjects = $subjectModel->getAssignedSubjectsByClass($id);
        
        // Calculate financial statistics
        $financialStats = $this->calculateClassFinancialStats($id);
        
        // Calculate promotion statistics
        $promotionModel = new \App\Models\Promotion();
        $promotionStats = $promotionModel->getClassPromotionStats($id);

        $this->view('classes/show', [
            'class' => $class,
            'students' => $students,
            'studentPagination' => $studentPagination,
            'exams' => $exams,
            'examResults' => $examResults,
            'utilization' => $utilization,
            'assignedSubjects' => $assignedSubjects,
            'termPerformance' => $termPerformance,
            'financialStats' => $financialStats,
            'promotionStats' => $promotionStats
        ]);

    }
    
    /**
     * Calculate term-based performance for the class
     */
    /**
     * Calculate financial statistics for the class
     */
    private function calculateClassFinancialStats($classId)
    {
        $financialStats = [
            'total_bills' => 0,
            'total_paid' => 0,
            'total_balance' => 0,
            'fee_defaulters' => [],
            'payment_summary' => []
        ];
        
        // Get fee assignments for this class
        $feeAssignmentModel = new \App\Models\FeeAssignment();
        $studentModel = new Student();
        $paymentModel = new \App\Models\Payment();
        $feeModel = new \App\Models\Fee();
        
        // Get students in this class
        $students = $studentModel->getByClassId($classId);
        $studentIds = array_column($students, 'id');
        
        if (empty($studentIds)) {
            return $financialStats;
        }
        
        // Get all fee assignments for these students
        $assignments = [];
        foreach ($studentIds as $studentId) {
            $studentAssignments = $feeAssignmentModel->getByStudentId($studentId);
            $assignments = array_merge($assignments, $studentAssignments);
        }
        
        // Get student information for all students in this class
        $studentInfoMap = [];
        foreach ($students as $student) {
            $studentInfoMap[$student['id']] = [
                'first_name' => $student['first_name'] ?? 'Unknown',
                'last_name' => $student['last_name'] ?? 'Student',
                'admission_no' => $student['admission_no'] ?? 'N/A'
            ];
        }
        
        // Calculate totals
        $studentBalances = [];
        foreach ($assignments as $assignment) {
            $studentId = $assignment['student_id'];
            $feeAmount = $assignment['fee_amount'] ?? 0;
            
            if (!isset($studentBalances[$studentId])) {
                $studentInfo = $studentInfoMap[$studentId] ?? [
                    'first_name' => 'Unknown',
                    'last_name' => 'Student',
                    'admission_no' => 'N/A'
                ];
                
                $studentBalances[$studentId] = [
                    'student_info' => [
                        'id' => $studentId,
                        'first_name' => $studentInfo['first_name'],
                        'last_name' => $studentInfo['last_name'],
                        'admission_no' => $studentInfo['admission_no']
                    ],
                    'total_billed' => 0,
                    'total_paid' => 0,
                    'balance' => 0
                ];
            }
            
            $studentBalances[$studentId]['total_billed'] += $feeAmount;
            $financialStats['total_bills'] += $feeAmount;
        }
        
        // Get payments for these students
        $payments = [];
        foreach ($studentIds as $studentId) {
            $studentPayments = $paymentModel->getByStudentId($studentId);
            $payments = array_merge($payments, $studentPayments);
        }
        
        // Calculate payments
        foreach ($payments as $payment) {
            $studentId = $payment['student_id'];
            $amount = $payment['amount'] ?? 0;
            
            if (isset($studentBalances[$studentId])) {
                $studentBalances[$studentId]['total_paid'] += $amount;
                $financialStats['total_paid'] += $amount;
            }
        }
        
        // Calculate balances and identify defaulters
        foreach ($studentBalances as $studentId => $balanceInfo) {
            $balanceInfo['balance'] = $balanceInfo['total_billed'] - $balanceInfo['total_paid'];
            $studentBalances[$studentId] = $balanceInfo;
            
            if ($balanceInfo['balance'] > 0) {
                $financialStats['fee_defaulters'][] = $balanceInfo;
            }
        }
        
        $financialStats['total_balance'] = $financialStats['total_bills'] - $financialStats['total_paid'];
        $financialStats['student_balances'] = $studentBalances;
        
        // Sort defaulters by balance (highest first)
        usort($financialStats['fee_defaulters'], function($a, $b) {
            return $b['balance'] <=> $a['balance'];
        });
        
        return $financialStats;
    }
    
    private function calculateTermPerformance($exams, $examResults)
    {
        $termPerformance = [];
        
        // Group exams by term
        $examsByTerm = [];
        foreach ($exams as $exam) {
            $term = $exam['term'] ?? 'Unknown';
            if (!isset($examsByTerm[$term])) {
                $examsByTerm[$term] = [];
            }
            $examsByTerm[$term][] = $exam;
        }
        
        // Calculate performance for each term
        foreach ($examsByTerm as $term => $termExams) {
            $termResults = [];
            $termExamIds = array_column($termExams, 'id');
            
            // Filter results for this term's exams
            foreach ($examResults as $result) {
                if (in_array($result['exam_id'], $termExamIds)) {
                    $termResults[] = $result;
                }
            }
            
            if (!empty($termResults)) {
                // Calculate averages
                $totalMarks = array_sum(array_column($termResults, 'marks'));
                $totalResults = count($termResults);
                $average = $totalResults > 0 ? round($totalMarks / $totalResults, 2) : 0;
                
                // Group by subject
                $subjectPerformance = [];
                $resultsBySubject = [];
                foreach ($termResults as $result) {
                    $subject = $result['subject_name'] ?? 'Unknown';
                    if (!isset($resultsBySubject[$subject])) {
                        $resultsBySubject[$subject] = [];
                    }
                    $resultsBySubject[$subject][] = $result;
                }
                
                foreach ($resultsBySubject as $subject => $subjectResults) {
                    $subjectTotal = array_sum(array_column($subjectResults, 'marks'));
                    $subjectCount = count($subjectResults);
                    $subjectAverage = $subjectCount > 0 ? round($subjectTotal / $subjectCount, 2) : 0;
                    
                    $subjectPerformance[$subject] = [
                        'average' => $subjectAverage,
                        'count' => $subjectCount
                    ];
                }
                
                $termPerformance[$term] = [
                    'average' => $average,
                    'total_results' => $totalResults,
                    'subject_performance' => $subjectPerformance,
                    'exams_count' => count($termExams)
                ];
            }
        }
        
        return $termPerformance;
    }
}