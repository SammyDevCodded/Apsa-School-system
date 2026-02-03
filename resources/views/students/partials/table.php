<?php
// Define student category labels
$studentCategoryLabels = [
    'regular_day' => 'Day',
    'regular_boarding' => 'Boarding',
    'international' => 'Intl'
];
?>

<div class="overflow-x-auto">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Profile
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Admission No
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Name
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Class
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Category
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Guardian
                </th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Actions
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            <?php if (empty($students)): ?>
                <tr>
                    <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                        No students found.
                    </td>
                </tr>
            <?php else: ?>
                <?php foreach ($students as $student): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?php if (!empty($student['profile_picture'])): ?>
                                <img src="/storage/uploads/<?= htmlspecialchars($student['profile_picture']) ?>" alt="Profile Picture" class="h-10 w-10 object-cover rounded-full" onerror="this.src='/images/default-profile.png';">
                            <?php else: ?>
                                <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                    <span class="text-gray-500 text-sm"><?= substr($student['first_name'], 0, 1) ?></span>
                                </div>
                            <?php endif; ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?= htmlspecialchars($student['admission_no']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?= htmlspecialchars($student['class_name'] ?? 'N/A') ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                <?= htmlspecialchars($studentCategoryLabels[$student['student_category'] ?? 'regular_day']) ?>
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            <?= htmlspecialchars($student['guardian_name'] ?? 'N/A') ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <a href="/students/<?= $student['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                View
                            </a>
                            <a href="/students/<?= $student['id'] ?>/edit" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                Edit
                            </a>
                            <a href="/students/<?= $student['id'] ?>/delete" class="text-red-600 hover:text-red-900"
                               onclick="return confirm('Are you sure you want to delete this student?')">
                                Delete
                            </a>
                        </td>
                    </tr>
                <?php endforeach; ?>
            <?php endif; ?>
        </tbody>
    </table>
</div>

<?php if (!empty($students) && $pagination['total_pages'] > 1): ?>
<div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
    <div class="flex-1 flex justify-between sm:hidden">
        <?php if ($pagination['current_page'] > 1): ?>
            <a href="?<?= http_build_query(array_merge($filters, ['search' => $searchTerm, 'page' => $pagination['current_page'] - 1, 'per_page' => $pagination['per_page']])) ?>" 
               class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                Previous
            </a>
        <?php endif; ?>
        
        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
            <a href="?<?= http_build_query(array_merge($filters, ['search' => $searchTerm, 'page' => $pagination['current_page'] + 1, 'per_page' => $pagination['per_page']])) ?>" 
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
                    <a href="?<?= http_build_query(array_merge($filters, ['search' => $searchTerm, 'page' => 1, 'per_page' => $pagination['per_page']])) ?>" 
                       class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">First</span>
                        First
                    </a>
                    <a href="?<?= http_build_query(array_merge($filters, ['search' => $searchTerm, 'page' => $pagination['current_page'] - 1, 'per_page' => $pagination['per_page']])) ?>" 
                       class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Previous</span>
                        &laquo;
                    </a>
                <?php endif; ?>
                
                <?php 
                // Show page numbers
                $startPage = max(1, $pagination['current_page'] - 2);
                $endPage = min($pagination['total_pages'], $pagination['current_page'] + 2);
                
                for ($i = $startPage; $i <= $endPage; $i++): ?>
                    <a href="?<?= http_build_query(array_merge($filters, ['search' => $searchTerm, 'page' => $i, 'per_page' => $pagination['per_page']])) ?>" 
                       class="relative inline-flex items-center px-4 py-2 border <?= $i == $pagination['current_page'] ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' ?> text-sm font-medium">
                        <?= $i ?>
                    </a>
                <?php endfor; ?>
                
                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <a href="?<?= http_build_query(array_merge($filters, ['search' => $searchTerm, 'page' => $pagination['current_page'] + 1, 'per_page' => $pagination['per_page']])) ?>" 
                       class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                        <span class="sr-only">Next</span>
                        &raquo;
                    </a>
                    <a href="?<?= http_build_query(array_merge($filters, ['search' => $searchTerm, 'page' => $pagination['total_pages'], 'per_page' => $pagination['per_page']])) ?>" 
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

<script>
    // Handle per_page selection change in AJAX-loaded content
    document.addEventListener('DOMContentLoaded', function() {
        const perPageSelect = document.getElementById('per_page');
        if (perPageSelect) {
            perPageSelect.addEventListener('change', function() {
                // Find the search form in the parent document
                const form = document.querySelector('#searchForm');
                if (form) {
                    // Update the form with the new per_page value
                    const perPageInput = form.querySelector('input[name="per_page"]');
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
                }
            });
        }
    });
</script>