<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\ExamResult;
use App\Models\Exam;
use App\Models\Student;
use App\Models\Subject;
use App\Models\ClassModel;
use App\Models\GradingScale;
use App\Models\GradingRule;
use App\Models\AcademicYear;
use App\Helpers\AcademicYearHelper;

class ExamResultController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $resultModel = new ExamResult();
        $classModel = new ClassModel();
        $subjectModel = new Subject();
        
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
            'exam_name' => $this->get('exam_name', ''),
            'student_name' => $this->get('student_name', ''),
            'class_id' => $this->get('class_id', ''),
            'subject_id' => $this->get('subject_id', ''),
            'grade' => $this->get('grade', ''),
            'date_mode' => $this->get('date_mode', ''), // New date mode filter
            'date_from' => $this->get('date_from', ''), // New date from filter
            'date_to' => $this->get('date_to', '')       // New date to filter
        ];
        
        // Get exam results based on search and filters with pagination
        $resultsData = $resultModel->searchWithDetailsPaginated($searchTerm, $filters, $page, $perPage);
        
        // Get all classes and subjects for filter dropdowns
        $classes = $classModel->getAll();
        $subjects = $subjectModel->all();
        
        // Get distinct grades for filter dropdown
        $grades = $this->getDistinctGrades();
        
        // Check if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';
        
        if ($isAjax) {
            // For AJAX requests, only render the table and pagination
            $this->view('exam_results/partials/table', [
                'results' => $resultsData['data'],
                'pagination' => [
                    'current_page' => $resultsData['page'],
                    'total_pages' => $resultsData['total_pages'],
                    'total_records' => $resultsData['total'],
                    'per_page' => $resultsData['per_page']
                ],
                'filters' => $filters,
                'searchTerm' => $searchTerm
            ]);
        } else {
            // For regular requests, render the full page
            $this->view('exam_results/index', [
                'results' => $resultsData['data'],
                'classes' => $classes,
                'subjects' => $subjects,
                'grades' => $grades,
                'searchTerm' => $searchTerm,
                'filters' => $filters,
                'pagination' => [
                    'current_page' => $resultsData['page'],
                    'total_pages' => $resultsData['total_pages'],
                    'total_records' => $resultsData['total'],
                    'per_page' => $resultsData['per_page']
                ]
            ]);
        }
    }
    
    // Helper method to get distinct grades
    private function getDistinctGrades()
    {
        $resultModel = new ExamResult();
        return $resultModel->getDistinctGrades();
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
        
        $resultModel = new ExamResult();
        $result = $resultModel->find($id);
        
        if (!$result) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Exam result not found']);
                return;
            }
            $this->flash('error', 'Exam result not found');
            $this->redirect('/exam_results');
        }
        
        $examModel = new Exam();
        $exam = $examModel->find($result['exam_id']);
        
        $studentModel = new Student();
        $student = $studentModel->find($result['student_id']);
        
        $subjectModel = new Subject();
        $subject = $subjectModel->find($result['subject_id']);
        
        // Get class information
        $classModel = new ClassModel();
        $class = null;
        if ($exam && !empty($exam['class_id'])) {
            $class = $classModel->find($exam['class_id']);
        }
        
        // Get academic year information
        $academicYear = null;
        if ($exam && !empty($exam['academic_year_id'])) {
            $academicYearModel = new AcademicYear();
            $academicYear = $academicYearModel->find($exam['academic_year_id']);
            // Add academic year name to exam data for the view
            if ($academicYear) {
                $exam['academic_year_name'] = $academicYear['name'];
            }
        }
        
        if ($isAjax) {
            // For AJAX requests, only render the view content
            $this->view('exam_results/show', [
                'result' => $result,
                'exam' => $exam,
                'student' => $student,
                'subject' => $subject,
                'class' => $class,
                'isAjax' => true
            ]); 
        } else {
            // For regular requests, render the full page
            $this->view('exam_results/show', [
                'result' => $result,
                'exam' => $exam,
                'student' => $student,
                'subject' => $subject,
                'class' => $class,
                'isAjax' => false
            ]);
        }
    }
    
    public function create()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $examModel = new Exam();
        $exams = $examModel->all();
        
        $gradingScaleModel = new GradingScale();
        $gradingScales = $gradingScaleModel->all();
        
        $this->view('exam_results/create', [
            'exams' => $exams,
            'gradingScales' => $gradingScales
        ]);
    }
    
    public function getClassesByExam()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            error_log("User not logged in");
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['classes' => []]);
            return;
        }
        
        $examId = $this->get('exam_id');
        
        // Add debugging logging
        error_log("getClassesByExam called with exam_id: " . $examId);
        
        if (!$examId) {
            error_log("No exam_id provided");
            header('Content-Type: application/json');
            echo json_encode(['classes' => []]);
            return;
        }
        
        $examModel = new Exam();
        $classes = $examModel->getClasses($examId);
        
        error_log("Found classes for exam: " . print_r($classes, true));
        
        // Ensure we're returning the data in the correct format
        $response = [
            'classes' => $classes
        ];
        
        header('Content-Type: application/json');
        echo json_encode($response);
        error_log("Response sent: " . json_encode($response));
    }
    
    public function getStudentsByClass()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['students' => []]);
            return;
        }
        
        $classId = $this->get('class_id');
        
        if (!$classId) {
            header('Content-Type: application/json');
            echo json_encode(['students' => []]);
            return;
        }
        
        $studentModel = new Student();
        $students = $studentModel->getByClassId($classId);
        
        header('Content-Type: application/json');
        echo json_encode(['students' => $students]);
    }
    
    // New method to get assigned subjects by class
    public function getAssignedSubjectsByClass()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['subjects' => []]);
            return;
        }
        
        $classId = $this->get('class_id');
        
        if (!$classId) {
            header('Content-Type: application/json');
            echo json_encode(['subjects' => []]);
            return;
        }
        
        $subjectModel = new Subject();
        $subjects = $subjectModel->getAssignedSubjectsByClass($classId);
        
        header('Content-Type: application/json');
        echo json_encode(['subjects' => $subjects]);
    }
    
    public function getGradingRules()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['rules' => []]);
            return;
        }
        
        $scaleId = $this->get('scale_id');
        
        if (!$scaleId) {
            header('Content-Type: application/json');
            echo json_encode(['rules' => []]);
            return;
        }
        
        $gradingRuleModel = new GradingRule();
        $rules = $gradingRuleModel->getRulesByScaleSorted($scaleId);
        
        header('Content-Type: application/json');
        echo json_encode(['rules' => $rules]);
    }
    
    // New method to get existing exam results for preventing double entry
    public function getExistingResults()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            http_response_code(401);
            header('Content-Type: application/json');
            echo json_encode(['results' => []]);
            return;
        }
        
        $examId = $this->get('exam_id');
        $classId = $this->get('class_id');
        $subjectId = $this->get('subject_id');
        
        if (!$examId || !$classId || !$subjectId) {
            header('Content-Type: application/json');
            echo json_encode(['results' => []]);
            return;
        }
        
        $resultModel = new ExamResult();
        $results = $resultModel->getByExamClassAndSubject($examId, $classId, $subjectId);
        
        header('Content-Type: application/json');
        echo json_encode(['results' => $results]);
    }
    
    public function store()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        if ($this->requestMethod() === 'POST' || $this->requestMethod() === 'PUT') {
            // Check if this is a bulk submission (new format) or single result (old format)
            $studentIds = $this->post('student_ids', []);
            
            if (!empty($studentIds) && is_array($studentIds)) {
                // Handle bulk submission (new format)
                $this->storeBulk();
            } else {
                // Handle single result submission (old format)
                $data = [
                    'exam_id' => $this->post('exam_id'),
                    'student_id' => $this->post('student_id'),
                    'subject_id' => $this->post('subject_id'),
                    'marks' => $this->post('marks')
                ];
                
                // Basic validation
                if (empty($data['exam_id']) || empty($data['student_id']) || empty($data['subject_id']) || !is_numeric($data['marks'])) {
                    $this->flash('error', 'All fields are required and marks must be numeric');
                    $this->redirect('/exam_results/create');
                }
                
                // Validate marks range
                if ($data['marks'] < 0 || $data['marks'] > 100) {
                    $this->flash('error', 'Marks must be between 0 and 100');
                    $this->redirect('/exam_results/create');
                }
                
                $resultModel = new ExamResult();
                
                // Check if result already exists
                $existingResult = $resultModel->findByExamStudentSubject($data['exam_id'], $data['student_id'], $data['subject_id']);
                
                if ($existingResult) {
                    // Update existing result
                    $result = $resultModel->update($existingResult['id'], $data);
                    if ($result) {
                        $this->flash('success', 'Exam result updated successfully');
                    } else {
                        $this->flash('error', 'Failed to update exam result');
                    }
                } else {
                    // Create new result
                    $resultId = $resultModel->create($data);
                    if ($resultId) {
                        $this->flash('success', 'Exam result recorded successfully');
                    } else {
                        $this->flash('error', 'Failed to record exam result');
                    }
                }
                
                $this->redirect('/exam_results');
            }
        } else {
            $this->create();
        }
    }
    
    public function storeBulk()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            // Check if this is an AJAX request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                echo json_encode(['success' => false, 'message' => 'User not logged in']);
                return;
            }
            $this->redirect('/login');
        }
        
        if ($this->requestMethod() === 'POST' || $this->requestMethod() === 'PUT') {
            $examId = $this->post('exam_id');
            $classId = $this->post('class_id');
            $subjectId = $this->post('subject_id');
            $gradingScaleId = $this->post('grading_scale_id');
            $studentIds = $this->post('student_ids', []);
            
            // Check if this is a classwork-enabled exam by checking for classwork marks
            $examMarks = $this->post('exam_marks', []);
            $classworkMarks = $this->post('classwork_marks', []);
            $finalMarks = $this->post('final_marks', []);
            $marks = $this->post('marks', []); // For exams without classwork
            $grades = $this->post('grades', []);
            $remarks = $this->post('remarks', []);
            
            // Basic validation
            if (empty($examId) || empty($classId) || empty($subjectId) || empty($gradingScaleId) || empty($studentIds)) {
                // Check if this is an AJAX request
                if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'All fields are required']);
                    return;
                }
                $this->flash('error', 'All fields are required');
                $this->redirect('/exam_results/create-bulk');
            }
            
            $resultModel = new ExamResult();
            
            $successCount = 0;
            $errorCount = 0;
            
            // Process each student
            foreach ($studentIds as $index => $studentId) {
                // Determine which marks to use based on whether classwork is enabled
                $studentMark = null;
                $examMark = null;
                $classworkMark = null;
                $finalMark = null;
                
                // Check if this is a classwork-enabled exam
                if (!empty($examMarks) && isset($examMarks[$index])) {
                    // Classwork-enabled exam
                    $examMark = $examMarks[$index] ?? null;
                    $classworkMark = $classworkMarks[$index] ?? null;
                    $finalMark = $finalMarks[$index] ?? null;
                    $studentMark = $finalMark; // Use final mark for grade calculation
                } else {
                    // Regular exam
                    $studentMark = $marks[$index] ?? null;
                }
                
                // Skip if mark is empty
                if ($studentMark === '' || $studentMark === null) {
                    continue;
                }
                
                // Validate marks
                if (!is_numeric($studentMark) || $studentMark < 0 || $studentMark > 100) {
                    $errorCount++;
                    continue;
                }
                
                // Get grade and remark from form data, or calculate if not provided
                $grade = $grades[$index] ?? '';
                $remark = $remarks[$index] ?? '';
                
                // If grade/remark not provided, calculate using the grading scale
                if (empty($grade) && empty($remark)) {
                    $gradeRemark = $resultModel->calculateGradeWithScale($studentMark, $gradingScaleId);
                    $grade = $gradeRemark['grade'];
                    $remark = $gradeRemark['remark'];
                }
                
                $data = [
                    'exam_id' => $examId,
                    'student_id' => $studentId,
                    'subject_id' => $subjectId,
                    'marks' => $studentMark,
                    'grade' => $grade,
                    'remark' => $remark
                ];
                
                // Add classwork fields if this is a classwork-enabled exam
                if ($examMark !== null) {
                    $data['exam_marks'] = $examMark;
                    $data['classwork_marks'] = $classworkMark;
                    $data['final_marks'] = $finalMark;
                }
                
                // Check if result already exists
                $existingResult = $resultModel->findByExamStudentSubject($examId, $studentId, $subjectId);
                
                if ($existingResult) {
                    // Update existing result
                    $result = $resultModel->update($existingResult['id'], $data);
                    if ($result) {
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                } else {
                    // Create new result
                    $resultId = $resultModel->create($data);
                    if ($resultId) {
                        $successCount++;
                    } else {
                        $errorCount++;
                    }
                }
            }
            
            // Check if this is an AJAX request
            if (!empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest') {
                header('Content-Type: application/json');
                if ($successCount > 0) {
                    echo json_encode(['success' => true, 'message' => "{$successCount} exam results recorded successfully" . ($errorCount > 0 ? ", {$errorCount} failed" : "")]);
                } else {
                    echo json_encode(['success' => false, 'message' => 'Failed to record exam results']);
                }
                return;
            }
            
            if ($successCount > 0) {
                $this->flash('success', "{$successCount} exam results recorded successfully" . ($errorCount > 0 ? ", {$errorCount} failed" : ""));
            } else {
                $this->flash('error', 'Failed to record exam results');
            }
            
            $this->redirect('/exam_results');
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
        
        $resultModel = new ExamResult();
        $result = $resultModel->find($id);
        
        if (!$result) {
            if ($isAjax) {
                header('Content-Type: application/json');
                echo json_encode(['error' => 'Exam result not found']);
                return;
            }
            $this->flash('error', 'Exam result not found');
            $this->redirect('/exam_results');
        }
        
        $examModel = new Exam();
        $exams = $examModel->all();
        
        $studentModel = new Student();
        $students = $studentModel->all();
        
        $subjectModel = new Subject();
        $subjects = $subjectModel->all();
        
        if ($isAjax) {
            // For AJAX requests, only render the edit form content
            $this->view('exam_results/edit', [
                'result' => $result,
                'exams' => $exams,
                'students' => $students,
                'subjects' => $subjects,
                'isAjax' => true
            ]); 
        } else {
            // For regular requests, render the full page
            $this->view('exam_results/edit', [
                'result' => $result,
                'exams' => $exams,
                'students' => $students,
                'subjects' => $subjects,
                'isAjax' => false
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
            $data = [
                'exam_id' => $this->post('exam_id'),
                'student_id' => $this->post('student_id'),
                'subject_id' => $this->post('subject_id'),
                'marks' => $this->post('marks')
            ];
            
            // Basic validation
            if (empty($data['exam_id']) || empty($data['student_id']) || empty($data['subject_id']) || !is_numeric($data['marks'])) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'All fields are required and marks must be numeric']);
                    return;
                }
                $this->flash('error', 'All fields are required and marks must be numeric');
                $this->redirect("/exam_results/{$id}/edit");
            }
            
            // Validate marks range
            if ($data['marks'] < 0 || $data['marks'] > 100) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Marks must be between 0 and 100']);
                    return;
                }
                $this->flash('error', 'Marks must be between 0 and 100');
                $this->redirect("/exam_results/{$id}/edit");
            }
            
            $resultModel = new ExamResult();
            
            // Update the exam result
            $result = $resultModel->update($id, $data);
            
            if ($result) {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => true, 'message' => 'Exam result updated successfully']);
                    return;
                }
                $this->flash('success', 'Exam result updated successfully');
                $this->redirect('/exam_results');
            } else {
                if ($isAjax) {
                    header('Content-Type: application/json');
                    echo json_encode(['success' => false, 'message' => 'Failed to update exam result']);
                    return;
                }
                $this->flash('error', 'Failed to update exam result');
                $this->redirect("/exam_results/{$id}/edit");
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
        
        $resultModel = new ExamResult();
        $result = $resultModel->delete($id);
        
        if ($result) {
            $this->flash('success', 'Exam result deleted successfully');
        } else {
            $this->flash('error', 'Failed to delete exam result');
        }
        
        $this->redirect('/exam_results');
    }
    
    public function byExam($examId)
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
        
        $resultModel = new ExamResult();
        $results = $resultModel->getByExamId($examId);
        
        $examModel = new Exam();
        $exam = $examModel->find($examId);

        // Fetch additional details for the exam
        if ($exam) {
            // Get Academic Year Name
            if (!empty($exam['academic_year_id'])) {
                $academicYearModel = new AcademicYear();
                $academicYear = $academicYearModel->find($exam['academic_year_id']);
                $exam['academic_year_name'] = $academicYear ? $academicYear['name'] : 'N/A';
            } else {
                $exam['academic_year_name'] = 'N/A';
            }

            // Get Class Name if not present (though getByExamId joins it, having it on exam object is useful)
            if (!empty($exam['class_id'])) {
                $classModel = new ClassModel();
                $class = $classModel->find($exam['class_id']);
                $exam['class_name'] = $class ? $class['name'] . ' ' . $class['level'] : 'N/A';
            } else {
                $exam['class_name'] = 'N/A';
            }
        }
        
        if ($this->isAjaxRequest()) {
            // Process results to rank students
            $studentResults = [];
            foreach ($results as $row) {
                $studentId = $row['student_id'];
                if (!isset($studentResults[$studentId])) {
                    $studentResults[$studentId] = [
                        'student_info' => [
                            'id' => $row['student_id'],
                            'first_name' => $row['first_name'],
                            'last_name' => $row['last_name'],
                            'admission_no' => $row['admission_no'],
                        ],
                        'results' => [],
                        'total_marks' => 0,
                        'subject_count' => 0
                    ];
                }
                
                $studentResults[$studentId]['results'][] = $row;
                // Use 'final_marks' if available, otherwise 'marks'
                // Assuming 'marks' is relevant here based on existing code usage
                $mark = is_numeric($row['marks']) ? $row['marks'] : 0;
                $studentResults[$studentId]['total_marks'] += $mark;
                $studentResults[$studentId]['subject_count']++;
            }
            
            // Sort by total marks descending
            usort($studentResults, function($a, $b) {
                // If marks are equal, you could sort by name, but here strictly by marks
                return $b['total_marks'] <=> $a['total_marks'];
            });
            
            // Assign ranks
            $rank = 1;
            $rankedResults = [];
            foreach ($studentResults as $data) {
                $data['rank'] = $rank++;
                $data['average'] = $data['subject_count'] > 0 ? round($data['total_marks'] / $data['subject_count'], 2) : 0;
                $rankedResults[] = $data;
            }
            
            // Return JSON data for AJAX requests (used by modals)
            $this->jsonResponse([
                'success' => true,
                'exam' => $exam,
                'results' => $rankedResults // Returns the ranked aggregated list
            ]);
        } else {
            // Render the full page for regular requests
            $this->view('exam_results/by_exam', [
                'results' => $results,
                'exam' => $exam
            ]);
        }
    }
    
    // Submission status functionality - modified to show all exams with submission statistics
    public function submissionStatus()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Get current academic year with term
        $academicYearModel = new AcademicYear();
        $currentAcademicYear = $academicYearModel->getCurrentAcademicYearWithTerm();
        
        // Get all academic years
        $academicYears = $academicYearModel->getAll();
        
        // Get all classes
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        // Get all subjects
        $subjectModel = new Subject();
        $subjects = $subjectModel->all();
        
        // Get all terms
        $terms = ['first', 'second', 'third'];
        
        // Get pagination and search parameters
        $page = (int) $this->get('page', 1);
        $perPage = (int) $this->get('per_page', 10);
        $searchTerm = trim($this->get('search', ''));
        
        // Get filter parameters
        $classId = $this->get('class_id');
        $subjectId = $this->get('subject_id');
        $term = $this->get('term');
        $academicYearId = $this->get('academic_year_id', $currentAcademicYear ? $currentAcademicYear['id'] : null);
        
        // Get all exams with submission statistics
        $examModel = new Exam();
        $resultModel = new ExamResult();
        $studentModel = new Student();
        
        // Prepare filters
        $filters = [];
        if ($classId) {
            $filters['class_id'] = $classId;
        }
        if ($term) {
            $filters['term'] = $term;
        }
        if ($academicYearId) {
            $filters['academic_year_id'] = $academicYearId;
        }
        
        // Get paginated exams with filters and search
        $examResult = $examModel->getAllWithClassAndAcademicYearPaginated($filters, $page, $perPage, $searchTerm);
        $exams = $examResult['data'];
        $pagination = [
            'total' => $examResult['total'],
            'page' => $examResult['page'],
            'per_page' => $examResult['per_page'],
            'total_pages' => $examResult['total_pages']
        ];
        
        // Add submission statistics to each exam and subject combination
        $examStats = [];
        
        foreach ($exams as $exam) {
            // Get class details
            $classModel = new ClassModel();
            $classDetails = $classModel->find($exam['class_id']);
            
            // Get all subjects assigned to this class
            $assignedSubjects = $subjectModel->getAssignedSubjectsByClass($exam['class_id']);
            
            // If subject filter is applied, filter to only that subject
            if ($subjectId) {
                $assignedSubjects = array_filter($assignedSubjects, function($subject) use ($subjectId) {
                    return $subject['id'] == $subjectId;
                });
            }
            
            // For each subject, get submission statistics
            foreach ($assignedSubjects as $subject) {
                // Get all students in this class
                $allStudents = $studentModel->getByClassId($exam['class_id']);
                
                // Get students who have submitted results for this exam and subject
                $resultModel = new ExamResult();
                $submittedResults = $resultModel->getByExamClassAndSubject($exam['id'], $exam['class_id'], $subject['id']);
                
                // Create a map of submitted student IDs
                $submittedStudentIds = [];
                foreach ($submittedResults as $result) {
                    $submittedStudentIds[$result['student_id']] = $result;
                }
                
                // Count submitted and unsubmitted students
                $submittedCount = count($submittedStudentIds);
                $totalStudents = count($allStudents);
                $unsubmittedCount = $totalStudents - $submittedCount;
                
                $examStats[] = [
                    'exam' => $exam,
                    'class' => $classDetails,
                    'subject' => $subject,
                    'totalStudents' => $totalStudents,
                    'submittedCount' => $submittedCount,
                    'unsubmittedCount' => $unsubmittedCount,
                    'completionPercentage' => $totalStudents > 0 ? round(($submittedCount / $totalStudents) * 100, 1) : 0
                ];
            }
            
            // If no subjects are assigned to this class, still show the exam with "No Subjects" status
            if (empty($assignedSubjects)) {
                $examStats[] = [
                    'exam' => $exam,
                    'class' => $classDetails,
                    'subject' => null,
                    'totalStudents' => 0,
                    'submittedCount' => 0,
                    'unsubmittedCount' => 0,
                    'completionPercentage' => 0
                ];
            }
        }
        
        $this->view('exam_results/submission_status', [
            'classes' => $classes,
            'subjects' => $subjects,
            'terms' => $terms,
            'academic_years' => $academicYears,
            'current_academic_year' => $currentAcademicYear,
            'examStats' => $examStats,
            'classId' => $classId,
            'subjectId' => $subjectId,
            'term' => $term,
            'academicYearId' => $academicYearId,
            'search' => $searchTerm,
            'page' => $page,
            'per_page' => $perPage,
            'pagination' => $pagination
        ]);
    }
    
    // Submission status details functionality
    public function submissionStatusDetails()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Get parameters
        $examId = $this->get('exam_id');
        $subjectId = $this->get('subject_id');
        $searchTerm = trim($this->get('search', ''));
        
        if (!$examId) {
            $this->flash('error', 'Exam ID is required');
            $this->redirect('/exam_results/submission-status');
        }
        
        // Get exam details
        $examModel = new Exam();
        $exam = $examModel->find($examId);
        
        if (!$exam) {
            $this->flash('error', 'Exam not found');
            $this->redirect('/exam_results/submission-status');
        }
        
        // Get class details
        $classModel = new ClassModel();
        $class = $classModel->find($exam['class_id']);
        
        // Get subject details if provided
        $subject = null;
        if ($subjectId) {
            $subjectModel = new Subject();
            $subject = $subjectModel->find($subjectId);
        }
        
        // Get all students in this class
        $studentModel = new Student();
        $allStudents = $studentModel->getByClassId($exam['class_id']);
        
        // Get students who have submitted results for this exam and subject
        $resultModel = new ExamResult();
        $submittedResults = $resultModel->getByExamClassAndSubject($exam['id'], $exam['class_id'], $subjectId ?? 0);
        
        // Create a map of submitted student IDs
        $submittedStudentIds = [];
        foreach ($submittedResults as $result) {
            $submittedStudentIds[$result['student_id']] = $result;
        }
        
        // Separate submitted and unsubmitted students
        $submittedStudents = [];
        $unsubmittedStudents = [];
        
        foreach ($allStudents as $student) {
            if (isset($submittedStudentIds[$student['id']])) {
                $submittedStudents[] = [
                    'student' => $student,
                    'result' => $submittedStudentIds[$student['id']]
                ];
            } else {
                $unsubmittedStudents[] = $student;
            }
        }
        
        // Apply search filtering if search term is provided
        if (!empty($searchTerm)) {
            // Filter submitted students
            $submittedStudents = array_filter($submittedStudents, function($item) use ($searchTerm) {
                $student = $item['student'];
                $fullName = strtolower(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? ''));
                $admissionNo = strtolower($student['admission_no'] ?? '');
                $searchLower = strtolower($searchTerm);
                return strpos($fullName, $searchLower) !== false || strpos($admissionNo, $searchLower) !== false;
            });
            
            // Filter unsubmitted students
            $unsubmittedStudents = array_filter($unsubmittedStudents, function($student) use ($searchTerm) {
                $fullName = strtolower(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? ''));
                $admissionNo = strtolower($student['admission_no'] ?? '');
                $searchLower = strtolower($searchTerm);
                return strpos($fullName, $searchLower) !== false || strpos($admissionNo, $searchLower) !== false;
            });
            
            // Re-index arrays after filtering
            $submittedStudents = array_values($submittedStudents);
            $unsubmittedStudents = array_values($unsubmittedStudents);
        }
        
        $submissionData = [
            'exam' => $exam,
            'class' => $class,
            'subject' => $subject,
            'submittedStudents' => $submittedStudents,
            'unsubmittedStudents' => $unsubmittedStudents,
            'totalStudents' => count($allStudents),
            'submittedCount' => count($submittedStudents),
            'unsubmittedCount' => count($unsubmittedStudents)
        ];
        
        // Check if this is an AJAX request
        $isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && 
                  strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

        $this->view('exam_results/submission_status_details', [
            'submissionData' => $submissionData,
            'search' => $searchTerm,
            'isAjax' => $isAjax
        ]);
    }
    
    public function export()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Check if this is a print request
        $print = $this->get('print', false);
        
        // Check if we're exporting for a specific exam
        $examId = $this->get('exam_id');
        
        if ($examId) {
            // Export results for a specific exam
            if ($print) {
                $this->printExamResults($examId);
            } else {
                $this->exportExamResults($examId);
            }
        } else {
            // Export filtered results
            if ($print) {
                $this->printFilteredResults();
            } else {
                $this->exportFilteredResults();
            }
        }
    }
    
    private function exportResults()
    {
        $resultModel = new ExamResult();
        $results = $resultModel->getAllWithDetails();
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="exam_results.csv"');
        
        // Output CSV
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['Exam', 'Student Name', 'Admission No', 'Class', 'Subject', 'Marks', 'Grade', 'Date']);
        
        // Add data
        foreach ($results as $result) {
            fputcsv($output, [
                $result['exam_name'] ?? 'N/A',
                $result['first_name'] . ' ' . $result['last_name'],
                $result['admission_no'],
                $result['class_name'] ?? 'N/A',
                $result['subject_name'] ?? 'N/A',
                $result['marks'],
                $result['grade'],
                $result['exam_date'] ?? 'N/A'
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    private function exportExamResults($examId)
    {
        $resultModel = new ExamResult();
        $results = $resultModel->getByExamId($examId);
        
        // Get exam information
        $examModel = new Exam();
        $exam = $examModel->find($examId);
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="exam_results_' . ($exam['name'] ?? 'exam') . '.csv"');
        
        // Output CSV
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['Student Name', 'Admission No', 'Subject', 'Marks', 'Grade']);
        
        // Add data
        foreach ($results as $result) {
            // Get student information
            $studentModel = new Student();
            $student = $studentModel->find($result['student_id']);
            
            // Get subject information
            $subjectModel = new Subject();
            $subject = $subjectModel->find($result['subject_id']);
            
            fputcsv($output, [
                ($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? ''),
                $student['admission_no'] ?? 'N/A',
                $subject['name'] ?? 'N/A',
                $result['marks'],
                $result['grade']
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    private function printExamResults($examId)
    {
        $resultModel = new ExamResult();
        $results = $resultModel->getByExamId($examId);
        
        // Get exam information
        $examModel = new Exam();
        $exam = $examModel->find($examId);
        
        // Group results by subject
        $resultsBySubject = [];
        foreach ($results as $result) {
            $subjectId = $result['subject_id'];
            if (!isset($resultsBySubject[$subjectId])) {
                $subjectModel = new Subject();
                $subject = $subjectModel->find($subjectId);
                $resultsBySubject[$subjectId] = [
                    'subject' => $subject,
                    'results' => []
                ];
            }
            $resultsBySubject[$subjectId]['results'][] = $result;
        }
        
        // Render print view
        $this->view('exam_results/print', [
            'resultsBySubject' => $resultsBySubject,
            'exam' => $exam
        ]);
    }
    
    private function exportFilteredResults()
    {
        // Get current academic year with term
        $currentAcademicYear = AcademicYearHelper::getCurrentAcademicYearWithTerm();
        
        $resultModel = new ExamResult();
        $results = $resultModel->getAllWithDetails();
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="filtered_exam_results.csv"');
        
        // Output CSV
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['Exam', 'Student Name', 'Admission No', 'Class', 'Subject', 'Marks', 'Grade', 'Date']);
        
        // Add data
        foreach ($results as $result) {
            fputcsv($output, [
                $result['exam_name'] ?? 'N/A',
                $result['first_name'] . ' ' . $result['last_name'],
                $result['admission_no'],
                $result['class_name'] ?? 'N/A',
                $result['subject_name'] ?? 'N/A',
                $result['marks'],
                $result['grade'],
                $result['exam_date'] ?? 'N/A'
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    private function printFilteredResults()
    {
        // Get current academic year with term
        $currentAcademicYear = AcademicYearHelper::getCurrentAcademicYearWithTerm();
        
        $resultModel = new ExamResult();
        $results = $resultModel->getAllWithDetails();
        
        // Group results by exam
        $resultsByExam = [];
        foreach ($results as $result) {
            $examId = $result['exam_id'];
            if (!isset($resultsByExam[$examId])) {
                $examModel = new Exam();
                $exam = $examModel->find($examId);
                $resultsByExam[$examId] = [
                    'exam' => $exam,
                    'results' => []
                ];
            }
            $resultsByExam[$examId]['results'][] = $result;
        }
        
        // Render print view
        $this->view('exam_results/print_filtered', [
            'resultsByExam' => $resultsByExam,
            'currentAcademicYear' => $currentAcademicYear
        ]);
    }
}