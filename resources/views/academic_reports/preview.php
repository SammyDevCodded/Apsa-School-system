<?php 
$title = 'Preview Academic Reports'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6 no-print">
            <h1 class="text-2xl font-semibold text-gray-900">Preview Academic Reports</h1>
            <div class="flex space-x-2">
                <button id="printPreviewBtn" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700">
                    Print Preview
                </button>
                <form action="/academic-reports/export-pdf" method="GET" target="_blank">
                    <input type="hidden" name="exam_id" value="<?= $exam['id'] ?>">
                    <input type="hidden" name="class_id" value="<?= $class['id'] ?>">
                    <input type="hidden" name="student_ids" value="<?= implode(',', array_column($students, 'id')) ?>">
                    <button type="submit" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700">
                        Export to PDF
                    </button>
                </form>
                <form action="/academic-reports/print" method="GET" target="printWindow">
                    <input type="hidden" name="exam_id" value="<?= $exam['id'] ?>">
                    <input type="hidden" name="class_id" value="<?= $class['id'] ?>">
                    <input type="hidden" name="student_ids" value="<?= implode(',', array_column($students, 'id')) ?>">
                    <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                        Print All Reports
                    </button>
                </form>
                <a href="/academic-reports" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                    Back
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6 no-print">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Selected Parameters</h2>
                <div class="grid grid-cols-1 md:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Exam</p>
                        <p class="font-medium"><?= htmlspecialchars($exam['name']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Class</p>
                        <p class="font-medium"><?= htmlspecialchars($class['name'] . ' ' . $class['level']) ?></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Students</p>
                        <p class="font-medium"><?= count($students) ?> selected</p>
                    </div>
                </div>
            </div>
        </div>

        <?php if (isset($settings['show_grading_scale']) && $settings['show_grading_scale']): ?>
        <!-- Grading Scale -->
        <div class="mb-6 no-print" id="gradingScale">
            <h3 class="text-md font-semibold mb-2">Grading Scale</h3>
            <table class="min-w-full text-sm border border-gray-300">
                <thead>
                    <tr class="bg-gray-100">
                        <th class="border border-gray-300 px-2 py-1">Grade</th>
                        <th class="border border-gray-300 px-2 py-1">Range</th>
                        <th class="border border-gray-300 px-2 py-1">Remarks</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (isset($gradingScale) && !empty($gradingScale)): ?>
                        <?php foreach ($gradingScale as $rule): ?>
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
        <?php endif; ?>

        <?php foreach ($students as $student): ?>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex justify-between items-center mb-4 no-print">
                    <h3 class="text-lg font-medium text-gray-900">
                        <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?> 
                        (<?= htmlspecialchars($student['admission_no']) ?>)
                    </h3>
                    <form action="/academic-reports/print" method="GET" target="_blank">
                        <input type="hidden" name="exam_id" value="<?= $exam['id'] ?>">
                        <input type="hidden" name="class_id" value="<?= $class['id'] ?>">
                        <input type="hidden" name="student_ids" value="<?= $student['id'] ?>">
                        <button type="submit" class="text-indigo-600 hover:text-indigo-900 text-sm font-medium">
                            Print Individual Report
                        </button>
                    </form>
                </div>
                
                <?php if (isset($resultsByStudent[$student['id']]) && !empty($resultsByStudent[$student['id']])): ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subject
                                </th>
                                <?php if (isset($settings['show_class_score']) && $settings['show_class_score']): ?>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Class Score
                                </th>
                                <?php endif; ?>
                                <?php if (isset($settings['show_exam_score']) && $settings['show_exam_score']): ?>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Exam Score
                                </th>
                                <?php endif; ?>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Score
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Grade
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Remarks
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($resultsByStudent[$student['id']] as $result): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($result['subject_name']) ?>
                                </td>
                                <?php if (isset($settings['show_class_score']) && $settings['show_class_score']): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= isset($result['classwork_marks']) ? number_format($result['classwork_marks'], 2) : 'N/A' ?>
                                </td>
                                <?php endif; ?>
                                <?php if (isset($settings['show_exam_score']) && $settings['show_exam_score']): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= isset($result['exam_marks']) ? number_format($result['exam_marks'], 2) : 'N/A' ?>
                                </td>
                                <?php endif; ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= isset($result['total_score']) ? number_format($result['total_score'], 2) : 'N/A' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= isset($result['grade']) ? htmlspecialchars($result['grade']) : 'N/A' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= isset($result['remark']) ? htmlspecialchars($result['remark']) : 'N/A' ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php else: ?>
                <div class="text-center py-8">
                    <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                    <h3 class="mt-2 text-sm font-medium text-gray-900">No results found</h3>
                    <p class="mt-1 text-sm text-gray-500">
                        No exam results have been submitted for this student.
                    </p>
                </div>
                <?php endif; ?>
            </div>
        </div>
        <?php endforeach; ?>
    </div>
</div>

<style>
@media print {
    /* Hide navigation and other UI elements when printing */
    nav, header, footer, .no-print, #printPreviewBtn {
        display: none !important;
    }
    
    /* Hide the top bar and buttons when printing */
    .flex.justify-between.items-center.mb-6, 
    .bg-white.shadow.overflow-hidden.sm\:rounded-lg.mb-6,
    .mb-6#gradingScale,
    .flex.justify-between.items-center.mb-4 {
        display: none !important;
    }
    
    /* Ensure the content takes full width when printing */
    .max-w-7xl {
        max-width: 100% !important;
    }
    
    .px-4.py-6.sm\:px-0 {
        padding: 0 !important;
    }
    
    /* Add page breaks between student reports */
    .bg-white.shadow.overflow-hidden.sm\:rounded-lg.mb-8 {
        page-break-after: always;
        break-after: page;
    }
    
    /* Remove shadows and rounded corners for cleaner print */
    .bg-white.shadow.overflow-hidden.sm\:rounded-lg.mb-8,
    .bg-white.shadow.overflow-hidden.sm\:rounded-lg {
        box-shadow: none !important;
        border-radius: 0 !important;
        border: none !important;
    }
    
    /* Ensure tables are printed properly */
    table {
        page-break-inside: auto;
    }
    
    tr {
        page-break-inside: avoid;
        page-break-after: auto;
    }
    
    thead {
        display: table-header-group;
    }
    
    tfoot {
        display: table-footer-group;
    }
    
    /* Show only the actual report card content */
    body * {
        visibility: hidden;
    }
    
    .max-w-7xl, .max-w-7xl * {
        visibility: visible;
    }
    
    .max-w-7xl {
        position: absolute;
        left: 0;
        top: 0;
        width: 100%;
    }
}
</style>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const printPreviewBtn = document.getElementById('printPreviewBtn');
    
    if (printPreviewBtn) {
        printPreviewBtn.addEventListener('click', function(e) {
            e.preventDefault();
            window.print();
        });
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>