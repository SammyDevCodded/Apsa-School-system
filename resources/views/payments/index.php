<?php 
// Function to build pagination URLs
function buildPaginationUrl($page, $perPage, $searchTerm = '', $classId = '', $dateFrom = '', $dateTo = '', $enableDateFilter = false, $academicYearId = '') {
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
    
    if ($academicYearId) {
        $params['academic_year_id'] = $academicYearId;
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
    
    return '/payments?' . http_build_query($params);
}

$title = 'Payments'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Payments</h1>
            <button id="add-payment-btn" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                Add Payment
            </button>
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
                        <label for="academic_year_id" class="block text-sm font-medium text-gray-700">Academic Year</label>
                        <select name="academic_year_id" id="academic_year_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Academic Years</option>
                            <?php foreach ($academicYears ?? [] as $year): ?>
                                <option value="<?= $year['id'] ?>" <?= (isset($academicYearId) && $academicYearId == $year['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($year['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
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

        <!-- Payments Table -->
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
                                                Academic Year & Term
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Fee
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Remarks
                                            </th>
                                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                Actions
                                            </th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php if (empty($payments)): ?>
                                            <tr>
                                                <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                                    No payments found
                                                </td>
                                            </tr>
                                        <?php else: ?>
                                            <?php foreach ($payments as $payment): ?>
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap">
                                                        <div class="text-sm font-medium text-gray-900">
                                                            <?= htmlspecialchars($payment['student_name'] ?? '') ?>
                                                        </div>
                                                        <div class="text-sm text-gray-500">
                                                            <?= htmlspecialchars($payment['admission_no'] ?? '') ?>
                                                        </div>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        ₵<?= number_format($payment['amount'] ?? 0, 2) ?>
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
                                                        $method = $payment['method'] ?? 'other';
                                                        $methodLabel = $methodLabels[$method] ?? ucfirst(str_replace('_', ' ', $method));
                                                        ?>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                            <?= htmlspecialchars($methodLabel) ?>
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <?= date('M j, Y', strtotime($payment['date'] ?? '')) ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <?= htmlspecialchars($payment['academic_year_name'] ?? 'N/A') ?>
                                                        <?php if (!empty($payment['term'])): ?>
                                                            <br><span class="text-xs text-gray-400">(<?= htmlspecialchars($payment['term']) ?>)</span>
                                                        <?php endif; ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <?= htmlspecialchars($payment['fee_name'] ?? 'N/A') ?>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                                        <?= htmlspecialchars($payment['remarks'] ?? '') ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                                        <a href="/receipts/<?= $payment['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                            Receipt
                                                        </a>
                                                        <button class="view-payment-btn text-indigo-600 hover:text-indigo-900 mr-3" data-payment-id="<?= $payment['id'] ?>">
                                                            View
                                                        </button>
                                                        <button class="edit-payment-btn text-gray-600 hover:text-gray-900 mr-3" data-payment-id="<?= $payment['id'] ?>">
                                                            Edit
                                                        </button>
                                                        <a href="/payments/<?= $payment['id'] ?>/delete" 
                                                           class="text-red-600 hover:text-red-900"
                                                           onclick="return confirm('Are you sure you want to delete this payment?')">
                                                            Delete
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
                
                <?php if (!empty($payments)): ?>
                    <div class="mt-4">
                        <p class="text-sm text-gray-700">
                            Showing <?= count($payments) ?> of <?= $totalPayments ?> payments
                        </p>
                    </div>
                <?php endif; ?>
                
                <!-- Pagination -->
                <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                        <div class="flex-1 flex justify-between sm:hidden">
                            <?php if ($pagination['current_page'] > 1): ?>
                                <a href="<?= buildPaginationUrl($pagination['current_page'] - 1, $perPage ?? 10, $searchTerm ?? '', $classId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $academicYearId ?? '') ?>" 
                                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                    Previous
                                </a>
                            <?php endif; ?>
                            
                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                <a href="<?= buildPaginationUrl($pagination['current_page'] + 1, $perPage ?? 10, $searchTerm ?? '', $classId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $academicYearId ?? '') ?>" 
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
                                        <a href="<?= buildPaginationUrl(1, $perPage ?? 10, $searchTerm ?? '', $classId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $academicYearId ?? '') ?>" 
                                           class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span>First</span>
                                        </a>
                                        <a href="<?= buildPaginationUrl($pagination['current_page'] - 1, $perPage ?? 10, $searchTerm ?? '', $classId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $academicYearId ?? '') ?>" 
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
                                        <a href="<?= buildPaginationUrl($i, $perPage ?? 10, $searchTerm ?? '', $classId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $academicYearId ?? '') ?>" 
                                           class="relative inline-flex items-center px-4 py-2 border <?= $i == $pagination['current_page'] ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' ?> text-sm font-medium">
                                            <?= $i ?>
                                        </a>
                                    <?php endfor; ?>
                                    
                                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                        <a href="<?= buildPaginationUrl($pagination['current_page'] + 1, $perPage ?? 10, $searchTerm ?? '', $classId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $academicYearId ?? '') ?>" 
                                           class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span class="sr-only">Next</span>
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                            </svg>
                                        </a>
                                        <a href="<?= buildPaginationUrl($pagination['total_pages'], $perPage ?? 10, $searchTerm ?? '', $classId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $academicYearId ?? '') ?>" 
                                           class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                            <span>Last</span>
                                        </a>
                                    <?php endif; ?>
                                </nav>
                        </div>
                    </div>
                            </div>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Payment Modals -->
<!-- Step 1: Student Selection Modal -->
<div id="payment-modal-step1" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add Payment - Select Student</h3>
                <button id="cancel-payment-step1-btn" class="text-gray-400 hover:text-gray-500">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            
            <form id="student-selection-form" class="space-y-6">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Search Student</label>
                    <div class="relative">
                        <input type="text" id="student-search" 
                            class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Type to search students by name or admission number">
                        <div id="student-suggestions" class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md hidden max-h-60 overflow-auto"></div>
                    </div>
                </div>
                
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="modal-class_id" class="block text-sm font-medium text-gray-700">Class</label>
                        <select id="modal-class_id"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Classes</option>
                            <?php foreach ($allClasses ?? [] as $class): ?>
                                <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="modal-fee_type" class="block text-sm font-medium text-gray-700">Fee Type</label>
                        <select id="modal-fee_type"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Fee Types</option>
                            <?php foreach ($allFeeTypes ?? [] as $feeType): ?>
                                <option value="<?= $feeType ?>"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $feeType))) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                </div>
                
                <div id="students-dropdown-container" class="hidden">
                    <label for="students-dropdown" class="block text-sm font-medium text-gray-700">Or Select from Dropdown</label>
                    <select id="students-dropdown"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select a student</option>
                    </select>
                </div>
                
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-2">Selected Student</label>
                    <div class="border border-gray-300 rounded-md p-4">
                        <div id="selected-student-info">
                            <p class="text-gray-500">No student selected</p>
                        </div>
                        <input type="hidden" id="selected-student-id" name="student_id">
                    </div>
                </div>
                
                <div id="student-selection-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline" id="student-selection-error-message"></span>
                </div>
                
                <div class="flex justify-end space-x-3">
                    <button type="button" id="cancel-payment-step1-btn" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Cancel
                    </button>
                    <button type="button" id="load-student-btn" disabled
                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Load Student
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Step 2: Payment Allocation Modal -->
<div id="payment-modal-step2" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add Payment - Allocate Amount</h3>
                <button id="cancel-payment-step2-btn" class="text-gray-400 hover:text-gray-500">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            
            <div class="mb-6 p-4 bg-gray-50 rounded-lg">
                <h4 class="text-md font-medium text-gray-900 mb-2">Student Information</h4>
                <div class="grid grid-cols-1 sm:grid-cols-3 gap-4">
                    <div>
                        <p class="text-sm text-gray-500">Name</p>
                        <p id="allocation-student-name" class="font-medium"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Class</p>
                        <p id="allocation-student-class" class="font-medium"></p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">Admission No</p>
                        <p id="allocation-student-admission" class="font-medium"></p>
                    </div>
                </div>
                <input type="hidden" id="allocation-student-id" name="student_id">
            </div>
            
            <form id="payment-allocation-form" class="space-y-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Allocate</th>
                            </tr>
                        </thead>
                        <tbody id="fee-allocations-container" class="bg-white divide-y divide-gray-200">
                            <!-- Fee allocations will be populated here -->
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td class="px-6 py-3 text-right text-sm font-medium text-gray-900" colspan="4">Total Allocated:</td>
                                <td class="px-6 py-3 text-left text-sm font-bold text-gray-900">
                                    <span id="total-amount">₵0.00</span>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                    <div>
                        <label for="modal-date" class="block text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                        <input type="date" id="modal-date" name="date" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="modal-method" class="block text-sm font-medium text-gray-700">Payment Method <span class="text-red-500">*</span></label>
                        <select id="modal-method" name="method" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">Select Method</option>
                            <option value="cash">Cash</option>
                            <option value="cheque">Cheque</option>
                            <option value="mobile_money">Mobile Money</option>
                            <option value="bank_transfer">Bank Transfer</option>
                            <option value="other">Other</option>
                        </select>
                    </div>
                </div>
                
                <div id="payment-method-details">
                    <!-- Payment method specific fields will be populated here -->
                </div>
                
                <div>
                    <label for="modal-remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                    <textarea id="modal-remarks" name="remarks" rows="2"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                </div>
                
                <div id="allocation-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline" id="allocation-error-message"></span>
                </div>
                
                <div id="allocation-success" class="hidden bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                    <span class="block sm:inline" id="allocation-success-message"></span>
                </div>
                
                <div class="flex justify-between">
                    <button type="button" id="back-to-step1-btn" class="bg-gray-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                        Back
                    </button>
                    <div class="flex space-x-3">
                        <button type="button" id="cancel-payment-step2-btn" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </button>
                        <button type="submit" id="submit-payment-btn"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Record Payment
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- View Payment Modal -->
<div id="view-payment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Payment Details</h3>
                <button id="close-view-modal-btn" class="text-gray-400 hover:text-gray-500">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            
            <div id="view-payment-content" class="mt-4">
                <!-- Payment details will be populated here -->
            </div>
            
            <div class="mt-6 flex justify-end">
                <button id="close-view-modal-btn" class="bg-gray-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Payment Modal -->
<div id="edit-payment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Payment</h3>
                <button id="close-edit-modal-btn" class="text-gray-400 hover:text-gray-500">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            
            <div id="edit-payment-content" class="mt-4">
                <!-- Edit form will be populated here -->
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button id="close-edit-modal-btn" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Cancel
                </button>
                <button id="save-edit-payment-btn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save Changes
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Receipt Modal -->
<div id="receipt-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Payment Receipt</h3>
                <div class="flex items-center space-x-4">
                    <label class="inline-flex items-center text-sm text-gray-600" id="show-logo-container" style="display: none;">
                        <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="show-logo-checkbox">
                        <span class="ml-2">Show School Logo</span>
                    </label>
                    <button id="close-receipt-modal-btn" class="text-gray-400 hover:text-gray-500">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>
            </div>
            
            <div id="receipt-content" class="mt-4">
                <!-- Receipt content will be populated here -->
            </div>
            
            <div class="mt-6 flex justify-end space-x-3">
                <button id="close-receipt-modal-btn" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Close
                </button>
                <button id="print-receipt-btn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                    </svg>
                    Print Receipt
                </button>
            </div>
        </div>
    </div>
</div>

<script>
let allStudents = <?php echo json_encode($allStudents ?? []); ?>;
let filteredStudents = [];
let selectedStudent = null;
let studentFeeAllocations = [];

// Handle Add Payment button click
document.getElementById('add-payment-btn').addEventListener('click', function() {
    // Reset forms
    document.getElementById('student-selection-form').reset();
    document.getElementById('payment-allocation-form').reset();
    document.getElementById('modal-date').value = new Date().toISOString().split('T')[0]; // Set to today's date
    
    // Reset student selection
    document.getElementById('selected-student-id').value = '';
    document.getElementById('selected-student-info').innerHTML = '<p class="text-gray-500">No student selected</p>';
    document.getElementById('load-student-btn').disabled = true;
    
    // Hide any previous messages
    document.getElementById('student-selection-error').classList.add('hidden');
    document.getElementById('allocation-error').classList.add('hidden');
    document.getElementById('allocation-success').classList.add('hidden');
    
    // Show step 1 modal
    document.getElementById('payment-modal-step1').classList.remove('hidden');
    document.getElementById('payment-modal-step2').classList.add('hidden');
});

// Handle Cancel button click for step 1
document.getElementById('cancel-payment-step1-btn').addEventListener('click', function() {
    document.getElementById('payment-modal-step1').classList.add('hidden');
});

// Handle Cancel button click for step 2
document.getElementById('cancel-payment-step2-btn').addEventListener('click', function() {
    document.getElementById('payment-modal-step2').classList.add('hidden');
});

// Handle Back button click
document.getElementById('back-to-step1-btn').addEventListener('click', function() {
    document.getElementById('payment-modal-step2').classList.add('hidden');
    document.getElementById('payment-modal-step1').classList.remove('hidden');
});

// Handle student search input
document.getElementById('student-search').addEventListener('input', function() {
    const searchTerm = this.value.toLowerCase();
    showStudentSuggestions(searchTerm);
});

// Handle student search focus
document.getElementById('student-search').addEventListener('focus', function() {
    const searchTerm = this.value.toLowerCase();
    showStudentSuggestions(searchTerm);
});

// Handle click outside student suggestions
document.addEventListener('click', function(e) {
    // Only hide suggestions if we're in the payment modal and clicking outside the search area
    const paymentModalStep1 = document.getElementById('payment-modal-step1');
    const paymentModalStep2 = document.getElementById('payment-modal-step2');
    if ((paymentModalStep1 && !paymentModalStep1.classList.contains('hidden')) || 
        (paymentModalStep2 && !paymentModalStep2.classList.contains('hidden'))) {
        const studentSearch = document.getElementById('student-search');
        const studentSuggestions = document.getElementById('student-suggestions');
        
        if (studentSearch && studentSuggestions && 
            !studentSearch.contains(e.target) && 
            !studentSuggestions.contains(e.target)) {
            studentSuggestions.classList.add('hidden');
        }
    }
});

// Handle class selection
document.getElementById('modal-class_id').addEventListener('change', function() {
    filterStudents();
    
    // Show students dropdown if a class is selected
    const studentsDropdownContainer = document.getElementById('students-dropdown-container');
    const studentsDropdown = document.getElementById('students-dropdown');
    
    if (this.value) {
        studentsDropdownContainer.classList.remove('hidden');
        populateStudentsDropdown(this.value);
    } else {
        studentsDropdownContainer.classList.add('hidden');
        studentsDropdown.innerHTML = '<option value="">Select a student</option>';
    }
});

// Handle students dropdown selection
document.getElementById('students-dropdown').addEventListener('change', function() {
    const studentId = this.value;
    if (studentId) {
        // Find the student in our data
        const student = allStudents.find(s => s.id == studentId);
        if (student) {
            selectStudent(student);
        }
    } else {
        // Clear selection
        document.getElementById('selected-student-id').value = '';
        document.getElementById('selected-student-info').innerHTML = '<p class="text-gray-500">No student selected</p>';
        document.getElementById('load-student-btn').disabled = true;
    }
});

// Handle fee type selection
document.getElementById('modal-fee_type').addEventListener('change', function() {
    filterStudents();
});

// Handle payment method change
document.getElementById('modal-method').addEventListener('change', function() {
    const method = this.value;
    const paymentDetailsContainer = document.getElementById('payment-method-details');
    
    // Clear previous details
    paymentDetailsContainer.innerHTML = '';
    
    // Show relevant fields based on payment method
    switch (method) {
        case 'cash':
            paymentDetailsContainer.innerHTML = `
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mt-4">
                    <div>
                        <label for="payer-name" class="block text-sm font-medium text-gray-700">Payer Name</label>
                        <input type="text" id="payer-name" name="payer_name"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="payer-phone" class="block text-sm font-medium text-gray-700">Payer Phone</label>
                        <input type="text" id="payer-phone" name="payer_phone"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
            `;
            break;
            
        case 'cheque':
            paymentDetailsContainer.innerHTML = `
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mt-4">
                    <div>
                        <label for="cheque-number" class="block text-sm font-medium text-gray-700">Cheque Number <span class="text-red-500">*</span></label>
                        <input type="text" id="cheque-number" name="cheque_number" required
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="cheque-bank" class="block text-sm font-medium text-gray-700">Bank</label>
                        <input type="text" id="cheque-bank" name="cheque_bank"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
            `;
            break;
            
        case 'mobile_money':
            paymentDetailsContainer.innerHTML = `
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mt-4">
                    <div>
                        <label for="mobile-name" class="block text-sm font-medium text-gray-700">Sender Name</label>
                        <input type="text" id="mobile-name" name="mobile_name"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="mobile-number" class="block text-sm font-medium text-gray-700">Sender Number</label>
                        <input type="text" id="mobile-number" name="mobile_number"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div class="mt-4">
                    <label for="mobile-reference" class="block text-sm font-medium text-gray-700">Reference <span class="text-red-500">*</span></label>
                    <input type="text" id="mobile-reference" name="mobile_reference" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            `;
            break;
            
        case 'bank_transfer':
            paymentDetailsContainer.innerHTML = `
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 mt-4">
                    <div>
                        <label for="bank-invoice" class="block text-sm font-medium text-gray-700">Bank Draft/Invoice Number</label>
                        <input type="text" id="bank-invoice" name="bank_invoice"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    <div>
                        <label for="bank-name" class="block text-sm font-medium text-gray-700">Bank Name</label>
                        <input type="text" id="bank-name" name="bank_name"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                </div>
                <div class="mt-4">
                    <label for="bank-contact" class="block text-sm font-medium text-gray-700">Contact Person</label>
                    <input type="text" id="bank-contact" name="bank_contact"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            `;
            break;
            
        default:
            // For 'other' or any other method, no additional fields
            break;
    }
});

// Handle Load Student button click
document.getElementById('load-student-btn').addEventListener('click', function() {
    if (selectedStudent) {
        loadStudentForPayment(selectedStudent);
    } else {
        // Show error if no student is selected
        document.getElementById('student-selection-error-message').textContent = 'Please select a student first.';
        document.getElementById('student-selection-error').classList.remove('hidden');
    }
});

// Handle form submission for payment allocation
document.getElementById('payment-allocation-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    
    // Get student name and class for remarks generation
    const studentName = document.getElementById('allocation-student-name').textContent;
    const studentClass = document.getElementById('allocation-student-class').textContent;
    
    // Generate remarks based on payment method
    let remarks = '';
    const method = document.getElementById('modal-method').value;
    
    switch (method) {
        case 'cash':
            const payerName = document.getElementById('payer-name')?.value || '';
            const payerPhone = document.getElementById('payer-phone')?.value || '';
            remarks = `Cash Payment for ${studentName}`;
            if (payerName) {
                remarks += `, Payer: ${payerName}`;
            }
            if (payerPhone) {
                remarks += `, Phone: ${payerPhone}`;
            }
            break;
            
        case 'cheque':
            const chequeNumber = document.getElementById('cheque-number')?.value || '';
            const chequeBank = document.getElementById('cheque-bank')?.value || '';
            remarks = `Cheque Payment for ${studentName}`;
            if (chequeNumber) {
                remarks += ` - Cheque #: ${chequeNumber}`;
            }
            if (chequeBank) {
                remarks += `, Bank: ${chequeBank}`;
            }
            break;
            
        case 'mobile_money':
            const mobileName = document.getElementById('mobile-name')?.value || '';
            const mobileNumber = document.getElementById('mobile-number')?.value || '';
            const mobileReference = document.getElementById('mobile-reference')?.value || `${studentName} - ${studentClass}`;
            remarks = `Mobile Money Payment for ${studentName} - Reference: ${mobileReference}`;
            if (mobileName) {
                remarks += `, Sender: ${mobileName}`;
            }
            if (mobileNumber) {
                remarks += `, Number: ${mobileNumber}`;
            }
            break;
            
        case 'bank_transfer':
            const bankInvoice = document.getElementById('bank-invoice')?.value || '';
            const bankName = document.getElementById('bank-name')?.value || '';
            const bankContact = document.getElementById('bank-contact')?.value || '';
            remarks = `Bank Transfer Payment for ${studentName}`;
            if (bankInvoice) {
                remarks += ` - Invoice: ${bankInvoice}`;
            }
            if (bankName) {
                remarks += `, Bank: ${bankName}`;
            }
            if (bankContact) {
                remarks += `, Contact: ${bankContact}`;
            }
            break;
            
        default:
            remarks = document.getElementById('modal-remarks').value || `Payment for ${studentName}`;
    }
    
    // Set remarks in form data
    formData.set('remarks', remarks);
    
    // Show loading state
    const submitBtn = document.getElementById('submit-payment-btn');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Processing...';
    submitBtn.disabled = true;
    
    // Hide previous messages
    document.getElementById('allocation-error').classList.add('hidden');
    document.getElementById('allocation-success').classList.add('hidden');
    
    // Submit form via AJAX
    fetch('/payments', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => {
        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        
        if (response.ok) {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.includes('application/json')) {
                return response.json();
            } else {
                // If it's not JSON, it's likely a redirect
                window.location.reload();
                return;
            }
        } else {
            throw new Error('Network response was not ok');
        }
    })
    .then(data => {
        if (data && data.success) {
            // Show success message
            document.getElementById('allocation-success-message').textContent = data.message || 'Payment recorded successfully';
            document.getElementById('allocation-success').classList.remove('hidden');
            
            // Generate receipt after a short delay
            setTimeout(() => {
                generateReceipt(data.payment_id);
            }, 1000);
        } else if (data && data.error) {
            // Show error message
            document.getElementById('allocation-error-message').textContent = data.error;
            document.getElementById('allocation-error').classList.remove('hidden');
        }
    })
    .catch(error => {
        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        
        // Show error message
        document.getElementById('allocation-error-message').textContent = 'An error occurred while saving the payment. Please try again.';
        document.getElementById('allocation-error').classList.remove('hidden');
    });
});

// Generate and display payment receipt
function generateReceipt(paymentId) {
    // Show loading state
    document.getElementById('receipt-content').innerHTML = '<p>Generating receipt...</p>';
    document.getElementById('receipt-modal').classList.remove('hidden');
    
    // Prepare data for receipt generation
    const formData = new FormData();
    formData.append('payment_id', paymentId);
    
    // Fetch receipt data via AJAX
    fetch('/payments?generate_receipt=1', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data && data.success && data.receipt) {
            const receipt = data.receipt;
            const payment = receipt.payment;
            const student = receipt.student;
            const fee = receipt.fee;
            const issuer = receipt.issuer;
            const schoolName = data.school_name || 'APSA-ERP';
            const schoolLogo = data.school_logo || null;
                        
            // Format payment method
            let methodLabel = payment.method.replace('_', ' ').toUpperCase();
                        
            // Format date
            const paymentDate = new Date(payment.date).toLocaleDateString();
                        
            // Generate receipt HTML
            document.getElementById('receipt-content').innerHTML = `
                <div class="bg-white p-6 rounded-lg shadow-sm border border-gray-200">
                    <div class="flex justify-between items-start mb-6">
                        ${schoolLogo ? `<img src="${schoolLogo}" alt="School Logo" class="h-16 w-auto" id="receipt-logo" onerror="this.style.display='none'">` : ''}
                        <div class="text-center flex-1">
                            <h1 class="text-2xl font-bold text-gray-900">${schoolName}</h1>
                            <h2 class="text-2xl font-bold text-gray-900 mt-2">PAYMENT RECEIPT</h2>
                            <p class="text-gray-600 mt-2">Receipt #: ${payment.id}</p>
                        </div>
                    </div>
                    
                    <div class="grid grid-cols-2 gap-4 mb-6">
                        <div>
                            <p class="text-sm text-gray-500">Date</p>
                            <p class="font-medium">${paymentDate}</p>
                        </div>
                        <div>
                            <p class="text-sm text-gray-500">Issued By</p>
                            <p class="font-medium">${issuer.name}</p>
                            <p class="text-sm text-gray-500">${issuer.username}</p>
                        </div>
                        ${payment.academic_year_name || payment.term ? `
                        <div>
                            <p class="text-sm text-gray-500">Academic Year</p>
                            <p class="font-medium">${payment.academic_year_name || 'N/A'}</p>
                            ${payment.term ? `<p class="text-sm text-gray-500">${payment.term}</p>` : ''}
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Student Information</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Name</p>
                                <p class="font-medium">${student.first_name} ${student.last_name}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Admission No</p>
                                <p class="font-medium">${student.admission_no}</p>
                            </div>
                        </div>
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 mb-6">
                        <h3 class="text-lg font-medium text-gray-900 mb-3">Payment Details</h3>
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Amount</p>
                                <p class="text-xl font-bold text-green-600">₵${parseFloat(payment.amount).toFixed(2)}</p>
                            </div>
                            <div>
                                <p class="text-sm text-gray-500">Payment Method</p>
                                <p class="font-medium">${methodLabel}</p>
                            </div>
                        </div>
                    </div>
                        ${fee ? `
                        <div class="mt-3">
                            <p class="text-sm text-gray-500">Fee Type</p>
                            <p class="font-medium">${fee.name} (${fee.type})</p>
                        </div>
                        ` : ''}
                        ${payment.remarks ? `
                        <div class="mt-3">
                            <p class="text-sm text-gray-500">Remarks</p>
                            <p class="font-medium">${payment.remarks}</p>
                        </div>
                        ` : ''}
                    </div>
                    
                    <div class="border-t border-gray-200 pt-4 text-center">
                        <p class="text-sm text-gray-500">Thank you for your payment</p>
                    </div>
                </div>
            `;
            
            // Show/hide logo checkbox based on whether logo exists
            const showLogoContainer = document.getElementById('show-logo-container');
            const showLogoCheckbox = document.getElementById('show-logo-checkbox');
            const receiptLogo = document.getElementById('receipt-logo');
            
            if (schoolLogo && showLogoContainer && showLogoCheckbox && receiptLogo) {
                // Show the checkbox container
                showLogoContainer.style.display = 'flex';
                
                // Set initial checkbox state to checked
                showLogoCheckbox.checked = true;
                
                // Add event listener for the show logo checkbox
                showLogoCheckbox.addEventListener('change', function() {
                    receiptLogo.style.display = this.checked ? 'block' : 'none';
                });
            } else if (showLogoContainer) {
                // Hide the checkbox container if no logo
                showLogoContainer.style.display = 'none';
            }
        } else {
            document.getElementById('receipt-content').innerHTML = '<p class="text-red-500">Failed to generate receipt.</p>';
        }
    })
    .catch(error => {
        document.getElementById('receipt-content').innerHTML = '<p class="text-red-500">Error generating receipt.</p>';
    });
}

// Handle Print Receipt button click
document.getElementById('print-receipt-btn').addEventListener('click', function() {
    // Get receipt content
    const receiptContent = document.getElementById('receipt-content').innerHTML;
    
    // Open print window
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Payment Receipt</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .receipt { max-width: 600px; margin: 0 auto; }
                    .text-center { text-align: center; }
                    .text-right { text-align: right; }
                    .font-bold { font-weight: bold; }
                    .text-xl { font-size: 1.25rem; }
                    .text-2xl { font-size: 1.5rem; }
                    .text-sm { font-size: 0.875rem; }
                    .text-gray-500 { color: #6b7280; }
                    .text-gray-600 { color: #4b5563; }
                    .text-gray-900 { color: #111827; }
                    .text-green-600 { color: #16a34a; }
                    .border-t { border-top: 1px solid #e5e7eb; }
                    .pt-4 { padding-top: 1rem; }
                    .pt-6 { padding-top: 1.5rem; }
                    .mb-3 { margin-bottom: 0.75rem; }
                    .mb-6 { margin-bottom: 1.5rem; }
                    .grid { display: grid; }
                    .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                    .gap-4 { gap: 1rem; }
                    /* Logo sizing */
                    .h-16 { height: 4rem; }
                    .w-auto { width: auto; }
                </style>
            </head>
            <body>
                <div class="receipt">
                    ${receiptContent}
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                        // Close window after printing
                        setTimeout(function() {
                            window.close();
                        }, 1000);
                    }
                </script>
            </body>
        </html>
    `);
    printWindow.document.close();
});

// Handle Close Receipt Modal button click
document.getElementById('close-receipt-modal-btn').addEventListener('click', function() {
    document.getElementById('receipt-modal').classList.add('hidden');
    // Don't reload the page immediately, let the user close the modal first
});

// Close receipt modal when clicking outside
document.getElementById('receipt-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});

// Populate students dropdown based on selected class
function populateStudentsDropdown(classId) {
    const studentsDropdown = document.getElementById('students-dropdown');
    studentsDropdown.innerHTML = '<option value="">Select a student</option>';
    
    // Filter students by class
    const classStudents = allStudents.filter(student => student.class_id == classId);
    
    // Populate dropdown
    classStudents.forEach(student => {
        const option = document.createElement('option');
        option.value = student.id;
        option.textContent = `${student.first_name} ${student.last_name} (${student.admission_no})`;
        studentsDropdown.appendChild(option);
    });
}

// Select a student and update UI
function selectStudent(student) {
    // Set selected student
    selectedStudent = student;
    
    // Update UI
    document.getElementById('selected-student-id').value = student.id;
    document.getElementById('selected-student-info').innerHTML = `
        <div class="flex justify-between">
            <div>
                <p class="font-medium">${student.first_name} ${student.last_name}</p>
                <p class="text-sm text-gray-500">${student.class_name}</p>
            </div>
            <div>
                <p class="text-sm text-gray-500">${student.admission_no}</p>
            </div>
        </div>
    `;
    
    // Enable load button
    document.getElementById('load-student-btn').disabled = false;
    
    // Auto-select class
    document.getElementById('modal-class_id').value = student.class_id;
}

// Show student suggestions
function showStudentSuggestions(searchTerm = '') {
    const suggestionsContainer = document.getElementById('student-suggestions');
    
    if (searchTerm.length < 1) {
        suggestionsContainer.classList.add('hidden');
        return;
    }
    
    // Filter students based on search term and current filters
    filterStudents(searchTerm);
    
    if (filteredStudents.length === 0) {
        suggestionsContainer.innerHTML = '<div class="px-4 py-2 text-gray-500">No students found</div>';
        suggestionsContainer.classList.remove('hidden');
        return;
    }
    
    // Create suggestions list
    let suggestionsHTML = '';
    filteredStudents.slice(0, 10).forEach(student => {
        const isDeleted = student.is_deleted || false;
        const className = isDeleted ? 'text-gray-400 line-through' : 'text-gray-900';
        const deletedIndicator = isDeleted ? ' (Deleted)' : '';
        
        suggestionsHTML += `
            <div class="px-4 py-2 hover:bg-gray-100 cursor-pointer student-suggestion-item ${isDeleted ? 'opacity-60' : ''}" 
                 data-student-id="${student.id}"
                 data-student-name="${student.first_name} ${student.last_name}"
                 data-student-admission="${student.admission_no}"
                 data-student-class="${student.class_name}"
                 data-student-class-id="${student.class_id}">
                <div class="flex justify-between">
                    <span class="${className}">${student.first_name} ${student.last_name}${deletedIndicator}</span>
                    <span class="text-gray-500 text-sm">${student.admission_no}</span>
                </div>
                <div class="text-sm text-gray-500">${student.class_name}</div>
            </div>
        `;
    });
    
    suggestionsContainer.innerHTML = suggestionsHTML;
    suggestionsContainer.classList.remove('hidden');
    
    // Add click event to suggestions
    document.querySelectorAll('.student-suggestion-item').forEach(item => {
        item.addEventListener('click', function() {
            const studentId = this.getAttribute('data-student-id');
            const studentName = this.getAttribute('data-student-name');
            const studentAdmission = this.getAttribute('data-student-admission');
            const studentClass = this.getAttribute('data-student-class');
            const studentClassId = this.getAttribute('data-student-class-id');
            
            // Set selected student
            selectedStudent = {
                id: studentId,
                first_name: studentName.split(' ')[0],
                last_name: studentName.split(' ').slice(1).join(' '),
                admission_no: studentAdmission,
                class_name: studentClass,
                class_id: studentClassId
            };
            
            // Update UI
            document.getElementById('selected-student-id').value = studentId;
            document.getElementById('selected-student-info').innerHTML = `
                <div class="flex justify-between">
                    <div>
                        <p class="font-medium">${studentName}</p>
                        <p class="text-sm text-gray-500">${studentClass}</p>
                    </div>
                    <div>
                        <p class="text-sm text-gray-500">${studentAdmission}</p>
                    </div>
                </div>
            `;
            
            // Enable load button
            document.getElementById('load-student-btn').disabled = false;
            
            // Auto-select class
            document.getElementById('modal-class_id').value = studentClassId;
            
            // Hide suggestions
            suggestionsContainer.classList.add('hidden');
            
            // Clear search input
            document.getElementById('student-search').value = '';
        });
    });
}

// Filter students by search term and filters
function filterStudents(searchTerm = '') {
    const classId = document.getElementById('modal-class_id').value;
    const feeType = document.getElementById('modal-fee_type').value;
    
    filteredStudents = allStudents.filter(student => {
        // Check search term
        if (searchTerm) {
            const fullName = (student.first_name + ' ' + student.last_name).toLowerCase();
            const admissionNo = student.admission_no.toLowerCase();
            if (!fullName.includes(searchTerm) && !admissionNo.includes(searchTerm)) {
                return false;
            }
        }
        
        // Check class filter
        if (classId && student.class_id != classId) {
            return false;
        }
        
        // Check fee type filter (if we have fee assignments data)
        if (feeType && student.fee_assignments) {
            const hasFeeType = student.fee_assignments.some(fa => fa.fee_type === feeType);
            if (!hasFeeType) {
                return false;
            }
        }
        
        return true;
    });
}

// Load student for payment allocation
function loadStudentForPayment(student) {
    // Show loading state
    document.getElementById('load-student-btn').textContent = 'Loading...';
    document.getElementById('load-student-btn').disabled = true;
    
    // Fetch student's fee allocations
    fetch(`/payments/student-fees/${student.id}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Reset button state
        document.getElementById('load-student-btn').textContent = 'Load Student';
        document.getElementById('load-student-btn').disabled = false;
        
        if (data.success && data.fee_allocations) {
            studentFeeAllocations = data.fee_allocations;
            
            // Populate step 2 modal
            document.getElementById('allocation-student-name').textContent = `${student.first_name} ${student.last_name}`;
            document.getElementById('allocation-student-class').textContent = student.class_name;
            document.getElementById('allocation-student-admission').textContent = student.admission_no;
            document.getElementById('allocation-student-id').value = student.id;
            
            // Populate fee allocations in table format
            const container = document.getElementById('fee-allocations-container');
            container.innerHTML = '';
            
            if (studentFeeAllocations.length === 0) {
                container.innerHTML = '<tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No fee allocations found for this student</td></tr>';
                document.getElementById('total-amount').textContent = '₵0.00';
                document.getElementById('total-paid').textContent = '₵0.00';
                document.getElementById('total-balance').textContent = '₵0.00';
            } else {
                let totalAmount = 0;
                let totalPaid = 0;
                let totalBalance = 0;
                
                studentFeeAllocations.forEach((allocation, index) => {
                    const amount = parseFloat(allocation.amount) || 0;
                    const paid = parseFloat(allocation.paid_amount) || 0;
                    const balance = amount - paid;
                    
                    totalAmount += amount;
                    totalPaid += paid;
                    totalBalance += balance;
                    
                    const row = document.createElement('tr');
                    row.innerHTML = `
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="text-sm font-medium text-gray-900">${allocation.fee_name}</div>
                            <div class="text-sm text-gray-500">${allocation.fee_type}</div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                            ₵${amount.toFixed(2)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600">
                            ₵${paid.toFixed(2)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600">
                            ₵${balance.toFixed(2)}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <input type="number" step="0.01" min="0" max="${balance}" 
                                   name="allocate_${allocation.fee_id}" 
                                   data-fee-id="${allocation.fee_id}"
                                   data-balance="${balance}"
                                   class="fee-allocate-input w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                   value="0.00">
                        </td>
                    `;
                    container.appendChild(row);
                });
                
                // Update totals
                document.getElementById('total-amount').textContent = '₵0.00';
                document.getElementById('total-paid').textContent = '₵' + totalPaid.toFixed(2);
                document.getElementById('total-balance').textContent = '₵' + totalBalance.toFixed(2);
                
                // Add event listeners to allocation inputs
                document.querySelectorAll('.fee-allocate-input').forEach(input => {
                    input.addEventListener('input', function() {
                        validateAllocationInput(this);
                        updateTotals();
                    });
                });
            }
            
            // Hide step 1 and show step 2
            document.getElementById('payment-modal-step1').classList.add('hidden');
            document.getElementById('payment-modal-step2').classList.remove('hidden');
        } else {
            // Show error
            document.getElementById('student-selection-error-message').textContent = data.error || 'Failed to load student fees';
            document.getElementById('student-selection-error').classList.remove('hidden');
        }
    })
    .catch(error => {
        // Reset button state
        document.getElementById('load-student-btn').textContent = 'Load Student';
        document.getElementById('load-student-btn').disabled = false;
        
        // Show error
        document.getElementById('student-selection-error-message').textContent = 'Failed to load student fees. Please try again.';
        document.getElementById('student-selection-error').classList.remove('hidden');
    });
}

// Validate allocation input
function validateAllocationInput(input) {
    const balance = parseFloat(input.getAttribute('data-balance')) || 0;
    const value = parseFloat(input.value) || 0;
    
    // Ensure value doesn't exceed balance
    if (value > balance) {
        input.value = balance.toFixed(2);
    }
    
    // Ensure value is not negative
    if (value < 0) {
        input.value = '0.00';
    }
}

// Update totals based on fee allocations
function updateTotals() {
    let totalAllocated = 0;
    document.querySelectorAll('.fee-allocate-input').forEach(input => {
        const value = parseFloat(input.value) || 0;
        totalAllocated += value;
    });
    
    // Update the main total amount display
    document.getElementById('total-amount').textContent = '₵' + totalAllocated.toFixed(2);
}

// Handle View Payment button clicks
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('view-payment-btn') || e.target.closest('.view-payment-btn')) {
        const button = e.target.classList.contains('view-payment-btn') ? e.target : e.target.closest('.view-payment-btn');
        const paymentId = button.getAttribute('data-payment-id');
        
        // Show loading state
        document.getElementById('view-payment-content').innerHTML = '<p>Loading payment details...</p>';
        document.getElementById('view-payment-modal').classList.remove('hidden');
        
        // Fetch payment details via AJAX
        fetch(`/payments/${paymentId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.payment) {
                const payment = data.payment;
                const student = data.student;
                
                // Format payment details
                let methodClass = '';
                switch(payment.method) {
                    case 'cash': methodClass = 'bg-green-100 text-green-800'; break;
                    case 'cheque': methodClass = 'bg-blue-100 text-blue-800'; break;
                    case 'bank_transfer': methodClass = 'bg-purple-100 text-purple-800'; break;
                    case 'mobile_money': methodClass = 'bg-yellow-100 text-yellow-800'; break;
                    default: methodClass = 'bg-gray-100 text-gray-800';
                }
                
                document.getElementById('view-payment-content').innerHTML = `
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-lg font-medium text-gray-900">${student.first_name} ${student.last_name}</h4>
                            <p class="text-gray-600">Admission No: ${student.admission_no}</p>
                        </div>
                        
                        <div class="grid grid-cols-2 gap-4">
                            <div>
                                <p class="text-sm text-gray-500">Amount</p>
                                <p class="text-lg font-medium">₵${parseFloat(payment.amount).toFixed(2)}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Payment Method</p>
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ${methodClass}">
                                    ${payment.method.replace('_', ' ').toUpperCase()}
                                </span>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Date</p>
                                <p class="text-lg font-medium">${new Date(payment.date).toLocaleDateString()}</p>
                            </div>
                            
                            <div>
                                <p class="text-sm text-gray-500">Fee</p>
                                <p class="text-lg font-medium">${payment.fee_name || 'N/A'}</p>
                            </div>
                        </div>
                        
                        <div>
                            <p class="text-sm text-gray-500">Remarks</p>
                            <p class="mt-1 text-gray-900">${payment.remarks || 'N/A'}</p>
                        </div>
                    </div>
                `;
            } else {
                document.getElementById('view-payment-content').innerHTML = '<p class="text-red-500">Failed to load payment details.</p>';
            }
        })
        .catch(error => {
            document.getElementById('view-payment-content').innerHTML = '<p class="text-red-500">Error loading payment details.</p>';
        });
    }
});

// Handle Edit Payment button clicks
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('edit-payment-btn') || e.target.closest('.edit-payment-btn')) {
        const button = e.target.classList.contains('edit-payment-btn') ? e.target : e.target.closest('.edit-payment-btn');
        const paymentId = button.getAttribute('data-payment-id');
        
        // Show loading state
        document.getElementById('edit-payment-content').innerHTML = '<p>Loading payment details...</p>';
        document.getElementById('edit-payment-modal').classList.remove('hidden');
        
        // Fetch payment details via AJAX
        fetch(`/payments/${paymentId}/edit`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.payment) {
                const payment = data.payment;
                const students = data.students;
                
                // Format payment method options
                const methodOptions = ['cash', 'cheque', 'bank_transfer', 'mobile_money', 'other'].map(method => {
                    const selected = payment.method === method ? 'selected' : '';
                    const label = method.replace('_', ' ').toUpperCase();
                    return `<option value="${method}" ${selected}>${label}</option>`;
                }).join('');
                
                // Format student options
                const studentOptions = students.map(student => {
                    const selected = payment.student_id == student.id ? 'selected' : '';
                    return `<option value="${student.id}" ${selected}>${student.first_name} ${student.last_name} (${student.admission_no})</option>`;
                }).join('');
                
                document.getElementById('edit-payment-content').innerHTML = `
                    <form id="edit-payment-form" class="space-y-4">
                        <input type="hidden" name="id" value="${payment.id}">
                        
                        <div>
                            <label for="edit-student-id" class="block text-sm font-medium text-gray-700">Student</label>
                            <select name="student_id" id="edit-student-id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                ${studentOptions}
                            </select>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="edit-amount" class="block text-sm font-medium text-gray-700">Amount</label>
                                <input type="number" step="0.01" name="amount" id="edit-amount" value="${payment.amount}" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            
                            <div>
                                <label for="edit-method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                                <select name="method" id="edit-method" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    ${methodOptions}
                                </select>
                            </div>
                        </div>
                        
                        <div>
                            <label for="edit-date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" id="edit-date" value="${payment.date}" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="edit-remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea name="remarks" id="edit-remarks" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">${payment.remarks || ''}</textarea>
                        </div>
                        
                        <div id="edit-payment-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline" id="edit-payment-error-message"></span>
                        </div>
                    </form>
                `;
            } else {
                document.getElementById('edit-payment-content').innerHTML = '<p class="text-red-500">Failed to load payment details.</p>';
            }
        })
        .catch(error => {
            document.getElementById('edit-payment-content').innerHTML = '<p class="text-red-500">Error loading payment details.</p>';
        });
    }
});

// Handle Save Edit Payment button click
document.getElementById('save-edit-payment-btn').addEventListener('click', function() {
    const form = document.getElementById('edit-payment-form');
    const formData = new FormData(form);
    
    // Show loading state
    const saveBtn = this;
    const originalText = saveBtn.textContent;
    saveBtn.textContent = 'Saving...';
    saveBtn.disabled = true;
    
    // Hide previous error messages
    document.getElementById('edit-payment-error').classList.add('hidden');
    
    // Submit form via AJAX
    fetch('/payments/' + formData.get('id'), {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Reset button state
        saveBtn.textContent = originalText;
        saveBtn.disabled = false;
        
        if (data.success) {
            // Reload the page to show updated data
            window.location.reload();
        } else {
            // Show error message
            document.getElementById('edit-payment-error-message').textContent = data.error || 'Failed to update payment.';
            document.getElementById('edit-payment-error').classList.remove('hidden');
        }
    })
    .catch(error => {
        // Reset button state
        saveBtn.textContent = originalText;
        saveBtn.disabled = false;
        
        // Show error message
        document.getElementById('edit-payment-error-message').textContent = 'Error updating payment.';
        document.getElementById('edit-payment-error').classList.remove('hidden');
    });
});

// Handle Close View Modal button click
document.getElementById('close-view-modal-btn').addEventListener('click', function() {
    document.getElementById('view-payment-modal').classList.add('hidden');
});

// Handle Close Edit Modal button click
document.getElementById('close-edit-modal-btn').addEventListener('click', function() {
    document.getElementById('edit-payment-modal').classList.add('hidden');
});

// Close modals when clicking outside
document.getElementById('view-payment-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});

document.getElementById('edit-payment-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});

// Initialize the form
document.addEventListener('DOMContentLoaded', function() {
    // Initially show all students
    filterStudents();
    
    // Handle date filter checkbox toggle
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