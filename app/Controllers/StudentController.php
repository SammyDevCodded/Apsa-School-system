<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Student;
use App\Models\ClassModel;
use App\Helpers\AuditHelper;
use App\Models\Notification;
use App\Helpers\IdGeneratorHelper;
use App\Models\AcademicYear;

class StudentController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $studentModel = new Student();
        $classModel = new ClassModel();
        
        // Get pagination parameters
        $page = (int) $this->get('page', 1);
        // Get per_page parameter with default of 10, and allow values of 10, 25, 50, 100
        $perPage = (int) $this->get('per_page', 10);
        $allowedPerPage = [10, 25, 50, 100];
        if (!in_array($perPage, $allowedPerPage)) {
            $perPage = 10;
        }
        
        // Get search and filter parameters
        $searchTerm = $this->get('search', '');
        $filters = [
            'admission_no' => $this->get('admission_no', ''),
            'name' => $this->get('name', ''),
            'class_id' => $this->get('class_id', ''),
            'category' => $this->get('category', ''),
            'guardian' => $this->get('guardian', ''),
            'date_mode' => $this->get('date_mode', ''), // New date mode filter
            'date_from' => $this->get('date_from', ''), // New date from filter
            'date_to' => $this->get('date_to', '')       // New date to filter
        ];
        
        // Get students based on search and filters with pagination
        $studentsResult = $studentModel->searchWithClassPaginated($searchTerm, $filters, $page, $perPage);
        
        // Get all classes for filter dropdown
        $classes = $classModel->getAll();
        
        // Check if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        if ($isAjax) {
            // For AJAX requests, only render the table and pagination
            $this->view('students/partials/table', [
                'students' => $studentsResult['data'],
                'pagination' => [
                    'current_page' => $studentsResult['page'],
                    'total_pages' => $studentsResult['total_pages'],
                    'total_records' => $studentsResult['total'],
                    'per_page' => $studentsResult['per_page']
                ],
                'filters' => $filters,
                'searchTerm' => $searchTerm
            ]);
        } else {
            // For regular requests, render the full page
            $this->view('students/index', [
                'students' => $studentsResult['data'],
                'classes' => $classes,
                'searchTerm' => $searchTerm,
                'filters' => $filters,
                'pagination' => [
                    'current_page' => $studentsResult['page'],
                    'total_pages' => $studentsResult['total_pages'],
                    'total_records' => $studentsResult['total'],
                    'per_page' => $studentsResult['per_page']
                ]
            ]);
        }
    }
    
    public function show($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Unauthorized'], 401);
            } else {
                $this->redirect('/login');
            }
            return;
        }
        
        $studentModel = new Student();
        $student = $studentModel->getByIdWithClass($id);
        
        if (!$student) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Student not found'], 404);
            } else {
                $this->flash('error', 'Student not found');
                $this->redirect('/students');
            }
            return;
        }
        
        // Get academic records
        $academicRecords = $studentModel->getAcademicRecords($id);
        
        // Group academic records by academic year, term, and exam
        $groupedAcademicRecords = [];
        foreach ($academicRecords as $record) {
            $academicYear = !empty($record['academic_year_name']) ? $record['academic_year_name'] : 'Unknown Year';
            $term = !empty($record['term']) ? $record['term'] : 'Unknown Term';
            $examName = !empty($record['exam_name']) ? $record['exam_name'] : 'Unknown Exam';
            
            if (!isset($groupedAcademicRecords[$academicYear])) {
                $groupedAcademicRecords[$academicYear] = [];
            }
            
            if (!isset($groupedAcademicRecords[$academicYear][$term])) {
                $groupedAcademicRecords[$academicYear][$term] = [];
            }
            
            if (!isset($groupedAcademicRecords[$academicYear][$term][$examName])) {
                $groupedAcademicRecords[$academicYear][$term][$examName] = [];
            }
            
            $groupedAcademicRecords[$academicYear][$term][$examName][] = $record;
        }
        
        // Get financial records
        $financialInfo = $studentModel->getFinancialRecords($id);
        
        // Get promotion history
        $promotionModel = new \App\Models\Promotion();
        $promotionHistory = $promotionModel->getStudentPromotionHistory($id);
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();

        // Get Grouped Attendance Records
        $attendanceModel = new \App\Models\Attendance();
        $attendanceRecords = $attendanceModel->getByStudentId($id);
        $academicYears = $academicYearModel->getAll();

        $groupedAttendance = [];
        foreach ($attendanceRecords as $record) {
            $recordDate = strtotime($record['date']);
            $yearLabel = 'Unknown Year';
            $termLabel = '';

            foreach ($academicYears as $year) {
                if ($recordDate >= strtotime($year['start_date']) && $recordDate <= strtotime($year['end_date'])) {
                    $yearLabel = $year['name'];
                    $termLabel = $year['term'] ?? ''; // Assuming term is stored in academic_years
                    break;
                }
            }
            
            // Construct a key for grouping: "Year Name - Term"
            $groupKey = $yearLabel;
            if (!empty($termLabel)) {
                $groupKey .= ' - ' . $termLabel;
            }

            if (!isset($groupedAttendance[$groupKey])) {
                $groupedAttendance[$groupKey] = [
                    'year' => $yearLabel,
                    'term' => $termLabel,
                    'present' => 0,
                    'absent' => 0,
                    'late' => 0,
                    'total' => 0,
                    'records' => []
                ];
            }

            $groupedAttendance[$groupKey]['records'][] = $record;
            $groupedAttendance[$groupKey]['total']++;
            
            $status = strtolower($record['status']);
            if (isset($groupedAttendance[$groupKey][$status])) {
                $groupedAttendance[$groupKey][$status]++;
            }
        }
        
        // Sort grouped attendance by academic year (descending logic if possible, or leave as is)
        // Since we iterate through attendance records which are likely sorted by date DESC, the groups might appear in that order of first encounter. 
        // We can sort keys if needed, but array order depends on first insertion.

        
        // Get school settings for branding
        $settingModel = new \App\Models\Setting();
        $settings = $settingModel->getSettings();
        
        if ($this->isAjaxRequest()) {
            // For AJAX requests, return JSON data for the modal
            $this->jsonResponse([
                'success' => true,
                'student' => $student
            ]);
        } else {
            // For regular requests, render the full page
            $this->view('students/show', [
                'student' => $student,
                'academicRecords' => $academicRecords,
                'groupedAcademicRecords' => $groupedAcademicRecords,
                'financialInfo' => $financialInfo,
                'promotionHistory' => $promotionHistory,
                'currentAcademicYear' => $currentAcademicYear,
                'groupedAttendance' => $groupedAttendance,
                'settings' => $settings
            ]);
        }
    }
    
    public function create()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Unauthorized'], 401);
            } else {
                $this->redirect('/login');
            }
            return;
        }
        
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        // Generate a default admission number using the helper
        $defaultAdmissionNo = IdGeneratorHelper::generateAdmissionNumber();
        $formatDescription = IdGeneratorHelper::getAdmissionFormatDescription();
        
        if ($this->isAjaxRequest()) {
            // For AJAX requests, only render the form content without the layout
            $this->view('students/partials/create_form', [
                'classes' => $classes,
                'defaultAdmissionNo' => $defaultAdmissionNo,
                'formatDescription' => $formatDescription,
                'currentAcademicYear' => $currentAcademicYear
            ]);
        } else {
            // For regular requests, render the full page
            $this->view('students/create', [
                'classes' => $classes,
                'defaultAdmissionNo' => $defaultAdmissionNo,
                'formatDescription' => $formatDescription,
                'currentAcademicYear' => $currentAcademicYear
            ]);
        }
    }
    
    public function store()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Unauthorized'], 401);
            } else {
                $this->redirect('/login');
            }
            return;
        }
        
        if ($this->requestMethod() === 'POST') {
            // Get current academic year
            $academicYearModel = new AcademicYear();
            $currentAcademicYear = $academicYearModel->getCurrent();
            
            $data = [
                'admission_no' => $this->post('admission_no'),
                'first_name' => $this->post('first_name'),
                'last_name' => $this->post('last_name'),
                'dob' => $this->post('dob'),
                'gender' => $this->post('gender'),
                'class_id' => $this->post('class_id'),
                'guardian_name' => $this->post('guardian_name'),
                'guardian_phone' => $this->post('guardian_phone'),
                'address' => $this->post('address'),
                'medical_info' => $this->post('medical_info'),
                'student_category' => $this->post('student_category', 'regular_day'),
                'student_category_details' => $this->post('student_category_details'),
                'admission_date' => $this->post('admission_date'),
                'academic_year_id' => $currentAcademicYear ? $currentAcademicYear['id'] : null // Add academic year ID
            ];
            
            // Handle profile picture upload
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleProfilePictureUpload($_FILES['profile_picture']);
                if ($uploadResult['success']) {
                    $data['profile_picture'] = $uploadResult['filename'];
                } else {
                    $this->flash('error', $uploadResult['message']);
                    if ($this->isAjaxRequest()) {
                        $this->jsonResponse(['error' => $uploadResult['message']], 400);
                    } else {
                        $this->redirect('/students/create');
                    }
                    return;
                }
            }
            
            // Basic validation
            $requiredFields = ['admission_no' => 'Admission number', 'first_name' => 'First name', 'last_name' => 'Last name', 'dob' => 'Date of birth', 'gender' => 'Gender', 'class_id' => 'Class', 'guardian_phone' => 'Guardian phone'];
            $missingFields = [];
            foreach ($requiredFields as $field => $label) {
                if (empty($data[$field])) {
                    $missingFields[] = $label;
                }
            }
            
            if ($data['student_category'] === 'international' && empty($data['student_category_details'])) {
                $missingFields[] = 'Category details';
            }
            
            if (!empty($missingFields)) {
                $errorMessage = 'The following fields are required: ' . implode(', ', $missingFields);
                $this->flash('error', $errorMessage);
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => $errorMessage], 400);
                } else {
                    $this->redirect('/students/create');
                }
                return;
            }
            
            $studentModel = new Student();
            
            // Check if admission number already exists
            $existingStudents = $studentModel->findByAdmissionNo($data['admission_no']);
            if (!empty($existingStudents)) {
                $this->flash('error', 'A student with this admission number already exists');
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'A student with this admission number already exists'], 400);
                } else {
                    $this->redirect('/students/create');
                }
                return;
            }
            
            // Create the student
            $studentId = $studentModel->create($data);
            
            if ($studentId) {
                // Get class name for the success message
                $classModel = new ClassModel();
                $class = $classModel->find($data['class_id']);
                $className = $class ? $class['name'] : 'Unknown Class';
                
                // AUTO-ASSIGN FEES: If the class has assigned fees, automatically assign them to this new student
                if (!empty($data['class_id'])) {
                    $feeModel = new \App\Models\Fee();
                    $classFees = $feeModel->getFeesByClassId($data['class_id']);
                    
                    if (!empty($classFees)) {
                        $feeAssignmentModel = new \App\Models\FeeAssignment();
                        foreach ($classFees as $fee) {
                            $feeAssignmentModel->assignStudents($fee['id'], [$studentId]);
                        }
                    }
                }
                
                // Log audit trail with academic year and term
                $academicYearModel = new AcademicYear();
                $currentAcademicYear = $academicYearModel->getCurrent();
                
                AuditHelper::log(
                    $_SESSION['user']['id'],
                    'create',
                    'students',
                    $studentId,
                    'Created student: ' . $data['first_name'] . ' ' . $data['last_name'],
                    null, // newValues
                    $currentAcademicYear ? $currentAcademicYear['id'] : null,
                    $currentAcademicYear ? $currentAcademicYear['term'] : null
                );
                
                // Create notification
                $notificationModel = new Notification();
                $notificationData = [
                    'user_id' => $_SESSION['user']['id'],
                    'message' => 'Created student: ' . $data['first_name'] . ' ' . $data['last_name'],
                    'type' => 'student',
                    'is_read' => 0,
                    'related_id' => $studentId,
                    'related_type' => 'student'
                ];
                $notificationModel->create($notificationData);
                
                $this->flash('success', 'Successfully added student ' . $data['first_name'] . ' ' . $data['last_name'] . ' to class ' . $className);
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['success' => true, 'message' => 'Student created successfully', 'student_id' => $studentId]);
                } else {
                    $this->redirect('/students/' . $studentId);
                }
            } else {
                $this->flash('error', 'Failed to create student');
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'Failed to create student'], 500);
                } else {
                    $this->redirect('/students/create');
                }
            }
        } else {
            $this->create();
        }
    }
    
    public function edit($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Unauthorized'], 401);
            } else {
                $this->redirect('/login');
            }
            return;
        }
        
        $studentModel = new Student();
        $student = $studentModel->find($id);
        
        if (!$student) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Student not found'], 404);
            } else {
                $this->flash('error', 'Student not found');
                $this->redirect('/students');
            }
            return;
        }
        
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        $formatDescription = IdGeneratorHelper::getAdmissionFormatDescription();
        
        if ($this->isAjaxRequest()) {
            // For AJAX requests, only render the form content without the layout
            $this->view('students/partials/edit_form', [
                'student' => $student,
                'classes' => $classes,
                'formatDescription' => $formatDescription,
                'currentAcademicYear' => $currentAcademicYear
            ]);
        } else {
            // For regular requests, render the full page
            $this->view('students/edit', [
                'student' => $student,
                'classes' => $classes,
                'formatDescription' => $formatDescription,
                'currentAcademicYear' => $currentAcademicYear
            ]);
        }
    }
    
    public function update($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Unauthorized'], 401);
            } else {
                $this->redirect('/login');
            }
            return;
        }
        
        if ($this->requestMethod() === 'POST' || $this->requestMethod() === 'PUT') {
            
            $data = [
                'admission_no' => $this->post('admission_no'),
                'first_name' => $this->post('first_name'),
                'last_name' => $this->post('last_name'),
                'dob' => $this->post('dob'),
                'gender' => $this->post('gender'),
                'class_id' => $this->post('class_id'),
                'guardian_name' => $this->post('guardian_name'),
                'guardian_phone' => $this->post('guardian_phone'),
                'address' => $this->post('address'),
                'medical_info' => $this->post('medical_info'),
                'student_category' => $this->post('student_category', 'regular_day'),
                'student_category_details' => $this->post('student_category_details'),
                'admission_date' => $this->post('admission_date')
                // Note: academic_year_id is not included here to preserve existing value
            ];
            
            // Handle academic_year_id - if provided, use it; if empty string, set to NULL
            $academicYearId = $this->post('academic_year_id');
            if ($academicYearId !== null && $academicYearId !== '' && $academicYearId !== '0') {
                $data['academic_year_id'] = $academicYearId;
            } else if ($academicYearId === '' || $academicYearId === '0') {
                // Explicitly set to null if empty string or '0' is provided
                $data['academic_year_id'] = null;
            } else {
                // If academic_year_id is not provided in the form at all, don't include it in the update data
                // This preserves the existing value in the database
                unset($data['academic_year_id']);
            }
            
            // Handle profile picture upload
            if (isset($_FILES['profile_picture']) && $_FILES['profile_picture']['error'] === UPLOAD_ERR_OK) {
                $uploadResult = $this->handleProfilePictureUpload($_FILES['profile_picture']);
                if ($uploadResult['success']) {
                    $data['profile_picture'] = $uploadResult['filename'];
                } else {
                    if ($this->isAjaxRequest()) {
                        $this->jsonResponse(['error' => $uploadResult['message']], 400);
                    } else {
                        $this->flash('error', $uploadResult['message']);
                        $this->redirect("/students/{$id}/edit");
                    }
                    return;
                }
            }
            
            // Basic validation
            $requiredFields = ['admission_no' => 'Admission number', 'first_name' => 'First name', 'last_name' => 'Last name', 'dob' => 'Date of birth', 'gender' => 'Gender', 'class_id' => 'Class', 'guardian_phone' => 'Guardian phone'];
            $missingFields = [];
            foreach ($requiredFields as $field => $label) {
                if (empty($data[$field])) {
                    $missingFields[] = $label;
                }
            }
            
            if ($data['student_category'] === 'international' && empty($data['student_category_details'])) {
                $missingFields[] = 'Category details';
            }
            
            if (!empty($missingFields)) {
                $errorMessage = 'The following fields are required: ' . implode(', ', $missingFields);
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => $errorMessage], 400);
                } else {
                    $this->flash('error', $errorMessage);
                    $this->redirect("/students/{$id}/edit");
                }
                return;
            }
            
            $studentModel = new Student();
            
            // Check if admission number already exists for another student
            $existingStudents = $studentModel->findByAdmissionNo($data['admission_no']);
            if (!empty($existingStudents)) {
                // Filter out the current student
                $otherStudents = array_filter($existingStudents, function($s) use ($id) {
                    return $s['id'] != $id;
                });
                
                if (!empty($otherStudents)) {
                    if ($this->isAjaxRequest()) {
                        $this->jsonResponse(['error' => 'A student with this admission number already exists'], 400);
                    } else {
                        $this->flash('error', 'A student with this admission number already exists');
                        $this->redirect("/students/{$id}/edit");
                    }
                    return;
                }
            }
            
            // Check if we have any data to update
            if (empty($data)) {
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'No data provided for update'], 400);
                } else {
                    $this->flash('error', 'No data provided for update');
                    $this->redirect("/students/{$id}/edit");
                }
                return;
            }
            
            // Update the student
            $result = $studentModel->update($id, $data);
            
            if ($result !== false) {
                // If class changed, auto-assign the new class fees
                if (isset($student['class_id']) && $student['class_id'] != $data['class_id']) {
                    $feeModel = new \App\Models\Fee();
                    $classFees = $feeModel->getFeesByClassId($data['class_id']);
                    
                    if (!empty($classFees)) {
                        $feeAssignmentModel = new \App\Models\FeeAssignment();
                        foreach ($classFees as $fee) {
                            $feeAssignmentModel->assignStudents($fee['id'], [$id]);
                        }
                    }
                }
                
                // Get current academic year for audit logging
                $academicYearModel = new AcademicYear();
                $currentAcademicYear = $academicYearModel->getCurrent();
                
                // Log audit trail with academic year and term
                AuditHelper::log(
                    $_SESSION['user']['id'],
                    'update',
                    'students',
                    $id,
                    'Updated student: ' . $data['first_name'] . ' ' . $data['last_name'],
                    null, // newValues
                    $currentAcademicYear ? $currentAcademicYear['id'] : null,
                    $currentAcademicYear ? $currentAcademicYear['term'] : null
                );
                
                // Create notification
                $notificationModel = new Notification();
                $notificationData = [
                    'user_id' => $_SESSION['user']['id'],
                    'message' => 'Updated student: ' . $data['first_name'] . ' ' . $data['last_name'],
                    'type' => 'student',
                    'is_read' => 0,
                    'related_id' => $id,
                    'related_type' => 'student'
                ];
                $notificationModel->create($notificationData);
                
                $message = $result > 0 ? 'Student updated successfully' : 'Student information saved (no changes detected)';
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['success' => true, 'message' => $message, 'student_id' => $id]);
                } else {
                    $this->flash('success', $message);
                    $this->redirect('/students/' . $id);
                }
            } else {
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'Failed to update student'], 500);
                } else {
                    $this->flash('error', 'Failed to update student');
                    $this->redirect("/students/{$id}/edit");
                }
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
        
        $studentModel = new Student();
        $student = $studentModel->find($id);
        
        if (!$student) {
            $this->flash('error', 'Student not found');
            $this->redirect('/students');
        }
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        // Delete the student
        $result = $studentModel->delete($id);
        
        if ($result) {
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'],
                'delete',
                'students',
                $id,
                'Deleted student: ' . $student['first_name'] . ' ' . $student['last_name'],
                null,
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            // Create notification
            $notificationModel = new Notification();
            $notificationData = [
                'user_id' => $_SESSION['user']['id'],
                'message' => 'Deleted student: ' . $student['first_name'] . ' ' . $student['last_name'],
                'type' => 'student',
                'is_read' => 0,
                'related_id' => $id,
                'related_type' => 'student'
            ];
            $notificationModel->create($notificationData);
        }
        
        $this->flash('success', 'Student deleted successfully');
        $this->redirect('/students');
    }
    
    public function export()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $studentModel = new Student();
        
        // Get search and filter parameters (including date range filters)
        $searchTerm = $this->get('search', '');
        $filters = [
            'admission_no' => $this->get('admission_no', ''),
            'name' => $this->get('name', ''),
            'class_id' => $this->get('class_id', ''),
            'category' => $this->get('category', ''),
            'guardian' => $this->get('guardian', ''),
            'date_mode' => $this->get('date_mode', ''), // Include date mode filter
            'date_from' => $this->get('date_from', ''), // Include date from filter
            'date_to' => $this->get('date_to', '')       // Include date to filter
        ];
        
        // Get all students based on search and filters (no pagination)
        $students = $studentModel->searchWithClass($searchTerm, $filters);
        
        // Check if this is a print request
        $print = $this->get('print', false);
        
        if ($print) {
            // For print, generate HTML
            $this->generatePrintView($students, $filters, $searchTerm);
        } else {
            // For export, generate CSV
            $this->generateCSVExport($students);
        }
    }
    
    private function generateCSVExport($students)
    {
        // Set headers for CSV export
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="students_export_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, [
            'Admission No', 
            'First Name', 
            'Last Name', 
            'Date of Birth', 
            'Gender', 
            'Class', 
            'Category', 
            'Guardian Name', 
            'Guardian Phone', 
            'Address', 
            'Admission Date'
        ]);
        
        // Add student data
        foreach ($students as $student) {
            fputcsv($output, [
                $student['admission_no'],
                $student['first_name'],
                $student['last_name'],
                $student['dob'] ?? '',
                $student['gender'] ?? '',
                $student['class_name'] ?? '',
                $student['student_category'] ?? '',
                $student['guardian_name'] ?? '',
                $student['guardian_phone'] ?? '',
                $student['address'] ?? '',
                $student['admission_date'] ?? ''
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    private function generatePrintView($students, $filters, $searchTerm)
    {
        // Get watermark settings
        $settingModel = new \App\Models\Setting();
        $watermarkSettings = $settingModel->getWatermarkSettings();
        
        // Define student category labels
        $studentCategoryLabels = [
            'regular_day' => 'Day',
            'regular_boarding' => 'Boarding',
            'international' => 'International'
        ];
        
        // Start output buffering
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Students Report</title>
            <style>
                body {
                    font-family: Arial, sans-serif;
                    margin: 20px;
                }
                .header {
                    text-align: center;
                    margin-bottom: 30px;
                    border-bottom: 2px solid #333;
                    padding-bottom: 10px;
                }
                .report-title {
                    font-size: 24px;
                    font-weight: bold;
                    color: #333;
                }
                .report-date {
                    font-size: 14px;
                    color: #666;
                    margin-top: 5px;
                }
                .filters {
                    background-color: #f5f5f5;
                    padding: 10px;
                    margin-bottom: 20px;
                    border-radius: 5px;
                }
                .filter-title {
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .filter-item {
                    display: inline-block;
                    margin-right: 15px;
                    font-size: 12px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                }
                th, td {
                    border: 1px solid #ddd;
                    padding: 8px;
                    text-align: left;
                }
                th {
                    background-color: #f2f2f2;
                    font-weight: bold;
                }
                tr:nth-child(even) {
                    background-color: #f9f9f9;
                }
                .watermark {
                    position: fixed;
                    opacity: <?= (100 - $watermarkSettings['transparency']) / 100 ?>;
                    z-index: -1;
                    pointer-events: none;
                }
                .footer {
                    margin-top: 30px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                }
                @media print {
                    .no-print { display: none; }
                    body { margin: 0; padding: 0; }
                    .watermark { opacity: 0.1 !important; }
                }
            </style>
            <script>
                window.onload = function() {
                    window.print();
                    // Optional: window.close(); // Uncomment to close after printing if desired, but often better to let user decide
                }
            </script>
        </head>
        <body>
            <?php if ($watermarkSettings['type'] !== 'none'): ?>
                <div class="watermark" style="top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 48px; color: #ccc; text-align: center;">
                    <?php if ($watermarkSettings['type'] === 'name' || $watermarkSettings['type'] === 'both'): ?>
                        <?php 
                        $settings = $settingModel->getSettings();
                        echo htmlspecialchars($settings['school_name'] ?? 'School');
                        ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="header">
                <div class="report-title">Students Report</div>
                <div class="report-date">Generated on <?= date('F j, Y') ?></div>
            </div>
            
            <?php if (!empty(array_filter($filters)) || !empty($searchTerm)): ?>
                <div class="filters">
                    <div class="filter-title">Applied Filters:</div>
                    <?php if (!empty($searchTerm)): ?>
                        <div class="filter-item"><strong>Search:</strong> <?= htmlspecialchars($searchTerm) ?></div>
                    <?php endif; ?>
                    <?php foreach ($filters as $key => $value): ?>
                        <?php if (!empty($value)): ?>
                            <div class="filter-item"><strong><?= ucfirst(str_replace('_', ' ', $key)) ?>:</strong> <?= htmlspecialchars($value) ?></div>
                        <?php endif; ?>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <table>
                <thead>
                    <tr>
                        <th>Admission No</th>
                        <th>Name</th>
                        <th>Class</th>
                        <th>Category</th>
                        <th>Guardian</th>
                        <th>Admission Date</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($students)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center;">No students found.</td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($students as $student): ?>
                            <tr>
                                <td><?= htmlspecialchars($student['admission_no']) ?></td>
                                <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                <td><?= htmlspecialchars($student['class_name'] ?? 'N/A') ?></td>
                                <td><?= htmlspecialchars($studentCategoryLabels[$student['student_category'] ?? 'regular_day']) ?></td>
                                <td><?= htmlspecialchars($student['guardian_name'] ?? 'N/A') ?></td>
                                <td><?= !empty($student['admission_date']) ? htmlspecialchars(date('M j, Y', strtotime($student['admission_date']))) : 'N/A' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
            
            <div class="footer">
                Total Students: <?= count($students) ?> | 
                Report generated by: <?= $_SESSION['user']['username'] ?? 'System' ?>
            </div>
            
            <script>
                window.onload = function() {
                    window.print();
                }
            </script>
        </body>
        </html>
        <?php
        $html = ob_get_clean();
        
        // Set content type for HTML
        header('Content-Type: text/html');
        header('Content-Disposition: inline; filename="students_report_' . date('Y-m-d') . '.html"');
        
        echo $html;
        exit;
    }
    
    /**
     * Get all students with their fee assignments (AJAX endpoint)
     */
    public function getAllWithFees()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
            return;
        }
        
        // Check if this is an AJAX request
        if (!$this->isAjaxRequest()) {
            $this->jsonResponse(['error' => 'Invalid request'], 400);
            return;
        }
        
        $studentModel = new Student();
        $students = $studentModel->getAllWithClassAndFeeAssignments();
        
        $this->jsonResponse([
            'success' => true,
            'students' => $students
        ]);
    }
    
    private function handleProfilePictureUpload($file)
    {
        // Define upload directory (in storage directory)
        $uploadDir = ROOT_PATH . '/storage/uploads/';
        
        // Create directory if it doesn't exist
        if (!is_dir($uploadDir)) {
            mkdir($uploadDir, 0755, true);
        }
        
        // Check if file is an image
        $imageInfo = getimagesize($file['tmp_name']);
        if ($imageInfo === false) {
            return [
                'success' => false,
                'message' => 'Invalid image file'
            ];
        }
        
        // Check file size (max 2MB)
        if ($file['size'] > 2 * 1024 * 1024) {
            return [
                'success' => false,
                'message' => 'File size exceeds 2MB limit'
            ];
        }
        
        // Generate unique filename
        $fileExtension = pathinfo($file['name'], PATHINFO_EXTENSION);
        $filename = 'student_' . time() . '_' . uniqid() . '.' . $fileExtension;
        
        // Move uploaded file
        $destination = $uploadDir . $filename;
        if (move_uploaded_file($file['tmp_name'], $destination)) {
            return [
                'success' => true,
                'filename' => $filename
            ];
        } else {
            return [
                'success' => false,
                'message' => 'Failed to upload file'
            ];
        }
    }
}