<?php 
$title = 'Record Exam Result'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Record Exam Result</h1>
            <a href="/exam_results" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Results
            </a>
        </div>

        <!-- Flash Messages -->
        <div id="flash-messages">
            <!-- Success and error messages will be displayed here -->
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form id="exam-result-form" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="exam_id" class="block text-sm font-medium text-gray-700">Exam</label>
                            <select name="exam_id" id="exam_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Exam</option>
                                <?php foreach ($exams ?? [] as $exam): ?>
                                    <option value="<?= $exam['id'] ?>">
                                        <?= htmlspecialchars($exam['name'] . ' (' . date('M j, Y', strtotime($exam['date'])) . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Class</label>
                            <div id="class-selection-container">
                                <select name="class_id" id="class_id" required
                                    class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">Select an exam first</option>
                                    <!-- Classes will be loaded dynamically based on selected exam -->
                                </select>
                                <div id="class-checkboxes" class="mt-2 hidden">
                                    <!-- Class checkboxes will be loaded here dynamically -->
                                </div>
                                <input type="hidden" name="selected_class_id" id="selected_class_id">
                            </div>
                        </div>

                        <div>
                            <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                            <select name="subject_id" id="subject_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Class First</option>
                                <!-- Subjects will be loaded dynamically based on selected class -->
                            </select>
                        </div>

                        <div>
                            <label for="grading_scale_id" class="block text-sm font-medium text-gray-700">Grading Scale</label>
                            <select name="grading_scale_id" id="grading_scale_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Grading Scale</option>
                                <?php foreach ($gradingScales ?? [] as $scale): ?>
                                    <option value="<?= $scale['id'] ?>">
                                        <?= htmlspecialchars($scale['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="button" id="load-score-sheet-btn"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Load Score Sheet
                        </button>
                    </div>
                </form>
            </div>
        </div>

        <!-- Students Selection Section -->
        <div id="students-selection" class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg hidden">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Select Students</h3>
                <div class="flex items-center mb-4">
                    <input type="checkbox" id="select-all-students" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                    <label for="select-all-students" class="ml-2 block text-sm text-gray-900">Select All Students</label>
                </div>
                <div id="students-list" class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3">
                    <!-- Students will be loaded here dynamically -->
                </div>
                <div class="mt-6 flex justify-end">
                    <button type="button" id="submit-results-btn"
                        class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                        Load Score Sheet for Selected Students
                    </button>
                </div>
            </div>
        </div>

        <!-- Grading Rules Display Section -->
        <div id="grading-rules-display" class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg hidden">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Grading Scale Rules</h3>
                <div id="grading-rules-content" class="overflow-x-auto">
                    <!-- Grading rules will be displayed here -->
                </div>
            </div>
        </div>

        <!-- Score Sheet Section -->
        <div id="score-sheet" class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg hidden">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Score Sheet</h3>
                <div class="mb-6 p-4 bg-blue-50 rounded-lg">
                    <h4 class="text-md font-medium text-gray-900 mb-2">How Grades Are Determined</h4>
                    <p class="text-sm text-gray-700">
                        When you enter marks for a student, the system looks up the grading rules shown above to find the matching grade and remark.
                        No calculation is performed - the system simply finds the rule where the entered marks fall between the Min and Max scores,
                        then uses the exact Grade and Remark values from that rule.
                    </p>
                </div>
                <form id="exam-results-form" action="/exam_results/store-bulk" method="POST">
                    <input type="hidden" name="exam_id" id="form-exam-id">
                    <input type="hidden" name="class_id" id="form-class-id">
                    <input type="hidden" name="subject_id" id="form-subject-id">
                    <input type="hidden" name="grading_scale_id" id="form-grading-scale-id">
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr id="score-sheet-header">
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission No</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marks (0-100)</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remark</th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matching Rule</th>
                                </tr>
                            </thead>
                            <tbody id="score-sheet-content" class="bg-white divide-y divide-gray-200">
                                <!-- Score sheet rows will be loaded here dynamically -->
                            </tbody>
                        </table>
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="button" id="back-to-selection-btn"
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Back to Selection
                        </button>
                        <button type="submit" id="submit-exam-results-btn"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Submit Results
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const loadScoreSheetBtn = document.getElementById('load-score-sheet-btn');
    const selectAllStudents = document.getElementById('select-all-students');
    const studentsList = document.getElementById('students-list');
    const studentsSelection = document.getElementById('students-selection');
    const gradingRulesDisplay = document.getElementById('grading-rules-display');
    const gradingRulesContent = document.getElementById('grading-rules-content');
    const scoreSheet = document.getElementById('score-sheet');
    const scoreSheetContent = document.getElementById('score-sheet-content');
    const submitResultsBtn = document.getElementById('submit-results-btn');
    const backToSelectionBtn = document.getElementById('back-to-selection-btn');
    const flashMessages = document.getElementById('flash-messages');
    
    // Form elements
    const examIdSelect = document.getElementById('exam_id');
    const classIdSelect = document.getElementById('class_id');
    const classCheckboxesContainer = document.getElementById('class-checkboxes');
    const selectedClassIdInput = document.getElementById('selected_class_id');
    const subjectIdSelect = document.getElementById('subject_id');
    const gradingScaleIdSelect = document.getElementById('grading_scale_id');
    
    // Form hidden inputs
    const formExamId = document.getElementById('form-exam-id');
    const formClassId = document.getElementById('form-class-id');
    const formSubjectId = document.getElementById('form-subject-id');
    const formGradingScaleId = document.getElementById('form-grading-scale-id');
    
    // Current grading rules
    let currentGradingRules = [];
    // Store student data for use in the table
    let selectedStudentsData = [];
    
    // Load classes when exam is selected
    examIdSelect.addEventListener('change', function() {
        const examId = this.value;
        
        if (examId) {
            loadClassesByExam(examId);
            // Check if this exam has classwork enabled
            checkExamClasswork(examId);
        } else {
            // Reset all fields
            resetClassSelection();
            subjectIdSelect.innerHTML = '<option value="">Select Class First</option>';
            // Hide classwork fields if they were shown
            hideClassworkFields();
        }
    });
    
    // Function to check if exam has classwork enabled
    function checkExamClasswork(examId) {
        fetch(`/exams/${examId}/classwork-info`)
            .then(response => response.json())
            .then(data => {
                if (data.has_classwork) {
                    showClassworkFields(data.classwork_percentage);
                } else {
                    hideClassworkFields();
                }
            })
            .catch(error => {
                console.error('Error checking exam classwork:', error);
                hideClassworkFields();
            });
    }
    
    // Function to show classwork fields
    function showClassworkFields(percentage) {
        // Create or show classwork section
        let classworkSection = document.getElementById('classwork-section');
        if (!classworkSection) {
            classworkSection = document.createElement('div');
            classworkSection.id = 'classwork-section';
            classworkSection.className = 'mt-6 p-4 bg-blue-50 rounded-lg';
            
            // Insert after the grading rules display
            const scoreSheet = document.getElementById('score-sheet');
            if (scoreSheet) {
                scoreSheet.parentNode.insertBefore(classworkSection, scoreSheet);
            } else {
                document.querySelector('.max-w-7xl').appendChild(classworkSection);
            }
        }
        
        classworkSection.innerHTML = `
            <h4 class="text-md font-medium text-gray-900 mb-2">Classwork Component</h4>
            <p class="text-sm text-gray-700 mb-3">This exam includes a classwork component that contributes ${percentage}% to the final grade.</p>
            <p class="text-sm text-gray-700">When entering scores, you will be able to input both exam marks and classwork scores.</p>
        `;
        classworkSection.classList.remove('hidden');
        
        // Update table header to include classwork columns
        updateTableHeader(true);
    }
    
    // Function to hide classwork fields
    function hideClassworkFields() {
        const classworkSection = document.getElementById('classwork-section');
        if (classworkSection) {
            classworkSection.classList.add('hidden');
        }
        
        // Update table header to remove classwork columns
        updateTableHeader(false);
    }
    
    // Function to update table header based on classwork status
    function updateTableHeader(hasClasswork) {
        const headerRow = document.getElementById('score-sheet-header');
        if (!headerRow) return;
        
        if (hasClasswork) {
            headerRow.innerHTML = `
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission No</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam Marks</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Classwork Marks</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Final Marks</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remark</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matching Rule</th>
            `;
        } else {
            headerRow.innerHTML = `
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission No</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Marks (0-100)</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remark</th>
                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Matching Rule</th>
            `;
        }
    }
    
    // Function to reset class selection
    function resetClassSelection() {
        classIdSelect.innerHTML = '<option value="">Select an exam first</option>';
        classCheckboxesContainer.innerHTML = '';
        classCheckboxesContainer.classList.add('hidden');
        classIdSelect.classList.remove('hidden');
        selectedClassIdInput.value = '';
    }
    
    // Function to load classes by exam
    function loadClassesByExam(examId) {
        // Reset class selection first
        resetClassSelection();
        
        fetch(`/exam_results/classes-by-exam?exam_id=${examId}`)
            .then(response => response.json())
            .then(data => {
                if (!data || !data.classes || data.classes.length === 0) {
                    classIdSelect.innerHTML = '<option value="">No classes assigned to this exam</option>';
                    return;
                }
                
                if (data.classes.length === 1) {
                    // Single class - use dropdown
                    const classItem = data.classes[0];
                    classIdSelect.innerHTML = `
                        <option value="${classItem.id}">${classItem.name} (${classItem.level})</option>
                    `;
                    classIdSelect.value = classItem.id;
                    selectedClassIdInput.value = classItem.id;
                    
                    // Auto-load subjects and students
                    loadAssignedSubjectsByClass(classItem.id);
                    loadStudents(classItem.id);
                } else {
                    // Multiple classes - use checkboxes
                    classIdSelect.classList.add('hidden');
                    classCheckboxesContainer.classList.remove('hidden');
                    classCheckboxesContainer.innerHTML = '<label class="block text-sm font-medium text-gray-700 mb-2">Select Class:</label>';
                    
                    data.classes.forEach(classItem => {
                        const div = document.createElement('div');
                        div.className = 'flex items-center mb-2';
                        div.innerHTML = `
                            <input type="checkbox" id="class_${classItem.id}" name="class_checkboxes[]" value="${classItem.id}" 
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded class-checkbox">
                            <label for="class_${classItem.id}" class="ml-2 block text-sm text-gray-900">
                                ${classItem.name} (${classItem.level})
                            </label>
                        `;
                        classCheckboxesContainer.appendChild(div);
                    });
                    
                    // Add event listeners to checkboxes
                    const checkboxes = classCheckboxesContainer.querySelectorAll('.class-checkbox');
                    checkboxes.forEach(checkbox => {
                        checkbox.addEventListener('change', function() {
                            if (this.checked) {
                                // Uncheck other checkboxes
                                checkboxes.forEach(cb => {
                                    if (cb !== this) cb.checked = false;
                                });
                                // Set the selected class ID
                                selectedClassIdInput.value = this.value;
                                // Load subjects and students
                                loadAssignedSubjectsByClass(this.value);
                                loadStudents(this.value);
                            } else {
                                // If unchecked, clear selection
                                selectedClassIdInput.value = '';
                                subjectIdSelect.innerHTML = '<option value="">Select Class First</option>';
                                studentsList.innerHTML = '';
                            }
                        });
                    });
                }
            })
            .catch(error => {
                console.error('Error loading classes:', error);
                classIdSelect.innerHTML = '<option value="">Error loading classes</option>';
            });
    }
    
    // Function to load assigned subjects by class
    function loadAssignedSubjectsByClass(classId) {
        fetch(`/exam_results/assigned-subjects-by-class?class_id=${classId}`)
            .then(response => response.json())
            .then(data => {
                subjectIdSelect.innerHTML = '<option value="">Select Subject</option>';
                if (data.subjects && data.subjects.length > 0) {
                    data.subjects.forEach(subject => {
                        const option = document.createElement('option');
                        option.value = subject.id;
                        option.textContent = subject.name + ' (' + subject.code + ')';
                        subjectIdSelect.appendChild(option);
                    });
                }
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                subjectIdSelect.innerHTML = '<option value="">Error loading subjects</option>';
            });
    }
    
    // Function to load students by class
    function loadStudents(classId) {
        // Get current exam and subject IDs
        const examId = examIdSelect.value;
        const subjectId = subjectIdSelect.value;
        
        fetch(`/exam_results/students-by-class?class_id=${classId}`)
            .then(response => response.json())
            .then(data => {
                studentsList.innerHTML = '';
                if (data.students && data.students.length > 0) {
                    data.students.forEach(student => {
                        const div = document.createElement('div');
                        div.className = 'flex items-center mb-2';
                        div.innerHTML = `
                            <input type="checkbox" id="student_${student.id}" name="student_ids[]" value="${student.id}" 
                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                            <label for="student_${student.id}" class="ml-2 block text-sm text-gray-900">
                                ${student.first_name} ${student.last_name} (${student.admission_no})
                            </label>
                        `;
                        studentsList.appendChild(div);
                    });
                    
                    // After loading students, check for existing results
                    if (examId && classId && subjectId) {
                        loadExistingResults(examId, classId, subjectId);
                    }
                }
            })
            .catch(error => {
                console.error('Error loading students:', error);
                studentsList.innerHTML = '<div class="text-red-500">Error loading students</div>';
            });
    }
    
    // Function to load existing results and show preview
    function loadExistingResults(examId, classId, subjectId) {
        if (!examId || !classId || !subjectId) {
            return;
        }
        
        fetch(`/exam_results/existing-results?exam_id=${examId}&class_id=${classId}&subject_id=${subjectId}`)
            .then(response => response.json())
            .then(data => {
                // Show preview of existing results
                showExistingResultsPreview(data.results);
            })
            .catch(error => {
                console.error('Error loading existing results:', error);
            });
    }
    
    // Function to show preview of existing results
    function showExistingResultsPreview(results) {
        // Create or update preview section
        let previewSection = document.getElementById('existing-results-preview');
        if (!previewSection) {
            previewSection = document.createElement('div');
            previewSection.id = 'existing-results-preview';
            previewSection.className = 'mt-4 p-4 bg-yellow-50 border border-yellow-200 rounded-lg';
            studentsSelection.parentNode.insertBefore(previewSection, studentsSelection.nextSibling);
        }
        
        if (results.length > 0) {
            let previewHTML = `
                <h4 class="text-md font-medium text-gray-900 mb-2">Students with Existing Results</h4>
                <p class="text-sm text-gray-700 mb-3">The following students already have marks recorded for this exam and subject:</p>
                <div class="grid grid-cols-1 gap-2 sm:grid-cols-2 lg:grid-cols-3">
            `;
            
            results.forEach(result => {
                previewHTML += `
                    <div class="flex items-center p-2 bg-white border border-gray-200 rounded">
                        <div class="flex-shrink-0 h-8 w-8 bg-indigo-100 rounded-full flex items-center justify-center">
                            <span class="text-indigo-800 text-xs font-medium">
                                ${result.first_name.charAt(0)}${result.last_name.charAt(0)}
                            </span>
                        </div>
                        <div class="ml-2">
                            <p class="text-sm font-medium text-gray-900">${result.first_name} ${result.last_name}</p>
                            <p class="text-xs text-gray-500">Marks: ${result.marks} | Grade: ${result.grade}</p>
                        </div>
                    </div>
                `;
            });
            
            previewHTML += `</div>`;
            previewSection.innerHTML = previewHTML;
            previewSection.classList.remove('hidden');
        } else {
            previewSection.classList.add('hidden');
        }
    }
    
    // Load subjects when class is selected (for dropdown method)
    classIdSelect.addEventListener('change', function() {
        const classId = this.value;
        if (classId) {
            selectedClassIdInput.value = classId;
            loadAssignedSubjectsByClass(classId);
            loadStudents(classId);
        } else {
            selectedClassIdInput.value = '';
            subjectIdSelect.innerHTML = '<option value="">Select Class First</option>';
            studentsList.innerHTML = '';
        }
    });
    
    // Load grading rules when grading scale is selected
    gradingScaleIdSelect.addEventListener('change', function() {
        const scaleId = this.value;
        if (scaleId) {
            loadGradingRules(scaleId);
        } else {
            currentGradingRules = [];
            gradingRulesDisplay.classList.add('hidden');
        }
    });
    
    // Load existing results when subject is selected
    subjectIdSelect.addEventListener('change', function() {
        const examId = examIdSelect.value;
        const classId = selectedClassIdInput.value || classIdSelect.value;
        const subjectId = this.value;
        
        if (examId && classId && subjectId) {
            loadExistingResults(examId, classId, subjectId);
        }
    });
    
    // Function to load grading rules
    function loadGradingRules(scaleId) {
        fetch(`/exam_results/grading-rules?scale_id=${scaleId}`)
            .then(response => response.json())
            .then(data => {
                if (data && data.rules && Array.isArray(data.rules)) {
                    currentGradingRules = data.rules;
                    displayGradingRules(data.rules);
                } else {
                    currentGradingRules = [];
                    gradingRulesDisplay.classList.add('hidden');
                }
            })
            .catch(error => {
                console.error('Error loading grading rules:', error);
                currentGradingRules = [];
                gradingRulesDisplay.classList.add('hidden');
            });
    }
    
    // Function to display grading rules
    function displayGradingRules(rules) {
        if (rules.length === 0) {
            gradingRulesContent.innerHTML = '<p class="text-gray-500">No grading rules found for this scale.</p>';
            gradingRulesDisplay.classList.add('hidden');
            return;
        }
        
        let rulesHTML = `
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Min Score</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Max Score</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remark</th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
        `;
        
        // Sort rules by min_score descending for proper display
        rules.sort((a, b) => parseFloat(b.min_score) - parseFloat(a.min_score));
        
        rules.forEach(rule => {
            rulesHTML += `
                <tr id="rule-${rule.id}" class="rule-row">
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${rule.min_score}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${rule.max_score}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">${rule.grade}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${rule.remark}</td>
                </tr>
            `;
        });
        
        rulesHTML += `
                </tbody>
            </table>
        `;
        
        gradingRulesContent.innerHTML = rulesHTML;
        gradingRulesDisplay.classList.remove('hidden');
    }
    
    // Load score sheet button
    loadScoreSheetBtn.addEventListener('click', function() {
        const examId = examIdSelect.value;
        const classId = selectedClassIdInput.value || classIdSelect.value;
        const subjectId = subjectIdSelect.value;
        const gradingScaleId = gradingScaleIdSelect.value;
        
        if (!examId || !classId || !subjectId || !gradingScaleId) {
            showFlashMessage('Please select all fields first.', 'error');
            return;
        }
        
        // Show students selection section
        studentsSelection.classList.remove('hidden');
        
        // Set form hidden inputs
        formExamId.value = examId;
        formClassId.value = classId;
        formSubjectId.value = subjectId;
        formGradingScaleId.value = gradingScaleId;
        
        // Load grading rules if not already loaded
        if (currentGradingRules.length === 0 && gradingScaleId) {
            loadGradingRules(gradingScaleId);
        }
    });
    
    // Select all students checkbox
    selectAllStudents.addEventListener('change', function() {
        const checkboxes = studentsList.querySelectorAll('input[type="checkbox"]');
        checkboxes.forEach(checkbox => {
            checkbox.checked = this.checked;
        });
    });
    
    // Submit results button (Load Score Sheet for Selected Students)
    submitResultsBtn.addEventListener('click', function() {
        // Get selected students
        const selectedStudents = [];
        const studentCheckboxes = studentsList.querySelectorAll('input[type="checkbox"]:checked');
        studentCheckboxes.forEach(checkbox => {
            selectedStudents.push(checkbox.value);
        });
        
        if (selectedStudents.length === 0) {
            showFlashMessage('Please select at least one student.', 'error');
            return;
        }
        
        // Get class ID from either dropdown or hidden input
        const classId = selectedClassIdInput.value || classIdSelect.value;
        if (!classId) {
            showFlashMessage('Please select a class.', 'error');
            return;
        }
        
        // Set the form class ID
        formClassId.value = classId;
        
        // Get student data for the selected students
        selectedStudentsData = [];
        studentCheckboxes.forEach(checkbox => {
            if (checkbox.checked) {
                const studentId = checkbox.value;
                const studentLabel = checkbox.nextElementSibling;
                const studentText = studentLabel.textContent.trim();
                // Extract name and admission number
                const match = studentText.match(/(.+?)\s*\((.+?)\)/);
                if (match) {
                    selectedStudentsData.push({
                        id: studentId,
                        name: match[1].trim(),
                        admission_no: match[2]
                    });
                }
            }
        });
        
        // Show score sheet with all students in table format
        showScoreSheetTable();
    });
    
    // Back to selection button
    backToSelectionBtn.addEventListener('click', function() {
        scoreSheet.classList.add('hidden');
        studentsSelection.classList.remove('hidden');
    });
    
    // Function to show score sheet in table format
    function showScoreSheetTable() {
        // Clear previous content
        scoreSheetContent.innerHTML = '';
        
        // Get current exam, class, and subject IDs
        const examId = formExamId.value;
        const classId = formClassId.value;
        const subjectId = formSubjectId.value;
        
        // Check if exam has classwork enabled
        fetch(`/exams/${examId}/classwork-info`)
            .then(response => response.json())
            .then(examData => {
                // Load existing results to determine which students should be grayed out
                if (examId && classId && subjectId) {
                    fetch(`/exam_results/existing-results?exam_id=${examId}&class_id=${classId}&subject_id=${subjectId}`)
                        .then(response => response.json())
                        .then(data => {
                            // Create a map of student IDs that already have results
                            const existingResultsMap = {};
                            data.results.forEach(result => {
                                existingResultsMap[result.student_id] = result;
                            });
                            
                            // Build score sheet content in table format
                            selectedStudentsData.forEach(student => {
                                // Check if this student already has results
                                const hasExistingResult = existingResultsMap.hasOwnProperty(student.id);
                                const existingResult = existingResultsMap[student.id];
                                
                                // Build row HTML based on whether classwork is enabled
                                let rowHTML = '';
                                if (examData.has_classwork) {
                                    rowHTML = `
                                        <tr data-student-id="${student.id}" ${hasExistingResult ? 'class="bg-gray-100"' : ''}>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <input type="hidden" name="student_ids[]" value="${student.id}">
                                                ${student.name} ${hasExistingResult ? '<span class="text-xs text-gray-500">(Has marks)</span>' : ''}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${student.admission_no}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <input type="number" name="exam_marks[]" min="0" max="100" step="0.1" 
                                                       class="exam-marks-input w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm ${hasExistingResult ? 'bg-gray-100 cursor-not-allowed' : ''}" 
                                                       placeholder="0-100"
                                                       data-student-id="${student.id}"
                                                       ${hasExistingResult ? 'readonly value="' + (existingResult.exam_marks || existingResult.marks) + '"' : ''}>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <input type="number" name="classwork_marks[]" min="0" max="100" step="0.1" 
                                                       class="classwork-marks-input w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm ${hasExistingResult ? 'bg-gray-100 cursor-not-allowed' : ''}" 
                                                       placeholder="0-100"
                                                       data-student-id="${student.id}"
                                                       ${hasExistingResult ? 'readonly value="' + (existingResult.classwork_marks || '') + '"' : ''}>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <input type="number" name="final_marks[]" min="0" max="100" step="0.1" 
                                                       class="final-marks-input w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-100" 
                                                       placeholder="Calculated"
                                                       data-student-id="${student.id}"
                                                       readonly value="${hasExistingResult ? (existingResult.final_marks || '') : ''}">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <input type="text" name="grades[]" readonly
                                                       class="grade-display w-16 border border-gray-300 rounded-md shadow-sm py-1 px-2 sm:text-sm bg-gray-50" 
                                                       value="${hasExistingResult ? existingResult.grade : ''}" placeholder="Grade">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <input type="text" name="remarks[]" readonly
                                                       class="remark-display w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 sm:text-sm bg-gray-50" 
                                                       value="${hasExistingResult ? existingResult.remark : ''}" placeholder="Remark">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 matching-rule-info">
                                                <span class="matching-rule-range">${hasExistingResult ? 'Already recorded' : ''}</span>
                                            </td>
                                        </tr>
                                    `;
                                } else {
                                    rowHTML = `
                                        <tr data-student-id="${student.id}" ${hasExistingResult ? 'class="bg-gray-100"' : ''}>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                                <input type="hidden" name="student_ids[]" value="${student.id}">
                                                ${student.name} ${hasExistingResult ? '<span class="text-xs text-gray-500">(Has marks)</span>' : ''}
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${student.admission_no}</td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <input type="number" name="marks[]" min="0" max="100" step="0.1" 
                                                       class="marks-input w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm ${hasExistingResult ? 'bg-gray-100 cursor-not-allowed' : ''}" 
                                                       placeholder="0-100"
                                                       data-student-id="${student.id}"
                                                       ${hasExistingResult ? 'readonly value="' + existingResult.marks + '"' : ''}>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <input type="text" name="grades[]" readonly
                                                       class="grade-display w-16 border border-gray-300 rounded-md shadow-sm py-1 px-2 sm:text-sm bg-gray-50" 
                                                       value="${hasExistingResult ? existingResult.grade : ''}" placeholder="Grade">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <input type="text" name="remarks[]" readonly
                                                       class="remark-display w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 sm:text-sm bg-gray-50" 
                                                       value="${hasExistingResult ? existingResult.remark : ''}" placeholder="Remark">
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 matching-rule-info">
                                                <span class="matching-rule-range">${hasExistingResult ? 'Already recorded' : ''}</span>
                                            </td>
                                        </tr>
                                    `;
                                }
                                scoreSheetContent.innerHTML += rowHTML;
                            });
                            
                            // Add event listeners for marks inputs based on classwork status
                            if (examData.has_classwork) {
                                // Add event listeners to exam marks inputs for real-time grade and remark calculation
                                const examMarksInputs = document.querySelectorAll('.exam-marks-input:not([readonly])');
                                examMarksInputs.forEach(input => {
                                    input.addEventListener('input', function() {
                                        const studentId = this.getAttribute('data-student-id');
                                        calculateFinalMarksAndGrade(studentId, examData.classwork_percentage);
                                    });
                                });
                                
                                // Add event listeners to classwork marks inputs for real-time grade and remark calculation
                                const classworkMarksInputs = document.querySelectorAll('.classwork-marks-input:not([readonly])');
                                classworkMarksInputs.forEach(input => {
                                    input.addEventListener('input', function() {
                                        const studentId = this.getAttribute('data-student-id');
                                        calculateFinalMarksAndGrade(studentId, examData.classwork_percentage);
                                    });
                                });
                            } else {
                                // Add event listeners to marks inputs for real-time grade and remark calculation (only for students without existing results)
                                const marksInputs = document.querySelectorAll('.marks-input:not([readonly])');
                                marksInputs.forEach(input => {
                                    input.addEventListener('input', function() {
                                        const marks = this.value;
                                        const studentId = this.getAttribute('data-student-id');
                                        
                                        // Get grade and remark for the entered marks
                                        const gradeRemark = getGradeAndRemarkForMarks(marks);
                                        
                                        // Find the corresponding row for this student
                                        const studentRow = document.querySelector(`tr[data-student-id="${studentId}"]`);
                                        if (studentRow) {
                                            const gradeInput = studentRow.querySelector('.grade-display');
                                            const remarkInput = studentRow.querySelector('.remark-display');
                                            const matchingRangeInfo = studentRow.querySelector('.matching-rule-range');
                                            
                                            // Update the grade and remark fields
                                            if (gradeInput) {
                                                gradeInput.value = gradeRemark.grade;
                                            }
                                            if (remarkInput) {
                                                remarkInput.value = gradeRemark.remark;
                                            }
                                            
                                            // Show which rule matched
                                            if (gradeRemark.matchedRule) {
                                                matchingRangeInfo.innerHTML = `
                                                    ${gradeRemark.matchedRule.min_score} - ${gradeRemark.matchedRule.max_score}
                                                    <div class="text-xs text-green-600">
                                                        Grade: ${gradeRemark.matchedRule.grade}, Remark: ${gradeRemark.matchedRule.remark}
                                                    </div>
                                                `;
                                            } else {
                                                matchingRangeInfo.textContent = '';
                                            }
                                        }
                                    });
                                });
                            }
                            
                            // Show score sheet
                            scoreSheet.classList.remove('hidden');
                            studentsSelection.classList.add('hidden');
                        })
                        .catch(error => {
                            console.error('Error loading existing results:', error);
                            // Fallback: show score sheet without existing results check
                            buildScoreSheetWithoutExistingResults(examData);
                        });
                } else {
                    // Fallback: show score sheet without existing results check
                    buildScoreSheetWithoutExistingResults(examData);
                }
            })
            .catch(error => {
                console.error('Error checking exam classwork:', error);
                // Fallback: show score sheet without classwork
                buildScoreSheetWithoutExistingResults({ has_classwork: false });
            });
    }
    
    // Fallback function to build score sheet without checking existing results
    function buildScoreSheetWithoutExistingResults(examData) {
        // Clear previous content
        scoreSheetContent.innerHTML = '';
        
        // Build score sheet content in table format
        selectedStudentsData.forEach(student => {
            let rowHTML = '';
            if (examData.has_classwork) {
                rowHTML = `
                    <tr data-student-id="${student.id}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <input type="hidden" name="student_ids[]" value="${student.id}">
                            ${student.name}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${student.admission_no}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <input type="number" name="exam_marks[]" min="0" max="100" step="0.1" 
                                   class="exam-marks-input w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                   placeholder="0-100"
                                   data-student-id="${student.id}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <input type="number" name="classwork_marks[]" min="0" max="100" step="0.1" 
                                   class="classwork-marks-input w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                   placeholder="0-100"
                                   data-student-id="${student.id}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <input type="number" name="final_marks[]" min="0" max="100" step="0.1" 
                                   class="final-marks-input w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm bg-gray-100" 
                                   placeholder="Calculated"
                                   data-student-id="${student.id}"
                                   readonly>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <input type="text" name="grades[]" readonly
                                   class="grade-display w-16 border border-gray-300 rounded-md shadow-sm py-1 px-2 sm:text-sm bg-gray-50" 
                                   value="" placeholder="Grade">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <input type="text" name="remarks[]" readonly
                                   class="remark-display w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 sm:text-sm bg-gray-50" 
                                   value="" placeholder="Remark">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 matching-rule-info">
                            <span class="matching-rule-range"></span>
                        </td>
                    </tr>
                `;
            } else {
                rowHTML = `
                    <tr data-student-id="${student.id}">
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <input type="hidden" name="student_ids[]" value="${student.id}">
                            ${student.name}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${student.admission_no}</td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <input type="number" name="marks[]" min="0" max="100" step="0.1" 
                                   class="marks-input w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" 
                                   placeholder="0-100"
                                   data-student-id="${student.id}">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <input type="text" name="grades[]" readonly
                                   class="grade-display w-16 border border-gray-300 rounded-md shadow-sm py-1 px-2 sm:text-sm bg-gray-50" 
                                   value="" placeholder="Grade">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <input type="text" name="remarks[]" readonly
                                   class="remark-display w-24 border border-gray-300 rounded-md shadow-sm py-1 px-2 sm:text-sm bg-gray-50" 
                                   value="" placeholder="Remark">
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 matching-rule-info">
                            <span class="matching-rule-range"></span>
                        </td>
                    </tr>
                `;
            }
            scoreSheetContent.innerHTML += rowHTML;
        });
        
        // Add event listeners for marks inputs based on classwork status
        if (examData.has_classwork) {
            // Add event listeners to exam marks inputs for real-time grade and remark calculation
            const examMarksInputs = document.querySelectorAll('.exam-marks-input');
            examMarksInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const studentId = this.getAttribute('data-student-id');
                    calculateFinalMarksAndGrade(studentId, examData.classwork_percentage);
                });
            });
            
            // Add event listeners to classwork marks inputs for real-time grade and remark calculation
            const classworkMarksInputs = document.querySelectorAll('.classwork-marks-input');
            classworkMarksInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const studentId = this.getAttribute('data-student-id');
                    calculateFinalMarksAndGrade(studentId, examData.classwork_percentage);
                });
            });
        } else {
            // Add event listeners to marks inputs for real-time grade and remark calculation
            const marksInputs = document.querySelectorAll('.marks-input');
            marksInputs.forEach(input => {
                input.addEventListener('input', function() {
                    const marks = this.value;
                    const studentId = this.getAttribute('data-student-id');
                    
                    // Get grade and remark for the entered marks
                    const gradeRemark = getGradeAndRemarkForMarks(marks);
                    
                    // Find the corresponding row for this student
                    const studentRow = document.querySelector(`tr[data-student-id="${studentId}"]`);
                    if (studentRow) {
                        const gradeInput = studentRow.querySelector('.grade-display');
                        const remarkInput = studentRow.querySelector('.remark-display');
                        const matchingRangeInfo = studentRow.querySelector('.matching-rule-range');
                        
                        // Update the grade and remark fields
                        if (gradeInput) {
                            gradeInput.value = gradeRemark.grade;
                        }
                        if (remarkInput) {
                            remarkInput.value = gradeRemark.remark;
                        }
                        
                        // Show which rule matched
                        if (gradeRemark.matchedRule) {
                            matchingRangeInfo.innerHTML = `
                                ${gradeRemark.matchedRule.min_score} - ${gradeRemark.matchedRule.max_score}
                                <div class="text-xs text-green-600">
                                    Grade: ${gradeRemark.matchedRule.grade}, Remark: ${gradeRemark.matchedRule.remark}
                                </div>
                            `;
                        } else {
                            matchingRangeInfo.textContent = '';
                        }
                    }
                });
            });
        }
        
        // Show score sheet
        scoreSheet.classList.remove('hidden');
        studentsSelection.classList.add('hidden');
    }
    
    // Function to calculate final marks and grade based on exam and classwork marks
    function calculateFinalMarksAndGrade(studentId, classworkPercentage) {
        // Get exam and classwork marks for this student
        const studentRow = document.querySelector(`tr[data-student-id="${studentId}"]`);
        if (!studentRow) return;
        
        const examMarksInput = studentRow.querySelector('.exam-marks-input');
        const classworkMarksInput = studentRow.querySelector('.classwork-marks-input');
        const finalMarksInput = studentRow.querySelector('.final-marks-input');
        const gradeInput = studentRow.querySelector('.grade-display');
        const remarkInput = studentRow.querySelector('.remark-display');
        const matchingRangeInfo = studentRow.querySelector('.matching-rule-range');
        
        if (!examMarksInput || !classworkMarksInput || !finalMarksInput) return;
        
        const examMarks = parseFloat(examMarksInput.value) || 0;
        const classworkMarks = parseFloat(classworkMarksInput.value) || 0;
        
        // Calculate final marks based on percentage
        const examPercentage = 100 - classworkPercentage;
        const finalMarks = ((examMarks * examPercentage) + (classworkMarks * classworkPercentage)) / 100;
        
        // Update final marks input
        finalMarksInput.value = finalMarks.toFixed(2);
        
        // Get grade and remark for the final marks
        const gradeRemark = getGradeAndRemarkForMarks(finalMarks);
        
        // Update the grade and remark fields
        if (gradeInput) {
            gradeInput.value = gradeRemark.grade;
        }
        if (remarkInput) {
            remarkInput.value = gradeRemark.remark;
        }
        
        // Show which rule matched
        if (gradeRemark.matchedRule) {
            matchingRangeInfo.innerHTML = `
                ${gradeRemark.matchedRule.min_score} - ${gradeRemark.matchedRule.max_score}
                <div class="text-xs text-green-600">
                    Grade: ${gradeRemark.matchedRule.grade}, Remark: ${gradeRemark.matchedRule.remark}
                </div>
            `;
        } else {
            matchingRangeInfo.textContent = '';
        }
    }
    
    // Function to look up grade and remark for marks entered on scoresheet form
    function getGradeAndRemarkForMarks(marks) {
        let grade = '';
        let remark = '';
        let matchedRule = null;
        
        // Check if marks is a valid number
        if (marks === '' || isNaN(marks)) {
            return { grade, remark, matchedRule };
        }
        
        // Convert marks to float for comparison
        const marksFloat = parseFloat(marks);
        
        // Loop through grading rules in reverse order to find the first matching rule
        for (let i = currentGradingRules.length - 1; i >= 0; i--) {
            const rule = currentGradingRules[i];
            if (marksFloat >= parseFloat(rule.min_score) && marksFloat <= parseFloat(rule.max_score)) {
                grade = rule.grade;
                remark = rule.remark;
                matchedRule = rule;
                break;
            }
        }
        
        return { grade, remark, matchedRule };
    }
    
    // Handle form submission for exam results
    document.getElementById('exam-results-form').addEventListener('submit', function(e) {
        e.preventDefault();
        
        // Show loading state
        const submitBtn = document.getElementById('submit-exam-results-btn');
        const originalText = submitBtn.textContent;
        submitBtn.textContent = 'Submitting...';
        submitBtn.disabled = true;
        
        // Get form data
        const formData = new FormData(this);
        
        // Submit the form via AJAX
        fetch('/exam_results/store-bulk', {
            method: 'POST',
            body: formData
        })
        .then(response => {
            // Check if response is JSON
            const contentType = response.headers.get('content-type');
            if (contentType && contentType.indexOf('application/json') !== -1) {
                return response.json();
            } else {
                // If not JSON, try to parse as text
                return response.text().then(text => {
                    // Try to parse as JSON, if fails return as text
                    try {
                        return JSON.parse(text);
                    } catch (e) {
                        // If it's not JSON, assume success if status is OK
                        return {
                            success: response.ok,
                            message: response.ok ? 'Exam results recorded successfully!' : 'Failed to record exam results.'
                        };
                    }
                });
            }
        })
        .then(data => {
            if (data.success) {
                showFlashMessage(data.message || 'Exam results recorded successfully!', 'success');
                // Redirect to exam results page after a short delay
                setTimeout(() => {
                    window.location.href = '/exam_results';
                }, 2000);
            } else {
                showFlashMessage(data.message || 'Failed to record exam results.', 'error');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            showFlashMessage('An error occurred while submitting the form. Please check that all required fields are filled correctly.', 'error');
        })
        .finally(() => {
            // Reset button state
            submitBtn.textContent = originalText;
            submitBtn.disabled = false;
        });
    });
    
    // Function to show flash messages
    function showFlashMessage(message, type) {
        // Remove any existing flash messages
        flashMessages.innerHTML = '';
        
        // Create flash message element
        const messageDiv = document.createElement('div');
        messageDiv.className = `rounded px-4 py-3 mb-4 text-sm ${
            type === 'success' 
                ? 'bg-green-100 border border-green-400 text-green-700' 
                : 'bg-red-100 border border-red-400 text-red-700'
        }`;
        messageDiv.textContent = message;
        
        // Add to flash messages container
        flashMessages.appendChild(messageDiv);
        
        // Scroll to top to see the message
        window.scrollTo({ top: 0, behavior: 'smooth' });
        
        // Remove message after 5 seconds
        setTimeout(() => {
            if (messageDiv.parentNode) {
                messageDiv.parentNode.removeChild(messageDiv);
            }
        }, 5000);
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>