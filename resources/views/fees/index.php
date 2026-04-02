<?php 
// Function to build pagination URLs
function buildPaginationUrl($page, $perPage, $searchTerm = '', $academicYearId = '', $dateFrom = '', $dateTo = '', $enableDateFilter = false) {
    $params = [
        'tab' => 'payments',
        'page' => $page,
        'per_page' => $perPage
    ];
    
    if ($searchTerm) {
        $params['search'] = $searchTerm;
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
    
    return '/fees?' . http_build_query($params);
}

$title = 'Fee Management'; 
ob_start(); 
$activeTab = $_GET['tab'] ?? 'structures';
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Fee Management</h1>
            <?php if ($activeTab === 'structures'): ?>
            <a href="/fees/create" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                Add Fee Structure
            </a>
            <?php endif; ?>
        </div>
        
        <!-- Tabs -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="/fees?tab=structures" class="<?= $activeTab === 'structures' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Fee Structures
                </a>
                <a href="/fees?tab=pay" class="<?= $activeTab === 'pay' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Pay Fees
                </a>
                <a href="/fees?tab=payments" class="<?= $activeTab === 'payments' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Payments
                </a>
            </nav>
        </div>
        
        <!-- Fee Structures Tab -->
        <?php if ($activeTab === 'structures'): ?>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Type
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Class
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($fees)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No fee structures found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($fees as $fee): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($fee['name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php
                                        // Include currency helper
                                        require_once APP_PATH . '/Helpers/CurrencyHelper.php';
                                        echo \App\Helpers\CurrencyHelper::formatAmount($fee['amount']);
                                        ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php 
                                            switch($fee['type']) {
                                                case 'tuition': echo 'bg-blue-100 text-blue-800'; break;
                                                case 'transport': echo 'bg-green-100 text-green-800'; break;
                                                case 'feeding': echo 'bg-yellow-100 text-yellow-800'; break;
                                                default: echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?= htmlspecialchars(ucfirst($fee['type'])) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($fee['display_classes'] ?? $fee['class_name'] ?? 'All Classes') ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?= htmlspecialchars(substr($fee['description'] ?? 'N/A', 0, 50)) ?><?= strlen($fee['description'] ?? '') > 50 ? '...' : '' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <?= $fee['student_count'] ?? 0 ?> students
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button type="button" onclick="viewFeeStructure(<?= $fee['id'] ?>)" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            View
                                        </button>
                                        <?php if (isset($isSuperAdmin) && $isSuperAdmin): ?>
                                        <a href="/fees/<?= $fee['id'] ?>/edit" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </a>
                                        <?php endif; ?>
                                        <a href="/fees/<?= $fee['id'] ?>/assign" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Assign
                                        </a>
                                        <a href="/fees/<?= $fee['id'] ?>/delete" class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('Are you sure you want to delete this fee structure?')">
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
        <?php endif; ?>
        
        <!-- Pay Fees Tab -->
        <?php if ($activeTab === 'pay'): ?>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form id="pay-fees-form" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <!-- Search Student Section -->
                        <div class="sm:col-span-2">
                            <label for="student-search" class="block text-sm font-medium text-gray-700">Search Student</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="text" id="student-search" 
                                       class="block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                       placeholder="Search by name or admission number...">
                                <div id="student-suggestions" class="absolute z-10 mt-1 w-full bg-white shadow-lg rounded-md hidden">
                                    <!-- Suggestions will be populated here -->
                                </div>
                            </div>
                        </div>
                        
                        <!-- Class Selection -->
                        <div>
                            <label for="class-filter" class="block text-sm font-medium text-gray-700">Filter by Class</label>
                            <select id="class-filter" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Classes</option>
                                <?php foreach ($classes ?? [] as $class): ?>
                                    <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <!-- Students List -->
                    <div id="students-container">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Students</h3>
                        <div id="students-list" class="border border-gray-300 rounded-md max-h-60 overflow-y-auto">
                            <!-- Students will be populated here -->
                        </div>
                    </div>
                    
                    <!-- Selected Student Info (New Section) -->
                    <div id="selected-student-info" class="hidden bg-blue-50 border border-blue-200 rounded-md p-4 mt-4">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div>
                                    <h4 class="text-lg font-medium text-gray-900" id="selected-student-name"></h4>
                                    <p class="text-sm text-gray-500">Admission No: <span id="selected-student-admission"></span></p>
                                    <p class="text-sm text-gray-500">Class: <span id="selected-student-class"></span></p>
                                </div>
                            </div>
                            <button type="button" id="clear-student-selection" class="text-sm text-red-600 hover:text-red-900">
                                Clear
                            </button>
                        </div>
                    </div>
                    
                    <!-- Payment Structures Section -->
                    <div id="payment-structures-section" class="hidden">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Payment Structures</h3>
                        <div class="border border-gray-300 rounded-md">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Structure</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Balance</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Pay Amount</th>
                                    </tr>
                                </thead>
                                <tbody id="payment-structures-table" class="bg-white divide-y divide-gray-200">
                                    <!-- Payment structures will be populated here -->
                                </tbody>
                                <!-- Totals Row -->
                                <tfoot id="payment-structures-totals" class="bg-gray-50 font-medium">
                                    <tr>
                                        <td class="px-6 py-3 text-left text-sm font-medium text-gray-900">Totals</td>
                                        <td id="total-amount" class="px-6 py-3 text-left text-sm text-gray-900">₵0.00</td>
                                        <td id="total-balance" class="px-6 py-3 text-left text-sm text-gray-900">₵0.00</td>
                                        <td id="total-pay-amount" class="px-6 py-3 text-left text-sm text-gray-900">₵0.00</td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </div>
                    
                    <!-- Payment Details -->
                    <div id="payment-details" class="hidden">
                        <h3 class="text-lg font-medium text-gray-900 mb-2">Payment Details</h3>
                        <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                            <div>
                                <label for="method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                                <select id="method" name="method" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select Payment Method</option>
                                    <option value="cash">Cash</option>
                                    <option value="mobile_money">Mobile Money</option>
                                    <option value="bank_transfer">Bank Transfer</option>
                                    <option value="cheque">Cheque</option>
                                </select>
                            </div>
                            
                            <div>
                                <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                                <input type="date" id="date" name="date" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>
                        
                        <!-- Payment Method Specific Fields -->
                        <div id="payment-method-fields" class="mt-4 hidden">
                            <!-- Cash Fields -->
                            <div id="cash-fields" class="hidden">
                                <h4 class="text-md font-medium text-gray-900 mb-2">Cash Payment Details</h4>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="cash_payer_name" class="block text-sm font-medium text-gray-700">Payer Name</label>
                                        <input type="text" id="cash_payer_name" name="cash_payer_name"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="cash_payer_phone" class="block text-sm font-medium text-gray-700">Payer Phone</label>
                                        <input type="text" id="cash_payer_phone" name="cash_payer_phone"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Mobile Money Fields -->
                            <div id="mobile_money-fields" class="hidden">
                                <h4 class="text-md font-medium text-gray-900 mb-2">Mobile Money Details</h4>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="mobile_money_sender_name" class="block text-sm font-medium text-gray-700">Sender Name</label>
                                        <input type="text" id="mobile_money_sender_name" name="mobile_money_sender_name"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="mobile_money_sender_number" class="block text-sm font-medium text-gray-700">Sender Number</label>
                                        <input type="text" id="mobile_money_sender_number" name="mobile_money_sender_number"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="mobile_money_reference" class="block text-sm font-medium text-gray-700">Reference</label>
                                        <input type="text" id="mobile_money_reference" name="mobile_money_reference" required
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Bank Transfer Fields -->
                            <div id="bank_transfer-fields" class="hidden">
                                <h4 class="text-md font-medium text-gray-900 mb-2">Bank Transfer Details</h4>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="bank_transfer_sender_bank" class="block text-sm font-medium text-gray-700">Sender Bank</label>
                                        <input type="text" id="bank_transfer_sender_bank" name="bank_transfer_sender_bank"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="bank_transfer_invoice_number" class="block text-sm font-medium text-gray-700">Invoice Number</label>
                                        <input type="text" id="bank_transfer_invoice_number" name="bank_transfer_invoice_number"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="bank_transfer_details" class="block text-sm font-medium text-gray-700">Details</label>
                                        <textarea id="bank_transfer_details" name="bank_transfer_details" rows="2"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Cheque Fields -->
                            <div id="cheque-fields" class="hidden">
                                <h4 class="text-md font-medium text-gray-900 mb-2">Cheque Details</h4>
                                <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                                    <div>
                                        <label for="cheque_bank" class="block text-sm font-medium text-gray-700">Bank</label>
                                        <input type="text" id="cheque_bank" name="cheque_bank"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div>
                                        <label for="cheque_number" class="block text-sm font-medium text-gray-700">Cheque Number</label>
                                        <input type="text" id="cheque_number" name="cheque_number"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    </div>
                                    <div class="sm:col-span-2">
                                        <label for="cheque_details" class="block text-sm font-medium text-gray-700">Details</label>
                                        <textarea id="cheque_details" name="cheque_details" rows="2"
                                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mt-4">
                            <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea id="remarks" name="remarks" rows="2"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>
                        
                        <div id="payment-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline" id="payment-error-message"></span>
                        </div>
                                
                        <div id="payment-success" class="hidden bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                            <span class="block sm:inline" id="payment-success-message"></span>
                        </div>
                                
                        <div class="flex justify-end mt-4">
                            <button type="button" id="make-payment-btn" 
                                class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 hidden">
                                Make Payment
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
        
        <!-- Receipt Preview Modal -->
        <div id="receipt-preview-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white max-h-screen overflow-y-auto">
                <div class="mt-3">
                    <div class="flex justify-between items-center mb-4">
                        <h3 class="text-lg font-medium text-gray-900">Payment Receipt Preview</h3>
                        <div class="flex items-center space-x-4">
                            <label class="inline-flex items-center text-sm text-gray-600" id="show-preview-logo-container" style="display: none;">
                                <input type="checkbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" id="show-preview-logo-checkbox">
                                <span class="ml-2">Show School Logo</span>
                            </label>
                            <button id="close-receipt-preview-btn" class="text-gray-400 hover:text-gray-500">
                                <span class="text-2xl">&times;</span>
                            </button>
                        </div>
                    </div>
                    
                    <div id="receipt-preview-content" class="mt-4">
                        <!-- Receipt preview content will be populated here -->
                    </div>
                    
                    <div class="mt-6 flex justify-end space-x-3">
                        <button id="close-receipt-preview-btn-2" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Close
                        </button>
                        <button id="reorganise-btn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                <path fill-rule="evenodd" d="M4 4a2 2 0 012-2h4.586A2 2 0 0112 2.586L15.414 6A2 2 0 0116 7.414V16a2 2 0 01-2 2H6a2 2 0 01-2-2V4zm2 6a1 1 0 011-1h6a1 1 0 110 2H7a1 1 0 01-1-1zm1 3a1 1 0 100 2h6a1 1 0 100-2H7z" clip-rule="evenodd" />
                            </svg>
                            ReOrganise
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
        
        <!-- View Fee Structure Modal -->
        <div id="view-fee-structure-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
            <div class="relative top-10 mx-auto p-5 border w-11/12 md:w-3/4 max-w-4xl shadow-lg rounded-md bg-white max-h-[90vh] flex flex-col">
                <div class="flex justify-between items-center mb-4 flex-shrink-0">
                    <h3 class="text-xl font-medium text-gray-900">Fee Structure Details</h3>
                    <div class="flex items-center gap-2">
                        <?php if (isset($isSuperAdmin) && $isSuperAdmin): ?>
                        <a id="modal-fee-edit-link" href="#" class="inline-flex items-center px-3 py-1 border border-transparent text-sm leading-4 font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                            Edit
                        </a>
                        <?php endif; ?>
                        <button onclick="document.getElementById('view-fee-structure-modal').classList.add('hidden')" class="text-gray-400 hover:text-gray-500">
                            <span class="text-2xl">&times;</span>
                        </button>
                    </div>
                </div>
                
                <div id="view-fee-structure-content" class="mt-2 overflow-y-auto flex-1">
                    <div class="animate-pulse flex space-x-4">
                        <div class="flex-1 space-y-4 py-1">
                            <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                            <div class="space-y-2">
                                <div class="h-4 bg-gray-200 rounded"></div>
                                <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                            </div>
                        </div>
                    </div>
                </div>
                
                <div class="mt-6 flex justify-end gap-3 flex-shrink-0">
                    <button id="print-fee-structure-btn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                            <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                        </svg>
                        Print Details
                    </button>
                    <button onclick="document.getElementById('view-fee-structure-modal').classList.add('hidden')" class="bg-white border border-gray-300 rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-gray-700 hover:bg-gray-50">
                        Close
                    </button>
                </div>
            </div>
        </div>
        
        <!-- Payments Tab -->
        <?php if ($activeTab === 'payments'): ?>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Filter Section -->
            <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                <form id="payments-filter-form" method="GET" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-5">
                    <input type="hidden" name="tab" value="payments">
                    
                    <!-- Search Input -->
                    <div>
                        <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                        <input type="text" name="search" id="search" 
                               value="<?= htmlspecialchars($_GET['search'] ?? $searchTerm ?? '') ?>" 
                               placeholder="Name or Admission No" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    
                    <!-- Academic Year Filter -->
                    <div>
                        <label for="academic_year_id" class="block text-sm font-medium text-gray-700">Academic Year</label>
                        <select name="academic_year_id" id="academic_year_id" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Academic Years</option>
                            <?php foreach ($academicYears ?? [] as $year): ?>
                                <option value="<?= $year['id'] ?>" <?= (isset($_GET['academic_year_id']) && $_GET['academic_year_id'] == $year['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($year['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <!-- Date Range Checkbox -->
                    <div class="flex items-center mt-6">
                        <input id="enable_date_filter" name="enable_date_filter" type="checkbox" 
                               <?= (isset($_GET['enable_date_filter']) && $_GET['enable_date_filter']) ? 'checked' : '' ?>
                               class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="enable_date_filter" class="ml-2 block text-sm text-gray-900">
                            Filter by Date Range
                        </label>
                    </div>
                    
                    <!-- Date Range Fields (Initially Hidden) -->
                    <div id="date-range-fields" class="sm:col-span-2 <?= (isset($_GET['enable_date_filter']) && $_GET['enable_date_filter']) ? '' : 'hidden' ?>">
                        <div class="grid grid-cols-1 gap-4 sm:grid-cols-2">
                            <div>
                                <label for="date_from" class="block text-sm font-medium text-gray-700">From</label>
                                <input type="date" name="date_from" id="date_from" 
                                       value="<?= htmlspecialchars($_GET['date_from'] ?? $dateFrom ?? '') ?>" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                            <div>
                                <label for="date_to" class="block text-sm font-medium text-gray-700">To</label>
                                <input type="date" name="date_to" id="date_to" 
                                       value="<?= htmlspecialchars($_GET['date_to'] ?? $dateTo ?? '') ?>" 
                                       class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        </div>
                    </div>
                    
                    <!-- Submit Button -->
                    <div class="flex items-end">
                        <button type="submit" 
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Filter
                        </button>
                    </div>
                </form>
            </div>
            
            <!-- Payments Table -->
            <div class="overflow-x-auto">
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
                                Academic Year
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
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <div class="font-medium text-gray-900"><?= htmlspecialchars($payment['first_name'] ?? '') ?> <?= htmlspecialchars($payment['last_name'] ?? '') ?></div>
                                        <div class="text-gray-500 text-xs">Admission: <?= htmlspecialchars($payment['admission_no'] ?? '') ?></div>
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
                                        <?= htmlspecialchars($payment['fee_name'] ?? 'N/A') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($payment['academic_year_name'] ?? 'N/A') ?>
                                        <?php if (!empty($payment['term'])): ?>
                                            <div class="text-xs text-gray-400"><?= htmlspecialchars($payment['term']) ?></div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                                        <?= htmlspecialchars($payment['remarks'] ?? '') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                        <button class="profile-financial-btn text-indigo-600 hover:text-indigo-900 mr-3" data-student-id="<?= $payment['student_id'] ?>" onclick="openStudentFinancialModal(<?= $payment['student_id'] ?>)">
                                            Profile
                                        </button>
                                        <button class="view-payment-btn text-indigo-600 hover:text-indigo-900 mr-3" data-payment-id="<?= $payment['id'] ?>">
                                            View
                                        </button>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
            <div class="px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?php if ($pagination['current_page'] > 1): ?>
                    <a href="<?= buildPaginationUrl($pagination['current_page'] - 1, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false) ?>" 
                       class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <a href="<?= buildPaginationUrl($pagination['current_page'] + 1, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false) ?>" 
                       class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </a>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium"><?= (($pagination['current_page'] - 1) * $pagination['per_page'] + 1) ?></span>
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
                            <a href="<?= buildPaginationUrl(1, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false) ?>" 
                               class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span>First</span>
                            </a>
                            <a href="<?= buildPaginationUrl($pagination['current_page'] - 1, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false) ?>" 
                               class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span>Previous</span>
                            </a>
                            <?php endif; ?>
                            
                            <?php 
                            // Show page numbers
                            $startPage = max(1, $pagination['current_page'] - 2);
                            $endPage = min($pagination['total_pages'], $pagination['current_page'] + 2);
                            
                            for ($i = $startPage; $i <= $endPage; $i++): 
                            ?>
                            <a href="<?= buildPaginationUrl($i, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false) ?>" 
                               class="<?= $i == $pagination['current_page'] ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' ?> relative inline-flex items-center px-4 py-2 border text-sm font-medium">
                                <?= $i ?>
                            </a>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <a href="<?= buildPaginationUrl($pagination['current_page'] + 1, $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false) ?>" 
                               class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span>Next</span>
                            </a>
                            <a href="<?= buildPaginationUrl($pagination['total_pages'], $perPage ?? 10, $searchTerm ?? '', $academicYearId ?? '', $dateFrom ?? '', $dateTo ?? '', $enableDateFilter ?? false) ?>" 
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
        <?php endif; ?>
    </div>
</div>

<script>
// Handle date range checkbox
if (document.getElementById('enable_date_filter')) {
    document.getElementById('enable_date_filter').addEventListener('change', function() {
        const dateRangeFields = document.getElementById('date-range-fields');
        if (this.checked) {
            dateRangeFields.classList.remove('hidden');
        } else {
            dateRangeFields.classList.add('hidden');
        }
    });
}

// Handle View Payment button clicks for payments tab
document.addEventListener('click', function(e) {
    if (e.target.classList.contains('view-payment-btn') || e.target.closest('.view-payment-btn')) {
        const button = e.target.classList.contains('view-payment-btn') ? e.target : e.target.closest('.view-payment-btn');
        const paymentId = button.getAttribute('data-payment-id');
        
        // Show loading state
        showViewPaymentModal('Loading payment details...');
        
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
                
                const paymentContent = `
                    <div class="space-y-4">
                        <div class="bg-gray-50 p-4 rounded-lg">
                            <h4 class="text-lg font-medium text-gray-900">${student.first_name} ${student.last_name}</h4>
                            <p class="text-gray-600">Admission No: ${student.admission_no}</p>
                            ${student.class_name ? `<p class="text-gray-600">Class: ${student.class_name}</p>` : ''}
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
                        
                        <!-- Payment Method Specific Details -->
                        ${payment.method === 'cash' ? `
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="text-md font-medium text-gray-900 mb-2">Cash Payment Details</h4>
                                ${payment.cash_payer_name ? `<p class="text-sm"><span class="font-medium">Payer Name:</span> ${payment.cash_payer_name}</p>` : ''}
                                ${payment.cash_payer_phone ? `<p class="text-sm"><span class="font-medium">Payer Phone:</span> ${payment.cash_payer_phone}</p>` : ''}
                            </div>
                        ` : ''}
                        
                        ${payment.method === 'mobile_money' ? `
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="text-md font-medium text-gray-900 mb-2">Mobile Money Details</h4>
                                ${payment.mobile_money_sender_name ? `<p class="text-sm"><span class="font-medium">Sender Name:</span> ${payment.mobile_money_sender_name}</p>` : ''}
                                ${payment.mobile_money_sender_number ? `<p class="text-sm"><span class="font-medium">Sender Number:</span> ${payment.mobile_money_sender_number}</p>` : ''}
                                ${payment.mobile_money_reference ? `<p class="text-sm"><span class="font-medium">Reference:</span> ${payment.mobile_money_reference}</p>` : ''}
                            </div>
                        ` : ''}
                        
                        ${payment.method === 'bank_transfer' ? `
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="text-md font-medium text-gray-900 mb-2">Bank Transfer Details</h4>
                                ${payment.bank_transfer_sender_bank ? `<p class="text-sm"><span class="font-medium">Sender Bank:</span> ${payment.bank_transfer_sender_bank}</p>` : ''}
                                ${payment.bank_transfer_invoice_number ? `<p class="text-sm"><span class="font-medium">Invoice Number:</span> ${payment.bank_transfer_invoice_number}</p>` : ''}
                                ${payment.bank_transfer_details ? `<p class="text-sm"><span class="font-medium">Details:</span> ${payment.bank_transfer_details}</p>` : ''}
                            </div>
                        ` : ''}
                        
                        ${payment.method === 'cheque' ? `
                            <div class="mt-4 pt-4 border-t border-gray-200">
                                <h4 class="text-md font-medium text-gray-900 mb-2">Cheque Details</h4>
                                ${payment.cheque_bank ? `<p class="text-sm"><span class="font-medium">Bank:</span> ${payment.cheque_bank}</p>` : ''}
                                ${payment.cheque_number ? `<p class="text-sm"><span class="font-medium">Cheque Number:</span> ${payment.cheque_number}</p>` : ''}
                                ${payment.cheque_details ? `<p class="text-sm"><span class="font-medium">Details:</span> ${payment.cheque_details}</p>` : ''}
                            </div>
                        ` : ''}
                    </div>
                `;
                
                showViewPaymentModal(paymentContent);
            } else {
                showViewPaymentModal('<p class="text-red-500">Failed to load payment details.</p>');
            }
        })
        .catch(error => {
            showViewPaymentModal('<p class="text-red-500">Error loading payment details.</p>');
        });
    }
});

// Function to show the view payment modal
function showViewPaymentModal(content) {
    // Create modal if it doesn't exist
    let modal = document.getElementById('view-payment-modal');
    if (!modal) {
        modal = document.createElement('div');
        modal.id = 'view-payment-modal';
        modal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50';
        modal.innerHTML = 
            '<div class="relative top-20 mx-auto p-5 border w-11/12 md:w-1/2 shadow-lg rounded-md bg-white">' +
            '    <div class="mt-3">' +
            '        <div class="flex justify-between items-center mb-4">' +
            '            <h3 class="text-lg font-medium text-gray-900">Payment Details</h3>' +
            '            <button id="close-view-modal-btn" class="text-gray-400 hover:text-gray-500">' +
            '                <span class="text-2xl">&times;</span>' +
            '            </button>' +
            '        </div>' +
            '        ' +
            '        <div id="view-payment-content" class="mt-4">' +
            '            ' + content +
            '        </div>' +
            '        ' +
            '        <div class="mt-6 flex justify-end">' +
            '            <button id="close-view-modal-btn-2" class="bg-gray-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">' +
            '                Close' +
            '            </button>' +
            '        </div>' +
            '    </div>' +
            '</div>';
        document.body.appendChild(modal);
        
        // Add event listeners for closing the modal
        modal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
        
        document.getElementById('close-view-modal-btn').addEventListener('click', function() {
            document.getElementById('view-payment-modal').classList.add('hidden');
        });
        
        document.getElementById('close-view-modal-btn-2').addEventListener('click', function() {
            document.getElementById('view-payment-modal').classList.add('hidden');
        });
    } else {
        // Update content
        document.getElementById('view-payment-content').innerHTML = content;
    }
    
    // Show modal
    modal.classList.remove('hidden');
}

// Pay Fees Tab Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Set default date to today
    const today = new Date().toISOString().split('T')[0];
    const dateInput = document.getElementById('date');
    if (dateInput) {
        dateInput.value = today;
    }
    
    // Student search functionality
    const studentSearch = document.getElementById('student-search');
    const studentSuggestions = document.getElementById('student-suggestions');
    const classFilter = document.getElementById('class-filter');
    const studentsList = document.getElementById('students-list');
    const paymentStructuresSection = document.getElementById('payment-structures-section');
    const paymentDetails = document.getElementById('payment-details');
    const makePaymentBtn = document.getElementById('make-payment-btn');
    
    let allStudents = [];
    let filteredStudents = [];
    
    // School settings
    window.schoolSettings = <?php echo json_encode($schoolSettings ?? []); ?>;
    let selectedStudent = null;
    
    // Load all students on page load
    loadAllStudents();
    
    // Load students based on search and class filter
    function loadStudents() {
        const searchTerm = studentSearch.value.toLowerCase();
        const classId = classFilter.value;
        
        filteredStudents = allStudents.filter(student => {
            const matchesSearch = !searchTerm || 
                student.first_name.toLowerCase().includes(searchTerm) || 
                student.last_name.toLowerCase().includes(searchTerm) || 
                student.admission_no.toLowerCase().includes(searchTerm);
            const matchesClass = !classId || student.class_id == classId;
            return matchesSearch && matchesClass;
        });
        
        renderStudentsList();
    }
    
    // Render students list
    function renderStudentsList() {
        studentsList.innerHTML = '';
        
        if (filteredStudents.length === 0) {
            studentsList.innerHTML = '<div class="p-4 text-center text-gray-500">No students found</div>';
            return;
        }
        
        filteredStudents.forEach(student => {
            const studentDiv = document.createElement('div');
            studentDiv.className = 'flex items-center p-3 border-b border-gray-200 cursor-pointer hover:bg-gray-50';
            studentDiv.innerHTML = `
                <div class="flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900">${student.first_name} ${student.last_name}</p>
                    <p class="text-sm text-gray-500 truncate">Admission No: ${student.admission_no}</p>
                    <p class="text-xs text-gray-400">Class: ${student.class_name || 'N/A'}</p>
                </div>
            `;
            
            studentDiv.addEventListener('click', function(event) {
                selectStudent(student, event);
            });
            studentsList.appendChild(studentDiv);
        });
    }
    
    // Select a student
    function selectStudent(student, event) {
        selectedStudent = student;
        
        // Highlight selected student
        document.querySelectorAll('#students-list > div').forEach(div => {
            div.classList.remove('bg-blue-50');
        });
        
        // Add highlighting to the selected student
        if (event && event.currentTarget) {
            event.currentTarget.classList.add('bg-blue-50');
        }
        
        // Show selected student info
        document.getElementById('selected-student-name').textContent = student.first_name + ' ' + student.last_name;
        document.getElementById('selected-student-admission').textContent = student.admission_no;
        document.getElementById('selected-student-class').textContent = student.class_name || 'N/A';
        document.getElementById('selected-student-info').classList.remove('hidden');
        
        // Load payment structures for the selected student
        loadPaymentStructures(student.id);
    }
    
    // Load payment structures for a student
    function loadPaymentStructures(studentId) {
        // Show loading state
        paymentStructuresSection.classList.remove('hidden');
        document.getElementById('payment-structures-table').innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center">Loading payment structures...</td></tr>';
        
        // Fetch student fee allocations
        fetch(`/payments/student-fees/${studentId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.fee_allocations) {
                renderPaymentStructures(data.fee_allocations);
            } else {
                document.getElementById('payment-structures-table').innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-red-500">Failed to load payment structures</td></tr>';
            }
        })
        .catch(error => {
            console.error('Error:', error);
            document.getElementById('payment-structures-table').innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center text-red-500">Error loading payment structures</td></tr>';
        });
    }
    
    // Render payment structures table
    function renderPaymentStructures(feeAllocations) {
        const tableBody = document.getElementById('payment-structures-table');
        tableBody.innerHTML = '';
        
        // Initialize totals
        let totalAmount = 0;
        let totalBalance = 0;
        let totalPayAmount = 0;
        
        if (feeAllocations.length === 0) {
            tableBody.innerHTML = '<tr><td colspan="4" class="px-6 py-4 text-center">No payment structures assigned to this student</td></tr>';
            // Hide selected student info if no payment structures
            // document.getElementById('selected-student-info').classList.add('hidden');
            return;
        }
        
        feeAllocations.forEach(allocation => {
            const balance = allocation.amount - allocation.paid_amount;
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    ${allocation.fee_name}
                    <input type="hidden" name="fee_id" value="${allocation.fee_id}">
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 amount-cell" data-amount="${allocation.amount}">
                    ₵${parseFloat(allocation.amount).toFixed(2)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 balance-cell" data-balance="${balance}">
                    <span class="text-gray-400">₵${parseFloat(balance).toFixed(2)}</span>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    <input type="number" name="pay_amount" 
                           class="pay-amount-input border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                           min="0" max="${balance}" step="0.01" 
                           value="0"
                           data-max="${balance}">
                </td>
            `;
            tableBody.appendChild(row);
            
            // Add to totals
            totalAmount += parseFloat(allocation.amount);
            totalBalance += balance;
        });
        
        // Update totals display
        document.getElementById('total-amount').textContent = '₵' + totalAmount.toFixed(2);
        document.getElementById('total-balance').textContent = '₵' + totalBalance.toFixed(2);
        document.getElementById('total-pay-amount').textContent = '₵' + totalPayAmount.toFixed(2);
        
        // Show payment details and make payment button
        paymentDetails.classList.remove('hidden');
        makePaymentBtn.classList.remove('hidden');
        
        // Add event listeners to pay amount inputs to update total pay amount
        document.querySelectorAll('.pay-amount-input').forEach(input => {
            // Add input event listener
            input.addEventListener('input', updateTotalPayAmount);
            
            // Add focus event to remove any default styling if needed
            input.addEventListener('focus', function() {
                this.classList.remove('text-gray-400');
            });
        });
    }
    
    // Previous duplicate updateTotalPayAmount removed.
    
    // Load all students
    function loadAllStudents() {
        fetch('/students/all-with-fees', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.students) {
                allStudents = data.students;
                filteredStudents = [...allStudents];
                renderStudentsList();
            }
        })
        .catch(error => {
            console.error('Error loading students:', error);
            studentsList.innerHTML = '<div class="p-4 text-center text-red-500">Error loading students</div>';
        });
    }
    
    // Clear student selection
    function clearStudentSelection() {
        selectedStudent = null;
        
        // Remove highlighting from all students
        document.querySelectorAll('#students-list > div').forEach(div => {
            div.classList.remove('bg-blue-50');
        });
        
        // Hide selected student info
        document.getElementById('selected-student-info').classList.add('hidden');
        
        // Hide payment sections
        paymentStructuresSection.classList.add('hidden');
        paymentDetails.classList.add('hidden');
        makePaymentBtn.classList.add('hidden');
        
        // Reset form
        document.getElementById('method').value = '';
        document.getElementById('remarks').value = '';
        
        // Clear payment method specific fields
        document.querySelectorAll('#payment-method-fields input').forEach(input => {
            input.value = '';
        });
        
        // Hide payment method fields
        document.getElementById('payment-method-fields').classList.add('hidden');
        document.getElementById('cash-fields').classList.add('hidden');
        document.getElementById('mobile_money-fields').classList.add('hidden');
        document.getElementById('bank_transfer-fields').classList.add('hidden');
        document.getElementById('cheque-fields').classList.add('hidden');
        
        // Reset date to today
        const today = new Date().toISOString().split('T')[0];
        const dateInput = document.getElementById('date');
        if (dateInput) {
            dateInput.value = today;
        }
    }
    
    // Event listeners
    if (studentSearch) {
        studentSearch.addEventListener('input', loadStudents);
    }
    
    if (classFilter) {
        classFilter.addEventListener('change', loadStudents);
    }
    
    // Add event listener for clear student selection button
    if (document.getElementById('clear-student-selection')) {
        document.getElementById('clear-student-selection').addEventListener('click', clearStudentSelection);
    }
    
    // Payment method change handler
    if (document.getElementById('method')) {
        document.getElementById('method').addEventListener('change', function() {
            const selectedMethod = this.value;
            const paymentMethodFields = document.getElementById('payment-method-fields');
            
            // Hide all method-specific fields
            document.getElementById('cash-fields').classList.add('hidden');
            document.getElementById('mobile_money-fields').classList.add('hidden');
            document.getElementById('bank_transfer-fields').classList.add('hidden');
            document.getElementById('cheque-fields').classList.add('hidden');
            
            // Show the selected method fields
            if (selectedMethod && document.getElementById(selectedMethod + '-fields')) {
                paymentMethodFields.classList.remove('hidden');
                document.getElementById(selectedMethod + '-fields').classList.remove('hidden');
                
                // Set default reference for mobile money to student name (admission number)
                if (selectedMethod === 'mobile_money' && selectedStudent) {
                    const referenceField = document.getElementById('mobile_money_reference');
                    if (referenceField && !referenceField.value) {
                        referenceField.value = selectedStudent.first_name + ' ' + selectedStudent.last_name + ' (' + selectedStudent.admission_no + ')';
                    }
                }
            } else {
                paymentMethodFields.classList.add('hidden');
            }
        });
    }
    
    // Make Payment button click handler
    if (makePaymentBtn) {
        makePaymentBtn.addEventListener('click', function() {
            if (!selectedStudent) {
                showError('Please select a student first');
                return;
            }
            
            const method = document.getElementById('method').value;
            const date = document.getElementById('date').value;
            const remarks = document.getElementById('remarks').value;
            
            if (!method || !date) {
                showError('Please fill in all required payment details');
                return;
            }
            
            // Validate payment method-specific required fields
            if (method === 'mobile_money') {
                const reference = document.getElementById('mobile_money_reference').value;
                if (!reference) {
                    showError('Mobile money reference is required');
                    return;
                }
            }
            
            // Get payment amounts for each fee structure
            const paymentRows = document.querySelectorAll('#payment-structures-table tr');
            const payments = [];
            
            console.log('Number of payment rows found:', paymentRows.length);
            
            paymentRows.forEach((row, index) => {
                const feeIdInput = row.querySelector('input[name="fee_id"]');
                const amountInput = row.querySelector('input[name="pay_amount"]');
                
                console.log(`Row ${index}: feeIdInput=`, feeIdInput, 'amountInput=', amountInput);
                
                if (feeIdInput && amountInput) {
                    const amount = parseFloat(amountInput.value);
                    console.log(`Row ${index}: amount=`, amount);
                    
                    // Only include payments with amount > 0
                    if (amount > 0) {
                        // Validate that fee_id is not empty
                        const feeId = feeIdInput.value;
                        console.log(`Row ${index}: feeId=`, feeId);
                        
                        if (feeId && feeId.trim() !== '') {
                            payments.push({
                                fee_id: feeId.trim(),
                                amount: amount.toFixed(2) // Ensure consistent formatting
                            });
                            console.log(`Row ${index}: Added payment`, {fee_id: feeId.trim(), amount: amount.toFixed(2)});
                        } else {
                            console.error('Missing fee_id for row', index, 'feeIdInput:', feeIdInput);
                        }
                    } else {
                        console.log(`Row ${index}: Amount is 0 or invalid, skipping`);
                    }
                } else {
                    console.error('Missing inputs for row', index, 'feeIdInput:', feeIdInput, 'amountInput:', amountInput);
                }
            });
            
            // Debug: Log collected payments
            console.log('Collected payments:', payments);
            console.log('Number of payments:', payments.length);
            
            // Log each payment individually for debugging
            payments.forEach((payment, index) => {
                console.log(`Payment ${index}: fee_id=${payment.fee_id}, amount=${payment.amount}`);
            });
            
            if (payments.length === 0) {
                showError('Please enter at least one payment amount greater than zero');
                return;
            }
            
            // Debug: Log payments to console
            console.log('Payments to be sent:', payments);
            
            // Show loading state
            this.textContent = 'Processing...';
            this.disabled = true;
            
            // Prepare form data
            const formData = new FormData();
            formData.append('student_id', selectedStudent.id);
            formData.append('method', method);
            formData.append('date', date);
            formData.append('remarks', remarks);
            
            // Add payment method-specific fields
            if (method === 'cash') {
                const payerName = document.getElementById('cash_payer_name').value;
                const payerPhone = document.getElementById('cash_payer_phone').value;
                if (payerName) formData.append('cash_payer_name', payerName);
                if (payerPhone) formData.append('cash_payer_phone', payerPhone);
            } else if (method === 'mobile_money') {
                const senderName = document.getElementById('mobile_money_sender_name').value;
                const senderNumber = document.getElementById('mobile_money_sender_number').value;
                const reference = document.getElementById('mobile_money_reference').value;
                if (senderName) formData.append('mobile_money_sender_name', senderName);
                if (senderNumber) formData.append('mobile_money_sender_number', senderNumber);
                if (reference) formData.append('mobile_money_reference', reference);
            } else if (method === 'bank_transfer') {
                const senderBank = document.getElementById('bank_transfer_sender_bank').value;
                const invoiceNumber = document.getElementById('bank_transfer_invoice_number').value;
                const details = document.getElementById('bank_transfer_details').value;
                if (senderBank) formData.append('bank_transfer_sender_bank', senderBank);
                if (invoiceNumber) formData.append('bank_transfer_invoice_number', invoiceNumber);
                if (details) formData.append('bank_transfer_details', details);
            } else if (method === 'cheque') {
                const bank = document.getElementById('cheque_bank').value;
                const chequeNumber = document.getElementById('cheque_number').value;
                const details = document.getElementById('cheque_details').value;
                if (bank) formData.append('cheque_bank', bank);
                if (chequeNumber) formData.append('cheque_number', chequeNumber);
                if (details) formData.append('cheque_details', details);
            }
            
            // Add payments to form data
            payments.forEach((payment, index) => {
                formData.append(`payments[${index}][fee_id]`, payment.fee_id);
                formData.append(`payments[${index}][amount]`, payment.amount);
            });
            
            // Debug: Log form data
            console.log('Form data entries:');
            const formDataEntries = [];
            for (let pair of formData.entries()) {
                console.log(pair[0] + ': ' + pair[1]);
                formDataEntries.push({key: pair[0], value: pair[1]});
            }
            console.log('All form data entries:', formDataEntries);
            
            // Also log the raw FormData keys to see the structure
            console.log('FormData keys:');
            for (let key of formData.keys()) {
                console.log('Key:', key);
            }
            
            // Submit payment
            fetch('/payments', {
                method: 'POST',
                body: formData,
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => {
                // Check if response is OK (2xx status)
                if (!response.ok) {
                    throw new Error(`HTTP error! status: ${response.status}`);
                }
                
                // Check content type
                const contentType = response.headers.get('content-type');
                if (!contentType || !contentType.includes('application/json')) {
                    // If not JSON, read as text to see what was returned
                    return response.text().then(text => {
                        console.error('Non-JSON response received:', text);
                        throw new Error('Server returned non-JSON response: ' + text.substring(0, 200) + '...');
                    });
                }
                
                return response.json();
            })
            .then(data => {
                // Reset button
                this.textContent = 'Make Payment';
                this.disabled = false;
                
                if (data.success) {
                    showSuccess(data.message || 'Payment recorded successfully');
                    
                    // Show receipt preview
                    if (data.payment_ids && data.payment_ids.length > 0) {
                        showReceiptPreview(data.payment_ids);
                    } else if (data.payment_id) {
                        // Fallback for single payment
                        showReceiptPreview([data.payment_id]);
                    }
                    
                    // Reset form fields but keep student selection
                    document.getElementById('method').value = '';
                    document.getElementById('remarks').value = '';
                    
                    // Clear payment method specific fields
                    document.querySelectorAll('#payment-method-fields input').forEach(input => {
                        input.value = '';
                    });
                    
                    // Hide payment method fields
                    document.getElementById('payment-method-fields').classList.add('hidden');
                    document.getElementById('cash-fields').classList.add('hidden');
                    document.getElementById('mobile_money-fields').classList.add('hidden');
                    document.getElementById('bank_transfer-fields').classList.add('hidden');
                    document.getElementById('cheque-fields').classList.add('hidden');
                    
                    // Reset date to today
                    const today = new Date().toISOString().split('T')[0];
                    const dateInput = document.getElementById('date');
                    if (dateInput) {
                        dateInput.value = today;
                    }
                    
                    // Reload payment structures for the student to reflect updated balances
                    loadPaymentStructures(selectedStudent.id);
                } else {
                    showError(data.message || 'Failed to record payment');
                }
            })
            .catch(error => {
                console.error('Error:', error);
                this.textContent = 'Make Payment';
                this.disabled = false;
                showError('An error occurred while recording the payment. Please try again. Error: ' + error.message);
            });
        });
    }
    
    // Update the updateTotalPayAmount function to handle the "grayed out" issue
    function updateTotalPayAmount() {
        let totalPayAmount = 0;
        document.querySelectorAll('.pay-amount-input').forEach(input => {
            let value = parseFloat(input.value) || 0;
            // Ensure value doesn't exceed balance
            const max = parseFloat(input.dataset.max) || 0;
            if (value > max) {
                input.value = max;
                value = max;
            }
            totalPayAmount += value;
        });
        document.getElementById('total-pay-amount').textContent = '₵' + totalPayAmount.toFixed(2);
        
        // Enable the make payment button if there's at least one payment > 0
        const hasPayments = Array.from(document.querySelectorAll('.pay-amount-input'))
            .some(input => parseFloat(input.value) > 0);
        
        if (hasPayments && makePaymentBtn) {
            makePaymentBtn.classList.remove('hidden');
        }
    }
    
    // Function to show receipt preview for single or multiple payments
    function showReceiptPreview(paymentIds) {
        // Handle single payment ID as well
        if (!Array.isArray(paymentIds)) {
            paymentIds = [paymentIds];
        }
        
        // Fetch payment details for all payments
        const fetchPromises = paymentIds.map(paymentId => 
            fetch(`/payments/${paymentId}`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            }).then(response => response.json())
        );
        
        // First fetch the latest school settings
        fetch('/settings/school-name', {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(schoolData => {
            // Then fetch payment details
            return Promise.all(fetchPromises)
            .then(results => {
            // Filter successful results
            const successfulResults = results.filter(result => result.success && result.payment && result.student);
            
            if (successfulResults.length > 0) {
                // Use the first student data (assuming all payments are for the same student)
                const firstResult = successfulResults[0];
                const student = firstResult.student;
                
                // Collect all payments and include fee names
                const payments = successfulResults.map(result => {
                    const payment = {...result.payment};
                    if (result.fee && result.fee.name) {
                        payment.fee_name = result.fee.name;
                    }
                    return payment;
                });
                
                // Format payment method (use the method from the first payment)
                const firstPayment = payments[0];
                let methodLabel = firstPayment.method.replace('_', ' ');
                methodLabel = methodLabel.charAt(0).toUpperCase() + methodLabel.slice(1);
                
                // Get payment method specific details (from the first payment)
                let methodDetails = '';
                if (firstPayment.method === 'cash' && (firstPayment.cash_payer_name || firstPayment.cash_payer_phone)) {
                    methodDetails = `
                        <div class="mt-2">
                            ${firstPayment.cash_payer_name ? `<p><span class="font-medium">Payer Name:</span> ${firstPayment.cash_payer_name}</p>` : ''}
                            ${firstPayment.cash_payer_phone ? `<p><span class="font-medium">Payer Phone:</span> ${firstPayment.cash_payer_phone}</p>` : ''}
                        </div>
                    `;
                } else if (firstPayment.method === 'mobile_money' && (firstPayment.mobile_money_sender_name || firstPayment.mobile_money_sender_number || firstPayment.mobile_money_reference)) {
                    methodDetails = `
                        <div class="mt-2">
                            ${firstPayment.mobile_money_sender_name ? `<p><span class="font-medium">Sender Name:</span> ${firstPayment.mobile_money_sender_name}</p>` : ''}
                            ${firstPayment.mobile_money_sender_number ? `<p><span class="font-medium">Sender Number:</span> ${firstPayment.mobile_money_sender_number}</p>` : ''}
                            ${firstPayment.mobile_money_reference ? `<p><span class="font-medium">Reference:</span> ${firstPayment.mobile_money_reference}</p>` : ''}
                        </div>
                    `;
                } else if (firstPayment.method === 'bank_transfer' && (firstPayment.bank_transfer_sender_bank || firstPayment.bank_transfer_invoice_number || firstPayment.bank_transfer_details)) {
                    methodDetails = `
                        <div class="mt-2">
                            ${firstPayment.bank_transfer_sender_bank ? `<p><span class="font-medium">Sender Bank:</span> ${firstPayment.bank_transfer_sender_bank}</p>` : ''}
                            ${firstPayment.bank_transfer_invoice_number ? `<p><span class="font-medium">Invoice Number:</span> ${firstPayment.bank_transfer_invoice_number}</p>` : ''}
                            ${firstPayment.bank_transfer_details ? `<p><span class="font-medium">Details:</span> ${firstPayment.bank_transfer_details}</p>` : ''}
                        </div>
                    `;
                } else if (firstPayment.method === 'cheque' && (firstPayment.cheque_bank || firstPayment.cheque_number || firstPayment.cheque_details)) {
                    methodDetails = `
                        <div class="mt-2">
                            ${firstPayment.cheque_bank ? `<p><span class="font-medium">Bank:</span> ${firstPayment.cheque_bank}</p>` : ''}
                            ${firstPayment.cheque_number ? `<p><span class="font-medium">Cheque Number:</span> ${firstPayment.cheque_number}</p>` : ''}
                            ${firstPayment.cheque_details ? `<p><span class="font-medium">Details:</span> ${firstPayment.cheque_details}</p>` : ''}
                        </div>
                    `;
                }
                
                // Calculate total amount
                const totalAmount = payments.reduce((sum, payment) => sum + parseFloat(payment.amount), 0);
                
                // Get school name and logo from the AJAX response
                let schoolName = schoolData.school_name || 'APSA-ERP';
                let schoolLogo = schoolData.school_logo || null;
                
                // Generate receipt HTML with all payments
                const receiptContent = `
                    <div class="max-w-2xl mx-auto bg-white p-8 border border-gray-300">
                        <div class="flex justify-between items-start mb-6">
                            ${schoolLogo ? `<img src="${schoolLogo}" alt="School Logo" class="h-16 w-auto" id="receipt-preview-logo" onerror="this.style.display='none'">` : ''}
                            <div class="text-center flex-1">
                                <h1 class="text-2xl font-bold text-gray-800">${schoolName}</h1>
                                <h2 class="text-2xl font-bold text-gray-800 mt-2">PAYMENT RECEIPT</h2>
                                <p class="text-gray-600 mt-2">Official Receipt for Payment</p>
                            </div>
                        </div>
                        
                        <div class="border-t border-b border-gray-300 py-4 mb-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Receipt No(s):</p>
                                    <p class="font-medium">${payments.map(p => '#' + p.id).join(', ')}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Date:</p>
                                    <p class="font-medium">${new Date(firstPayment.date).toLocaleDateString()}</p>
                                    ${firstPayment.academic_year_name || firstPayment.term ? `
                                    <p class="text-sm text-gray-600 mt-1">Academic Year: ${firstPayment.academic_year_name || 'N/A'}${firstPayment.term ? ` (${firstPayment.term})` : ''}</p>
                                    ` : ''}
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Student:</p>
                                    <p class="student-name font-bold">${student.first_name} ${student.last_name}</p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Admission No:</p>
                                    <p class="font-medium">${student.admission_no}</p>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-3">Payment Details</h3>
                            <div class="border border-gray-300 rounded-md">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            <th class="px-4 py-2 text-right text-sm font-medium text-gray-700">Amount (₵)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${payments.map(payment => `
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900">${payment.fee_name || 'Payment'}</td>
                                                <td class="px-4 py-2 text-sm text-gray-900 text-right">₵${parseFloat(payment.amount).toFixed(2)}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-t border-gray-300">
                                            <td class="px-4 py-2 text-sm font-medium text-gray-900">Total</td>
                                            <td class="px-4 py-2 text-sm font-medium text-gray-900 text-right">₵${totalAmount.toFixed(2)}</td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Payment Method:</p>
                                    <p class="font-medium">${methodLabel}</p>
                                    ${methodDetails}
                                </div>
                                ${firstPayment.remarks ? `
                                <div>
                                    <p class="text-sm text-gray-600">Remarks:</p>
                                    <p class="font-medium">${firstPayment.remarks}</p>
                                </div>
                                ` : ''}
                            </div>
                        </div>
                        
                        <div class="text-center text-xs text-gray-500 mt-8">
                            <p>Thank you for your payment. This is an official receipt.</p>
                            <p class="mt-1">Generated on ${new Date().toLocaleDateString()}</p>
                        </div>
                    </div>
                `;
                
                // Show receipt preview modal
                document.getElementById('receipt-preview-content').innerHTML = receiptContent;
                document.getElementById('receipt-preview-modal').classList.remove('hidden');
                
                // Show/hide logo checkbox based on whether logo exists
                const showPreviewLogoContainer = document.getElementById('show-preview-logo-container');
                const showPreviewLogoCheckbox = document.getElementById('show-preview-logo-checkbox');
                const receiptPreviewLogo = document.getElementById('receipt-preview-logo');
                
                if (schoolLogo && showPreviewLogoContainer && showPreviewLogoCheckbox && receiptPreviewLogo) {
                    // Show the checkbox container
                    showPreviewLogoContainer.style.display = 'flex';
                    
                    // Set initial checkbox state to checked
                    showPreviewLogoCheckbox.checked = true;
                    
                    // Add event listener for the show logo checkbox
                    showPreviewLogoCheckbox.addEventListener('change', function() {
                        receiptPreviewLogo.style.display = this.checked ? 'block' : 'none';
                    });
                } else if (showPreviewLogoContainer) {
                    // Hide the checkbox container if no logo
                    showPreviewLogoContainer.style.display = 'none';
                }
            }
        })
        .catch(error => {
            console.error('Error fetching receipt data:', error);
        });
    })
    .catch(error => {
        console.error('Error fetching school settings:', error);
    });
    }
    
    // Handle close receipt preview modal button clicks
    document.getElementById('close-receipt-preview-btn').addEventListener('click', function() {
        document.getElementById('receipt-preview-modal').classList.add('hidden');
    });
    
    document.getElementById('close-receipt-preview-btn-2').addEventListener('click', function() {
        document.getElementById('receipt-preview-modal').classList.add('hidden');
    });
    
    // Handle print receipt button click
    document.getElementById('print-receipt-btn').addEventListener('click', function() {
        const receiptContent = document.getElementById('receipt-preview-content').innerHTML;
        
        // Create a new window for printing with the exact same styles as the preview
        const printWindow = window.open('', '_blank');
        printWindow.document.write(`
            <!DOCTYPE html>
            <html>
                <head>
                    <title>Payment Receipt</title>
                    <style>
                        /* Copy all the styles from the main application to maintain consistency */
                        body { 
                            font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 
                                       'Helvetica Neue', Arial, 'Noto Sans', sans-serif, 'Apple Color Emoji', 
                                       'Segoe UI Emoji', 'Segoe UI Symbol', 'Noto Color Emoji';
                            margin: 0;
                            padding: 20px;
                            background-color: #f3f4f6;
                        }
                        
                        /* Receipt container styles */
                        .max-w-2xl { max-width: 42rem; }
                        .mx-auto { margin-left: auto; margin-right: auto; }
                        .bg-white { background-color: #fff; }
                        .p-8 { padding: 2rem; }
                        .border { border: 1px solid #d1d5db; }
                        .border-gray-300 { border-color: #d1d5db; }
                        
                        /* Text styles */
                        .text-center { text-align: center; }
                        .text-2xl { font-size: 1.5rem; line-height: 2rem; }
                        .font-bold { font-weight: 700; }
                        .text-gray-800 { color: #1f2937; }
                        .text-gray-600 { color: #4b5563; }
                        .mt-2 { margin-top: 0.5rem; }
                        
                        /* Border styles */
                        .border-t { border-top: 1px solid #d1d5db; }
                        .border-b { border-bottom: 1px solid #d1d5db; }
                        .border-gray-300 { border-color: #d1d5db; }
                        .py-4 { padding-top: 1rem; padding-bottom: 1rem; }
                        .mb-6 { margin-bottom: 1.5rem; }
                        
                        /* Grid styles */
                        .grid { display: grid; }
                        .grid-cols-2 { grid-template-columns: repeat(2, minmax(0, 1fr)); }
                        .gap-4 { gap: 1rem; }
                        
                        /* Text and font styles */
                        .text-sm { font-size: 0.875rem; line-height: 1.25rem; }
                        .text-gray-600 { color: #4b5563; }
                        .font-medium { font-weight: 500; }
                        .text-gray-900 { color: #111827; }
                        
                        /* Table styles */
                        .mb-6 { margin-bottom: 1.5rem; }
                        .text-lg { font-size: 1.125rem; line-height: 1.75rem; }
                        .rounded-md { border-radius: 0.375rem; }
                        .min-w-full { min-width: 100%; }
                        .border-collapse { border-collapse: collapse; }
                        .bg-gray-50 { background-color: #f9fafb; }
                        
                        .px-4 { padding-left: 1rem; padding-right: 1rem; }
                        .py-2 { padding-top: 0.5rem; padding-bottom: 0.5rem; }
                        .text-left { text-align: left; }
                        .text-right { text-align: right; }
                        .text-xs { font-size: 0.75rem; line-height: 1rem; }
                        .text-gray-500 { color: #6b7280; }
                        .border-t { border-top: 1px solid #d1d5db; }
                        .border-gray-300 { border-color: #d1d5db; }
                        
                        /* Margin and padding utilities */
                        .mt-8 { margin-top: 2rem; }
                        .mt-1 { margin-top: 0.25rem; }
                        
                        /* Logo sizing */
                        .h-16 { height: 4rem; }
                        .w-auto { width: auto; }
                        
                        /* Print specific styles */
                        @media print {
                            body {
                                background-color: white;
                                padding: 0;
                            }
                        }
                    </style>
                </head>
                <body>
                    <div class="receipt">
                        ${receiptContent}
                    </div>
                    <script>
                        window.onload = function() {
                            window.print();
                            // Close the window after printing (optional)
                            // window.close();
                        }
                    <\/script>
                </body>
            </html>
        `);
        printWindow.document.close();
    });
    
    // Handle reorganise button click
    document.getElementById('reorganise-btn').addEventListener('click', function() {
        // Get the current receipt content
        const receiptContent = document.getElementById('receipt-preview-content').innerHTML;
        
        // Create compact landscape modal
        const compactModal = document.createElement('div');
        compactModal.id = 'compact-receipt-modal';
        compactModal.className = 'fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full z-50 flex items-center justify-center';
        compactModal.innerHTML = `
            <div class="relative mx-auto p-3 border shadow-lg rounded-md bg-white w-11/12 max-w-3xl max-h-screen overflow-y-auto readable-compact-layout">
                <div class="mt-2">
                    <div class="flex justify-between items-center mb-3">
                        <h3 class="text-base font-medium text-gray-900">Readable Compact Receipt Preview</h3>
                        <div class="flex space-x-2">
                            <button id="print-compact-receipt-btn" class="text-indigo-600 hover:text-indigo-800">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
                            </button>
                            <button id="export-compact-receipt-btn" class="text-green-600 hover:text-green-800">
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                    <path fill-rule="evenodd" d="M3 17a1 1 0 011-1h12a1 1 0 110 2H4a1 1 0 01-1-1zm3.293-7.707a1 1 0 011.414 0L9 10.586V3a1 1 0 112 0v7.586l1.293-1.293a1 1 0 111.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                </svg>
                            </button>
                            <button id="close-compact-receipt-btn" class="text-gray-400 hover:text-gray-500">
                                <span class="text-xl">&times;</span>
                            </button>
                        </div>
                    </div>
                    
                    <div id="compact-receipt-content" class="mt-2 text-sm">
                        ${receiptContent}
                    </div>
                    
                    <div class="mt-3 flex justify-end space-x-2">
                        <button id="close-compact-receipt-btn-2" class="bg-white border border-gray-300 rounded shadow-sm py-1.5 px-3 inline-flex justify-center text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-1 focus:ring-offset-1 focus:ring-indigo-500">
                            Close
                        </button>
                    </div>
                </div>
            </div>
        `;
        
        // Add the compact modal to the body
        document.body.appendChild(compactModal);
        
        // Add custom styles for the readable compact layout
        const style = document.createElement('style');
        style.innerHTML = `
            .readable-compact-layout {
                transform: rotate(0deg);
                max-width: 85vw;
                max-height: 85vh;
            }
            
            .readable-compact-layout #receipt-preview-logo {
                max-height: 2.5rem;
            }
            
            /* Increase font sizes for better readability */
            .readable-compact-layout {
                font-size: 1rem;
            }
            
            .readable-compact-layout .text-2xl {
                font-size: 1.25rem;
            }
            
            .readable-compact-layout .text-xl {
                font-size: 1.125rem;
            }
            
            .readable-compact-layout .text-lg {
                font-size: 1rem;
            }
            
            .readable-compact-layout .text-base {
                font-size: 0.95rem;
            }
            
            .readable-compact-layout .text-sm {
                font-size: 0.875rem;
            }
            
            .readable-compact-layout .text-xs {
                font-size: 0.8125rem;
            }
            
            .readable-compact-layout table {
                font-size: 0.875rem;
            }
            
            /* Emphasize student name and payment details */
            .readable-compact-layout .student-name {
                font-size: 1.125rem;
                font-weight: 600;
                color: #111827;
            }
            
            .readable-compact-layout .payment-details {
                font-size: 1rem;
                font-weight: 500;
            }
            
            .readable-compact-layout .amount {
                font-size: 1.125rem;
                font-weight: 700;
                color: #111827;
            }
            
            /* Adjust spacing to accommodate larger fonts */
            .readable-compact-layout .px-4 {
                padding-left: 0.375rem;
                padding-right: 0.375rem;
            }
            
            .readable-compact-layout .py-2 {
                padding-top: 0.25rem;
                padding-bottom: 0.25rem;
            }
            
            .readable-compact-layout .p-8 {
                padding: 0.75rem;
            }
            
            .readable-compact-layout .mb-6 {
                margin-bottom: 0.75rem;
            }
            
            .readable-compact-layout .mt-8 {
                margin-top: 0.75rem;
            }
            
            .readable-compact-layout .grid-cols-2 {
                grid-template-columns: 1fr 1fr;
                gap: 0.375rem;
            }
            
            .readable-compact-layout .border-t,
            .readable-compact-layout .border-b {
                border-width: 1px;
            }
            
            .readable-compact-layout .rounded-md {
                border-radius: 0.25rem;
            }
            
            /* Ensure table headers are bold for clarity */
            .readable-compact-layout th {
                font-weight: 600;
            }
        `;
        document.head.appendChild(style);
        
        // Add event listeners for closing the compact modal
        document.getElementById('close-compact-receipt-btn').addEventListener('click', function() {
            document.body.removeChild(compactModal);
            document.head.removeChild(style);
        });
        
        document.getElementById('close-compact-receipt-btn-2').addEventListener('click', function() {
            document.body.removeChild(compactModal);
            document.head.removeChild(style);
        });
        
        // Add print functionality
        document.getElementById('print-compact-receipt-btn').addEventListener('click', function() {
            const receiptContent = document.getElementById('compact-receipt-content').innerHTML;
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>Readable Compact Payment Receipt</title>
                        <style>
                            body { 
                                font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 
                                           'Helvetica Neue', Arial, 'Noto Sans', sans-serif;
                                margin: 0;
                                padding: 8px;
                                font-size: 12px;
                                background-color: #fff;
                                color: #000;
                                line-height: 1.4;
                            }
                            .receipt-container { 
                                max-width: 100%; 
                                margin: 0 auto;
                            }
                            /* Match the readable compact layout exactly */
                            .text-center { text-align: center; }
                            .font-bold { font-weight: 700; }
                            .font-medium { font-weight: 500; }
                            .text-gray-800 { color: #1f2937; }
                            .text-gray-600 { color: #4b5563; }
                            .border-t { border-top: 1px solid #d1d5db; }
                            .border-b { border-bottom: 1px solid #d1d5db; }
                            .py-1 { padding-top: 0.25rem; padding-bottom: 0.25rem; }
                            .mb-2 { margin-bottom: 0.5rem; }
                            .mt-1 { margin-top: 0.25rem; }
                            .grid { display: grid; }
                            .grid-cols-2 { grid-template-columns: 1fr 1fr; gap: 0.375rem; }
                            .text-base { font-size: 12px; }
                            .text-sm { font-size: 11px; }
                            .text-xs { font-size: 10px; }
                            .text-right { text-align: right; }
                            .text-left { text-align: left; }
                            table { 
                                width: 100%; 
                                border-collapse: collapse; 
                                font-size: 11px; 
                                margin-bottom: 0.5rem;
                            }
                            th, td { 
                                padding: 2px 4px; 
                                text-align: left;
                            }
                            th {
                                font-weight: 600;
                            }
                            .border-gray-300 { border-color: #d1d5db; }
                            /* Readable logo styling */
                            #receipt-preview-logo { 
                                max-height: 2.5rem !important; 
                                width: auto !important; 
                                display: block;
                                margin: 0 auto;
                            }
                            .h-16 { height: 2.5rem !important; }
                            .w-auto { width: auto !important; }
                            .p-8 { padding: 0.75rem !important; }
                            .mb-6 { margin-bottom: 0.75rem !important; }
                            .mt-8 { margin-top: 0.75rem !important; }
                            .px-4 { padding-left: 0.375rem !important; padding-right: 0.375rem !important; }
                            .py-2 { padding-top: 0.25rem !important; padding-bottom: 0.25rem !important; }
                            /* Emphasize key information */
                            .student-name {
                                font-size: 14px;
                                font-weight: 600;
                                color: #111827;
                            }
                            .payment-details {
                                font-size: 12px;
                                font-weight: 500;
                            }
                            .amount {
                                font-size: 14px;
                                font-weight: 700;
                                color: #111827;
                            }
                            /* Print-specific optimizations */
                            @media print {
                                body { 
                                    margin: 0; 
                                    padding: 3mm;
                                }
                                @page { 
                                    margin: 3mm; 
                                    size: auto;
                                }
                                * { 
                                    -webkit-print-color-adjust: exact !important; 
                                    color-adjust: exact !important; 
                                }
                            }
                            /* Ensure all elements maintain their readable sizing */
                            div, p, span, h1, h2, h3, h4, h5, h6 { 
                                margin: 0; 
                                padding: 0; 
                            }
                            .flex { display: flex; }
                            .justify-between { justify-content: space-between; }
                            .items-center { align-items: center; }
                        </style>
                    </head>
                    <body>
                        <div class="receipt-container">
                            ${receiptContent}
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
        
        // Add export functionality (as PDF simulation)
        document.getElementById('export-compact-receipt-btn').addEventListener('click', function() {
            alert('In a full implementation, this would export the receipt as a PDF. For now, you can use the Print function and select "Save as PDF" in your printer options.');
        });
    });
    
    // Helper functions
    function showError(message) {
        document.getElementById('payment-error-message').textContent = message;
        document.getElementById('payment-error').classList.remove('hidden');
        
        // Hide error after 5 seconds
        setTimeout(() => {
            document.getElementById('payment-error').classList.add('hidden');
        }, 5000);
    }
    
    function showSuccess(message) {
        document.getElementById('payment-success-message').textContent = message;
        document.getElementById('payment-success').classList.remove('hidden');
        
        // Hide success after 5 seconds
        setTimeout(() => {
            document.getElementById('payment-success').classList.add('hidden');
        }, 5000);
    }
});
</script>

<script>
// Function to open student financial information modal
function openStudentFinancialModal(studentId) {
    // Redirect to student profile with financial tab active
    window.location.href = `/students/${studentId}?tab=financial`;
}
// Function to view fee structure details
function viewFeeStructure(feeId) {
    const modal = document.getElementById('view-fee-structure-modal');
    const content = document.getElementById('view-fee-structure-content');
    const editLink = document.getElementById('modal-fee-edit-link');
    
    // Show modal with loading state
    modal.classList.remove('hidden');
    content.innerHTML = `
        <div class="animate-pulse flex space-x-4">
            <div class="flex-1 space-y-4 py-1">
                <div class="h-4 bg-gray-200 rounded w-3/4"></div>
                <div class="space-y-2">
                    <div class="h-4 bg-gray-200 rounded"></div>
                    <div class="h-4 bg-gray-200 rounded w-5/6"></div>
                </div>
            </div>
        </div>
    `;
    
    // Set edit link href
    editLink.href = `/fees/${feeId}/edit`;
    
    // Fetch fee details via AJAX
    fetch(`/fees/${feeId}?students=1`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        if (data.success && data.fee) {
            const fee = data.fee;
            const students = data.students || [];
            const classes = data.classes || [];
            
            // Format fee type
            let typeHtml = '';
            switch(fee.type) {
                case 'tuition': typeHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Tuition</span>'; break;
                case 'transport': typeHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Transport</span>'; break;
                case 'feeding': typeHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Feeding</span>'; break;
                default: typeHtml = '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">' + fee.type.charAt(0).toUpperCase() + fee.type.slice(1) + '</span>';
            }
            
            // Format classes
            let classesHtml = '<span class="text-gray-500">No classes assigned</span>';
            if (classes.length > 0) {
                classesHtml = classes.map(c => c.name).join(', ');
            }
            
            // Students table logic
            let studentsHtml = '';
            if (students.length === 0) {
                studentsHtml = `
                    <div class="px-4 py-5 sm:p-6 text-center">
                        <p class="text-gray-500">No students assigned to this fee structure yet.</p>
                        <div class="mt-4">
                            <a href="/fees/${fee.id}/assign" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700">
                                Assign Students
                            </a>
                        </div>
                    </div>
                `;
            } else {
                let rows = students.map(s => `
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${s.first_name} ${s.last_name}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${s.admission_no}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${s.class_name || 'N/A'}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${new Date(s.assigned_date).toLocaleDateString()}</td>
                    </tr>
                `).join('');
                
                studentsHtml = `
                    <div class="overflow-x-auto border-t border-gray-200">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Assigned Date</th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                ${rows}
                            </tbody>
                        </table>
                    </div>
                `;
            }
            
            content.innerHTML = `
                <div class="border-t border-gray-200 mt-2">
                    <dl>
                        <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Name</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${fee.name}</dd>
                        </div>
                        <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Amount</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">₵${parseFloat(fee.amount).toFixed(2).replace(/\\d(?=(\\d{3})+\\.)/g, '$&,')}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Type</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${typeHtml}</dd>
                        </div>
                        <div class="bg-white px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Class(es)</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${classesHtml}</dd>
                        </div>
                        <div class="bg-gray-50 px-4 py-3 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                            <dt class="text-sm font-medium text-gray-500">Description</dt>
                            <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${fee.description || 'N/A'}</dd>
                        </div>
                    </dl>
                </div>
                
                <div class="mt-4 border border-gray-200 rounded-md overflow-hidden">
                    <div class="bg-gray-50 px-4 py-3 border-b border-gray-200 flex justify-between items-center">
                        <h4 class="text-md font-medium text-gray-900">Assigned Students</h4>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                            ${students.length} students
                        </span>
                    </div>
                    ${studentsHtml}
                </div>
            `;
        } else {
            content.innerHTML = '<p class="text-red-500">Failed to load fee structure details.</p>';
        }
    })
    .catch(error => {
        content.innerHTML = '<p class="text-red-500">Error loading details. Please try again.</p>';
        console.error('Error fetching fee details:', error);
    });
}

// Add print functionality for the Fee Structure Modal
document.addEventListener('DOMContentLoaded', function() {
    const printBtn = document.getElementById('print-fee-structure-btn');
    if (printBtn) {
        printBtn.addEventListener('click', function() {
            const content = document.getElementById('view-fee-structure-content').innerHTML;
            const feeName = document.querySelector('#view-fee-structure-modal h3').textContent || 'Fee Structure Details';
            
            const printWindow = window.open('', '_blank');
            printWindow.document.write(`
                <!DOCTYPE html>
                <html>
                    <head>
                        <title>Print - ${feeName}</title>
                        <style>
                            body { 
                                font-family: system-ui, -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, 
                                           'Helvetica Neue', Arial, 'Noto Sans', sans-serif;
                                margin: 0;
                                padding: 20px;
                                color: #111827;
                                background-color: white;
                            }
                            h3 { font-size: 1.5rem; margin-bottom: 20px; text-align: center; }
                            
                            /* Maintain basic layout */
                            .grid { display: grid; }
                            .grid-cols-3 { grid-template-columns: repeat(3, minmax(0, 1fr)); }
                            .gap-4 { gap: 1rem; }
                            
                            /* Clean borders and padding */
                            .border-t { border-top: 1px solid #e5e7eb; }
                            .border-b { border-bottom: 1px solid #e5e7eb; }
                            dl > div { padding: 12px 0; border-bottom: 1px solid #f3f4f6; }
                            
                            /* Typography */
                            .text-sm { font-size: 0.875rem; }
                            .text-md { font-size: 1rem; }
                            .font-medium { font-weight: 500; text-transform: uppercase; color: #6b7280; }
                            .text-gray-900 { color: #111827; }
                            .text-gray-500 { color: #6b7280; }
                            
                            /* Table Styles */
                            table { width: 100%; border-collapse: collapse; margin-top: 15px; }
                            th, td { padding: 10px 15px; text-align: left; border-bottom: 1px solid #e5e7eb; }
                            th { font-weight: 600; color: #4b5563; text-transform: uppercase; font-size: 0.75rem; background-color: #f9fafb; border-top: 1px solid #e5e7eb; }
                            td { font-size: 0.875rem; }
                            
                            /* Hide loading states and interactive elements */
                            .animate-pulse { display: none; }
                            a, button { display: none !important; }
                            
                            /* Label styling */
                            .bg-blue-100, .bg-green-100, .bg-yellow-100, .bg-gray-100 { 
                                padding: 2px 8px; border-radius: 9999px; font-weight: 600; font-size: 0.75rem;
                                display: inline-block; border: 1px solid #d1d5db;
                            }
                            
                            .mt-4 { margin-top: 1.5rem; }
                            .mt-1 { margin-top: 0.25rem; }
                            
                            @media print {
                                body { padding: 0; }
                                @page { margin: 1.5cm; }
                                * { -webkit-print-color-adjust: exact !important; color-adjust: exact !important; }
                            }
                        </style>
                    </head>
                    <body>
                        <h3>${feeName}</h3>
                        ${content}
                        <script>
                            window.onload = function() {
                                setTimeout(function() { window.print(); }, 500);
                            }
                        <\/script>
                    </body>
                </html>
            `);
            printWindow.document.close();
        });
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>