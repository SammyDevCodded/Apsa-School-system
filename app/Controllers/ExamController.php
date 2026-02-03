<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Exam;
use App\Models\ClassModel;
use App\Models\AcademicYear;
use App\Models\GradingScale;
use App\Helpers\AuditHelper;

class ExamController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $examModel = new Exam();
        $exams = $examModel->getAllWithClasses();
        
        $this->view('exams/index', [
            'exams' => $exams
        ]);
    }
    
    public function show($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Check if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        $examModel = new Exam();
        $exam = $examModel->find($id);
        
        if (!$exam) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Exam not found']);
                return;
            }
            $this->flash('error', 'Exam not found');
            $this->redirect('/exams');
        }
        
        $classModel = new ClassModel();
        $class = $classModel->find($exam['class_id']);
        
        // Get all assigned classes for this exam
        $assignedClasses = $examModel->getClasses($id);
        
        if ($isAjax) {
            // For AJAX requests, only render the view content
            $this->view('exams/show', [
                'exam' => $exam,
                'class' => $class,
                'assignedClasses' => $assignedClasses
            ], false); // false means don't include layout
        } else {
            // For regular requests, render the full page
            $this->view('exams/show', [
                'exam' => $exam,
                'class' => $class,
                'assignedClasses' => $assignedClasses
            ]);
        }
    }
    
    public function create()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        // Get current academic year with term
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrentAcademicYearWithTerm();
        
        // Get all grading scales
        $gradingScaleModel = new GradingScale();
        $gradingScales = $gradingScaleModel->all();
        
        $this->view('exams/create', [
            'classes' => $classes,
            'currentAcademicYear' => $currentAcademicYear,
            'gradingScales' => $gradingScales
        ]);
    }
    
    public function store()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        if ($this->requestMethod() === 'POST' || $this->requestMethod() === 'PUT') {
            // Get current academic year with term
            $academicYearModel = new AcademicYear();
            $currentAcademicYear = $academicYearModel->getCurrentAcademicYearWithTerm();
            
            // Use selected academic year or default to current
            $academicYearId = $this->post('academic_year_id') ?: ($currentAcademicYear ? $currentAcademicYear['id'] : null);
            
            // Handle term selection - use custom term if provided, otherwise use selected term
            $term = $this->post('custom_term');
            if (empty($term)) {
                $term = $this->post('term') ?: ($currentAcademicYear && !empty($currentAcademicYear['term']) ? $currentAcademicYear['term'] : '1st Term');
            }
            
            // Get selected class IDs (multiple classes)
            $classIds = $this->post('class_ids', []);
            
            // For backward compatibility, we still need a primary class_id
            $primaryClassId = !empty($classIds) ? $classIds[0] : null;
            
            // Get grading scale ID
            $gradingScaleId = $this->post('grading_scale_id');
            
            // Get classwork fields
            $hasClasswork = $this->post('has_classwork') ? 1 : 0;
            $classworkPercentage = $hasClasswork ? $this->post('classwork_percentage') : 0;
            
            $data = [
                'name' => $this->post('name'),
                'term' => $term,
                'class_id' => $primaryClassId, // Keep for backward compatibility
                'academic_year_id' => $academicYearId,
                'date' => $this->post('date'),
                'description' => $this->post('description'),
                'grading_scale_id' => $gradingScaleId,
                'has_classwork' => $hasClasswork,
                'classwork_percentage' => $classworkPercentage
            ];
            
            // Basic validation
            if (empty($data['name']) || empty($data['term']) || empty($data['class_id']) || empty($data['date'])) {
                $this->flash('error', 'Exam name, term, class, and date are required');
                $this->redirect('/exams/create');
            }
            
            // Validate date
            if (!strtotime($data['date'])) {
                $this->flash('error', 'Please enter a valid date');
                $this->redirect('/exams/create');
            }
            
            // Validate that at least one class is selected
            if (empty($classIds)) {
                $this->flash('error', 'Please select at least one class');
                $this->redirect('/exams/create');
            }
            
            // Validate classwork percentage if classwork is enabled
            if ($hasClasswork) {
                if (!is_numeric($classworkPercentage) || $classworkPercentage < 0 || $classworkPercentage > 100) {
                    $this->flash('error', 'Classwork percentage must be between 0 and 100');
                    $this->redirect('/exams/create');
                }
            }
            
            $examModel = new Exam();
            
            // Create the exam
            $examId = $examModel->create($data);
            
            if ($examId) {
                // Assign classes to the exam
                $examModel->assignClasses($examId, $classIds);
                
                // Log audit trail with academic year and term
                AuditHelper::log(
                    $_SESSION['user']['id'],
                    'create',
                    'exams',
                    $examId,
                    null,
                    $data,
                    $academicYearId,
                    $term
                );
                
                $this->flash('success', 'Exam created successfully');
                $this->redirect('/exams');
            } else {
                $this->flash('error', 'Failed to create exam');
                $this->redirect('/exams/create');
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
        
        // Check if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        $examModel = new Exam();
        $exam = $examModel->find($id);
        
        if (!$exam) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Exam not found']);
                return;
            }
            $this->flash('error', 'Exam not found');
            $this->redirect('/exams');
        }
        
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        // Get current academic year with term
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrentAcademicYearWithTerm();
        
        // Get assigned classes for this exam
        $assignedClassIds = [];
        $assignedClasses = $examModel->getClasses($id);
        foreach ($assignedClasses as $assignedClass) {
            $assignedClassIds[] = $assignedClass['id'];
        }
        
        // Get all grading scales
        $gradingScaleModel = new GradingScale();
        $gradingScales = $gradingScaleModel->all();
        
        if ($isAjax) {
            // For AJAX requests, only render the edit form content
            $this->view('exams/edit', [
                'exam' => $exam,
                'classes' => $classes,
                'currentAcademicYear' => $currentAcademicYear,
                'assignedClassIds' => $assignedClassIds,
                'gradingScales' => $gradingScales
            ], false); // false means don't include layout
        } else {
            // For regular requests, render the full page
            $this->view('exams/edit', [
                'exam' => $exam,
                'classes' => $classes,
                'currentAcademicYear' => $currentAcademicYear,
                'assignedClassIds' => $assignedClassIds,
                'gradingScales' => $gradingScales
            ]);
        }
    }
    
    public function update($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Check if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        if ($this->requestMethod() === 'POST' || $this->requestMethod() === 'PUT') {
            $examModel = new Exam();
            $exam = $examModel->find($id);
            
            if (!$exam) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Exam not found']);
                    return;
                }
                $this->flash('error', 'Exam not found');
                $this->redirect('/exams');
            }
            
            // Get current academic year with term
            $academicYearModel = new AcademicYear();
            $currentAcademicYear = $academicYearModel->getCurrentAcademicYearWithTerm();
            
            // Use selected academic year or default to current
            $academicYearId = $this->post('academic_year_id') ?: ($currentAcademicYear ? $currentAcademicYear['id'] : null);
            
            // Handle term selection - use custom term if provided, otherwise use selected term
            $term = $this->post('custom_term');
            if (empty($term)) {
                $term = $this->post('term') ?: ($currentAcademicYear && !empty($currentAcademicYear['term']) ? $currentAcademicYear['term'] : '1st Term');
            }
            
            // Get selected class IDs (multiple classes)
            $classIds = $this->post('class_ids', []);
            
            // For backward compatibility, we still need a primary class_id
            $primaryClassId = !empty($classIds) ? $classIds[0] : null;
            
            // Get grading scale ID
            $gradingScaleId = $this->post('grading_scale_id');
            
            // Get classwork fields
            $hasClasswork = $this->post('has_classwork') ? 1 : 0;
            $classworkPercentage = $hasClasswork ? $this->post('classwork_percentage') : 0;
            
            $data = [
                'name' => $this->post('name'),
                'term' => $term,
                'class_id' => $primaryClassId, // Keep for backward compatibility
                'academic_year_id' => $academicYearId,
                'date' => $this->post('date'),
                'description' => $this->post('description'),
                'grading_scale_id' => $gradingScaleId,
                'has_classwork' => $hasClasswork,
                'classwork_percentage' => $classworkPercentage
            ];
            
            // Basic validation
            if (empty($data['name']) || empty($data['term']) || empty($data['class_id']) || empty($data['date'])) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Exam name, term, class, and date are required']);
                    return;
                }
                $this->flash('error', 'Exam name, term, class, and date are required');
                $this->redirect("/exams/{$id}/edit");
            }
            
            // Validate date
            if (!strtotime($data['date'])) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Please enter a valid date']);
                    return;
                }
                $this->flash('error', 'Please enter a valid date');
                $this->redirect("/exams/{$id}/edit");
            }
            
            // Validate that at least one class is selected
            if (empty($classIds)) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Please select at least one class']);
                    return;
                }
                $this->flash('error', 'Please select at least one class');
                $this->redirect("/exams/{$id}/edit");
            }
            
            // Validate classwork percentage if classwork is enabled
            if ($hasClasswork) {
                if (!is_numeric($classworkPercentage) || $classworkPercentage < 0 || $classworkPercentage > 100) {
                    if ($isAjax) {
                        header('Content-Type: application/json');
                        echo json_encode(['success' => false, 'message' => 'Classwork percentage must be between 0 and 100']);
                        return;
                    }
                    $this->flash('error', 'Classwork percentage must be between 0 and 100');
                    $this->redirect("/exams/{$id}/edit");
                }
            }
            
            // Update the exam
            $result = $examModel->update($id, $data);
            
            if ($result) {
                // Update class assignments
                $examModel->assignClasses($id, $classIds);
                
                // Log audit trail with academic year and term
                AuditHelper::log(
                    $_SESSION['user']['id'],
                    'update',
                    'exams',
                    $id,
                    $exam, // old values
                    $data, // new values
                    $academicYearId,
                    $term
                );
                
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Exam updated successfully']);
                    return;
                }
                $this->flash('success', 'Exam updated successfully');
                $this->redirect('/exams');
            } else {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Failed to update exam']);
                    return;
                }
                $this->flash('error', 'Failed to update exam');
                $this->redirect("/exams/{$id}/edit");
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
        
        $examModel = new Exam();
        $exam = $examModel->find($id);
        
        if (!$exam) {
            $this->flash('error', 'Exam not found');
            $this->redirect('/exams');
        }
        
        // Get current academic year with term for audit logging
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrentAcademicYearWithTerm();
        
        $result = $examModel->delete($id);
        
        if ($result) {
            // Log audit trail with academic year and term
            AuditHelper::log(
                $_SESSION['user']['id'],
                'delete',
                'exams',
                $id,
                $exam, // old values
                null, // new values
                $currentAcademicYear ? $currentAcademicYear['id'] : null,
                $currentAcademicYear ? $currentAcademicYear['term'] : null
            );
            
            $this->flash('success', 'Exam deleted successfully');
        } else {
            $this->flash('error', 'Failed to delete exam');
        }
        
        $this->redirect('/exams');
    }
    
    // New method to get classwork information for an exam
    public function getClassworkInfo($examId)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['has_classwork' => false]);
            return;
        }
        
        $examModel = new Exam();
        $exam = $examModel->find($examId);
        
        if (!$exam) {
            http_response_code(404);
            header('Content-Type: application/json');
            echo json_encode(['has_classwork' => false]);
            return;
        }
        
        header('Content-Type: application/json');
        echo json_encode([
            'has_classwork' => (bool) $exam['has_classwork'],
            'classwork_percentage' => $exam['classwork_percentage'] ?? 0
        ]);
    }
}