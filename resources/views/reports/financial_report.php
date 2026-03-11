<?php 
$title = 'Financial Report'; 
ob_start(); 

require_once APP_PATH . '/Helpers/CurrencyHelper.php';
$currencyFormat = function($amount) {
    return \App\Helpers\CurrencyHelper::formatAmount($amount ?? 0);
};

// Calculate net balance
$netBalance = ($totalAmount ?? 0) - ($totalExpenses ?? 0);
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center mb-6 space-y-4 sm:space-y-0">
            <div class="flex items-center space-x-4">
                <a href="/reports" class="text-indigo-600 hover:text-indigo-900 flex items-center bg-indigo-50 px-3 py-2 rounded-md">
                    <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18" />
                    </svg>
                    Back to Reports
                </a>
                <h1 class="text-2xl font-semibold text-gray-900">Financial Report</h1>
            </div>
            
            <form method="GET" class="flex space-x-2">
                <select name="academic_year_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <?php 
                    foreach ($academicYears ?? [] as $ay): 
                        $label = $ay['name'];
                        if (preg_match('/(\d{4})\s*-\s*(\d{4})/', $ay['name'], $matches)) {
                             $label = $matches[1]; 
                        }
                    ?>
                        <option value="<?= $ay['id'] ?>" <?= (isset($selectedAcademicYearId) && $selectedAcademicYearId == $ay['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($label) ?>
                        </option>
                    <?php endforeach; ?>
                </select>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                    Filter
                </button>
            </form>
        </div>

        <!-- Financial Summary Cards -->
        <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
            <!-- Total Income (Fees) -->
            <div class="bg-white shadow rounded-lg overflow-hidden border-t-4 border-green-500">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Total Income (Fees)</h3>
                    <div class="mt-2 text-3xl font-bold text-green-600">
                        <?= $currencyFormat($totalAmount) ?>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">From recorded fee payments</p>
                </div>
            </div>

            <!-- Total Expenses -->
            <div class="bg-white shadow rounded-lg overflow-hidden border-t-4 border-red-500">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Total Expenses</h3>
                    <div class="mt-2 text-3xl font-bold text-red-600">
                        <?= $currencyFormat($totalExpenses) ?>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">From approved expenses</p>
                </div>
            </div>

            <!-- Net Balance -->
            <div class="bg-white shadow rounded-lg overflow-hidden border-t-4 <?= $netBalance >= 0 ? 'border-blue-500' : 'border-yellow-500' ?>">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Net Flow</h3>
                    <div class="mt-2 text-3xl font-bold <?= $netBalance >= 0 ? 'text-blue-600' : 'text-yellow-600' ?>">
                        <?= $currencyFormat($netBalance) ?>
                    </div>
                    <p class="text-sm text-gray-500 mt-1">Income minus Expenses</p>
                </div>
            </div>
        </div>
        
        <!-- Cash Book Summary Card -->
        <div class="bg-gray-50 shadow rounded-lg overflow-hidden mb-8 border border-gray-200">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center bg-white border-b border-gray-200">
                <div>
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Cash Book Overview</h3>
                    <p class="text-sm text-gray-500 mt-1">Ledger totals for the selected period</p>
                </div>
            </div>
            <div class="px-4 py-5 sm:p-6 text-center grid grid-cols-2 divide-x divide-gray-200">
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Total Credit</h4>
                    <span class="text-2xl font-bold text-green-600"><?= $currencyFormat($cashBookTotals['total_credit'] ?? 0) ?></span>
                </div>
                <div>
                    <h4 class="text-sm font-medium text-gray-500 uppercase tracking-wider mb-2">Total Debit</h4>
                    <span class="text-2xl font-bold text-red-600"><?= $currencyFormat($cashBookTotals['total_debit'] ?? 0) ?></span>
                </div>
            </div>
        </div>

        <!-- Monthly Fee Payments -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Monthly Fee Payments</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Month</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Transactions</th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($monthlyPayments ?? [] as $data): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= htmlspecialchars($data['month']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $currencyFormat($data['amount']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= $data['count'] ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <button type="button" onclick="viewDetails('<?= $data['month_key'] ?>', '<?= htmlspecialchars($data['month']) ?>')" class="text-indigo-600 hover:text-indigo-900">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($monthlyPayments)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No fee payments found for this academic year.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
        <!-- Expenses Breakdown -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
            <div class="px-4 py-5 sm:px-6 flex justify-between items-center">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Expenses Breakdown</h3>
            </div>
            <div class="overflow-x-auto max-h-[400px]">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50 sticky top-0">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Title / Code</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($expenses ?? [] as $exp): ?>
                            <tr class="hover:bg-gray-50">
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M j, Y', strtotime($exp['expense_date'])) ?></td>
                                <td class="px-6 py-4 text-sm text-gray-900">
                                    <div class="font-medium"><?= htmlspecialchars($exp['title']) ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($exp['expense_code']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                        <?= htmlspecialchars($exp['category_name'] ?? 'Uncategorized') ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-red-600"><?= $currencyFormat($exp['amount']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($expenses)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">No expenses recorded for this academic year.</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
        
    </div>
</div>

<!-- Details Modal (For Fee Payments) -->
<div id="detailsModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:text-left w-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Payment Details - <span id="modalMonthName"></span>
                            </h3>
                            <div class="flex space-x-2">
                                <button onclick="printModal()" class="bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-sm hover:bg-gray-200">Print</button>
                                <button onclick="exportModalCSV()" class="bg-green-600 text-white px-3 py-1 rounded-md text-sm hover:bg-green-700">Export CSV</button>
                                <button onclick="closeModal()" class="text-gray-400 hover:text-gray-500">
                                    <span class="sr-only">Close</span>
                                    <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                    </svg>
                                </button>
                            </div>
                        </div>
                        
                        <div id="modalContent" class="overflow-x-auto max-h-96">
                            <table class="min-w-full divide-y divide-gray-200" id="detailsTable">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Fee Type</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                        <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200" id="detailsTableBody">
                                    <!-- Populated by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
const allPayments = <?= json_encode($yearlyPayments ?? []) ?>;
let currentModalData = [];

function viewDetails(monthKey, monthName) {
    document.getElementById('modalMonthName').textContent = monthName;
    
    const tbody = document.getElementById('detailsTableBody');
    tbody.innerHTML = '';
    
    currentModalData = allPayments.filter(p => p.date.substring(0, 7) === monthKey);
    
    if (currentModalData.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-4 py-2 text-center text-gray-500">No records found.</td></tr>';
    } else {
        currentModalData.forEach(p => {
            const row = document.createElement('tr');
            row.innerHTML = `
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">${p.date}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">${p.first_name} ${p.last_name} (${p.admission_no})</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">${p.class_name || 'N/A'}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">${p.fee_name || 'N/A'}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm font-semibold text-green-600">${formatCurrency(p.amount)}</td>
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900 capitalize">${p.method.replace('_', ' ')}</td>
            `;
            tbody.appendChild(row);
        });
    }
    
    document.getElementById('detailsModal').classList.remove('hidden');
}

function closeModal() {
    document.getElementById('detailsModal').classList.add('hidden');
}

function formatCurrency(amount) {
    return '<?= $settings['currency_symbol'] ?? 'GH₵' ?>' + parseFloat(amount).toFixed(2);
}

function printModal() {
    const monthName = document.getElementById('modalMonthName').textContent;
    const tableHtml = document.getElementById('detailsTable').outerHTML;
    
    const printWindow = window.open('', '_blank', 'height=600,width=800');
    
    if (printWindow) {
        printWindow.document.write('<html><head><title>Payment Details - ' + monthName + '</title>');
        printWindow.document.write('<style>body{font-family:sans-serif;padding:20px} h1{text-align:center;border-bottom:2px solid #eee;padding-bottom:15px} .meta{text-align:center;color:#666;margin-bottom:20px} table{width:100%;border-collapse:collapse;margin-bottom:20px} th,td{border:1px solid #ddd;padding:12px;text-align:left} th{background:#f8f9fa}</style>');
        printWindow.document.write('</head><body>');
        printWindow.document.write('<h1>Payment Details - ' + monthName + '</h1>');
        printWindow.document.write('<div class="meta"><?= htmlspecialchars($settings['school_name'] ?? 'School Management System') ?> - Financial Report</div>');
        printWindow.document.write(tableHtml);
        printWindow.document.write('</body></html>');
        printWindow.document.close();
        
        printWindow.focus();
        setTimeout(() => printWindow.print(), 500);
    } else {
        alert('Please allow popups to print reports.');
    }
}

function exportModalCSV() {
    if (currentModalData.length === 0) return;
    
    const monthName = document.getElementById('modalMonthName').textContent;
    let csvContent = "data:text/csv;charset=utf-8,Date,Student,Admission No,Class,Fee Type,Amount,Method\n";
    
    currentModalData.forEach(p => {
        const row = [p.date, `"${p.first_name} ${p.last_name}"`, p.admission_no, p.class_name || 'N/A', `"${p.fee_name || 'N/A'}"`, p.amount, p.method];
        csvContent += row.join(",") + "\n";
    });
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.href = encodedUri;
    link.download = `payment_details_${monthName.replace(' ', '_')}.csv`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>