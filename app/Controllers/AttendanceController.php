<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Attendance;
use App\Models\Student;
use App\Models\ClassModel;

class AttendanceController extends Controller
{
    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Get today's date as default
        $date = $this->get('date', date('Y-m-d'));
        
        $attendanceModel = new Attendance();
        $attendanceRecords = $attendanceModel->getByDate($date);
        
        // Get months with attendance records
        $monthsWithAttendance = $attendanceModel->getMonthsWithAttendance();
        
        // Get attendance dates for the current month or selected month
        $selectedMonth = $this->get('month', date('Y-m'));
        $attendanceDates = $attendanceModel->getAttendanceDatesForMonth($selectedMonth);
        
        // Get recent attendance records for the history tab
        // Get filter parameters
        $filters = [
            'class_id' => $this->get('history_class_id', ''),
            'student' => $this->get('history_student', ''),
            'period' => $this->get('history_period', 'today'), // Default to today
            'start_date' => $this->get('history_start_date', ''),
            'end_date' => $this->get('history_end_date', '')
        ];
        
        // Calculate date range based on period
        switch ($filters['period']) {
            case 'today':
                $filters['start_date'] = date('Y-m-d');
                $filters['end_date'] = date('Y-m-d');
                break;
            case 'week':
                // Current week (Monday to Sunday)
                $filters['start_date'] = date('Y-m-d', strtotime('monday this week'));
                $filters['end_date'] = date('Y-m-d', strtotime('sunday this week'));
                break;
            case 'month':
                $filters['start_date'] = date('Y-m-01');
                $filters['end_date'] = date('Y-m-t');
                break;
            case 'year':
                // Current year
                $filters['start_date'] = date('Y-01-01');
                $filters['end_date'] = date('Y-12-31');
                break;
            case 'custom':
                // Use provided start/end dates
                break;
            default:
                // Default to today if invalid period
                 $filters['start_date'] = date('Y-m-d');
                 $filters['end_date'] = date('Y-m-d');
        }
        
        $historyRecords = $attendanceModel->getHistory($filters);
        
        // Group records by class
        $attendanceByClass = [];
        foreach ($attendanceRecords as $record) {
            $className = $record['class_name'] ?? 'No Class';
            if (!isset($attendanceByClass[$className])) {
                $attendanceByClass[$className] = [];
            }
            $attendanceByClass[$className][] = $record;
        }
        
        // Get all classes for filter dropdown
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        $this->view('attendance/index', [
            'attendanceByClass' => $attendanceByClass,
            'date' => $date,
            'monthsWithAttendance' => $monthsWithAttendance,
            'selectedMonth' => $selectedMonth,
            'attendanceDates' => $attendanceDates,
            'historyRecords' => $historyRecords,
            'filters' => $filters,
            'classes' => $classes
        ]);
    }
    
    public function create()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Get today's date as default
        $date = $this->get('date', date('Y-m-d'));
        
        // Get selected class ID
        $classId = $this->get('class_id', '');
        
        // Get all classes for dropdown
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        $studentsByClass = [];
        
        // If a class is selected, get students for that class only
        if (!empty($classId)) {
            $studentModel = new Student();
            $students = $studentModel->where('class_id', $classId);
            
            // Get class name for display
            $selectedClass = $classModel->find($classId);
            
            if (!empty($students)) {
                $className = $selectedClass['name'] ?? 'Unknown Class';
                $studentsByClass[$className] = $students;
            }
        }
        
        $this->view('attendance/create', [
            'studentsByClass' => $studentsByClass,
            'date' => $date,
            'classes' => $classes,
            'selectedClassId' => $classId
        ]);
    }
    
    public function store()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        if ($this->requestMethod() === 'POST' || $this->requestMethod() === 'PUT') {
            $date = $this->post('date');
            $attendanceData = $this->post('attendance');
            
            if (empty($date)) {
                $this->flash('error', 'Please select a date');
                $this->redirect('/attendance/create');
            }
            
            if (empty($attendanceData)) {
                $this->flash('error', 'No attendance data provided');
                $this->redirect('/attendance/create?date=' . $date);
            }
            
            $attendanceModel = new Attendance();
            
            // Process each attendance record
            $successCount = 0;
            $errorCount = 0;
            
            foreach ($attendanceData as $studentId => $data) {
                $status = $data['status'] ?? 'present';
                $remarks = $data['remarks'] ?? '';
                
                // Check if record already exists
                $existing = $attendanceModel->where('student_id', $studentId, 'date', $date);
                
                $attendanceRecord = [
                    'student_id' => $studentId,
                    'date' => $date,
                    'status' => $status,
                    'remarks' => $remarks
                ];
                
                try {
                    if ($existing) {
                        // Update existing record
                        $attendanceModel->update($existing[0]['id'], $attendanceRecord);
                    } else {
                        // Create new record
                        $attendanceModel->create($attendanceRecord);
                    }
                    $successCount++;
                } catch (\Exception $e) {
                    $errorCount++;
                }
            }
            
            if ($successCount > 0) {
                $this->flash('success', "Attendance recorded for {$successCount} students");
            }
            
            if ($errorCount > 0) {
                $this->flash('error', "Failed to record attendance for {$errorCount} students");
            }
            
            $this->redirect('/attendance?date=' . $date);
        } else {
            $this->create();
        }
    }
    
    public function show($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $attendanceModel = new Attendance();
        $attendance = $attendanceModel->find($id);
        
        if (!$attendance) {
            $this->flash('error', 'Attendance record not found');
            $this->redirect('/attendance');
        }
        
        $studentModel = new Student();
        $student = $studentModel->find($attendance['student_id']);
        
        $this->view('attendance/show', [
            'attendance' => $attendance,
            'student' => $student
        ]);
    }
    
    public function edit($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $attendanceModel = new Attendance();
        $attendance = $attendanceModel->find($id);
        
        if (!$attendance) {
            $this->flash('error', 'Attendance record not found');
            $this->redirect('/attendance');
        }
        
        $studentModel = new Student();
        $student = $studentModel->find($attendance['student_id']);
        
        $this->view('attendance/edit', [
            'attendance' => $attendance,
            'student' => $student
        ]);
    }
    
    public function update($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        if ($this->requestMethod() === 'POST' || $this->requestMethod() === 'PUT') {
            $data = [
                'status' => $this->post('status'),
                'remarks' => $this->post('remarks')
            ];
            
            // Basic validation
            if (empty($data['status'])) {
                $this->flash('error', 'Attendance status is required');
                $this->redirect("/attendance/{$id}/edit");
            }
            
            $attendanceModel = new Attendance();
            
            // Update the attendance record
            $result = $attendanceModel->update($id, $data);
            
            if ($result) {
                $this->flash('success', 'Attendance record updated successfully');
                $this->redirect('/attendance');
            } else {
                $this->flash('error', 'Failed to update attendance record');
                $this->redirect("/attendance/{$id}/edit");
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
        
        $attendanceModel = new Attendance();
        $result = $attendanceModel->delete($id);
        
        if ($result) {
            $this->flash('success', 'Attendance record deleted successfully');
        } else {
            $this->flash('error', 'Failed to delete attendance record');
        }
        
        $this->redirect('/attendance');
    }
    
    public function report()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Default to current month
        $startDate = $this->get('start_date', date('Y-m-01'));
        $endDate = $this->get('end_date', date('Y-m-t'));
        
        // Get search parameters
        $searchTerm = $this->get('search', '');
        $classId = $this->get('class_id', '');
        
        // Check if this is an export request
        $export = $this->get('export', false);
        
        $attendanceModel = new Attendance();
        
        if ($export) {
            // Handle export
            $this->exportAttendanceReport($startDate, $endDate, $searchTerm, $classId);
            return;
        }
        
        // Get attendance summary with filters
        $attendanceSummary = $attendanceModel->getAttendanceSummaryWithFilters($startDate, $endDate, $searchTerm, $classId);
        
        // Get all classes for filter dropdown
        $classModel = new ClassModel();
        $classes = $classModel->getAll();
        
        // Group by class
        $summaryByClass = [];
        foreach ($attendanceSummary as $summary) {
            $className = $summary['class_name'] ?? 'No Class';
            if (!isset($summaryByClass[$className])) {
                $summaryByClass[$className] = [];
            }
            $summaryByClass[$className][] = $summary;
        }
        
        $this->view('attendance/report', [
            'summaryByClass' => $summaryByClass,
            'startDate' => $startDate,
            'endDate' => $endDate,
            'searchTerm' => $searchTerm,
            'classId' => $classId,
            'classes' => $classes
        ]);
    }
    
    public function studentReport($studentId)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Get date range parameters
        $startDate = $this->get('start_date', date('Y-m-01'));
        $endDate = $this->get('end_date', date('Y-m-t'));
        
        // Check if this is an export request
        $export = $this->get('export', false);
        
        $attendanceModel = new Attendance();
        $studentModel = new Student();
        
        // Get student details
        $student = $studentModel->find($studentId);
        if (!$student) {
            $this->flash('error', 'Student not found');
            $this->redirect('/attendance/report');
        }
        
        // Get student's class
        $classModel = new ClassModel();
        $class = $classModel->find($student['class_id']);
        
        if ($export) {
            // Handle export
            $this->exportStudentAttendanceReport($student, $class, $startDate, $endDate);
            return;
        }
        
        // Get detailed attendance records
        $attendanceRecords = $attendanceModel->getDetailedAttendanceByStudent($studentId, $startDate, $endDate);
        
        // Get attendance statistics
        $attendanceStats = $attendanceModel->getAttendanceStatsByStudent($studentId, $startDate, $endDate);
        
        $this->view('attendance/student_report', [
            'student' => $student,
            'class' => $class,
            'attendanceRecords' => $attendanceRecords,
            'attendanceStats' => $attendanceStats,
            'startDate' => $startDate,
            'endDate' => $endDate
        ]);
    }
    
    private function exportAttendanceReport($startDate, $endDate, $searchTerm = '', $classId = '')
    {
        $attendanceModel = new Attendance();
        $attendanceSummary = $attendanceModel->getAttendanceSummaryWithFilters($startDate, $endDate, $searchTerm, $classId);
        
        // Get watermark settings
        $settingModel = new \App\Models\Setting();
        $watermarkSettings = $settingModel->getWatermarkSettings();
        
        // Check if this is a print request
        $print = $this->get('print', false);
        
        if ($print) {
            // Generate HTML report for printing
            $this->generateAttendancePrintReport($attendanceSummary, $startDate, $endDate, $searchTerm, $classId, $watermarkSettings);
        } else {
            // Generate CSV export
            $this->generateAttendanceCSVExport($attendanceSummary, $startDate, $endDate);
        }
    }
    
    private function exportStudentAttendanceReport($student, $class, $startDate, $endDate)
    {
        $attendanceModel = new Attendance();
        
        // Get detailed attendance records
        $attendanceRecords = $attendanceModel->getDetailedAttendanceByStudent($student['id'], $startDate, $endDate);
        
        // Get attendance statistics
        $attendanceStats = $attendanceModel->getAttendanceStatsByStudent($student['id'], $startDate, $endDate);
        
        // Check if this is a print request
        $print = $this->get('print', false);
        
        if ($print) {
            // Generate HTML report for printing
            $this->generateStudentAttendancePrintReport($student, $class, $attendanceRecords, $attendanceStats, $startDate, $endDate);
        } else {
            // Generate CSV export
            $this->generateStudentAttendanceCSVExport($student, $class, $attendanceRecords, $attendanceStats, $startDate, $endDate);
        }
    }
    
    private function generateAttendanceCSVExport($attendanceSummary, $startDate, $endDate)
    {
        // Set headers for CSV export
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="attendance_report_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Add CSV headers
        fputcsv($output, [
            'Class',
            'Student Name',
            'Admission No',
            'Present',
            'Absent',
            'Late',
            'Total',
            'Attendance Rate (%)'
        ]);
        
        // Add data
        foreach ($attendanceSummary as $summary) {
            $total = $summary['total'];
            $present = $summary['present'];
            $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
            
            fputcsv($output, [
                $summary['class_name'] ?? 'No Class',
                $summary['first_name'] . ' ' . $summary['last_name'],
                $summary['admission_no'],
                $summary['present'],
                $summary['absent'],
                $summary['late'],
                $summary['total'],
                $rate
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    private function generateStudentAttendanceCSVExport($student, $class, $attendanceRecords, $attendanceStats, $startDate, $endDate)
    {
        // Set headers for CSV export
        header('Content-Type: text/csv');
        header('Content-Disposition: attachment; filename="student_attendance_' . $student['admission_no'] . '_' . date('Y-m-d') . '.csv"');
        header('Pragma: no-cache');
        header('Expires: 0');
        
        // Open output stream
        $output = fopen('php://output', 'w');
        
        // Add student information
        fputcsv($output, ['Student Attendance Report']);
        fputcsv($output, ['Student Name', $student['first_name'] . ' ' . $student['last_name']]);
        fputcsv($output, ['Admission No', $student['admission_no']]);
        fputcsv($output, ['Class', $class['name'] ?? 'N/A']);
        fputcsv($output, ['Period', date('M j, Y', strtotime($startDate)) . ' to ' . date('M j, Y', strtotime($endDate))]);
        fputcsv($output, []);
        
        // Add attendance statistics
        $total = $attendanceStats['total'];
        $present = $attendanceStats['present'];
        $absent = $attendanceStats['absent'];
        $late = $attendanceStats['late'];
        $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
        
        fputcsv($output, ['Attendance Summary']);
        fputcsv($output, ['Present', 'Absent', 'Late', 'Total', 'Attendance Rate (%)']);
        fputcsv($output, [$present, $absent, $late, $total, $rate]);
        fputcsv($output, []);
        
        // Add detailed attendance records
        fputcsv($output, ['Detailed Attendance Records']);
        fputcsv($output, ['Date', 'Status', 'Remarks']);
        
        foreach ($attendanceRecords as $record) {
            fputcsv($output, [
                date('M j, Y', strtotime($record['date'])),
                ucfirst($record['status']),
                $record['remarks'] ?? 'N/A'
            ]);
        }
        
        fclose($output);
        exit;
    }
    
    private function generateAttendancePrintReport($attendanceSummary, $startDate, $endDate, $searchTerm, $classId, $watermarkSettings)
    {
        // Group by class for display
        $summaryByClass = [];
        foreach ($attendanceSummary as $summary) {
            $className = $summary['class_name'] ?? 'No Class';
            if (!isset($summaryByClass[$className])) {
                $summaryByClass[$className] = [];
            }
            $summaryByClass[$className][] = $summary;
        }
        
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
                .filters {
                    background-color: #f5f5f5;
                    padding: 10px;
                    margin-bottom: 20px;
                    border-radius: 5px;
                }
                .filter-title {
                    font-weight: bold;
                    margin-bottom: 5px;
                }
                .filter-item {
                    display: inline-block;
                    margin-right: 15px;
                    font-size: 12px;
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
                .attendance-rate-high {
                    color: #22c55e;
                }
                .attendance-rate-medium {
                    color: #eab308;
                }
                .attendance-rate-low {
                    color: #ef4444;
                }
            </style>
        </head>
        <body>
            <?php if ($watermarkSettings['type'] !== 'none'): ?>
                <div class="watermark" style="top: 50%; left: 50%; transform: translate(-50%, -50%); font-size: 48px; color: #ccc; text-align: center;">
                    <?php if ($watermarkSettings['type'] === 'name' || $watermarkSettings['type'] === 'both'): ?>
                        <?php 
                        $settingModel = new \App\Models\Setting();
                        $settings = $settingModel->getSettings();
                        echo htmlspecialchars($settings['school_name'] ?? 'School');
                        ?>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <div class="header">
                <div class="report-title">Attendance Report</div>
                <div class="report-date">
                    Period: <?= date('M j, Y', strtotime($startDate)) ?> to <?= date('M j, Y', strtotime($endDate)) ?>
                </div>
            </div>
            
            <?php if (!empty($searchTerm) || !empty($classId)): ?>
                <div class="filters">
                    <div class="filter-title">Applied Filters:</div>
                    <?php if (!empty($searchTerm)): ?>
                        <div class="filter-item"><strong>Search:</strong> <?= htmlspecialchars($searchTerm) ?></div>
                    <?php endif; ?>
                    <?php if (!empty($classId)): ?>
                        <?php 
                        $classModel = new \App\Models\ClassModel();
                        $class = $classModel->find($classId);
                        ?>
                        <div class="filter-item"><strong>Class:</strong> <?= htmlspecialchars($class['name'] ?? 'Unknown') ?></div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
            
            <?php if (empty($summaryByClass)): ?>
                <p class="text-center text-gray-500">No attendance records found for the selected criteria.</p>
            <?php else: ?>
                <?php foreach ($summaryByClass as $className => $students): ?>
                    <div style="margin-bottom: 30px;">
                        <h3 style="font-size: 18px; font-weight: bold; margin-bottom: 10px;"><?= htmlspecialchars($className) ?></h3>
                        <table>
                            <thead>
                                <tr>
                                    <th>Student</th>
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
                                    <?php 
                                    $total = $student['total'];
                                    $present = $student['present'];
                                    $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                                    $rateClass = $rate >= 90 ? 'attendance-rate-high' : ($rate >= 75 ? 'attendance-rate-medium' : 'attendance-rate-low');
                                    ?>
                                    <tr>
                                        <td><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></td>
                                        <td><?= htmlspecialchars($student['admission_no']) ?></td>
                                        <td><?= $student['present'] ?></td>
                                        <td><?= $student['absent'] ?></td>
                                        <td><?= $student['late'] ?></td>
                                        <td><?= $student['total'] ?></td>
                                        <td class="<?= $rateClass ?>"><?= $rate ?>%</td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endforeach; ?>
            <?php endif; ?>
            
            <div class="footer">
                Total Students: <?= count($attendanceSummary) ?> | 
                Report generated on <?= date('F j, Y') ?> by <?= $_SESSION['user']['username'] ?? 'System' ?>
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
    
    private function generateStudentAttendancePrintReport($student, $class, $attendanceRecords, $attendanceStats, $startDate, $endDate)
    {
        // Start output buffering
        ob_start();
        ?>
        <!DOCTYPE html>
        <html>
        <head>
            <title>Student Attendance Report</title>
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
                .student-info {
                    background-color: #f5f5f5;
                    padding: 15px;
                    margin-bottom: 20px;
                    border-radius: 5px;
                }
                .info-item {
                    display: inline-block;
                    margin-right: 30px;
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
                .summary-table {
                    width: 50%;
                    margin: 20px 0;
                }
                .status-present {
                    color: #22c55e;
                    font-weight: bold;
                }
                .status-absent {
                    color: #ef4444;
                    font-weight: bold;
                }
                .status-late {
                    color: #eab308;
                    font-weight: bold;
                }
                .footer {
                    margin-top: 30px;
                    text-align: center;
                    font-size: 12px;
                    color: #666;
                }
            </style>
        </head>
        <body>
            <div class="header">
                <div class="report-title">Student Attendance Report</div>
                <div class="report-date">
                    Period: <?= date('M j, Y', strtotime($startDate)) ?> to <?= date('M j, Y', strtotime($endDate)) ?>
                </div>
            </div>
            
            <div class="student-info">
                <div class="info-item"><strong>Student Name:</strong> <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></div>
                <div class="info-item"><strong>Admission No:</strong> <?= htmlspecialchars($student['admission_no']) ?></div>
                <div class="info-item"><strong>Class:</strong> <?= htmlspecialchars($class['name'] ?? 'N/A') ?></div>
            </div>
            
            <?php 
            $total = $attendanceStats['total'];
            $present = $attendanceStats['present'];
            $absent = $attendanceStats['absent'];
            $late = $attendanceStats['late'];
            $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
            ?>
            
            <h3>Attendance Summary</h3>
            <table class="summary-table">
                <tr>
                    <th>Present</th>
                    <th>Absent</th>
                    <th>Late</th>
                    <th>Total</th>
                    <th>Attendance Rate</th>
                </tr>
                <tr>
                    <td class="status-present"><?= $present ?></td>
                    <td class="status-absent"><?= $absent ?></td>
                    <td class="status-late"><?= $late ?></td>
                    <td><?= $total ?></td>
                    <td><?= $rate ?>%</td>
                </tr>
            </table>
            
            <h3>Detailed Attendance Records</h3>
            <?php if (empty($attendanceRecords)): ?>
                <p>No attendance records found for the selected period.</p>
            <?php else: ?>
                <table>
                    <thead>
                        <tr>
                            <th>Date</th>
                            <th>Status</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($attendanceRecords as $record): ?>
                            <tr>
                                <td><?= date('M j, Y', strtotime($record['date'])) ?></td>
                                <td class="status-<?= $record['status'] ?>"><?= ucfirst($record['status']) ?></td>
                                <td><?= htmlspecialchars($record['remarks'] ?? 'N/A') ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php endif; ?>
            
            <div class="footer">
                Report generated on <?= date('F j, Y') ?> by <?= $_SESSION['user']['username'] ?? 'System' ?>
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
        header('Content-Disposition: inline; filename="student_attendance_' . $student['admission_no'] . '_' . date('Y-m-d') . '.html"');
        
        echo $html;
        exit;
    }
}