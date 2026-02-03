<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Setting;
use App\Models\GradingScale;
use App\Models\GradingRule;
use App\Models\Student;
use App\Models\ClassModel;
use Exception;
use PhpOffice\PhpSpreadsheet\IOFactory;

class SettingsController extends Controller
{
    private $settingModel;
    private $gradingScaleModel;
    private $gradingRuleModel;

    // Define currency symbol to code mapping
    private $currencyOptions = [
        'GH₵' => 'GHS',
        '$' => 'USD',
        '€' => 'EUR',
        '£' => 'GBP',
        '¥' => 'JPY',
        '₹' => 'INR',
        '₩' => 'KRW',
        '₽' => 'RUB',
        'R' => 'ZAR',
        '₵' => 'GHS', // Alternative symbol for Ghanaian Cedi
        'CFA' => 'XOF', // West African CFA Franc
        '₦' => 'NGN', // Nigerian Naira
        'Sh' => 'KES', // Kenyan Shilling
        'TSh' => 'TZS', // Tanzanian Shilling
        'UGX' => 'UGX', // Ugandan Shilling
        'Le' => 'SLL', // Sierra Leonean Leone
    ];

    public function __construct()
    {
        // Removed parent::__construct() call since parent Controller class doesn't have a constructor
        $this->settingModel = new Setting();
        $this->gradingScaleModel = new GradingScale();
        $this->gradingRuleModel = new GradingRule();
    }

    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        // Load settings from database
        $settings = $this->settingModel->getSettings();
        
        // If no settings found, use defaults
        if (!$settings) {
            $settings = [
                'school_name' => 'APSA-ERP',
                'school_logo' => null,
                'currency_code' => 'GHS',
                'currency_symbol' => 'GH₵',
                'watermark_type' => 'none',
                'watermark_position' => 'center',
                'watermark_transparency' => 20,
                'student_admission_prefix' => 'EPI',
                'staff_employee_prefix' => 'StID'
            ];
        }

        // Load grading scales
        $gradingScales = $this->gradingScaleModel->getAllWithRules();

        // Check if user has super admin role
        $isSuperAdmin = $this->hasAnyRole(['admin', 'super_admin']);

        $this->view('settings/index', [
            'settings' => $settings,
            'gradingScales' => $gradingScales,
            'isSuperAdmin' => $isSuperAdmin
        ]);
    }

    public function update()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        // Check which form is being submitted
        $formType = $_POST['form_type'] ?? '';

        switch ($formType) {
            case 'school_info':
                $this->updateSchoolInfo();
                break;
            case 'watermark':
                $this->updateWatermarkSettings();
                break;
            case 'auto_generate':
                $this->updateAutoGenerateSettings();
                break;
            case 'academic_settings':
                $this->updateAcademicSettings();
                break;
            default:
                // Default to school info form if no specific form is identified
                $this->updateSchoolInfo();
                break;
        }

        $this->redirect('/settings');
    }

    private function updateSchoolInfo()
    {
        // Validate input
        $school_name = $_POST['school_name'] ?? '';
        $school_address = $_POST['school_address'] ?? '';
        $school_logo = $_FILES['school_logo'] ?? null;
        $currency_symbol = $_POST['currency_symbol'] ?? 'GH₵';
        
        // Determine currency code based on symbol
        $currency_code = $this->currencyOptions[$currency_symbol] ?? 'GHS';

        // Prepare data for update
        $data = [
            'school_name' => $school_name,
            'school_address' => $school_address,
            'currency_code' => $currency_code,
            'currency_symbol' => $currency_symbol
        ];

        // Handle file upload if a new logo was provided
        if ($school_logo && $school_logo['error'] === UPLOAD_ERR_OK) {
            $uploadDir = ROOT_PATH . '/storage/uploads/';
            if (!is_dir($uploadDir)) {
                if (!mkdir($uploadDir, 0755, true)) {
                    $_SESSION['flash_error'] = 'Failed to create upload directory.';
                    return;
                }
            }
            
            // Validate file type
            $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
            $fileType = mime_content_type($school_logo['tmp_name']);
            if (!in_array($fileType, $allowedTypes)) {
                $_SESSION['flash_error'] = 'Invalid file type. Please upload a JPG, PNG, or GIF image.';
                return;
            }
            
            // Validate file size (max 2MB)
            if ($school_logo['size'] > 2 * 1024 * 1024) {
                $_SESSION['flash_error'] = 'File size too large. Please upload an image smaller than 2MB.';
                return;
            }
            
            $fileName = 'logo_' . time() . '_' . basename($school_logo['name']);
            $uploadPath = $uploadDir . $fileName;
            
            // Move uploaded file
            if (move_uploaded_file($school_logo['tmp_name'], $uploadPath)) {
                // Store relative path in database
                $data['school_logo'] = '/storage/uploads/' . $fileName;
            } else {
                $_SESSION['flash_error'] = 'Failed to upload logo file.';
                return;
            }
        } elseif (isset($_POST['remove_logo']) && $_POST['remove_logo'] === '1') {
            // Remove logo if requested
            $data['school_logo'] = null;
        }

        // Update settings in database
        $result = $this->settingModel->updateSettings($data);

        // Check if update was successful (not false) - 0 rows affected is still successful
        if ($result !== false) {
            $_SESSION['flash_success'] = 'School information updated successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to update school information.';
        }
    }

    private function updateWatermarkSettings()
    {
        // Validate input
        $watermark_type = $_POST['watermark_type'] ?? 'none';
        $watermark_position = $_POST['watermark_position'] ?? 'center';
        $watermark_transparency = (int)($_POST['watermark_transparency'] ?? 20);
        
        // Ensure transparency is between 0 and 100
        $watermark_transparency = max(0, min(100, $watermark_transparency));

        // Prepare data for update
        $data = [
            'watermark_type' => $watermark_type,
            'watermark_position' => $watermark_position,
            'watermark_transparency' => $watermark_transparency
        ];

        // Update settings in database
        $result = $this->settingModel->updateSettings($data);

        // Check if update was successful (not false) - 0 rows affected is still successful
        if ($result !== false) {
            $_SESSION['flash_success'] = 'Watermark settings updated successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to update watermark settings.';
        }
    }

    private function updateAutoGenerateSettings()
    {
        // Validate input
        $student_admission_prefix = $_POST['student_admission_prefix'] ?? 'EPI';
        $staff_employee_prefix = $_POST['staff_employee_prefix'] ?? 'StID';

        // Prepare data for update
        $data = [
            'student_admission_prefix' => $student_admission_prefix,
            'staff_employee_prefix' => $staff_employee_prefix
        ];

        // Update settings in database
        $result = $this->settingModel->updateSettings($data);

        if ($result !== false) {
            $_SESSION['flash_success'] = 'Auto-generate settings updated successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to update auto-generate settings.';
        }
    }

    private function updateAcademicSettings()
    {
        // Validate input
        $academic_year_future_limit = (int)($_POST['academic_year_future_limit'] ?? 10);
        
        // Prepare data for update
        $data = [
            'academic_year_future_limit' => $academic_year_future_limit
        ];

        // Update settings in database
        $result = $this->settingModel->updateSettings($data);

        // Check if update was successful (not false) - 0 rows affected is still successful
        if ($result !== false) {
            $_SESSION['flash_success'] = 'Academic settings updated successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to update academic settings.';
        }
    }

    public function getSchoolName()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }

        // Get school settings
        $settings = $this->settingModel->getSettings();
        
        $this->jsonResponse([
            'success' => true,
            'school_name' => $settings['school_name'] ?? 'APSA-ERP',
            'school_logo' => $settings['school_logo'] ?? null
        ]);
    }
    
    // Handle grading scale creation
    public function createGradingScale()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        // Validate input
        $name = $_POST['scale_name'] ?? '';
        $gradingType = $_POST['grading_type'] ?? 'numeric';

        if (empty($name)) {
            $_SESSION['flash_error'] = 'Grading scale name is required.';
            $this->redirect('/settings');
            return;
        }

        // Create grading scale
        $data = [
            'name' => $name,
            'grading_type' => $gradingType
        ];

        $scaleId = $this->gradingScaleModel->create($data);

        if ($scaleId) {
            $_SESSION['flash_success'] = 'Grading scale created successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to create grading scale.';
        }

        $this->redirect('/settings');
    }
    
    // Handle grading rule creation
    public function createGradingRule()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        // Validate input
        $scaleId = $_POST['scale_id'] ?? '';
        $minScore = $_POST['min_score'] ?? 0;
        $maxScore = $_POST['max_score'] ?? 0;
        $grade = $_POST['grade'] ?? '';
        $remark = $_POST['remark'] ?? '';

        if (empty($scaleId) || empty($grade)) {
            $_SESSION['flash_error'] = 'Scale and grade are required.';
            $this->redirect('/settings');
            return;
        }

        // Validate score range
        if ($minScore > $maxScore) {
            $_SESSION['flash_error'] = 'Minimum score cannot be greater than maximum score.';
            $this->redirect('/settings');
            return;
        }

        // Create grading rule
        $data = [
            'scale_id' => $scaleId,
            'min_score' => $minScore,
            'max_score' => $maxScore,
            'grade' => $grade,
            'remark' => $remark
        ];

        $ruleId = $this->gradingRuleModel->create($data);

        if ($ruleId) {
            $_SESSION['flash_success'] = 'Grading rule created successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to create grading rule.';
        }

        $this->redirect('/settings');
    }
    
    // Handle grading scale deletion
    public function deleteGradingScale($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        // Delete grading scale (will cascade delete rules)
        $result = $this->gradingScaleModel->delete($id);

        if ($result) {
            $_SESSION['flash_success'] = 'Grading scale deleted successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to delete grading scale.';
        }

        $this->redirect('/settings');
    }
    
    // Handle grading rule deletion
    public function deleteGradingRule($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }

        // Delete grading rule
        $result = $this->gradingRuleModel->delete($id);

        if ($result) {
            $_SESSION['flash_success'] = 'Grading rule deleted successfully.';
        } else {
            $_SESSION['flash_error'] = 'Failed to delete grading rule.';
        }

        $this->redirect('/settings');
    }
    
    // New method to generate admission number
    public function generateAdmissionNumber()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }
        
        // Get settings to get the prefix
        $settings = $this->settingModel->getSettings();
        $prefix = $settings['student_admission_prefix'] ?? 'EPI';
        
        // Generate admission number in format: [Prefix][HHMMSS]
        $timestamp = date('His'); // HHMMSS format
        $admissionNumber = $prefix . '-' . $timestamp;
        
        // Return the generated number
        echo json_encode(['admission_number' => $admissionNumber]);
    }
    
    // New method to generate employee ID
    public function generateEmployeeId()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }
        
        // Get settings to get the prefix
        $settings = $this->settingModel->getSettings();
        $prefix = $settings['staff_employee_prefix'] ?? 'StID';
        
        // Generate employee ID in format: [Prefix][HHMMSS]
        $timestamp = date('His'); // HHMMSS format
        $employeeId = $prefix . '-' . $timestamp;
        
        // Return the generated ID
        echo json_encode(['employee_id' => $employeeId]);
    }
    
    // Show import form (only for super admins)
    public function showImportForm()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }
        
        // Load settings from database
        $settings = $this->settingModel->getSettings();
        
        // If no settings found, use defaults
        if (!$settings) {
            $settings = [
                'school_name' => 'APSA-ERP',
                'school_logo' => null,
                'currency_code' => 'GHS',
                'currency_symbol' => 'GH₵',
                'watermark_type' => 'none',
                'watermark_position' => 'center',
                'watermark_transparency' => 20,
                'student_admission_prefix' => 'EPI',
                'staff_employee_prefix' => 'StID'
            ];
        }
        
        // Load grading scales
        $gradingScales = $this->gradingScaleModel->getAllWithRules();
        
        // Get all classes for class import
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        // Check if user has super admin role
        $isSuperAdmin = $this->hasAnyRole(['admin', 'super_admin']);
        
        $this->view('settings/import', [
            'settings' => $settings,
            'gradingScales' => $gradingScales,
            'classes' => $classes,
            'isSuperAdmin' => $isSuperAdmin
        ]);
    }
    
    // Import students from CSV/Excel
    public function importStudents()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }
        
        if ($this->requestMethod() !== 'POST') {
            $this->redirect('/settings/import');
        }
        
        // Check if file was uploaded
        if (!isset($_FILES['students_file']) || $_FILES['students_file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = 'Please select a file to import.';
            $this->redirect('/settings/import');
        }
        
        $file = $_FILES['students_file'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Validate file type
        if (!in_array($fileExtension, ['csv', 'xls', 'xlsx'])) {
            $_SESSION['flash_error'] = 'Only CSV, XLS, and XLSX files are allowed.';
            $this->redirect('/settings/import');
        }
        
        // Process the file
        $studentsData = $this->parseStudentFile($file, $fileExtension);
        
        if ($studentsData === false) {
            $_SESSION['flash_error'] = 'Failed to parse the file. Please check the file format.';
            $this->redirect('/settings/import');
        }
        
        // If preview is requested, show the data
        if (isset($_POST['preview'])) {
            $this->view('settings/import_preview', [
                'data' => $studentsData,
                'type' => 'students',
                'settings' => $this->settingModel->getSettings(),
                'isSuperAdmin' => $this->hasAnyRole(['admin', 'super_admin'])
            ]);
            return;
        }
        
        // Import the data
        $studentModel = new Student();
        $importedCount = 0;
        $errors = [];
        
        foreach ($studentsData as $index => $studentData) {
            try {
                // Validate required fields
                if (empty($studentData['first_name']) || empty($studentData['last_name'])) {
                    $errors[] = "Row " . ($index + 1) . ": First name and last name are required.";
                    continue;
                }
                
                // Create student
                $studentId = $studentModel->create($studentData);
                
                if ($studentId) {
                    $importedCount++;
                } else {
                    $errors[] = "Row " . ($index + 1) . ": Failed to import student.";
                }
            } catch (Exception $e) {
                $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
            }
        }
        
        if ($importedCount > 0) {
            $_SESSION['flash_success'] = "$importedCount students imported successfully.";
        }
        
        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode("\n", $errors);
        }
        
        $this->redirect('/settings/import');
    }
    
    // Import classes from CSV/Excel
    public function importClasses()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }
        
        if ($this->requestMethod() !== 'POST') {
            $this->redirect('/settings/import');
        }
        
        // Check if file was uploaded
        if (!isset($_FILES['classes_file']) || $_FILES['classes_file']['error'] !== UPLOAD_ERR_OK) {
            $_SESSION['flash_error'] = 'Please select a file to import.';
            $this->redirect('/settings/import');
        }
        
        $file = $_FILES['classes_file'];
        $fileExtension = strtolower(pathinfo($file['name'], PATHINFO_EXTENSION));
        
        // Validate file type
        if (!in_array($fileExtension, ['csv', 'xls', 'xlsx'])) {
            $_SESSION['flash_error'] = 'Only CSV, XLS, and XLSX files are allowed.';
            $this->redirect('/settings/import');
        }
        
        // Process the file
        $classesData = $this->parseClassFile($file, $fileExtension);
        
        if ($classesData === false) {
            $_SESSION['flash_error'] = 'Failed to parse the file. Please check the file format.';
            $this->redirect('/settings/import');
        }
        
        // If preview is requested, show the data
        if (isset($_POST['preview'])) {
            $this->view('settings/import_preview', [
                'data' => $classesData,
                'type' => 'classes',
                'settings' => $this->settingModel->getSettings(),
                'isSuperAdmin' => $this->hasAnyRole(['admin', 'super_admin'])
            ]);
            return;
        }
        
        // Import the data
        $classModel = new ClassModel();
        $importedCount = 0;
        $errors = [];
        
        foreach ($classesData as $index => $classData) {
            try {
                // Validate required fields
                if (empty($classData['name']) || empty($classData['level'])) {
                    $errors[] = "Row " . ($index + 1) . ": Class name and level are required.";
                    continue;
                }
                
                // Create class
                $classId = $classModel->create($classData);
                
                if ($classId) {
                    $importedCount++;
                } else {
                    $errors[] = "Row " . ($index + 1) . ": Failed to import class.";
                }
            } catch (Exception $e) {
                $errors[] = "Row " . ($index + 1) . ": " . $e->getMessage();
            }
        }
        
        if ($importedCount > 0) {
            $_SESSION['flash_success'] = "$importedCount classes imported successfully.";
        }
        
        if (!empty($errors)) {
            $_SESSION['flash_error'] = implode("\n", $errors);
        }
        
        $this->redirect('/settings/import');
    }
    
    // Parse student file (CSV, XLS, XLSX)
    private function parseStudentFile($file, $extension)
    {
        try {
            if ($extension === 'csv') {
                return $this->parseCSV($file['tmp_name']);
            } else {
                return $this->parseExcel($file['tmp_name']);
            }
        } catch (Exception $e) {
            error_log("Error parsing student file: " . $e->getMessage());
            return false;
        }
    }
    
    // Parse class file (CSV, XLS, XLSX)
    private function parseClassFile($file, $extension)
    {
        try {
            if ($extension === 'csv') {
                return $this->parseCSV($file['tmp_name']);
            } else {
                return $this->parseExcel($file['tmp_name']);
            }
        } catch (Exception $e) {
            error_log("Error parsing class file: " . $e->getMessage());
            return false;
        }
    }
    
    // Parse CSV file
    private function parseCSV($filePath)
    {
        $data = [];
        
        if (($handle = fopen($filePath, 'r')) !== false) {
            // Get headers
            $headers = fgetcsv($handle);
            
            // Process rows
            while (($row = fgetcsv($handle)) !== false) {
                if (count($row) === count($headers)) {
                    $data[] = array_combine($headers, $row);
                }
            }
            
            fclose($handle);
        }
        
        return $data;
    }
    
    // Parse Excel file (XLS, XLSX)
    private function parseExcel($filePath)
    {
        try {
            // Load the Excel file
            $spreadsheet = IOFactory::load($filePath);
            $worksheet = $spreadsheet->getActiveSheet();
            
            // Get the highest row and column
            $highestRow = $worksheet->getHighestRow();
            $highestColumn = $worksheet->getHighestColumn();
            
            // Convert column to number
            $highestColumnIndex = \PhpOffice\PhpSpreadsheet\Cell\Coordinate::columnIndexFromString($highestColumn);
            
            // Get headers from the first row
            $headers = [];
            for ($col = 1; $col <= $highestColumnIndex; $col++) {
                $cellValue = $worksheet->getCellByColumnAndRow($col, 1)->getValue();
                $headers[] = $cellValue;
            }
            
            // Process data rows
            $data = [];
            for ($row = 2; $row <= $highestRow; $row++) {
                $rowData = [];
                for ($col = 1; $col <= $highestColumnIndex; $col++) {
                    $cellValue = $worksheet->getCellByColumnAndRow($col, $row)->getValue();
                    $rowData[$headers[$col - 1]] = $cellValue;
                }
                $data[] = $rowData;
            }
            
            return $data;
        } catch (Exception $e) {
            error_log("Error parsing Excel file: " . $e->getMessage());
            return false;
        }
    }
    
    // Download sample student CSV
    public function downloadStudentSample()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="student_import_sample.csv"');
        
        // Output CSV
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['admission_no', 'first_name', 'last_name', 'dob', 'gender', 'class_id', 'guardian_name', 'guardian_phone', 'address', 'student_category', 'admission_date', 'academic_year_id']);
        
        // Add sample data
        fputcsv($output, ['STU001', 'John', 'Doe', '2010-01-15', 'Male', '1', 'Jane Doe', '1234567890', '123 Main St', 'regular_day', '2023-09-01', '1']);
        fputcsv($output, ['STU002', 'Jane', 'Smith', '2010-05-20', 'Female', '2', 'John Smith', '0987654321', '456 Oak Ave', 'regular_boarding', '2023-09-01', '1']);
        
        fclose($output);
        exit();
    }
    
    // Download sample class CSV
    public function downloadClassSample()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="class_import_sample.csv"');
        
        // Output CSV
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['name', 'level', 'capacity']);
        
        // Add sample data
        fputcsv($output, ['Grade 1', 'Primary', '30']);
        fputcsv($output, ['Grade 2', 'Primary', '30']);
        fputcsv($output, ['Grade 3', 'Primary', '30']);
        
        fclose($output);
        exit();
    }
    
    // Show system wipe form (only for super admins)
    public function showWipeForm()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }
        
        // Load settings from database
        $settings = $this->settingModel->getSettings();
        
        // If no settings found, use defaults
        if (!$settings) {
            $settings = [
                'school_name' => 'APSA-ERP',
                'school_logo' => null,
                'currency_code' => 'GHS',
                'currency_symbol' => 'GH₵',
                'watermark_type' => 'none',
                'watermark_position' => 'center',
                'watermark_transparency' => 20,
                'student_admission_prefix' => 'EPI',
                'staff_employee_prefix' => 'StID'
            ];
        }
        
        // Load grading scales
        $gradingScales = $this->gradingScaleModel->getAllWithRules();
        
        // Check if user has super admin role
        $isSuperAdmin = $this->hasAnyRole(['admin', 'super_admin']);
        
        $this->view('settings/wipe', [
            'settings' => $settings,
            'gradingScales' => $gradingScales,
            'isSuperAdmin' => $isSuperAdmin
        ]);
    }
    
    // Perform system wipe (only for super admins)
    public function performWipe()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->redirect('/dashboard');
        }
        
        if ($this->requestMethod() !== 'POST') {
            $this->redirect('/settings/wipe');
        }
        
        // Get the sections to wipe
        $sections = $_POST['sections'] ?? [];
        
        if (empty($sections)) {
            $_SESSION['flash_error'] = 'Please select at least one section to wipe.';
            $this->redirect('/settings/wipe');
        }
        
        // Confirm the action with a special parameter
        if (!isset($_POST['confirm_wipe']) || $_POST['confirm_wipe'] !== 'yes') {
            $_SESSION['flash_error'] = 'Please confirm the wipe action by checking the confirmation box.';
            $this->redirect('/settings/wipe');
        }
        
        $wipeResults = [];
        
        try {
            // Disable foreign key checks to prevent constraint violations during wipe
            $this->settingModel->executeRaw("SET FOREIGN_KEY_CHECKS = 0");

            // Wipe selected sections
            foreach ($sections as $section) {
                $result = $this->wipeSection($section);
                $wipeResults[] = "$section: " . ($result ? 'Success' : 'Failed');
            }
            
            // Re-enable foreign key checks
            $this->settingModel->executeRaw("SET FOREIGN_KEY_CHECKS = 1");
            
            $_SESSION['flash_success'] = "System wipe completed successfully.<br>" . implode("<br>", $wipeResults);
        } catch (Exception $e) {
            $_SESSION['flash_error'] = 'Error during system wipe: ' . $e->getMessage();
        }
        
        $this->redirect('/settings/wipe');
    }
    
    // Wipe a specific section of the system
    private function wipeSection($section)
    {
        switch ($section) {
            case 'users':
                // Wipe users data (EXCEPT current user)
                $userModel = new \App\Models\User();
                $currentUserId = $_SESSION['user']['id'];
                
                // Use a prepared statement for safety, though executeRaw might take raw SQL
                // If executeRaw doesn't support params, we need to be careful.
                // Assuming executeRaw just runs the query.
                // Safe if $currentUserId is integer from session.
                $currentUserId = (int)$currentUserId;
                
                $sql = "DELETE FROM users WHERE id != $currentUserId";
                return $userModel->executeRaw($sql);

            case 'students':
                // Wipe student data
                $studentModel = new Student();
                $sql = "DELETE FROM students";
                return $studentModel->executeRaw($sql);
                
            case 'staff':
                // Wipe staff data
                $staffModel = new \App\Models\Staff();
                $sql = "DELETE FROM staff";
                return $staffModel->executeRaw($sql);
                
            case 'subjects':
                // Wipe subject data
                $subjectModel = new \App\Models\Subject();
                $sql = "DELETE FROM subjects";
                return $subjectModel->executeRaw($sql);
                
            case 'classes':
                // Wipe class data
                $classModel = new ClassModel();
                $sql = "DELETE FROM classes";
                return $classModel->executeRaw($sql);
                
            case 'fees':
                // Wipe fee data
                $feeModel = new \App\Models\Fee();
                $sql = "DELETE FROM fees";
                return $feeModel->executeRaw($sql);
                
            case 'payments':
                // Wipe payment data
                $paymentModel = new \App\Models\Payment();
                $sql = "DELETE FROM payments";
                return $paymentModel->executeRaw($sql);
                
            case 'exams':
                // Wipe exam data (and related exam results)
                $examResultModel = new \App\Models\ExamResult();
                $examModel = new \App\Models\Exam();
                
                // Delete exam results first
                $sql1 = "DELETE FROM exam_results";
                $examResultModel->executeRaw($sql1);
                
                // Then delete exams
                $sql2 = "DELETE FROM exams";
                return $examModel->executeRaw($sql2);
                
            case 'attendance':
                // Wipe attendance data
                $attendanceModel = new \App\Models\Attendance();
                $sql = "DELETE FROM attendance";
                return $attendanceModel->executeRaw($sql);
                
            case 'timetables':
                // Wipe timetable data
                $timetableModel = new \App\Models\Timetable();
                $sql = "DELETE FROM timetables";
                return $timetableModel->executeRaw($sql);
                
            case 'reports':
                // Wipe report card settings
                $reportCardSettingModel = new \App\Models\ReportCardSetting();
                $sql = "DELETE FROM report_card_settings";
                return $reportCardSettingModel->executeRaw($sql);
                
            case 'academic_years':
                // Wipe academic years (but keep the structure)
                $academicYearModel = new \App\Models\AcademicYear();
                $sql = "DELETE FROM academic_years";
                return $academicYearModel->executeRaw($sql);
                
            case 'notifications':
                // Wipe notifications
                $notificationModel = new \App\Models\Notification();
                $sql = "DELETE FROM notifications";
                return $notificationModel->executeRaw($sql);
                
            case 'audit_logs':
                // Wipe audit logs
                $auditLogModel = new \App\Models\AuditLog();
                $sql = "DELETE FROM audit_logs";
                return $auditLogModel->executeRaw($sql);
                
            default:
                return false;
        }
    }
}