<?php 
$title = 'Student Promotions'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Header -->
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Student Promotions</h1>
            <div class="flex space-x-2">
                <a href="/promotions/history" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                    Promotion History
                </a>
            </div>
        </div>

        <!-- Status notifications are delivered via the global app.php Toast system -->

        <!-- Promotion Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Bulk Student Promotion</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Promote students from one class to another in bulk</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <form id="promotionForm" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <!-- Source Class Selection -->
                        <div>
                            <label for="from_class_id" class="block text-sm font-medium text-gray-700">From Class *</label>
                            <select name="from_class_id" id="from_class_id" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">Select source class</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name']) ?> (<?= htmlspecialchars($class['level']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Target Class Selection -->
                        <div>
                            <label for="to_class_id" class="block text-sm font-medium text-gray-700">To Class *</label>
                            <select name="to_class_id" id="to_class_id" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">Select target class</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name']) ?> (<?= htmlspecialchars($class['level']) ?>)</option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Academic Year Selection -->
                        <div>
                            <label for="academic_year_id" class="block text-sm font-medium text-gray-700">Academic Year *</label>
                            <select name="academic_year_id" id="academic_year_id" 
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">Select academic year</option>
                                <?php foreach ($academicYears as $year): ?>
                                    <option value="<?= $year['id'] ?>" <?= $year['is_current'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($year['name']) ?> 
                                        <?= $year['term'] ? '(' . htmlspecialchars($year['term']) . ')' : '' ?>
                                        <?= $year['is_current'] ? '(Current)' : '' ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Promotion Date -->
                        <div>
                            <label for="promotion_date" class="block text-sm font-medium text-gray-700">Promotion Date *</label>
                            <input type="date" name="promotion_date" id="promotion_date" 
                                   value="<?= date('Y-m-d') ?>"
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                        </div>
                    </div>

                    <!-- Remarks -->
                    <div>
                        <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks (Optional)</label>
                        <textarea name="remarks" id="remarks" rows="3"
                                  class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                  placeholder="Enter any additional remarks about this promotion..."></textarea>
                    </div>

                    <!-- Student Selection Section -->
                    <div id="studentSelectionSection" class="hidden">
                        <div class="border-t border-gray-200 pt-6">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Select Students to Promote</h4>
                            
                            <!-- Select All Checkbox -->
                            <div class="mb-4">
                                <label class="inline-flex items-center">
                                    <input type="checkbox" id="selectAllCheckbox" class="rounded border-gray-300 text-indigo-600 shadow-sm focus:border-indigo-300 focus:ring focus:ring-indigo-200 focus:ring-opacity-50">
                                    <span class="ml-2 text-sm text-gray-600">Select All Students</span>
                                </label>
                            </div>

                            <!-- Students List -->
                            <div id="studentsContainer" class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4 max-h-96 overflow-y-auto border border-gray-200 rounded-md p-4">
                                <!-- Students will be loaded here dynamically -->
                                <div class="text-center text-gray-500 col-span-full py-8">
                                    <p>Select a source class to load students</p>
                                </div>
                            </div>

                            <!-- Selected Count -->
                            <div class="mt-4 text-sm text-gray-600">
                                Selected: <span id="selectedCount">0</span> students
                            </div>
                        </div>
                    </div>

                    <!-- Submit Button -->
                    <div class="flex justify-end pt-6 border-t border-gray-200">
                        <button type="submit" id="promoteButton" disabled
                                class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50 disabled:cursor-not-allowed">
                            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                            </svg>
                            Promote Selected Students
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Recent Promotions Preview -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Recent Promotions</h3>
                    <a href="/promotions/history" class="text-sm text-indigo-600 hover:text-indigo-500">
                        View All History
                    </a>
                </div>
            </div>
            <div class="border-t border-gray-200">
                <div class="px-4 py-5 sm:px-6 text-center text-gray-500">
                    <p>Promotion history will appear here after promotions are made.</p>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const fromClassSelect = document.getElementById('from_class_id');
    const toClassSelect = document.getElementById('to_class_id');
    const academicYearSelect = document.getElementById('academic_year_id');
    const promotionDateInput = document.getElementById('promotion_date');
    const remarksInput = document.getElementById('remarks');
    const studentSelectionSection = document.getElementById('studentSelectionSection');
    const studentsContainer = document.getElementById('studentsContainer');
    const selectAllCheckbox = document.getElementById('selectAllCheckbox');
    const selectedCountSpan = document.getElementById('selectedCount');
    const promoteButton = document.getElementById('promoteButton');
    const promotionForm = document.getElementById('promotionForm');

    let studentsData = [];

    // Load students when source class is selected
    fromClassSelect.addEventListener('change', function() {
        const classId = this.value;
        
        if (classId) {
            loadStudents(classId);
        } else {
            studentSelectionSection.classList.add('hidden');
            studentsContainer.innerHTML = '<div class="text-center text-gray-500 col-span-full py-8"><p>Select a source class to load students</p></div>';
            updateSelectedCount();
        }
    });

    // Load students via AJAX
    function loadStudents(classId) {
        studentsContainer.innerHTML = '<div class="text-center text-gray-500 col-span-full py-8"><p>Loading students...</p></div>';
        
        fetch(`/promotions/students-by-class?class_id=${classId}`)
            .then(response => response.json())
            .then(data => {
                if (data.students && data.students.length > 0) {
                    studentsData = data.students;
                    renderStudents(studentsData);
                    studentSelectionSection.classList.remove('hidden');
                } else {
                    studentsContainer.innerHTML = '<div class="text-center text-gray-500 col-span-full py-8"><p>No students found in this class</p></div>';
                    studentSelectionSection.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error loading students:', error);
                studentsContainer.innerHTML = '<div class="text-center text-red-500 col-span-full py-8"><p>Error loading students</p></div>';
            });
    }

    // Render students in the container
    function renderStudents(students) {
        studentsContainer.innerHTML = '';
        
        students.forEach(student => {
            const studentDiv = document.createElement('div');
            studentDiv.className = 'flex items-center p-3 bg-gray-50 rounded-md hover:bg-gray-100';
            studentDiv.innerHTML = `
                <input type="checkbox" 
                       name="student_ids[]" 
                       value="${student.id}" 
                       class="student-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                <div class="ml-3 flex-1 min-w-0">
                    <p class="text-sm font-medium text-gray-900 truncate">
                        ${student.first_name} ${student.last_name}
                    </p>
                    <p class="text-sm text-gray-500 truncate">
                        ${student.admission_no}
                    </p>
                </div>
            `;
            studentsContainer.appendChild(studentDiv);
        });

        // Add event listeners to checkboxes
        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', updateSelectedCount);
        });

        updateSelectedCount();
    }

    // Update selected count
    function updateSelectedCount() {
        const selectedCheckboxes = document.querySelectorAll('.student-checkbox:checked');
        selectedCountSpan.textContent = selectedCheckboxes.length;
        
        // Enable/disable promote button based on selections
        promoteButton.disabled = selectedCheckboxes.length === 0;
    }

    // Select all functionality
    selectAllCheckbox.addEventListener('change', function() {
        const checkboxes = document.querySelectorAll('.student-checkbox');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
        updateSelectedCount();
    });

    // Handle form submission
    promotionForm.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Get form data
        const formData = new FormData();
        formData.append('from_class_id', fromClassSelect.value);
        formData.append('to_class_id', toClassSelect.value);
        formData.append('academic_year_id', academicYearSelect.value);
        formData.append('promotion_date', promotionDateInput.value);
        formData.append('remarks', remarksInput.value);
        
        // Add selected student IDs
        const selectedCheckboxes = document.querySelectorAll('.student-checkbox:checked');
        selectedCheckboxes.forEach(checkbox => {
            formData.append('student_ids[]', checkbox.value);
        });

        // Disable button and show loading state
        promoteButton.disabled = true;
        promoteButton.innerHTML = `
            <svg class="animate-spin -ml-1 mr-2 h-5 w-5 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
            </svg>
            Processing...
        `;

        // Submit via AJAX
        fetch('/promotions/promote', {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                showToast(`${data.message}`, 'success');
                
                // Reset form
                promotionForm.reset();
                studentSelectionSection.classList.add('hidden');
                studentsContainer.innerHTML = '<div class="text-center text-gray-500 col-span-full py-8"><p>Select a source class to load students</p></div>';
                selectAllCheckbox.checked = false;
                selectedCountSpan.textContent = '0';
                
                // Reload students if same class is selected
                if (fromClassSelect.value) {
                    loadStudents(fromClassSelect.value);
                }
            } else {
                showToast(data.error || 'Failed to promote students', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showToast('An error occurred while promoting students', 'error');
        })
        .finally(() => {
            // Reset button
            promoteButton.disabled = false;
            promoteButton.innerHTML = `
                <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                    <path fill-rule="evenodd" d="M16.707 5.293a1 1 0 010 1.414l-8 8a1 1 0 01-1.414 0l-4-4a1 1 0 011.414-1.414L8 12.586l7.293-7.293a1 1 0 011.414 0z" clip-rule="evenodd" />
                </svg>
                Promote Selected Students
            `;
        });
    });
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>