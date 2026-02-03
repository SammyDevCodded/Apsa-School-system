<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Staff;
use App\Models\Subject;
use App\Helpers\IdGeneratorHelper;
use App\Core\Database;
use App\Models\AcademicYear;
use App\Helpers\AuditHelper;

class StaffController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Get search and pagination parameters
        $search = $this->get('search', '');
        $page = (int) $this->get('page', 1);
        $perPage = (int) $this->get('per_page', 10);
        
        // Validate perPage value
        if (!in_array($perPage, [10, 25, 50, 100])) {
            $perPage = 10;
        }
        
        // Check if this is an AJAX request for modal content
        if ($this->isAjaxRequest()) {
            // Return JSON response with paginated staff data
            $staffModel = new Staff();
            $result = $staffModel->getAllWithUserPaginated($search, $page, $perPage);
            $this->jsonResponse([
                'staff' => $result['data'],
                'pagination' => $result['pagination'],
                'search' => $search,
                'perPage' => $perPage
            ]);
            return;
        }
        
        $staffModel = new Staff();
        $result = $staffModel->getAllWithUserPaginated($search, $page, $perPage);
        
        $this->view('staff/index', [
            'staff' => $result['data'],
            'pagination' => $result['pagination'],
            'search' => $search,
            'perPage' => $perPage
        ]);
    }
    
    public function show($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $staffModel = new Staff();
        $staff = $staffModel->find($id);
        
        if (!$staff) {
            // Handle AJAX request differently
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Staff member not found'], 404);
                return;
            }
            
            $this->flash('error', 'Staff member not found');
            $this->redirect('/staff');
        }
        
        // Get subjects taught by this staff member
        $subjects = $staffModel->getSubjects($id);
        
        // Get number of students handled by this teacher across all subjects
        $studentCount = 0;
        if (!empty($subjects)) {
            $subjectIds = array_column($subjects, 'id');
            $subjectIdsStr = implode(',', $subjectIds);
            
            // Get classes that have these subjects
            $db = new Database();
            $sql = "SELECT DISTINCT c.id FROM classes c 
                    JOIN subjects s ON c.id = s.class_id 
                    WHERE s.id IN ({$subjectIdsStr})";
            $classResults = $db->fetchAll($sql);
            
            if (!empty($classResults)) {
                $classIds = array_column($classResults, 'id');
                $classIdsStr = implode(',', $classIds);
                
                // Count students in these classes
                $sql = "SELECT COUNT(*) as count FROM students WHERE class_id IN ({$classIdsStr})";
                $result = $db->fetchOne($sql);
                $studentCount = $result ? $result['count'] : 0;
            }
        }
        
        // Handle AJAX request for modal content
        if ($this->isAjaxRequest()) {
            // For AJAX requests, we need to render the view and return the HTML
            ob_start();
            extract([
                'staff' => $staff,
                'subjects' => $subjects,
                'studentCount' => $studentCount
            ]);
            include RESOURCES_PATH . '/views/staff/show.php';
            $content = ob_get_clean();
            $this->jsonResponse(['html' => $content]);
            return;
        }
        
        $this->view('staff/show', [
            'staff' => $staff,
            'subjects' => $subjects,
            'studentCount' => $studentCount
        ]);
    }
    
    public function create()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Generate a default employee ID using the helper
        $defaultEmployeeId = IdGeneratorHelper::generateEmployeeId();
        
        // Get all subjects for the checkboxes
        $subjectModel = new Subject();
        $subjects = $subjectModel->all();
        
        // Handle AJAX request for modal content
        if ($this->isAjaxRequest()) {
            // For AJAX requests, we need to render the view and return the HTML
            ob_start();
            extract([
                'defaultEmployeeId' => $defaultEmployeeId,
                'subjects' => $subjects
            ]);
            include RESOURCES_PATH . '/views/staff/create.php';
            $content = ob_get_clean();
            $this->jsonResponse(['html' => $content]);
            return;
        }
        
        $this->view('staff/create', [
            'defaultEmployeeId' => $defaultEmployeeId,
            'subjects' => $subjects
        ]);
    }
    
    public function store()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        if ($this->requestMethod() === 'POST') {
            // Get current academic year
            $academicYearModel = new AcademicYear();
            $currentAcademicYear = $academicYearModel->getCurrent();
            
            $data = [
                'employee_id' => $this->post('employee_id'),
                'first_name' => $this->post('first_name'),
                'last_name' => $this->post('last_name'),
                'position' => $this->post('position'),
                'department' => $this->post('department'),
                'email' => $this->post('email'),
                'phone' => $this->post('phone'),
                'hire_date' => $this->post('hire_date'),
                'salary' => $this->post('salary'),
                'status' => $this->post('status') ?? 'active'
            ];
            
            // Get selected subjects
            $selectedSubjects = $this->post('subject_ids', []);
            
            // Basic validation
            if (empty($data['employee_id']) || empty($data['first_name']) || empty($data['last_name'])) {
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'Employee ID, first name, and last name are required'], 400);
                    return;
                }
                
                $this->flash('error', 'Employee ID, first name, and last name are required');
                $this->redirect('/staff/create');
            }
            
            $staffModel = new Staff();
            
            // Check if employee ID already exists
            $existingStaff = $staffModel->findByEmployeeId($data['employee_id']);
            if ($existingStaff) {
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'A staff member with this employee ID already exists'], 400);
                    return;
                }
                
                $this->flash('error', 'A staff member with this employee ID already exists');
                $this->redirect('/staff/create');
            }
            
            // Create the staff member
            $staffId = $staffModel->create($data);
            
            if ($staffId) {
                // Assign subjects to this staff member
                if (!empty($selectedSubjects)) {
                    $staffModel->assignSubjects($staffId, $selectedSubjects);
                }
                
                // Log audit trail with academic year and term
                AuditHelper::log(
                    $_SESSION['user']['id'],
                    'create',
                    'staff',
                    $staffId,
                    null,
                    $data,
                    $currentAcademicYear ? $currentAcademicYear['id'] : null,
                    $currentAcademicYear ? $currentAcademicYear['term'] : null
                );
                
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['success' => true, 'message' => 'Staff member created successfully']);
                    return;
                }
                
                $this->flash('success', 'Staff member created successfully');
                $this->redirect('/staff');
            } else {
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'Failed to create staff member'], 500);
                    return;
                }
                
                $this->flash('error', 'Failed to create staff member');
                $this->redirect('/staff/create');
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
        
        $staffModel = new Staff();
        $staff = $staffModel->find($id);
        
        if (!$staff) {
            // Handle AJAX request differently
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Staff member not found'], 404);
                return;
            }
            
            $this->flash('error', 'Staff member not found');
            $this->redirect('/staff');
        }
        
        // Get all subjects for the checkboxes
        $subjectModel = new Subject();
        $subjects = $subjectModel->all();
        
        // Get currently assigned subjects for this staff member
        $assignedSubjects = $staffModel->getSubjects($id);
        $assignedSubjectIds = array_column($assignedSubjects, 'id');
        
        // Handle AJAX request for modal content
        if ($this->isAjaxRequest()) {
            // For AJAX requests, we need to render the view and return the HTML
            ob_start();
            extract([
                'staff' => $staff,
                'subjects' => $subjects,
                'assignedSubjectIds' => $assignedSubjectIds
            ]);
            include RESOURCES_PATH . '/views/staff/edit.php';
            $content = ob_get_clean();
            $this->jsonResponse(['html' => $content]);
            return;
        }
        
        $this->view('staff/edit', [
            'staff' => $staff,
            'subjects' => $subjects,
            'assignedSubjectIds' => $assignedSubjectIds
        ]);
    }
    
    public function update($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        if ($this->requestMethod() === 'POST' || $this->requestMethod() === 'PUT') {
            $staffModel = new Staff();
            $staff = $staffModel->find($id);
            
            if (!$staff) {
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'Staff member not found'], 404);
                    return;
                }
                
                $this->flash('error', 'Staff member not found');
                $this->redirect('/staff');
            }
            
            $data = [
                'employee_id' => $this->post('employee_id'),
                'first_name' => $this->post('first_name'),
                'last_name' => $this->post('last_name'),
                'position' => $this->post('position'),
                'department' => $this->post('department'),
                'email' => $this->post('email'),
                'phone' => $this->post('phone'),
                'hire_date' => $this->post('hire_date'),
                'salary' => $this->post('salary'),
                'status' => $this->post('status') ?? 'active'
            ];
            
            // Get selected subjects
            $selectedSubjects = $this->post('subject_ids', []);
            
            // Basic validation
            if (empty($data['employee_id']) || empty($data['first_name']) || empty($data['last_name'])) {
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'Employee ID, first name, and last name are required'], 400);
                    return;
                }
                
                $this->flash('error', 'Employee ID, first name, and last name are required');
                $this->redirect("/staff/{$id}/edit");
            }
            
            // Update the staff member
            $result = $staffModel->update($id, $data);
            
            if ($result) {
                // Update subject assignments for this staff member
                $staffModel->assignSubjects($id, $selectedSubjects);
                
                // Get current academic year for audit logging
                $academicYearModel = new AcademicYear();
                $currentAcademicYear = $academicYearModel->getCurrent();
                
                // Log audit trail with academic year and term
                AuditHelper::log(
                    $_SESSION['user']['id'],
                    'update',
                    'staff',
                    $id,
                    $staff, // old values
                    $data, // new values
                    $currentAcademicYear ? $currentAcademicYear['id'] : null,
                    $currentAcademicYear ? $currentAcademicYear['term'] : null
                );
                
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['success' => true, 'message' => 'Staff member updated successfully']);
                    return;
                }
                
                $this->flash('success', 'Staff member updated successfully');
                $this->redirect('/staff');
            } else {
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'Failed to update staff member'], 500);
                    return;
                }
                
                $this->flash('error', 'Failed to update staff member');
                $this->redirect("/staff/{$id}/edit");
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
        
        $staffModel = new Staff();
        $staff = $staffModel->find($id);
        
        if (!$staff) {
            $this->flash('error', 'Staff member not found');
            $this->redirect('/staff');
        }
        
        // Get current academic year for audit logging
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        $result = $staffModel->delete($id);
        
        if ($result) {
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'],
                'delete',
                'staff',
                $id,
                $staff, // old values
                null, // new values
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            $this->flash('success', 'Staff member deleted successfully');
        } else {
            $this->flash('error', 'Failed to delete staff member');
        }
        
        $this->redirect('/staff');
    }
}