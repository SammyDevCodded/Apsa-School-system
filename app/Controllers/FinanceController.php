<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Payment;
use App\Models\Fee;
use App\Models\FeeAssignment;
use App\Models\Student;
use App\Models\ClassModel;

class FinanceController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Get filter parameters
        $searchTerm = $this->get('search');
        $academicYearId = $this->get('academic_year_id');
        $enableDateFilter = $this->get('enable_date_filter');
        $dateFrom = null;
        $dateTo = null;
        $reportType = $this->get('report_type', 'daily'); // daily, weekly, monthly, termly, yearly
        $tab = $this->get('tab', 'payments'); // payments, bills
        $classId = $this->get('class_id'); // For bills tab filtering
        
        // Get pagination parameters
        $page = (int) $this->get('page', 1);
        $perPage = (int) $this->get('per_page', 10);
        
        // Validate perPage value
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }
        
        // Only get date parameters if date filter is enabled
        if ($enableDateFilter) {
            $dateFrom = $this->get('date_from');
            $dateTo = $this->get('date_to');
        }
        
        $term = $this->get('term');
        
        // Prepare filters
        $filters = [];
        if ($academicYearId) {
            $filters['academic_year_id'] = $academicYearId;
        }
        if ($dateFrom) {
            $filters['date_from'] = $dateFrom;
        }
        if ($dateTo) {
            $filters['date_to'] = $dateTo;
        }
        if ($classId) {
            $filters['class_id'] = $classId;
        }
        if ($term) {
            $filters['term'] = $term;
        }
        
        // Get academic years for filtering
        $academicYearModel = new \App\Models\AcademicYear();
        $academicYears = $academicYearModel->getAll();
        
        // Get classes for filtering
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        if ($tab === 'bills') {
            // Get bills data
            $feeModel = new Fee();
            $fees = $feeModel->getAllWithOriginalClassesAndStudentCount();
            
            // Get fee assignments with payment information
            $feeAssignmentModel = new FeeAssignment();
            $studentModel = new Student();
            $paymentModel = new Payment();
            
            // Process fees to calculate balances
            $processedFees = [];
            
            foreach ($fees as $fee) {
                // Filter by Term
                if (!empty($term) && isset($fee['term']) && $fee['term'] !== $term) {
                    continue;
                }
                
                // Get students assigned to this fee
                $assignedStudents = $feeAssignmentModel->getByFeeId($fee['id']);
                
                // Filter students based on search term and class filter
                $filteredStudents = [];
                foreach ($assignedStudents as $student) {
                    // Check if student matches search criteria
                    $matchesSearch = true;
                    if (!empty($searchTerm)) {
                        $fullName = strtolower($student['first_name'] . ' ' . $student['last_name']);
                        $searchTermLower = strtolower($searchTerm);
                        $matchesSearch = (strpos($fullName, $searchTermLower) !== false) || 
                                         (strpos(strtolower($student['admission_no']), $searchTermLower) !== false);
                    }
                    
                    // Check if student matches class filter
                    $matchesClass = true;
                    if (!empty($classId)) {
                        $matchesClass = isset($student['class_id']) && $student['class_id'] == $classId;
                    }
                    
                    if ($matchesSearch && $matchesClass) {
                        $filteredStudents[] = $student;
                    }
                }
                
                // Calculate total amount paid for this fee by filtered students
                $totalPaid = 0;
                $studentPayments = [];
                
                foreach ($filteredStudents as $student) {
                    // Get payments for this student and fee
                    $studentPaymentsData = $paymentModel->getByStudentIdAndFeeId($student['student_id'], $fee['id']);
                    $studentTotalPaid = array_sum(array_column($studentPaymentsData, 'amount'));
                    
                    $studentPayments[] = [
                        'student' => $student,
                        'payments' => $studentPaymentsData,
                        'total_paid' => $studentTotalPaid,
                        'balance' => $fee['amount'] - $studentTotalPaid
                    ];
                    
                    $totalPaid += $studentTotalPaid;
                }
                
                // Calculate overall balance for the fee
                // Use count of filtered students instead of all students when filtering is applied
                $studentCount = !empty($searchTerm) || !empty($classId) ? count($filteredStudents) : $fee['student_count'];
                $totalExpected = $fee['amount'] * $studentCount;
                $overallBalance = $totalExpected - $totalPaid;
                
                // Only include fees with students matching the filters
                if (empty($searchTerm) && empty($classId) || $studentCount > 0) {
                    $processedFees[] = [
                        'fee' => $fee,
                        'assigned_students' => $assignedStudents,
                        'student_payments' => $studentPayments,
                        'total_expected' => $totalExpected,
                        'total_paid' => $totalPaid,
                        'overall_balance' => $overallBalance
                    ];
                }
            }
            
            // Get student bills (assignments) for the separate list
            $filters = [];
            if ($classId) {
                $filters['class_id'] = $classId;
            }
            if ($academicYearId) {
                $filters['academic_year_id'] = $academicYearId;
            }
            if ($term) {
                $filters['term'] = $term;
            }
            
            $studentBills = $feeAssignmentModel->getAllStudentBills($page, $perPage, $filters, $searchTerm);
            
            // Get School Settings
            $settingModel = new \App\Models\Setting();
            $settings = $settingModel->getSettings();
            
            $this->view('finance/index', [
                'tab' => $tab,
                'fees' => $processedFees,
                'studentBills' => $studentBills['data'] ?? [],
                'pagination' => $studentBills, // Use this for pagination links
                'academicYears' => $academicYears,
                'classes' => $classes,
                'searchTerm' => $searchTerm,
                'academicYearId' => $academicYearId,
                'classId' => $classId,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'enableDateFilter' => $enableDateFilter,
                'perPage' => $perPage,
                'settings' => $settings
            ]);
        } else {
            // Get payments with filtering and pagination (existing functionality)
            $paymentModel = new Payment();
            $payments = $paymentModel->getAllWithDetails($filters, $searchTerm, $page, $perPage);
            
            // Get report data based on report type
            $reportData = $this->getReportData($paymentModel, $reportType, $filters, $searchTerm);
            
            // Get School Settings
            $settingModel = new \App\Models\Setting();
            $settings = $settingModel->getSettings();
            
            $this->view('finance/index', [
                'tab' => $tab,
                'payments' => $payments['data'] ?? $payments,
                'academicYears' => $academicYears,
                'classes' => $classes,
                'pagination' => $payments,
                'searchTerm' => $searchTerm,
                'academicYearId' => $academicYearId,
                'classId' => $classId,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'enableDateFilter' => $enableDateFilter,
                'term' => $term,
                'perPage' => $perPage,
                'reportType' => $reportType,
                'reportData' => $reportData,
                'settings' => $settings
            ]);
        }
    }
    
    public function reportDetails()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Unauthorized']);
            return;
        }
        
        // Check if request is AJAX
        if (!$this->isAjaxRequest()) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid request']);
            return;
        }
        
        // Get parameters
        $reportType = $this->post('report_type');
        $searchTerm = $this->post('search_term');
        $academicYearId = $this->post('academic_year_id');
        
        // Prepare filters
        $filters = [];
        if ($academicYearId) {
            $filters['academic_year_id'] = $academicYearId;
        }
        
        // Get payments based on report type
        $paymentModel = new Payment();
        $payments = [];
        
        switch ($reportType) {
            case 'daily':
                $reportDate = $this->post('report_date');
                if ($reportDate) {
                    $filters['date_from'] = $reportDate;
                    $filters['date_to'] = $reportDate;
                }
                $payments = $paymentModel->getAllWithDetails($filters, $searchTerm)['data'] ?? [];
                break;
                
            case 'weekly':
                $startDate = $this->post('start_date');
                $endDate = $this->post('end_date');
                if ($startDate && $endDate) {
                    $filters['date_from'] = $startDate;
                    $filters['date_to'] = $endDate;
                }
                $payments = $paymentModel->getAllWithDetails($filters, $searchTerm)['data'] ?? [];
                break;
                
            case 'monthly':
                $month = $this->post('month');
                if ($month) {
                    // Convert month format (YYYY-MM) to date range
                    $startDate = $month . '-01';
                    $endDate = date('Y-m-t', strtotime($startDate));
                    $filters['date_from'] = $startDate;
                    $filters['date_to'] = $endDate;
                }
                $payments = $paymentModel->getAllWithDetails($filters, $searchTerm)['data'] ?? [];
                break;
                
            case 'termly':
                $term = $this->post('term');
                if ($term) {
                    $filters['term'] = $term;
                }
                $payments = $paymentModel->getAllWithDetails($filters, $searchTerm)['data'] ?? [];
                break;
                
            case 'yearly':
                $year = $this->post('year');
                if ($year) {
                    $startDate = $year . '-01-01';
                    $endDate = $year . '-12-31';
                    $filters['date_from'] = $startDate;
                    $filters['date_to'] = $endDate;
                }
                $payments = $paymentModel->getAllWithDetails($filters, $searchTerm)['data'] ?? [];
                break;
                
            default:
                $payments = [];
        }
        
        $this->jsonResponse(['success' => true, 'payments' => $payments]);
    }
    
    /**
     * Get fee details for a specific fee structure
     */
    public function getFeeDetails($feeId)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Unauthorized']);
            return;
        }
        
        // Check if request is AJAX
        if (!$this->isAjaxRequest()) {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid request']);
            return;
        }
        
        try {
            // Get fee assignments with payment information
            $feeAssignmentModel = new FeeAssignment();
            $paymentModel = new Payment();
            $feeModel = new Fee();
            
            // Get fee details including academic year and term
            $fee = $feeModel->find($feeId);
            $academicYear = 'N/A';
            $term = 'N/A';
            
            if ($fee) {
                if (!empty($fee['academic_year_id'])) {
                    $academicYearModel = new \App\Models\AcademicYear();
                    $ay = $academicYearModel->find($fee['academic_year_id']);
                    if ($ay) {
                        $academicYear = $ay['name'];
                    }
                }
                if (!empty($fee['term'])) {
                    $term = $fee['term'];
                }
            }
            
            // Get students assigned to this fee
            $assignedStudents = $feeAssignmentModel->getByFeeId($feeId);
            
            // Calculate payments for each student
            $studentDetails = [];
            $classes = [];
            foreach ($assignedStudents as $student) {
                // Get payments for this student and fee
                $studentPaymentsData = $paymentModel->getByStudentIdAndFeeId($student['student_id'], $feeId);
                $studentTotalPaid = array_sum(array_column($studentPaymentsData, 'amount'));
                
                if (!empty($student['class_name'])) {
                    $classes[] = $student['class_name'];
                }

                $studentDetails[] = [
                    'student_id' => $student['student_id'],
                    'first_name' => $student['first_name'],
                    'last_name' => $student['last_name'],
                    'admission_no' => $student['admission_no'],
                    'class_name' => $student['class_name'] ?? 'N/A',
                    'fee_type' => $student['fee_type'] ?? 'N/A',
                    'fee_name' => $student['fee_name'] ?? 'N/A',
                    'assigned_date' => $student['assigned_date'] ?? null,
                    'fee_amount' => $student['fee_amount'],
                    'total_paid' => $studentTotalPaid,
                    'balance' => $student['fee_amount'] - $studentTotalPaid
                ];
            }
            
            $assignedClasses = !empty($classes) ? implode(', ', array_unique($classes)) : 'N/A';
            
            $this->jsonResponse([
                'success' => true, 
                'students' => $studentDetails,
                'assigned_classes' => $assignedClasses,
                'academic_year' => $academicYear,
                'term' => $term
            ]);
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to load fee details']);
        }
    }
    
    private function getReportData($paymentModel, $reportType, $filters, $searchTerm)
    {
        switch ($reportType) {
            case 'daily':
                return $paymentModel->getDailyReport($filters, $searchTerm);
            case 'weekly':
                return $paymentModel->getWeeklyReport($filters, $searchTerm);
            case 'monthly':
                return $paymentModel->getMonthlyReport($filters, $searchTerm);
            case 'termly':
                return $paymentModel->getTermlyReport($filters, $searchTerm);
            case 'yearly':
                return $paymentModel->getYearlyReport($filters, $searchTerm);
            default:
                return $paymentModel->getDailyReport($filters, $searchTerm);
        }
    }
}