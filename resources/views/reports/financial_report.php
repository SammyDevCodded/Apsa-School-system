<?php 
$title = 'Financial Report'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Financial Report</h1>
            <form method="GET" class="flex space-x-2">
                <select name="academic_year_id" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <?php 
                    foreach ($academicYears ?? [] as $ay): 
                        // User requested "Show 2026 instead of 2025-2026"
                        // Assuming standard format "Start-End", so 2025-2026.
                        // If they want "2026", that's the end year. 
                        
                        $label = $ay['name'];
                        // Try to parse YYYY-YYYY (allowing for spaces)
                        if (preg_match('/(\d{4})\s*-\s*(\d{4})/', $ay['name'], $matches)) {
                             // The Academic List "Year" column uses the first part (Start Year).
                             // To ensure the dropdown matches the list, we show the Start Year ($matches[1]).
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

        <!-- Financial Summary -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Financial Summary</h3>
                <div class="mt-2">
                    <p class="text-3xl font-bold text-indigo-600">
                        <?php
                        // Include currency helper
                        require_once APP_PATH . '/Helpers/CurrencyHelper.php';
                        echo \App\Helpers\CurrencyHelper::formatAmount($totalAmount ?? 0);
                        ?>
                    </p>
                    <p class="text-sm text-gray-500">Total Payments for Selected Academic Year</p>
                </div>
            </div>
        </div>

        <!-- Monthly Payments Chart -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Monthly Payments</h3>
            </div>
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Month
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Amount
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Transactions
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php foreach ($monthlyPayments ?? [] as $data): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($data['month']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?php
                                    echo \App\Helpers\CurrencyHelper::formatAmount($data['amount']);
                                    ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= $data['count'] ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <button type="button" 
                                            onclick="viewDetails('<?= $data['month_key'] ?>', '<?= htmlspecialchars($data['month']) ?>')"
                                            class="text-indigo-600 hover:text-indigo-900">
                                        View Details
                                    </button>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                        <?php if (empty($monthlyPayments)): ?>
                            <tr>
                                <td colspan="4" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No payments found for this academic year.
                                </td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- Details Modal -->
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
                                <button onclick="printModal()" class="bg-gray-100 text-gray-700 px-3 py-1 rounded-md text-sm hover:bg-gray-200">
                                    Print
                                </button>
                                <button onclick="exportModalCSV()" class="bg-green-600 text-white px-3 py-1 rounded-md text-sm hover:bg-green-700">
                                    Export CSV
                                </button>
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
// Store payment data in JS variable
const allPayments = <?= json_encode($yearlyPayments ?? []) ?>;
let currentModalData = [];

function viewDetails(monthKey, monthName) {
    document.getElementById('modalMonthName').textContent = monthName;
    
    const tbody = document.getElementById('detailsTableBody');
    tbody.innerHTML = '';
    
    // Filter payments for this month
    currentModalData = allPayments.filter(p => {
        return p.date.substring(0, 7) === monthKey;
    });
    
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
                <td class="px-4 py-2 whitespace-nowrap text-sm text-gray-900">${formatCurrency(p.amount)}</td>
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
    
    // Create a new window for printing
    const printWindow = window.open('', '_blank', 'height=600,width=800');
    
    if (printWindow) {
        printWindow.document.write('<html><head><title>Payment Details - ' + monthName + '</title>');
        printWindow.document.write('<style>');
        printWindow.document.write('body { font-family: "Segoe UI", Tahoma, Geneva, Verdana, sans-serif; padding: 20px; color: #333; }');
        printWindow.document.write('h1 { text-align: center; color: #111; margin-bottom: 20px; font-size: 24px; border-bottom: 2px solid #eee; padding-bottom: 15px; }');
        printWindow.document.write('.meta { margin-bottom: 20px; text-align: center; color: #666; font-size: 14px; }');
        printWindow.document.write('table { width: 100%; border-collapse: collapse; margin-bottom: 20px; font-size: 14px; }');
        printWindow.document.write('th, td { border: 1px solid #ddd; padding: 12px 10px; text-align: left; }');
        printWindow.document.write('th { background-color: #f8f9fa; font-weight: 600; color: #444; }');
        printWindow.document.write('tr:nth-child(even) { background-color: #f9f9f9; }');
        printWindow.document.write('.footer { text-align: center; margin-top: 40px; font-size: 12px; color: #888; border-top: 1px solid #eee; padding-top: 15px; }');
        printWindow.document.write('@media print { body { padding: 0; } .no-print { display: none; } }');
        printWindow.document.write('</style>');
        printWindow.document.write('</head><body>');
        
        printWindow.document.write('<h1>Payment Details - ' + monthName + '</h1>');
        printWindow.document.write('<div class="meta"><?= htmlspecialchars($settings['school_name'] ?? 'School Management System') ?> - Financial Report</div>');
        printWindow.document.write(tableHtml);
        printWindow.document.write('<div class="footer">Generated on ' + new Date().toLocaleString() + '</div>');
        
        printWindow.document.write('</body></html>');
        printWindow.document.close(); // Finish writing
        
        // Wait specifically for content to load before printing
        printWindow.focus();
        setTimeout(function() {
            printWindow.print();
            // Optional: Close after print dialog is closed (user action)
            // printWindow.close(); 
        }, 500);
    } else {
        alert('Please allow popups for this website to print reports.');
    }
}

function exportModalCSV() {
    if (currentModalData.length === 0) return;
    
    const monthName = document.getElementById('modalMonthName').textContent;
    let csvContent = "data:text/csv;charset=utf-8,";
    csvContent += "Date,Student,Admission No,Class,Fee Type,Amount,Method\n";
    
    currentModalData.forEach(p => {
        const row = [
            p.date,
            `"${p.first_name} ${p.last_name}"`,
            p.admission_no,
            p.class_name || 'N/A',
            `"${p.fee_name || 'N/A'}"`,
            p.amount,
            p.method
        ];
        csvContent += row.join(",") + "\n";
    });
    
    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    link.setAttribute("download", `payment_details_${monthName.replace(' ', '_')}.csv`);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>