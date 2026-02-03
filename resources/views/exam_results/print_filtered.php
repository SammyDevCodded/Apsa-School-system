<?php 
$title = 'Exam Results Report'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Exam Results Report</h1>
                <?php if (!empty($currentAcademicYear)): ?>
                    <p class="mt-1 text-sm text-gray-500">
                        Academic Year: <?= htmlspecialchars($currentAcademicYear['name']) ?> 
                        (<?= htmlspecialchars($currentAcademicYear['term']) ?>)
                    </p>
                <?php endif; ?>
            </div>
            <button onclick="window.print()" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                Print
            </button>
        </div>

        <?php if (empty($resultsByExam)): ?>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <p class="text-center text-gray-500">No exam results found.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($resultsByExam as $examData): ?>
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
                    <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                        <h2 class="text-lg leading-6 font-medium text-gray-900">
                            <?= htmlspecialchars($examData['exam']['name'] ?? 'Unknown Exam') ?>
                        </h2>
                        <?php if (!empty($examData['exam']['date'])): ?>
                            <p class="mt-1 text-sm text-gray-500">
                                Date: <?= htmlspecialchars(date('M j, Y', strtotime($examData['exam']['date']))) ?>
                            </p>
                        <?php endif; ?>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Student
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Admission No
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Class
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Subject
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Marks
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Grade
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (empty($examData['results'])): ?>
                                    <tr>
                                        <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                            No results for this exam.
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($examData['results'] as $result): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= htmlspecialchars(($result['first_name'] ?? '') . ' ' . ($result['last_name'] ?? '')) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= htmlspecialchars($result['admission_no'] ?? 'N/A') ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= !empty($result['student_class_name']) ? htmlspecialchars($result['student_class_name'] . ' ' . ($result['student_class_level'] ?? '')) : 'N/A' ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= htmlspecialchars($result['subject_name'] ?? 'N/A') ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= number_format($result['marks'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= htmlspecialchars($result['grade'] ?? 'N/A') ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-8">
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="text-sm text-gray-500">
                    Total Exams: <?= count($resultsByExam) ?>
                </div>
                <div class="text-sm text-gray-500">
                    Generated on: <?= date('M j, Y g:i A') ?>
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
        .max-w-7xl, .max-w-7xl * {
            visibility: visible;
        }
        .max-w-7xl {
            position: absolute;
            left: 0;
            top: 0;
            width: 100%;
        }
        button {
            display: none;
        }
    }
</style>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>