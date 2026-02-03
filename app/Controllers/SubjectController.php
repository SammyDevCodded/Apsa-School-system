<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\Staff;
use App\Core\Database;
use App\Models\AcademicYear;
use App\Helpers\AuditHelper;

class SubjectController extends Controller
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
        
        $subjectModel = new Subject();
        $result = $subjectModel->getAllWithClassPaginated($search, $page, $perPage);
        
        // Handle AJAX request for modal content
        if ($this->isAjaxRequest()) {
            // For AJAX requests, we need to render the view and return the HTML
            ob_start();
            extract([
                'subjects' => $result['data'],
                'pagination' => $result['pagination'],
                'search' => $search,
                'perPage' => $perPage
            ]);
            include RESOURCES_PATH . '/views/subjects/index.php';
            $content = ob_get_clean();
            $this->jsonResponse([
                'html' => $content,
                'pagination' => $result['pagination'],
                'search' => $search,
                'perPage' => $perPage
            ]);
            return;
        }
        
        $this->view('subjects/index', [
            'subjects' => $result['data'],
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
        
        $subjectModel = new Subject();
        $subject = $subjectModel->getSubjectDetails($id);
        
        if (!$subject) {
            // Handle AJAX request differently
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Subject not found'], 404);
                return;
            }
            
            $this->flash('error', 'Subject not found');
            $this->redirect('/subjects');
        }
        
        $classModel = new ClassModel();
        $class = $classModel->find($subject['class_id']);
        
        // Handle AJAX request for modal content
        if ($this->isAjaxRequest()) {
            // For AJAX requests, we need to render the view and return the HTML
            ob_start();
            extract([
                'subject' => $subject,
                'class' => $class
            ]);
            include RESOURCES_PATH . '/views/subjects/show.php';
            $content = ob_get_clean();
            $this->jsonResponse(['html' => $content]);
            return;
        }
        
        $this->view('subjects/show', [
            'subject' => $subject,
            'class' => $class
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
        
        // Get all staff members for teacher assignment
        $staffModel = new Staff();
        $staff = $staffModel->all();
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        // Handle AJAX request for modal content
        if ($this->isAjaxRequest()) {
            // For AJAX requests, we need to render the view and return the HTML
            ob_start();
            extract([
                'classes' => $classes,
                'staff' => $staff
            ]);
            include RESOURCES_PATH . '/views/subjects/create.php';
            $content = ob_get_clean();
            $this->jsonResponse(['html' => $content]);
            return;
        }
        
        $this->view('subjects/create', [
            'classes' => $classes,
            'staff' => $staff,
            'currentAcademicYear' => $currentAcademicYear
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
                'name' => $this->post('name'),
                'code' => $this->post('code'),
                'class_id' => $this->post('class_id'),
                'description' => $this->post('description')
            ];
            
            // Get selected staff members
            $selectedStaff = $this->post('staff_ids', []);
            
            // Basic validation
            if (empty($data['name']) || empty($data['code']) || empty($data['class_id'])) {
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'Subject name, code, and class are required'], 400);
                    return;
                }
                
                $this->flash('error', 'Subject name, code, and class are required');
                $this->redirect('/subjects/create');
            }
            
            $subjectModel = new Subject();
            
            // Check if subject code already exists
            $existingSubject = $subjectModel->findByCode($data['code']);
            if ($existingSubject) {
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'A subject with this code already exists'], 400);
                    return;
                }
                
                $this->flash('error', 'A subject with this code already exists');
                $this->redirect('/subjects/create');
            }
            
            // Create the subject
            $subjectId = $subjectModel->create($data);
            
            if ($subjectId) {
                // Assign staff members to this subject
                if (!empty($selectedStaff)) {
                    $db = new Database();
                    foreach ($selectedStaff as $staffId) {
                        // Insert into staff_subjects table
                        $sql = "INSERT INTO staff_subjects (staff_id, subject_id) VALUES (:staff_id, :subject_id)";
                        $db->execute($sql, [
                            'staff_id' => $staffId,
                            'subject_id' => $subjectId
                        ]);
                    }
                }
                
                // Automatically assign subject to the selected class
                $this->assignSubjectToClass($subjectId, $data['class_id']);
                
                // Log audit trail with academic year and term
                AuditHelper::log(
                    $_SESSION['user']['id'],
                    'create',
                    'subjects',
                    $subjectId,
                    null,
                    $data,
                    $currentAcademicYear ? $currentAcademicYear['id'] : null,
                    $currentAcademicYear ? $currentAcademicYear['term'] : null
                );
                
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['success' => true, 'message' => 'Subject created successfully']);
                    return;
                }
                
                $this->flash('success', 'Subject created successfully');
                $this->redirect('/subjects');
            } else {
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'Failed to create subject'], 500);
                    return;
                }
                
                $this->flash('error', 'Failed to create subject');
                $this->redirect('/subjects/create');
            }
        } else {
            $this->create();
        }
    }
    
    // Helper method to assign subject to class
    private function assignSubjectToClass($subjectId, $classId)
    {
        try {
            $db = new Database();
            $sql = "INSERT INTO class_subject_assignments (class_id, subject_id, student_id) VALUES (:class_id, :subject_id, NULL)";
            $db->execute($sql, [
                'class_id' => $classId,
                'subject_id' => $subjectId
            ]);
        } catch (\Exception $e) {
            // Log error but don't stop the process
            error_log("Failed to assign subject to class: " . $e->getMessage());
        }
    }

    public function edit($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $subjectModel = new Subject();
        $subject = $subjectModel->find($id);
        
        if (!$subject) {
            // Handle AJAX request differently
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Subject not found'], 404);
                return;
            }
            
            $this->flash('error', 'Subject not found');
            $this->redirect('/subjects');
        }
        
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        // Get all staff members for teacher assignment
        $staffModel = new Staff();
        $staff = $staffModel->all();
        
        // Get currently assigned staff for this subject
        $assignedStaff = $subjectModel->getStaff($id);
        $assignedStaffIds = array_column($assignedStaff, 'id');
        
        // Get current academic year
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        // Handle AJAX request for modal content
        if ($this->isAjaxRequest()) {
            // For AJAX requests, we need to render the view and return the HTML
            ob_start();
            extract([
                'subject' => $subject,
                'classes' => $classes,
                'staff' => $staff,
                'assignedStaffIds' => $assignedStaffIds
            ]);
            include RESOURCES_PATH . '/views/subjects/edit.php';
            $content = ob_get_clean();
            $this->jsonResponse(['html' => $content]);
            return;
        }
        
        $this->view('subjects/edit', [
            'subject' => $subject,
            'classes' => $classes,
            'staff' => $staff,
            'assignedStaffIds' => $assignedStaffIds,
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
            $subjectModel = new Subject();
            $subject = $subjectModel->find($id);
            
            if (!$subject) {
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'Subject not found'], 404);
                    return;
                }
                
                $this->flash('error', 'Subject not found');
                $this->redirect('/subjects');
            }
            
            $data = [
                'name' => $this->post('name'),
                'code' => $this->post('code'),
                'class_id' => $this->post('class_id'),
                'description' => $this->post('description')
            ];
            
            // Get selected staff members
            $selectedStaff = $this->post('staff_ids', []);
            
            // Basic validation
            if (empty($data['name']) || empty($data['code']) || empty($data['class_id'])) {
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'Subject name, code, and class are required'], 400);
                    return;
                }
                
                $this->flash('error', 'Subject name, code, and class are required');
                $this->redirect("/subjects/{$id}/edit");
            }
            
            // Update the subject
            $result = $subjectModel->update($id, $data);
            
            if ($result) {
                // Update staff assignments for this subject
                $db = new Database();
                // First, remove all existing assignments
                $deleteSql = "DELETE FROM staff_subjects WHERE subject_id = :subject_id";
                $db->execute($deleteSql, ['subject_id' => $id]);
                
                // Then, add new assignments
                if (!empty($selectedStaff)) {
                    foreach ($selectedStaff as $staffId) {
                        $sql = "INSERT INTO staff_subjects (staff_id, subject_id) VALUES (:staff_id, :subject_id)";
                        $db->execute($sql, [
                            'staff_id' => $staffId,
                            'subject_id' => $id
                        ]);
                    }
                }
                
                // Get current academic year for audit logging
                $academicYearModel = new AcademicYear();
                $currentAcademicYear = $academicYearModel->getCurrent();
                
                // Log audit trail with academic year and term
                AuditHelper::log(
                    $_SESSION['user']['id'],
                    'update',
                    'subjects',
                    $id,
                    $subject, // old values
                    $data, // new values
                    $currentAcademicYear ? $currentAcademicYear['id'] : null,
                    $currentAcademicYear ? $currentAcademicYear['term'] : null
                );
                
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['success' => true, 'message' => 'Subject updated successfully']);
                    return;
                }
                
                $this->flash('success', 'Subject updated successfully');
                $this->redirect('/subjects');
            } else {
                // Handle AJAX request differently
                if ($this->isAjaxRequest()) {
                    $this->jsonResponse(['error' => 'Failed to update subject'], 500);
                    return;
                }
                
                $this->flash('error', 'Failed to update subject');
                $this->redirect("/subjects/{$id}/edit");
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
        
        $subjectModel = new Subject();
        $subject = $subjectModel->find($id);
        
        if (!$subject) {
            $this->flash('error', 'Subject not found');
            $this->redirect('/subjects');
        }
        
        // Get current academic year for audit logging
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrent();
        
        $result = $subjectModel->delete($id);
        
        if ($result) {
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'],
                'delete',
                'subjects',
                $id,
                $subject, // old values
                null, // new values
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            $this->flash('success', 'Subject deleted successfully');
        } else {
            $this->flash('error', 'Failed to delete subject');
        }
        
        $this->redirect('/subjects');
    }
}