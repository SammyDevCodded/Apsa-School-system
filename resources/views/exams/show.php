<?php 
$title = 'Exam Details'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Exam Details</h1>
            <div>
                <!-- Removed the Back to Exams link since it's handled by the modal -->
                <a href="#" onclick="openEditModal(<?= $exam['id'] ?>); return false;" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 mr-2">
                    Edit
                </a>
                <a href="#" onclick="closeViewModal(); return false;" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                    Close
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    <?= htmlspecialchars($exam['name']) ?>
                </h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">
                    Exam Date: <?= date('F j, Y', strtotime($exam['date'])) ?>
                </p>
            </div>
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Exam Name
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($exam['name']) ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Term
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php 
                                // Determine styling based on the term value
                                if (stripos($exam['term'], '1st') !== false || stripos($exam['term'], 'first') !== false) {
                                    echo 'bg-blue-100 text-blue-800';
                                } elseif (stripos($exam['term'], '2nd') !== false || stripos($exam['term'], 'second') !== false) {
                                    echo 'bg-green-100 text-green-800';
                                } elseif (stripos($exam['term'], '3rd') !== false || stripos($exam['term'], 'third') !== false) {
                                    echo 'bg-purple-100 text-purple-800';
                                } else {
                                    echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= htmlspecialchars($exam['term']) ?>
                            </span>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Classes
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?php if (isset($assignedClasses) && !empty($assignedClasses)): ?>
                                <ul class="list-disc pl-5">
                                    <?php foreach ($assignedClasses as $assignedClass): ?>
                                        <li><?= htmlspecialchars($assignedClass['name'] . ' (' . $assignedClass['level'] . ')') ?></li>
                                    <?php endforeach; ?>
                                </ul>
                            <?php else: ?>
                                <?= htmlspecialchars($class['name'] ?? 'N/A') ?>
                            <?php endif; ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Date
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= date('F j, Y', strtotime($exam['date'])) ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Description
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($exam['description'] ?? 'N/A') ?>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>
    </div>
</div>

<script>
// Function to open edit modal from within the view modal
function openEditModal(examId) {
    // Close the view modal first
    closeViewModal();
    // Then open the edit modal
    window.parent.openEditModal(examId);
}

// Function to close the view modal
function closeViewModal() {
    // This will be called from the parent window context
    if (window.parent && window.parent.closeViewModal) {
        window.parent.closeViewModal();
    } else {
        // Fallback if called directly
        const modal = document.getElementById('viewModal');
        if (modal) {
            modal.classList.add('hidden');
        }
    }
}
</script>

<?php 
$content = ob_get_clean();
if (isset($isAjax) && $isAjax) {
    echo $content;
} else {
    include RESOURCES_PATH . '/layouts/app.php';
}
?>