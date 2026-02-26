<!-- Reports Partial -->
<div class="sm:flex sm:items-center sm:justify-between mb-4">
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">Expenditure Reports</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Filter and analyze your expense data.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M5 4v3H4a2 2 0 00-2 2v3a2 2 0 002 2h1v2a2 2 0 002 2h6a2 2 0 002-2v-2h1a2 2 0 002-2V9a2 2 0 00-2-2h-1V4a2 2 0 00-2-2H7a2 2 0 00-2 2zm8 0H7v3h6V4zm0 8H7v4h6v-4z" clip-rule="evenodd" />
            </svg>
            Print Report
        </button>
    </div>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-lg mb-8">
    <div class="p-6 border-b border-gray-200 bg-gray-50">
        <form method="GET" action="/finance/expenses" class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-4">
            <input type="hidden" name="tab" value="reports">
            
            <div>
                <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                <input type="date" name="start_date" id="start_date" value="<?= htmlspecialchars($_GET['start_date'] ?? date('Y-m-01')) ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
            </div>
            
            <div>
                <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                <input type="date" name="end_date" id="end_date" value="<?= htmlspecialchars($_GET['end_date'] ?? date('Y-m-t')) ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
            </div>
            
            <div>
                <label for="category_id" class="block text-sm font-medium text-gray-700">Category Filter</label>
                <select name="category_id" id="category_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    <option value="">All Categories</option>
                    <?php 
                    $catModel = new \App\Models\ExpenseCategory();
                    $allCategories = $catModel->getAll();
                    foreach ($allCategories as $cat): 
                        $selected = (isset($_GET['category_id']) && $_GET['category_id'] == $cat['id']) ? 'selected' : '';
                    ?>
                        <option value="<?= $cat['id'] ?>" <?= $selected ?>><?= htmlspecialchars($cat['category_name']) ?></option>
                    <?php endforeach; ?>
                </select>
            </div>
            
            <div class="flex items-end">
                <button type="submit" class="w-full inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Generate
                </button>
            </div>
        </form>
    </div>
    
    <?php 
    // Load filtered report data
    $expModel = new \App\Models\Expense();
    $filters = [];
    if (!empty($_GET['start_date'])) $filters['start_date'] = $_GET['start_date'];
    if (!empty($_GET['end_date'])) $filters['end_date'] = $_GET['end_date'];
    if (!empty($_GET['category_id'])) $filters['category_id'] = $_GET['category_id'];
    
    $reportData = $expModel->getAllWithDetails($filters);
    
    $totalAmount = 0;
    foreach ($reportData as $row) {
        if ($row['status'] === 'approved') {
            $totalAmount += $row['amount'];
        }
    }
    ?>
    
    <div class="px-4 py-5 sm:p-6 print-section">
        <h4 class="text-lg font-bold text-gray-800 mb-4 border-b pb-2">Report Summary</h4>
        <dl class="grid grid-cols-1 gap-x-4 gap-y-8 sm:grid-cols-2">
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">Period Date Range</dt>
                <dd class="mt-1 text-sm text-gray-900 font-medium">
                    <?= date('M j, Y', strtotime($filters['start_date'] ?? date('Y-m-01'))) ?> - <?= date('M j, Y', strtotime($filters['end_date'] ?? date('Y-m-t'))) ?>
                </dd>
            </div>
            <div class="sm:col-span-1">
                <dt class="text-sm font-medium text-gray-500">Total Valid Expenditures</dt>
                <dd class="mt-1 text-2xl font-bold text-red-600"><?= \App\Helpers\CurrencyHelper::formatAmount($totalAmount) ?></dd>
            </div>
        </dl>
        
        <h4 class="text-lg font-bold text-gray-800 mt-8 mb-4 border-b pb-2">Itemized Breakdown</h4>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 border mt-4">
                <thead class="bg-gray-100">
                    <tr>
                        <th class="px-4 py-2 text-left text-xs font-bold text-gray-600 uppercase border">Date</th>
                        <th class="px-4 py-2 text-left text-xs font-bold text-gray-600 uppercase border">Category</th>
                        <th class="px-4 py-2 text-left text-xs font-bold text-gray-600 uppercase border">Description</th>
                        <th class="px-4 py-2 text-left text-xs font-bold text-gray-600 uppercase border">Amount</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200">
                    <?php if (empty($reportData)): ?>
                        <tr><td colspan="4" class="px-4 py-2 text-center text-sm">No data matching the criteria.</td></tr>
                    <?php else: ?>
                        <?php foreach ($reportData as $item): ?>
                            <tr>
                                <td class="px-4 py-2 text-sm border"><?= date('M j, Y', strtotime($item['expense_date'])) ?></td>
                                <td class="px-4 py-2 text-sm border"><?= htmlspecialchars($item['category_name']) ?></td>
                                <td class="px-4 py-2 text-sm border">
                                    <span class="font-medium"><?= htmlspecialchars($item['title']) ?></span>
                                    <?php if ($item['staff_first']): ?>
                                        <br><span class="text-xs text-gray-500">Staff: <?= htmlspecialchars($item['staff_first'] . ' ' . $item['staff_last']) ?></span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-4 py-2 text-sm border font-medium text-right"><?= \App\Helpers\CurrencyHelper::formatAmount($item['amount']) ?></td>
                            </tr>
                        <?php endforeach; ?>
                        <tr class="bg-gray-50">
                            <td colspan="3" class="px-4 py-2 text-sm font-bold border text-right">Total:</td>
                            <td class="px-4 py-2 text-sm font-bold border text-right text-red-600"><?= \App\Helpers\CurrencyHelper::formatAmount($totalAmount) ?></td>
                        </tr>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </div>
</div>

<style>
@media print {
    body * { visibility: hidden; }
    .print-section, .print-section * { visibility: visible; }
    .print-section { position: absolute; left: 0; top: 0; width: 100%; }
    form, button { display: none !important; }
}
</style>
