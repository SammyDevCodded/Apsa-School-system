<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Fee;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\FeeAssignment;
use App\Models\AcademicYear;
use App\Helpers\AuditHelper;

class FeeController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $tab = $this->get('tab', 'structures');
        
        if ($tab === 'pay') {
            // For pay tab, we need fee structures and classes
            $feeModel = new Fee();
            $fees = $feeModel->getAllWithOriginalClassesAndStudentCount();
            
            $classModel = new ClassModel();
            $classes = $classModel->getAll();
            
            $this->view('fees/index', [
                'fees' => $fees,
                'classes' => $classes,
                'tab' => 'pay'
            ]);
        } elseif ($tab === 'payments') {
            // For payments tab, we need payment data
            $paymentModel = new \App\Models\Payment();
            
            // Get filter parameters
            $searchTerm = $this->get('search');
            $academicYearId = $this->get('academic_year_id');
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
            
            // Get payments with filtering and pagination
            $payments = $paymentModel->getAllWithDetails($filters, $searchTerm, $page, $perPage);
            
            // Get academic years for filtering
            $academicYearModel = new \App\Models\AcademicYear();
            $academicYears = $academicYearModel->getAll();
            
            // Get school settings
            require_once APP_PATH . '/Helpers/TemplateHelper.php';
            $schoolSettings = getSchoolSettings();
            
            $this->view('fees/index', [
                'payments' => $payments['data'] ?? $payments,
                'academicYears' => $academicYears,
                'tab' => 'payments',
                'pagination' => $payments,
                'searchTerm' => $searchTerm,
                'academicYearId' => $academicYearId,
                'dateFrom' => $dateFrom,
                'dateTo' => $dateTo,
                'enableDateFilter' => $enableDateFilter,
                'perPage' => $perPage,
                'schoolSettings' => $schoolSettings
            ]);
        } else {
            // For structures tab, we need fee data
            $feeModel = new Fee();
            $fees = $feeModel->getAllWithOriginalClassesAndStudentCount();
            
            $classModel = new ClassModel();
            $classes = $classModel->getAll();
            
            $this->view('fees/index', [
                'fees' => $fees,
                'classes' => $classes,
                'tab' => 'structures'
            ]);
        }
    }
    
    public function show($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Handle AJAX request for students
        if ($this->isAjaxRequest() && isset($_GET['students'])) {
            $feeModel = new Fee();
            $fee = $feeModel->find($id);
            
            if (!$fee) {
                $this->jsonResponse(['success' => false, 'error' => 'Fee structure not found'], 404);
                return;
            }
            
            // Get students assigned to this fee
            $feeAssignmentModel = new FeeAssignment();
            $students = $feeAssignmentModel->getByFeeId($id);
            
            // Get classes assigned to this fee
            $classes = $feeAssignmentModel->getClassesByFeeId($id);
            
            // Add fee amount to each student
            foreach ($students as &$student) {
                $student['fee_amount'] = $fee['amount'];
            }
            
            $this->jsonResponse([
                'success' => true,
                'fee' => $fee,
                'students' => $students,
                'classes' => $classes
            ]);
            return;
        }
        
        $feeModel = new Fee();
        $fee = $feeModel->find($id);
        
        if (!$fee) {
            $this->flash('error', 'Fee structure not found');
            $this->redirect('/fees');
        }
        
        // Get originally selected classes for this fee
        $originalClassIds = $feeModel->getOriginalClasses($id);
        
        if (!empty($originalClassIds)) {
            // Get class details for the originally selected classes
            $classModel = new ClassModel();
            $assignedClasses = [];
            foreach ($originalClassIds as $classId) {
                $class = $classModel->find($classId);
                if ($class) {
                    $assignedClasses[] = $class;
                }
            }
        } else {
            // Fallback to previous method if no original classes stored
            $feeAssignmentModel = new FeeAssignment();
            $assignedClasses = $feeAssignmentModel->getOriginalClassesByFeeId($id);
            
            // If still no classes found, fall back to all assigned classes
            if (empty($assignedClasses)) {
                $assignedClasses = $feeAssignmentModel->getClassesByFeeId($id);
            }
        }
        
        // Get assigned students for this fee
        $feeAssignmentModel = new FeeAssignment();
        $assignedStudents = $feeAssignmentModel->getByFeeId($id);
        
        $this->view('fees/show', [
            'fee' => $fee,
            'assignedClasses' => $assignedClasses,
            'assignedStudents' => $assignedStudents
        ]);
    }
    
    public function create()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        $this->view('fees/create', [
            'classes' => $classes,
            'currentAcademicYear' => $currentAcademicYear
        ]);
    }
    
    public function store()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        if ($this->requestMethod() === 'POST' || $this->requestMethod() === 'PUT') {
            // Get current academic year
            $academicYearModel = new AcademicYear();
            $currentAcademicYear = $academicYearModel->getCurrent();
            
            $data = [
                'name' => $this->post('name'),
                'amount' => $this->post('amount'),
                'type' => $this->post('type'),
                'class_id' => $this->post('class_id'), // This will now be comma-separated values
                'description' => $this->post('description'),
                'academic_year_id' => $currentAcademicYear ? $currentAcademicYear['id'] : null,
                'term' => $currentAcademicYear ? $currentAcademicYear['term'] : null
            ];
            
            // Basic validation
            if (empty($data['name']) || empty($data['amount']) || empty($data['type'])) {
                $this->flash('error', 'Fee name, amount, and type are required');
                $this->redirect('/fees/create');
            }
            
            // Validate amount is numeric
            if (!is_numeric($data['amount'])) {
                $this->flash('error', 'Fee amount must be a valid number');
                $this->redirect('/fees/create');
            }
            
            $feeModel = new Fee();
            
            // Create the fee structure
            $feeId = $feeModel->create($data);
            
            if ($feeId) {
                // Store originally selected classes
                $selectedClassIds = $this->post('selected_classes', []);
                if (!empty($selectedClassIds)) {
                    $feeModel->setOriginalClasses($feeId, $selectedClassIds);
                }
                
                // If students were selected, assign them to the fee
                if (!empty($this->post('selected_students'))) {
                    $selectedStudentIds = $this->post('selected_students');
                    $feeAssignmentModel = new FeeAssignment();
                    
                    // Assign students to the fee
                    if (!empty($selectedStudentIds)) {
                        $feeAssignmentModel->assignStudents($feeId, $selectedStudentIds);
                    }
                }
                
                // Log audit trail with academic year and term
                AuditHelper::log(
                    $_SESSION['user']['id'],
                    'create',
                    'fees',
                    $feeId,
                    null,
                    $data,
                    $currentAcademicYear ? $currentAcademicYear['id'] : null,
                    $currentAcademicYear ? $currentAcademicYear['term'] : null
                );
                
                $this->flash('success', 'Fee structure created successfully');
                $this->redirect('/fees');
            } else {
                $this->flash('error', 'Failed to create fee structure');
                $this->redirect('/fees/create');
            }
        } else {
            $this->create();
        }
    }
    
    public function edit($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $feeModel = new Fee();
        $fee = $feeModel->find($id);
        
        if (!$fee) {
            $this->flash('error', 'Fee structure not found');
            $this->redirect('/fees');
        }
        
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        // Get assigned students for this fee
        $feeAssignmentModel = new FeeAssignment();
        $assignedStudents = $feeAssignmentModel->getByFeeId($id);
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        $this->view('fees/edit', [
            'fee' => $fee,
            'classes' => $classes,
            'assignedStudents' => $assignedStudents,
            'currentAcademicYear' => $currentAcademicYear
        ]);
    }
    
    public function update($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        if ($this->requestMethod() === 'POST' || $this->requestMethod() === 'PUT') {
            $feeModel = new Fee();
            $fee = $feeModel->find($id);
            
            if (!$fee) {
                $this->flash('error', 'Fee structure not found');
                $this->redirect('/fees');
            }
            
            $data = [
                'name' => $this->post('name'),
                'amount' => $this->post('amount'),
                'type' => $this->post('type'),
                'class_id' => $this->post('class_id'),
                'description' => $this->post('description')
            ];
            
            // Basic validation
            if (empty($data['name']) || empty($data['amount']) || empty($data['type'])) {
                $this->flash('error', 'Fee name, amount, and type are required');
                $this->redirect("/fees/{$id}/edit");
            }
            
            // Validate amount is numeric
            if (!is_numeric($data['amount'])) {
                $this->flash('error', 'Fee amount must be a valid number');
                $this->redirect("/fees/{$id}/edit");
            }
            
            // Update the fee structure
            $result = $feeModel->update($id, $data);
            
            if ($result) {
                // Get current academic year for audit logging
                $academicYearModel = new AcademicYear();
                $currentAcademicYear = $academicYearModel->getCurrent();
                
                // Log audit trail with academic year and term
                AuditHelper::log(
                    $_SESSION['user']['id'],
                    'update',
                    'fees',
                    $id,
                    $fee, // old values
                    $data, // new values
                    $currentAcademicYear ? $currentAcademicYear['id'] : null,
                    $currentAcademicYear ? $currentAcademicYear['term'] : null
                );
                
                $this->flash('success', 'Fee structure updated successfully');
                $this->redirect('/fees');
            } else {
                $this->flash('error', 'Failed to update fee structure');
                $this->redirect("/fees/{$id}/edit");
            }
        } else {
            $this->edit($id);
        }
    }
    
    public function delete($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $feeModel = new Fee();
        $fee = $feeModel->find($id);
        
        if (!$fee) {
            $this->flash('error', 'Fee structure not found');
            $this->redirect('/fees');
        }
        
        // Get current academic year for audit logging
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        $result = $feeModel->delete($id);
        
        if ($result) {
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'],
                'delete',
                'fees',
                $id,
                $fee, // old values
                null, // new values
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            $this->flash('success', 'Fee structure deleted successfully');
        } else {
            $this->flash('error', 'Failed to delete fee structure');
        }
        
        $this->redirect('/fees');
    }
    
    /**
     * Save student assignments to a fee structure
     */
    public function saveStudentAssignments($feeId)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }
        
        try {
            $feeModel = new Fee();
            $fee = $feeModel->find($feeId);
            
            if (!$fee) {
                $this->jsonResponse(['success' => false, 'error' => 'Fee structure not found'], 404);
                return;
            }
            
            // Get selected student IDs from POST data
            $studentIds = $this->post('student_ids', []);
            
            if (empty($studentIds)) {
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['success' => false, 'error' => 'No students selected'], 400);
                    return;
                } else {
                    $this->flash('error', 'No students selected');
                    $this->redirect("/fees/{$feeId}/assign");
                    return;
                }
            }
            
            // Assign students to the fee
            $feeAssignmentModel = new FeeAssignment();
            $result = $feeAssignmentModel->assignStudents($feeId, $studentIds);
            
            if ($result) {
                // Get current academic year for audit logging
                $academicYearModel = new AcademicYear();
                $currentAcademicYear = $academicYearModel->getCurrent();
                
                // Log audit trail with academic year and term
                AuditHelper::log(
                    $_SESSION['user']['id'],
                    'assign_students',
                    'fees',
                    $feeId,
                    null,
                    ['assigned_students' => count($studentIds)],
                    $currentAcademicYear ? $currentAcademicYear['id'] : null,
                    $currentAcademicYear ? $currentAcademicYear['term'] : null
                );
                
                $this->jsonResponse([
                    'success' => true,
                    'message' => 'Students assigned to fee structure successfully'
                ]);
            } else {
                $this->jsonResponse(['success' => false, 'error' => 'Failed to assign students'], 500);
            }
            
        } catch (\Exception $e) {
            error_log("Error saving student assignments: " . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Failed to assign students'], 500);
        }
    }
    
    /**
     * Show assign students form
     */
    public function showAssignStudents($feeId)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $feeModel = new Fee();
        $fee = $feeModel->find($feeId);
        
        if (!$fee) {
            $this->flash('error', 'Fee structure not found');
            $this->redirect('/fees');
        }
        
        // Get all classes for the dropdown
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        $this->view('fees/assign_students', [
            'fee' => $fee,
            'classes' => $classes
        ]);
    }
    
    /**
     * Get students by selected classes (AJAX endpoint)
     */
    public function getStudentsByClasses()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }
        
        try {
            // Get selected class IDs from POST data
            $classIds = $this->post('class_ids', []);
            
            if (empty($classIds)) {
                $this->jsonResponse(['success' => false, 'error' => 'No classes selected'], 400);
                return;
            }
            
            // Get students from selected classes and organize by class
            $studentModel = new Student();
            $studentsByClass = [];
            
            foreach ($classIds as $classId) {
                $students = $studentModel->getByClassId($classId);
                // Get class information
                $classModel = new ClassModel();
                $class = $classModel->find($classId);
                
                $studentsByClass[$classId] = [
                    'class_name' => $class ? $class['name'] : 'Unknown Class',
                    'students' => $students
                ];
            }
            
            $this->jsonResponse([
                'success' => true,
                'students_by_class' => $studentsByClass
            ]);
            
        } catch (\Exception $e) {
            error_log("Error getting students by classes: " . $e->getMessage());
            $this->jsonResponse(['success' => false, 'error' => 'Failed to load students'], 500);
        }
    }
    
    /**
     * Assign students to a fee structure
     */
    public function assignStudents($feeId)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $feeModel = new Fee();
        $fee = $feeModel->find($feeId);
        
        if (!$fee) {
            $this->flash('error', 'Fee structure not found');
            $this->redirect('/fees');
        }
        
        // Get selected class IDs
        $selectedClassIds = $this->post('selected_classes', []);
        
        if (empty($selectedClassIds)) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'error' => 'Please select at least one class'], 400);
                return;
            } else {
                $this->flash('error', 'Please select at least one class');
                $this->redirect("/fees/{$feeId}/assign");
                return;
            }
        }
        
        // Get students from selected classes
        $studentModel = new Student();
        $allStudents = [];
        $selectedStudents = [];
        
        foreach ($selectedClassIds as $classId) {
            $students = $studentModel->getByClassId($classId);
            $allStudents = array_merge($allStudents, $students);
        }
        
        // Get already assigned students to show warning
        $feeAssignmentModel = new FeeAssignment();
        $assignedStudents = $feeAssignmentModel->getByFeeId($feeId);
        $assignedStudentIds = array_column($assignedStudents, 'id');
        
        // Get newly selected students
        $newlySelectedStudentIds = $this->post('selected_students', []);
        
        // Filter out already assigned students
        $studentsToAssign = array_diff($newlySelectedStudentIds, $assignedStudentIds);
        
        if (empty($studentsToAssign)) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'error' => 'No new students selected for assignment'], 400);
                return;
            } else {
                $this->flash('error', 'No new students selected for assignment');
                $this->redirect("/fees/{$feeId}/assign");
                return;
            }
        }
        
        // Assign students to the fee
        $feeAssignmentModel->assignStudents($feeId, $studentsToAssign);
        
        // Get current academic year for audit logging
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        // Log audit trail with academic year and term
        AuditHelper::log(
            $_SESSION['user']['id'],
            'assign_students',
            'fees',
            $feeId,
            null,
            ['assigned_students' => $studentsToAssign],
            $currentAcademicYear ? $currentAcademicYear['id'] : null,
            $currentAcademicYear ? $currentAcademicYear['term'] : null
        );
        
        // Check if this is an AJAX request
        if ($this->isAjaxRequest()) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Students assigned to fee structure successfully'
            ]);
        } else {
            $this->flash('success', 'Students assigned to fee structure successfully');
            $this->redirect('/fees');
        }
    }
}