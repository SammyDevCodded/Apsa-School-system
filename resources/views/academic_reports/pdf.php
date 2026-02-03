<!DOCTYPE html>
<html>
<head>
    <title>Academic Reports</title>
    <meta charset="UTF-8">
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 0;
            padding: 20px;
            font-size: 12px;
        }
        
        .report-card {
            page-break-after: always;
            margin-bottom: 30px;
        }
        
        .report-card:last-child {
            page-break-after: avoid;
        }
        
        .school-header {
            text-align: center;
            margin-bottom: 20px;
        }
        
        .school-logo {
            width: 80px;
            height: 80px;
            margin: 0 auto 10px;
            border-radius: 50%;
        }
        
        .school-name {
            font-size: 18px;
            font-weight: bold;
            margin: 0;
        }
        
        .school-address {
            font-size: 12px;
            margin: 5px 0;
        }
        
        .report-title {
            text-align: center;
            font-size: 16px;
            font-weight: bold;
            margin: 20px 0;
            padding: 10px 0;
            border-top: 1px solid #000;
            border-bottom: 1px solid #000;
        }
        
        .student-info {
            display: flex;
            justify-content: space-between;
            margin-bottom: 20px;
        }
        
        .info-group {
            flex: 1;
        }
        
        .info-label {
            font-weight: bold;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin: 15px 0;
        }
        
        th, td {
            border: 1px solid #000;
            padding: 6px;
            text-align: left;
        }
        
        th {
            background-color: #f2f2f2;
            font-weight: bold;
        }
        
        .text-center {
            text-align: center;
        }
        
        .grading-scale {
            margin-top: 20px;
        }
        
        .grading-scale h3 {
            font-size: 14px;
            margin-bottom: 10px;
        }
        
        .signature-section {
            margin-top: 40px;
            display: flex;
            justify-content: space-between;
        }
        
        .signature-box {
            text-align: center;
            flex: 1;
        }
        
        .signature-line {
            height: 1px;
            background-color: #000;
            margin: 30px 0 5px 0;
        }
    </style>
</head>
<body>
    <?php foreach ($students as $index => $student): ?>
        <?php if ($index > 0): ?>
            <div style="page-break-before: always;"></div>
        <?php endif; ?>
        
        <div class="report-card">
            <!-- School Header -->
            <div class="school-header">
                <?php if (!empty($generalSettings['school_logo']) && isset($settings['show_school_logo']) && $settings['show_school_logo']): ?>
                    <?php
                    // Convert web path to absolute file path for DomPDF
                    $logoPath = $_SERVER['DOCUMENT_ROOT'] . str_replace('/storage/uploads/', '/storage/uploads/', parse_url($generalSettings['school_logo'], PHP_URL_PATH));
                    if (file_exists($logoPath)) {
                        echo '<img src="' . htmlspecialchars($logoPath) . '" alt="School Logo" class="school-logo">';
                    }
                    ?>
                <?php endif; ?>
                
                <?php if (isset($settings['show_school_name']) && $settings['show_school_name']): ?>
                    <h1 class="school-name"><?= htmlspecialchars($generalSettings['school_name'] ?? 'School Name') ?></h1>
                <?php endif; ?>
                
                <?php if (isset($settings['show_school_address']) && $settings['show_school_address']): ?>
                    <?php 
                    // Determine school address - use custom address if enabled and provided, otherwise use default
                    if (isset($settings['custom_school_address']) && !empty($settings['custom_school_address'])) {
                        $schoolAddress = $settings['custom_school_address'];
                    } else {
                        $schoolAddress = $generalSettings['school_address'] ?? 'School Address';
                    }
                    ?>
                    <p class="school-address"><?= htmlspecialchars($schoolAddress) ?></p>
                <?php endif; ?>
            </div>
            
            <!-- Report Title -->
            <div class="report-title">
                STUDENT REPORT CARD
            </div>
            
            <!-- Student Information -->
            <div class="student-info">
                <div class="info-group">
                    <p><span class="info-label">Student Name:</span> <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></p>
                    <p><span class="info-label">Admission No:</span> <?= htmlspecialchars($student['admission_no']) ?></p>
                    <?php if (isset($settings['show_date_of_birth']) && $settings['show_date_of_birth'] && !empty($student['date_of_birth'])): ?>
                        <p><span class="info-label">Date of Birth:</span> <?= htmlspecialchars(date('M j, Y', strtotime($student['date_of_birth']))) ?></p>
                    <?php endif; ?>
                </div>
                <div class="info-group">
                    <p><span class="info-label">Class:</span> <?= htmlspecialchars($class['name'] . ' ' . $class['level']) ?></p>
                    <p><span class="info-label">Academic Year:</span> <?= htmlspecialchars($currentAcademicYear['academic_year']['name'] ?? 'N/A') ?></p>
                    <p><span class="info-label">Term:</span> <?= htmlspecialchars($currentAcademicYear['term'] ?? 'N/A') ?></p>
                    <p><span class="info-label">Exam:</span> <?= htmlspecialchars($exam['name']) ?></p>
                    <?php if (isset($settings['show_position']) && $settings['show_position'] && isset($studentRanks[$student['id']])): ?>
                        <p><span class="info-label">Class Position:</span> <?= htmlspecialchars($studentRanks[$student['id']]) ?></p>
                    <?php endif; ?>
                </div>
            </div>
            
            <!-- Academic Performance -->
            <h3>Academic Performance</h3>
            <?php if (isset($resultsByStudent[$student['id']]) && !empty($resultsByStudent[$student['id']])): ?>
                <table>
                    <thead>
                        <tr>
                            <th>Subject</th>
                            <?php if (isset($settings['show_class_score']) && $settings['show_class_score']): ?>
                                <th>Class Score</th>
                            <?php endif; ?>
                            <?php if (isset($settings['show_exam_score']) && $settings['show_exam_score']): ?>
                                <th>Exam Score</th>
                            <?php endif; ?>
                            <th>Score</th>
                            <th>Grade</th>
                            <th>Remarks</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($resultsByStudent[$student['id']] as $result): ?>
                            <tr>
                                <td><?= htmlspecialchars($result['subject_name']) ?></td>
                                <?php if (isset($settings['show_class_score']) && $settings['show_class_score']): ?>
                                    <td class="text-center">
                                        <?= isset($result['classwork_marks']) ? number_format($result['classwork_marks'], 2) : 'N/A' ?>
                                    </td>
                                <?php endif; ?>
                                <?php if (isset($settings['show_exam_score']) && $settings['show_exam_score']): ?>
                                    <td class="text-center">
                                        <?= isset($result['exam_marks']) ? number_format($result['exam_marks'], 2) : 'N/A' ?>
                                    </td>
                                <?php endif; ?>
                                <td class="text-center"><?= isset($result['total_score']) ? number_format($result['total_score'], 2) : 'N/A' ?></td>
                                <td class="text-center"><?= isset($result['grade']) ? htmlspecialchars($result['grade']) : 'N/A' ?></td>
                                <td><?= isset($result['remark']) ? htmlspecialchars($result['remark']) : 'N/A' ?></td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            <?php else: ?>
                <p>No exam results found for this student.</p>
            <?php endif; ?>
            
            <!-- Grading Scale -->
            <?php if (isset($settings['show_grading_scale']) && $settings['show_grading_scale']): ?>
                <div class="grading-scale">
                    <h3>Grading Scale</h3>
                    <table>
                        <thead>
                            <tr>
                                <th>Grade</th>
                                <th>Range</th>
                                <th>Remarks</th>
                            </tr>
                        </thead>
                        <tbody>
                            <?php if (isset($gradingScale) && !empty($gradingScale)): ?>
                                <?php foreach ($gradingScale as $rule): ?>
                                    <tr>
                                        <td class="text-center"><?= htmlspecialchars($rule['grade']) ?></td>
                                        <td class="text-center"><?= number_format($rule['min_score'], 0) ?>-<?= number_format($rule['max_score'], 0) ?></td>
                                        <td><?= htmlspecialchars($rule['remark']) ?></td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php else: ?>
                                <!-- Fallback to default grading scale if none found -->
                                <tr>
                                    <td class="text-center">A</td>
                                    <td class="text-center">80-100</td>
                                    <td>Excellent</td>
                                </tr>
                                <tr>
                                    <td class="text-center">B</td>
                                    <td class="text-center">70-79</td>
                                    <td>Very Good</td>
                                </tr>
                                <tr>
                                    <td class="text-center">C</td>
                                    <td class="text-center">60-69</td>
                                    <td>Good</td>
                                </tr>
                                <tr>
                                    <td class="text-center">D</td>
                                    <td class="text-center">50-59</td>
                                    <td>Satisfactory</td>
                                </tr>
                                <tr>
                                    <td class="text-center">F</td>
                                    <td class="text-center">0-49</td>
                                    <td>Fail</td>
                                </tr>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            <?php endif; ?>
            
            <!-- Attendance and Comments -->
            <div style="display: flex; justify-content: space-between; margin-top: 20px;">
                <?php if (isset($settings['show_attendance']) && $settings['show_attendance']): ?>
                    <div style="flex: 1;">
                        <h3>Attendance</h3>
                        <p>Total School Days: ______</p>
                        <p>Days Present: ______</p>
                        <p>Days Absent: ______</p>
                    </div>
                <?php endif; ?>
                
                <?php if (isset($settings['show_comments']) && $settings['show_comments']): ?>
                    <div style="flex: 1; margin-left: 20px;">
                        <h3>Comments</h3>
                        <div style="margin-bottom: 8px;">
                            <div style="border-bottom: 1px solid #d1d5db; height: 1.2em; margin-bottom: 4px;"></div>
                        </div>
                        <div style="margin-bottom: 8px;">
                            <div style="border-bottom: 1px solid #d1d5db; height: 1.2em; margin-bottom: 4px;"></div>
                        </div>
                        <div style="margin-bottom: 8px;">
                            <div style="border-bottom: 1px solid #d1d5db; height: 1.2em; margin-bottom: 4px;"></div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
            
            <!-- Signatures -->
            <?php if (isset($settings['show_signatures']) && $settings['show_signatures']): ?>
                <div class="signature-section">
                    <?php if (isset($settings['show_teacher_signature']) && $settings['show_teacher_signature']): ?>
                        <div class="signature-box">
                            <p>Class Teacher</p>
                            <div class="signature-line"></div>
                            <p>Signature</p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($settings['show_headteacher_signature']) && $settings['show_headteacher_signature']): ?>
                        <div class="signature-box">
                            <p>Head Teacher</p>
                            <div class="signature-line"></div>
                            <p>Signature</p>
                        </div>
                    <?php endif; ?>
                    
                    <?php if (isset($settings['show_parent_signature']) && $settings['show_parent_signature']): ?>
                        <div class="signature-box">
                            <p>Parent/Guardian</p>
                            <div class="signature-line"></div>
                            <p>Signature</p>
                        </div>
                    <?php endif; ?>
                </div>
            <?php endif; ?>
        </div>
    <?php endforeach; ?>
</body>
</html>