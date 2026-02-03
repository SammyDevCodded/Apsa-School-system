<?php 
$title = 'Exam Results'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center">
            <div class="flex items-center">
                <h1 class="text-2xl font-semibold text-gray-900">Exam Results</h1>
                <div class="ml-4 flex space-x-2">
                    <a href="/exam_results/export?print=1" 
                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500"
                       target="_blank">
                        <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                        </svg>
                        Print
                    </a>
                    <a href="/exam_results/export" 
                       class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Export CSV
                    </a>
                </div>
            </div>
            <div class="flex space-x-2">
                <a href="/exam_results/create" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                    Record Result
                </a>
            </div>
        </div>

        <!-- Search and Filter Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Search and Filter</h3>
                <p class="mt-1 text-sm text-gray-500">Search exam results by various criteria</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <form id="searchForm" method="GET" action="/exam_results" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="Student name, admission no, exam..."
                               value="<?= htmlspecialchars($searchTerm ?? '') ?>">
                    </div>
                    
                    <div>
                        <label for="exam_name" class="block text-sm font-medium text-gray-700">Exam Name</label>
                        <input type="text" name="exam_name" id="exam_name" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="Exam name"
                               value="<?= htmlspecialchars($filters['exam_name'] ?? '') ?>">
                    </div>
                    
                    <div>
                        <label for="student_name" class="block text-sm font-medium text-gray-700">Student Name</label>
                        <input type="text" name="student_name" id="student_name" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                               placeholder="Student name"
                               value="<?= htmlspecialchars($filters['student_name'] ?? '') ?>">
                    </div>
                    
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                        <select name="class_id" id="class_id" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Classes</option>
                            <?php foreach ($classes ?? [] as $class): ?>
                                <option value="<?= $class['id'] ?>" <?= (isset($filters['class_id']) && $filters['class_id'] == $class['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($class['name'] . ' ' . $class['level']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                        <select name="subject_id" id="subject_id" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Subjects</option>
                            <?php foreach ($subjects ?? [] as $subject): ?>
                                <option value="<?= $subject['id'] ?>" <?= (isset($filters['subject_id']) && $filters['subject_id'] == $subject['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($subject['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="grade" class="block text-sm font-medium text-gray-700">Grade</label>
                        <select name="grade" id="grade" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Grades</option>
                            <?php foreach ($grades ?? [] as $grade): ?>
                                <option value="<?= $grade ?>" <?= (isset($filters['grade']) && $filters['grade'] == $grade) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($grade) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Date Range Filter -->
                    <div class="sm:col-span-6">
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="date_mode" id="date_mode" value="enabled" 
                                   <?= (isset($filters['date_mode']) && $filters['date_mode'] === 'enabled') ? 'checked' : '' ?>
                                   class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="date_mode" class="ml-2 block text-sm font-medium text-gray-700">
                                Enable Date Range Filter
                            </label>
                        </div>
                        
                        <div id="dateRangeFields" class="<?= (isset($filters['date_mode']) && $filters['date_mode'] === 'enabled') ? '' : 'hidden' ?>">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
                                <div>
                                    <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                                    <input type="date" name="date_from" id="date_from" 
                                           value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>" 
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                
                                <div>
                                    <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                                    <input type="date" name="date_to" id="date_to" 
                                           value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>" 
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="flex items-end">
                        <div class="space-x-2">
                            <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Search
                            </button>
                            <a href="/exam_results" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Exam Results Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Exam
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Class
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Student
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Admission No
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
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($results)): ?>
                            <tr>
                                <td colspan="9" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No exam results found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($results as $result): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($result['exam_name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= !empty($result['exam_date']) ? htmlspecialchars(date('M j, Y', strtotime($result['exam_date']))) : 'N/A' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= !empty($result['student_class_name']) ? htmlspecialchars($result['student_class_name'] . ' ' . $result['student_class_level']) : 'N/A' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($result['first_name'] . ' ' . $result['last_name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($result['admission_no']) ?>
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3 view-link" data-result-id="<?= $result['id'] ?>">
                                            View
                                        </a>
                                        <a href="#" class="text-indigo-600 hover:text-indigo-900 mr-3 edit-link" data-result-id="<?= $result['id'] ?>">
                                            Edit
                                        </a>
                                        <a href="/exam_results/<?= $result['id'] ?>/delete" class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('Are you sure you want to delete this exam result?')">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?php if ($pagination['current_page'] > 1): ?>
                        <a href="?<?= http_build_query(array_merge($filters ?? [], ['search' => $searchTerm ?? '', 'page' => $pagination['current_page'] - 1, 'per_page' => $pagination['per_page']])) ?>" 
                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                    <?php endif; ?>
                    
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                        <a href="?<?= http_build_query(array_merge($filters ?? [], ['search' => $searchTerm ?? '', 'page' => $pagination['current_page'] + 1, 'per_page' => $pagination['per_page']])) ?>" 
                           class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </a>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium"><?= (($pagination['current_page'] - 1) * $pagination['per_page']) + 1 ?></span>
                            to
                            <span class="font-medium"><?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total_records']) ?></span>
                            of
                            <span class="font-medium"><?= $pagination['total_records'] ?></span>
                            results
                        </p>
                        <div class="mt-2 flex items-center">
                            <label for="per_page" class="text-sm text-gray-700 mr-2">Items per page:</label>
                            <select id="per_page" name="per_page" class="border border-gray-300 rounded-md shadow-sm py-1 px-2 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="10" <?= $pagination['per_page'] == 10 ? 'selected' : '' ?>>10</option>
                                <option value="25" <?= $pagination['per_page'] == 25 ? 'selected' : '' ?>>25</option>
                                <option value="50" <?= $pagination['per_page'] == 50 ? 'selected' : '' ?>>50</option>
                                <option value="100" <?= $pagination['per_page'] == 100 ? 'selected' : '' ?>>100</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <?php if ($pagination['current_page'] > 1): ?>
                                <a href="?<?= http_build_query(array_merge($filters ?? [], ['search' => $searchTerm ?? '', 'page' => 1, 'per_page' => $pagination['per_page']])) ?>" 
                                   class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">First</span>
                                    First
                                </a>
                                <a href="?<?= http_build_query(array_merge($filters ?? [], ['search' => $searchTerm ?? '', 'page' => $pagination['current_page'] - 1, 'per_page' => $pagination['per_page']])) ?>" 
                                   class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            <?php endif; ?>
                            
                            <?php 
                            // Show page numbers (max 5 pages around current page)
                            $start = max(1, $pagination['current_page'] - 2);
                            $end = min($pagination['total_pages'], $pagination['current_page'] + 2);
                            
                            for ($i = $start; $i <= $end; $i++): ?>
                                <a href="?<?= http_build_query(array_merge($filters ?? [], ['search' => $searchTerm ?? '', 'page' => $i, 'per_page' => $pagination['per_page']])) ?>" 
                                   class="relative inline-flex items-center px-4 py-2 border text-sm font-medium <?= $i == $pagination['current_page'] ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' ?>">
                                    <?= $i ?>
                                </a>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                <a href="?<?= http_build_query(array_merge($filters ?? [], ['search' => $searchTerm ?? '', 'page' => $pagination['current_page'] + 1, 'per_page' => $pagination['per_page']])) ?>" 
                                   class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="?<?= http_build_query(array_merge($filters ?? [], ['search' => $searchTerm ?? '', 'page' => $pagination['total_pages'], 'per_page' => $pagination['per_page']])) ?>" 
                                   class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Last</span>
                                    Last
                                </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="viewModalTitle">
                                Exam Result Details
                            </h3>
                            <button type="button" class="close-view-modal text-gray-400 hover:text-gray-500">
                                <span class="text-2xl">&times;</span>
                            </button>
                        </div>
                        <div id="viewModalContent" class="mt-2">
                            <!-- Content will be loaded here via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="editModalTitle">
                                Edit Exam Result
                            </h3>
                            <button type="button" class="close-edit-modal text-gray-400 hover:text-gray-500">
                                <span class="text-2xl">&times;</span>
                            </button>
                        </div>
                        <div id="editModalContent" class="mt-2">
                            <!-- Content will be loaded here via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
    // Add event listeners for dynamic search
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('searchForm');
        const inputs = form.querySelectorAll('input, select');
        const dateModeCheckbox = document.getElementById('date_mode');
        const dateRangeFields = document.getElementById('dateRangeFields');
        const perPageSelect = document.getElementById('per_page');
        let searchTimeout;
        
        // Toggle date range fields based on checkbox
        if (dateModeCheckbox) {
            dateModeCheckbox.addEventListener('change', function() {
                if (this.checked) {
                    dateRangeFields.classList.remove('hidden');
                } else {
                    dateRangeFields.classList.add('hidden');
                    // Clear date fields when disabled
                    document.getElementById('date_from').value = '';
                    document.getElementById('date_to').value = '';
                }
            });
        }
        
        // Handle per_page selection change
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                // Update the form with the new per_page value
                const perPageInput = document.querySelector('input[name="per_page"]');
                if (perPageInput) {
                    perPageInput.value = this.value;
                } else {
                    // Create hidden input if it doesn't exist
                    const hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'per_page';
                    hiddenInput.value = this.value;
                    form.appendChild(hiddenInput);
                }
                // Submit the form
                form.submit();
            });
        }
        
        // Export dropdown functionality
        const exportButton = document.getElementById('export-menu-button');
        const exportMenu = document.getElementById('export-menu');
        
        if (exportButton && exportMenu) {
            exportButton.addEventListener('click', function() {
                exportMenu.classList.toggle('hidden');
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!exportButton.contains(event.target) && !exportMenu.contains(event.target)) {
                    exportMenu.classList.add('hidden');
                }
            });
        }
        
        // Add real-time search functionality
        inputs.forEach(input => {
            if (input.type === 'text' || input.type === 'date') {
                input.addEventListener('input', function() {
                    clearTimeout(searchTimeout);
                    searchTimeout = setTimeout(function() {
                        submitForm();
                    }, 500);
                });
            }
            
            input.addEventListener('change', function() {
                // Skip per_page select as it's handled separately
                if (input.id !== 'per_page') {
                    submitForm();
                }
            });
        });
        
        // Attach event listeners for the initial page load
        console.log('Calling attachEventListeners on page load');
        attachEventListeners();
        
        // Function to submit form via AJAX
        function submitForm() {
            const formData = new FormData(form);
            const queryString = new URLSearchParams(formData).toString();
            
            fetch('/exam_results?' + queryString, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Update the table content
                const tableContainer = document.querySelector('.bg-white.shadow.overflow-hidden.sm:rounded-lg');
                if (tableContainer) {
                    // Replace only the table and pagination part
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const newTable = doc.querySelector('.bg-white.shadow.overflow-hidden.sm:rounded-lg');
                    if (newTable) {
                        tableContainer.innerHTML = newTable.innerHTML;
                        // Reattach event listeners for the new content
                        attachEventListeners();
                    }
                }
            })
            .catch(error => {
                console.error('Error:', error);
            });
        }
        
        // Function to attach event listeners to new elements
        function attachEventListeners() {
            console.log('Attaching event listeners...');
            
            // Reattach export dropdown functionality
            const exportButton = document.getElementById('export-menu-button');
            const exportMenu = document.getElementById('export-menu');
            
            if (exportButton && exportMenu) {
                exportButton.addEventListener('click', function() {
                    exportMenu.classList.toggle('hidden');
                });
            }
            
            // Update export links with current filters
            const exportLinks = document.querySelectorAll('#export-menu a');
            exportLinks.forEach(link => {
                const url = new URL(link.href);
                const form = document.getElementById('searchForm');
                const formData = new FormData(form);
                
                // Clear existing search params
                url.search = '';
                
                // Add current form data
                for (const [key, value] of formData.entries()) {
                    if (value) {
                        url.searchParams.append(key, value);
                    }
                }
                
                link.href = url.toString();
            });
            
            // Reattach date mode toggle functionality
            const dateModeCheckbox = document.getElementById('date_mode');
            const dateRangeFields = document.getElementById('dateRangeFields');
            
            if (dateModeCheckbox) {
                dateModeCheckbox.addEventListener('change', function() {
                    if (this.checked) {
                        dateRangeFields.classList.remove('hidden');
                    } else {
                        dateRangeFields.classList.add('hidden');
                        // Clear date fields when disabled
                        document.getElementById('date_from').value = '';
                        document.getElementById('date_to').value = '';
                    }
                });
            }
            
            // Reattach per_page selection functionality
            const perPageSelect = document.getElementById('per_page');
            if (perPageSelect) {
                perPageSelect.addEventListener('change', function() {
                    // Update the form with the new per_page value
                    const perPageInput = document.querySelector('input[name="per_page"]');
                    if (perPageInput) {
                        perPageInput.value = this.value;
                    } else {
                        // Create hidden input if it doesn't exist
                        const hiddenInput = document.createElement('input');
                        hiddenInput.type = 'hidden';
                        hiddenInput.name = 'per_page';
                        hiddenInput.value = this.value;
                        form.appendChild(hiddenInput);
                    }
                    // Submit the form
                    form.submit();
                });
            }
            
            // Ensure view links work properly after AJAX updates
            const viewLinks = document.querySelectorAll('.view-link');
            console.log('Found', viewLinks.length, 'view links');
            viewLinks.forEach((link, index) => {
                console.log('Attaching event listener to view link', index, link);
                // Remove any existing event listeners to prevent interference
                link.removeEventListener('click', handleViewLinkClick);
                // Add new event listener
                link.addEventListener('click', handleViewLinkClick);
            });
            
            // Add event listeners for edit links
            const editLinks = document.querySelectorAll('.edit-link');
            console.log('Found', editLinks.length, 'edit links');
            editLinks.forEach((link, index) => {
                console.log('Attaching event listener to edit link', index, link);
                link.removeEventListener('click', handleEditLinkClick);
                link.addEventListener('click', handleEditLinkClick);
            });
            
            // Add event listeners for modal close buttons
            document.querySelectorAll('.close-view-modal').forEach(button => {
                button.removeEventListener('click', closeViewModal);
                button.addEventListener('click', closeViewModal);
            });
            
            document.querySelectorAll('.close-edit-modal').forEach(button => {
                button.removeEventListener('click', closeEditModal);
                button.addEventListener('click', closeEditModal);
            });
            
            console.log('Finished attaching event listeners');
        }
        
        // Function to handle view link clicks
        function handleViewLinkClick(e) {
            e.preventDefault();
            const resultId = this.getAttribute('data-result-id');
            console.log('View link clicked, resultId:', resultId);
            openViewModal(resultId);
        }        
        // Function to handle edit link clicks
        function handleEditLinkClick(e) {
            e.preventDefault();
            const resultId = this.getAttribute('data-result-id');
            openEditModal(resultId);
        }
        
        // Function to open view modal
        function openViewModal(resultId) {
            console.log('Opening view modal for resultId:', resultId);
            const modal = document.getElementById('viewModal');
            const modalContent = document.getElementById('viewModalContent');
            
            // Show loading indicator
            modalContent.innerHTML = '<div class="text-center py-8"><div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-indigo-600 border-t-transparent"></div></div>';
            
            // Show modal
            modal.classList.remove('hidden');
            
            // Fetch content via AJAX
            fetch(`/exam_results/${resultId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                console.log('AJAX response received for resultId:', resultId);
                return response.text();
            })
            .then(html => {
                console.log('AJAX response parsed for resultId:', resultId);
                // Parse the HTML to extract only the content we need
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const content = doc.querySelector('.max-w-7xl');
                
                if (content) {
                    modalContent.innerHTML = content.innerHTML;
                    
                    // Add close button functionality to the view modal content
                    const backButton = modalContent.querySelector('a[href="/exam_results"]');
                    if (backButton) {
                        backButton.addEventListener('click', function(e) {
                            e.preventDefault();
                            closeViewModal();
                        });
                    }
                } else {
                    modalContent.innerHTML = '<div class="text-center py-8 text-red-500">Error loading content</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = '<div class="text-center py-8 text-red-500">Error loading content</div>';
            });
        }
        
        // Function to open edit modal
        function openEditModal(resultId) {
            const modal = document.getElementById('editModal');
            const modalContent = document.getElementById('editModalContent');
            
            // Show loading indicator
            modalContent.innerHTML = '<div class="text-center py-8"><div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-indigo-600 border-t-transparent"></div></div>';
            
            // Show modal
            modal.classList.remove('hidden');
            
            // Fetch content via AJAX
            fetch(`/exam_results/${resultId}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.text())
            .then(html => {
                // Parse the HTML to extract only the content we need
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                const content = doc.querySelector('.max-w-7xl');
                
                if (content) {
                    modalContent.innerHTML = content.innerHTML;
                    
                    // Handle form submission within the modal
                    const form = modalContent.querySelector('form');
                    if (form) {
                        form.addEventListener('submit', function(e) {
                            e.preventDefault();
                            
                            // Show loading state
                            const submitBtn = form.querySelector('button[type="submit"]');
                            const originalText = submitBtn ? submitBtn.textContent : '';
                            if (submitBtn) {
                                submitBtn.textContent = 'Saving...';
                                submitBtn.disabled = true;
                            }
                            
                            // Submit form via AJAX
                            const formData = new FormData(form);
                            fetch(form.action, {
                                method: 'POST',
                                body: formData,
                                headers: {
                                    'X-Requested-With': 'XMLHttpRequest'
                                }
                            })
                            .then(response => response.json())
                            .then(data => {
                                if (data.success) {
                                    // Close modal and refresh the table
                                    closeEditModal();
                                    submitForm(); // Refresh the main table
                                    // Show success message
                                    showMessage(data.message || 'Exam result updated successfully', 'success');
                                } else {
                                    // Show error message
                                    showMessage(data.message || 'Failed to update exam result', 'error');
                                }
                            })
                            .catch(error => {
                                console.error('Error:', error);
                                showMessage('An error occurred while saving the exam result', 'error');
                            })
                            .finally(() => {
                                // Restore button state
                                if (submitBtn) {
                                    submitBtn.textContent = originalText;
                                    submitBtn.disabled = false;
                                }
                            });
                        });
                    }
                    
                    // Add close button functionality to the edit modal content
                    const backButton = modalContent.querySelector('a[href="/exam_results"]');
                    if (backButton) {
                        backButton.addEventListener('click', function(e) {
                            e.preventDefault();
                            closeEditModal();
                        });
                    }
                } else {
                    modalContent.innerHTML = '<div class="text-center py-8 text-red-500">Error loading content</div>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = '<div class="text-center py-8 text-red-500">Error loading content</div>';
            });
        }
        
        // Function to close view modal
        function closeViewModal() {
            const modal = document.getElementById('viewModal');
            modal.classList.add('hidden');
        }
        
        // Function to close edit modal
        function closeEditModal() {
            const modal = document.getElementById('editModal');
            modal.classList.add('hidden');
        }
        
        // Function to show message
        function showMessage(message, type) {
            // Create or update flash message element
            let flashMessages = document.getElementById('flash-messages');
            if (!flashMessages) {
                flashMessages = document.createElement('div');
                flashMessages.id = 'flash-messages';
                document.querySelector('.max-w-7xl').prepend(flashMessages);
            }
            
            // Create flash message element
            const messageDiv = document.createElement('div');
            messageDiv.className = `rounded px-4 py-3 mb-4 text-sm ${
                type === 'success' 
                    ? 'bg-green-100 border border-green-400 text-green-700' 
                    : 'bg-red-100 border border-red-400 text-red-700'
            }`;
            messageDiv.textContent = message;
            
            // Add to flash messages container
            flashMessages.innerHTML = '';
            flashMessages.appendChild(messageDiv);
            
            // Scroll to top to see the message
            window.scrollTo({ top: 0, behavior: 'smooth' });
            
            // Remove message after 5 seconds
            setTimeout(() => {
                if (messageDiv.parentNode) {
                    messageDiv.parentNode.removeChild(messageDiv);
                }
            }, 5000);
        }
    });
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>