<?php 
$title = 'Edit Subject'; 
// Only start output buffering if not being included in a modal
if (!isset($_GET['modal'])) {
    ob_start(); 
}
?>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <form id="edit-subject-form" action="/subjects/<?= $subject['id'] ?>" method="POST" class="space-y-6">
            <input type="hidden" name="_method" value="PUT">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="code" class="block text-sm font-medium text-gray-700">Subject Code</label>
                    <input type="text" name="code" id="code" value="<?= htmlspecialchars($subject['code'] ?? '') ?>" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="name" class="block text-sm font-medium text-gray-700">Subject Name</label>
                    <input type="text" name="name" id="name" value="<?= htmlspecialchars($subject['name'] ?? '') ?>" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                    <select name="class_id" id="class_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select Class</option>
                        <?php foreach ($classes ?? [] as $class): ?>
                            <option value="<?= $class['id'] ?>" <?= (isset($subject['class_id']) && $subject['class_id'] == $class['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($class['name'] . ' (' . $class['level'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div class="sm:col-span-2">
                    <label class="block text-sm font-medium text-gray-700">Teachers</label>
                    <p class="mt-1 text-sm text-gray-500">Select teachers who will teach this subject</p>
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-60 overflow-y-auto p-2 border border-gray-300 rounded-md">
                        <?php if (empty($staff)): ?>
                            <div class="text-gray-500 text-sm">No staff members available</div>
                        <?php else: ?>
                            <?php foreach ($staff as $member): ?>
                                <?php if (!empty($member['position']) && strpos($member['position'], 'Teacher') !== false): ?>
                                    <div class="flex items-center">
                                        <input type="checkbox" name="staff_ids[]" value="<?= $member['id'] ?>" id="staff_<?= $member['id'] ?>"
                                            <?= (in_array($member['id'], $assignedStaffIds ?? [])) ? 'checked' : '' ?>
                                            class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                        <label for="staff_<?= $member['id'] ?>" class="ml-2 text-sm text-gray-700">
                                            <?= htmlspecialchars($member['first_name'] . ' ' . $member['last_name']) ?>
                                            <span class="text-gray-500 text-xs block"><?= htmlspecialchars($member['employee_id']) ?></span>
                                        </label>
                                    </div>
                                <?php endif; ?>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                </div>

                <div class="sm:col-span-2">
                    <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                    <textarea name="description" id="description" rows="3"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?= htmlspecialchars($subject['description'] ?? '') ?></textarea>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="button" id="cancel-edit-btn" class="bg-gray-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancel
                </button>
                <button type="submit"
                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Update Subject
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Only initialize if not in a modal context or if specifically requested
if (typeof initializeEditForm === 'undefined' || initializeEditForm === true) {
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('edit-subject-form');
        const cancelBtn = document.getElementById('cancel-edit-btn');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(form);
                
                // Submit via AJAX
                fetch('/subjects/<?= $subject['id'] ?>', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the modal and refresh the subject list
                        document.getElementById('edit-subject-modal').classList.add('hidden');
                        location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'Unknown error occurred'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the subject.');
                });
            });
        }
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                document.getElementById('edit-subject-modal').classList.add('hidden');
            });
        }
    });
}
</script>

<?php 
// Only end output buffering if not being included in a modal
if (!isset($_GET['modal'])) {
    $content = ob_get_clean();
    include RESOURCES_PATH . '/layouts/app.php';
}
?>