<style>
@media print {
    /* Hide ALL unnecessary layout wrappers on print */
    body * {
        visibility: hidden;
    }
    
    /* Make ONLY the print-header and the target table visible */
    #main-content, #main-content * {
        visibility: visible;
    }

    body {
        margin: 0 !important;
        padding: 0 !important;
        background: #fff !important;
    }

    /* Remove limits on the container itself */
    #main-content {
        position: absolute;
        left: 0;
        top: 0;
        width: 100vw;
        max-width: 100%;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    .print-header {
        display: block !important;
        visibility: visible !important;
        width: 100%;
        text-align: center;
        margin-bottom: 2rem;
    }
    
    .print-header * {
        visibility: visible !important;
    }

    /* The table itself */
    table {
        width: 100vw !important;
        max-width: 100vw !important;
        table-layout: fixed; /* Crucial: Forces hard width percentages */
        border-collapse: collapse;
        margin-top: 1rem;
    }
    
    th, td {
        word-wrap: break-word;
        border: 1px solid #e5e7eb;
        padding: 8px 4px !important;
        font-size: 11pt !important; 
    }
    
    th {
        background-color: #f9fafb !important;
        -webkit-print-color-adjust: exact; 
        print-color-adjust: exact;
    }

    /* Remove background pills/badges for cleaner print */
    .bg-indigo-100, .bg-green-100 {
        background-color: transparent !important;
        padding: 0 !important;
    }

    /* Hardcode exact percentages for standard A4 landscape or portrait text distribution */
    th:nth-child(1), td:nth-child(1) { width: 14%; } /* Date */
    th:nth-child(2), td:nth-child(2) { width: 35%; } /* Item */
    th:nth-child(3), td:nth-child(3) { width: 20%; } /* Category */
    th:nth-child(4), td:nth-child(4) { width: 15%; text-align: right; } /* Amount */
    th:nth-child(5), td:nth-child(5) { width: 16%; text-align: center; } /* Status */
}
</style>

<!-- Print Header (Hidden on Screen) -->
<div class="hidden print:block mb-8 text-center print-header">
    <?php if (!empty($settings['school_logo'])): ?>
        <img src="<?= htmlspecialchars($settings['school_logo']) ?>" alt="School Logo" class="h-20 mx-auto mb-4">
    <?php endif; ?>
    <h1 class="text-2xl font-bold"><?= htmlspecialchars($settings['school_name'] ?? 'School Management System') ?></h1>
    <h2 class="text-xl mt-2 font-semibold">Expense Log</h2>
    <p class="text-gray-600 mt-1">
        <?php if (!empty($filters['start_date']) && !empty($filters['end_date'])): ?>
            Period: <?= date('M j, Y', strtotime($filters['start_date'])) ?> to <?= date('M j, Y', strtotime($filters['end_date'])) ?>
        <?php elseif (!empty($filters['start_date'])): ?>
            From: <?= date('M j, Y', strtotime($filters['start_date'])) ?>
        <?php elseif (!empty($filters['end_date'])): ?>
            Until: <?= date('M j, Y', strtotime($filters['end_date'])) ?>
        <?php else: ?>
            All Time
        <?php endif; ?>
    </p>
</div>

<!-- Expenses List Partial -->
<div class="sm:flex sm:items-center sm:justify-between mb-4 print:hidden">
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">Expenses Log</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Record and manage daily school expenses.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 mr-2">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print
        </button>
        <button onclick="openExpenseModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Log Expense
        </button>
    </div>
</div>

<!-- Filters -->
<div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6 mb-6 print:hidden">
    <form method="GET" action="/finance/expenses" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:gap-4">
        <input type="hidden" name="tab" value="expenses">
        
        <div class="w-full sm:w-1/4">
            <label for="search_exp" class="block text-sm font-medium text-gray-700">Search Title/Code</label>
            <input type="text" name="search" id="search_exp" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search..." class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 border px-3 py-2 rounded-md">
        </div>
        
        <div class="w-full sm:w-1/4">
            <label for="cat_filter" class="block text-sm font-medium text-gray-700">Category</label>
            <select name="category_id" id="cat_filter" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md border">
                <option value="">All Categories</option>
                <?php foreach ($categories as $cat): ?>
                    <option value="<?= $cat['id'] ?>" <?= (isset($filters['category_id']) && $filters['category_id'] == $cat['id']) ? 'selected' : '' ?>>
                        <?= htmlspecialchars($cat['category_name']) ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div class="w-full sm:w-1/5">
            <label for="start_date_exp" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" name="start_date" id="start_date_exp" value="<?= htmlspecialchars($filters['start_date'] ?? '') ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 border px-3 py-2 rounded-md">
        </div>
        
        <div class="w-full sm:w-1/5">
            <label for="end_date_exp" class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" name="end_date" id="end_date_exp" value="<?= htmlspecialchars($filters['end_date'] ?? '') ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 border px-3 py-2 rounded-md">
        </div>
        
        <div class="w-20 sm:w-24">
            <label for="limit_exp" class="block text-xs font-medium text-gray-700 whitespace-nowrap">Per Page</label>
            <select name="limit" id="limit_exp" class="mt-1 block w-full pl-2 pr-2 py-1 text-xs border-b border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500 rounded-none shadow-none text-gray-700">
                <option value="10" <?= ($limit ?? 50) == 10 ? 'selected' : '' ?>>10</option>
                <option value="25" <?= ($limit ?? 50) == 25 ? 'selected' : '' ?>>25</option>
                <option value="50" <?= ($limit ?? 50) == 50 ? 'selected' : '' ?>>50</option>
                <option value="100" <?= ($limit ?? 50) == 100 ? 'selected' : '' ?>>100</option>
            </select>
        </div>
        
        <div class="w-full sm:w-auto mt-4 sm:mt-0 flex items-end h-full pt-6">
            <button type="submit" class="w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                Compute
            </button>
            <?php if (!empty($search) || !empty($filters['start_date']) || !empty($filters['end_date']) || !empty($filters['category_id'])): ?>
                <a href="/finance/expenses?tab=expenses" class="ml-2 w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Clear
                </a>
            <?php endif; ?>
        </div>
    </form>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expense Item</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Category</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th scope="col" class="px-6 py-3 text-center text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="relative px-6 py-3 print:hidden">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($expenses)): ?>
                    <tr>
                        <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No expenses recorded yet.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($expenses as $expense): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M j, Y', strtotime($expense['expense_date'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($expense['title']) ?></div>
                                <?php if (!empty($expense['staff_first'])): ?>
                                    <div class="text-xs text-gray-500">For: <?= htmlspecialchars($expense['staff_first'] . ' ' . $expense['staff_last']) ?></div>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800">
                                    <?= htmlspecialchars($expense['category_name']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium text-red-600">
                                <?= \App\Helpers\CurrencyHelper::formatAmount($expense['amount']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-center">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                    <?= ucfirst($expense['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium print:hidden">
                                <button onclick='editExpense(<?= json_encode($expense) ?>)' class="text-indigo-600 hover:text-indigo-900 mr-3">Edit</button>
                                <form action="/finance/expenses/delete" method="POST" class="inline" onsubmit="return confirm('Delete this expense record? This will also un-debit from the cash book.');">
                                    <input type="hidden" name="id" value="<?= $expense['id'] ?>">
                                    <button type="submit" class="text-red-600 hover:text-red-900">Delete</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
    
    <?php if (isset($totalPages) && $totalPages > 1): ?>
        <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 print:hidden">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing page <span class="font-medium"><?= $page ?></span> of <span class="font-medium"><?= $totalPages ?></span>
                        (Total <span class="font-medium"><?= $totalItems ?></span> items)
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <?php if ($page > 1): ?>
                            <a href="/finance/expenses?tab=expenses<?= !empty($search) ? '&search='.urlencode($search) : '' ?><?= !empty($filters['start_date']) ? '&start_date='.urlencode($filters['start_date']) : '' ?><?= !empty($filters['end_date']) ? '&end_date='.urlencode($filters['end_date']) : '' ?><?= !empty($filters['category_id']) ? '&category_id='.urlencode($filters['category_id']) : '' ?>&page=<?= $page - 1 ?>&limit=<?= $limit ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                Previous
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($page < $totalPages): ?>
                            <a href="/finance/expenses?tab=expenses<?= !empty($search) ? '&search='.urlencode($search) : '' ?><?= !empty($filters['start_date']) ? '&start_date='.urlencode($filters['start_date']) : '' ?><?= !empty($filters['end_date']) ? '&end_date='.urlencode($filters['end_date']) : '' ?><?= !empty($filters['category_id']) ? '&category_id='.urlencode($filters['category_id']) : '' ?>&page=<?= $page + 1 ?>&limit=<?= $limit ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                Next
                            </a>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </div>
    <?php endif; ?>
</div>

<!-- Modal: Add/Edit Expense -->
<div id="add-expense-modal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeExpenseModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <form id="expense-form" action="/finance/expenses/save" method="POST">
                <input type="hidden" name="id" id="exp_id" value="">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="exp-modal-title">Log New Expense</h3>
                    
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <label for="title" class="block text-sm font-medium text-gray-700">Expense Title / Summary <span class="text-red-500">*</span></label>
                            <input type="text" name="title" id="exp_title" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                        </div>

                        <div>
                            <label for="category_id" class="block text-sm font-medium text-gray-700">Category <span class="text-red-500">*</span></label>
                            <select id="exp_category" name="category_id" required class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" onchange="handleCategoryChange(this)">
                                <option value="">Select Category</option>
                                <?php foreach ($categories as $cat): ?>
                                    <option value="<?= $cat['id'] ?>" data-name="<?= htmlspecialchars($cat['category_name']) ?>"><?= htmlspecialchars($cat['category_name']) ?></option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="expense_date" class="block text-sm font-medium text-gray-700">Date <span class="text-red-500">*</span></label>
                            <input type="date" name="expense_date" id="exp_date" value="<?= date('Y-m-d') ?>" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                        </div>

                        <!-- Staff Selection (Hidden by default, shown for Staff Pay category) -->
                        <div id="staff_selection_container" class="sm:col-span-2 hidden bg-indigo-50 p-4 rounded-md border border-indigo-100 mb-2">
                            <label for="staff_id" class="block text-sm font-medium text-indigo-800 mb-2">Select Staff Member</label>
                            <select id="staff_selection" name="staff_id" class="block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" onchange="autoFillSalary(this)">
                                <option value="">-- Choose Staff --</option>
                                <?php if (!empty($staffs)): ?>
                                    <?php foreach ($staffs as $staff): ?>
                                        <option value="<?= $staff['id'] ?>" data-salary="<?= $staff['salary'] ?? 0 ?>">
                                            <?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?> 
                                            (<?= htmlspecialchars($staff['position'] ?? 'Staff') ?>)
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <p class="text-xs text-indigo-600 mt-2">Selecting a staff member will auto-fill the amount with their registered salary.</p>
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount (<?= \App\Helpers\CurrencyHelper::getCurrencySymbol() ?>) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" min="0.01" name="amount" id="exp_amount" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                        </div>

                        <div>
                            <label for="payment_method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <select id="exp_method" name="payment_method" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="cash">Cash</option>
                                <option value="bank">Bank Transfer</option>
                                <option value="mobile_money">Mobile Money</option>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Additional Remarks</label>
                            <textarea name="description" id="exp_description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Expense
                    </button>
                    <button type="button" onclick="closeExpenseModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function handleCategoryChange(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const categoryName = selectedOption.getAttribute('data-name');
    const staffContainer = document.getElementById('staff_selection_container');
    const titleInput = document.getElementById('exp_title');
    
    if (categoryName && categoryName.toLowerCase().includes('staff')) {
        staffContainer.classList.remove('hidden');
        if (!titleInput.value) {
            titleInput.value = "Staff Allowance / Payment";
        }
    } else {
        staffContainer.classList.add('hidden');
        document.getElementById('staff_selection').value = '';
    }
}

function autoFillSalary(selectElement) {
    const selectedOption = selectElement.options[selectElement.selectedIndex];
    const salary = selectedOption.getAttribute('data-salary');
    
    if (salary && parseFloat(salary) > 0) {
        document.getElementById('exp_amount').value = salary;
    }
}

function openExpenseModal() {
    document.getElementById('expense-form').reset();
    document.getElementById('exp_id').value = '';
    document.getElementById('exp_date').value = new Date().toISOString().split('T')[0];
    document.getElementById('exp-modal-title').innerText = 'Log New Expense';
    document.getElementById('staff_selection_container').classList.add('hidden');
    document.getElementById('add-expense-modal').classList.remove('hidden');
}

function editExpense(expense) {
    document.getElementById('exp_id').value = expense.id;
    document.getElementById('exp_title').value = expense.title;
    document.getElementById('exp_category').value = expense.category_id;
    document.getElementById('exp_date').value = expense.expense_date;
    document.getElementById('exp_amount').value = expense.amount;
    document.getElementById('exp_method').value = expense.payment_method;
    document.getElementById('exp_description').value = expense.description || '';
    
    // Trigger category logic to potentially show staff selection
    handleCategoryChange(document.getElementById('exp_category'));
    if (expense.staff_id) {
        document.getElementById('staff_selection').value = expense.staff_id;
    }
    
    document.getElementById('exp-modal-title').innerText = 'Edit Expense';
    document.getElementById('add-expense-modal').classList.remove('hidden');
}

function closeExpenseModal() {
    document.getElementById('add-expense-modal').classList.add('hidden');
}

// Check if open modal requested via URL param
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'add' && urlParams.get('tab') === 'expenses') {
        openExpenseModal();
    }
});
</script>
