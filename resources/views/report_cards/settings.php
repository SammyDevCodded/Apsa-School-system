<?php 
$title = 'Report Card Settings'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Report Card Settings</h1>
            <div class="flex space-x-2">
                <a href="/report-cards/sample" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700" target="_blank">
                    View Sample
                </a>
                <a href="/dashboard" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                    Back to Dashboard
                </a>
            </div>
        </div>

        <div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
            <!-- Settings Form -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <form action="/report-cards/settings" method="POST" class="space-y-6" id="reportCardForm" enctype="multipart/form-data">
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <!-- Logo Position -->
                            <div>
                                <label for="logo_position" class="block text-sm font-medium text-gray-700">Logo Position</label>
                                <select name="logo_position" id="logo_position" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="top-left" <?= (isset($settings['logo_position']) && $settings['logo_position'] == 'top-left') ? 'selected' : '' ?>>Top Left</option>
                                    <option value="top-center" <?= (isset($settings['logo_position']) && $settings['logo_position'] == 'top-center') ? 'selected' : '' ?>>Top Center</option>
                                    <option value="top-right" <?= (isset($settings['logo_position']) && $settings['logo_position'] == 'top-right') ? 'selected' : '' ?>>Top Right</option>
                                    <option value="bottom-left" <?= (isset($settings['logo_position']) && $settings['logo_position'] == 'bottom-left') ? 'selected' : '' ?>>Bottom Left</option>
                                    <option value="bottom-center" <?= (isset($settings['logo_position']) && $settings['logo_position'] == 'bottom-center') ? 'selected' : '' ?>>Bottom Center</option>
                                    <option value="bottom-right" <?= (isset($settings['logo_position']) && $settings['logo_position'] == 'bottom-right') ? 'selected' : '' ?>>Bottom Right</option>
                                </select>
                            </div>

                            <!-- Header Font Size -->
                            <div>
                                <label for="header_font_size" class="block text-sm font-medium text-gray-700">Header Font Size</label>
                                <input type="number" name="header_font_size" id="header_font_size" min="10" max="30" value="<?= htmlspecialchars($settings['header_font_size'] ?? 16) ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>

                            <!-- Body Font Size -->
                            <div>
                                <label for="body_font_size" class="block text-sm font-medium text-gray-700">Body Font Size</label>
                                <input type="number" name="body_font_size" id="body_font_size" min="8" max="24" value="<?= htmlspecialchars($settings['body_font_size'] ?? 12) ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>

                        <!-- Display Options -->
                        <div class="border-t border-gray-200 pt-6">
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Display Options</h3>
                            <p class="mt-1 text-sm text-gray-500">Select which elements to show on the report card</p>
                            
                            <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                                <div class="flex items-center">
                                    <input id="show_school_name" name="show_school_name" type="checkbox" <?= (isset($reportCardSettings['show_school_name']) && $reportCardSettings['show_school_name']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_school_name" class="ml-3 block text-sm font-medium text-gray-700">Show School Name</label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="show_school_address" name="show_school_address" type="checkbox" <?= (isset($reportCardSettings['show_school_address']) && $reportCardSettings['show_school_address']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_school_address" class="ml-3 block text-sm font-medium text-gray-700">Show School Address</label>
                                </div>
                                
                                <div class="flex items-center ml-8 mt-2">
                                    <input type="hidden" name="use_custom_school_address" value="0">
                                    <input id="use_custom_school_address" name="use_custom_school_address" type="checkbox" value="1" <?= (isset($reportCardSettings['custom_school_address']) && !empty($reportCardSettings['custom_school_address'])) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="use_custom_school_address" class="ml-3 block text-sm font-medium text-gray-700">Use Custom School Address</label>
                                </div>
                                
                                <div id="custom_school_address_field" class="ml-8 mt-2 <?= (isset($reportCardSettings['custom_school_address']) && !empty($reportCardSettings['custom_school_address'])) ? '' : 'hidden' ?>">
                                    <label for="custom_school_address" class="block text-sm font-medium text-gray-700">Custom School Address</label>
                                    <div class="mt-1">
                                        <textarea id="custom_school_address" name="custom_school_address" rows="3" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"><?= htmlspecialchars($reportCardSettings['custom_school_address'] ?? '') ?></textarea>
                                    </div>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="show_school_logo" name="show_school_logo" type="checkbox" <?= (isset($reportCardSettings['show_school_logo']) && $reportCardSettings['show_school_logo']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_school_logo" class="ml-3 block text-sm font-medium text-gray-700">Show School Logo</label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="show_student_photo" name="show_student_photo" type="checkbox" <?= (isset($reportCardSettings['show_student_photo']) && $reportCardSettings['show_student_photo']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_student_photo" class="ml-3 block text-sm font-medium text-gray-700">Show Student Photo</label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="show_grading_scale" name="show_grading_scale" type="checkbox" <?= (isset($reportCardSettings['show_grading_scale']) && $reportCardSettings['show_grading_scale']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_grading_scale" class="ml-3 block text-sm font-medium text-gray-700">Show Grading Scale</label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="show_attendance" name="show_attendance" type="checkbox" <?= (isset($reportCardSettings['show_attendance']) && $reportCardSettings['show_attendance']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_attendance" class="ml-3 block text-sm font-medium text-gray-700">Show Attendance</label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="show_comments" name="show_comments" type="checkbox" <?= (isset($reportCardSettings['show_comments']) && $reportCardSettings['show_comments']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_comments" class="ml-3 block text-sm font-medium text-gray-700">Show Comments</label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="show_signatures" name="show_signatures" type="checkbox" <?= (isset($reportCardSettings['show_signatures']) && $reportCardSettings['show_signatures']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_signatures" class="ml-3 block text-sm font-medium text-gray-700">Show All Signatures</label>
                                </div>
                                
                                <!-- New options -->
                                <div class="flex items-center">
                                    <input id="show_date_of_birth" name="show_date_of_birth" type="checkbox" <?= (isset($reportCardSettings['show_date_of_birth']) && $reportCardSettings['show_date_of_birth']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_date_of_birth" class="ml-3 block text-sm font-medium text-gray-700">Show Date of Birth</label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="show_class_score" name="show_class_score" type="checkbox" <?= (isset($reportCardSettings['show_class_score']) && $reportCardSettings['show_class_score']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_class_score" class="ml-3 block text-sm font-medium text-gray-700">Show Class Score</label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="show_headteacher_signature" name="show_headteacher_signature" type="checkbox" <?= (isset($reportCardSettings['show_headteacher_signature']) && $reportCardSettings['show_headteacher_signature']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_headteacher_signature" class="ml-3 block text-sm font-medium text-gray-700">Show Headteacher Signature</label>
                                </div>
                                
                                <div id="headteacher_signature_field" class="ml-8 mt-2 <?= (isset($reportCardSettings['show_headteacher_signature']) && $reportCardSettings['show_headteacher_signature']) ? '' : 'hidden' ?>">
                                    <label class="block text-sm font-medium text-gray-700">Headteacher Signature Image</label>
                                    <div class="mt-1 flex items-center">
                                        <?php if (!empty($reportCardSettings['headteacher_signature'])): ?>
                                            <div class="mr-4">
                                                <img src="<?= htmlspecialchars($reportCardSettings['headteacher_signature']) ?>" alt="Headteacher Signature" class="h-12 object-contain border border-gray-300 p-1 bg-white">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" name="headteacher_signature" id="headteacher_signature" accept="image/*" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Upload a transparent PNG signature image.</p>
                                </div>
                                
                                <div class="flex items-center mt-4">
                                    <input id="show_teacher_signature" name="show_teacher_signature" type="checkbox" <?= (isset($reportCardSettings['show_teacher_signature']) && $reportCardSettings['show_teacher_signature']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_teacher_signature" class="ml-3 block text-sm font-medium text-gray-700">Show Teacher Signature</label>
                                </div>

                                <div id="teacher_signature_field" class="ml-8 mt-2 <?= (isset($reportCardSettings['show_teacher_signature']) && $reportCardSettings['show_teacher_signature']) ? '' : 'hidden' ?>">
                                    <label class="block text-sm font-medium text-gray-700">Teacher Signature Image</label>
                                    <div class="mt-1 flex items-center">
                                        <?php if (!empty($reportCardSettings['teacher_signature'])): ?>
                                            <div class="mr-4">
                                                <img src="<?= htmlspecialchars($reportCardSettings['teacher_signature']) ?>" alt="Teacher Signature" class="h-12 object-contain border border-gray-300 p-1 bg-white">
                                            </div>
                                        <?php endif; ?>
                                        <input type="file" name="teacher_signature" id="teacher_signature" accept="image/*" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                    <p class="mt-1 text-xs text-gray-500">Upload a transparent PNG signature image.</p>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="show_parent_signature" name="show_parent_signature" type="checkbox" <?= (isset($reportCardSettings['show_parent_signature']) && $reportCardSettings['show_parent_signature']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_parent_signature" class="ml-3 block text-sm font-medium text-gray-700">Show Parent Signature</label>
                                </div>
                                
                                <div class="flex items-center">
                                    <input id="show_exam_score" name="show_exam_score" type="checkbox" <?= (isset($reportCardSettings['show_exam_score']) && $reportCardSettings['show_exam_score']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_exam_score" class="ml-3 block text-sm font-medium text-gray-700">Show Exam Score</label>
                                </div>

                                <div class="flex items-center">
                                    <input id="show_position" name="show_position" type="checkbox" <?= (isset($reportCardSettings['show_position']) && $reportCardSettings['show_position']) ? 'checked' : '' ?> class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="show_position" class="ml-3 block text-sm font-medium text-gray-700">Show Class Position</label>
                                </div>
                            </div>
                        </div>

                        <div class="flex justify-end">
                            <button type="submit" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Settings
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Preview Section -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h2 class="text-xl font-semibold text-gray-900 mb-4">Report Card Preview</h2>
                    <div class="border border-gray-3300 rounded-lg p-4 bg-gray-50 min-h-[500px]">
                        <div id="reportCardPreview" class="bg-white p-6 rounded shadow">
                            <!-- Preview content will be dynamically generated here -->
                            <div class="text-center mb-6" id="schoolHeader">
                                <div id="previewLogo" class="mx-auto mb-4">
                                    <?php if (!empty($generalSettings['school_logo'])): ?>
                                        <img src="<?= htmlspecialchars($generalSettings['school_logo']) ?>" alt="School Logo" class="h-16 w-16 mx-auto rounded-full">
                                    <?php else: ?>
                                        <div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 mx-auto"></div>
                                    <?php endif; ?>
                                </div>
                                <h1 id="previewSchoolName" class="text-2xl font-bold"><?= htmlspecialchars($generalSettings['school_name'] ?? 'Sample School Name') ?></h1>
                                <p id="previewSchoolAddress" class="text-gray-600"><?= htmlspecialchars($generalSettings['school_address'] ?? '123 Education Street, City, Country') ?></p>
                            </div>
                            
                            <div class="border-t border-b border-gray-300 py-4 my-4">
                                <h2 class="text-center text-lg font-semibold mb-2">STUDENT REPORT CARD</h2>
                                <div class="grid grid-cols-2 gap-4 text-sm">
                                    <div>
                                        <p><strong>Student Name:</strong> <span id="previewStudentName">John Doe</span></p>
                                        <p><strong>Admission No:</strong> <span id="previewAdmissionNo">STU001</span></p>
                                        <p id="previewDateOfBirthLine"><strong>Date of Birth:</strong> <span id="previewDateOfBirth">Jan 1, 2010</span></p>
                                    </div>
                                    <div>
                                        <p><strong>Class:</strong> <span id="previewClass">Grade 5</span></p>
                                        <p><strong>Academic Year:</strong> <span id="previewAcademicYear">2025/2026</span></p>
                                        <p><strong>Term:</strong> <span id="previewTerm">First Term</span></p>
                                        <p id="previewPositionLine"><strong>Class Position:</strong> <span id="previewPosition">1st</span></p>
                                    </div>
                                </div>
                            </div>
                            
                            <div class="mb-6" id="academicPerformance">
                                <h3 class="text-md font-semibold mb-2">Academic Performance</h3>
                                <table class="w-full text-sm border border-gray-300">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-300 px-2 py-1">Subject</th>
                                            <th class="border border-gray-300 px-2 py-1 class-score-col">Class Score</th>
                                            <th class="border border-gray-300 px-2 py-1">Exam Score</th>
                                            <th class="border border-gray-300 px-2 py-1">Total Score</th>
                                            <th class="border border-gray-300 px-2 py-1">Grade</th>
                                            <th class="border border-gray-300 px-2 py-1">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1">Mathematics</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center class-score-col">85</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center">90</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center">87.5</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center">A</td>
                                            <td class="border border-gray-300 px-2 py-1">Excellent</td>
                                        </tr>
                                        <tr>
                                            <td class="border border-gray-300 px-2 py-1">English</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center class-score-col">78</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center">82</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center">80</td>
                                            <td class="border border-gray-300 px-2 py-1 text-center">A</td>
                                            <td class="border border-gray-300 px-2 py-1">Very Good</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="mb-6" id="gradingScale">
                                <h3 class="text-md font-semibold mb-2">Grading Scale</h3>
                                <table class="w-full text-sm border border-gray-300">
                                    <thead>
                                        <tr class="bg-gray-100">
                                            <th class="border border-gray-300 px-2 py-1">Grade</th>
                                            <th class="border border-gray-300 px-2 py-1">Range</th>
                                            <th class="border border-gray-300 px-2 py-1">Remarks</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php if (isset($gradingScale['rules']) && !empty($gradingScale['rules'])): ?>
                                            <?php foreach ($gradingScale['rules'] as $rule): ?>
                                            <tr>
                                                <td class="border border-gray-300 px-2 py-1 text-center"><?= htmlspecialchars($rule['grade']) ?></td>
                                                <td class="border border-gray-300 px-2 py-1 text-center"><?= number_format($rule['min_score'], 0) ?>-<?= number_format($rule['max_score'], 0) ?></td>
                                                <td class="border border-gray-300 px-2 py-1"><?= htmlspecialchars($rule['remark']) ?></td>
                                            </tr>
                                            <?php endforeach; ?>
                                        <?php else: ?>
                                            <!-- Fallback to default grading scale if none found -->
                                            <tr>
                                                <td class="border border-gray-300 px-2 py-1 text-center">A</td>
                                                <td class="border border-gray-300 px-2 py-1 text-center">80-100</td>
                                                <td class="border border-gray-300 px-2 py-1">Excellent</td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-300 px-2 py-1 text-center">B</td>
                                                <td class="border border-gray-300 px-2 py-1 text-center">70-79</td>
                                                <td class="border border-gray-300 px-2 py-1">Very Good</td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-300 px-2 py-1 text-center">C</td>
                                                <td class="border border-gray-300 px-2 py-1 text-center">60-69</td>
                                                <td class="border border-gray-300 px-2 py-1">Good</td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-300 px-2 py-1 text-center">D</td>
                                                <td class="border border-gray-300 px-2 py-1 text-center">50-59</td>
                                                <td class="border border-gray-300 px-2 py-1">Satisfactory</td>
                                            </tr>
                                            <tr>
                                                <td class="border border-gray-300 px-2 py-1 text-center">F</td>
                                                <td class="border border-gray-300 px-2 py-1 text-center">0-49</td>
                                                <td class="border border-gray-300 px-2 py-1">Fail</td>
                                            </tr>
                                        <?php endif; ?>
                                    </tbody>
                                </table>
                            </div>
                            
                            <div class="grid grid-cols-2 gap-4 mb-6">
                                <div id="attendanceSection">
                                    <h3 class="text-md font-semibold mb-2">Attendance</h3>
                                    <p>Total School Days: 90</p>
                                    <p>Days Present: 85</p>
                                    <p>Days Absent: 5</p>
                                </div>
                                <div id="commentsSection">
                                    <h3 class="text-md font-semibold mb-2">Comments</h3>
                                    <p class="text-sm">John is a diligent student who consistently performs well in all subjects.</p>
                                </div>
                            </div>
                            
                            <div class="border-t border-gray-300 pt-4" id="signaturesSection">
                                <div class="grid grid-cols-3 gap-4 text-center signatures-container">
                                    <div class="teacher-signature">
                                        <p>Class Teacher</p>
                                        <div class="h-12 border-b border-gray-300 mt-8"></div>
                                        <p class="mt-1">Signature</p>
                                    </div>
                                    <div class="headteacher-signature">
                                        <p>Head Teacher</p>
                                        <div class="h-12 border-b border-gray-300 mt-8"></div>
                                        <p class="mt-1">Signature</p>
                                    </div>
                                    <div class="parent-signature">
                                        <p>Parent/Guardian</p>
                                        <div class="h-12 border-b border-gray-300 mt-8"></div>
                                        <p class="mt-1">Signature</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Get all form elements
    const formElements = document.querySelectorAll('#reportCardForm input, #reportCardForm select');
    
    // Get preview elements
    const previewSchoolName = document.getElementById('previewSchoolName');
    const previewSchoolAddress = document.getElementById('previewSchoolAddress');
    const previewLogoContainer = document.getElementById('previewLogo');
    const schoolHeader = document.getElementById('schoolHeader');
    const academicPerformance = document.getElementById('academicPerformance');
    const gradingScale = document.getElementById('gradingScale');
    const attendanceSection = document.getElementById('attendanceSection');
    const commentsSection = document.getElementById('commentsSection');
    const signaturesSection = document.getElementById('signaturesSection');
    const previewDateOfBirthLine = document.getElementById('previewDateOfBirthLine');
    
    // Get custom address elements
    const useCustomAddressCheckbox = document.getElementById('use_custom_school_address');
    const customAddressField = document.getElementById('custom_school_address_field');
    const customAddressTextarea = document.getElementById('custom_school_address');
    
    // Store the school logo URL from PHP
    const schoolLogoUrl = <?= json_encode(!empty($generalSettings['school_logo']) ? $generalSettings['school_logo'] : null) ?>;
    const schoolName = <?= json_encode($generalSettings['school_name'] ?? 'Sample School Name') ?>;
    const schoolAddress = <?= json_encode($generalSettings['school_address'] ?? '123 Education Street, City, Country') ?>;
    
    // Toggle custom address field visibility
    if (useCustomAddressCheckbox) {
        useCustomAddressCheckbox.addEventListener('change', function() {
            if (this.checked) {
                customAddressField.classList.remove('hidden');
            } else {
                customAddressField.classList.add('hidden');
                customAddressTextarea.value = '';
                updatePreview();
            }
        });
    }
    
    // Function to update preview
    function updatePreview() {
        // Update school name
        previewSchoolName.textContent = document.getElementById('school_name') ? document.getElementById('school_name').value || schoolName : schoolName;
        
        // Update school address - use custom address if enabled and provided, otherwise use default
        const useCustomAddress = document.getElementById('use_custom_school_address') && document.getElementById('use_custom_school_address').checked;
        const customAddress = document.getElementById('custom_school_address') ? document.getElementById('custom_school_address').value : '';
        
        if (useCustomAddress && customAddress.trim() !== '') {
            previewSchoolAddress.textContent = customAddress;
        } else {
            previewSchoolAddress.textContent = document.getElementById('school_address') ? document.getElementById('school_address').value || schoolAddress : schoolAddress;
        }
        
        // Update logo position
        const logoPosition = document.getElementById('logo_position').value;
        previewLogoContainer.className = 'mx-auto mb-4';
        
        switch(logoPosition) {
            case 'top-left':
                previewLogoContainer.className = 'float-left mr-4 mb-4';
                break;
            case 'top-center':
                previewLogoContainer.className = 'mx-auto mb-4';
                break;
            case 'top-right':
                previewLogoContainer.className = 'float-right ml-4 mb-4';
                break;
            case 'bottom-left':
                previewLogoContainer.className = 'float-left mr-4 mt-4';
                break;
            case 'bottom-center':
                previewLogoContainer.className = 'mx-auto mt-4';
                break;
            case 'bottom-right':
                previewLogoContainer.className = 'float-right ml-4 mt-4';
                break;
        }
        
        // Update logo display
        const showSchoolLogo = document.getElementById('show_school_logo').checked;
        if (showSchoolLogo && schoolLogoUrl) {
            // Create or update img element
            let img = previewLogoContainer.querySelector('img');
            if (!img) {
                // Clear container and add image
                previewLogoContainer.innerHTML = '';
                img = document.createElement('img');
                img.alt = 'School Logo';
                img.className = 'h-16 w-16 mx-auto rounded-full';
                previewLogoContainer.appendChild(img);
            }
            img.src = schoolLogoUrl;
            previewLogoContainer.style.display = 'block';
        } else if (showSchoolLogo && !schoolLogoUrl) {
            // Show placeholder if no logo is set
            previewLogoContainer.innerHTML = '<div class="bg-gray-200 border-2 border-dashed rounded-xl w-16 h-16 mx-auto"></div>';
            previewLogoContainer.style.display = 'block';
        } else {
            // Hide logo
            previewLogoContainer.style.display = 'none';
        }
        
        // Update font sizes
        const headerFontSize = parseInt(document.getElementById('header_font_size').value);
        const bodyFontSize = parseInt(document.getElementById('body_font_size').value);
        
        // Apply to school name (header)
        previewSchoolName.style.fontSize = headerFontSize + 'px';
        
        // Update all headers (h2, h3)
        const headers = document.querySelectorAll('#reportCardPreview h2, #reportCardPreview h3');
        headers.forEach(element => {
            if (element.id !== 'previewSchoolName') {
                const originalSize = element.tagName === 'H2' ? headerFontSize - 2 : headerFontSize - 4;
                element.style.fontSize = Math.max(10, originalSize) + 'px';
            }
        });
        
        // Update all body text
        const bodyElements = document.querySelectorAll('#reportCardPreview p, #reportCardPreview td, #reportCardPreview th, #reportCardPreview div > p');
        bodyElements.forEach(element => {
            element.style.fontSize = bodyFontSize + 'px';
        });
        
        // Update table text specifically
        const tableElements = document.querySelectorAll('#reportCardPreview table td, #reportCardPreview table th');
        tableElements.forEach(element => {
            element.style.fontSize = (bodyFontSize - 1) + 'px';
        });
        
        // Update display options
        const showSchoolName = document.getElementById('show_school_name').checked;
        previewSchoolName.style.display = showSchoolName ? 'block' : 'none';
        
        const showSchoolAddress = document.getElementById('show_school_address').checked;
        previewSchoolAddress.style.display = showSchoolAddress ? 'block' : 'none';
        
        // Hide/show entire school header if all elements are hidden
        const schoolHeaderVisible = showSchoolName || showSchoolAddress || (showSchoolLogo && (schoolLogoUrl || !schoolLogoUrl));
        schoolHeader.style.display = schoolHeaderVisible ? 'block' : 'none';
        
        // Update other sections
        academicPerformance.style.display = 'block'; // Always show Academic Performance table
        gradingScale.style.display = document.getElementById('show_grading_scale').checked ? 'block' : 'none';
        attendanceSection.style.display = document.getElementById('show_attendance').checked ? 'block' : 'none';
        commentsSection.style.display = document.getElementById('show_comments').checked ? 'block' : 'none';
        signaturesSection.style.display = document.getElementById('show_signatures').checked ? 'block' : 'none';
        
        // Update Date of Birth visibility
        previewDateOfBirthLine.style.display = document.getElementById('show_date_of_birth').checked ? 'block' : 'none';
        
        // Update class score visibility
        const classScoreCols = document.querySelectorAll('.class-score-col');
        classScoreCols.forEach(col => {
            col.style.display = document.getElementById('show_class_score').checked ? 'table-cell' : 'none';
        });
        
        // Update exam score visibility (in preview table)
        const examScoreHeader = document.querySelector('th:nth-child(3)'); // Exam Score header
        const examScoreCells = document.querySelectorAll('tbody tr td:nth-child(3)'); // Exam Score cells
        if (examScoreHeader && examScoreCells.length > 0) {
            const showExamScore = document.getElementById('show_exam_score').checked;
            examScoreHeader.style.display = showExamScore ? 'table-cell' : 'none';
            examScoreCells.forEach(cell => {
                cell.style.display = showExamScore ? 'table-cell' : 'none';
            });
        }
        
        // Update position visibility
        // Update position visibility
        const positionLine = document.getElementById('previewPositionLine');
        if (positionLine) {
            positionLine.style.display = document.getElementById('show_position').checked ? 'block' : 'none';
        }
        
        // Update signature visibility
        const teacherSignature = document.querySelector('.teacher-signature');
        const headteacherSignature = document.querySelector('.headteacher-signature');
        const parentSignature = document.querySelector('.parent-signature');
        
        if (teacherSignature) {
            teacherSignature.style.display = document.getElementById('show_teacher_signature').checked ? 'block' : 'none';
            // Update image if available (this is tricky with file input preview, so we rely on what's already saved or just show placeholder)
            // For now, let's just use the server-side value if available
            const teacherSigImg = teacherSignature.querySelector('img');
            const teacherSigUrl = <?= json_encode(!empty($reportCardSettings['teacher_signature']) ? $reportCardSettings['teacher_signature'] : null) ?>;
            
            if (teacherSigUrl) {
                if (!teacherSigImg) {
                    const img = document.createElement('img');
                    img.src = teacherSigUrl;
                    img.className = 'mx-auto h-12 object-contain';
                    // Insert before the line
                    teacherSignature.insertBefore(img, teacherSignature.querySelector('.border-b'));
                     // Remove the line? No, usually signature is on top of line
                }
            }
        }
        
        if (headteacherSignature) {
            headteacherSignature.style.display = document.getElementById('show_headteacher_signature').checked ? 'block' : 'none';
            const headTeacherSigImg = headteacherSignature.querySelector('img');
            const headTeacherSigUrl = <?= json_encode(!empty($reportCardSettings['headteacher_signature']) ? $reportCardSettings['headteacher_signature'] : null) ?>;
            
            if (headTeacherSigUrl) {
                if (!headTeacherSigImg) {
                     const img = document.createElement('img');
                    img.src = headTeacherSigUrl;
                    img.className = 'mx-auto h-12 object-contain';
                    headteacherSignature.insertBefore(img, headteacherSignature.querySelector('.border-b'));
                }
            }
        }
        
        if (parentSignature) parentSignature.style.display = document.getElementById('show_parent_signature').checked ? 'block' : 'none';
    }
    
    // Toggle signature upload fields
    const showHeadteacherSignatureCheckbox = document.getElementById('show_headteacher_signature');
    const headteacherSignatureField = document.getElementById('headteacher_signature_field');
    
    if (showHeadteacherSignatureCheckbox) {
        showHeadteacherSignatureCheckbox.addEventListener('change', function() {
            if (this.checked) {
                headteacherSignatureField.classList.remove('hidden');
            } else {
                headteacherSignatureField.classList.add('hidden');
            }
        });
    }

    const showTeacherSignatureCheckbox = document.getElementById('show_teacher_signature');
    const teacherSignatureField = document.getElementById('teacher_signature_field');
    
    if (showTeacherSignatureCheckbox) {
        showTeacherSignatureCheckbox.addEventListener('change', function() {
            if (this.checked) {
                teacherSignatureField.classList.remove('hidden');
            } else {
                teacherSignatureField.classList.add('hidden');
            }
        });
    }
    
    // Attach event listeners to all form elements
    formElements.forEach(element => {
        element.addEventListener('change', updatePreview);
        element.addEventListener('input', updatePreview);
    });
    
    // Initialize preview
    updatePreview();
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>