<?php 
$title = 'Payment Details'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Payment Details</h1>
            <div>
                <a href="/payments/<?= $payment['id'] ?>/edit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 mr-2">
                    Edit
                </a>
                <a href="/payments/<?= $payment['id'] ?>/delete" class="bg-red-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-red-700"
                   onclick="return confirm('Are you sure you want to delete this payment record?')">
                    Delete
                </a>
            </div>
        </div>

        <!-- Payment Details -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Payment Record
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Payment Amount: 
                    <?php
                    // Include currency helper
                    require_once APP_PATH . '/Helpers/CurrencyHelper.php';
                    echo \App\Helpers\CurrencyHelper::formatAmount($payment['amount']);
                    ?>
                </p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Student
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Admission No
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['admission_no'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Amount
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?php
                            // Include currency helper
                            require_once APP_PATH . '/Helpers/CurrencyHelper.php';
                            echo \App\Helpers\CurrencyHelper::formatAmount($payment['amount']);
                            ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Payment Method
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php 
                                switch($payment['method']) {
                                    case 'cash': echo 'bg-green-100 text-green-800'; break;
                                    case 'cheque': echo 'bg-blue-100 text-blue-800'; break;
                                    case 'bank_transfer': echo 'bg-purple-100 text-purple-800'; break;
                                    default: echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $payment['method']))) ?>
                            </span>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Date
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= date('F j, Y', strtotime($payment['date'])) ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Remarks
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($payment['remarks'] ?? 'N/A') ?>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>