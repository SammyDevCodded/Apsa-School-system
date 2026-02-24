<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Timetable;
use App\Models\ClassModel;
use App\Models\Subject;
use App\Models\Staff;
use App\Models\AcademicYear;

class TimetableController extends Controller
{
    private $timetableModel;
    private $classModel;
    private $subjectModel;
    private $staffModel;
    private $academicYearModel;

    public function __construct()
    {
        // Removed parent::__construct() call since parent Controller class doesn't have a constructor
        $this->timetableModel = new Timetable();
        $this->classModel = new ClassModel();
        $this->subjectModel = new Subject();
        $this->staffModel = new Staff();
        $this->academicYearModel = new AcademicYear();
    }

    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        // Get filter parameters
        $classId = $_GET['class_id'] ?? null;
        $staffId = $_GET['staff_id'] ?? null;
        $subjectId = $_GET['subject_id'] ?? null;
        
        error_log("Filter parameters - Class: $classId, Staff: $staffId, Subject: $subjectId");
        
        // Get timetables with filters
        if ($classId || $staffId || $subjectId) {
            try {
                $timetables = $this->timetableModel->getFiltered($classId, $staffId, $subjectId);
                error_log("Filtered timetables count: " . count($timetables));
            } catch (\Exception $e) {
                error_log("Error in getFiltered: " . $e->getMessage());
                $timetables = $this->timetableModel->getAll();
            }
        } else {
            $timetables = $this->timetableModel->getAll();
            error_log("All timetables count: " . count($timetables));
        }
        
        $classes = $this->classModel->getAll();
        $staff = $this->staffModel->getTeachingStaff();
        $subjects = $this->subjectModel->all();
        
        // Handle AJAX request for modal content
        if ($this->isAjaxRequest()) {
            try {
                // For AJAX requests, we need to render the view and return the HTML
                ob_start();
                extract([
                    'timetables' => $timetables,
                    'classes' => $classes,
                    'staff' => $staff,
                    'subjects' => $subjects
                ]);
                include RESOURCES_PATH . '/views/timetables/index.php';
                $content = ob_get_clean();
                $this->jsonResponse(['html' => $content]);
            } catch (\Exception $e) {
                error_log("Error rendering AJAX response: " . $e->getMessage());
                $this->jsonResponse(['error' => 'Error rendering response'], 500);
            }
            return;
        }
        
        $this->view('timetables/index', [
            'timetables' => $timetables,
            'classes' => $classes,
            'staff' => $staff,
            'subjects' => $subjects
        ]);
    }

    public function create()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        $userRoleId = $_SESSION['user']['role_id'] ?? 0;
        if ($userRoleId != 1 && $userRoleId != 3) {  // 1 = admin, 3 = super_admin
            $this->redirect('/dashboard');
        }

        $classes = $this->classModel->all();
        $subjects = $this->subjectModel->all();
        $staff = $this->staffModel->getTeachingStaff();
        $academicYears = $this->academicYearModel->all();
        
        // Get the current academic year with term
        $currentAcademicYear = $this->academicYearModel->getCurrentAcademicYearWithTerm();

        // Handle AJAX request for modal content
        if ($this->isAjaxRequest()) {
            // For AJAX requests, we need to render the view and return the HTML
            // We'll create a minimal layout for AJAX requests
            $viewPath = RESOURCES_PATH . '/views/timetables/create.php';
            if (file_exists($viewPath)) {
                ob_start();
                extract([
                    'classes' => $classes,
                    'subjects' => $subjects,
                    'staff' => $staff,
                    'academicYears' => $academicYears,
                    'currentAcademicYear' => $currentAcademicYear,
                    'subjectModel' => $this->subjectModel,
                    'staffModel' => $this->staffModel
                ]);
                include $viewPath;
                $content = ob_get_clean();
                $this->jsonResponse(['html' => $content]);
            } else {
                $this->jsonResponse(['html' => 'Error: View file not found'], 500);
            }
            return;
        }

        $this->view('timetables/create', [
            'classes' => $classes,
            'subjects' => $subjects,
            'staff' => $staff,
            'academicYears' => $academicYears,
            'currentAcademicYear' => $currentAcademicYear,
            'subjectModel' => $this->subjectModel,
            'staffModel' => $this->staffModel
        ]);
    }

    public function store()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        $userRoleId = $_SESSION['user']['role_id'] ?? 0;
        if ($userRoleId != 1 && $userRoleId != 3) {  // 1 = admin, 3 = super_admin
            $this->redirect('/dashboard');
        }

        // Validate input
        $class_id = $_POST['class_id'] ?? '';
        $subject_id = $_POST['subject_id'] ?? '';
        $staff_id = $_POST['staff_id'] ?? '';
        $day_of_week = $_POST['day_of_week'] ?? '';
        $start_time = $_POST['start_time'] ?? '';
        $end_time = $_POST['end_time'] ?? '';
        $room = $_POST['room'] ?? '';
        $academic_year_id = $_POST['academic_year_id'] ?? '';

        if (empty($class_id) || empty($subject_id) || empty($staff_id) || empty($day_of_week) || 
            empty($start_time) || empty($end_time) || empty($academic_year_id)) {
            // Handle AJAX request
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'error' => 'All fields are required.'], 400);
                return;
            }
            $_SESSION['flash_error'] = 'All fields are required.';
            $this->redirect('/timetables/create');
            return;
        }

        // Check if start time is before end time
        if ($start_time >= $end_time) {
            // Handle AJAX request
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'error' => 'Start time must be before end time.'], 400);
                return;
            }
            $_SESSION['flash_error'] = 'Start time must be before end time.';
            $this->redirect('/timetables/create');
            return;
        }

        // Insert into database
        $data = [
            'class_id' => $class_id,
            'subject_id' => $subject_id,
            'staff_id' => $staff_id,
            'day_of_week' => $day_of_week,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'room' => $room,
            'academic_year_id' => $academic_year_id
        ];

        $result = $this->timetableModel->create($data);

        if ($result) {
            // Handle AJAX request
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => true, 'message' => 'Timetable entry created successfully.']);
                return;
            }
            $_SESSION['flash_success'] = 'Timetable entry created successfully.';
            $this->redirect('/timetables');
        } else {
            // Handle AJAX request
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'error' => 'Failed to create timetable entry.'], 500);
                return;
            }
            $_SESSION['flash_error'] = 'Failed to create timetable entry.';
            $this->redirect('/timetables/create');
        }
    }

    public function edit($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        $userRoleId = $_SESSION['user']['role_id'] ?? 0;
        if ($userRoleId != 1 && $userRoleId != 3) {  // 1 = admin, 3 = super_admin
            $this->redirect('/dashboard');
        }

        $timetable = $this->timetableModel->find($id);
        if (!$timetable) {
            // Handle AJAX request
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'error' => 'Timetable entry not found.'], 404);
                return;
            }
            $_SESSION['flash_error'] = 'Timetable entry not found.';
            $this->redirect('/timetables');
            return;
        }

        $classes = $this->classModel->all();
        $subjects = $this->subjectModel->all();
        $staff = $this->staffModel->getTeachingStaff();
        $academicYears = $this->academicYearModel->all();
        
        // Get the current academic year with term
        $currentAcademicYear = $this->academicYearModel->getCurrentAcademicYearWithTerm();

        // Handle AJAX request for modal content
        if ($this->isAjaxRequest()) {
            // For AJAX requests, we need to render the view and return the HTML
            ob_start();
            extract([
                'timetable' => $timetable,
                'classes' => $classes,
                'subjects' => $subjects,
                'staff' => $staff,
                'academicYears' => $academicYears,
                'currentAcademicYear' => $currentAcademicYear,
                'subjectModel' => $this->subjectModel,
                'staffModel' => $this->staffModel
            ]);
            include RESOURCES_PATH . '/views/timetables/edit.php';
            $content = ob_get_clean();
            $this->jsonResponse(['html' => $content]);
            return;
        }
        
        $this->view('timetables/edit', [
            'timetable' => $timetable,
            'classes' => $classes,
            'subjects' => $subjects,
            'staff' => $staff,
            'academicYears' => $academicYears,
            'currentAcademicYear' => $currentAcademicYear,
            'subjectModel' => $this->subjectModel,
            'staffModel' => $this->staffModel
        ]);
    }

    public function update($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        $userRoleId = $_SESSION['user']['role_id'] ?? 0;
        if ($userRoleId != 1 && $userRoleId != 3) {  // 1 = admin, 3 = super_admin
            $this->redirect('/dashboard');
        }

        $timetable = $this->timetableModel->find($id);
        if (!$timetable) {
            // Handle AJAX request
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'error' => 'Timetable entry not found.'], 404);
                return;
            }
            $_SESSION['flash_error'] = 'Timetable entry not found.';
            $this->redirect('/timetables');
            return;
        }

        // Validate input
        $class_id = $_POST['class_id'] ?? '';
        $subject_id = $_POST['subject_id'] ?? '';
        $staff_id = $_POST['staff_id'] ?? '';
        $day_of_week = $_POST['day_of_week'] ?? '';
        $start_time = $_POST['start_time'] ?? '';
        $end_time = $_POST['end_time'] ?? '';
        $room = $_POST['room'] ?? '';
        $academic_year_id = $_POST['academic_year_id'] ?? '';

        if (empty($class_id) || empty($subject_id) || empty($staff_id) || empty($day_of_week) || 
            empty($start_time) || empty($end_time) || empty($academic_year_id)) {
            // Handle AJAX request
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'error' => 'All fields are required.'], 400);
                return;
            }
            $_SESSION['flash_error'] = 'All fields are required.';
            $this->redirect('/timetables/' . $id . '/edit');
            return;
        }

        // Check if start time is before end time
        if ($start_time >= $end_time) {
            // Handle AJAX request
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'error' => 'Start time must be before end time.'], 400);
                return;
            }
            $_SESSION['flash_error'] = 'Start time must be before end time.';
            $this->redirect('/timetables/' . $id . '/edit');
            return;
        }

        // Update database
        $data = [
            'class_id' => $class_id,
            'subject_id' => $subject_id,
            'staff_id' => $staff_id,
            'day_of_week' => $day_of_week,
            'start_time' => $start_time,
            'end_time' => $end_time,
            'room' => $room,
            'academic_year_id' => $academic_year_id
        ];

        $result = $this->timetableModel->update($id, $data);

        if ($result) {
            // Handle AJAX request
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => true, 'message' => 'Timetable entry updated successfully.']);
                return;
            }
            $_SESSION['flash_success'] = 'Timetable entry updated successfully.';
            $this->redirect('/timetables');
        } else {
            // Handle AJAX request
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'error' => 'Failed to update timetable entry.'], 500);
                return;
            }
            $_SESSION['flash_error'] = 'Failed to update timetable entry.';
            $this->redirect('/timetables/' . $id . '/edit');
        }
    }

    public function delete($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        $userRoleId = $_SESSION['user']['role_id'] ?? 0;
        if ($userRoleId != 1 && $userRoleId != 3) {  // 1 = admin, 3 = super_admin
            $this->redirect('/dashboard');
        }

        $timetable = $this->timetableModel->find($id);
        if (!$timetable) {
            // Handle AJAX request
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'error' => 'Timetable entry not found.'], 404);
                return;
            }
            $_SESSION['flash_error'] = 'Timetable entry not found.';
            $this->redirect('/timetables');
            return;
        }

        $result = $this->timetableModel->delete($id);

        if ($result) {
            // Handle AJAX request
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => true, 'message' => 'Timetable entry deleted successfully.']);
                return;
            }
            $_SESSION['flash_success'] = 'Timetable entry deleted successfully.';
        } else {
            // Handle AJAX request
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['success' => false, 'error' => 'Failed to delete timetable entry.'], 500);
                return;
            }
            $_SESSION['flash_error'] = 'Failed to delete timetable entry.';
        }

        // Handle AJAX request
        if ($this->isAjaxRequest()) {
            return;
        }

        $this->redirect('/timetables');
    }

    // AJAX endpoint to get subjects by class
    public function getSubjectsByClass($classId)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
            return;
        }
        
        $subjects = $this->subjectModel->getByClassId($classId);
        $this->jsonResponse($subjects);
    }
    
    // AJAX endpoint to get teachers by subject
    public function getTeachersBySubject($subjectId)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
            return;
        }
        
        $teachers = $this->staffModel->getTeachersBySubject($subjectId);
        $this->jsonResponse($teachers);
    }

    public function viewByClass($classId)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        // For viewing timetables, all authenticated users can access
        // No need to check for admin role here

        $timetables = $this->timetableModel->getByClass($classId);
        $class = $this->classModel->find($classId);
        
        $this->view('timetables/view_by_class', [
            'timetables' => $timetables,
            'class' => $class
        ]);
    }

    public function viewByDay($day)
    {
        // ... existing code ...
        $this->view('timetables/view_by_day', [
            'timetables' => $timetables,
            'day' => $day,
            'dayName' => $dayName
        ]);
    }

    public function print()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        // Get filter parameters
        $classId = $_GET['class_id'] ?? null;
        $staffId = $_GET['staff_id'] ?? null;
        $subjectId = $_GET['subject_id'] ?? null;

        $title = 'Timetable';
        $subTitle = 'All Entires';

        // Get timetables with filters
        if ($classId || $staffId || $subjectId) {
            $timetables = $this->timetableModel->getFiltered($classId, $staffId, $subjectId);
            
            // Determine Title
            if ($classId) {
                $class = $this->classModel->find($classId);
                if ($class) {
                    $title = 'Class Timetable';
                    $subTitle = 'Class: ' . $class['name'];
                }
            } elseif ($staffId) {
                $staff = $this->staffModel->find($staffId);
                if ($staff) {
                    $title = 'Staff Timetable';
                    $subTitle = 'Staff: ' . $staff['first_name'] . ' ' . $staff['last_name'];
                }
            } elseif ($subjectId) {
                 $subject = $this->subjectModel->find($subjectId);
                 if ($subject) {
                     $title = 'Subject Timetable';
                     $subTitle = 'Subject: ' . $subject['name'];
                 }
            }
        } else {
            $timetables = $this->timetableModel->getAll();
        }

        // Get school settings for header
        $settingModel = new \App\Models\Setting();
        $settings = $settingModel->getSettings();

        $this->view('timetables/print', [
            'timetables' => $timetables,
            'settings' => $settings,
            'pageTitle' => $title,
            'subTitle' => $subTitle
        ]);
    }
}