<?php 
$title = 'Import Preview'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Import Preview</h1>
            <a href="/settings/import" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Import
            </a>
        </div>

        <!-- Preview Data -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <?= ucfirst($type ?? 'Data') ?> Import Preview
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Review the data before importing. <?= count($data ?? []) ?> records found.
                </p>
            </div>
            <div class="border-t border-gray-200">
                <?php if (empty($data)): ?>
                    <div class="px-4 py-5 sm:p-6 text-center text-gray-500">
                        No data to preview.
                    </div>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <?php 
                                    // Get headers from the first row
                                    $headers = array_keys($data[0] ?? []);
                                    foreach ($headers as $header): 
                                    ?>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            <?= htmlspecialchars($header) ?>
                                        </th>
                                    <?php endforeach; ?>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($data as $index => $row): ?>
                                    <tr>
                                        <?php foreach ($headers as $header): ?>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= htmlspecialchars($row[$header] ?? '') ?>
                                            </td>
                                        <?php endforeach; ?>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                    
                    <div class="px-4 py-4 bg-gray-50 sm:px-6">
                        <div class="flex items-center justify-between">
                            <p class="text-sm text-gray-700">
                                Showing <?= count($data) ?> records
                            </p>
                            <form action="/settings/import/<?= htmlspecialchars($type ?? 'data') ?>" method="POST" enctype="multipart/form-data">
                                <button type="submit"
                                    class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Confirm and Import
                                </button>
                            </form>
                        </div>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>