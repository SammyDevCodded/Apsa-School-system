<?php 
$title = 'Add Staff Member'; 
// Only start output buffering if not being included in a modal
if (!isset($_GET['modal'])) {
    ob_start(); 
}
?>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <form id="create-staff-form" action="/staff" method="POST" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="employee_id" class="block text-sm font-medium text-gray-700">Employee ID</label>
                    <div class="mt-1 flex rounded-md shadow-sm">
                        <input type="text" name="employee_id" id="employee_id" value="<?= htmlspecialchars($defaultEmployeeId ?? '') ?>" required
                            class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <button type="button" id="generate-employee-btn" class="inline-flex items-center px-3 rounded-r-md border border-l-0 border-gray-300 bg-gray-50 text-gray-500 text-sm hover:bg-gray-100">
                            Generate
                        </button>
                    </div>
                </div>

                <div>
                    <label for="position" class="block text-sm font-medium text-gray-700">Position</label>
                    <div class="mt-2 space-y-2">
                        <div class="flex items-center">
                            <input id="teaching" name="position" type="radio" value="Teacher" 
                                class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 position-radio">
                            <label for="teaching" class="ml-3 block text-sm text-gray-700">Teaching Staff</label>
                        </div>
                        <div class="flex items-center">
                            <input id="non-teaching" name="position" type="radio" value="Non-Teaching Staff" 
                                class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500 position-radio">
                            <label for="non-teaching" class="ml-3 block text-sm text-gray-700">Non-Teaching Staff</label>
                        </div>
                    </div>
                </div>

                <div>
                    <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                    <input type="text" name="first_name" id="first_name" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                    <input type="text" name="last_name" id="last_name" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="department" class="block text-sm font-medium text-gray-700">Department</label>
                    <input type="text" name="department" id="department"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email" name="email" id="email"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="phone" class="block text-sm font-medium text-gray-700">Phone</label>
                    <input type="text" name="phone" id="phone"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="hire_date" class="block text-sm font-medium text-gray-700">Hire Date</label>
                    <input type="date" name="hire_date" id="hire_date"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="salary" class="block text-sm font-medium text-gray-700">Salary</label>
                    <input type="number" name="salary" id="salary" step="0.01" min="0"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                    <select name="status" id="status"
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="active">Active</option>
                        <option value="inactive">Inactive</option>
                    </select>
                </div>

                <!-- Subjects section for teaching staff -->
                <div id="subjects-field" class="sm:col-span-2 hidden">
                    <label class="block text-sm font-medium text-gray-700">Subjects</label>
                    <p class="mt-1 text-sm text-gray-500">Select subjects this teacher will teach</p>
                    <div class="mt-2 grid grid-cols-1 sm:grid-cols-2 md:grid-cols-3 gap-4 max-h-60 overflow-y-auto p-2 border border-gray-300 rounded-md">
                        <?php if (empty($subjects)): ?>
                            <div class="text-gray-500 text-sm">No subjects available</div>
                        <?php else: ?>
                            <?php foreach ($subjects as $subject): ?>
                                <div class="flex items-center">
                                    <input type="checkbox" name="subject_ids[]" value="<?= $subject['id'] ?>" id="subject_<?= $subject['id'] ?>"
                                        class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    <label for="subject_<?= $subject['id'] ?>" class="ml-2 text-sm text-gray-700">
                                        <?= htmlspecialchars($subject['name']) ?>
                                        <span class="text-gray-500 text-xs block"><?= htmlspecialchars($subject['code']) ?></span>
                                    </label>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </div>
                    <p class="mt-1 text-sm text-gray-500">Or <a href="/subjects/create" target="_blank" class="text-indigo-600 hover:text-indigo-900">add a new subject</a></p>
                </div>
            </div>

            <div class="flex justify-end">
                <button type="button" id="cancel-create-btn" class="bg-gray-600 border border-transparent rounded-md shadow-sm py-2 px-4 inline-flex justify-center text-sm font-medium text-white hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-gray-500">
                    Cancel
                </button>
                <button type="submit"
                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Save Staff Member
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// Only initialize if not in a modal context or if specifically requested
if (typeof initializeCreateForm === 'undefined' || initializeCreateForm === true) {
    document.addEventListener('DOMContentLoaded', function() {
        const form = document.getElementById('create-staff-form');
        const cancelBtn = document.getElementById('cancel-create-btn');
        
        // Handle form submission
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(form);
                
                // Submit via AJAX
                fetch('/staff', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the modal and refresh the staff list
                        document.getElementById('add-staff-modal').classList.add('hidden');
                        location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'Unknown error occurred'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving the staff member.');
                });
            });
        }
        
        // Handle cancel button
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                document.getElementById('add-staff-modal').classList.add('hidden');
            });
        }
        
        // Initialize the generate button and position radios
        const generateBtn = document.getElementById('generate-employee-btn');
        const employeeInput = document.getElementById('employee_id');
        const positionRadios = document.querySelectorAll('.position-radio');
        const subjectsField = document.getElementById('subjects-field');
        const teachingRadio = document.getElementById('teaching');
        
        if (generateBtn && employeeInput) {
            generateBtn.addEventListener('click', function() {
                // Make AJAX request to generate new employee ID
                fetch('/settings/generate/employee')
                    .then(response => response.json())
                    .then(data => {
                        if (data.employee_id) {
                            employeeInput.value = data.employee_id;
                        }
                    })
                    .catch(error => {
                        console.error('Error generating employee ID:', error);
                        // Fallback to client-side generation
                        const prefix = 'StID'; // Default prefix
                        const timestamp = new Date().toLocaleTimeString('en-GB', { hour12: false }).replace(/:/g, '');
                        employeeInput.value = prefix + '-' + timestamp;
                    });
            });
        }
        
        // Handle position radio changes
        if (positionRadios.length > 0) {
            positionRadios.forEach(radio => {
                radio.addEventListener('change', function() {
                    if (this.value === 'Teacher' && this.checked) {
                        subjectsField.classList.remove('hidden');
                    } else {
                        subjectsField.classList.add('hidden');
                    }
                });
            });
        }
        
        // Show subject field if teaching position is already selected
        if (teachingRadio && teachingRadio.checked) {
            subjectsField.classList.remove('hidden');
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