<?php 
$title = 'Academic Reports'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Academic Reports</h1>
            <a href="/dashboard" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Dashboard
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form id="academicReportForm" action="/academic-reports/preview" method="POST">
                    <div class="grid grid-cols-1 gap-6">
                        <!-- Exam Selection -->
                        <div>
                            <label for="exam_id" class="block text-sm font-medium text-gray-700">Select Exam</label>
                            <select name="exam_id" id="exam_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" required>
                                <option value="">Select an exam</option>
                                <?php foreach ($exams as $exam): ?>
                                    <option value="<?= $exam['id'] ?>">
                                        <?= htmlspecialchars($exam['name']) ?> 
                                        (<?= htmlspecialchars($exam['academic_year_name'] ?? 'N/A') ?> - <?= htmlspecialchars($exam['term'] ?? 'N/A') ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <!-- Class Selection -->
                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700">Select Class</label>
                            <select name="class_id" id="class_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" disabled required>
                                <option value="">Select an exam first</option>
                            </select>
                        </div>

                        <!-- Student Selection -->
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Select Students</label>
                            <div id="student-selection" class="mt-1 border border-gray-300 rounded-md shadow-sm p-4 min-h-[100px]">
                                <p id="student-placeholder" class="text-gray-500">Select a class to load students</p>
                                <div id="student-checkboxes" class="hidden"></div>
                            </div>
                        </div>

                        <!-- Action Buttons -->
                        <div class="flex justify-end space-x-3">
                            <button type="button" id="select-all-students" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 hidden">
                                Select All
                            </button>
                            <button type="button" id="deselect-all-students" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 hidden">
                                Deselect All
                            </button>
                            <button type="submit" id="preview-btn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" disabled>
                                Preview Reports
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const examSelect = document.getElementById('exam_id');
    const classSelect = document.getElementById('class_id');
    const studentPlaceholder = document.getElementById('student-placeholder');
    const studentCheckboxes = document.getElementById('student-checkboxes');
    const selectAllBtn = document.getElementById('select-all-students');
    const deselectAllBtn = document.getElementById('deselect-all-students');
    const previewBtn = document.getElementById('preview-btn');
    
    // When exam is selected, load related classes
    examSelect.addEventListener('change', function() {
        const examId = this.value;
        
        if (!examId) {
            // Reset class and student selections
            classSelect.innerHTML = '<option value="">Select an exam first</option>';
            classSelect.disabled = true;
            resetStudentSelection();
            return;
        }
        
        // Fetch classes for the selected exam
        fetch('/academic-reports/classes', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'exam_id=' + encodeURIComponent(examId)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            if (data.classes && data.classes.length > 0) {
                // Populate class dropdown
                classSelect.innerHTML = '<option value="">Select a class</option>';
                data.classes.forEach(classObj => {
                    const option = document.createElement('option');
                    option.value = classObj.id;
                    option.textContent = classObj.name + ' ' + classObj.level;
                    classSelect.appendChild(option);
                });
                classSelect.disabled = false;
            } else {
                classSelect.innerHTML = '<option value="">No classes found for this exam</option>';
                classSelect.disabled = true;
                resetStudentSelection();
            }
        })
        .catch(error => {
            console.error('Error:', error);
            classSelect.innerHTML = '<option value="">Error loading classes: ' + error.message + '</option>';
            classSelect.disabled = true;
            resetStudentSelection();
        });
    });
    
    // When class is selected, load students
    classSelect.addEventListener('change', function() {
        const classId = this.value;
        const examId = examSelect.value;
        
        if (!classId || !examId) {
            resetStudentSelection();
            return;
        }
        
        // Fetch students for the selected class and exam
        fetch('/academic-reports/students', {
            method: 'POST',
            headers: {
                'Content-Type': 'application/x-www-form-urlencoded',
            },
            body: 'exam_id=' + encodeURIComponent(examId) + '&class_id=' + encodeURIComponent(classId)
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok: ' + response.status);
            }
            return response.json();
        })
        .then(data => {
            if (data.error) {
                throw new Error(data.error);
            }
            
            if (data.students && data.students.length > 0) {
                // Populate student checkboxes
                studentCheckboxes.innerHTML = '';
                data.students.forEach(student => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center mb-2';
                    div.innerHTML = `
                        <input type="checkbox" name="student_ids[]" value="${student.id}" id="student_${student.id}" class="student-checkbox h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                        <label for="student_${student.id}" class="ml-3 block text-sm font-medium text-gray-700">
                            ${student.first_name} ${student.last_name} (${student.admission_no})
                            ${student.has_results ? `<span class="text-green-600 text-xs">(${student.results_count} subjects submitted)</span>` : `<span class="text-red-600 text-xs">(No results submitted)</span>`}
                        </label>
                    `;
                    studentCheckboxes.appendChild(div);
                });
                
                studentPlaceholder.classList.add('hidden');
                studentCheckboxes.classList.remove('hidden');
                selectAllBtn.classList.remove('hidden');
                deselectAllBtn.classList.remove('hidden');
                
                // Add event listeners to checkboxes
                document.querySelectorAll('.student-checkbox').forEach(checkbox => {
                    checkbox.addEventListener('change', updatePreviewButtonState);
                });
                
                updatePreviewButtonState();
            } else {
                studentCheckboxes.innerHTML = '<p class="text-gray-500">No students found in this class</p>';
                studentPlaceholder.classList.add('hidden');
                studentCheckboxes.classList.remove('hidden');
                selectAllBtn.classList.add('hidden');
                deselectAllBtn.classList.add('hidden');
                previewBtn.disabled = true;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            studentCheckboxes.innerHTML = '<p class="text-red-500">Error loading students: ' + error.message + '</p>';
            studentPlaceholder.classList.add('hidden');
            studentCheckboxes.classList.remove('hidden');
            selectAllBtn.classList.add('hidden');
            deselectAllBtn.classList.add('hidden');
            previewBtn.disabled = true;
        });
    });
    
    // Select all students
    selectAllBtn.addEventListener('click', function() {
        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
            checkbox.checked = true;
        });
        updatePreviewButtonState();
    });
    
    // Deselect all students
    deselectAllBtn.addEventListener('click', function() {
        document.querySelectorAll('.student-checkbox').forEach(checkbox => {
            checkbox.checked = false;
        });
        updatePreviewButtonState();
    });
    
    // Update preview button state based on selections
    function updatePreviewButtonState() {
        const anyChecked = document.querySelectorAll('.student-checkbox:checked').length > 0;
        previewBtn.disabled = !anyChecked;
    }
    
    // Reset student selection area
    function resetStudentSelection() {
        studentPlaceholder.textContent = 'Select a class to load students';
        studentPlaceholder.classList.remove('hidden');
        studentCheckboxes.classList.add('hidden');
        studentCheckboxes.innerHTML = '';
        selectAllBtn.classList.add('hidden');
        deselectAllBtn.classList.add('hidden');
        previewBtn.disabled = true;
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>