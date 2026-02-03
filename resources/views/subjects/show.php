<?php 
$title = 'Subject Details'; 
// Only start output buffering if not being included in a modal
if (!isset($_GET['modal'])) {
    ob_start(); 
}
?>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?= htmlspecialchars($subject['name']) ?>
            </h3>
            <button id="close-view-details-btn" class="text-gray-400 hover:text-gray-500">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Subject Code: <?= htmlspecialchars($subject['code']) ?>
        </p>
    </div>
    <div class="border-t border-gray-200">
        <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Subject Code
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($subject['code']) ?>
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Subject Name
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($subject['name']) ?>
                </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Class
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($subject['class_name'] ?? 'N/A') ?>
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Description
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($subject['description'] ?? 'N/A') ?>
                </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Date Added
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= isset($subject['created_at']) ? date('F j, Y', strtotime($subject['created_at'])) : 'N/A' ?>
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Number of Students
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($subject['student_count'] ?? 0) ?>
                </dd>
            </div>
        </dl>
    </div>
</div>

<!-- Subject Assignments -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Subject Assignments
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Classes and students assigned to this subject
        </p>
    </div>
    <div class="border-t border-gray-200">
        <?php if (empty($subject['assignments'])): ?>
            <div class="px-4 py-5 sm:px-6 text-center text-gray-500">
                This subject is not assigned to any classes or students yet.
            </div>
        <?php else: ?>
            <div class="px-4 py-5 sm:px-6">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Class
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Assignment Type
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($subject['assignments'] as $assignment): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($assignment['class_name'] ?? 'N/A') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php if ($assignment['student_id']): ?>
                                            <?= htmlspecialchars(($assignment['first_name'] ?? '') . ' ' . ($assignment['last_name'] ?? '')) ?> 
                                            (<?= htmlspecialchars($assignment['admission_no'] ?? '') ?>)
                                        <?php else: ?>
                                            <span class="text-gray-500">All students in class</span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?php if ($assignment['student_id']): ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                Specific Student
                                            </span>
                                        <?php else: ?>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                Entire Class
                                            </span>
                                        <?php endif; ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<!-- Teachers Section -->
<div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Teachers
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Teachers assigned to teach this subject
        </p>
    </div>
    <div class="border-t border-gray-200">
        <?php if (empty($subject['teachers'])): ?>
            <div class="px-4 py-5 sm:px-6 text-center text-gray-500">
                No teachers assigned to this subject.
            </div>
        <?php else: ?>
            <ul class="divide-y divide-gray-200">
                <?php foreach ($subject['teachers'] as $teacher): ?>
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-indigo-800 font-medium">
                                        <?= substr($teacher['first_name'], 0, 1) . substr($teacher['last_name'], 0, 1) ?>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($teacher['first_name'] . ' ' . $teacher['last_name']) ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Employee ID: <?= htmlspecialchars($teacher['employee_id']) ?>
                                    </div>
                                </div>
                            </div>
                            <div>
                                <a href="/staff/<?= $teacher['id'] ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                    View Profile
                                </a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>

<div class="mt-6 flex justify-end">
    <button id="close-view-modal-btn" class="bg-gray-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
        Close
    </button>
    <button id="edit-subject-from-view-btn" data-subject-id="<?= $subject['id'] ?>" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        Edit Subject
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const closeViewModalBtn = document.getElementById('close-view-modal-btn');
    const closeViewDetailsBtn = document.getElementById('close-view-details-btn');
    const editSubjectFromViewBtn = document.getElementById('edit-subject-from-view-btn');
    
    // Close modal when close button is clicked
    if (closeViewModalBtn) {
        closeViewModalBtn.addEventListener('click', function() {
            document.getElementById('view-subject-modal').classList.add('hidden');
        });
    }
    
    // Close modal when X button is clicked
    if (closeViewDetailsBtn) {
        closeViewDetailsBtn.addEventListener('click', function() {
            document.getElementById('view-subject-modal').classList.add('hidden');
        });
    }
    
    // Edit subject from view modal
    if (editSubjectFromViewBtn) {
        editSubjectFromViewBtn.addEventListener('click', function() {
            const subjectId = this.getAttribute('data-subject-id');
            
            // Close view modal
            document.getElementById('view-subject-modal').classList.add('hidden');
            
            // Open edit modal after a short delay to allow the first modal to close
            setTimeout(function() {
                // Load the edit form content via AJAX
                fetch(`/subjects/${subjectId}/edit`)
                    .then(response => response.text())
                    .then(html => {
                        // Extract only the form content
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const formContent = doc.querySelector('.bg-white.shadow.overflow-hidden');
                        if (formContent) {
                            document.getElementById('edit-subject-content').innerHTML = formContent.innerHTML;
                            document.getElementById('edit-subject-modal').classList.remove('hidden');
                            
                            // Re-initialize any JavaScript needed for the form
                            // Set flag to initialize the form
                            if (typeof initializeEditForm !== 'undefined') {
                                initializeEditForm = true;
                            }
                            // Dispatch a custom event to trigger form initialization
                            document.dispatchEvent(new CustomEvent('editFormLoaded'));
                        }
                    })
                    .catch(error => {
                        console.error('Error loading edit subject form:', error);
                        document.getElementById('edit-subject-content').innerHTML = '<p class="text-red-500">Error loading form. Please try again.</p>';
                        document.getElementById('edit-subject-modal').classList.remove('hidden');
                    });
            }, 300);
        });
    }
});
</script>

<?php 
// Only end output buffering if not being included in a modal
if (!isset($_GET['modal'])) {
    $content = ob_get_clean();
    include RESOURCES_PATH . '/layouts/app.php';
}
?>