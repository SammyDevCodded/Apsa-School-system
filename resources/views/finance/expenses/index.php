<?php 
$title = 'Expense Tracking'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6 print:hidden">
            <h1 class="text-2xl font-semibold text-gray-900">Expense Tracking</h1>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Tabs Navigation -->
            <div class="border-b border-gray-200 print:hidden">
                <nav class="-mb-px flex" aria-label="Tabs">
                    <a href="?tab=dashboard" class="<?= $activeTab == 'dashboard' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> w-1/5 py-4 px-1 text-center border-b-2 font-medium text-sm">
                        Dashboard
                    </a>
                    <a href="?tab=expenses" class="<?= $activeTab == 'expenses' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> w-1/5 py-4 px-1 text-center border-b-2 font-medium text-sm">
                        Expenses
                    </a>
                    <a href="?tab=categories" class="<?= $activeTab == 'categories' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> w-1/5 py-4 px-1 text-center border-b-2 font-medium text-sm">
                        Categories
                    </a>
                    <a href="?tab=requests" class="<?= $activeTab == 'requests' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> w-1/5 py-4 px-1 text-center border-b-2 font-medium text-sm flex justify-center items-center">
                        Payment Requests
                        <?php if (isset($stats) && isset($pendingRequests) && $pendingRequests > 0): ?>
                            <span class="ml-2 bg-red-100 text-red-800 py-0.5 px-2 rounded-full text-xs font-medium"><?= $pendingRequests ?></span>
                        <?php endif; ?>
                    </a>
                    <a href="?tab=cashbook" class="<?= $activeTab == 'cashbook' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> w-1/5 py-4 px-1 text-center border-b-2 font-medium text-sm">
                        Cash Book
                    </a>
                </nav>
            </div>

            <div class="px-4 py-5 sm:p-6">
                <!-- Content Sections -->
                <?php if ($activeTab == 'dashboard'): ?>
                    <?php include __DIR__ . '/partials/dashboard.php'; ?>
                <?php elseif ($activeTab == 'expenses'): ?>
                    <?php include __DIR__ . '/partials/expenses_list.php'; ?>
                <?php elseif ($activeTab == 'categories'): ?>
                    <?php include __DIR__ . '/partials/categories.php'; ?>
                <?php elseif ($activeTab == 'requests'): ?>
                    <?php include __DIR__ . '/partials/requests.php'; ?>
                <?php elseif ($activeTab == 'cashbook'): ?>
                    <?php include __DIR__ . '/partials/cashbook.php'; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>
