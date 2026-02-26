<?php
// Web Routes

use App\Core\Router;

// Authentication routes (no middleware needed)
$router->get('/login', 'AuthController@showLoginForm');
$router->post('/login', 'AuthController@login');
$router->get('/logout', 'AuthController@logout');
$router->get('/register', 'AuthController@showRegisterForm');
$router->post('/register', 'AuthController@register');
$router->post('/auth/unlock', 'AuthController@unlock');

// Dashboard route (requires authentication)
$router->get('/dashboard', 'DashboardController@index', ['auth']);

// Student routes (require authentication)
$router->get('/students', 'StudentController@index', ['auth']);
$router->get('/students/export', 'StudentController@export', ['auth']);
$router->get('/students/create', 'StudentController@create', ['auth']);
$router->post('/students', 'StudentController@store', ['auth']);
$router->get('/students/([0-9]+)/edit', 'StudentController@edit', ['auth']);
$router->put('/students/([0-9]+)', 'StudentController@update', ['auth']);
$router->get('/students/([0-9]+)', 'StudentController@show', ['auth']);
$router->get('/students/([0-9]+)/delete', 'StudentController@delete', ['auth']);
$router->get('/students/all-with-fees', 'StudentController@getAllWithFees', ['auth']); // Add this line for getting all students with fees

// Staff routes (require authentication)
$router->get('/staff', 'StaffController@index', ['auth']);
$router->get('/staff/create', 'StaffController@create', ['auth']);
$router->post('/staff', 'StaffController@store', ['auth']);
$router->get('/staff/([0-9]+)/edit', 'StaffController@edit', ['auth']);
$router->put('/staff/([0-9]+)', 'StaffController@update', ['auth']);
$router->get('/staff/([0-9]+)', 'StaffController@show', ['auth']);
$router->get('/staff/([0-9]+)/delete', 'StaffController@delete', ['auth']);

// Subject routes (require authentication)
$router->get('/subjects', 'SubjectController@index', ['auth']);
$router->get('/subjects/create', 'SubjectController@create', ['auth']);
$router->post('/subjects', 'SubjectController@store', ['auth']);
$router->get('/subjects/([0-9]+)/edit', 'SubjectController@edit', ['auth']);
$router->put('/subjects/([0-9]+)', 'SubjectController@update', ['auth']);
$router->get('/subjects/([0-9]+)', 'SubjectController@show', ['auth']);
$router->get('/subjects/([0-9]+)/delete', 'SubjectController@delete', ['auth']);

// Fee routes (require authentication)
$router->get('/fees', 'FeeController@index', ['auth']);
$router->get('/fees/create', 'FeeController@create', ['auth']);
$router->post('/fees', 'FeeController@store', ['auth']);
$router->get('/fees/([0-9]+)/edit', 'FeeController@edit', ['auth']);
$router->put('/fees/([0-9]+)', 'FeeController@update', ['auth']);
$router->get('/fees/([0-9]+)', 'FeeController@show', ['auth']);
$router->get('/fees/([0-9]+)/delete', 'FeeController@delete', ['auth']);
$router->get('/fees/([0-9]+)/assign', 'FeeController@showAssignStudents', ['auth']);
$router->post('/fees/([0-9]+)/assign', 'FeeController@assignStudents', ['auth']);
$router->post('/fees/([0-9]+)/assign/save', 'FeeController@saveStudentAssignments', ['auth']);
$router->post('/fees/students-by-classes', 'FeeController@getStudentsByClasses', ['auth']);
$router->get('/fees/([0-9]+)/students', 'FeeController@show', ['auth']); // Add this line for getting students by fee structure

// Finance routes (require authentication)
$router->get('/finance', 'FinanceController@index', ['auth']);
$router->post('/finance/report-details', 'FinanceController@reportDetails', ['auth']);
$router->get('/finance/fee-details/([0-9]+)', 'FinanceController@getFeeDetails', ['auth']);

// Expense Tracking routes (require authentication)
$router->get('/finance/expenses', 'ExpenseController@index', ['auth']);
$router->post('/finance/expenses/category', 'ExpenseController@saveCategory', ['auth']);
$router->post('/finance/expenses/category/delete', 'ExpenseController@deleteCategory', ['auth']);
$router->post('/finance/expenses/save', 'ExpenseController@saveExpense', ['auth']);
$router->post('/finance/expenses/delete', 'ExpenseController@deleteExpense', ['auth']);
$router->post('/finance/expenses/request', 'ExpenseController@savePaymentRequest', ['auth']);
$router->post('/finance/expenses/request/status', 'ExpenseController@updatePaymentRequestStatus', ['auth']);
$router->post('/finance/expenses/request/delete', 'ExpenseController@deletePaymentRequest', ['auth']);

// Payment routes (require authentication)
$router->get('/payments', 'PaymentController@index', ['auth']);
$router->get('/payments/create', 'PaymentController@create', ['auth']);
$router->get('/pay-fees', 'PaymentController@payFees', ['auth']);
$router->post('/payments', 'PaymentController@store', ['auth']);
$router->get('/payments/([0-9]+)/edit', 'PaymentController@edit', ['auth']);
$router->put('/payments/([0-9]+)', 'PaymentController@update', ['auth']);
$router->get('/payments/([0-9]+)', 'PaymentController@show', ['auth']);
$router->get('/payments/([0-9]+)/delete', 'PaymentController@delete', ['auth']);
$router->get('/payments/student-fees/([0-9]+)', 'PaymentController@getStudentFees', ['auth']);
$router->post('/payments/bulk', 'PaymentController@store', ['auth']); // Add this line for bulk payments

// Receipt routes (require authentication)
$router->get('/receipts', 'ReceiptController@index', ['auth']);
$router->get('/receipts/([0-9]+)', 'ReceiptController@show', ['auth']);
$router->post('/receipts/generate/([0-9]+)', 'ReceiptController@generate', ['auth']);

// Attendance routes (require authentication)
$router->get('/attendance', 'AttendanceController@index', ['auth']);
$router->get('/attendance/create', 'AttendanceController@create', ['auth']);
$router->post('/attendance', 'AttendanceController@store', ['auth']);
$router->get('/attendance/([0-9]+)/edit', 'AttendanceController@edit', ['auth']);
$router->put('/attendance/([0-9]+)', 'AttendanceController@update', ['auth']);
$router->get('/attendance/([0-9]+)', 'AttendanceController@show', ['auth']);
$router->get('/attendance/([0-9]+)/delete', 'AttendanceController@delete', ['auth']);
$router->get('/attendance/report', 'AttendanceController@report', ['auth']);
$router->get('/attendance/student/([0-9]+)/report', 'AttendanceController@studentReport', ['auth']);

// Exam routes (require authentication)
$router->get('/exams', 'ExamController@index', ['auth']);
$router->get('/exams/create', 'ExamController@create', ['auth']);
$router->post('/exams', 'ExamController@store', ['auth']);
$router->get('/exams/([0-9]+)/edit', 'ExamController@edit', ['auth']);
$router->put('/exams/([0-9]+)', 'ExamController@update', ['auth']);
$router->get('/exams/([0-9]+)', 'ExamController@show', ['auth']);
$router->get('/exams/([0-9]+)/delete', 'ExamController@delete', ['auth']);
$router->get('/exams/([0-9]+)/classwork-info', 'ExamController@getClassworkInfo', ['auth']);

// Exam Results routes (require authentication)
$router->get('/exam_results', 'ExamResultController@index', ['auth']);
$router->get('/exam_results/create', 'ExamResultController@create', ['auth']);
$router->get('/exam_results/([0-9]+)', 'ExamResultController@show', ['auth']);
$router->get('/exam_results/exam/([0-9]+)', 'ExamResultController@byExam', ['auth']);
$router->post('/exam_results/store', 'ExamResultController@store', ['auth']);
$router->post('/exam_results/store-bulk', 'ExamResultController@storeBulk', ['auth']);
$router->get('/exam_results/([0-9]+)/edit', 'ExamResultController@edit', ['auth']);
$router->post('/exam_results/([0-9]+)/update', 'ExamResultController@update', ['auth']);
$router->get('/exam_results/([0-9]+)/delete', 'ExamResultController@delete', ['auth']);
$router->get('/exam_results/by-exam/([0-9]+)', 'ExamResultController@byExam', ['auth']);
$router->get('/exam_results/classes-by-exam', 'ExamResultController@getClassesByExam', ['auth']);
$router->get('/exam_results/students-by-class', 'ExamResultController@getStudentsByClass', ['auth']);
$router->get('/exam_results/assigned-subjects-by-class', 'ExamResultController@getAssignedSubjectsByClass', ['auth']);
$router->get('/exam_results/grading-rules', 'ExamResultController@getGradingRules', ['auth']);
$router->get('/exam_results/existing-results', 'ExamResultController@getExistingResults', ['auth']);
$router->get('/exam_results/export', 'ExamResultController@export', ['auth']);
$router->get('/exam_results/submission-status', 'ExamResultController@submissionStatus', ['auth']);
$router->get('/exam_results/submission-status/details', 'ExamResultController@submissionStatusDetails', ['auth']);

// Reports routes (require authentication)
$router->get('/reports', 'ReportsController@index', ['auth']);
$router->get('/reports/analytics', 'ReportsController@analytics', ['auth']);
$router->get('/reports/analytics-data', 'ReportsController@getAnalyticsData', ['auth']);
$router->get('/reports/analytics-filter-options', 'ReportsController@getFilterOptions', ['auth']);
$router->get('/reports/students', 'ReportsController@studentReport', ['auth']);
$router->get('/reports/attendance', 'ReportsController@attendanceReport', ['auth']);
$router->get('/reports/financial', 'ReportsController@financialReport', ['auth']);
$router->get('/reports/academic', 'ReportsController@academicReport', ['auth']);
$router->get('/reports/export/([a-zA-Z]+)', 'ReportsController@export', ['auth']);

// Academic Year routes (require authentication and admin role)
$router->get('/academic_years', 'AcademicYearController@index', ['auth']);
$router->get('/academic_years/create', 'AcademicYearController@create', ['auth']);
$router->post('/academic_years', 'AcademicYearController@store', ['auth']);
$router->get('/academic_years/([0-9]+)/edit', 'AcademicYearController@edit', ['auth']);
$router->put('/academic_years/([0-9]+)', 'AcademicYearController@update', ['auth']);
$router->get('/academic_years/([0-9]+)/delete', 'AcademicYearController@delete', ['auth']);
$router->post('/academic_years/([0-9]+)/set_current', 'AcademicYearController@setCurrent', ['auth']);
$router->get('/academic_years/([0-9]+)', 'AcademicYearController@show', ['auth']);
$router->post('/academic_years/([0-9]+)/update_status', 'AcademicYearController@updateStatus', ['auth']);
$router->post('/academic_years/update_term', 'AcademicYearController@updateTerm', ['auth']);

// School Database routes (require authentication)
$router->get('/school-database', 'ArchiveController@index', ['auth']);

// Timetable routes (require authentication)
$router->get('/timetables', 'TimetableController@index', ['auth']);
$router->get('/timetables/print', 'TimetableController@print', ['auth']); // Add print route first
$router->get('/timetables/create', 'TimetableController@create', ['auth']);
$router->post('/timetables', 'TimetableController@store', ['auth']);
$router->get('/timetables/([0-9]+)/edit', 'TimetableController@edit', ['auth']);
$router->put('/timetables/([0-9]+)', 'TimetableController@update', ['auth']);
$router->get('/timetables/([0-9]+)/delete', 'TimetableController@delete', ['auth']);
$router->get('/timetables/class/([0-9]+)', 'TimetableController@viewByClass', ['auth']);
$router->get('/timetables/day/([a-zA-Z]+)', 'TimetableController@viewByDay', ['auth']);

// AJAX routes for timetable creation (require authentication)
$router->get('/timetables/subjects/class/([0-9]+)', 'TimetableController@getSubjectsByClass', ['auth']);
$router->get('/timetables/teachers/subject/([0-9]+)', 'TimetableController@getTeachersBySubject', ['auth']);

// Notification routes (require authentication)
$router->get('/notifications', 'NotificationController@index', ['auth']);
$router->get('/notifications/([0-9]+)/read', 'NotificationController@markAsRead', ['auth']);
$router->get('/notifications/unread_count', 'NotificationController@getUnreadCount', ['auth']);
$router->get('/notifications/get_notifications', 'NotificationController@getNotifications', ['auth']);
$router->post('/notifications/mark_all_as_read', 'NotificationController@markAllAsRead', ['auth']);

// Audit Log routes (require authentication and admin role)
$router->get('/audit_logs', 'AuditLogController@index', ['auth']);
$router->get('/audit_logs/([0-9]+)', 'AuditLogController@show', ['auth']);
$router->get('/audit_logs/user/([0-9]+)', 'AuditLogController@viewByUser', ['auth']);

// Archive routes (require authentication and admin role)
$router->get('/archives', 'ArchiveController@index', ['auth']);
$router->get('/archives/([0-9]+)', 'ArchiveController@show', ['auth']);

// Backup routes (require authentication and admin role)
$router->get('/backups', 'BackupController@index', ['auth']);
$router->get('/backups/create', 'BackupController@create', ['auth']);
$router->get('/backups/download/([^/]+)', 'BackupController@download', ['auth']);
$router->get('/backups/delete/([^/]+)', 'BackupController@delete', ['auth']);

// User management routes (require authentication and admin role)
$router->get('/users', 'UserController@index', ['auth']);
$router->get('/users/create', 'UserController@create', ['auth']);
$router->post('/users', 'UserController@store', ['auth']);
$router->get('/users/([0-9]+)/edit', 'UserController@edit', ['auth']);
$router->put('/users/([0-9]+)', 'UserController@update', ['auth']);
$router->get('/users/([0-9]+)/change_password', 'UserController@changePassword', ['auth']);
$router->put('/users/([0-9]+)/change_password', 'UserController@updatePassword', ['auth']);
$router->get('/users/([0-9]+)/delete', 'UserController@delete', ['auth']);

// Settings routes (require authentication and admin role)
$router->get('/settings', 'SettingsController@index', ['auth']);
$router->put('/settings', 'SettingsController@update', ['auth']);
$router->get('/settings/generate/admission', 'SettingsController@generateAdmissionNumber', ['auth']);
$router->get('/settings/generate/employee', 'SettingsController@generateEmployeeId', ['auth']);
$router->get('/settings/import', 'SettingsController@showImportForm', ['auth']);
$router->post('/settings/import/students', 'SettingsController@importStudents', ['auth']);
$router->post('/settings/import/classes', 'SettingsController@importClasses', ['auth']);
$router->get('/settings/import/sample/students', 'SettingsController@downloadStudentSample', ['auth']);
$router->get('/settings/import/sample/classes', 'SettingsController@downloadClassSample', ['auth']);
$router->get('/settings/wipe', 'SettingsController@showWipeForm', ['auth']);
$router->post('/settings/wipe', 'SettingsController@performWipe', ['auth']);
$router->get('/settings/school-name', 'SettingsController@getSchoolName', ['auth']);

// Grading System routes (require authentication and admin role)
$router->post('/settings/grading-scale', 'SettingsController@createGradingScale', ['auth']);
$router->post('/settings/grading-rule', 'SettingsController@createGradingRule', ['auth']);
$router->get('/settings/grading-scale/([0-9]+)/delete', 'SettingsController@deleteGradingScale', ['auth']);
$router->get('/settings/grading-rule/([0-9]+)/delete', 'SettingsController@deleteGradingRule', ['auth']);

// Profile routes (require authentication)
$router->get('/profile', 'ProfileController@index', ['auth']);
$router->put('/profile', 'ProfileController@update', ['auth']);
$router->get('/profile/change_password', 'ProfileController@changePassword', ['auth']);
$router->post('/profile/change_password', 'ProfileController@updatePassword', ['auth']);

// Classes routes (require authentication)
$router->get('/classes', 'ClassesController@index', ['auth']);
$router->get('/classes/create', 'ClassesController@create', ['auth']);
$router->post('/classes', 'ClassesController@store', ['auth']);
$router->get('/classes/([0-9]+)', 'ClassesController@show', ['auth']);
$router->get('/classes/([0-9]+)/edit', 'ClassesController@edit', ['auth']);
$router->put('/classes/([0-9]+)', 'ClassesController@update', ['auth']);
$router->get('/classes/([0-9]+)/delete', 'ClassesController@delete', ['auth']);
$router->get('/classes/([0-9]+)/assign-subjects', 'ClassesController@assignSubjects', ['auth']);
$router->post('/classes/([0-9]+)/assign-subjects', 'ClassesController@storeSubjectAssignments', ['auth']);

// Report Card routes (require authentication and admin role)
$router->get('/report-cards/settings', 'ReportCardController@index', ['auth']);
$router->post('/report-cards/settings', 'ReportCardController@update', ['auth']);
$router->get('/report-cards/sample', 'ReportCardController@sample', ['auth']);

// Academic Report routes (require authentication)
$router->get('/academic-reports', 'AcademicReportController@index', ['auth']);
$router->post('/academic-reports/classes', 'AcademicReportController@getClassesByExam', ['auth']);
$router->post('/academic-reports/students', 'AcademicReportController@getStudentsByClassAndExam', ['auth']);
$router->post('/academic-reports/preview', 'AcademicReportController@preview', ['auth']);
$router->get('/academic-reports/print', 'AcademicReportController@print', ['auth']);
$router->get('/academic-reports/export-pdf', 'AcademicReportController@exportPdf', ['auth']);

// Serve uploaded files
$router->get('/storage/uploads/([^/]+)', function($filename) {
    $filePath = ROOT_PATH . '/storage/uploads/' . $filename;
    
    // Check if file exists
    if (file_exists($filePath)) {
        // Set appropriate content type
        $extension = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $mimeTypes = [
            'jpg' => 'image/jpeg',
            'jpeg' => 'image/jpeg',
            'png' => 'image/png',
            'gif' => 'image/gif',
            'webp' => 'image/webp'
        ];
        
        $mimeType = $mimeTypes[$extension] ?? 'application/octet-stream';
        header('Content-Type: ' . $mimeType);
        header('Content-Length: ' . filesize($filePath));
        
        // Output the file
        readfile($filePath);
        exit();
    } else {
        // File not found
        http_response_code(404);
        echo "File not found";
        exit();
    }
});

// Promotion routes (require authentication and admin role)
$router->get('/promotions', 'PromotionController@index', ['auth']);
$router->get('/promotions/history', 'PromotionController@history', ['auth']);
$router->get('/promotions/students-by-class', 'PromotionController@getStudentsByClass', ['auth']);
$router->post('/promotions/promote', 'PromotionController@promote', ['auth']);
$router->get('/promotions/statistics', 'PromotionController@statistics', ['auth']);

// Portal Management routes (require authentication and admin role)
$router->get('/admin/portal', 'Admin\PortalAccessController@index', ['auth']);
$router->post('/admin/portal/student-password', 'Admin\PortalAccessController@updateStudentPassword', ['auth']);
$router->post('/admin/portal/parent-password', 'Admin\PortalAccessController@updateParentPassword', ['auth']);
$router->post('/admin/portal/student-status', 'Admin\PortalAccessController@updateStudentStatus', ['auth']);
$router->post('/admin/portal/update-access', 'Admin\PortalAccessController@updateAccessSettings', ['auth']);
$router->post('/admin/portal/update-access', 'Admin\PortalAccessController@updateAccessSettings', ['auth']);
$router->post('/admin/portal/grant-staff-access', 'Admin\PortalAccessController@grantStaffAccess', ['auth']);
$router->post('/admin/portal/update-staff-access', 'Admin\PortalAccessController@updateStaffAccess', ['auth']);
$router->post('/admin/portal/send-message', 'Admin\PortalAccessController@sendMessage', ['auth']);
$router->post('/admin/portal/end-session', 'Admin\PortalAccessController@endSession', ['auth']);

// Portal Authentication Routes
$router->get('/portal/notifications/list', 'Portal\NotificationController@index');
$router->post('/portal/notifications/mark-read', 'Portal\NotificationController@markRead');
$router->post('/portal/notifications/archive', 'Portal\NotificationController@archive');
$router->post('/portal/notifications/delete', 'Portal\NotificationController@delete');
$router->get('/portal/check-status', 'Portal\SessionController@checkStatus');
$router->get('/portal/login', 'Portal\PortalAuthController@showLoginForm');
$router->post('/portal/login', 'Portal\PortalAuthController@login');
$router->get('/portal/logout', 'Portal\PortalAuthController@logout');

// Student Portal Routes
$router->get('/portal/student/dashboard', 'Portal\StudentController@dashboard');
$router->get('/portal/student/profile', 'Portal\StudentController@profile');
$router->get('/portal/student/profile-data', 'Portal\StudentController@getProfileData');
$router->get('/portal/student/academics', 'Portal\StudentController@academics');
$router->get('/portal/student/fees', 'Portal\StudentController@fees');

// Parent Portal Routes
$router->get('/portal/parent/dashboard', 'Portal\ParentController@dashboard');
$router->get('/portal/parent/profile', 'Portal\ParentController@profile');
$router->get('/portal/parent/academics', 'Portal\ParentController@academics');
$router->get('/portal/parent/fees', 'Portal\ParentController@fees');

// Staff Portal Routes
$router->get('/portal/staff/dashboard', 'Portal\StaffPortalController@dashboard');
$router->get('/portal/staff/timetable', 'Portal\StaffPortalController@timetable');
$router->get('/portal/staff/academics', 'Portal\StaffPortalController@academics');
$router->get('/portal/staff/profile-data', 'Portal\StaffPortalController@getProfileData');
$router->get('/portal/staff/subjects-data', 'Portal\StaffPortalController@getSubjectsData');
$router->get('/portal/staff/timetable-data', 'Portal\StaffPortalController@getTimetableData');

// About route (require authentication)
$router->get('/about', 'AboutController@index', ['auth']);

// Home route
$router->get('/', function() {
    header('Location: /dashboard');
});