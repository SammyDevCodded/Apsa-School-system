<?php 
$title = 'Edit Payment'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Payment</h1>
            <a href="/payments" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Payments
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="/payments/<?= $payment['id'] ?>" method="POST" class="space-y-6">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                            <select name="student_id" id="student_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Student</option>
                                <?php foreach ($students ?? [] as $student): ?>
                                    <option value="<?= $student['id'] ?>" <?= (isset($payment['student_id']) && $payment['student_id'] == $student['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name'] . ' (' . $student['admission_no'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount ($)</label>
                            <input type="number" step="0.01" name="amount" id="amount" value="<?= htmlspecialchars($payment['amount'] ?? '') ?>" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <select name="method" id="method" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Method</option>
                                <option value="cash" <?= (isset($payment['method']) && $payment['method'] == 'cash') ? 'selected' : '' ?>>Cash</option>
                                <option value="cheque" <?= (isset($payment['method']) && $payment['method'] == 'cheque') ? 'selected' : '' ?>>Cheque</option>
                                <option value="bank_transfer" <?= (isset($payment['method']) && $payment['method'] == 'bank_transfer') ? 'selected' : '' ?>>Bank Transfer</option>
                            </select>
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" id="date" value="<?= htmlspecialchars($payment['date'] ?? date('Y-m-d')) ?>" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?= htmlspecialchars($payment['remarks'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>