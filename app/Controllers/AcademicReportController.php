<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Exam;
use App\Models\ClassModel;
use App\Models\Student;
use App\Models\ExamResult;
use App\Models\ReportCardSetting;
use App\Models\Setting;
use App\Helpers\AcademicYearHelper;
use Dompdf\Dompdf;
use Dompdf\Options;

class AcademicReportController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $examModel = new Exam();
        $exams = $examModel->getAllWithClass();
        
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        $this->view('academic_reports/index', [
            'exams' => $exams,
            'classes' => $classes
        ]);
    }
    
    public function getClassesByExam()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        if ($this->requestMethod() !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method'], 400);
            return;
        }
        
        $examId = (int) $this->post('exam_id');
        
        if (!$examId) {
            $this->jsonResponse(['error' => 'Exam ID is required'], 400);
            return;
        }
        
        try {
            $examModel = new Exam();
            $classes = $examModel->getClassesByExam($examId);
            
            $this->jsonResponse(['classes' => $classes]);
        } catch (\Exception $e) {
            error_log("Error in getClassesByExam: " . $e->getMessage());
            $this->jsonResponse(['error' => 'Error loading classes: ' . $e->getMessage()], 500);
        }
    }
    
    public function getStudentsByClassAndExam()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        if ($this->requestMethod() !== 'POST') {
            $this->jsonResponse(['error' => 'Invalid request method'], 400);
            return;
        }
        
        $examId = (int) $this->post('exam_id');
        $classId = (int) $this->post('class_id');
        
        if (!$examId || !$classId) {
            $this->jsonResponse(['error' => 'Exam ID and Class ID are required'], 400);
            return;
        }
        
        try {
            $studentModel = new Student();
            $students = $studentModel->getStudentsByClass($classId);
            
            // Get exam results for this exam and class to check submission status
            $examResultModel = new ExamResult();
            $results = $examResultModel->getByExamAndClass($examId, $classId);
            
            // Group results by student for easier checking
            $resultsByStudent = [];
            foreach ($results as $result) {
                $resultsByStudent[$result['student_id']][] = $result;
            }
            
            // Add submission status to students
            foreach ($students as &$student) {
                $student['has_results'] = isset($resultsByStudent[$student['id']]);
                $student['results_count'] = isset($resultsByStudent[$student['id']]) ? count($resultsByStudent[$student['id']]) : 0;
            }
            
            $this->jsonResponse(['students' => $students]);
        } catch (\Exception $e) {
            error_log("Error in getStudentsByClassAndExam: " . $e->getMessage());
            $this->jsonResponse(['error' => 'Error loading students: ' . $e->getMessage()], 500);
        }
    }
    
    public function preview()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        if ($this->requestMethod() !== 'POST') {
            $this->redirect('/academic-reports');
            return;
        }
        
        $examId = (int) $this->post('exam_id');
        $classId = (int) $this->post('class_id');
        $studentIds = $this->post('student_ids', []);
        
        if (!$examId || !$classId || empty($studentIds)) {
            $this->flash('error', 'Please select an exam, class, and at least one student.');
            $this->redirect('/academic-reports');
            return;
        }
        
        $examModel = new Exam();
        $exam = $examModel->find($examId);
        
        $classModel = new ClassModel();
        $class = $classModel->find($classId);
        
        $studentModel = new Student();
        $students = [];
        foreach ($studentIds as $studentId) {
            $student = $studentModel->find($studentId);
            if ($student) {
                $students[] = $student;
            }
        }
        
        // Get exam results for selected students
        $examResultModel = new ExamResult();
        $results = $examResultModel->getByExamAndClass($examId, $classId);
        
        // Group results by student
        $resultsByStudent = [];
        foreach ($results as $result) {
            if (in_array($result['student_id'], $studentIds)) {
                $resultsByStudent[$result['student_id']][] = $result;
            }
        }
        
        // Get report card settings
        $reportCardSettingModel = new ReportCardSetting();
        $settings = $reportCardSettingModel->getSettings();
        
        $settingModel = new Setting();
        $generalSettings = $settingModel->getSettings();
        
        // Get current academic year
        $currentAcademicYear = AcademicYearHelper::getCurrentAcademicYearWithTerm();
        
        // Get grading scale used for this exam
        $gradingScale = $this->getGradingScaleForExam($examId);
        
        $this->view('academic_reports/preview', [
            'exam' => $exam,
            'class' => $class,
            'students' => $students,
            'resultsByStudent' => $resultsByStudent,
            'settings' => $settings,
            'generalSettings' => $generalSettings,
            'currentAcademicYear' => $currentAcademicYear,
            'gradingScale' => $gradingScale
        ]);
    }
    
    public function print()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $examId = (int) $this->get('exam_id');
        $classId = (int) $this->get('class_id');
        $studentIds = explode(',', $this->get('student_ids'));
        
        if (!$examId || !$classId || empty($studentIds)) {
            $this->flash('error', 'Invalid parameters for printing.');
            $this->redirect('/academic-reports');
            return;
        }
        
        $examModel = new Exam();
        $exam = $examModel->find($examId);
        
        $classModel = new ClassModel();
        $class = $classModel->find($classId);
        
        $studentModel = new Student();
        $students = [];
        foreach ($studentIds as $studentId) {
            $student = $studentModel->find($studentId);
            if ($student) {
                $students[] = $student;
            }
        }
        
        // Get exam results for selected students
        $examResultModel = new ExamResult();
        $results = $examResultModel->getByExamAndClass($examId, $classId);
        
        // Group results by student
        $resultsByStudent = [];
        foreach ($results as $result) {
            if (in_array($result['student_id'], $studentIds)) {
                $resultsByStudent[$result['student_id']][] = $result;
            }
        }
        
        // Get report card settings
        $reportCardSettingModel = new ReportCardSetting();
        $settings = $reportCardSettingModel->getSettings();
        
        $settingModel = new Setting();
        $generalSettings = $settingModel->getSettings();
        
        // Get current academic year
        $currentAcademicYear = AcademicYearHelper::getCurrentAcademicYearWithTerm();
        
        // Get grading scale used for this exam
        $gradingScale = $this->getGradingScaleForExam($examId);
        
        // Calculate Rank
        // Use perPage = 0 to get all results without pagination
        $rankedResultsData = $examResultModel->getRankedResults(['exam_id' => $examId, 'class_id' => $classId], 1, 0);
        $rankedResults = $rankedResultsData['data'] ?? [];
        $studentRanks = [];
        
        foreach ($rankedResults as $index => $row) {
            $currentRank = $index + 1;
            // Add ordinal suffix (st, nd, rd, th)
            $suffixes = ["th", "st", "nd", "rd", "th", "th", "th", "th", "th", "th"];
            $suffix = "";
            if (($currentRank % 100) >= 11 && ($currentRank % 100) <= 13) {
                $suffix = "th";
            } else {
                $suffix = $suffixes[$currentRank % 10] ?? "th";
            }
            
            $studentRanks[$row['student_id']] = $currentRank . $suffix;
        }

        $this->view('academic_reports/print', [
            'exam' => $exam,
            'class' => $class,
            'students' => $students,
            'resultsByStudent' => $resultsByStudent,
            'studentRanks' => $studentRanks,
            'settings' => $settings,
            'generalSettings' => $generalSettings,
            'currentAcademicYear' => $currentAcademicYear,
            'gradingScale' => $gradingScale
        ]);
    }
    
    public function exportPdf()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $examId = (int) $this->get('exam_id');
        $classId = (int) $this->get('class_id');
        $studentIds = explode(',', $this->get('student_ids'));
        
        if (!$examId || !$classId || empty($studentIds)) {
            $this->flash('error', 'Invalid parameters for exporting.');
            $this->redirect('/academic-reports');
            return;
        }
        
        $examModel = new Exam();
        $exam = $examModel->find($examId);
        
        $classModel = new ClassModel();
        $class = $classModel->find($classId);
        
        $studentModel = new Student();
        $students = [];
        foreach ($studentIds as $studentId) {
            $student = $studentModel->find($studentId);
            if ($student) {
                $students[] = $student;
            }
        }
        
        // Get exam results for selected students
        $examResultModel = new ExamResult();
        $results = $examResultModel->getByExamAndClass($examId, $classId);
        
        // Group results by student
        $resultsByStudent = [];
        foreach ($results as $result) {
            if (in_array($result['student_id'], $studentIds)) {
                $resultsByStudent[$result['student_id']][] = $result;
            }
        }
        
        // Get report card settings
        $reportCardSettingModel = new ReportCardSetting();
        $settings = $reportCardSettingModel->getSettings();
        
        $settingModel = new Setting();
        $generalSettings = $settingModel->getSettings();
        
        // Get current academic year
        $currentAcademicYear = AcademicYearHelper::getCurrentAcademicYearWithTerm();
        
        // Get grading scale used for this exam
        $gradingScale = $this->getGradingScaleForExam($examId);
        
        // Calculate Rank
        // Use perPage = 0 to get all results without pagination
        $rankedResultsData = $examResultModel->getRankedResults(['exam_id' => $examId, 'class_id' => $classId], 1, 0);
        $rankedResults = $rankedResultsData['data'] ?? [];
        $studentRanks = [];
        
        foreach ($rankedResults as $index => $row) {
            $currentRank = $index + 1;
            // Add ordinal suffix (st, nd, rd, th)
            $suffixes = ["th", "st", "nd", "rd", "th", "th", "th", "th", "th", "th"];
            $suffix = "";
            if (($currentRank % 100) >= 11 && ($currentRank % 100) <= 13) {
                $suffix = "th";
            } else {
                $suffix = $suffixes[$currentRank % 10] ?? "th";
            }
            
            $studentRanks[$row['student_id']] = $currentRank . $suffix;
        }
        
        // Create PDF using DomPDF
        $this->generatePdfReport($exam, $class, $students, $resultsByStudent, $studentRanks, $settings, $generalSettings, $currentAcademicYear, $gradingScale);
    }
    
    private function generatePdfReport($exam, $class, $students, $resultsByStudent, $studentRanks, $settings, $generalSettings, $currentAcademicYear, $gradingScale)
    {
        // Create HTML content for PDF
        ob_start();
        include RESOURCES_PATH . '/views/academic_reports/pdf.php';
        $html = ob_get_clean();
        
        // Configure DomPDF
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $options->set('isRemoteEnabled', true);
        $options->set('isHtml5ParserEnabled', true);
        
        $dompdf = new Dompdf($options);
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();
        
        // Set headers for PDF download
        $fileName = 'academic_reports_' . date('Y-m-d') . '.pdf';
        $dompdf->stream($fileName, ['Attachment' => true]);
        exit();
    }
    
    // Helper method to get grading scale based on exam
    private function getGradingScaleForExam($examId)
    {
        $examModel = new Exam();
        $gradingRules = $examModel->getGradingRulesForExam($examId);
        
        return $gradingRules;
    }
}