<?php 
$title = 'Assign Students to Fee Structure'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Assign Students to Fee Structure</h1>
            <a href="/fees" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Fee Structures
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Fee Structure Details</h3>
                <div class="mt-4 grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Name</label>
                        <p class="mt-1 text-sm text-gray-900"><?= htmlspecialchars($fee['name']) ?></p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Amount</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <?php
                            // Include currency helper
                            require_once APP_PATH . '/Helpers/CurrencyHelper.php';
                            echo \App\Helpers\CurrencyHelper::formatAmount($fee['amount']);
                            ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Type</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                <?php 
                                switch($fee['type']) {
                                    case 'tuition': echo 'bg-blue-100 text-blue-800'; break;
                                    case 'transport': echo 'bg-green-100 text-green-800'; break;
                                    case 'feeding': echo 'bg-yellow-100 text-yellow-800'; break;
                                    default: echo 'bg-gray-100 text-gray-800';
                                }
                                ?>">
                                <?= htmlspecialchars(ucfirst($fee['type'])) ?>
                            </span>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <form action="/fees/<?= $fee['id'] ?>/assign" method="POST" class="space-y-6">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Select Classes</h3>
                    <div class="mb-2">
                        <div class="flex items-center">
                            <input type="checkbox" id="select-all-classes" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="select-all-classes" class="ml-2 block text-sm text-gray-900">
                                Select All Classes
                            </label>
                        </div>
                    </div>
                    <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                        <?php foreach ($classes as $class): ?>
                            <div class="flex items-center">
                                <input type="checkbox" name="selected_classes[]" value="<?= $class['id'] ?>" 
                                    id="class_<?= $class['id'] ?>" class="h-4 w-4 text-indigo-600 border-gray-300 rounded class-checkbox"
                                    <?= in_array($class['id'], $selectedClassIds ?? []) ? 'checked' : '' ?>>
                                <label for="class_<?= $class['id'] ?>" class="ml-2 block text-sm text-gray-900">
                                    <?= htmlspecialchars($class['name']) ?>
                                </label>
                            </div>
                        <?php endforeach; ?>
                    </div>
                    <div class="mt-4">
                        <button type="button" id="load-students-btn" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Load Students
                        </button>
                    </div>
                </div>
            </div>

            <div class="bg-white shadow overflow-hidden sm:rounded-lg" id="students-section" style="display: none;">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Select Students</h3>
                    <div class="mb-4">
                        <button type="button" id="select-all-btn" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Select All
                        </button>
                        <button type="button" id="deselect-all-btn" class="ml-2 inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Deselect All
                        </button>
                    </div>
                    <div id="students-container" class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-3">
                        <!-- Students will be loaded here dynamically -->
                    </div>
                </div>
            </div>

            <div class="flex justify-end">
                <a href="/fees" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                    Cancel
                </a>
                <button type="submit" id="submit-btn" disabled
                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 disabled:opacity-50">
                    Assign Selected Students
                </button>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const classCheckboxes = document.querySelectorAll('.class-checkbox');
    const selectAllClasses = document.getElementById('select-all-classes');
    const loadStudentsBtn = document.getElementById('load-students-btn');
    const studentsSection = document.getElementById('students-section');
    const studentsContainer = document.getElementById('students-container');
    const selectAllBtn = document.getElementById('select-all-btn');
    const deselectAllBtn = document.getElementById('deselect-all-btn');
    const submitBtn = document.getElementById('submit-btn');
    const form = document.querySelector('form');
    
    // Store previously loaded students to detect new additions
    let previouslyLoadedStudents = [];
    
    // Select All functionality for classes
    selectAllClasses.addEventListener('change', function() {
        classCheckboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Update Select All checkbox state when individual checkboxes are changed
    classCheckboxes.forEach(checkbox => {
        checkbox.addEventListener('change', function() {
            const allChecked = Array.from(classCheckboxes).every(cb => cb.checked);
            selectAllClasses.checked = allChecked;
        });
    });
    
    // Load students when button is clicked
    loadStudentsBtn.addEventListener('click', function() {
        loadStudentsForSelectedClasses();
    });
    
    // Auto-refresh students every 30 seconds to catch new additions
    setInterval(function() {
        if (studentsSection.style.display !== 'none') {
            loadStudentsForSelectedClasses(true); // Silent refresh
        }
    }, 30000); // 30 seconds
    
    // Handle form submission
    form.addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        submitBtn.disabled = true;
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Assigning...';
        
        // Submit form via AJAX
        const formData = new FormData(form);
        
        fetch(form.action, {
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
                alert('Students assigned successfully!');
                // Redirect to fees page
                window.location.href = '/fees';
            } else {
                // Show error message
                alert('Error: ' + (data.error || 'Failed to assign students'));
                // Reset button
                submitBtn.disabled = false;
                submitBtn.textContent = originalText;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred while assigning students. Please try again.');
            // Reset button
            submitBtn.disabled = false;
            submitBtn.textContent = originalText;
        });
    });
    
    // Load students for selected classes
    function loadStudentsForSelectedClasses(silent = false) {
        const selectedClassIds = Array.from(classCheckboxes)
            .filter(checkbox => checkbox.checked)
            .map(checkbox => checkbox.value);
        
        if (selectedClassIds.length === 0) {
            if (!silent) {
                alert('Please select at least one class');
            }
            return;
        }
        
        // Show loading state only for non-silent refreshes
        if (!silent) {
            loadStudentsBtn.disabled = true;
            loadStudentsBtn.textContent = 'Loading...';
            studentsContainer.innerHTML = '<p class="text-gray-500">Loading students...</p>';
            studentsSection.style.display = 'block';
        }
        
        // Prepare form data
        const formData = new FormData();
        selectedClassIds.forEach(id => formData.append('class_ids[]', id));
        
        // Make AJAX request to get real students from database
        fetch('/fees/students-by-classes', {
            method: 'POST',
            body: formData
        })
        .then(response => response.json())
        .then(data => {
            // Hide loading state only for non-silent refreshes
            if (!silent) {
                loadStudentsBtn.disabled = false;
                loadStudentsBtn.textContent = 'Load Students';
            }
            
            if (data.success) {
                // For silent refreshes, check if there are new students
                if (silent) {
                    const currentStudentIds = [];
                    Object.values(data.students_by_class).forEach(classData => {
                        classData.students.forEach(student => {
                            currentStudentIds.push(student.id.toString());
                        });
                    });
                    
                    // Check if there are new students
                    const newStudents = currentStudentIds.filter(id => 
                        !previouslyLoadedStudents.includes(id)
                    );
                    
                    if (newStudents.length > 0) {
                        // Show notification about new students
                        showNewStudentsNotification(newStudents.length);
                    }
                }
                
                // Update the previously loaded students list
                previouslyLoadedStudents = [];
                Object.values(data.students_by_class).forEach(classData => {
                    classData.students.forEach(student => {
                        previouslyLoadedStudents.push(student.id.toString());
                    });
                });
                
                generateStudentCheckboxes(data.students_by_class, silent);
            } else {
                if (!silent) {
                    studentsContainer.innerHTML = '<p class="text-red-500">Error loading students: ' + (data.error || 'Unknown error') + '</p>';
                }
            }
        })
        .catch(error => {
            // Hide loading state only for non-silent refreshes
            if (!silent) {
                loadStudentsBtn.disabled = false;
                loadStudentsBtn.textContent = 'Load Students';
                studentsContainer.innerHTML = '<p class="text-red-500">Error loading students: ' + error.message + '</p>';
            }
        });
    }
    
    // Show notification about new students
    function showNewStudentsNotification(count) {
        // Create notification element
        const notification = document.createElement('div');
        notification.className = 'bg-blue-100 border border-blue-400 text-blue-700 px-4 py-3 rounded relative mb-4';
        notification.role = 'alert';
        notification.innerHTML = `
            <strong class="font-bold">New students detected! </strong>
            <span class="block sm:inline">${count} new student(s) have been added to the selected classes. They are automatically checked below.</span>
        `;
        
        // Insert notification at the top of students container
        const firstChild = studentsContainer.firstChild;
        studentsContainer.insertBefore(notification, firstChild);
        
        // Auto-remove notification after 5 seconds
        setTimeout(() => {
            if (notification.parentNode) {
                notification.parentNode.removeChild(notification);
            }
        }, 5000);
    }
    
    // Select all students
    selectAllBtn.addEventListener('click', function() {
        document.querySelectorAll('.student-checkbox:not(:disabled)').forEach(checkbox => {
            checkbox.checked = true;
        });
        updateSubmitButton();
    });
    
    // Deselect all students
    deselectAllBtn.addEventListener('click', function() {
        document.querySelectorAll('.student-checkbox:not(:disabled)').forEach(checkbox => {
            checkbox.checked = false;
        });
        updateSubmitButton();
    });
    
    // Update submit button state
    function updateSubmitButton() {
        const selectedStudents = document.querySelectorAll('.student-checkbox:checked');
        submitBtn.disabled = selectedStudents.length === 0;
    }
    
    // Generate student checkboxes (this now uses real data)
    function generateStudentCheckboxes(studentsByClass, silent = false) {
        // Clear existing content only for non-silent refreshes
        if (!silent) {
            studentsContainer.innerHTML = '';
        }
        
        // Check if we have any classes with students
        const hasStudents = Object.keys(studentsByClass).length > 0;
        
        if (!hasStudents) {
            if (!silent) {
                studentsContainer.innerHTML = '<p class="text-gray-500 col-span-full text-center">No students found in selected classes.</p>';
            }
            updateSubmitButton();
            return;
        }
        
        // For silent refreshes, we need to update existing content rather than replace it
        if (silent) {
            updateExistingStudentCheckboxes(studentsByClass);
        } else {
            // Create a container for all class tables
            const tablesContainer = document.createElement('div');
            tablesContainer.className = 'space-y-6';
            
            // Create a separate table for each class
            Object.entries(studentsByClass).forEach(([classId, classData]) => {
                // Create table for this class
                const tableWrapper = document.createElement('div');
                tableWrapper.className = 'bg-white shadow overflow-hidden sm:rounded-lg';
                
                // Table header with class name
                const tableHeader = document.createElement('div');
                tableHeader.className = 'px-4 py-3 bg-gray-50 border-b border-gray-200';
                tableHeader.innerHTML = `<h3 class="text-lg font-medium text-gray-900">${classData.class_name}</h3>`;
                tableWrapper.appendChild(tableHeader);
                
                // Create table structure
                const table = document.createElement('table');
                table.className = 'min-w-full divide-y divide-gray-200';
                table.id = 'class-table-' + classId;
                
                // Create header row
                const thead = document.createElement('thead');
                const headerRow = document.createElement('tr');
                headerRow.className = 'bg-gray-50';
                
                const headers = ['Student Name', 'Admission No', 'Select'];
                headers.forEach(headerText => {
                    const th = document.createElement('th');
                    th.className = 'px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider';
                    th.textContent = headerText;
                    headerRow.appendChild(th);
                });
                
                thead.appendChild(headerRow);
                table.appendChild(thead);
                
                // Create body with students
                const tbody = document.createElement('tbody');
                tbody.className = 'bg-white divide-y divide-gray-200';
                tbody.id = 'class-students-' + classId;
                
                if (classData.students.length === 0) {
                    // Show message when no students in class
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');
                    cell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center';
                    cell.colSpan = 3;
                    cell.textContent = 'No students available in this class';
                    row.appendChild(cell);
                    tbody.appendChild(row);
                } else {
                    // Add student rows
                    classData.students.forEach(student => {
                        const row = createStudentRow(student);
                        tbody.appendChild(row);
                    });
                }
                
                table.appendChild(tbody);
                tableWrapper.appendChild(table);
                tablesContainer.appendChild(tableWrapper);
            });
            
            studentsContainer.appendChild(tablesContainer);
        }
        
        updateSubmitButton();
    }
    
    // Update existing student checkboxes without replacing the entire DOM
    function updateExistingStudentCheckboxes(studentsByClass) {
        Object.entries(studentsByClass).forEach(([classId, classData]) => {
            const tbody = document.getElementById('class-students-' + classId);
            if (tbody) {
                // Get existing student IDs
                const existingStudentRows = Array.from(tbody.querySelectorAll('tr[data-student-id]'));
                const existingStudentIds = existingStudentRows.map(row => row.getAttribute('data-student-id'));
                
                // Get new student IDs
                const newStudentIds = classData.students.map(student => student.id.toString());
                
                // Remove students that no longer exist (if any)
                existingStudentRows.forEach(row => {
                    const studentId = row.getAttribute('data-student-id');
                    if (!newStudentIds.includes(studentId)) {
                        tbody.removeChild(row);
                    }
                });
                
                // Add new students
                classData.students.forEach(student => {
                    const studentId = student.id.toString();
                    if (!existingStudentIds.includes(studentId)) {
                        const row = createStudentRow(student);
                        tbody.appendChild(row);
                    }
                });
                
                // Update "No students" message if needed
                if (classData.students.length === 0 && tbody.children.length === 0) {
                    const row = document.createElement('tr');
                    const cell = document.createElement('td');
                    cell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center';
                    cell.colSpan = 3;
                    cell.textContent = 'No students available in this class';
                    row.appendChild(cell);
                    tbody.appendChild(row);
                } else if (classData.students.length > 0 && tbody.children.length === 1 && tbody.children[0].children.length === 1) {
                    // Remove "No students" message if we now have students
                    tbody.removeChild(tbody.children[0]);
                }
            }
        });
    }
    
    // Create a student row element
    function createStudentRow(student) {
        const row = document.createElement('tr');
        row.setAttribute('data-student-id', student.id);
        
        // Student name cell
        const nameCell = document.createElement('td');
        nameCell.className = 'px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900';
        nameCell.textContent = `${student.first_name} ${student.last_name}`;
        row.appendChild(nameCell);
        
        // Admission no cell
        const admissionCell = document.createElement('td');
        admissionCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
        admissionCell.textContent = student.admission_no;
        row.appendChild(admissionCell);
        
        // Checkbox cell
        const checkboxCell = document.createElement('td');
        checkboxCell.className = 'px-6 py-4 whitespace-nowrap text-sm text-gray-500';
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = 'selected_students[]';
        checkbox.value = student.id;
        checkbox.className = 'h-4 w-4 text-indigo-600 border-gray-300 rounded student-checkbox';
        checkbox.addEventListener('change', updateSubmitButton);
        
        // Check if student is already assigned
        const isAssigned = <?php echo json_encode(array_column($assignedStudents ?? [], 'student_id')); ?>.includes(student.id.toString());
        if (isAssigned) {
            checkbox.checked = true;
            checkbox.disabled = true;
        } else {
            checkbox.checked = true; // Check by default for new assignments
        }
        
        checkboxCell.appendChild(checkbox);
        
        // Add already assigned indicator if needed
        if (isAssigned) {
            const indicator = document.createElement('span');
            indicator.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800 ml-2';
            indicator.textContent = 'Already Assigned';
            checkboxCell.appendChild(indicator);
        }
        
        row.appendChild(checkboxCell);
        
        return row;
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>