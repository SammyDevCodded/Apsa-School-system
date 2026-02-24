<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\ReportCardSetting;
use App\Models\Setting;
use App\Models\GradingScale;
use App\Models\GradingRule;

class ReportCardController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Check if user is Super Admin
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->flash('error', 'Access denied. Only Super Admins can access this page.');
            $this->redirect('/dashboard');
        }
        
        $reportCardSettingModel = new ReportCardSetting();
        $settings = $reportCardSettingModel->getSettings();
        
        $settingModel = new Setting();
        $generalSettings = $settingModel->getSettings();
        
        // Get sample grading scale for preview
        $gradingScale = $this->getSampleGradingScale();
        
        $this->view('report_cards/settings', [
            'reportCardSettings' => $settings,
            'generalSettings' => $generalSettings,
            'gradingScale' => $gradingScale
        ]);
    }
    
    public function update()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Check if user is Super Admin
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->flash('error', 'Access denied. Only Super Admins can access this page.');
            $this->redirect('/dashboard');
        }
        
        if ($this->requestMethod() === 'POST' || $this->requestMethod() === 'PUT') {
            // Get the custom school address value
            $customSchoolAddress = $this->post('custom_school_address', null);
            
            // If the custom address is empty, set it to null to clear it from the database
            if (empty($customSchoolAddress)) {
                $customSchoolAddress = null;
            }
            
            $data = [
                'logo_position' => $this->post('logo_position', 'top-left'),
                'show_school_name' => $this->post('show_school_name', false) ? 1 : 0,
                'show_school_address' => $this->post('show_school_address', false) ? 1 : 0,
                'custom_school_address' => $customSchoolAddress,
                'show_school_logo' => $this->post('show_school_logo', false) ? 1 : 0,
                'show_student_photo' => $this->post('show_student_photo', false) ? 1 : 0,
                'show_grading_scale' => $this->post('show_grading_scale', false) ? 1 : 0,
                'show_attendance' => $this->post('show_attendance', false) ? 1 : 0,
                'show_comments' => $this->post('show_comments', false) ? 1 : 0,
                'show_signatures' => $this->post('show_signatures', false) ? 1 : 0,
                'header_font_size' => (int) $this->post('header_font_size', 16),
                'body_font_size' => (int) $this->post('body_font_size', 12),
                'show_date_of_birth' => $this->post('show_date_of_birth', false) ? 1 : 0,
                'show_class_score' => $this->post('show_class_score', false) ? 1 : 0,
                'show_teacher_signature' => $this->post('show_teacher_signature', false) ? 1 : 0,
                'show_headteacher_signature' => $this->post('show_headteacher_signature', false) ? 1 : 0,
                'show_parent_signature' => $this->post('show_parent_signature', false) ? 1 : 0,
                'show_exam_score' => $this->post('show_exam_score', false) ? 1 : 0,
                'show_position' => $this->post('show_position', false) ? 1 : 0
            ];

            // Handle Headteacher Signature Upload
            if (isset($_FILES['headteacher_signature']) && $_FILES['headteacher_signature']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = PUBLIC_PATH . '/uploads/signatures/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileInfo = pathinfo($_FILES['headteacher_signature']['name']);
                $extension = strtolower($fileInfo['extension']);
                
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $fileName = 'headteacher_sig_' . time() . '.' . $extension;
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['headteacher_signature']['tmp_name'], $targetPath)) {
                        $data['headteacher_signature'] = '/uploads/signatures/' . $fileName;
                    }
                }
            }

            // Handle Teacher Signature Upload
            if (isset($_FILES['teacher_signature']) && $_FILES['teacher_signature']['error'] === UPLOAD_ERR_OK) {
                $uploadDir = PUBLIC_PATH . '/uploads/signatures/';
                if (!is_dir($uploadDir)) {
                    mkdir($uploadDir, 0755, true);
                }
                
                $fileInfo = pathinfo($_FILES['teacher_signature']['name']);
                $extension = strtolower($fileInfo['extension']);
                
                if (in_array($extension, ['jpg', 'jpeg', 'png', 'gif'])) {
                    $fileName = 'teacher_sig_' . time() . '.' . $extension;
                    $targetPath = $uploadDir . $fileName;
                    
                    if (move_uploaded_file($_FILES['teacher_signature']['tmp_name'], $targetPath)) {
                        $data['teacher_signature'] = '/uploads/signatures/' . $fileName;
                    }
                }
            }
            
            $reportCardSettingModel = new ReportCardSetting();
            $result = $reportCardSettingModel->updateSettings($data);
            
            // The update method returns the number of affected rows, which can be 0 if no changes were made
            // We consider it successful if it's not false (which indicates an error)
            if ($result !== false) {
                $this->flash('success', 'Report card settings updated successfully.');
            } else {
                $this->flash('error', 'Failed to update report card settings.');
            }
            
            $this->redirect('/report-cards/settings');
        } else {
            $this->index();
        }
    }
    
    public function sample()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Check if user is Super Admin
        if (!$this->hasAnyRole(['admin', 'super_admin'])) {
            $this->flash('error', 'Access denied. Only Super Admins can access this page.');
            $this->redirect('/dashboard');
        }
        
        $reportCardSettingModel = new ReportCardSetting();
        $settings = $reportCardSettingModel->getSettings();
        
        $settingModel = new Setting();
        $generalSettings = $settingModel->getSettings();
        
        // Get sample grading scale for preview
        $gradingScale = $this->getSampleGradingScale();
        
        $this->view('report_cards/sample', [
            'settings' => $settings,
            'generalSettings' => $generalSettings,
            'gradingScale' => $gradingScale
        ]);
    }
    
    public function print()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }

        $studentId = $this->get('student_id');
        $examIds = $this->get('exam_id'); // Can be string "1,2,3"

        if (!$studentId || !$examIds) {
            $this->flash('error', 'Invalid Student or Exam IDs.');
            $this->redirect('/reports/academic');
            return;
        }

        // 1. Get Settings
        $reportCardSettingModel = new ReportCardSetting();
        $settings = $reportCardSettingModel->getSettings();
        
        $settingModel = new Setting();
        $generalSettings = $settingModel->getSettings();

        // 2. Get Student Data
        $studentModel = new \App\Models\Student();
        $studentData = $studentModel->find($studentId);
        
        if (!$studentData) {
            die("Student not found.");
        }
        
        // Get Class Name for Student
        $classModel = new \App\Models\SchoolClass();
        $classData = $classModel->find($studentData['class_id']);
        $className = $classData ? ($classData['name'] . ' ' . $classData['level']) : 'N/A';

        // 3. Get Student Results
        $examResultModel = new \App\Models\ExamResult();
        $examResults = $examResultModel->getStudentExamResults($studentId, $examIds);

        if (empty($examResults)) {
             die("No results found for this student and exam.");
        }

        // 4. Calculate Rank
        // Fetch all students ranked for this exam set
        $rankedResults = $examResultModel->getRankedResults(['exam_id' => $examId]);
        
        $rank = '-';
        $rankSuffix = '';
        foreach ($rankedResults as $index => $row) {
            if ($row['student_id'] == $studentId) {
                $rank = $index + 1;
                $rankSuffix = $this->ordinal_suffix($rank);
                $rank = $rank . $rankSuffix;
                break;
            }
        }

        // 5. Prepare View Data
        // Use first result for common metadata (Year, Term)
        $firstResult = $examResults[0];
        
        $student = [
            'name' => $studentData['first_name'] . ' ' . $studentData['last_name'],
            'admission_no' => $studentData['admission_no'],
            'dob' => $studentData['dob'] ? date('M j, Y', strtotime($studentData['dob'])) : '',
            'class' => $className
        ];

        $examDetails = [
            'academic_year' => $firstResult['academic_year_name'] ?? 'N/A',
            'term' => $firstResult['exam_term'] ?? 'N/A',
            'rank' => $rank
        ];

        // Format results for view loop
        $formattedResults = [];
        $totalScore = 0;
        foreach ($examResults as $res) {
            $formattedResults[] = [
                'subject' => $res['subject_name'],
                'class_score' => $res['classwork_marks'],
                'exam_score' => $res['exam_marks'],
                'total_score' => $res['marks'], // or final_marks
                'grade' => $res['grade'],
                'remark' => $res['remark']
            ];
        }

        // Attendance (Mock for now, or fetch if available)
        $attendance = [
            'total' => '-',
            'present' => '-',
            'absent' => '-'
        ];
        
        // Get Grading Scale (Default or specific)
        $gradingScale = $this->getSampleGradingScale();

        $this->view('report_cards/sample', [
            'settings' => $settings,
            'generalSettings' => $generalSettings,
            'gradingScale' => $gradingScale,
            'isSample' => false,
            'student' => $student,
            'examDetails' => $examDetails,
            'examResults' => $formattedResults,
            'attendance' => $attendance,
            'comments' => '' // Can be dynamic if comments exist in DB
        ]);
    }

    private function ordinal_suffix($num) {
        $num = $num % 100; // protect against large numbers
        if ($num < 11 || $num > 13) {
            switch ($num % 10) {
                case 1: return 'st';
                case 2: return 'nd';
                case 3: return 'rd';
            }
        }
        return 'th';
    }

    // Helper method to get a sample grading scale for preview
    private function getSampleGradingScale()
    {
        $gradingScaleModel = new GradingScale();
        $gradingRuleModel = new GradingRule();
        
        // Get the first available grading scale
        $scales = $gradingScaleModel->getAllWithRules();
        
        if (!empty($scales)) {
            return $scales[0]; // Return the first scale with its rules
        }
        
        // If no scales exist, return a default one
        return [
            'id' => 1,
            'name' => 'Default Grading Scale',
            'grading_type' => 'letter',
            'rules' => [
                ['grade' => 'A', 'min_score' => 80, 'max_score' => 100, 'remark' => 'Excellent'],
                ['grade' => 'B', 'min_score' => 70, 'max_score' => 79, 'remark' => 'Very Good'],
                ['grade' => 'C', 'min_score' => 60, 'max_score' => 69, 'remark' => 'Good'],
                ['grade' => 'D', 'min_score' => 50, 'max_score' => 59, 'remark' => 'Satisfactory'],
                ['grade' => 'F', 'min_score' => 0, 'max_score' => 49, 'remark' => 'Fail']
            ]
        ];
    }
}