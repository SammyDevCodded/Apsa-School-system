<?php 
$title = 'Exam Results'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <div>
                <h1 class="text-2xl font-semibold text-gray-900">Exam Results: <?= htmlspecialchars($exam['name'] ?? 'N/A') ?></h1>
                <?php if (!empty($exam['class_name'])): ?>
                    <p class="text-sm text-gray-500">Class: <?= htmlspecialchars($exam['class_name'] . ' ' . $exam['class_level']) ?></p>
                <?php endif; ?>
            </div>
            <div class="flex space-x-2">
                <div class="relative inline-block text-left">
                    <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 focus:outline-none" id="export-menu-button">
                        Export
                    </button>
                    <div class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden" id="export-menu">
                        <div class="py-1">
                            <a href="/exam_results/export?exam_id=<?= $exam['id'] ?? '' ?>" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Export to CSV
                            </a>
                            <a href="/exam_results/export?exam_id=<?= $exam['id'] ?? '' ?>&print=1" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" target="_blank">
                                Print/Export to PDF
                            </a>
                        </div>
                    </div>
                </div>
                <a href="/reports/academic" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                    Back to Academic Reports
                </a>
            </div>
        </div>

        <!-- Exam Results Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
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
                        <?php if (empty($results)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No exam results found for this exam.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($results as $result): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($result['first_name'] . ' ' . $result['last_name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($result['admission_no']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= !empty($result['student_class_name']) ? htmlspecialchars($result['student_class_name'] . ' ' . $result['student_class_level']) : 'N/A' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($result['subject_name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= number_format($result['marks'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php 
                                            switch($result['grade']) {
                                                case 'A+': echo 'bg-green-100 text-green-800'; break;
                                                case 'A': echo 'bg-green-100 text-green-800'; break;
                                                case 'B': echo 'bg-blue-100 text-blue-800'; break;
                                                case 'C': echo 'bg-yellow-100 text-yellow-800'; break;
                                                case 'D': echo 'bg-orange-100 text-orange-800'; break;
                                                case 'F': echo 'bg-red-100 text-red-800'; break;
                                                default: echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?= htmlspecialchars($result['grade']) ?>
                                        </span>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const exportMenuButton = document.getElementById('export-menu-button');
    const exportMenu = document.getElementById('export-menu');

    // Toggle export menu
    if (exportMenuButton && exportMenu) {
        exportMenuButton.addEventListener('click', function() {
            exportMenu.classList.toggle('hidden');
        });

        // Close export menu when clicking outside
        document.addEventListener('click', function(event) {
            if (!exportMenuButton.contains(event.target) && !exportMenu.contains(event.target)) {
                exportMenu.classList.add('hidden');
            }
        });
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>