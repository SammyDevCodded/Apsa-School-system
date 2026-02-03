<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Fee;
use App\Models\Receipt;
use App\Models\FeeAssignment;
use App\Models\AcademicYear;
use App\Helpers\AuditHelper;

class PaymentController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Handle receipt generation for AJAX requests
        if (($this->requestMethod() === 'POST' || $this->requestMethod() === 'PUT') && $this->get('generate_receipt') === '1') {
            $this->generateReceipt();
            return;
        }
        
        // Get filter parameters
        $searchTerm = $this->get('search');
        $academicYearId = $this->get('academic_year_id');
        $classId = $this->get('class_id');
        $enableDateFilter = $this->get('enable_date_filter');
        $dateFrom = null;
        $dateTo = null;
        
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
        
        $paymentModel = new Payment();
        $payments = $paymentModel->getAllWithDetails($filters, $searchTerm, $page, $perPage);
        
        // Get academic years for filtering
        $academicYearModel = new \App\Models\AcademicYear();
        $academicYears = $academicYearModel->getAll();
        
        // Get all classes for filtering
        $classModel = new \App\Models\ClassModel();
        $allClasses = $classModel->getAll();
        
        // Set default academic year to current if not specified
        if (empty($academicYearId)) {
            $currentAcademicYear = $academicYearModel->getCurrent();
            if ($currentAcademicYear) {
                $academicYearId = $currentAcademicYear['id'];
            }
        }
        
        $this->view('payments/index', [
            'payments' => $payments['data'] ?? $payments,
            'academicYears' => $academicYears,
            'allClasses' => $allClasses,
            'pagination' => $payments,
            'searchTerm' => $searchTerm,
            'academicYearId' => $academicYearId,
            'classId' => $classId,
            'dateFrom' => $dateFrom,
            'dateTo' => $dateTo,
            'enableDateFilter' => $enableDateFilter,
            'perPage' => $perPage
        ]);
    }
    
    public function show($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }
        
        $paymentModel = new Payment();
        $payment = $paymentModel->getByIdWithDetails($id);
        
        if (!$payment) {
            $this->jsonResponse(['success' => false, 'error' => 'Payment not found'], 404);
            return;
        }
        
        // Get student details
        $studentModel = new Student();
        $student = $studentModel->find($payment['student_id']);
        
        // Get fee details if fee_id exists
        $fee = null;
        if (!empty($payment['fee_id'])) {
            $feeModel = new Fee();
            $fee = $feeModel->find($payment['fee_id']);
        }
        
        // Get school settings
        require_once APP_PATH . '/Helpers/TemplateHelper.php';
        $schoolSettings = getSchoolSettings();
        
        $this->jsonResponse([
            'success' => true,
            'payment' => $payment,
            'student' => $student,
            'fee' => $fee,
            'school_name' => $schoolSettings['school_name'] ?? 'APSA-ERP',
            'school_logo' => $schoolSettings['school_logo'] ?? null
        ]);
    }
    
    public function store()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }
        
        if ($this->requestMethod() === 'POST' || $this->requestMethod() === 'PUT') {
            // Disable error display to prevent HTML in JSON responses
            ini_set('display_errors', 0);
            
            try {
                // Get current academic year for audit logging
                $academicYearModel = new AcademicYear();
                $currentAcademicYear = $academicYearModel->getCurrentAcademicYearWithTerm();
                
                $studentId = $this->post('student_id');
                $method = $this->post('method');
                $date = $this->post('date');
                $remarks = $this->post('remarks');
                
                // Validate required fields
                if (empty($studentId) || empty($method) || empty($date)) {
                    $this->jsonResponse(['success' => false, 'error' => 'Student ID, payment method, and date are required'], 400);
                    return;
                }
                
                // Validate date format
                $dateObj = \DateTime::createFromFormat('Y-m-d', $date);
                if (!$dateObj || $dateObj->format('Y-m-d') !== $date) {
                    $this->jsonResponse(['success' => false, 'error' => 'Invalid date format'], 400);
                    return;
                }
                
                // Get payments array
                $payments = [];
                $paymentIndex = 0;
                
                // Handle payments data from form
                while (isset($_POST["payments"][$paymentIndex])) {
                    $paymentData = $_POST["payments"][$paymentIndex];
                    if (isset($paymentData['fee_id']) && isset($paymentData['amount'])) {
                        $payments[] = [
                            'fee_id' => $paymentData['fee_id'],
                            'amount' => $paymentData['amount']
                        ];
                    }
                    $paymentIndex++;
                }
                
                // If no payments found in array format, try alternative format
                if (empty($payments)) {
                    // Check for individual payment fields
                    $feeIds = $this->post('fee_id', []);
                    $amounts = $this->post('amount', []);
                    
                    if (!empty($feeIds) && is_array($feeIds)) {
                        for ($i = 0; $i < count($feeIds); $i++) {
                            if (isset($amounts[$i]) && $amounts[$i] > 0) {
                                $payments[] = [
                                    'fee_id' => $feeIds[$i],
                                    'amount' => $amounts[$i]
                                ];
                            }
                        }
                    }
                }
                
                // Validate payments
                if (empty($payments)) {
                    $this->jsonResponse(['success' => false, 'error' => 'At least one payment is required'], 400);
                    return;
                }
                
                // Validate payment amounts
                foreach ($payments as $payment) {
                    if (!is_numeric($payment['amount']) || $payment['amount'] <= 0) {
                        $this->jsonResponse(['success' => false, 'error' => 'Payment amounts must be valid numbers greater than zero'], 400);
                        return;
                    }
                }
                
                $paymentModel = new Payment();
                $studentModel = new Student();
                
                // Verify student exists
                $student = $studentModel->find($studentId);
                if (!$student) {
                    $this->jsonResponse(['success' => false, 'error' => 'Student not found'], 404);
                    return;
                }
                
                // Collect payment method-specific fields
                $paymentData = [
                    'student_id' => $studentId,
                    'method' => $method,
                    'date' => $date,
                    'remarks' => $remarks,
                    'academic_year_id' => $currentAcademicYear ? $currentAcademicYear['id'] : null,
                    'term' => $currentAcademicYear ? $currentAcademicYear['term'] : null
                ];
                
                // Add payment method-specific fields
                if ($method === 'cash') {
                    $paymentData['cash_payer_name'] = $this->post('cash_payer_name');
                    $paymentData['cash_payer_phone'] = $this->post('cash_payer_phone');
                } elseif ($method === 'mobile_money') {
                    $paymentData['mobile_money_sender_name'] = $this->post('mobile_money_sender_name');
                    $paymentData['mobile_money_sender_number'] = $this->post('mobile_money_sender_number');
                    $paymentData['mobile_money_reference'] = $this->post('mobile_money_reference');
                } elseif ($method === 'bank_transfer') {
                    $paymentData['bank_transfer_sender_bank'] = $this->post('bank_transfer_sender_bank');
                    $paymentData['bank_transfer_invoice_number'] = $this->post('bank_transfer_invoice_number');
                    $paymentData['bank_transfer_details'] = $this->post('bank_transfer_details');
                } elseif ($method === 'cheque') {
                    $paymentData['cheque_bank'] = $this->post('cheque_bank');
                    $paymentData['cheque_number'] = $this->post('cheque_number');
                    $paymentData['cheque_details'] = $this->post('cheque_details');
                }
                
                // Process each payment
                $paymentIds = [];
                $feeModel = new Fee();
                
                foreach ($payments as $payment) {
                    $feeId = $payment['fee_id'];
                    $amount = $payment['amount'];
                    
                    // Verify fee exists
                    $fee = $feeModel->find($feeId);
                    if (!$fee) {
                        $this->jsonResponse(['success' => false, 'error' => "Fee structure with ID {$feeId} not found"], 404);
                        return;
                    }
                    
                    // Add fee_id to payment data
                    $paymentData['fee_id'] = $feeId;
                    $paymentData['amount'] = $amount;
                    
                    // Create payment record
                    $paymentId = $paymentModel->create($paymentData);
                    if (!$paymentId) {
                        $this->jsonResponse(['success' => false, 'error' => 'Failed to record payment'], 500);
                        return;
                    }
                    
                    $paymentIds[] = $paymentId;
                }
                
                // Generate and store receipt for each payment
                $receiptModel = new Receipt();
                $receiptIds = [];
                
                foreach ($paymentIds as $paymentId) {
                    $payment = $paymentModel->find($paymentId);
                    $student = $studentModel->find($payment['student_id']);
                    
                    // Format payment method
                    $methodLabels = [
                        'cash' => 'Cash',
                        'cheque' => 'Cheque',
                        'bank_transfer' => 'Bank Transfer',
                        'mobile_money' => 'Mobile Money',
                        'other' => 'Other'
                    ];
                    $methodLabel = $methodLabels[$method] ?? ucfirst(str_replace('_', ' ', $method));
                    
                    // Format date
                    $paymentDate = date('M j, Y', strtotime($payment['date'] ?? ''));
                    
                    // Get payment method specific details
                    $methodDetails = [];
                    if ($method === 'cash' && ($payment['cash_payer_name'] || $payment['cash_payer_phone'])) {
                        $methodDetails = [
                            'payer_name' => $payment['cash_payer_name'],
                            'payer_phone' => $payment['cash_payer_phone']
                        ];
                    } elseif ($method === 'mobile_money' && ($payment['mobile_money_sender_name'] || $payment['mobile_money_sender_number'] || $payment['mobile_money_reference'])) {
                        $methodDetails = [
                            'sender_name' => $payment['mobile_money_sender_name'],
                            'sender_number' => $payment['mobile_money_sender_number'],
                            'reference' => $payment['mobile_money_reference']
                        ];
                    } elseif ($method === 'bank_transfer' && ($payment['bank_transfer_sender_bank'] || $payment['bank_transfer_invoice_number'] || $payment['bank_transfer_details'])) {
                        $methodDetails = [
                            'sender_bank' => $payment['bank_transfer_sender_bank'],
                            'invoice_number' => $payment['bank_transfer_invoice_number'],
                            'details' => $payment['bank_transfer_details']
                        ];
                    } elseif ($method === 'cheque' && ($payment['cheque_bank'] || $payment['cheque_number'] || $payment['cheque_details'])) {
                        $methodDetails = [
                            'bank' => $payment['cheque_bank'],
                            'cheque_number' => $payment['cheque_number'],
                            'details' => $payment['cheque_details']
                        ];
                    }
                    
                    // Prepare receipt data
                    $receiptData = [
                        'payment' => $payment,
                        'student' => $student,
                        'method_label' => $methodLabel,
                        'payment_date' => $paymentDate,
                        'method_details' => $methodDetails,
                        'generated_at' => date('Y-m-d H:i:s')
                    ];
                    
                    // Store receipt
                    $receiptId = $receiptModel->createReceipt($paymentId, $receiptData);
                    if ($receiptId) {
                        $receiptIds[] = $receiptId;
                    }
                }
                
                // Log audit trail with academic year and term
                AuditHelper::log(
                    $_SESSION['user']['id'],
                    'create',
                    'payments',
                    null,
                    null,
                    [
                        'student_id' => $studentId,
                        'method' => $method,
                        'date' => $date,
                        'payment_count' => count($paymentIds)
                    ],
                    $currentAcademicYear ? $currentAcademicYear['id'] : null,
                    $currentAcademicYear ? $currentAcademicYear['term'] : null
                );
                
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Payment recorded successfully',
                    'payment_ids' => $paymentIds,
                    'receipt_ids' => $receiptIds
                ]);
                
            } catch (\Exception $e) {
                error_log("Payment error: " . $e->getMessage());
                $this->jsonResponse(['success' => false, 'error' => 'An error occurred while processing the payment'], 500);
            }
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Invalid request method'], 400);
        }
    }
    
    public function delete($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $paymentModel = new Payment();
        $payment = $paymentModel->find($id);
        
        if (!$payment) {
            $this->flash('error', 'Payment not found');
            $this->redirect('/payments');
        }
        
        // Get current academic year for audit logging
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrentAcademicYearWithTerm();
        
        $result = $paymentModel->delete($id);
        
        if ($result) {
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'],
                'delete',
                'payments',
                $id,
                $payment, // old values
                null, // new values
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            $this->flash('success', 'Payment deleted successfully');
        } else {
            $this->flash('error', 'Failed to delete payment');
        }
        
        $this->redirect('/payments');
    }
    
    /**
     * Get student fees for payment processing
     */
    public function getStudentFees($studentId)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }
        
        try {
            $studentModel = new Student();
            $student = $studentModel->find($studentId);
            
            if (!$student) {
                $this->jsonResponse(['success' => false, 'error' => 'Student not found'], 404);
                return;
            }
            
            // Get financial records for the student
            $financialRecords = $studentModel->getFinancialRecords($studentId);
            $fees = $financialRecords['fees'];
            
            // Format the data for the frontend
            $feeAllocations = [];
            foreach ($fees as $fee) {
                $feeAllocations[] = [
                    'fee_id' => $fee['id'],
                    'fee_name' => $fee['name'],
                    'amount' => $fee['amount'],
                    'paid_amount' => $fee['paid_amount'],
                    'balance' => $fee['balance'],
                    'status' => $fee['status']
                ];
            }
            
            $this->jsonResponse([
                'success' => true,
                'fee_allocations' => $feeAllocations
            ]);
            
        } catch (\Exception $e) {
            error_log("Error getting student fees: " . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Failed to load payment structures'], 500);
        }
    }
    
    private function generateReceipt()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }
        
        // Get payment ID from POST data
        $paymentId = $this->post('payment_id');
        
        if (empty($paymentId)) {
            $this->jsonResponse(['success' => false, 'error' => 'Payment ID is required'], 400);
            return;
        }
        
        $paymentModel = new Payment();
        $payment = $paymentModel->find($paymentId);
        
        if (!$payment) {
            $this->jsonResponse(['success' => false, 'error' => 'Payment not found'], 404);
            return;
        }
        
        $studentModel = new Student();
        $student = $studentModel->find($payment['student_id']);
        
        // Get fee details if fee_id exists
        $fee = null;
        if (!empty($payment['fee_id'])) {
            $feeModel = new Fee();
            $fee = $feeModel->find($payment['fee_id']);
        }
        
        // Get school settings
        require_once APP_PATH . '/Helpers/TemplateHelper.php';
        $schoolSettings = getSchoolSettings();
        
        $this->jsonResponse([
            'success' => true,
            'payment' => $payment,
            'student' => $student,
            'fee' => $fee,
            'school_name' => $schoolSettings['school_name'] ?? 'APSA-ERP',
            'school_logo' => $schoolSettings['school_logo'] ?? null
        ]);
    }
}