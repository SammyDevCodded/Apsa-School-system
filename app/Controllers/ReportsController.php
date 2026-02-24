<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Student;
use App\Models\Staff;
use App\Models\Attendance;
use App\Models\Payment;
use App\Models\ExamResult;
use App\Models\ClassModel;
use App\Models\AcademicYear;
use App\Models\Setting;

class ReportsController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // This is now the "General Reports" page
        $this->view('reports/general_reports');
    }
    
    public function analytics()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Get data for analytics dashboard
        $studentModel = new Student();
        $staffModel = new Staff();
        $attendanceModel = new Attendance();
        $paymentModel = new Payment();
        $examResultModel = new ExamResult();
        $classModel = new ClassModel();
        $settingModel = new Setting();
        
        // Get settings for branding
        $appSettings = $settingModel->getSettings();
        $school_name = $appSettings['school_name'] ?? 'School Name';
        // Assuming logo is stored in uploads folder
        $school_logo = !empty($appSettings['school_logo']) ? '/uploads/' . $appSettings['school_logo'] : '/assets/images/logo.png';

        // Get counts for key metrics
        $totalStudents = $studentModel->getTotalCount();
        $totalStaff = $staffModel->getTotalCount();
        $totalClasses = $classModel->getTotalCount();
        
        // Get recent data for charts
        $recentPayments = $paymentModel->getRecentPayments(30); // Last 30 days
        $recentAttendance = $attendanceModel->getRecentAttendanceForAnalytics(30); // Last 30 days
        
        // Get exam statistics
        $examStats = $examResultModel->getStatistics();
        
        $this->view('reports/analytics', [
            'totalStudents' => $totalStudents,
            'totalStaff' => $totalStaff,
            'totalClasses' => $totalClasses,
            'recentPayments' => $recentPayments,
            'recentAttendance' => $recentAttendance,
            'examStats' => $examStats,
            'school_name' => $school_name,
            'school_logo' => $school_logo
        ]);
    }
    
    public function studentReport()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        // Get filters from request
        $searchTerm = $this->get('search');
        $classId = $this->get('class_id');
        $perPage = (int)$this->get('per_page', 25);
        $page = (int)$this->get('page', 1);
        
        // Validate perPage
        if (!in_array($perPage, [10, 25, 50, 100, 1000])) {
            $perPage = 25;
        }
        
        $filters = [];
        if ($classId) {
            $filters['class_id'] = $classId;
        }
        
        $studentModel = new Student();
        // Use the paginated search method
        // Note: The model method signature is searchWithClassPaginated($searchTerm = '', $filters = [], $page = 1, $perPage = 10)
        $studentsData = $studentModel->searchWithClassPaginated($searchTerm, $filters, $page, $perPage);
        
        // Get settings for branding in print view
        $settingModel = new Setting();
        $settings = $settingModel->getSettings();
        
        $this->view('reports/student_report', [
            'students' => $studentsData['data'], // Array of students
            'pagination' => [
                'total' => $studentsData['total'],
                'per_page' => $studentsData['per_page'],
                'current_page' => $studentsData['page'],
                'total_pages' => $studentsData['total_pages']
            ],
            'classes' => $classes,
            'filters' => [
                'search' => $searchTerm,
                'class_id' => $classId,
                'per_page' => $perPage
            ],
            'settings' => $settings
        ]);
    }
    
    public function attendanceReport()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Default to current month
        $startDate = $this->get('start_date', date('Y-m-01'));
        $endDate = $this->get('end_date', date('Y-m-t'));
        
        $attendanceModel = new Attendance();
        $attendanceSummary = $attendanceModel->getAttendanceSummary($startDate, $endDate);
        
        // Group by class
        $summaryByClass = [];
        foreach ($attendanceSummary as $summary) {
            $className = $summary['class_name'] ?? 'No Class';
            if (!isset($summaryByClass[$className])) {
                $summaryByClass[$className] = [];
            }
            $summaryByClass[$className][] = $summary;
        }
        
        $this->view('reports/attendance_report', [
            'summaryByClass' => $summaryByClass,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
    
    public function financialReport()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Default to current academic year if not set
        $academicYearId = $this->get('academic_year_id');
        
        $academicYearModel = new AcademicYear();
        $academicYears = $academicYearModel->getAll();
        
        // If no ID provided, try to find the current active one
        if (!$academicYearId) {
            $currentYear = $academicYearModel->getCurrent();
            $academicYearId = $currentYear['id'] ?? null;
            
            // If still null (no current year), use the first one from the list
            if (!$academicYearId && !empty($academicYears)) {
                $academicYearId = $academicYears[0]['id'];
            }
        }
        
        $paymentModel = new Payment();
        // Fetch ALL payments (or better, fetch filtered by academic year directly if model supports it)
        // Since getAll() fetches everything, we should filter in the loop or use a custom query.
        // But Payment model has getAll(), let's use a WHERE clause if possible or filter locally.
        // Actually, Payment model has getAllWithDetails which supports filters!
        
        $filters = ['academic_year_id' => $academicYearId];
        $filteredPayments = $paymentModel->getAllWithDetails($filters, '', 1, 10000); // Fetch up to 10000 records
        $yearlyPayments = $filteredPayments['data'];
        
        $totalAmount = 0;
        foreach ($yearlyPayments as $payment) {
            $totalAmount += $payment['amount'];
        }
        
        // Group by month
        $monthlyPayments = [];
        // Helper to sort months correctly might be needed, but let's build the array first
        
        foreach ($yearlyPayments as $payment) {
            // Format: Y-m (e.g. 2025-09)
            $monthKey = date('Y-m', strtotime($payment['date']));
            $monthName = date('F Y', strtotime($payment['date'])); // e.g. September 2025
            
            if (!isset($monthlyPayments[$monthKey])) {
                $monthlyPayments[$monthKey] = [
                    'month_key' => $monthKey,
                    'month' => $monthName,
                    'amount' => 0,
                    'count' => 0
                ];
            }
            
            $monthlyPayments[$monthKey]['amount'] += $payment['amount'];
            $monthlyPayments[$monthKey]['count']++;
        }
        
        // Sort months chronologically
        ksort($monthlyPayments);
        
        // Get settings for branding
        $settingModel = new Setting();
        $settings = $settingModel->getSettings();
        
        $this->view('reports/financial_report', [
            'yearlyPayments' => $yearlyPayments,
            'monthlyPayments' => $monthlyPayments,
            'totalAmount' => $totalAmount,
            'selectedAcademicYearId' => $academicYearId,
            'academicYears' => $academicYears,
            'settings' => $settings
        ]);
    }
    
    public function academicReport()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $filters = $this->getAnalyticsFilters();
        
        // Get settings for branding
        $settingModel = new Setting();
        $appSettings = $settingModel->getSettings();
        $school_name = $appSettings ? ($appSettings['school_name'] ?? 'School Name') : 'School Name';
        $school_logo = ($appSettings && !empty($appSettings['school_logo'])) ? '/uploads/' . $appSettings['school_logo'] : '/assets/images/logo.png';
        
        // DEBUG: Log first exam to check data
        if (!empty($filters['exams'])) {
            file_put_contents(ROOT_PATH . '/debug_exams_log.txt', "Initial Load Exam: " . print_r($filters['exams'][0], true) . "\n", FILE_APPEND);
        } else {
            file_put_contents(ROOT_PATH . '/debug_exams_log.txt', "Initial Load: No exams found\n", FILE_APPEND);
        }

        $this->view('reports/academic_report', [
            'filters' => $filters,
            'school_name' => $school_name,
            'school_logo' => $school_logo
        ]);
    }

    private function getAnalyticsFilters()
    {
        $academicYearModel = new AcademicYear();
        $classModel = new ClassModel();
        
        // We'll need a Subject Model or query distinct subjects
        // For now, let's use a direct query or generic Model method if specific Subject model lacks getAll
        // Assuming models exist and work similarly
        $subjectModel = new \App\Models\Subject(); 
        $examModel = new \App\Models\Exam();

        return [
            'academic_years' => $academicYearModel->getAll(),
            'classes' => $classModel->getAll(),
            'subjects' => $subjectModel->all(),
            'exams' => $examModel->all(), // Might be too large, but okay for now or AJAX load later
            'terms' => $examModel->getDistinctTerms() // Fetched from distinct exams
        ];
    }

    public function getAnalyticsData()
    {
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
            return;
        }

        $filters = [
            'academic_year_id' => $this->get('academic_year_id'),
            'term' => $this->get('term'),
            'class_id' => $this->get('class_id'),
            'subject_id' => $this->get('subject_id'),
            'exam_id' => $this->get('exam_id')
        ];

        $type = $this->get('type', 'ranking'); // 'ranking' or 'trend'
        $page = (int)$this->get('page', 1);
        $perPage = (int)$this->get('per_page', 10);

        $examResultModel = new ExamResult();

        try {
            if ($type === 'trend') {
                $dimension = $this->get('dimension', 'exam');
                $data = $examResultModel->getTrendData($filters, $dimension, $page, $perPage);
            } else {
                $data = $examResultModel->getRankedResults($filters, $page, $perPage);
            }

            $this->jsonResponse(['success' => true, 'data' => $data]);
        } catch (\Exception $e) {
            $this->jsonResponse(['success' => false, 'message' => $e->getMessage()]);
        }
    }
    
    public function export($type)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        switch ($type) {
            case 'students':
                $this->exportStudents();
                break;
            case 'attendance':
                // Check if this is a print request
                $print = $this->get('print', false);
                if ($print) {
                    $this->printAttendance();
                } else {
                    $this->exportAttendance();
                }
                break;
            case 'payments':
                $this->exportPayments();
                break;
            case 'results':
                // Check if this is a print request
                $print = $this->get('print', false);
                if ($print) {
                    $this->printResults();
                } else {
                    $this->exportResults();
                }
                break;
            case 'analytics_ranking':
                $filters = [
                    'academic_year_id' => $this->get('academic_year_id'),
                    'term' => $this->get('term'),
                    'class_id' => $this->get('class_id'),
                    'subject_id' => $this->get('subject_id'),
                    'exam_id' => $this->get('exam_id')
                ];
                
                $print = $this->get('print', false);
                if ($print) {
                    $this->printAnalyticsRanking($filters);
                } else {
                    $this->exportAnalyticsRanking($filters);
                }
                break;
            default:
                $this->flash('error', 'Invalid export type');
                $this->redirect('/reports');
        }
    }

    private function exportAnalyticsRanking($filters)
    {
        $examResultModel = new ExamResult();
        // Pass -1 for perPage to get all records
        $result = $examResultModel->getRankedResults($filters, 1, -1);
        $data = $result['data'];

        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="performance_ranking.csv"');

        $output = fopen('php://output', 'w');
        fputcsv($output, ['Rank', 'First Name', 'Last Name', 'Admission No', 'Class', 'Subjects Count', 'Total Score', 'Average Score']);

        $rank = 1;
        foreach ($data as $row) {
            fputcsv($output, [
                $rank++,
                $row['first_name'],
                $row['last_name'],
                $row['admission_no'],
                $row['class_name'] ?? 'N/A',
                $row['subjects_count'],
                number_format($row['total_score'], 2),
                number_format($row['average_score'], 2)
            ]);
        }
        fclose($output);
        exit();
    }

    private function printAnalyticsRanking($filters)
    {
        $examResultModel = new ExamResult();
        // Pass -1 for perPage to get all records
        $result = $examResultModel->getRankedResults($filters, 1, -1);
        $data = $result['data'];
        
        // Get school settings
        $settingModel = new \App\Models\Setting();
        $watermarkSettings = $settingModel->getWatermarkSettings();
        $settings = $settingModel->getSettings();
        $schoolName = $settings['school_name'] ?? 'School Name';
        $schoolLogo = $settings['school_logo'] ?? null;

        // Interpret filters for display
        $filterStrings = [];
        if (!empty($filters['academic_year_id'])) {
            $ay = (new AcademicYear())->find($filters['academic_year_id']);
            if ($ay) $filterStrings[] = "Academic Year: " . $ay['name'];
        }
        if (!empty($filters['term'])) $filterStrings[] = "Term: " . $filters['term'];
        if (!empty($filters['class_id'])) {
            $cls = (new ClassModel())->find($filters['class_id']);
            if ($cls) $filterStrings[] = "Class: " . $cls['name'] . " " . $cls['level'];
        }
        if (!empty($filters['subject_id'])) {
             // Basic fetch just for name
             $sub = $this->db->fetchOne("SELECT name FROM subjects WHERE id = :id", ['id' => $filters['subject_id']]);
             if ($sub) $filterStrings[] = "Subject: " . $sub['name'];
        }
        if (!empty($filters['exam_id'])) {
             $ex = (new \App\Models\Exam())->find($filters['exam_id']);
             if ($ex) $filterStrings[] = "Exam: " . $ex['name'];
        }

        $filterText = empty($filterStrings) ? "All Records" : implode(' | ', $filterStrings);

        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Performance Ranking Report</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                .school-logo { max-height: 80px; margin-bottom: 10px; }
                .school-name { font-size: 28px; font-weight: bold; color: #333; margin-bottom: 5px; }
                .report-title { font-size: 20px; font-weight: bold; color: #555; text-transform: uppercase; margin-top: 10px; }
                .report-meta { font-size: 14px; color: #666; margin-top: 5px; }
                table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .text-right { text-align: right; }
                .watermark { position: fixed; top: 50%; left: 50%; transform: translate(-50%, -50%); opacity: 0.1; z-index: -1; font-size: 50px; pointer-events: none; }
            </style>
        </head>
        <body>
            <?php if ($watermarkSettings['type'] !== 'none'): ?>
                <div class="watermark">
                    <?php if ($watermarkSettings['type'] === 'name' || $watermarkSettings['type'] === 'both'): ?>
                        <?= htmlspecialchars($schoolName) ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>

            <div class="header">
                <?php if ($schoolLogo && file_exists(PUBLIC_PATH . '/uploads/settings/' . $schoolLogo)): ?>
                    <img src="/uploads/settings/<?= $schoolLogo ?>" alt="Logo" class="school-logo">
                <?php endif; ?>
                <div class="school-name"><?= htmlspecialchars($schoolName) ?></div>
                <div class="report-title">Performance Ranking Report</div>
                <div class="report-meta">Generated on <?= date('F j, Y') ?></div>
                <div class="report-meta">Filters: <?= htmlspecialchars($filterText) ?></div>
            </div>

            <table>
                <thead>
                    <tr>
                        <th>Rank</th>
                        <th>Student Name</th>
                        <th>Admission No</th>
                        <th>Class</th>
                        <th class="text-right">Subjects</th>
                        <th class="text-right">Total Score</th>
                        <th class="text-right">Average</th>
                    </tr>
                </thead>
                <tbody>
                    <?php 
                    $rank = 1;
                    foreach ($data as $row): 
                    ?>
                    <tr>
                        <td><?= $rank++ ?></td>
                        <td><?= htmlspecialchars($row['first_name'] . ' ' . $row['last_name']) ?></td>
                        <td><?= htmlspecialchars($row['admission_no']) ?></td>
                        <td><?= htmlspecialchars($row['class_name'] ?? 'N/A') ?></td>
                        <td class="text-right"><?= $row['subjects_count'] ?></td>
                        <td class="text-right"><?= number_format($row['total_score'], 1) ?></td>
                        <td class="text-right"><?= number_format($row['average_score'], 1) ?></td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>

            <script>window.onload = function() { window.print(); }</script>
        </body>
        </html>
        <?php
        echo ob_get_clean();
        exit;
    }
    
    private function exportStudents()
    {
        $studentModel = new Student();
        $students = $studentModel->getAllWithClass();
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="students.csv"');
        
        // Output CSV
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['Admission No', 'First Name', 'Last Name', 'DOB', 'Gender', 'Class', 'Guardian Name', 'Guardian Phone']);
        
        // Add data
        foreach ($students as $student) {
            fputcsv($output, [
                $student['admission_no'],
                $student['first_name'],
                $student['last_name'],
                $student['dob'],
                $student['gender'],
                $student['class_name'] ?? 'N/A',
                $student['guardian_name'] ?? 'N/A',
                $student['guardian_phone'] ?? 'N/A'
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    private function exportAttendance()
    {
        $startDate = $this->get('start_date', date('Y-m-01'));
        $endDate = $this->get('end_date', date('Y-m-t'));
        
        $attendanceModel = new Attendance();
        $attendanceSummary = $attendanceModel->getAttendanceSummary($startDate, $endDate);
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="attendance.csv"');
        
        // Output CSV
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['Student ID', 'First Name', 'Last Name', 'Admission No', 'Class', 'Present', 'Absent', 'Late', 'Total', 'Attendance Rate']);
        
        // Add data
        foreach ($attendanceSummary as $summary) {
            $rate = $summary['total'] > 0 ? round(($summary['present'] / $summary['total']) * 100, 1) : 0;
            fputcsv($output, [
                $summary['student_id'],
                $summary['first_name'],
                $summary['last_name'],
                $summary['admission_no'],
                $summary['class_name'] ?? 'N/A',
                $summary['present'],
                $summary['absent'],
                $summary['late'],
                $summary['total'],
                $rate . '%'
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    private function printAttendance()
    {
        $startDate = $this->get('start_date', date('Y-m-01'));
        $endDate = $this->get('end_date', date('Y-m-t'));
        
        $attendanceModel = new Attendance();
        $attendanceSummary = $attendanceModel->getAttendanceSummary($startDate, $endDate);
        
        // Group by class for better presentation
        $summaryByClass = [];
        foreach ($attendanceSummary as $summary) {
            $className = $summary['class_name'] ?? 'No Class';
            if (!isset($summaryByClass[$className])) {
                $summaryByClass[$className] = [];
            }
            $summaryByClass[$className][] = $summary;
        }
        
        // Get watermark settings
        $settingModel = new \App\Models\Setting();
        $watermarkSettings = $settingModel->getWatermarkSettings();
        
        // Start output buffering
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Attendance Report</title>
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
                .report-period {
                    font-size: 12px;
                    color: #666;
                    margin-bottom: 20px;
                }
                table {
                    width: 100%;
                    border-collapse: collapse;
                    margin-top: 20px;
                    margin-bottom: 30px;
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
                .class-section {
                    margin-bottom: 30px;
                }
                .class-title {
                    font-size: 18px;
                    font-weight: bold;
                    margin-bottom: 10px;
                    color: #444;
                }
            </style>
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
                <div class="report-title">Attendance Report</div>
                <div class="report-date">Generated on <?= date('F j, Y') ?></div>
                <div class="report-period">Period: <?= date('M j, Y', strtotime($startDate)) ?> to <?= date('M j, Y', strtotime($endDate)) ?></div>
            </div>
            
            <?php foreach ($summaryByClass as $className => $students): ?>
                <div class="class-section">
                    <div class="class-title"><?= htmlspecialchars($className) ?></div>
                    <table>
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Admission No</th>
                                <th>Present</th>
                                <th>Absent</th>
                                <th>Late</th>
                                <th>Total</th>
                                <th>Attendance Rate</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                    <td><?= htmlspecialchars($student['admission_no']) ?></td>
                                    <td><?= $student['present'] ?></td>
                                    <td><?= $student['absent'] ?></td>
                                    <td><?= $student['late'] ?></td>
                                    <td><?= $student['total'] ?></td>
                                    <td>
                                        <?php 
                                        $rate = $student['total'] > 0 ? round(($student['present'] / $student['total']) * 100, 1) : 0;
                                        echo $rate . '%';
                                        ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
            
            <div class="footer">
                Total Records: <?= count($attendanceSummary) ?> | 
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
        header('Content-Disposition: inline; filename="attendance_report_' . date('Y-m-d') . '.html"');
        
        echo $html;
        exit;
    }
    
    private function exportPayments()
    {
        $year = $this->get('year', date('Y'));
        
        $paymentModel = new Payment();
        $payments = $paymentModel->getAll();
        
        // Filter payments by year
        $yearlyPayments = [];
        foreach ($payments as $payment) {
            if (date('Y', strtotime($payment['date'])) == $year) {
                $yearlyPayments[] = $payment;
            }
        }
        
        // Set headers for CSV download
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="payments.csv"');
        
        // Output CSV
        $output = fopen('php://output', 'w');
        
        // Add headers
        fputcsv($output, ['Student Name', 'Admission No', 'Amount', 'Method', 'Date', 'Remarks']);
        
        // Add data
        foreach ($yearlyPayments as $payment) {
            fputcsv($output, [
                $payment['first_name'] . ' ' . $payment['last_name'],
                $payment['admission_no'],
                $payment['amount'],
                $payment['method'],
                $payment['date'],
                $payment['remarks'] ?? 'N/A'
            ]);
        }
        
        fclose($output);
        exit();
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
        fputcsv($output, ['Exam', 'Student Name', 'Admission No', 'Subject', 'Marks', 'Grade']);
        
        // Add data
        foreach ($results as $result) {
            fputcsv($output, [
                $result['exam_name'] ?? 'N/A',
                $result['first_name'] . ' ' . $result['last_name'],
                $result['admission_no'],
                $result['subject_name'] ?? 'N/A',
                $result['marks'],
                $result['grade']
            ]);
        }
        
        fclose($output);
        exit();
    }
    
    private function printResults()
    {
        $resultModel = new ExamResult();
        $results = $resultModel->getAllWithDetails();
        
        // Group results by exam for better presentation
        $resultsByExam = [];
        foreach ($results as $result) {
            $examName = $result['exam_name'] ?? 'Unknown Exam';
            if (!isset($resultsByExam[$examName])) {
                $resultsByExam[$examName] = [];
            }
            $resultsByExam[$examName][] = $result;
        }
        
        // Get watermark settings
        $settingModel = new \App\Models\Setting();
        $watermarkSettings = $settingModel->getWatermarkSettings();
        
        // Start output buffering
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Academic Results Report</title>
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
                .exam-section {
                    margin-bottom: 30px;
                }
                .exam-title {
                    font-size: 18px;
                    font-weight: bold;
                    margin-bottom: 10px;
                    color: #444;
                }
            </style>
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
                <div class="report-title">Academic Results Report</div>
                <div class="report-date">Generated on <?= date('F j, Y') ?></div>
            </div>
            
            <?php foreach ($resultsByExam as $examName => $examResults): ?>
                <div class="exam-section">
                    <div class="exam-title"><?= htmlspecialchars($examName) ?></div>
                    <table>
                        <thead>
                            <tr>
                                <th>Student Name</th>
                                <th>Admission No</th>
                                <th>Subject</th>
                                <th>Marks</th>
                                <th>Grade</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php foreach ($examResults as $result): ?>
                                <tr>
                                    <td><?= htmlspecialchars($result['first_name'] . ' ' . $result['last_name']) ?></td>
                                    <td><?= htmlspecialchars($result['admission_no']) ?></td>
                                    <td><?= htmlspecialchars($result['subject_name'] ?? 'N/A') ?></td>
                                    <td><?= number_format($result['marks'], 2) ?></td>
                                    <td><?= htmlspecialchars($result['grade']) ?></td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            <?php endforeach; ?>
            
            <div class="footer">
                Total Results: <?= count($results) ?> | 
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
        header('Content-Disposition: inline; filename="academic_results_report_' . date('Y-m-d') . '.html"');
        
        echo $html;
        exit;
    }
    /**
     * Get available filter options based on current selection via AJAX
     */
    public function getFilterOptions()
    {
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['error' => 'Unauthorized'], 401);
            return;
        }

        $filters = [
            'academic_year_id' => $this->get('academic_year_id'),
            'term' => $this->get('term'),
            'class_id' => $this->get('class_id'),
            'subject_id' => $this->get('subject_id'),
            'exam_id' => $this->get('exam_id')
        ];

        // Clean up empty filters
        $filters = array_filter($filters, function($value) {
            return $value !== null && $value !== '';
        });

        $examResultModel = new ExamResult();
        $options = $examResultModel->getAvailableFilters($filters);
        
        $this->jsonResponse($options);
    }
}