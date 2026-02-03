<?php 
$title = 'Print Academic Reports'; 
ob_start(); 

// Apply settings if available
$logoPosition = isset($settings['logo_position']) ? $settings['logo_position'] : 'top-left';
$headerFontSize = isset($settings['header_font_size']) ? $settings['header_font_size'] : 16;
$bodyFontSize = isset($settings['body_font_size']) ? $settings['body_font_size'] : 12;

// Display options
$showSchoolName = !isset($settings['show_school_name']) || $settings['show_school_name'];
$showSchoolAddress = !isset($settings['show_school_address']) || $settings['show_school_address'];
$showSchoolLogo = !isset($settings['show_school_logo']) || $settings['show_school_logo'];
$showStudentPhoto = !isset($settings['show_student_photo']) || $settings['show_student_photo'];
$showGradingScale = !isset($settings['show_grading_scale']) || $settings['show_grading_scale'];
$showAttendance = !isset($settings['show_attendance']) || $settings['show_attendance'];
$showComments = !isset($settings['show_comments']) || $settings['show_comments'];
$showSignatures = !isset($settings['show_signatures']) || $settings['show_signatures'];
$showDateOfBirth = !isset($settings['show_date_of_birth']) || $settings['show_date_of_birth'];
$showClassScore = !isset($settings['show_class_score']) || $settings['show_class_score'];
$showTeacherSignature = !isset($settings['show_teacher_signature']) || $settings['show_teacher_signature'];
$showHeadteacherSignature = !isset($settings['show_headteacher_signature']) || $settings['show_headteacher_signature'];
$showParentSignature = !isset($settings['show_parent_signature']) || $settings['show_parent_signature'];
$showClassPosition = !isset($settings['show_position']) || $settings['show_position'];

// School information
$schoolName = isset($generalSettings['school_name']) ? $generalSettings['school_name'] : 'Sample School Name';

// Determine school address - use custom address if enabled and provided, otherwise use default
if (isset($settings['custom_school_address']) && !empty($settings['custom_school_address'])) {
    $schoolAddress = $settings['custom_school_address'];
} else {
    $schoolAddress = isset($generalSettings['school_address']) ? $generalSettings['school_address'] : '123 Education Street, City, Country';
}

// Academic year information
$academicYearName = isset($currentAcademicYear['name']) ? $currentAcademicYear['name'] : 'N/A';
$term = isset($currentAcademicYear['term']) ? $currentAcademicYear['term'] : 'N/A';
?>

<body class="print-view">
<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8 print-container">
    <!-- Print Button -->
    <div class="mb-4 text-right no-print">
        <p class="text-sm text-gray-600 mb-2">The print dialog will automatically open in a few seconds, or click the button below:</p>
        <button id="printButton" class="bg-blue-600 text-white px-6 py-3 rounded-md text-lg font-medium hover:bg-blue-700 shadow-lg">
            Print Report Cards
        </button>
        <a href="javascript:window.close()" class="ml-2 bg-gray-600 text-white px-6 py-3 rounded-md text-lg font-medium hover:bg-gray-700 shadow-lg">
            Close Window
        </a>
    </div>
    
    <?php foreach ($students as $studentIndex => $student): ?>
    <div class="report-card-page print-content">
        <div class="px-4 py-6 sm:px-0">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div id="reportCard<?= $studentIndex ?>" class="bg-white p-8 rounded shadow report-card">
                        <!-- School Header -->
                        <div class="text-center mb-6" id="schoolHeader">
                            <?php if ($showSchoolLogo): ?>
                            <div id="schoolLogo" class="<?= $logoPosition === 'top-left' ? 'float-left mr-4 mb-4' : 
                                                         ($logoPosition === 'top-right' ? 'float-right ml-4 mb-4' : 
                                                         ($logoPosition === 'bottom-left' ? 'float-left mr-4 mt-4' : 
                                                         ($logoPosition === 'bottom-right' ? 'float-right ml-4 mt-4' : 
                                                         ($logoPosition === 'top-center' ? 'mx-auto mb-4' : 
                                                         ($logoPosition === 'bottom-center' ? 'mx-auto mt-4' : 'mx-auto mb-4'))))) ?>">
                                <?php if (!empty($generalSettings['school_logo'])): ?>
                                    <img src="<?= htmlspecialchars($generalSettings['school_logo']) ?>" alt="School Logo" class="h-16 w-16 rounded-full">
                                <?php else: ?>
                                    <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16"></div>
                                <?php endif; ?>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($showSchoolName): ?>
                            <h1 id="schoolName" class="text-2xl font-bold" style="font-size: <?= $headerFontSize ?>px;"><?= htmlspecialchars($schoolName) ?></h1>
                            <?php endif; ?>
                            
                            <?php if ($showSchoolAddress): ?>
                            <p id="schoolAddress" class="text-gray-600" style="font-size: <?= $bodyFontSize ?>px;"><?= htmlspecialchars($schoolAddress) ?></p>
                            <?php endif; ?>
                        </div>
                        
                        <div class="clear-both"></div>
                        
                        <!-- Report Card Title -->
                        <div class="border-t border-b border-gray-300 py-4 my-4">
                            <h2 class="text-center text-lg font-semibold mb-2" style="font-size: <?= ($headerFontSize - 2) ?>px;">STUDENT REPORT CARD</h2>
                            <div class="grid grid-cols-2 gap-4 text-sm" style="font-size: <?= $bodyFontSize ?>px;">
                                <div>
                                    <p><strong>Student Name:</strong> <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></p>
                                    <p><strong>Admission No:</strong> <?= htmlspecialchars($student['admission_no']) ?></p>
                                    <?php if ($showDateOfBirth && !empty($student['date_of_birth'])): ?>
                                    <p><strong>Date of Birth:</strong> <?= htmlspecialchars(date('M j, Y', strtotime($student['date_of_birth']))) ?></p>
                                    <?php endif; ?>
                                </div>
                                <div>
                                    <p><strong>Class:</strong> <?= htmlspecialchars($class['name'] . ' ' . $class['level']) ?></p>
                                    <p><strong>Academic Year:</strong> <?= htmlspecialchars($academicYearName) ?></p>
                                    <p><strong>Term:</strong> <?= htmlspecialchars($term) ?></p>
                                    <p><strong>Exam:</strong> <?= htmlspecialchars($exam['name']) ?></p>
                                    <?php if (isset($settings['show_position']) && $settings['show_position'] && isset($studentRanks[$student['id']])): ?>
                                    <p><strong>Class Position:</strong> <?= htmlspecialchars($studentRanks[$student['id']]) ?></p>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>
                        
                        <!-- Academic Performance -->
                        <div class="mb-6" id="academicPerformance">
                            <h3 class="text-md font-semibold mb-2" style="font-size: <?= ($headerFontSize - 4) ?>px;">Academic Performance</h3>
                            <?php if (isset($resultsByStudent[$student['id']]) && !empty($resultsByStudent[$student['id']])): ?>
                            <table class="w-full text-sm border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">
                                            Subject
                                        </th>
                                        <?php if ($showClassScore): ?>
                                        <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">
                                            Class Score
                                        </th>
                                        <?php endif; ?>
                                        <?php if (isset($settings['show_exam_score']) && $settings['show_exam_score']): ?>
                                        <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">
                                            Exam Score
                                        </th>
                                        <?php endif; ?>
                                        <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">
                                            Score
                                        </th>
                                        <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">
                                            Grade
                                        </th>
                                        <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">
                                            Remarks
                                        </th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php foreach ($resultsByStudent[$student['id']] as $result): ?>
                                    <tr>
                                        <td class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= htmlspecialchars($result['subject_name']) ?></td>
                                        <?php if ($showClassScore): ?>
                                        <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;">
                                            <?= isset($result['classwork_marks']) ? number_format($result['classwork_marks'], 2) : 'N/A' ?>
                                        </td>
                                        <?php endif; ?>
                                        <?php if (isset($settings['show_exam_score']) && $settings['show_exam_score']): ?>
                                        <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;">
                                            <?= isset($result['exam_marks']) ? number_format($result['exam_marks'], 2) : 'N/A' ?>
                                        </td>
                                        <?php endif; ?>
                                        <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= isset($result['total_score']) ? number_format($result['total_score'], 2) : 'N/A' ?></td>
                                        <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= isset($result['grade']) ? htmlspecialchars($result['grade']) : 'N/A' ?></td>
                                        <td class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= isset($result['remark']) ? htmlspecialchars($result['remark']) : 'N/A' ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                            <?php else: ?>
                            <div class="text-center py-4">
                                <p style="font-size: <?= $bodyFontSize ?>px;">No exam results found for this student.</p>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($showGradingScale): ?>
                        <!-- Grading Scale -->
                        <div class="mb-6" id="gradingScale">
                            <h3 class="text-md font-semibold mb-2" style="font-size: <?= ($headerFontSize - 4) ?>px;">Grading Scale</h3>
                            <table class="w-full text-sm border border-gray-300">
                                <thead>
                                    <tr class="bg-gray-100">
                                        <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Grade</th>
                                        <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Range</th>
                                        <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Remarks</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <?php if (isset($gradingScale) && !empty($gradingScale)): ?>
                                        <?php foreach ($gradingScale as $rule): ?>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= htmlspecialchars($rule['grade']) ?></td>
                                            <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= number_format($rule['min_score'], 0) ?>-<?= number_format($rule['max_score'], 0) ?></td>
                                            <td class="border border-gray-3300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= htmlspecialchars($rule['remark']) ?></td>
                                        </tr>
                                        <?php endforeach; ?>
                                    <?php else: ?>
                                        <!-- Fallback to default grading scale if none found -->
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;">A</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;">80-100</td>
                                            <td class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Excellent</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;">B</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;">70-79</td>
                                            <td class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Very Good</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;">C</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;">60-69</td>
                                            <td class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Good</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;">D</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;">50-59</td>
                                            <td class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Satisfactory</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;">F</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;">0-49</td>
                                            <td class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Fail</td>
                                        </tr>
                                    <?php endif; ?>
                                </tbody>
                            </table>
                        </div>
                        <?php endif; ?>
                        
                        <!-- Attendance and Comments -->
                        <div class="grid grid-cols-2 gap-4 mb-6">
                            <?php if ($showAttendance): ?>
                            <div id="attendanceSection">
                                <h3 class="text-md font-semibold mb-2" style="font-size: <?= ($headerFontSize - 4) ?>px;">Attendance</h3>
                                <p style="font-size: <?= $bodyFontSize ?>px;">Total School Days: ______</p>
                                <p style="font-size: <?= $bodyFontSize ?>px;">Days Present: ______</p>
                                <p style="font-size: <?= $bodyFontSize ?>px;">Days Absent: ______</p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($showComments): ?>
                            <div id="commentsSection">
                                <h3 class="text-md font-semibold mb-2" style="font-size: <?= ($headerFontSize - 4) ?>px;">Comments</h3>
                                <div style="font-size: <?= $bodyFontSize ?>px; margin-bottom: 8px;">
                                    <div style="border-bottom: 1px solid #d1d5db; height: 1.2em; margin-bottom: 4px;"></div>
                                </div>
                                <div style="font-size: <?= $bodyFontSize ?>px; margin-bottom: 8px;">
                                    <div style="border-bottom: 1px solid #d1d5db; height: 1.2em; margin-bottom: 4px;"></div>
                                </div>
                                <div style="font-size: <?= $bodyFontSize ?>px; margin-bottom: 8px;">
                                    <div style="border-bottom: 1px solid #d1d5db; height: 1.2em; margin-bottom: 4px;"></div>
                                </div>
                            </div>
                            <?php endif; ?>
                        </div>
                        
                        <?php if ($showSignatures): ?>
                        <!-- Signatures -->
                        <div class="border-t border-gray-300 pt-4" id="signaturesSection">
                            <div class="grid grid-cols-3 gap-4 text-center signatures-container">
                                <?php if ($showTeacherSignature): ?>
                                <div class="teacher-signature">
                                    <p>Class Teacher</p>
                                    <div class="h-12 border-b border-gray-300 mt-8"></div>
                                    <p class="mt-1">Signature</p>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($showHeadteacherSignature): ?>
                                <div class="headteacher-signature">
                                    <p>Head Teacher</p>
                                    <div class="h-12 border-b border-gray-300 mt-8"></div>
                                    <p class="mt-1">Signature</p>
                                </div>
                                <?php endif; ?>
                                
                                <?php if ($showParentSignature): ?>
                                <div class="parent-signature">
                                    <p>Parent/Guardian</p>
                                    <div class="h-12 border-b border-gray-3300 mt-8"></div>
                                    <p class="mt-1">Signature</p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <?php endforeach; ?>
</div>

<style>
@media print {
    /* Hide navigation and UI elements when printing */
    nav, header, footer, .navbar, .sidebar, .no-print, #printButton {
        display: none !important;
    }
    
    /* Show all content */
    body * {
        visibility: visible !important;
    }
    
    /* Ensure proper page breaks for each report card */
    .report-card-page {
        page-break-after: always;
        display: block !important;
        visibility: visible !important;
    }
    
    .report-card-page:last-child {
        page-break-after: avoid;
    }
    
    /* Remove shadows and rounded corners for clean print */
    .bg-white.shadow.overflow-hidden.sm\:rounded-lg {
        box-shadow: none !important;
        border-radius: 0 !important;
        border: none !important;
    }
    
    /* Additional print styles */
    @page {
        margin: 0.5in;
    }
    
    body {
        margin: 0;
        padding: 0;
    }
    
    html, body {
        height: auto;
        min-height: 100%;
    }
    
    /* Ensure the container takes full width */
    .print-container {
        max-width: 100% !important;
        padding: 0 !important;
        margin: 0 !important;
    }
    
    /* Ensure padding is consistent */
    .px-4.py-6.sm\:px-0 {
        padding: 0 !important;
    }
    
    /* Force display of all report card pages */
    .report-card-page {
        display: block !important;
        visibility: visible !important;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add click event to print button
    const printButton = document.getElementById('printButton');
    if (printButton) {
        printButton.addEventListener('click', function() {
            window.print();
        });
    }
    
    // Automatically focus the print button for keyboard accessibility
    if (printButton) {
        printButton.focus();
    }
    
    // Allow pressing Enter to print
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Enter' && document.activeElement === printButton) {
            window.print();
        }
    });
    
    // Auto-print after a short delay (1.5 seconds) to ensure content is loaded
    // This will work when the page is opened in a new window/tab
    setTimeout(function() {
        // Check if we're in a popup window or new tab with specific name
        if (window.opener || window.name === 'printWindow') {
            // Only auto-print if the print button exists
            if (printButton) {
                window.print();
            }
        }
    }, 1500);
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>