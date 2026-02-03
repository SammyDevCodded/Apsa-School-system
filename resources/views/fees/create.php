<?php 
$title = 'Add Fee Structure'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Add Fee Structure</h1>
            <a href="/fees" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Fee Structures
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="/fees" method="POST" class="space-y-6" id="fee-form">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Fee Name</label>
                            <input type="text" name="name" id="name" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount ($)</label>
                            <input type="number" step="0.01" name="amount" id="amount" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="type" class="block text-sm font-medium text-gray-700">Fee Type</label>
                            <select name="type" id="type" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Type</option>
                                <option value="tuition">Tuition</option>
                                <option value="transport">Transport</option>
                                <option value="feeding">Feeding</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Classes (Optional)</label>
                            <div class="mt-1">
                                <div class="flex items-center mb-2">
                                    <input type="checkbox" id="select-all-classes" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    <label for="select-all-classes" class="ml-2 block text-sm text-gray-900">
                                        Select All Classes
                                    </label>
                                </div>
                                <div class="space-y-2 max-h-40 overflow-y-auto border border-gray-300 rounded-md p-2">
                                    <?php foreach ($classes as $class): ?>
                                        <div class="flex items-center">
                                            <input type="checkbox" name="selected_classes[]" value="<?= $class['id'] ?>" 
                                                id="class_<?= $class['id'] ?>" class="h-4 w-4 text-indigo-600 border-gray-300 rounded class-checkbox">
                                            <label for="class_<?= $class['id'] ?>" class="ml-2 block text-sm text-gray-900">
                                                <?= htmlspecialchars($class['name']) ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                </div>
                            </div>
                        </div>

                        <div class="sm:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>
                    </div>

                    <!-- Students Section (Hidden by default) -->
                    <div id="students-section" class="bg-gray-50 p-4 rounded-lg" style="display: none;">
                        <h3 class="text-lg font-medium text-gray-900 mb-4">Select Students</h3>
                        <div class="mb-4">
                            <button type="button" id="select-all-students" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Select All Students
                            </button>
                            <button type="button" id="deselect-all-students" class="ml-2 inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Deselect All Students
                            </button>
                        </div>
                        <div id="students-container" class="overflow-x-auto">
                            <!-- Students will be loaded here dynamically in rows and columns -->
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Fee Structure
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Handle Select All functionality for classes
document.getElementById('select-all-classes').addEventListener('change', function() {
    const checkboxes = document.querySelectorAll('.class-checkbox');
    checkboxes.forEach(checkbox => {
        checkbox.checked = this.checked;
    });
    
    // Load students if any class is selected
    if (this.checked) {
        loadStudentsForSelectedClasses();
    } else {
        hideStudentsSection();
    }
});

// Update Select All checkbox state when individual checkboxes are changed
document.querySelectorAll('.class-checkbox').forEach(checkbox => {
    checkbox.addEventListener('change', function() {
        const allCheckboxes = document.querySelectorAll('.class-checkbox');
        const allChecked = Array.from(allCheckboxes).every(cb => cb.checked);
        document.getElementById('select-all-classes').checked = allChecked;
        
        // Load students if any class is selected
        loadStudentsForSelectedClasses();
    });
});

// Select All Students functionality
document.getElementById('select-all-students').addEventListener('click', function() {
    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.checked = true;
    });
});

// Deselect All Students functionality
document.getElementById('deselect-all-students').addEventListener('click', function() {
    document.querySelectorAll('.student-checkbox').forEach(checkbox => {
        checkbox.checked = false;
    });
});

// Load students for selected classes (gets selected class IDs and calls loadStudents)
function loadStudentsForSelectedClasses() {
    const selectedClassIds = Array.from(document.querySelectorAll('.class-checkbox:checked'))
        .map(checkbox => checkbox.value);
    
    loadStudents(selectedClassIds);
}

// Load students for selected classes
function loadStudents(selectedClassIds) {
    // Validate input
    if (!selectedClassIds || selectedClassIds.length === 0) {
        hideStudentsSection();
        return;
    }
    
    // Show loading state
    const studentsSection = document.getElementById('students-section');
    const studentsContainer = document.getElementById('students-container');
    studentsContainer.innerHTML = '<p class="text-gray-500">Loading students...</p>';
    studentsSection.style.display = 'block';
    
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
        if (data.success) {
            generateStudentCheckboxes(data.students_by_class);
        } else {
            studentsContainer.innerHTML = '<p class="text-red-500">Error loading students: ' + (data.error || 'Unknown error') + '</p>';
        }
    })
    .catch(error => {
        studentsContainer.innerHTML = '<p class="text-red-500">Error loading students: ' + error.message + '</p>';
    });
}

// Hide students section
function hideStudentsSection() {
    document.getElementById('students-section').style.display = 'none';
}

// Generate student checkboxes in rows and columns format
function generateStudentCheckboxes(studentsByClass) {
    const studentsContainer = document.getElementById('students-container');
    studentsContainer.innerHTML = '';
    
    // Check if we have any classes with students
    const hasStudents = Object.keys(studentsByClass).length > 0;
    
    if (!hasStudents) {
        studentsContainer.innerHTML = '<p class="text-gray-500">No students found in selected classes.</p>';
        return;
    }
    
    // Create table format for students
    const classIds = Object.keys(studentsByClass);
    if (classIds.length === 1) {
        // Single class selected - show students in a simple list with all checked
        createSingleClassLayout(studentsByClass);
    } else {
        // Multiple classes selected - show in rows and columns format
        createMultipleClassesLayout(studentsByClass);
    }
}

// Create layout for single class selection
function createSingleClassLayout(studentsByClass) {
    const studentsContainer = document.getElementById('students-container');
    
    // Get the first (and only) class
    const classData = Object.values(studentsByClass)[0];
    
    const studentList = document.createElement('div');
    studentList.className = 'grid grid-cols-1 gap-2 sm:grid-cols-2 md:grid-cols-3 lg:grid-cols-4';
    
    classData.students.forEach(student => {
        const studentDiv = document.createElement('div');
        studentDiv.className = 'flex items-center p-2 border border-gray-200 rounded bg-white';
        
        const checkbox = document.createElement('input');
        checkbox.type = 'checkbox';
        checkbox.name = 'selected_students[]';
        checkbox.value = student.id;
        checkbox.id = 'student_' + student.id;
        checkbox.className = 'h-4 w-4 text-indigo-600 border-gray-300 rounded student-checkbox';
        checkbox.checked = true; // Check by default
        
        const label = document.createElement('label');
        label.htmlFor = 'student_' + student.id;
        label.className = 'ml-2 block text-sm text-gray-900';
        label.innerHTML = `
            <span class="font-medium">${student.first_name} ${student.last_name}</span>
            <span class="block text-xs text-gray-500">${student.admission_no}</span>
        `;
        
        studentDiv.appendChild(checkbox);
        studentDiv.appendChild(label);
        studentList.appendChild(studentDiv);
    });
    
    studentsContainer.appendChild(studentList);
}

// Create layout for multiple classes selection (separate tables per class)
function createMultipleClassesLayout(studentsByClass) {
    const studentsContainer = document.getElementById('students-container');
    
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
                const row = document.createElement('tr');
                
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
                checkbox.checked = true; // Check by default
                checkboxCell.appendChild(checkbox);
                row.appendChild(checkboxCell);
                
                tbody.appendChild(row);
            });
        }
        
        table.appendChild(tbody);
        tableWrapper.appendChild(table);
        tablesContainer.appendChild(tableWrapper);
    });
    
    studentsContainer.appendChild(tablesContainer);
}
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>