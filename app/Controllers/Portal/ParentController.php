<?php
namespace App\Controllers\Portal;

use App\Core\Controller;
use App\Models\Student;
use App\Models\ExamResult;
use App\Models\Payment;
use App\Models\FeeAssignment;
use App\Models\ClassModel;

class ParentController extends Controller
{
    private $studentModel;
    private $examResultModel;
    private $paymentModel;
    private $feeAssignmentModel;
    private $classModel;

    public function __construct()
    {
        // Ensure parent is logged in
        if (!isset($_SESSION['parent_logged_in'])) {
            $this->redirect('/portal/login');
        }
        $this->studentModel = new Student();
        $this->examResultModel = new ExamResult();
        $this->paymentModel = new Payment();
        $this->feeAssignmentModel = new FeeAssignment(); // Check if model exists, if not use Fee model
        $this->classModel = new ClassModel();
    }

    public function dashboard()
    {
        $studentId = $_SESSION['student_id'];
        $student = $this->studentModel->find($studentId);
        
        if (!empty($student['class_id'])) {
             $class = $this->classModel->find($student['class_id']);
             $student['class_name'] = $class['name'] ?? 'N/A';
        }

        // Quick Stats
        $assignments = $this->feeAssignmentModel->getByStudentId($studentId);
        $totalFees = 0;
        foreach ($assignments as $fee) $totalFees += $fee['fee_amount'];
        
        $payments = $this->paymentModel->getByStudentId($studentId);
        $totalPaid = 0;
        foreach ($payments as $payment) $totalPaid += $payment['amount'];
        
        $balance = $totalFees - $totalPaid;

        $recentResults = $this->examResultModel->getByStudentId($studentId);
        $resultCount = count($recentResults);

        $this->view('portal/parent/dashboard', [
            'student' => $student,
            'fee_balance' => $balance,
            'exam_count' => $resultCount,
            'portal_role' => 'parent' // Explicitly set role
        ]);
    }

    public function profile()
    {
        $studentId = $_SESSION['student_id'];
        $student = $this->studentModel->find($studentId);
        
        if (!empty($student['class_id'])) {
             $class = $this->classModel->find($student['class_id']);
             $student['class_name'] = $class['name'] ?? 'N/A';
        }

        $this->view('portal/parent/profile', [
            'student' => $student
        ]);
    }

    public function academics()
    {
        $studentId = $_SESSION['student_id'];
        $results = $this->examResultModel->getByStudentId($studentId);
        
        $this->view('portal/parent/academics', [
            'results' => $results
        ]);
    }
    
     public function fees()
    {
        $studentId = $_SESSION['student_id'];
        
        $assignments = $this->feeAssignmentModel->getByStudentId($studentId);
        $payments = $this->paymentModel->getByStudentId($studentId);
        
        $totalBilled = 0;
        foreach ($assignments as $a) $totalBilled += $a['fee_amount'];
        
        $totalPaid = 0;
        foreach ($payments as $p) $totalPaid += $p['amount'];
        
        $balance = $totalBilled - $totalPaid;

        $this->view('portal/parent/fees', [
            'assignments' => $assignments,
            'payments' => $payments,
            'total_billed' => $totalBilled,
            'total_paid' => $totalPaid,
            'balance' => $balance
        ]);
    }

    public function getProfileData()
    {
        $studentId = $_SESSION['student_id'];
        $student = $this->studentModel->find($studentId);
        
        if (!empty($student['class_id'])) {
             $class = $this->classModel->find($student['class_id']);
             $student['class_name'] = $class['name'] ?? 'N/A';
        }

        // Return JSON
        header('Content-Type: application/json');
        echo json_encode(['student' => $student]);
        exit;
    }
}
