<?php 
$title = 'Sample Report Card'; 
ob_start(); 

// Apply settings if available
$logoPosition = isset($settings['logo_position']) ? $settings['logo_position'] : 'top-left';
$headerFontSize = isset($settings['header_font_size']) ? $settings['header_font_size'] : 16;
$bodyFontSize = isset($settings['body_font_size']) ? $settings['body_font_size'] : 12;

// Display options
$showSchoolName = !isset($settings['show_school_name']) || $settings['show_school_name'];
$showSchoolAddress = !isset($settings['show_school_address']) || $settings['show_school_address'];
$showSchoolLogo = !isset($settings['show_school_logo']) || $settings['show_school_logo'];
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

// Data Preparation
if (!isset($isSample)) {
    $isSample = true;
}

if ($isSample) {
    $student = [
        'name' => 'John Doe',
        'admission_no' => 'STU001',
        'dob' => 'Jan 1, 2010',
        'class' => 'Grade 5'
    ];
    $examDetails = [
        'academic_year' => '2025/2026',
        'term' => 'First Term',
        'rank' => '1st'
    ];
    $attendance = [
        'total' => 90,
        'present' => 85,
        'absent' => 5
    ];
    $comments = "John is a diligent student who consistently performs well in all subjects. He participates actively in class discussions and completes assignments on time.";
    
    // Sample Results
    $examResults = [
        [
            'subject' => 'Mathematics',
            'class_score' => 85,
            'exam_score' => 90,
            'total_score' => 87.5,
            'grade' => 'A',
            'remark' => 'Excellent'
        ],
        [
            'subject' => 'English',
            'class_score' => 78,
            'exam_score' => 82,
            'total_score' => 80,
            'grade' => 'A',
            'remark' => 'Very Good'
        ],
        [
            'subject' => 'Science',
            'class_score' => 88,
            'exam_score' => 85,
            'total_score' => 86.5,
            'grade' => 'A',
            'remark' => 'Excellent'
        ],
        [
            'subject' => 'Social Studies',
            'class_score' => 75,
            'exam_score' => 80,
            'total_score' => 77.5,
            'grade' => 'B',
            'remark' => 'Good'
        ]
    ];
}
?>

<div class="max-w-4xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div id="reportCard" class="bg-white p-8 rounded shadow">
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
                                <p><strong>Student Name:</strong> <?= htmlspecialchars($student['name']) ?></p>
                                <p><strong>Admission No:</strong> <?= htmlspecialchars($student['admission_no']) ?></p>
                                <?php if ($showDateOfBirth && !empty($student['dob'])): ?>
                                <p><strong>Date of Birth:</strong> <?= htmlspecialchars($student['dob']) ?></p>
                                <?php endif; ?>
                            </div>
                            <div>
                                <p><strong>Class:</strong> <?= htmlspecialchars($student['class']) ?></p>
                                <p><strong>Academic Year:</strong> <?= htmlspecialchars($examDetails['academic_year']) ?></p>
                                <p><strong>Term:</strong> <?= htmlspecialchars($examDetails['term']) ?></p>
                                <?php if ($showClassPosition && isset($examDetails['rank'])): ?>
                                <p><strong>Class Position:</strong> <?= htmlspecialchars($examDetails['rank']) ?></p>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Academic Performance -->
                    <div class="mb-6" id="academicPerformance">
                        <h3 class="text-md font-semibold mb-2" style="font-size: <?= ($headerFontSize - 4) ?>px;">Academic Performance</h3>
                        <table class="w-full text-sm border border-gray-300">
                            <thead>
                                <tr class="bg-gray-100">
                                    <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Subject</th>
                                    <?php if ($showClassScore): ?>
                                    <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Class Score</th>
                                    <?php endif; ?>
                                    <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Exam Score</th>
                                    <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Total Score</th>
                                    <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Total Score</th>
                                    <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Grade</th>
                                    <th class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;">Remarks</th>
                                </tr>
                            </thead>
                            <tbody>
                                <?php if (!empty($examResults)): ?>
                                    <?php foreach ($examResults as $result): ?>
                                    <tr>
                                        <td class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= htmlspecialchars($result['subject']) ?></td>
                                        <?php if ($showClassScore): ?>
                                        <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= isset($result['class_score']) && $result['class_score'] !== null ? number_format($result['class_score'], 1) : '-' ?></td>
                                        <?php endif; ?>
                                        <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= isset($result['exam_score']) && $result['exam_score'] !== null ? number_format($result['exam_score'], 1) : '-' ?></td>
                                        <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= isset($result['total_score']) ? number_format($result['total_score'], 1) : '-' ?></td>
                                        <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= htmlspecialchars($result['grade']) ?></td>
                                        <td class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= htmlspecialchars($result['remark'] ?? '') ?></td>
                                    </tr>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <tr><td colspan="7" class="border border-gray-300 px-2 py-1 text-center">No results found.</td></tr>
                                <?php endif; ?>
                            </tbody>
                        </table>
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
                                <?php if (isset($gradingScale['rules']) && !empty($gradingScale['rules'])): ?>
                                    <?php foreach ($gradingScale['rules'] as $rule): ?>
                                    <tr>
                                        <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= htmlspecialchars($rule['grade']) ?></td>
                                        <td class="border border-gray-300 px-2 py-1 text-center" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= number_format($rule['min_score'], 0) ?>-<?= number_format($rule['max_score'], 0) ?></td>
                                        <td class="border border-gray-300 px-2 py-1" style="font-size: <?= ($bodyFontSize - 1) ?>px;"><?= htmlspecialchars($rule['remark']) ?></td>
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
                            <p style="font-size: <?= $bodyFontSize ?>px;">Total School Days: <?= htmlspecialchars($attendance['total'] ?? '-') ?></p>
                            <p style="font-size: <?= $bodyFontSize ?>px;">Days Present: <?= htmlspecialchars($attendance['present'] ?? '-') ?></p>
                            <p style="font-size: <?= $bodyFontSize ?>px;">Days Absent: <?= htmlspecialchars($attendance['absent'] ?? '-') ?></p>
                        </div>
                        <?php endif; ?>
                        
                        <?php if ($showComments): ?>
                        <div id="commentsSection">
                            <h3 class="text-md font-semibold mb-2" style="font-size: <?= ($headerFontSize - 4) ?>px;">Comments</h3>
                            <p class="text-sm" style="font-size: <?= $bodyFontSize ?>px;"><?= nl2br(htmlspecialchars($comments ?? '')) ?></p>
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
                                <?php if (!empty($settings['teacher_signature'])): ?>
                                    <div class="h-12 mb-1 flex items-end justify-center">
                                        <img src="<?= htmlspecialchars($settings['teacher_signature']) ?>" alt="Signature" class="h-12 object-contain">
                                    </div>
                                    <div class="border-b border-gray-300"></div>
                                <?php else: ?>
                                    <div class="h-12 border-b border-gray-300 mt-8"></div>
                                <?php endif; ?>
                                <p class="mt-1">Signature</p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($showHeadteacherSignature): ?>
                            <div class="headteacher-signature">
                                <p>Head Teacher</p>
                                <?php if (!empty($settings['headteacher_signature'])): ?>
                                    <div class="h-12 mb-1 flex items-end justify-center">
                                        <img src="<?= htmlspecialchars($settings['headteacher_signature']) ?>" alt="Signature" class="h-12 object-contain">
                                    </div>
                                    <div class="border-b border-gray-300"></div>
                                <?php else: ?>
                                    <div class="h-12 border-b border-gray-300 mt-8"></div>
                                <?php endif; ?>
                                <p class="mt-1">Signature</p>
                            </div>
                            <?php endif; ?>
                            
                            <?php if ($showParentSignature): ?>
                            <div class="parent-signature">
                                <p>Parent/Guardian</p>
                                <div class="h-12 border-b border-gray-300 mt-8"></div>
                                <p class="mt-1">Signature</p>
                            </div>
                            <?php endif; ?>
                        </div>
                    </div>
                    <?php endif; ?>
                </div>
                
                <div class="mt-6 flex justify-end">
                    <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                        Print Report Card
                    </button>
                    <a href="/report-cards/settings" class="ml-3 bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                        Back to Settings
                    </a>
                </div>
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    body * {
        visibility: hidden;
    }
    #reportCard, #reportCard * {
        visibility: visible;
    }
    #reportCard {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
    button {
        display: none;
    }
    a {
        display: none;
    }
}
</style>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>