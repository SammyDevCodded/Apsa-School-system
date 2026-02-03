<?php 
// Function to build pagination URLs
function buildPaginationUrl($page, $perPage, $searchTerm = '', $classId = '', $dateFrom = '', $dateTo = '', $enableDateFilter = false) {
    $params = [
        'page' => $page,
        'per_page' => $perPage
    ];
    
    if ($searchTerm) {
        $params['search'] = $searchTerm;
    }
    
    if ($classId) {
        $params['class_id'] = $classId;
    }
    
    if ($enableDateFilter) {
        $params['enable_date_filter'] = 1;
        if ($dateFrom) {
            $params['date_from'] = $dateFrom;
        }
        if ($dateTo) {
            $params['date_to'] = $dateTo;
        }
    }
    
    return '/receipts?' . http_build_query($params);
}

$title = 'Receipts'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Receipts</h1>
        </div>

        <!-- Filters -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" value="<?= htmlspecialchars($searchTerm ?? '') ?>"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Student name or admission no">
                    </div>

                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                        <select name="class_id" id="class_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Classes</option>
                            <?php foreach ($allClasses ?? [] as $class): ?>
                                <option value="<?= $class['id'] ?>" <?= (isset($classId) && $classId == $class['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($class['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>

                    <div class="lg:col-span-2">
                        <div class="flex items-end space-x-4">
                            <div class="flex items-center">
                                <input id="enable_date_filter" name="enable_date_filter" type="checkbox" 
                                    class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded"
                                    <?= isset($enableDateFilter) && $enableDateFilter ? 'checked' : '' ?>>
                                <label for="enable_date_filter" class="ml-2 block text-sm text-gray-900">
                                    Filter by Date
                                </label>
                            </div>
                            
                            <div id="date_filter_fields" class="<?= (!isset($enableDateFilter) || !$enableDateFilter) ? 'hidden' : '' ?> flex-1 grid grid-cols-2 gap-4">
                                <div>
                                    <label for="date_from" class="block text-sm font-medium text-gray-700">From</label>
                                    <input type="date" name="date_from" id="date_from" value="<?= htmlspecialchars($dateFrom ?? '') ?>"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                <div>
                                    <label for="date_to" class="block text-sm font-medium text-gray-700">To</label>
                                    <input type="date" name="date_to" id="date_to" value="<?= htmlspecialchars($dateTo ?? '') ?>"
                                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                            
                            <div>
                                <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Filter
                                </button>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Receipts Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <div class="flex flex-col">
                    <div class="-my-2 overflow-x-auto sm:-mx-6 lg:-mx-8">
                        <div class="py-2 align-middle inline-block min-w-full sm:px-6 lg:px-8">
                            <div class="shadow overflow-hidden border-b border-gray-200 sm:rounded-lg">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Student
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Amount
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Method
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Date
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Fee
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Receipt Date
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php if (empty($receipts)): ?>
                                            <tr>
                                                <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                                    No receipts found
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($receipts as $receipt): ?>
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?= htmlspecialchars($receipt['first_name'] . ' ' . $receipt['last_name'] ?? '') ?>
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            <?= htmlspecialchars($receipt['admission_no'] ?? '') ?>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        ₵<?= number_format($receipt['amount'] ?? 0, 2) ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <?php
                                                        $methodLabels = [
                                                            'cash' => 'Cash',
                                                            'cheque' => 'Cheque',
                                                            'bank_transfer' => 'Bank Transfer',
                                                            'mobile_money' => 'Mobile Money',
                                                            'other' => 'Other'
                                                        ];
                                                        $method = $receipt['method'] ?? 'other';
                                                        $methodLabel = $methodLabels[$method] ?? ucfirst(str_replace('_', ' ', $method));
                                                        ?>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <?= htmlspecialchars($methodLabel) ?>
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <?= date('M j, Y', strtotime($receipt['date'] ?? '')) ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <?= htmlspecialchars($receipt['fee_name'] ?? 'N/A') ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <?= date('M j, Y g:i A', strtotime($receipt['created_at'] ?? '')) ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="/receipts/<?= $receipt['payment_id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                            View Receipt
                                                        </a>
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
                
                <!-- Pagination -->
                <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <?php if ($pagination['current_page'] > 1): ?>
                                <a href="<?= buildPaginationUrl($pagination['current_page'] - 1, $perPage ?? 10, $searchTerm ?? '', $classId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false) ?>" 
                                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                <a href="<?= buildPaginationUrl($pagination['current_page'] + 1, $perPage ?? 10, $searchTerm ?? '', $classId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false) ?>" 
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
                                    <span class="font-medium"><?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total']) ?></span>
                                    of
                                    <span class="font-medium"><?= $pagination['total'] ?></span>
                                    results
                                </p>
                            </div>
                            <div>
                                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                    <?php if ($pagination['current_page'] > 1): ?>
                                        <a href="<?= buildPaginationUrl(1, $perPage ?? 10, $searchTerm ?? '', $classId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false) ?>" 
                                           class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span>First</span>
                                        </a>
                                        <a href="<?= buildPaginationUrl($pagination['current_page'] - 1, $perPage ?? 10, $searchTerm ?? '', $classId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false) ?>" 
                                           class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Previous</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                    <?php endif; ?>
                                    
                                    <?php 
                                    // Calculate start and end page numbers to display
                                    $startPage = max(1, $pagination['current_page'] - 2);
                                    $endPage = min($pagination['total_pages'], $pagination['current_page'] + 2);
                                    
                                    // Adjust if at the beginning or end
                                    if ($startPage === 1) {
                                        $endPage = min(5, $pagination['total_pages']);
                                    } elseif ($endPage === $pagination['total_pages']) {
                                        $startPage = max(1, $pagination['total_pages'] - 4);
                                    }
                                    
                                    for ($i = $startPage; $i <= $endPage; $i++): ?>
                                        <a href="<?= buildPaginationUrl($i, $perPage ?? 10, $searchTerm ?? '', $classId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false) ?>" 
                                           class="relative inline-flex items-center px-4 py-2 border <?= $i == $pagination['current_page'] ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' ?> text-sm font-medium">
                                            <?= $i ?>
                                        </a>
                                    <?php endfor; ?>
                                    
                                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                        <a href="<?= buildPaginationUrl($pagination['current_page'] + 1, $perPage ?? 10, $searchTerm ?? '', $classId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false) ?>" 
                                           class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <a href="<?= buildPaginationUrl($pagination['total_pages'], $perPage ?? 10, $searchTerm ?? '', $classId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false) ?>" 
                                           class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span>Last</span>
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
</div>

<script>
// Handle date filter checkbox toggle
document.addEventListener('DOMContentLoaded', function() {
    const enableDateFilterCheckbox = document.getElementById('enable_date_filter');
    const dateFilterFields = document.getElementById('date_filter_fields');
    
    if (enableDateFilterCheckbox) {
        enableDateFilterCheckbox.addEventListener('change', function() {
            if (this.checked) {
                dateFilterFields.classList.remove('hidden');
            } else {
                dateFilterFields.classList.add('hidden');
                // Clear date values when hiding
                document.getElementById('date_from').value = '';
                document.getElementById('date_to').value = '';
            }
        });
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>