<?php 
$title = 'Notifications'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Notifications</h1>
            <?php if ($unreadCount > 0): ?>
            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-red-100 text-red-800">
                <?= $unreadCount ?> unread
            </span>
            <?php endif; ?>
        </div>

        <!-- Notifications List -->
        <div class="bg-white shadow overflow-hidden sm:rounded-md">
            <ul class="divide-y divide-gray-200">
                <?php if (empty($notifications)): ?>
                <li class="px-6 py-4">
                    <div class="text-center">
                        <p class="text-gray-500">No notifications found.</p>
                    </div>
                </li>
                <?php else: ?>
                <?php foreach ($notifications as $notification): ?>
                <li class="<?= $notification['is_read'] ? 'bg-white' : 'bg-blue-50' ?>">
                    <div class="px-6 py-4">
                        <div class="flex items-center justify-between">
                            <p class="text-sm font-medium <?= $notification['is_read'] ? 'text-gray-900' : 'text-blue-600' ?>">
                                <?= htmlspecialchars($notification['message']) ?>
                            </p>
                            <?php if (!$notification['is_read']): ?>
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                New
                            </span>
                            <?php endif; ?>
                        </div>
                        <div class="mt-2 flex items-center text-sm text-gray-500">
                            <span>
                                <?= date('M j, Y g:i A', strtotime($notification['created_at'])) ?>
                            </span>
                            <?php if ($notification['type']): ?>
                            <span class="ml-2 inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-800">
                                <?= ucfirst(htmlspecialchars($notification['type'])) ?>
                            </span>
                            <?php endif; ?>
                        </div>
                    </div>
                </li>
                <?php endforeach; ?>
                <?php endif; ?>
            </ul>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>