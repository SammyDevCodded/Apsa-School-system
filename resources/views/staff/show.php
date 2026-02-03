<?php 
$title = 'Staff Member Details'; 
// Only start output buffering if not being included in a modal
if (!isset($_GET['modal'])) {
    ob_start(); 
}
?>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <div class="flex justify-between items-center mb-4">
            <h3 class="text-lg leading-6 font-medium text-gray-900">
                <?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?>
            </h3>
            <button id="close-view-details-btn" class="text-gray-400 hover:text-gray-500">
                <span class="text-2xl">&times;</span>
            </button>
        </div>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Employee ID: <?= htmlspecialchars($staff['employee_id']) ?>
        </p>
    </div>
    <div class="border-t border-gray-200">
        <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Employee ID
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($staff['employee_id']) ?>
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Full Name
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?>
                </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Position
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?php if (!empty($staff['position'])): ?>
                        <?php 
                        // Split the position string and display each position as a badge
                        $positions = explode(',', $staff['position']);
                        foreach ($positions as $position): ?>
                            <span class="inline-block bg-indigo-100 text-indigo-800 text-xs px-2 py-1 rounded mr-1">
                                <?= htmlspecialchars(trim($position)) ?>
                            </span>
                        <?php endforeach; ?>
                    <?php else: ?>
                        <span class="text-gray-500">N/A</span>
                    <?php endif; ?>
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Department
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($staff['department'] ?? 'N/A') ?>
                </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Email
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($staff['email'] ?? 'N/A') ?>
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Phone
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($staff['phone'] ?? 'N/A') ?>
                </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Hire Date
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= isset($staff['hire_date']) ? date('F j, Y', strtotime($staff['hire_date'])) : 'N/A' ?>
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Salary
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= isset($staff['salary']) ? '₵' . number_format($staff['salary'], 2) : 'N/A' ?>
                </dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Status
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        <?= $staff['status'] === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                        <?= htmlspecialchars(ucfirst($staff['status'] ?? 'N/A')) ?>
                    </span>
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Date Added
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= isset($staff['created_at']) ? date('F j, Y', strtotime($staff['created_at'])) : 'N/A' ?>
                </dd>
            </div>
        </dl>
    </div>
</div>

<!-- Subjects Section for Teachers -->
<?php if (!empty($staff['position']) && $staff['position'] === 'Teacher'): ?>
<div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Subjects Taught
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Subjects this teacher is assigned to teach
        </p>
    </div>
    <div class="border-t border-gray-200">
        <?php if (empty($subjects)): ?>
            <div class="px-4 py-5 sm:px-6 text-center text-gray-500">
                No subjects assigned to this teacher.
            </div>
        <?php else: ?>
            <ul class="divide-y divide-gray-200">
                <?php foreach ($subjects as $subject): ?>
                    <li class="px-4 py-4 sm:px-6">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10 bg-indigo-100 rounded-full flex items-center justify-center">
                                    <span class="text-indigo-800 font-medium">
                                        <?= substr($subject['name'], 0, 1) ?>
                                    </span>
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($subject['name']) ?>
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        Code: <?= htmlspecialchars($subject['code']) ?>
                                    </div>
                                    <?php if (!empty($subject['class_name'])): ?>
                                    <div class="text-xs text-indigo-600 mt-1">
                                        Class: <?= htmlspecialchars($subject['class_name']) ?>
                                    </div>
                                    <?php endif; ?>
                                </div>
                            </div>
                            <div>
                                <a href="/subjects/<?= $subject['id'] ?>" class="inline-flex items-center px-3 py-1 border border-transparent text-sm font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200">
                                    View Subject
                                </a>
                            </div>
                        </div>
                    </li>
                <?php endforeach; ?>
            </ul>
        <?php endif; ?>
    </div>
</div>
<?php endif; ?>

<!-- Student Information for Teachers -->
<?php if (!empty($staff['position']) && $staff['position'] === 'Teacher' && !empty($subjects)): ?>
<div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">
            Academic Information
        </h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">
            Student information related to this teacher
        </p>
    </div>
    <div class="border-t border-gray-200">
        <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Total Students Handled
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= htmlspecialchars($studentCount ?? 0) ?>
                </dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">
                    Number of Subjects
                </dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <?= count($subjects) ?>
                </dd>
            </div>
        </dl>
    </div>
</div>
<?php endif; ?>

<div class="mt-6 flex justify-end">
    <button id="close-view-modal-btn" class="bg-gray-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
        Close
    </button>
    <button id="edit-staff-from-view-btn" data-staff-id="<?= $staff['id'] ?>" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
        Edit Staff Member
    </button>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const closeViewModalBtn = document.getElementById('close-view-modal-btn');
    const closeViewDetailsBtn = document.getElementById('close-view-details-btn');
    const editStaffFromViewBtn = document.getElementById('edit-staff-from-view-btn');
    
    // Close modal when close button is clicked
    if (closeViewModalBtn) {
        closeViewModalBtn.addEventListener('click', function() {
            document.getElementById('view-staff-modal').classList.add('hidden');
        });
    }
    
    // Close modal when X button is clicked
    if (closeViewDetailsBtn) {
        closeViewDetailsBtn.addEventListener('click', function() {
            document.getElementById('view-staff-modal').classList.add('hidden');
        });
    }
    
    // Edit staff member from view modal
    if (editStaffFromViewBtn) {
        editStaffFromViewBtn.addEventListener('click', function() {
            const staffId = this.getAttribute('data-staff-id');
            
            // Close view modal
            document.getElementById('view-staff-modal').classList.add('hidden');
            
            // Open edit modal after a short delay to allow the first modal to close
            setTimeout(function() {
                // Load the edit form content via AJAX
                fetch(`/staff/${staffId}/edit`)
                    .then(response => response.text())
                    .then(html => {
                        // Extract only the form content
                        const parser = new DOMParser();
                        const doc = parser.parseFromString(html, 'text/html');
                        const formContent = doc.querySelector('.bg-white.shadow.overflow-hidden');
                        if (formContent) {
                            document.getElementById('edit-staff-content').innerHTML = formContent.innerHTML;
                            document.getElementById('edit-staff-modal').classList.remove('hidden');
                            
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
                        console.error('Error loading edit staff form:', error);
                        document.getElementById('edit-staff-content').innerHTML = '<p class="text-red-500">Error loading form. Please try again.</p>';
                        document.getElementById('edit-staff-modal').classList.remove('hidden');
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