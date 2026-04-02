<!-- Print Header (Hidden on Screen) -->
<div class="hidden print:block mb-8 text-center print-header">
    <?php if (!empty($settings['school_logo'])): ?>
        <img src="<?= htmlspecialchars($settings['school_logo']) ?>" alt="School Logo" class="h-20 mx-auto mb-4">
    <?php endif; ?>
    <h1 class="text-2xl font-bold"><?= htmlspecialchars($settings['school_name'] ?? 'School Management System') ?></h1>
    <h2 class="text-xl mt-2 font-semibold">Cash Book Ledger</h2>
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

<!-- Cash Book Partial -->
<div class="sm:flex sm:items-center sm:justify-between mb-4 print:hidden">
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">Cash Book</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">A unified ledger tracking all incomings and outgoings.</p>
    </div>
    <div class="mt-4 sm:mt-0 flex gap-4 items-center">
        <button onclick="window.print()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
            </svg>
            Print Ledger
        </button>
        <div class="px-4 py-2 bg-gray-50 text-right shadow rounded-md border border-gray-200">
            <span class="text-sm font-medium text-gray-500 mr-2">Net Cash Balance:</span>
            <span class="text-2xl font-bold <?= ($currentBalance >= 0) ? 'text-green-600' : 'text-red-600' ?>">
                <?= \App\Helpers\CurrencyHelper::formatAmount($currentBalance) ?>
            </span>
        </div>
    </div>
</div>

<!-- Filters -->
<div class="bg-white shadow px-4 py-5 sm:rounded-lg sm:p-6 mb-6 print:hidden">
    <form method="GET" action="/finance/expenses" class="space-y-4 sm:space-y-0 sm:flex sm:items-center sm:gap-4">
        <input type="hidden" name="tab" value="cashbook">
        
        <div class="w-full sm:w-1/4">
            <label for="search" class="block text-sm font-medium text-gray-700">Search Description/Code</label>
            <input type="text" name="search" id="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search..." class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 border px-3 py-2 rounded-md">
        </div>
        
        <div class="w-full sm:w-1/4">
            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
            <input type="date" name="start_date" id="start_date" value="<?= htmlspecialchars($filters['start_date'] ?? '') ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 border px-3 py-2 rounded-md">
        </div>
        
        <div class="w-full sm:w-1/4">
            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
            <input type="date" name="end_date" id="end_date" value="<?= htmlspecialchars($filters['end_date'] ?? '') ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 border px-3 py-2 rounded-md">
        </div>
        
        <div class="w-full sm:w-1/4">
            <label for="transaction_type" class="block text-sm font-medium text-gray-700">Type</label>
            <select name="transaction_type" id="transaction_type" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md border">
                <option value="">All</option>
                <option value="credit" <?= (isset($filters['transaction_type']) && $filters['transaction_type'] === 'credit') ? 'selected' : '' ?>>Credit (In)</option>
                <option value="debit" <?= (isset($filters['transaction_type']) && $filters['transaction_type'] === 'debit') ? 'selected' : '' ?>>Debit (Out)</option>
            </select>
        </div>
        
        <div class="w-20 sm:w-24">
            <label for="limit" class="block text-xs font-medium text-gray-700 whitespace-nowrap">Per Page</label>
            <select name="limit" id="limit" class="mt-1 block w-full pl-2 pr-2 py-1 text-xs border-b border-gray-300 bg-transparent focus:outline-none focus:border-indigo-500 rounded-none shadow-none text-gray-700">
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
            <?php if (!empty($search) || !empty($filters['start_date']) || !empty($filters['end_date'])): ?>
                <a href="/finance/expenses?tab=cashbook" class="ml-2 w-full sm:w-auto inline-flex items-center justify-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
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
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Txn Code</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Ref Type</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Expense Item Description</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Credit In</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Debit Out</th>
                    <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Running Bal</th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($ledger)): ?>
                    <tr>
                        <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No transactions match the criteria.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($ledger as $entry): ?>
                        <tr class="<?= $entry['transaction_type'] === 'credit' ? 'bg-green-50' : '' ?>">
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M j, Y', strtotime($entry['transaction_date'])) ?></td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono"><?= htmlspecialchars($entry['transaction_code']) ?></td>
                            <td class="px-6 py-4 text-sm text-gray-500 whitespace-nowrap">
                                <?php
                                $refLabels = [
                                    'expense' => 'Direct Expense',
                                    'payment_request' => 'Payment Request',
                                    'fee_payment' => 'Fee Collection',
                                    'other_income' => 'Other Income'
                                ];
                                echo $refLabels[$entry['reference_type']] ?? 'Unknown';
                                ?>
                            </td>
                            <td class="px-6 py-4 text-sm text-gray-900">
                                <div class="flex items-center flex-wrap gap-2">
                                    <span><?= htmlspecialchars($entry['expense_title'] ?? $entry['request_purpose'] ?? '-') ?></span>
                                    <?php if ($entry['reference_type'] === 'payment_request' && !empty($entry['staff_first']) && $entry['pr_requested_by'] != $entry['staff_user_id']): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            On behalf of <?= htmlspecialchars($entry['staff_first'] . ' ' . $entry['staff_last']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            
                            <?php if ($entry['transaction_type'] === 'credit'): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600 text-right">+<?= \App\Helpers\CurrencyHelper::formatAmount($entry['amount']) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 text-right">-</td>
                            <?php else: ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-400 text-right">-</td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600 text-right">-<?= \App\Helpers\CurrencyHelper::formatAmount($entry['amount']) ?></td>
                            <?php endif; ?>
                            
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-right <?= ($entry['balance_after'] >= 0) ? 'text-gray-900' : 'text-red-600' ?>">
                                <?= \App\Helpers\CurrencyHelper::formatAmount($entry['balance_after']) ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
            <?php if (!empty($ledger)): ?>
            <tfoot class="bg-gray-50 font-bold border-t-2 border-gray-200">
                <tr>
                    <td colspan="4" class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">Total:</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-green-600 text-right">+<?= \App\Helpers\CurrencyHelper::formatAmount($ledgerTotals['total_credit'] ?? 0) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-red-600 text-right">-<?= \App\Helpers\CurrencyHelper::formatAmount($ledgerTotals['total_debit'] ?? 0) ?></td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">
                        <?php 
                        $netFiltered = ($ledgerTotals['total_credit'] ?? 0) - ($ledgerTotals['total_debit'] ?? 0);
                        $netClass = $netFiltered >= 0 ? 'text-green-600' : 'text-red-600';
                        $netPrefix = $netFiltered >= 0 ? '+' : '';
                        ?>
                        <span class="<?= $netClass ?>"><?= $netPrefix ?><?= \App\Helpers\CurrencyHelper::formatAmount($netFiltered) ?></span>
                    </td>
                </tr>
            </tfoot>
            <?php endif; ?>
        </table>
    </div>
    
    <!-- Pagination -->
    <?php if ($totalPages > 1): ?>
    <div class="bg-white px-4 py-3 border-t border-gray-200 sm:px-6 print:hidden">
        <div class="flex items-center justify-between">
            <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                <div>
                    <p class="text-sm text-gray-700">
                        Showing <span class="font-medium"><?= count($ledger) ?></span> of <span class="font-medium"><?= $totalItems ?></span> entries
                    </p>
                </div>
                <div>
                    <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                        <?php
                        // Build query string for pagination links
                        $queryParams = $_GET;
                        unset($queryParams['page']); // Remove current page so we can override it
                        $queryString = http_build_query($queryParams);
                        $baseUrl = "/finance/expenses?" . ($queryString ? $queryString . '&' : '') . "page=";
                        ?>
                        
                        <!-- Previous Page -->
                        <?php if ($page > 1): ?>
                            <a href="<?= $baseUrl . ($page - 1) ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        <?php else: ?>
                            <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        <?php endif; ?>

                        <!-- Page Numbers (Simplified logic: show around current page) -->
                        <?php
                        $startPage = max(1, $page - 2);
                        $endPage = min($totalPages, $page + 2);
                        
                        if ($startPage > 1) {
                            echo '<a href="' . $baseUrl . '1" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">1</a>';
                            if ($startPage > 2) {
                                echo '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>';
                            }
                        }

                        for ($i = $startPage; $i <= $endPage; $i++) {
                            if ($i == $page) {
                                echo '<span aria-current="page" class="z-10 bg-indigo-50 border-indigo-500 text-indigo-600 relative inline-flex items-center px-4 py-2 border text-sm font-medium">' . $i . '</span>';
                            } else {
                                echo '<a href="' . $baseUrl . $i . '" class="bg-white border-gray-300 text-gray-500 hover:bg-gray-50 relative inline-flex items-center px-4 py-2 border text-sm font-medium">' . $i . '</a>';
                            }
                        }

                        if ($endPage < $totalPages) {
                            if ($endPage < $totalPages - 1) {
                                echo '<span class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700">...</span>';
                            }
                            echo '<a href="' . $baseUrl . $totalPages . '" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-700 hover:bg-gray-50">' . $totalPages . '</a>';
                        }
                        ?>

                        <!-- Next Page -->
                        <?php if ($page < $totalPages): ?>
                            <a href="<?= $baseUrl . ($page + 1) ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                        <?php else: ?>
                            <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </span>
                        <?php endif; ?>
                    </nav>
                </div>
            </div>
        </div>
    </div>
    <?php endif; ?>
</div>

<style>
@media print {
    @page {
        size: landscape;
        margin: 10mm;
    }
    body, main {
        background-color: white !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Strip wrapper constraints for full bleed paper printing */
    .max-w-7xl, .mx-auto, .px-4, .py-6, .sm\:px-6, .lg\:px-8, .sm\:px-0, .sm\:p-6, .px-6, .py-5 {
        max-width: 100% !important;
        width: 100% !important;
        margin: 0 !important;
        padding: 0 !important;
    }
    
    /* Remove card styling to avoid "screenshot" look */
    .shadow, .sm\:rounded-lg, .overflow-hidden {
        box-shadow: none !important;
        border: none !important;
        border-radius: 0 !important;
        margin: 0 !important;
        padding: 0 !important;
    }

    .print\:hidden {
        display: none !important;
    }
    
    .print\:block {
        display: block !important;
    }
    
    /* Force logo rendering and alignment */
    .print-header img {
        display: block !important;
        max-height: 80px !important;
        width: auto !important;
        margin: 0 auto 15px auto !important;
        -webkit-print-color-adjust: exact !important; 
        print-color-adjust: exact !important;
    }

    /* Perfect table lines and contrast */
    table {
        border-collapse: collapse !important;
        width: 100% !important;
        table-layout: fixed !important;
    }
    
    th, td {
        border: 1px solid #e5e7eb !important;
        padding: 6px 8px !important;
        color: #000 !important;
        word-wrap: break-word !important;
        overflow-wrap: break-word !important;
    }
    
    /* Centered Text Helpers for specific columns */
    th:nth-child(3), td:nth-child(3) { text-align: center !important; } /* Ref Type */
    th:nth-child(5), td:nth-child(5) { text-align: center !important; } /* Credit In */
    th:nth-child(6), td:nth-child(6) { text-align: center !important; } /* Debit Out */
    th:nth-child(7), td:nth-child(7) { text-align: center !important; } /* Running Bal */

    /* Column specifically sized for better data distribution on paper */
    th:nth-child(1), td:nth-child(1) { width: 10% !important; }  /* Date */
    th:nth-child(2), td:nth-child(2) { width: 19% !important; } /* Txn Code */
    th:nth-child(3), td:nth-child(3) { width: 14% !important; } /* Ref Type */
    th:nth-child(4), td:nth-child(4) { width: 20% !important; } /* Description (Reduced) */
    th:nth-child(5), td:nth-child(5) { width: 12% !important; } /* Credit In */
    th:nth-child(6), td:nth-child(6) { width: 12% !important; } /* Debit Out */
    th:nth-child(7), td:nth-child(7) { width: 13% !important; } /* Running Bal */
    
    thead th {
        background-color: #f3f4f6 !important;
        -webkit-print-color-adjust: exact !important;
        print-color-adjust: exact !important;
        font-weight: bold !important;
    }

    /* Ensure specific data colors print properly */
    .text-green-600 { color: #059669 !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .text-red-600 { color: #dc2626 !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
    .bg-green-50 { background-color: #ecfdf5 !important; -webkit-print-color-adjust: exact !important; print-color-adjust: exact !important; }
}
</style>
