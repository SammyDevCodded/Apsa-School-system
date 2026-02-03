<?php 
$title = 'Edit Fee Structure'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Fee Structure</h1>
            <a href="/fees" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Fee Structures
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="/fees/<?= $fee['id'] ?>" method="POST" class="space-y-6">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Fee Name</label>
                            <input type="text" name="name" id="name" value="<?= htmlspecialchars($fee['name'] ?? '') ?>" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount ($)</label>
                            <input type="number" step="0.01" name="amount" id="amount" value="<?= htmlspecialchars($fee['amount'] ?? '') ?>" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Fee Type</label>
                            <select name="type" id="type" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Type</option>
                                <option value="tuition" <?= (isset($fee['type']) && $fee['type'] == 'tuition') ? 'selected' : '' ?>>Tuition</option>
                                <option value="transport" <?= (isset($fee['type']) && $fee['type'] == 'transport') ? 'selected' : '' ?>>Transport</option>
                                <option value="feeding" <?= (isset($fee['type']) && $fee['type'] == 'feeding') ? 'selected' : '' ?>>Feeding</option>
                                <option value="other" <?= (isset($fee['type']) && $fee['type'] == 'other') ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700">Class (Optional)</label>
                            <select name="class_id" id="class_id"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Classes</option>
                                <?php foreach ($classes ?? [] as $class): ?>
                                    <option value="<?= $class['id'] ?>" <?= (isset($fee['class_id']) && $fee['class_id'] == $class['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($class['name'] . ' (' . $class['level'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?= htmlspecialchars($fee['description'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Fee Structure
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