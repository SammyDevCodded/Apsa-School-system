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
                            </td>                        </tr>
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