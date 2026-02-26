<?php
// Ensure TemplateHelper is available
require_once ROOT_PATH . '/app/Helpers/TemplateHelper.php';
$schoolSettings = getSchoolSettings();

// Function to build pagination URLs
function buildPaginationUrl($page, $perPage, $searchTerm = '', $academicYearId = '', $dateFrom = '', $dateTo = '', $enableDateFilter = false, $reportType = '', $tab = 'payments', $classId = '', $term = '') {
    $params = [
        'page' => $page,
        'per_page' => $perPage,
        'tab' => $tab
    ];
    
    if ($searchTerm) {
        $params['search'] = $searchTerm;
    }
    
    if ($academicYearId) {
        $params['academic_year_id'] = $academicYearId;
    }

    if ($classId) {
        $params['class_id'] = $classId;
    }

    if ($term) {
        $params['term'] = $term;
    }
    
    if ($reportType) {
        $params['report_type'] = $reportType;
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
    
    return '/finance?' . http_build_query($params);
}

$title = 'Finance Records'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Finance Records</h1>
        </div>

        <!-- Tabs -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="<?= buildPaginationUrl(1, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $reportType ?? '', 'payments', $classId ?? '') ?>" 
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm <?= (isset($tab) && $tab === 'payments') ? 'border-indigo-500 text-indigo-600' : '' ?>">
                    Payments
                </a>
                <a href="<?= buildPaginationUrl(1, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, '', 'bills', $classId ?? '', $term ?? '') ?>" 
                   class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm <?= (isset($tab) && $tab === 'bills') ? 'border-indigo-500 text-indigo-600' : '' ?>">
                    Bills
                </a>
            </nav>
        </div>

        <?php if (isset($tab) && $tab === 'bills'): ?>
            <!-- Bills Tab Content -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <!-- Bills Filters -->
                    <form method="GET" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4 mb-6">
                        <input type="hidden" name="tab" value="bills">
                        <div>
                            <label for="bills_search" class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="search" id="bills_search" value="<?= htmlspecialchars($searchTerm ?? '') ?>"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Student name or admission no">
                        </div>

                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                            <select name="class_id" id="class_id"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Classes</option>
                                <?php foreach ($classes ?? [] as $class): ?>
                                    <option value="<?= $class['id'] ?>" <?= (isset($classId) && $classId == $class['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($class['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="bills_academic_year_id" class="block text-sm font-medium text-gray-700">Academic Year</label>
                            <select name="academic_year_id" id="bills_academic_year_id"
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
                            <label for="bills_term" class="block text-sm font-medium text-gray-700">Term</label>
                            <select name="term" id="bills_term"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Terms</option>
                                <option value="1st Term" <?= (isset($term) && $term == '1st Term') ? 'selected' : '' ?>>1st Term</option>
                                <option value="2nd Term" <?= (isset($term) && $term == '2nd Term') ? 'selected' : '' ?>>2nd Term</option>
                                <option value="3rd Term" <?= (isset($term) && $term == '3rd Term') ? 'selected' : '' ?>>3rd Term</option>
                            </select>
                        </div>

                        <div>
                            <label for="per_page" class="block text-sm font-medium text-gray-700">Rows per page</label>
                            <select name="per_page" id="per_page"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="10" <?= (isset($perPage) && $perPage == 10) ? 'selected' : '' ?>>10</option>
                                <option value="25" <?= (isset($perPage) && $perPage == 25) ? 'selected' : '' ?>>25</option>
                                <option value="50" <?= (isset($perPage) && $perPage == 50) ? 'selected' : '' ?>>50</option>
                                <option value="100" <?= (isset($perPage) && $perPage == 100) ? 'selected' : '' ?>>100</option>
                            </select>
                        </div>

                        <div class="flex items-center space-x-2 mt-6">
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Filter
                            </button>
                            <a href="/finance?tab=bills"
                                class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Clear
                            </a>
                        </div>
                    </form>

                    <!-- Bills Table -->
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Structure</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Students</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Expected</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Paid</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php if (empty($fees)): ?>
                                    <tr>
                                        <td colspan="7" class="px-6 py-4 text-center text-gray-500">
                                            No fee structures found
                                        </td>
                                    </tr>
                                <?php else: ?>
                                    <?php foreach ($fees as $feeData): 
                                        $fee = $feeData['fee'];
                                        ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($fee['name']) ?></div>
                                                <div class="text-sm text-gray-500"><?= htmlspecialchars($fee['display_classes'] ?? 'All Classes') ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ₵<?= number_format($fee['amount'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= $fee['student_count'] ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ₵<?= number_format($feeData['total_expected'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                ₵<?= number_format($feeData['total_paid'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm <?= $feeData['overall_balance'] > 0 ? 'text-red-600 font-bold' : 'text-green-600' ?>">
                                                ₵<?= number_format($feeData['overall_balance'], 2) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                                <button class="view-fee-details text-indigo-600 hover:text-indigo-900" 
                                                        data-fee-id="<?= $fee['id'] ?>">
                                                    View Details
                                                </button>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </tbody>
                        </table>
                    </div>

                    <!-- Student Bills Section -->
                    <div class="mt-8">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg font-medium text-gray-900">Student Bills</h3>
                            <?php if (!empty($studentBills)): ?>
                            <button id="print-student-bills-btn" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print
                            </button>
                            <?php endif; ?>
                        </div>
                        <div class="overflow-x-auto" id="student-bills-table-container">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Name</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Paid</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php if (empty($studentBills)): ?>
                                        <tr>
                                            <td colspan="8" class="px-6 py-4 text-center text-gray-500">
                                                No student bills found
                                            </td>
                                        </tr>
                                    <?php else: ?>
                                        <?php 
                                            $totalAmount = 0;
                                            $totalPaid = 0;
                                            $totalBalance = 0;
                                            foreach ($studentBills as $bill): 
                                            $balance = $bill['fee_amount'] - $bill['total_paid'];
                                            $totalAmount += $bill['fee_amount'];
                                            $totalPaid += $bill['total_paid'];
                                            $totalBalance += $balance;
                                            ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?= date('M d, Y', strtotime($bill['created_at'])) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap">
                                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($bill['first_name'] . ' ' . $bill['last_name']) ?></div>
                                                    <div class="text-sm text-gray-500"><?= htmlspecialchars($bill['admission_no']) ?></div>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?= htmlspecialchars($bill['class_name'] ?? 'N/A') ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    <?= htmlspecialchars($bill['fee_name']) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                    <?= ucfirst(str_replace('_', ' ', $bill['fee_type'] ?? '')) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    ₵<?= number_format($bill['fee_amount'], 2) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                    ₵<?= number_format($bill['total_paid'], 2) ?>
                                                </td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold <?= $balance > 0 ? 'text-red-600' : 'text-green-600' ?>">
                                                    ₵<?= number_format($balance, 2) ?>
                                                </td>
                                            </tr>
                                        <?php endforeach; ?>
                                    <?php endif; ?>
                                </tbody>
                                <?php if (!empty($studentBills)): ?>
                                <tfoot class="bg-gray-50">
                                    <tr>
                                        <td colspan="5" class="px-6 py-3 text-sm font-medium text-gray-900" style="text-align: right;">Total</td>
                                        <td class="px-6 py-3 text-sm font-bold text-gray-900">₵<?= number_format($totalAmount, 2) ?></td>
                                        <td class="px-6 py-3 text-sm font-bold text-gray-900">₵<?= number_format($totalPaid, 2) ?></td>
                                        <td class="px-6 py-3 text-sm font-bold <?= $totalBalance > 0 ? 'text-red-600' : 'text-green-600' ?>">₵<?= number_format($totalBalance, 2) ?></td>
                                    </tr>
                                </tfoot>
                                <?php endif; ?>
                            </table>
                        </div>
                        
                        <!-- Pagination for Bills -->
                        <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 mt-4">
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
                                                <a href="<?= buildPaginationUrl($pagination['current_page'] - 1, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, '', 'bills', $classId ?? '') ?>" 
                                                   class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                    Previous
                                                </a>
                                            <?php endif; ?>
                                            
                                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                                <a href="<?= buildPaginationUrl($pagination['current_page'] + 1, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, '', 'bills', $classId ?? '') ?>" 
                                                   class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                                    Next
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
        <?php else: ?>
            <!-- Existing Payments Tab Content -->
            <!-- Filters -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <form method="GET" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <input type="hidden" name="tab" value="payments">
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                            <input type="text" name="search" id="search" value="<?= htmlspecialchars($searchTerm ?? '') ?>"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Student name, admission no, or guardian name">
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
                                <?php foreach ($classes ?? [] as $class): ?>
                                    <option value="<?= $class['id'] ?>" <?= (isset($classId) && $classId == $class['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($class['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="report_type" class="block text-sm font-medium text-gray-700">Report Type</label>
                            <select name="report_type" id="report_type"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="daily" <?= (isset($reportType) && $reportType == 'daily') ? 'selected' : '' ?>>Daily</option>
                                <option value="weekly" <?= (isset($reportType) && $reportType == 'weekly') ? 'selected' : '' ?>>Weekly</option>
                                <option value="monthly" <?= (isset($reportType) && $reportType == 'monthly') ? 'selected' : '' ?>>Monthly</option>
                                <option value="termly" <?= (isset($reportType) && $reportType == 'termly') ? 'selected' : '' ?>>Termly</option>
                                <option value="yearly" <?= (isset($reportType) && $reportType == 'yearly') ? 'selected' : '' ?>>Yearly</option>
                            </select>
                        </div>

                        <div>
                            <label for="per_page" class="block text-sm font-medium text-gray-700">Rows per page</label>
                            <select name="per_page" id="per_page"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="10" <?= (isset($perPage) && $perPage == 10) ? 'selected' : '' ?>>10</option>
                                <option value="25" <?= (isset($perPage) && $perPage == 25) ? 'selected' : '' ?>>25</option>
                                <option value="50" <?= (isset($perPage) && $perPage == 50) ? 'selected' : '' ?>>50</option>
                                <option value="100" <?= (isset($perPage) && $perPage == 100) ? 'selected' : '' ?>>100</option>
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
                                
                                
                                <div class="flex items-center space-x-2">
                                    <button type="submit"
                                        class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Filter
                                    </button>
                                    <a href="/finance?tab=payments"
                                        class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Clear
                                    </a>
                                </div>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
            
            <!-- Report Summary -->
            <?php if (isset($reportData) && !empty($reportData)): ?>
            <div id="report-summary-section" class="bg-white shadow sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex justify-between items-center mb-4">
                        <h2 class="text-lg font-medium text-gray-900">Report Summary - <?= ucfirst($reportType ?? 'Daily') ?></h2>
                        <?php 
                        // Calculate total amount for the report
                        $reportTotal = array_sum(array_column($reportData, 'total_amount'));
                        ?>
                        <div class="flex items-center space-x-4">
                            <div class="text-lg font-bold text-indigo-600">
                                Total: ₵<?= number_format($reportTotal, 2) ?>
                            </div>
                            <div class="flex space-x-2">
                                <div class="relative inline-block text-left">
                                    <button id="print-summary-options" class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                                        </svg>
                                        Print
                                        <svg class="-mr-1 ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div id="print-summary-dropdown" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50">
                                        <div class="py-1">
                                            <button id="print-summary-only" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Print Summary Only</button>
                                            <button id="print-summary-and-fees" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Print Summary and Fees Data</button>
                                        </div>
                                    </div>
                                </div>
                                <div class="relative inline-block text-left">
                                    <button id="export-summary-options" class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                        Export
                                        <svg class="-mr-1 ml-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                            <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                        </svg>
                                    </button>
                                    <div id="export-summary-dropdown" class="origin-top-right absolute right-0 mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden z-50">
                                        <div class="py-1">
                                            <button id="export-summary-only" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Export Summary Only</button>
                                            <button id="export-summary-and-fees" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Export Summary and Fees Data</button>
                                            <button id="export-fees-only" class="block w-full text-left px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">Export Fees Data Only</button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <?php if ($reportType == 'daily'): ?>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (₵)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider actions-column">Actions</th>
                                </tr>
                                <?php elseif ($reportType == 'weekly'): ?>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Week</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Period</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (₵)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider actions-column">Actions</th>
                                </tr>
                                <?php elseif ($reportType == 'monthly'): ?>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (₵)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider actions-column">Actions</th>
                                </tr>
                                <?php elseif ($reportType == 'termly'): ?>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Term</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (₵)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider actions-column">Actions</th>
                                </tr>
                                <?php elseif ($reportType == 'yearly'): ?>
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount (₵)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider actions-column">Actions</th>
                                </tr>
                                <?php endif; ?>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($reportData as $report): ?>
                                <tr>
                                    <?php if ($reportType == 'daily'): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M j, Y', strtotime($report['report_date'])) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $report['transaction_count'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₵<?= number_format($report['total_amount'], 2) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium actions-column">
                                        <button class="view-report-details text-indigo-600 hover:text-indigo-900" 
                                                data-report-type="daily" 
                                                data-report-date="<?= $report['report_date'] ?>"
                                                data-search-term="<?= htmlspecialchars($searchTerm ?? '') ?>"
                                                data-academic-year-id="<?= htmlspecialchars($academicYearId ?? '') ?>">
                                            View Details
                                        </button>
                                    </td>
                                    <?php elseif ($reportType == 'weekly'): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">Week <?= date('W', strtotime($report['start_date'])) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M j', strtotime($report['start_date'])) ?> - <?= date('M j, Y', strtotime($report['end_date'])) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $report['transaction_count'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₵<?= number_format($report['total_amount'], 2) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium actions-column">
                                        <button class="view-report-details text-indigo-600 hover:text-indigo-900" 
                                                data-report-type="weekly" 
                                                data-start-date="<?= $report['start_date'] ?>"
                                                data-end-date="<?= $report['end_date'] ?>"
                                                data-week="<?= date('W', strtotime($report['start_date'])) ?>"
                                                data-search-term="<?= htmlspecialchars($searchTerm ?? '') ?>"
                                                data-academic-year-id="<?= htmlspecialchars($academicYearId ?? '') ?>">
                                            View Details
                                        </button>
                                    </td>
                                    <?php elseif ($reportType == 'monthly'): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $report['month_name'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $report['transaction_count'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₵<?= number_format($report['total_amount'], 2) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium actions-column">
                                        <button class="view-report-details text-indigo-600 hover:text-indigo-900" 
                                                data-report-type="monthly" 
                                                data-month="<?= $report['month'] ?>"
                                                data-month-name="<?= htmlspecialchars($report['month_name']) ?>"
                                                data-search-term="<?= htmlspecialchars($searchTerm ?? '') ?>"
                                                data-academic-year-id="<?= htmlspecialchars($academicYearId ?? '') ?>">
                                            View Details
                                        </button>
                                    </td>
                                    <?php elseif ($reportType == 'termly'): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $report['academic_year_name'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $report['term'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $report['transaction_count'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₵<?= number_format($report['total_amount'], 2) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium actions-column">
                                        <button class="view-report-details text-indigo-600 hover:text-indigo-900" 
                                                data-report-type="termly" 
                                                data-academic-year-id="<?= $report['academic_year_id'] ?>"
                                                data-academic-year-name="<?= htmlspecialchars($report['academic_year_name']) ?>"
                                                data-term="<?= $report['term'] ?>"
                                                data-search-term="<?= htmlspecialchars($searchTerm ?? '') ?>">
                                            View Details
                                        </button>
                                    </td>
                                    <?php elseif ($reportType == 'yearly'): ?>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $report['year'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $report['transaction_count'] ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₵<?= number_format($report['total_amount'], 2) ?></td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium actions-column">
                                        <button class="view-report-details text-indigo-600 hover:text-indigo-900" 
                                                data-report-type="yearly" 
                                                data-year="<?= $report['year'] ?>"
                                                data-search-term="<?= htmlspecialchars($searchTerm ?? '') ?>"
                                                data-academic-year-id="<?= htmlspecialchars($academicYearId ?? '') ?>">
                                            View Details
                                        </button>
                                    </td>
                                    <?php endif; ?>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Payments Table -->
            <div id="payments-table-section" class="bg-white shadow overflow-hidden sm:rounded-lg">
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
                                                    Class
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
                                                    Academic Year/Term
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Fee
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                    Remarks
                                                </th>
                                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider actions-column">
                                                    Actions
                                                </th>
                                            </tr>
                                        </thead>
                                        <tbody class="bg-white divide-y divide-gray-200">
                                            <?php if (empty($payments)): ?>
                                                <tr>
                                                    <td colspan="9" class="px-6 py-4 text-center text-gray-500">
                                                        No payments found
                                                    </td>
                                                </tr>
                                            <?php else: ?>
                                                <?php foreach ($payments as $payment): ?>
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap">
                                                            <div class="text-sm font-medium text-gray-900">
                                                                <?= htmlspecialchars($payment['first_name'] ?? $payment['student_name'] ?? '') ?> <?= htmlspecialchars($payment['last_name'] ?? '') ?>
                                                            </div>
                                                            <div class="text-sm text-gray-500">
                                                                <?= htmlspecialchars($payment['admission_no'] ?? '') ?>
                                                            </div>
                                                            <?php if (!empty($payment['guardian_name'])): ?>
                                                            <div class="text-xs text-gray-400">
                                                                Guardian: <?= htmlspecialchars($payment['guardian_name'] ?? '') ?>
                                                            </div>
                                                            <?php endif; ?>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            <?= htmlspecialchars($payment['class_name'] ?? 'N/A') ?>
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
                                                        <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium actions-column">
                                                            <a href="/students/<?= $payment['student_id'] ?>?tab=financial" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                                                Payment History
                                                            </a>
                                                            <button class="view-payment-btn text-indigo-600 hover:text-indigo-900 mr-3" data-payment-id="<?= $payment['id'] ?>">
                                                                Receipt
                                                            </button>
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
                                Showing <?= count($payments) ?> of <?= $pagination['total'] ?? 0 ?> payments
                            </p>
                        </div>
                    <?php endif; ?>
                    
                    <!-- Pagination -->
                    <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
                        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                            <div class="flex-1 flex justify-between sm:hidden">
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <a href="<?= buildPaginationUrl($pagination['current_page'] - 1, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $reportType ?? '', 'payments', $classId ?? '') ?>" 
                                       class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                        Previous
                                    </a>
                                <?php endif; ?>
                                
                                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                    <a href="<?= buildPaginationUrl($pagination['current_page'] + 1, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $reportType ?? '', 'payments', $classId ?? '') ?>" 
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
                                            <a href="<?= buildPaginationUrl(1, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $reportType ?? '', 'payments', $classId ?? '') ?>" 
                                               class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                <span>First</span>
                                            </a>
                                            <a href="<?= buildPaginationUrl($pagination['current_page'] - 1, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $reportType ?? '', 'payments', $classId ?? '') ?>" 
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
                                            <a href="<?= buildPaginationUrl($i, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $reportType ?? '', 'payments', $classId ?? '') ?>" 
                                               class="relative inline-flex items-center px-4 py-2 border <?= $i == $pagination['current_page'] ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' ?> text-sm font-medium">
                                                <?= $i ?>
                                            </a>
                                        <?php endfor; ?>
                                        
                                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                            <a href="<?= buildPaginationUrl($pagination['current_page'] + 1, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $reportType ?? '', 'payments', $classId ?? '') ?>" 
                                               class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                                <span class="sr-only">Next</span>
                                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                                </svg>
                                            </a>
                                            <a href="<?= buildPaginationUrl($pagination['total_pages'], $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false, $reportType ?? '', 'payments', $classId ?? '') ?>" 
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
        <?php endif; ?>
    </div>
</div>

<!-- Report Details Modal -->
<div id="report-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Transaction Details</h3>
                <div class="flex items-center space-x-2">
                    <button id="print-report-btn" class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                        </svg>
                        Print
                    </button>
                    <button id="export-report-btn" class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                        </svg>
                        Export
                    </button>
                    <button id="close-report-modal-btn" class="text-gray-400 hover:text-gray-500">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>
            </div>
            
            <div id="report-details-content" class="mt-4">
                <!-- Report details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<!-- Fee Details Modal -->
<div id="fee-details-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-2/3 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Fee Details</h3>
                <button id="close-fee-modal-btn" class="text-gray-400 hover:text-gray-500">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            
            <div id="fee-details-content" class="mt-4">
                <!-- Fee details will be loaded here -->
            </div>
        </div>
    </div>
</div>

<style>
@media print {
    /* Hide the Actions columns when printing */
    .actions-column {
        display: none !important;
    }
    
    /* Hide the dropdown buttons and other interactive elements when printing */
    .no-print {
        display: none !important;
    }
    
    /* Ensure tables expand to full width when printing */
    table {
        width: 100% !important;
        table-layout: fixed;
    }
    
    /* Remove background colors for better printing */
    th {
        background-color: transparent !important;
        -webkit-print-color-adjust: exact;
        color-adjust: exact;
    }
    
    /* Ensure proper spacing for printed content */
    body {
        margin: 0;
        padding: 20px;
    }
    
    /* Hide tabs when printing */
    .border-b {
        display: none !important;
    }
}
</style>

<script>
// Function to show view payment modal with content
function showViewPaymentModal(content) {
    const modal = document.getElementById('view-payment-modal');
    const contentDiv = document.getElementById('view-payment-content');
    
    if (modal && contentDiv) {
        contentDiv.innerHTML = content;
        modal.classList.remove('hidden');
    } else {
        console.error('Modal elements not found');
    }
}

// Handle View Payment button clicks
document.addEventListener('click', function(e) {
    console.log('Document clicked, checking for view-payment-btn');
    console.log('Target classes:', e.target.classList);
    if (e.target.classList.contains('view-payment-btn') || e.target.closest('.view-payment-btn')) {
        const button = e.target.classList.contains('view-payment-btn') ? e.target : e.target.closest('.view-payment-btn');
        const paymentId = button.getAttribute('data-payment-id');
        
        // Show loading state
        showViewPaymentModal('<p>Loading payment details...</p>');
        
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
                
                // Format payment method
                let methodClass = '';
                switch(payment.method) {
                    case 'cash': methodClass = 'bg-green-100 text-green-800'; break;
                    case 'cheque': methodClass = 'bg-blue-100 text-blue-800'; break;
                    case 'bank_transfer': methodClass = 'bg-purple-100 text-purple-800'; break;
                    case 'mobile_money': methodClass = 'bg-yellow-100 text-yellow-800'; break;
                    default: methodClass = 'bg-gray-100 text-gray-800';
                }
                
                // Generate payment details HTML
                const html = '<div class="space-y-4">' +
    '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">' +
        '<div>' +
            '<p class="text-sm text-gray-500">Student</p>' +
            '<p class="text-lg font-medium">' + (student.first_name || '') + ' ' + (student.last_name || '') + '</p>' +
            '<p class="text-sm text-gray-500">' + (student.admission_no || 'N/A') + '</p>' +
        '</div>' +
        '<div>' +
            '<p class="text-sm text-gray-500">Amount</p>' +
            '<p class="text-2xl font-bold text-indigo-600">₵' + parseFloat(payment.amount || 0).toFixed(2) + '</p>' +
        '</div>' +
    '</div>' +
    
    '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">' +
        '<div>' +
            '<p class="text-sm text-gray-500">Payment Method</p>' +
            '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium ' + methodClass + '">' +
                payment.method.replace('_', ' ').toUpperCase() +
            '</span>' +
        '</div>' +
        '<div>' +
            '<p class="text-sm text-gray-500">Date</p>' +
            '<p class="text-lg font-medium">' + new Date(payment.date).toLocaleDateString() + '</p>' +
        '</div>' +
    '</div>' +
    
    '<div>' +
        '<p class="text-sm text-gray-500">Fee</p>' +
        '<p class="text-lg font-medium">' + (payment.fee_name || 'N/A') + '</p>' +
    '</div>' +
    
    (payment.remarks ? 
        '<div>' +
            '<p class="text-sm text-gray-500">Remarks</p>' +
            '<p class="mt-1 text-gray-900">' + payment.remarks + '</p>' +
        '</div>'
    : '') +
    
    // Payment Method Specific Details
    (payment.method === 'cheque' ? 
        '<div class="border-t border-gray-200 pt-4">' +
            '<h4 class="text-sm font-medium text-gray-900 mb-2">Cheque Details</h4>' +
            '<div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">' +
                (payment.cheque_bank ? '<p><span class="font-medium">Bank:</span> ' + payment.cheque_bank + '</p>' : '') +
                (payment.cheque_number ? '<p><span class="font-medium">Cheque Number:</span> ' + payment.cheque_number + '</p>' : '') +
                (payment.cheque_details ? '<p><span class="font-medium">Details:</span> ' + payment.cheque_details + '</p>' : '') +
            '</div>' +
        '</div>'
    : '') +
    
    (payment.method === 'bank_transfer' ? 
        '<div class="border-t border-gray-200 pt-4">' +
            '<h4 class="text-sm font-medium text-gray-900 mb-2">Bank Transfer Details</h4>' +
            '<div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">' +
                (payment.bank_transfer_sender_bank ? '<p><span class="font-medium">Sender Bank:</span> ' + payment.bank_transfer_sender_bank + '</p>' : '') +
                (payment.bank_transfer_invoice_number ? '<p><span class="font-medium">Invoice Number:</span> ' + payment.bank_transfer_invoice_number + '</p>' : '') +
                (payment.bank_transfer_details ? '<p><span class="font-medium">Details:</span> ' + payment.bank_transfer_details + '</p>' : '') +
            '</div>' +
        '</div>'
    : '') +
    
    (payment.method === 'mobile_money' ? 
        '<div class="border-t border-gray-200 pt-4">' +
            '<h4 class="text-sm font-medium text-gray-900 mb-2">Mobile Money Details</h4>' +
            '<div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">' +
                (payment.mobile_money_sender_name ? '<p><span class="font-medium">Sender Name:</span> ' + payment.mobile_money_sender_name + '</p>' : '') +
                (payment.mobile_money_sender_number ? '<p><span class="font-medium">Sender Number:</span> ' + payment.mobile_money_sender_number + '</p>' : '') +
                (payment.mobile_money_reference ? '<p><span class="font-medium">Reference:</span> ' + payment.mobile_money_reference + '</p>' : '') +
            '</div>' +
        '</div>'
    : '') +
    
    (payment.method === 'cash' ? 
        '<div class="border-t border-gray-200 pt-4">' +
            '<h4 class="text-sm font-medium text-gray-900 mb-2">Cash Payment Details</h4>' +
            '<div class="grid grid-cols-1 md:grid-cols-2 gap-2 text-sm">' +
                (payment.cash_payer_name ? '<p><span class="font-medium">Payer Name:</span> ' + payment.cash_payer_name + '</p>' : '') +
                (payment.cash_payer_phone ? '<p><span class="font-medium">Payer Phone:</span> ' + payment.cash_payer_phone + '</p>' : '') +
            '</div>' +
        '</div>'
    : '') +
'</div>';
                
                showViewPaymentModal(html);
                
                // Store data on print button
                const printBtn = document.getElementById('print-view-payment-btn');
                if (printBtn) {
                    printBtn.setAttribute('data-payment', JSON.stringify(payment));
                    printBtn.setAttribute('data-student', JSON.stringify(student));
                    printBtn.setAttribute('data-school-name', '<?= addslashes($schoolSettings['school_name'] ?? 'APSA-ERP') ?>');
                    printBtn.setAttribute('data-school-logo', '<?= $schoolSettings['school_logo'] ?? '' ?>');
                }
            } else {
                showViewPaymentModal('<p class="text-red-500">Failed to load payment details.</p>');
            }
        })
        .catch(error => {
            showViewPaymentModal('<p class="text-red-500">Error loading payment details.</p>');
        });
    }
});

// Handle View Report Details button clicks
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('view-report-details') || e.target.closest('.view-report-details')) {
        const button = e.target.classList.contains('view-report-details') ? e.target : e.target.closest('.view-report-details');
        const reportType = button.getAttribute('data-report-type');
        
        // Show loading state
        const modal = document.getElementById('report-details-modal');
        const content = document.getElementById('report-details-content');
        content.innerHTML = '<p>Loading report details...</p>';
        modal.classList.remove('hidden');
        
        // Prepare data for AJAX request
        const formData = new FormData();
        formData.append('report_type', reportType);
        formData.append('search_term', button.getAttribute('data-search-term') || '');
        formData.append('academic_year_id', button.getAttribute('data-academic-year-id') || '');
        
        // Add specific report parameters
        if (reportType === 'daily') {
            formData.append('report_date', button.getAttribute('data-report-date'));
        } else if (reportType === 'weekly') {
            formData.append('start_date', button.getAttribute('data-start-date'));
            formData.append('end_date', button.getAttribute('data-end-date'));
        } else if (reportType === 'monthly') {
            formData.append('month', button.getAttribute('data-month'));
        } else if (reportType === 'termly') {
            formData.append('academic_year_id', button.getAttribute('data-academic-year-id'));
            formData.append('term', button.getAttribute('data-term'));
        } else if (reportType === 'yearly') {
            formData.append('year', button.getAttribute('data-year'));
        }
        
        // Fetch report details via AJAX
        fetch('/finance/report-details', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.payments) {
                // Update Modal Title and Generate Header Info
                const modalTitle = document.querySelector('#report-details-modal h3');
                let headerInfo = '';
                
                if (reportType === 'daily') {
                    const date = new Date(button.getAttribute('data-report-date'));
                    const formattedDate = date.toLocaleDateString('en-US', { weekday: 'long', year: 'numeric', month: 'long', day: 'numeric' });
                    if (modalTitle) modalTitle.textContent = 'Daily Report Details';
                    headerInfo = `<div class="mb-4 bg-gray-50 p-3 rounded-md border border-gray-200">
                        <p class="text-sm text-gray-600">Date: <span class="font-medium text-gray-900">${formattedDate}</span></p>
                    </div>`;
                } else if (reportType === 'weekly') {
                    const week = button.getAttribute('data-week');
                    const startDate = new Date(button.getAttribute('data-start-date')).toLocaleDateString();
                    const endDate = new Date(button.getAttribute('data-end-date')).toLocaleDateString();
                    if (modalTitle) modalTitle.textContent = 'Weekly Report Details';
                    headerInfo = `<div class="mb-4 bg-gray-50 p-3 rounded-md border border-gray-200">
                        <p class="text-sm text-gray-600">Week: <span class="font-medium text-gray-900">${week}</span></p>
                        <p class="text-sm text-gray-600">Period: <span class="font-medium text-gray-900">${startDate} - ${endDate}</span></p>
                    </div>`;
                } else if (reportType === 'monthly') {
                    const monthName = button.getAttribute('data-month-name');
                    if (modalTitle) modalTitle.textContent = 'Monthly Report Details';
                    headerInfo = `<div class="mb-4 bg-gray-50 p-3 rounded-md border border-gray-200">
                        <p class="text-sm text-gray-600">Month: <span class="font-medium text-gray-900">${monthName}</span></p>
                    </div>`;
                } else if (reportType === 'termly') {
                    const academicYear = button.getAttribute('data-academic-year-name');
                    const term = button.getAttribute('data-term');
                    if (modalTitle) modalTitle.textContent = 'Termly Report Details';
                    headerInfo = `<div class="mb-4 bg-gray-50 p-3 rounded-md border border-gray-200">
                        <p class="text-sm text-gray-600">Academic Year: <span class="font-medium text-gray-900">${academicYear}</span></p>
                        <p class="text-sm text-gray-600">Term: <span class="font-medium text-gray-900">${term}</span></p>
                    </div>`;
                } else if (reportType === 'yearly') {
                    const year = button.getAttribute('data-year');
                    if (modalTitle) modalTitle.textContent = 'Yearly Report Details';
                    headerInfo = `<div class="mb-4 bg-gray-50 p-3 rounded-md border border-gray-200">
                        <p class="text-sm text-gray-600">Year: <span class="font-medium text-gray-900">${year}</span></p>
                    </div>`;
                }

                // Generate HTML for the payments table
                let html = headerInfo + `
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200" id="report-details-table">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                `;
                
                let totalAmount = 0;
                
                data.payments.forEach(payment => {
                    totalAmount += parseFloat(payment.amount) || 0;
                    
                    const methodLabels = {
                        'cash': 'Cash',
                        'cheque': 'Cheque',
                        'bank_transfer': 'Bank Transfer',
                        'mobile_money': 'Mobile Money',
                        'other': 'Other'
                    };
                    const method = payment.method || 'other';
                    const methodLabel = methodLabels[method] || method.replace('_', ' ').toUpperCase();
                    
                    html += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${payment.first_name || ''} ${payment.last_name || ''}</div>
                                <div class="text-sm text-gray-500">${payment.admission_no || ''}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${payment.class_name || 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₵${parseFloat(payment.amount || 0).toFixed(2)}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                    ${methodLabel}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${new Date(payment.date).toLocaleDateString()}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${payment.fee_name || 'N/A'}</td>
                        </tr>
                    `;
                });
                
                html += `
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td colspan="1" class="px-6 py-3 text-sm font-medium text-gray-900">Total</td>
                                    <td class="px-6 py-3 text-sm font-bold text-gray-900">₵${totalAmount.toFixed(2)}</td>
                                    <td colspan="3"></td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                `;
                
                content.innerHTML = html;
                
                // Store data for export/print
                const exportBtn = document.getElementById('export-report-btn');
                const printBtn = document.getElementById('print-report-btn');
                
                exportBtn.setAttribute('data-payments', JSON.stringify(data.payments));
                printBtn.setAttribute('data-payments', JSON.stringify(data.payments));
                
                // Store header info for export/print
                // Create a temporary element to strip HTML tags for text-only export
                const tempDiv = document.createElement('div');
                tempDiv.innerHTML = headerInfo;
                const headerText = tempDiv.innerText;
                
                exportBtn.setAttribute('data-header-text', headerText);
                printBtn.setAttribute('data-header-html', headerInfo);
                printBtn.setAttribute('data-title', modalTitle ? modalTitle.textContent : 'Transaction Details');
            } else {
                content.innerHTML = '<p class="text-red-500">Failed to load report details.</p>';
            }
        })
        .catch(error => {
            content.innerHTML = '<p class="text-red-500">Error loading report details.</p>';
        });
    }
});

// Handle Close Report Modal button click
document.getElementById('close-report-modal-btn').addEventListener('click', function() {
    document.getElementById('report-details-modal').classList.add('hidden');
});

// Handle Export Report button click
document.getElementById('export-report-btn').addEventListener('click', function() {
    const paymentsData = JSON.parse(this.getAttribute('data-payments') || '[]');
    
    if (paymentsData.length === 0) {
        alert('No data to export');
        return;
    }
    
    // Create CSV content
    const headerText = this.getAttribute('data-header-text') || '';
    let csvContent = headerText ? headerText + '\n\n' : '';
    csvContent += 'Student Name,Admission No,Class,Amount,Method,Date,Fee Name\n';
    
    paymentsData.forEach(payment => {
        const methodLabels = {
            'cash': 'Cash',
            'cheque': 'Cheque',
            'bank_transfer': 'Bank Transfer',
            'mobile_money': 'Mobile Money',
            'other': 'Other'
        };
        const method = payment.method || 'other';
        const methodLabel = methodLabels[method] || method.replace('_', ' ').toUpperCase();
        
        csvContent += `"${(payment.first_name || '') + ' ' + (payment.last_name || '')}",`;
        csvContent += `"${payment.admission_no || ''}",`;
        csvContent += `"${payment.class_name || 'N/A'}",`;
        csvContent += `"${payment.amount || 0}",`;
        csvContent += `"${methodLabel}",`;
        csvContent += `"${new Date(payment.date).toLocaleDateString()}",`;
        csvContent += `"${payment.fee_name || 'N/A'}"\n`;
    });
    
    // Create download link
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', 'transaction_details.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
});

// Handle Print Report button click
document.getElementById('print-report-btn').addEventListener('click', function() {
    const paymentsData = JSON.parse(this.getAttribute('data-payments') || '[]');
    
    if (paymentsData.length === 0) {
        alert('No data to print');
        return;
    }
    
    const headerHtml = this.getAttribute('data-header-html') || '';
    const title = this.getAttribute('data-title') || 'Transaction Details';
    const schoolName = '<?= addslashes($settings['school_name'] ?? 'School Management System') ?>';
    const schoolLogo = '<?= $settings['school_logo'] ?? '' ?>';
    
    // Create print window
    const printWindow = window.open('', '_blank');
    
    const logoHtml = schoolLogo ? `<img src="${schoolLogo}" alt="School Logo" class="school-logo">` : '';
    
    printWindow.document.write(`
        <html>
            <head>
                <title>${title}</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
                    .school-logo { max-height: 80px; margin-bottom: 10px; }
                    .school-name { font-size: 24px; font-weight: bold; margin: 0 0 5px 0; color: #333; }
                    .report-title { font-size: 18px; margin: 10px 0; color: #555; text-transform: uppercase; letter-spacing: 1px; }
                    .header-info { margin-bottom: 20px; text-align: center; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    tfoot td { font-weight: bold; }
                </style>
            </head>
            <body>
                <div class="header">
                    ${logoHtml}
                    <h1 class="school-name">${schoolName}</h1>
                    <div class="report-title">${title}</div>
                    <p style="color: #666; font-size: 14px; margin-top: 5px;">Printed on: ${new Date().toLocaleString()}</p>
                </div>
                <div class="header-info">${headerHtml}</div>
                <table>
                    <thead>
                        <tr>
                            <th>Student Name</th>
                            <th>Admission No</th>
                            <th>Class</th>
                            <th>Amount</th>
                            <th>Method</th>
                            <th>Date</th>
                            <th>Fee Name</th>
                        </tr>
                    </thead>
                    <tbody>
    `);
    
    let totalAmount = 0;
    
    paymentsData.forEach(payment => {
        totalAmount += parseFloat(payment.amount) || 0;
        
        const methodLabels = {
            'cash': 'Cash',
            'cheque': 'Cheque',
            'bank_transfer': 'Bank Transfer',
            'mobile_money': 'Mobile Money',
            'other': 'Other'
        };
        const method = payment.method || 'other';
        const methodLabel = methodLabels[method] || method.replace('_', ' ').toUpperCase();
        
        printWindow.document.write(`
            <tr>
                <td>${(payment.first_name || '') + ' ' + (payment.last_name || '')}</td>
                <td>${payment.admission_no || ''}</td>
                <td>${payment.class_name || 'N/A'}</td>
                <td>₵${parseFloat(payment.amount || 0).toFixed(2)}</td>
                <td>${methodLabel}</td>
                <td>${new Date(payment.date).toLocaleDateString()}</td>
                <td>${payment.fee_name || 'N/A'}</td>
            </tr>
        `);
    });
    
    printWindow.document.write(`
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="2"><strong>Total</strong></td>
                            <td><strong>₵${totalAmount.toFixed(2)}</strong></td>
                            <td colspan="4"></td>
                        </tr>
                    </tfoot>
                </table>
                <script>
                    window.onload = function() {
                        window.print();
                    }
                <\/script>
            </body>
        </html>
    `);
    
    printWindow.document.close();
});

// Handle View Fee Details button clicks (for Bills tab)
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('view-fee-details') || e.target.closest('.view-fee-details')) {
        const button = e.target.classList.contains('view-fee-details') ? e.target : e.target.closest('.view-fee-details');
        const feeId = button.getAttribute('data-fee-id');
        
        // Show loading state
        const modal = document.getElementById('fee-details-modal');
        const content = document.getElementById('fee-details-content');
        content.innerHTML = '<p>Loading fee details...</p>';
        modal.classList.remove('hidden');
        
        // Fetch fee details via AJAX
        fetch(`/finance/fee-details/${feeId}`, {
            method: 'GET',
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.students) {
                // Generate HTML for the students table
                // Get fee type and name from first student record as it's common
                const feeType = (data.students.length > 0 && data.students[0].fee_type) ? data.students[0].fee_type : 'N/A';
                const feeName = (data.students.length > 0 && data.students[0].fee_name) ? data.students[0].fee_name : 'Fee Details';
                const assignedClasses = data.assigned_classes || 'N/A';
                const academicYear = data.academic_year || 'N/A';
                const term = data.term || 'N/A';
                
                const schoolName = '<?= addslashes($settings['school_name'] ?? 'School Management System') ?>';
                const schoolLogo = '<?= $settings['school_logo'] ?? '' ?>';
                const logoHtml = schoolLogo ? `<img src="${schoolLogo}" alt="School Logo" class="h-12 w-auto object-contain">` : '';

                let html = `
                    <div class="flex justify-between items-start mb-4 border-b pb-4">
                        <div class="flex items-center space-x-4">
                            ${logoHtml}
                            <div>
                                <h2 class="text-xl font-bold text-gray-900">${schoolName}</h2>
                                <h3 class="text-md font-medium text-gray-600">${feeName}</h3>
                                <div class="mt-2 text-sm text-gray-500">
                                    <p>Type: <span class="font-medium text-gray-900">${feeType.replace('_', ' ').toUpperCase()}</span></p>
                                    <p>Academic Year: <span class="font-medium text-gray-900">${academicYear}</span></p>
                                    <p>Term: <span class="font-medium text-gray-900">${term}</span></p>
                                    <p>Assigned to: <span class="font-medium text-gray-900">${assignedClasses}</span></p>
                                </div>
                            </div>
                        </div>
                        <div class="flex space-x-2 mt-1">
                            <button type="button" id="print-fee-details-btn" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                </svg>
                                Print
                            </button>
                            <button type="button" id="export-fee-details-btn" class="inline-flex items-center px-3 py-2 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                <svg class="-ml-0.5 mr-2 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                </svg>
                                CSV
                            </button>
                        </div>
                    </div>
                    <div id="fee-details-table-container" class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Amount</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                `;
                
                let totalExpected = 0;
                let totalPaid = 0;
                let totalBalance = 0;
                
                data.students.forEach(student => {
                    totalExpected += parseFloat(student.fee_amount) || 0;
                    totalPaid += parseFloat(student.total_paid) || 0;
                    totalBalance += parseFloat(student.balance) || 0;
                    
                    html += `
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${new Date(student.assigned_date).toLocaleDateString()}</td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900">${student.first_name || ''} ${student.last_name || ''}</div>
                                <div class="text-sm text-gray-500">${student.admission_no || ''}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${student.class_name || 'N/A'}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₵${parseFloat(student.fee_amount || 0).toFixed(2)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">₵${parseFloat(student.total_paid || 0).toFixed(2)}</td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm ${student.balance > 0 ? 'text-red-600 font-bold' : 'text-green-600'}">
                                ₵${parseFloat(student.balance || 0).toFixed(2)}
                            </td>
                        </tr>
                    `;
                });
                
                html += `
                            </tbody>
                            <tfoot class="bg-gray-50">
                                <tr>
                                    <td class="px-6 py-3 text-sm font-medium text-gray-900" colspan="3">Total</td>
                                    <td class="px-6 py-3 text-sm font-bold text-gray-900">₵${totalExpected.toFixed(2)}</td>
                                    <td class="px-6 py-3 text-sm font-bold text-gray-900">₵${totalPaid.toFixed(2)}</td>
                                    <td class="px-6 py-3 text-sm font-bold ${totalBalance > 0 ? 'text-red-600' : 'text-green-600'}">₵${totalBalance.toFixed(2)}</td>
                                </tr>
                            </tfoot>
                        </table>
                    </div>
                `;
                
                content.innerHTML = html;

                // Bind new buttons
                const printBtn = document.getElementById('print-fee-details-btn');
                if (printBtn) {
                    printBtn.addEventListener('click', function() {
                        const printWindow = window.open('', '_blank');
                        const tableHtml = document.querySelector('#fee-details-table-container table').outerHTML;
                        
                        const schoolName = '<?= addslashes($settings['school_name'] ?? 'School Management System') ?>';
                        const schoolLogo = '<?= $settings['school_logo'] ?? '' ?>';
                        const logoHtml = schoolLogo ? `<img src="${schoolLogo}" alt="School Logo" class="school-logo">` : '';
                        
                        printWindow.document.write(`
                            <html>
                                <head>
                                    <title>${feeName} - Details</title>
                                    <style>
                                        body { font-family: Arial, sans-serif; margin: 20px; }
                                        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
                                        .school-logo { max-height: 80px; margin-bottom: 10px; }
                                        .school-name { font-size: 24px; font-weight: bold; margin: 0 0 5px 0; color: #333; }
                                        .report-title { font-size: 18px; margin: 10px 0; color: #555; text-transform: uppercase; letter-spacing: 1px; }
                                        .header-info { text-align: center; margin-bottom: 20px; }
                                        .header-info p { margin: 5px 0; color: #666; }
                                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                                        th { background-color: #f2f2f2; }
                                        .total-row { font-weight: bold; }
                                    </style>
                                </head>
                                <body>
                                    <div class="header">
                                        ${logoHtml}
                                        <h1 class="school-name">${schoolName}</h1>
                                        <div class="report-title">${feeName}</div>
                                        <p style="color: #666; font-size: 14px; margin-top: 5px;">Printed on: ${new Date().toLocaleString()}</p>
                                    </div>
                                    <div class="header-info">
                                        <p>Type: ${feeType.replace('_', ' ').toUpperCase()}</p>
                                        <p>Academic Year: ${academicYear}</p>
                                        <p>Term: ${term}</p>
                                        <p>Assigned to: ${assignedClasses}</p>
                                    </div>
                                    ${tableHtml}
                                    <script>window.onload = function() { window.print(); }<\/script>
                                </body>
                            </html>
                        `);
                        printWindow.document.close();
                    });
                }

                const exportBtn = document.getElementById('export-fee-details-btn');
                if (exportBtn) {
                    exportBtn.addEventListener('click', function() {
                        let csvFnContent = `Fee Structure: ${feeName}\nType: ${feeType.replace('_', ' ').toUpperCase()}\nAcademic Year: ${academicYear}\nTerm: ${term}\nAssigned Class(es): ${assignedClasses}\n\n`;
                        
                        // Get headers
                        const headers = [];
                        document.querySelectorAll('#fee-details-table-container th').forEach(th => headers.push(th.textContent.trim()));
                        csvFnContent += headers.join(',') + '\n';
                        
                        // Get rows
                        document.querySelectorAll('#fee-details-table-container tbody tr').forEach(tr => {
                            const rowData = [];
                            tr.querySelectorAll('td').forEach(td => rowData.push(`"${td.textContent.trim().replace(/\n/g, ' ').replace(/\s+/g, ' ')}"`));
                            csvFnContent += rowData.join(',') + '\n';
                        });

                        // Download
                        const blob = new Blob([csvFnContent], { type: 'text/csv;charset=utf-8;' });
                        const url = URL.createObjectURL(blob);
                        const link = document.createElement('a');
                        link.setAttribute('href', url);
                        link.setAttribute('download', `${feeName.replace(/\s+/g, '_')}_Details.csv`);
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    });
                }
            } else {
                content.innerHTML = '<p class="text-red-500">Failed to load fee details.</p>';
            }
        })
        .catch(error => {
            content.innerHTML = '<p class="text-red-500">Error loading fee details.</p>';
        });
    }
});

// Handle Close Fee Modal button click
document.getElementById('close-fee-modal-btn').addEventListener('click', function() {
    document.getElementById('fee-details-modal').classList.add('hidden');
});

// Handle print summary dropdown
const printSummaryOptions = document.getElementById('print-summary-options');
const printSummaryDropdown = document.getElementById('print-summary-dropdown');

if (printSummaryOptions) {
    printSummaryOptions.addEventListener('click', function(e) {
        e.stopPropagation();
        printSummaryDropdown.classList.toggle('hidden');
    });
}

// Handle export summary dropdown
const exportSummaryOptions = document.getElementById('export-summary-options');
const exportSummaryDropdown = document.getElementById('export-summary-dropdown');

if (exportSummaryOptions) {
    exportSummaryOptions.addEventListener('click', function(e) {
        e.stopPropagation();
        exportSummaryDropdown.classList.toggle('hidden');
    });
}

// Close dropdowns when clicking outside
document.addEventListener('click', function(e) {
    if (printSummaryDropdown && !printSummaryDropdown.contains(e.target) && !printSummaryOptions.contains(e.target)) {
        printSummaryDropdown.classList.add('hidden');
    }
    
    if (exportSummaryDropdown && !exportSummaryDropdown.contains(e.target) && !exportSummaryOptions.contains(e.target)) {
        exportSummaryDropdown.classList.add('hidden');
    }
});

// Handle print summary only
const printSummaryOnly = document.getElementById('print-summary-only');
if (printSummaryOnly) {
    printSummaryOnly.addEventListener('click', function() {
        printSummaryOnlyFunction();
        printSummaryDropdown.classList.add('hidden');
    });
}

// Handle print summary and fees
const printSummaryAndFees = document.getElementById('print-summary-and-fees');
if (printSummaryAndFees) {
    printSummaryAndFees.addEventListener('click', function() {
        printSummaryAndFeesFunction();
        printSummaryDropdown.classList.add('hidden');
    });
}

// Handle export summary only
const exportSummaryOnly = document.getElementById('export-summary-only');
if (exportSummaryOnly) {
    exportSummaryOnly.addEventListener('click', function() {
        exportSummaryOnlyFunction();
        exportSummaryDropdown.classList.add('hidden');
    });
}

// Handle export summary and fees
const exportSummaryAndFees = document.getElementById('export-summary-and-fees');
if (exportSummaryAndFees) {
    exportSummaryAndFees.addEventListener('click', function() {
        exportSummaryAndFeesFunction();
        exportSummaryDropdown.classList.add('hidden');
    });
}

// Handle export fees only
const exportFeesOnly = document.getElementById('export-fees-only');
if (exportFeesOnly) {
    exportFeesOnly.addEventListener('click', function() {
        exportFeesOnlyFunction();
        exportSummaryDropdown.classList.add('hidden');
    });
}

// Print summary only function
function printSummaryOnlyFunction() {
    // Get the report summary table
    const reportTable = document.querySelector('#report-summary-section table');
    const reportHeader = document.querySelector('#report-summary-section h2');
    const reportTotalEl = document.querySelector('#report-summary-section .text-lg.font-bold.text-indigo-600');
    
    const reportTitle = reportHeader ? reportHeader.textContent : 'Report Summary';
    const reportTotal = reportTotalEl ? reportTotalEl.textContent : '';

    if (!reportTable) {
        alert('No report summary to print');
        return;
    }

    // Clone the table to avoid modifying the original
    const clonedTable = reportTable.cloneNode(true);

    // Create print window
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Finance Report Summary</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .header { margin-bottom: 20px; }
                    .total { font-weight: bold; font-size: 1.2em; color: #4f46e5; }
                    .actions-column { display: none; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>${reportTitle}</h2>
                    <div class="total">${reportTotal}</div>
                </div>
    `);

    // Add the cloned table to the print window
    printWindow.document.body.appendChild(clonedTable);

    printWindow.document.write(
        '<script>' +
        'window.onload = function() {' +
        '    window.print();' +
        '}' +
        '</' + 'script>' +
        '</body></html>'
    );

    printWindow.document.close();
}

// Print summary and fees function
function printSummaryAndFeesFunction() {
    // Get the report summary table
    const reportTable = document.querySelector('#report-summary-section table');
    const reportHeader = document.querySelector('#report-summary-section h2');
    const reportTotalEl = document.querySelector('#report-summary-section .text-lg.font-bold.text-indigo-600');
    
    const reportTitle = reportHeader ? reportHeader.textContent : 'Report Summary';
    const reportTotal = reportTotalEl ? reportTotalEl.textContent : '';

    // Get the fees data table (the main payments table)
    const feesTable = document.querySelector('#payments-table-section table');

    if (!reportTable) {
        alert('No report summary to print');
        return;
    }

    // Clone the tables to avoid modifying the original
    const clonedReportTable = reportTable.cloneNode(true);
    const clonedFeesTable = feesTable ? feesTable.cloneNode(true) : null;

    // Create print window
    const printWindow = window.open('', '_blank');
    printWindow.document.write(`
        <html>
            <head>
                <title>Finance Report Summary and Fees Data</title>
                <style>
                    body { font-family: Arial, sans-serif; margin: 20px; }
                    table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                    th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                    th { background-color: #f2f2f2; }
                    .header { margin-bottom: 20px; }
                    .total { font-weight: bold; font-size: 1.2em; color: #4f46e5; }
                    .section { margin-top: 40px; }
                    .actions-column { display: none; }
                </style>
            </head>
            <body>
                <div class="header">
                    <h2>${reportTitle}</h2>
                    <div class="total">${reportTotal}</div>
                </div>
    `);

    // Add the report summary table
    printWindow.document.write('<div class="section"><h3>Report Summary</h3>');
    printWindow.document.body.appendChild(clonedReportTable);
    printWindow.document.write('</div>');

    // Add fees table if available
    if (clonedFeesTable) {
        printWindow.document.write('<div class="section"><h3>Fees Data</h3>');
        printWindow.document.body.appendChild(clonedFeesTable);
        printWindow.document.write('</div>');
    }

    printWindow.document.write(
        '<script>' +
        'window.onload = function() {' +
        '    window.print();' +
        '}' +
        '</' + 'script>' +
        '</body></html>'
    );

    printWindow.document.close();
}

// Export summary only function
function exportSummaryOnlyFunction() {
    // Get the report summary table
    const reportTable = document.querySelector('#report-summary-section table');
    const reportHeader = document.querySelector('#report-summary-section h2');
    const reportTotalEl = document.querySelector('#report-summary-section .text-lg.font-bold.text-indigo-600');

    const reportTitle = reportHeader ? reportHeader.textContent : 'Report Summary';
    const reportTotal = reportTotalEl ? reportTotalEl.textContent : '';

    if (!reportTable) {
        alert('No report summary to export');
        return;
    }

    // Get table headers (excluding Actions column)
    const headers = [];
    const headerRow = reportTable.querySelector('thead tr');
    if (headerRow) {
        const headerCells = headerRow.querySelectorAll('th:not(.actions-column)');
        headerCells.forEach(cell => {
            headers.push(cell.textContent.trim());
        });
    }

    // Get table rows (excluding Actions column)
    const rows = [];
    reportTable.querySelectorAll('tbody tr').forEach(row => {
        const rowData = [];
        const cells = row.querySelectorAll('td:not(.actions-column)');
        cells.forEach(cell => {
            rowData.push(cell.textContent.trim());
        });
        rows.push(rowData.join(','));
    });

    // Create CSV content
    let csvContent = `${reportTitle}\n`;
    csvContent += `${reportTotal}\n\n`;
    csvContent += headers.join(',') + '\n';
    csvContent += rows.join('\n');

    // Create download link
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', 'finance_report_summary.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Export fees only function
function exportFeesOnlyFunction() {
    // Get the fees data table (the main payments table)
    const feesTable = document.querySelector('#payments-table-section table');

    if (!feesTable) {
        alert('No fees data to export');
        return;
    }

    // Create CSV content
    let csvContent = 'FEES DATA\n';

    // Get fees table headers (excluding Actions column)
    const feesHeaders = [];
    const feesHeaderRow = feesTable.querySelector('thead tr');
    if (feesHeaderRow) {
        const headerCells = feesHeaderRow.querySelectorAll('th:not(.actions-column)');
        headerCells.forEach(cell => {
            feesHeaders.push(cell.textContent.trim());
        });
    }

    csvContent += feesHeaders.join(',') + '\n';

    // Get fees table rows (excluding Actions column)
    feesTable.querySelectorAll('tbody tr').forEach(row => {
        const rowData = [];
        const cells = row.querySelectorAll('td:not(.actions-column)');
        cells.forEach(cell => {
            rowData.push(cell.textContent.trim().replace(/,/g, ';')); // Replace commas to avoid CSV issues
        });
        csvContent += rowData.join(',') + '\n';
    });

    // Create download link
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', 'finance_fees_data_only.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Export summary and fees function
function exportSummaryAndFeesFunction() {
    // Get the report summary table
    const reportTable = document.querySelector('#report-summary-section table');
    const reportHeader = document.querySelector('#report-summary-section h2');
    const reportTotalEl = document.querySelector('#report-summary-section .text-lg.font-bold.text-indigo-600');
    
    const reportTitle = reportHeader ? reportHeader.textContent : 'Report Summary';
    const reportTotal = reportTotalEl ? reportTotalEl.textContent : '';

    // Get the fees data table (the main payments table)
    const feesTable = document.querySelector('#payments-table-section table');

    if (!reportTable) {
        alert('No report summary to export');
        return;
    }

    // Create CSV content
    let csvContent = `${reportTitle}\n`;
    csvContent += `${reportTotal}\n\n`;

    // Add summary section
    csvContent += 'REPORT SUMMARY\n';

    // Get summary table headers (excluding Actions column)
    const headers = [];
    const headerRow = reportTable.querySelector('thead tr');
    if (headerRow) {
        const headerCells = headerRow.querySelectorAll('th:not(.actions-column)');
        headerCells.forEach(cell => {
            headers.push(cell.textContent.trim());
        });
    }

    csvContent += headers.join(',') + '\n';

    // Get summary table rows (excluding Actions column)
    reportTable.querySelectorAll('tbody tr').forEach(row => {
        const rowData = [];
        const cells = row.querySelectorAll('td:not(.actions-column)');
        cells.forEach(cell => {
            rowData.push(cell.textContent.trim().replace(/,/g, ';')); // Replace commas to avoid CSV issues
        });
        csvContent += rowData.join(',') + '\n';
    });

    // Add fees section if available
    if (feesTable) {
        csvContent += '\n\nFEES DATA\n';

        // Get fees table headers (excluding Actions column)
        const feesHeaders = [];
        const feesHeaderRow = feesTable.querySelector('thead tr');
        if (feesHeaderRow) {
            const headerCells = feesHeaderRow.querySelectorAll('th:not(.actions-column)');
            headerCells.forEach(cell => {
                feesHeaders.push(cell.textContent.trim());
            });
        }

        csvContent += feesHeaders.join(',') + '\n';

        // Get fees table rows (excluding Actions column)
        feesTable.querySelectorAll('tbody tr').forEach(row => {
            const rowData = [];
            const cells = row.querySelectorAll('td:not(.actions-column)');
            cells.forEach(cell => {
                rowData.push(cell.textContent.trim().replace(/,/g, ';')); // Replace commas to avoid CSV issues
            });
            csvContent += rowData.join(',') + '\n';
        });
    }

    // Create download link
    const blob = new Blob([csvContent], { type: 'text/csv;charset=utf-8;' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.setAttribute('href', url);
    link.setAttribute('download', 'finance_report_summary_and_fees.csv');
    link.style.visibility = 'hidden';
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

// Initialize the form
document.addEventListener('DOMContentLoaded', function() {
    console.log('DOM Content Loaded - Initializing finance page event listeners');
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

    // Handle report type change
    const reportTypeSelect = document.getElementById('report_type');
    if (reportTypeSelect) {
        reportTypeSelect.addEventListener('change', function() {
            // Submit the form when report type changes
            this.form.submit();
        });
    }

    // Handle dropdown toggles via delegation
    document.addEventListener('click', function(e) {
        // Print Summary Dropdown Toggle
        const printBtn = e.target.closest('#print-summary-options');
        const printDropdown = document.getElementById('print-summary-dropdown');
        if (printBtn && printDropdown) {
            e.stopPropagation();
            printDropdown.classList.toggle('hidden');
            // Close other dropdown if open
            const exportDropdown = document.getElementById('export-summary-dropdown');
            if (exportDropdown && !exportDropdown.classList.contains('hidden')) {
                exportDropdown.classList.add('hidden');
            }
            return;
        }

        // Export Summary Dropdown Toggle
        const exportBtn = e.target.closest('#export-summary-options');
        const exportDropdown = document.getElementById('export-summary-dropdown');
        if (exportBtn && exportDropdown) {
            e.stopPropagation();
            exportDropdown.classList.toggle('hidden');
             // Close other dropdown if open
            const printDropdown = document.getElementById('print-summary-dropdown');
            if (printDropdown && !printDropdown.classList.contains('hidden')) {
                printDropdown.classList.add('hidden');
            }
            return;
        }

        // Close dropdowns when clicking outside
        if (printDropdown && !printDropdown.classList.contains('hidden')) {
            printDropdown.classList.add('hidden');
        }
        if (exportDropdown && !exportDropdown.classList.contains('hidden')) {
            exportDropdown.classList.add('hidden');
        }
    });

    // Handle Print Actions via delegation
    document.addEventListener('click', function(e) {
        const printSummaryOnlyBtn = e.target.closest('#print-summary-only');
        if (printSummaryOnlyBtn) {
            printSummaryOnlyFunction();
            document.getElementById('print-summary-dropdown').classList.add('hidden');
            return;
        }

        const printSummaryFeesBtn = e.target.closest('#print-summary-and-fees');
        if (printSummaryFeesBtn) {
            printSummaryAndFeesFunction();
            document.getElementById('print-summary-dropdown').classList.add('hidden');
            return;
        }
    });

    // Handle Export Actions via delegation
    document.addEventListener('click', function(e) {
        const exportSummaryOnlyBtn = e.target.closest('#export-summary-only');
        if (exportSummaryOnlyBtn) {
            exportSummaryOnlyFunction();
            document.getElementById('export-summary-dropdown').classList.add('hidden');
            return;
        }

        const exportSummaryFeesBtn = e.target.closest('#export-summary-and-fees');
        if (exportSummaryFeesBtn) {
            exportSummaryAndFeesFunction();
            document.getElementById('export-summary-dropdown').classList.add('hidden');
            return;
        }

        const exportFeesOnlyBtn = e.target.closest('#export-fees-only');
        if (exportFeesOnlyBtn) {
            exportFeesOnlyFunction();
            document.getElementById('export-summary-dropdown').classList.add('hidden');
            return;
        }
    });
});
</script>
<!-- View Payment Modal -->
<div id="view-payment-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-start mb-4 border-b pb-4">
                <div class="flex items-center space-x-4">
                    <?php if (!empty($settings['school_logo'])): ?>
                        <img src="<?= htmlspecialchars($settings['school_logo']) ?>" alt="School Logo" class="h-12 w-auto object-contain">
                    <?php endif; ?>
                    <div>
                        <h2 class="text-xl font-bold text-gray-900"><?= htmlspecialchars($settings['school_name'] ?? 'School Management System') ?></h2>
                        <h3 class="text-md font-medium text-gray-600">Receipt</h3>
                    </div>
                </div>
                <div class="flex items-center space-x-2 mt-1">
                    <button id="print-view-payment-btn" class="inline-flex items-center px-3 py-1 border border-gray-300 text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-0.5 mr-1 h-4 w-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                        </svg>
                        Print
                    </button>
                    <button id="close-view-payment-modal" class="text-gray-400 hover:text-gray-500">
                        <span class="text-2xl">&times;</span>
                    </button>
                </div>
            </div>
            <div id="view-payment-content" class="mt-2">
                <!-- Content will be loaded here via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
// Handle Close View Payment Modal button click
document.getElementById('close-view-payment-modal').addEventListener('click', function() {
    document.getElementById('view-payment-modal').classList.add('hidden');
});

// Close modal when clicking outside
document.getElementById('view-payment-modal').addEventListener('click', function(e) {
    if (e.target === this) {
        this.classList.add('hidden');
    }
});
</script>

<script>
// Handle Print View Payment Modal button click
document.getElementById('print-view-payment-btn').addEventListener('click', function() {
    const paymentData = this.getAttribute('data-payment');
    const studentData = this.getAttribute('data-student');
    const schoolName = this.getAttribute('data-school-name') || 'School Name';
    const schoolLogo = this.getAttribute('data-school-logo') || '';
    
    if (!paymentData || !studentData) {
        alert('No payment details to print');
        return;
    }
    
    const payment = JSON.parse(paymentData);
    const student = JSON.parse(studentData);
    
    const printWindow = window.open('', '_blank');
    
    // Format date
    const date = new Date(payment.date).toLocaleDateString();
    
    // Format amount
    const amount = parseFloat(payment.amount || 0).toFixed(2);
    
    // Determine method label
    const methodLabels = {
        'cash': 'Cash',
        'cheque': 'Cheque',
        'bank_transfer': 'Bank Transfer',
        'mobile_money': 'Mobile Money'
    };
    const methodLabel = methodLabels[payment.method] || payment.method.replace('_', ' ').toUpperCase();
    
    // Logo HTML
    const logoHtml = schoolLogo ? `<img src="${schoolLogo}" alt="School Logo" style="max-height: 80px; margin-bottom: 10px;">` : '';
    
    printWindow.document.write(`
        <html>
            <head>
                <title>Payment Receipt - ${payment.id}</title>
                <style>
                    body { font-family: 'Helvetica Neue', Helvetica, Arial, sans-serif; color: #333; line-height: 1.6; padding: 40px; }
                    .receipt-container { max-width: 800px; margin: 0 auto; border: 1px solid #ddd; padding: 40px; box-shadow: 0 0 10px rgba(0,0,0,0.05); }
                    .header { text-align: center; margin-bottom: 40px; padding-bottom: 20px; border-bottom: 2px solid #eee; }
                    .header h1 { margin: 10px 0 0; color: #2c3e50; font-size: 28px; }
                    .header h2 { margin: 5px 0 0; color: #7f8c8d; font-size: 18px; font-weight: normal; }
                    .header p { color: #95a5a6; margin: 5px 0 0; font-size: 14px; }
                    .receipt-info { display: flex; justify-content: space-between; margin-bottom: 30px; }
                    .info-group h3 { margin: 0 0 10px; font-size: 14px; text-transform: uppercase; color: #95a5a6; letter-spacing: 1px; }
                    .info-group p { margin: 0; font-weight: bold; font-size: 16px; }
                    .amount-box { background: #f8f9fa; padding: 20px; border-radius: 8px; text-align: center; margin-bottom: 30px; }
                    .amount-box h2 { margin: 0; color: #2c3e50; font-size: 36px; }
                    .amount-box span { font-size: 14px; color: #7f8c8d; text-transform: uppercase; letter-spacing: 1px; }
                    .details-table { width: 100%; border-collapse: collapse; margin-bottom: 30px; }
                    .details-table th, .details-table td { padding: 12px; border-bottom: 1px solid #eee; text-align: left; }
                    .details-table th { color: #7f8c8d; font-weight: normal; width: 40%; }
                    .details-table td { font-weight: 500; }
                    .footer { text-align: center; font-size: 12px; color: #95a5a6; margin-top: 40px; padding-top: 20px; border-top: 1px solid #eee; }
                    @media print {
                        body { padding: 0; }
                        .receipt-container { border: none; box-shadow: none; }
                    }
                </style>
            </head>
            <body>
                <div class="receipt-container">
                    <div class="header">
                        ${logoHtml}
                        <h2>${schoolName}</h2>
                        <h1>Payment Receipt</h1>
                        <p>Receipt #${payment.id}</p>
                    </div>
                    
                    <div class="receipt-info">
                        <div class="info-group">
                            <h3>Student</h3>
                            <p>${student.first_name} ${student.last_name}</p>
                            <p style="font-weight: normal; font-size: 14px; color: #666;">${student.admission_no}</p>
                        </div>
                        <div class="info-group" style="text-align: right;">
                            <h3>Date</h3>
                            <p>${date}</p>
                        </div>
                    </div>
                    
                    <div class="amount-box">
                        <span>Amount Paid</span>
                        <h2>₵${amount}</h2>
                    </div>
                    
                    <table class="details-table">
                        <tr>
                            <th>Payment Method</th>
                            <td>${methodLabel}</td>
                        </tr>
                        <tr>
                            <th>Fee Type</th>
                            <td>${payment.fee_name || 'N/A'}</td>
                        </tr>
                        ${payment.remarks ? `
                        <tr>
                            <th>Remarks</th>
                            <td>${payment.remarks}</td>
                        </tr>` : ''}
                        ${generateMethodDetails(payment)}
                    </table>
                    
                    <div class="footer">
                        <p>This is a computer-generated receipt.</p>
                        <p>${new Date().toLocaleString()}</p>
                    </div>
                </div>
                <script>
                    window.onload = function() {
                        window.print();
                    }
                <\/script>
            </body>
        </html>
    `);
    
    printWindow.document.close();
});

// Helper to generate method specific details for print
function generateMethodDetails(payment) {
    if (payment.method === 'cheque') {
        return `
            <tr><th colspan="2" style="background: #f9f9f9; padding-top: 20px; padding-bottom: 5px;">Cheque Details</th></tr>
            ${payment.cheque_bank ? `<tr><th>Bank</th><td>${payment.cheque_bank}</td></tr>` : ''}
            ${payment.cheque_number ? `<tr><th>Cheque No.</th><td>${payment.cheque_number}</td></tr>` : ''}
        `;
    } else if (payment.method === 'bank_transfer') {
        return `
            <tr><th colspan="2" style="background: #f9f9f9; padding-top: 20px; padding-bottom: 5px;">Transfer Details</th></tr>
            ${payment.bank_transfer_sender_bank ? `<tr><th>Sender Bank</th><td>${payment.bank_transfer_sender_bank}</td></tr>` : ''}
            ${payment.bank_transfer_invoice_number ? `<tr><th>Invoice No.</th><td>${payment.bank_transfer_invoice_number}</td></tr>` : ''}
        `;
    } else if (payment.method === 'mobile_money') {
        return `
            <tr><th colspan="2" style="background: #f9f9f9; padding-top: 20px; padding-bottom: 5px;">Mobile Money Details</th></tr>
            ${payment.mobile_money_sender_name ? `<tr><th>Sender Name</th><td>${payment.mobile_money_sender_name}</td></tr>` : ''}
            ${payment.mobile_money_reference ? `<tr><th>Reference</th><td>${payment.mobile_money_reference}</td></tr>` : ''}
        `;
    }
    return '';
}
// Handle print student bills
const printStudentBillsBtn = document.getElementById('print-student-bills-btn');
if (printStudentBillsBtn) {
    printStudentBillsBtn.addEventListener('click', function() {
        const tableContainer = document.getElementById('student-bills-table-container');
        if (!tableContainer) return;

        const table = tableContainer.querySelector('table');
        if (!table) {
            alert('No data to print');
            return;
        }

        const clonedTable = table.cloneNode(true);
        const schoolName = '<?= addslashes($settings['school_name'] ?? 'School Management System') ?>';
        const schoolLogo = '<?= $settings['school_logo'] ?? '' ?>';
        const logoHtml = schoolLogo ? `<img src="${schoolLogo}" alt="School Logo" class="school-logo">` : '';

        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <html>
                <head>
                    <title>Student Bills Report</title>
                    <style>
                        body { font-family: Arial, sans-serif; margin: 20px; }
                        .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 15px; }
                        .school-logo { max-height: 80px; margin-bottom: 10px; }
                        .school-name { font-size: 24px; font-weight: bold; margin: 0 0 5px 0; color: #333; }
                        .report-title { font-size: 18px; margin: 10px 0; color: #555; text-transform: uppercase; letter-spacing: 1px; }
                        table { width: 100%; border-collapse: collapse; margin-top: 20px; }
                        th, td { border: 1px solid #ddd; padding: 8px; text-align: left; }
                        th { background-color: #f2f2f2; }
                        tfoot td { font-weight: bold; background-color: #f9fafb; font-size: 14px; }
                    </style>
                </head>
                <body>
                    <div class="header">
                        ${logoHtml}
                        <h1 class="school-name">${schoolName}</h1>
                        <div class="report-title">Student Bills Report</div>
                        <p style="color: #666; font-size: 14px; margin-top: 5px;">Printed on: ${new Date().toLocaleString()}</p>
                    </div>
        `);
        
        // Add the table structure
        printWindow.document.write('<div>');
        printWindow.document.body.appendChild(clonedTable);
        printWindow.document.write('</div>');
        
        printWindow.document.write(`
                    <script>
                        window.onload = function() {
                            window.print();
                        }
                    <\/script>
                </body>
            </html>
        `);
        
        printWindow.document.close();
    });
}
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>