<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\AcademicYear;
use App\Helpers\AcademicYearHelper;

class AcademicYearController extends Controller
{
    private $academicYearModel;

    public function __construct()
    {
        $this->academicYearModel = new AcademicYear();
    }

    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        $academicYears = $this->academicYearModel->getAll();
        
        // Get settings for dynamic year range
        $settingModel = new \App\Models\Setting();
        $settings = $settingModel->getSettings();
        $futureLimit = $settings['academic_year_future_limit'] ?? 10;
        
        $this->view('academic_years/index', [
            'academicYears' => $academicYears,
            'futureLimit' => $futureLimit
        ]);
    }

    public function create()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        $this->view('academic_years/create');
    }

    public function store()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        $name = $_POST['name'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        $year = $_POST['year'] ?? '';
        $status = $_POST['status'] ?? 'active';

        if (empty($name) || empty($start_date) || empty($end_date)) {
            $_SESSION['flash_error'] = 'All fields are required.';
            $this->redirect('/academic_years/create');
            return;
        }

        // Validate dates
        if (strtotime($start_date) >= strtotime($end_date)) {
            $_SESSION['flash_error'] = 'Start date must be before end date.';
            $this->redirect('/academic_years/create');
            return;
        }

        $data = [
            'name' => $name,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'end_date' => $end_date,
            'is_current' => 0, // Default to not current
            'status' => $status
        ];

        $result = $this->academicYearModel->create($data);

        if ($result) {
            $_SESSION['flash_success'] = 'Academic year created successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to create academic year.';
        }

        $this->redirect('/academic_years');
    }

    public function edit($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        $academicYear = $this->academicYearModel->find($id);
        if (!$academicYear) {
            $_SESSION['flash_error'] = 'Academic year not found.';
            $this->redirect('/academic_years');
            return;
        }

        $this->view('academic_years/edit', [
            'academicYear' => $academicYear
        ]);
    }

    public function update($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        $academicYear = $this->academicYearModel->find($id);
        if (!$academicYear) {
            $_SESSION['flash_error'] = 'Academic year not found.';
            $this->redirect('/academic_years');
            return;
        }

        $name = $_POST['name'] ?? '';
        $start_date = $_POST['start_date'] ?? '';
        $end_date = $_POST['end_date'] ?? '';
        $year = $_POST['year'] ?? '';
        $status = $_POST['status'] ?? 'active';

        if (empty($name) || empty($start_date) || empty($end_date) || empty($year)) {
            $_SESSION['flash_error'] = 'All fields are required.';
            $this->redirect('/academic_years/' . $id . '/edit');
            return;
        }

        // Validate dates
        if (strtotime($start_date) >= strtotime($end_date)) {
            $_SESSION['flash_error'] = 'Start date must be before end date.';
            $this->redirect('/academic_years/' . $id . '/edit');
            return;
        }

        $data = [
            'name' => $name,
            'start_date' => $start_date,
            'end_date' => $end_date,
            'status' => $status
        ];

        $result = $this->academicYearModel->update($id, $data);

        if ($result) {
            $_SESSION['flash_success'] = 'Academic year updated successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to update academic year.';
        }

        $this->redirect('/academic_years');
    }

    public function delete($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        $academicYear = $this->academicYearModel->find($id);
        if (!$academicYear) {
            $_SESSION['flash_error'] = 'Academic year not found.';
            $this->redirect('/academic_years');
            return;
        }

        // Don't allow deletion of current academic year
        if ($academicYear['is_current'] == 1) {
            $_SESSION['flash_error'] = 'Cannot delete the current academic year.';
            $this->redirect('/academic_years');
            return;
        }

        $result = $this->academicYearModel->delete($id);

        if ($result) {
            $_SESSION['flash_success'] = 'Academic year deleted successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to delete academic year.';
        }

        $this->redirect('/academic_years');
    }

    public function setCurrent($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        $result = $this->academicYearModel->setActive($id);

        if ($result) {
            $_SESSION['flash_success'] = 'Current academic year updated successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to update current academic year.';
        }

        $this->redirect('/academic_years');
    }

    // Method to update the term for the current academic year
    public function updateTerm()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        $term = $_POST['term'] ?? '';
        $academicYearId = $_POST['academic_year_id'] ?? '';

        if (empty($term) || empty($academicYearId)) {
            $_SESSION['flash_error'] = 'Term and academic year are required.';
            $this->redirect('/academic_years');
            return;
        }

        // Update the term for the academic year
        $result = $this->academicYearModel->updateTerm($academicYearId, $term);

        if ($result) {
            $_SESSION['flash_success'] = 'Term updated successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to update term.';
        }

        $this->redirect('/academic_years');
    }
    public function show($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        $academicYear = $this->academicYearModel->find($id);
        if (!$academicYear) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Academic year not found'], 404);
            }
            $_SESSION['flash_error'] = 'Academic year not found.';
            $this->redirect('/academic_years');
            return;
        }
        
        // Check permissions for editing completed years
        $canEditCompleted = $this->hasRole('super_admin');

        if ($this->isAjaxRequest()) {
            ob_start();
            include RESOURCES_PATH . '/views/academic_years/show.php';
            $content = ob_get_clean();
            $this->jsonResponse(['html' => $content]);
            return;
        }

        $this->view('academic_years/show', [
            'academicYear' => $academicYear,
            'canEditCompleted' => $canEditCompleted
        ]);
    }

    public function updateStatus($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        $status = $_POST['status'] ?? '';
        
        if (!in_array($status, ['active', 'inactive', 'completed'])) {
            $_SESSION['flash_error'] = 'Invalid status.';
            $this->redirect('/academic_years');
            return;
        }

        // Check permission if trying to reactivate a completed year
        if ($status === 'active') {
            $academicYear = $this->academicYearModel->find($id);
            if ($academicYear && $academicYear['status'] === 'completed' && !$this->hasRole('super_admin')) {
                $_SESSION['flash_error'] = 'Only Super Admins can reactivate a completed academic year.';
                $this->redirect('/academic_years');
                return;
            }
        }

        $result = $this->academicYearModel->update($id, ['status' => $status]);

        if ($result) {
            $_SESSION['flash_success'] = 'Academic year status updated to ' . ucfirst($status) . '.';
        } else {
            $_SESSION['flash_error'] = 'Failed to update academic year status.';
        }

        $this->redirect('/academic_years');
    }
}