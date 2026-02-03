<?php 
$title = 'Archive Record Details'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Archive Record Details</h1>
            <a href="/archives" class="text-indigo-600 hover:text-indigo-900">
                Back to Archive
            </a>
        </div>

        <!-- Archive Record Details -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Activity Details</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Detailed information about this archived system activity.</p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            User
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($auditLog['username'] ?? 'N/A') ?>
                            <?php if ($auditLog['first_name'] || $auditLog['last_name']): ?>
                            <div class="text-gray-500 text-xs">
                                <?= htmlspecialchars(($auditLog['first_name'] ?? '') . ' ' . ($auditLog['last_name'] ?? '')) ?>
                            </div>
                            <?php endif; ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Action
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php 
                                switch ($auditLog['action']) {
                                    case 'create':
                                        echo 'bg-green-100 text-green-800';
                                        break;
                                    case 'update':
                                        echo 'bg-blue-100 text-blue-800';
                                        break;
                                    case 'delete':
                                        echo 'bg-red-100 text-red-800';
                                        break;
                                    default:
                                        echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= ucfirst(htmlspecialchars($auditLog['action'])) ?>
                            </span>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Module
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars(ucwords(str_replace('_', ' ', $auditLog['table_name'] ?? 'N/A'))) ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Academic Year
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($auditLog['academic_year_name'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Term
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($auditLog['term'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Record ID
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($auditLog['record_id'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            IP Address
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($auditLog['ip_address'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            User Agent
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($auditLog['user_agent'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Date
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= date('M j, Y g:i A', strtotime($auditLog['created_at'])) ?>
                        </dd>
                    </div>
                    
                    <?php if (!empty($auditLog['old_values'])): ?>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Old Values
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <pre class="bg-gray-100 p-4 rounded-md overflow-x-auto"><?= htmlspecialchars(json_encode($auditLog['old_values'], JSON_PRETTY_PRINT)) ?></pre>
                        </dd>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($auditLog['new_values'])): ?>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            New Values
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <pre class="bg-gray-100 p-4 rounded-md overflow-x-auto"><?= htmlspecialchars(json_encode($auditLog['new_values'], JSON_PRETTY_PRINT)) ?></pre>
                        </dd>
                    </div>
                    <?php endif; ?>
                </dl>
            </div>
        </div>
        
        <!-- Formatted Data Section -->
        <?php if (!empty($auditLog['new_values']) || !empty($auditLog['old_values'])): ?>
        <div class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Formatted Data</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Human-readable representation of the changes.</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:p-6">
                    <?php if (!empty($auditLog['new_values'])): ?>
                    <h4 class="text-md font-medium text-gray-800 mb-3">Created/Updated Data</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($auditLog['new_values'] as $key => $value): ?>
                        <div class="border border-gray-200 rounded-md p-3">
                            <div class="text-sm font-medium text-gray-500"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $key))) ?></div>
                            <div class="mt-1 text-sm text-gray-900">
                                <?php if (is_array($value)): ?>
                                    <pre class="bg-gray-100 p-2 rounded"><?= htmlspecialchars(json_encode($value, JSON_PRETTY_PRINT)) ?></pre>
                                <?php else: ?>
                                    <?= htmlspecialchars($value ?? 'N/A') ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                    
                    <?php if (!empty($auditLog['old_values']) && $auditLog['action'] === 'update'): ?>
                    <h4 class="text-md font-medium text-gray-800 mt-6 mb-3">Previous Data</h4>
                    <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                        <?php foreach ($auditLog['old_values'] as $key => $value): ?>
                        <div class="border border-gray-200 rounded-md p-3">
                            <div class="text-sm font-medium text-gray-500"><?= htmlspecialchars(ucwords(str_replace('_', ' ', $key))) ?></div>
                            <div class="mt-1 text-sm text-gray-900">
                                <?php if (is_array($value)): ?>
                                    <pre class="bg-gray-100 p-2 rounded"><?= htmlspecialchars(json_encode($value, JSON_PRETTY_PRINT)) ?></pre>
                                <?php else: ?>
                                    <?= htmlspecialchars($value ?? 'N/A') ?>
                                <?php endif; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>