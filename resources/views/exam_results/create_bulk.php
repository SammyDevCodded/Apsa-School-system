<?php 
$title = 'Record Exam Results (Bulk)'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Record Exam Results (Bulk)</h1>
            <a href="/exam_results" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Results
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form id="bulk-exam-form" class="space-y-6">
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
                            <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                            <select name="class_id" id="class_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select an exam first</option>
                                <!-- Classes will be loaded dynamically based on selected exam -->
                            </select>
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
                        Load Score Sheet for All Students
                    </button>
                    <!-- Test button for grade matching -->
                    <button type="button" id="test-grade-matching" class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-blue-600 hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-blue-500">
                        Test Grade Matching
                    </button>
                </div>
            </div>
        </div>

        <!-- Score Sheet Section -->
        <div id="score-sheet" class="mt-8 bg-white shadow overflow-hidden sm:rounded-lg hidden">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Score Sheet</h3>
                <form id="exam-results-form" action="/exam_results/store-bulk" method="POST">
                    <input type="hidden" name="exam_id" id="form-exam-id">
                    <input type="hidden" name="class_id" id="form-class-id">
                    <input type="hidden" name="subject_id" id="form-subject-id">
                    <input type="hidden" name="grading_scale_id" id="form-grading-scale-id">
                    <div id="score-sheet-content">
                        <!-- Score sheet will be loaded here dynamically -->
                    </div>
                    <div class="mt-6 flex justify-end">
                        <button type="button" id="back-to-selection-btn"
                            class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Back to Selection
                        </button>
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Submit Results for Selected Students
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
    const scoreSheet = document.getElementById('score-sheet');
    const scoreSheetContent = document.getElementById('score-sheet-content');
    const submitResultsBtn = document.getElementById('submit-results-btn');
    const backToSelectionBtn = document.getElementById('back-to-selection-btn');
    
    // Form elements
    const examIdSelect = document.getElementById('exam_id');
    const classIdSelect = document.getElementById('class_id');
    const subjectIdSelect = document.getElementById('subject_id');
    const gradingScaleIdSelect = document.getElementById('grading_scale_id');
    
    // Form hidden inputs
    const formExamId = document.getElementById('form-exam-id');
    const formClassId = document.getElementById('form-class-id');
    const formSubjectId = document.getElementById('form-subject-id');
    const formGradingScaleId = document.getElementById('form-grading-scale-id');
    
    // Current grading rules
    let currentGradingRules = [];
    
    // Check if a grading scale is already selected on page load
    if (gradingScaleIdSelect && gradingScaleIdSelect.value) {
        console.log('Grading scale already selected on page load:', gradingScaleIdSelect.value);
        loadGradingRules(gradingScaleIdSelect.value);
    }
    
    // Load classes when exam is selected
    examIdSelect.addEventListener('change', function() {
        const examId = this.value;
        if (examId) {
            loadClassesByExam(examId);
        } else {
            // Reset class dropdown
            classIdSelect.innerHTML = '<option value="">Select an exam first</option>';
            // Reset subject dropdown
            subjectIdSelect.innerHTML = '<option value="">Select Class First</option>';
        }
    });
    
    // Load subjects when class is selected
    classIdSelect.addEventListener('change', function() {
        const classId = this.value;
        if (classId) {
            loadAssignedSubjectsByClass(classId);
            loadStudents(classId);
        } else {
            // Reset subject dropdown
            subjectIdSelect.innerHTML = '<option value="">Select Class First</option>';
        }
    });
    
    // Load grading rules when grading scale is selected
    gradingScaleIdSelect.addEventListener('change', function() {
        const scaleId = this.value;
        console.log('Grading scale changed to:', scaleId);
        if (scaleId) {
            loadGradingRules(scaleId);
        } else {
            currentGradingRules = [];
        }
    });
    
    // Load score sheet button
    loadScoreSheetBtn.addEventListener('click', function() {
        const examId = examIdSelect.value;
        const classId = classIdSelect.value;
        const subjectId = subjectIdSelect.value;
        const gradingScaleId = gradingScaleIdSelect.value;
        
        console.log('Load score sheet button clicked with values:', {
            examId: examId,
            classId: classId,
            subjectId: subjectId,
            gradingScaleId: gradingScaleId
        });
        
        if (!examId || !classId || !subjectId || !gradingScaleId) {
            alert('Please select all fields first.');
            return;
        }
        
        // Show students selection section
        studentsSelection.classList.remove('hidden');
        
        // Set form hidden inputs
        formExamId.value = examId;
        formClassId.value = classId;
        formSubjectId.value = subjectId;
        formGradingScaleId.value = gradingScaleId;
        
        console.log('Form hidden inputs set:', {
            examId: formExamId.value,
            classId: formClassId.value,
            subjectId: formSubjectId.value,
            gradingScaleId: formGradingScaleId.value
        });
        
        // Load grading rules if not already loaded
        if (currentGradingRules.length === 0 && gradingScaleId) {
            console.log('Loading grading rules for scale:', gradingScaleId);
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
    
    // Submit results button
    submitResultsBtn.addEventListener('click', function() {
        // Get selected students
        const selectedStudents = [];
        const studentCheckboxes = studentsList.querySelectorAll('input[type="checkbox"]:checked');
        studentCheckboxes.forEach(checkbox => {
            selectedStudents.push(checkbox.value);
        });
        
        if (selectedStudents.length === 0) {
            alert('Please select at least one student.');
            return;
        }
        
        // Check if grading rules are loaded, if not, load them first
        const gradingScaleId = gradingScaleIdSelect.value;
        if (currentGradingRules.length === 0 && gradingScaleId) {
            console.log('Loading grading rules before showing score sheet...');
            loadGradingRules(gradingScaleId);
            
            // Wait a bit for grading rules to load, then show score sheet
            setTimeout(() => {
                showScoreSheet(selectedStudents);
            }, 500);
        } else {
            // Show score sheet with all students, but only selected ones enabled
            showScoreSheet(selectedStudents);
        }
    });
    
    // Back to selection button
    backToSelectionBtn.addEventListener('click', function() {
        scoreSheet.classList.add('hidden');
        studentsSelection.classList.remove('hidden');
    });
    
    // Test grade matching button
    document.getElementById('test-grade-matching').addEventListener('click', function() {
        testGradeMatchingWithActualData();
    });
    
    // Function to load classes by exam
    function loadClassesByExam(examId) {
        fetch(`/exam_results/classes-by-exam?exam_id=${examId}`)
            .then(response => response.json())
            .then(data => {
                classIdSelect.innerHTML = '<option value="">Select Class</option>';
                data.classes.forEach(classItem => {
                    const option = document.createElement('option');
                    option.value = classItem.id;
                    option.textContent = classItem.name + ' (' + classItem.level + ')';
                    classIdSelect.appendChild(option);
                });
                
                // Reset subject dropdown
                subjectIdSelect.innerHTML = '<option value="">Select Class First</option>';
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
                data.subjects.forEach(subject => {
                    const option = document.createElement('option');
                    option.value = subject.id;
                    option.textContent = subject.name + ' (' + subject.code + ')';
                    subjectIdSelect.appendChild(option);
                });
            })
            .catch(error => {
                console.error('Error loading subjects:', error);
                subjectIdSelect.innerHTML = '<option value="">Error loading subjects</option>';
            });
    }
    
    // Function to load students by class
    function loadStudents(classId) {
        fetch(`/exam_results/students-by-class?class_id=${classId}`)
            .then(response => response.json())
            .then(data => {
                studentsList.innerHTML = '';
                data.students.forEach(student => {
                    const div = document.createElement('div');
                    div.className = 'flex items-center';
                    div.innerHTML = `
                        <input type="checkbox" id="student_${student.id}" name="student_ids[]" value="${student.id}" class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                        <label for="student_${student.id}" class="ml-2 block text-sm text-gray-900">
                            ${student.first_name} ${student.last_name} (${student.admission_no})
                        </label>
                    `;
                    studentsList.appendChild(div);
                });
            })
            .catch(error => {
                console.error('Error loading students:', error);
                studentsList.innerHTML = '<div class="text-red-500">Error loading students</div>';
            });
    }
    
    // Function to load grading rules with comprehensive debugging
    function loadGradingRules(scaleId) {
        console.log('=== LOADING GRADING RULES DEBUG ===');
        console.log('Attempting to load grading rules for scale ID:', scaleId);
        
        if (!scaleId) {
            console.log('No scale ID provided, clearing grading rules');
            currentGradingRules = [];
            console.log('=== END LOADING GRADING RULES DEBUG ===');
            return Promise.resolve([]);
        }
        
        console.log('Fetching from URL:', `/exam_results/grading-rules?scale_id=${scaleId}`);
        
        return fetch(`/exam_results/grading-rules?scale_id=${scaleId}`)
            .then(response => {
                console.log('Response received:', response.status, response.statusText);
                console.log('Response headers:', [...response.headers.entries()]);
                
                if (!response.ok) {
                    throw new Error('Network response was not ok: ' + response.status + ' ' + response.statusText);
                }
                
                // Log raw response text first
                return response.clone().text().then(text => {
                    console.log('Raw response text:', text);
                    return response.json();
                });
            })
            .then(data => {
                console.log('Parsed JSON data:', data);
                
                if (data && data.rules && Array.isArray(data.rules)) {
                    currentGradingRules = data.rules;
                    console.log('Successfully loaded', currentGradingRules.length, 'grading rules');
                    console.log('Loaded rules:', currentGradingRules);
                    
                    // Log first rule structure for debugging
                    if (currentGradingRules.length > 0) {
                        console.log('First rule structure:', currentGradingRules[0]);
                    }
                } else {
                    console.log('No valid rules found in response data');
                    console.log('Data structure:', data);
                    currentGradingRules = [];
                }
                
                console.log('=== END LOADING GRADING RULES DEBUG ===');
                return currentGradingRules;
            })
            .catch(error => {
                console.error('Error loading grading rules:', error);
                currentGradingRules = [];
                console.log('=== END LOADING GRADING RULES DEBUG (WITH ERROR) ===');
                return [];
            });
    }
    
    // Function to update all grade/remark fields based on current marks
    function updateAllGradeRemarkFields() {
        if (currentGradingRules.length === 0) {
            console.log('No grading rules available, skipping update');
            return;
        }
        
        console.log('Updating all grade/remark fields with current rules:', currentGradingRules);
        const inputs = document.querySelectorAll('.marks-input:not(:disabled)');
        console.log('Found', inputs.length, 'enabled marks inputs to update');
        
        let updatedCount = 0;
        inputs.forEach(input => {
            const marks = input.value || 0;
            const gradeRemark = getGradeAndRemarkForMarks(marks);
            console.log('Updating grade/remark for marks:', marks, 'Result:', gradeRemark);
            
            // Find the corresponding grade and remark inputs for this student
            const studentContainer = input.closest('.border');
            const gradeInput = studentContainer.querySelector('.grade-display');
            const remarkInput = studentContainer.querySelector('.remark-display');
            
            if (gradeInput) {
                gradeInput.value = gradeRemark.grade;
                console.log('Updated grade input to:', gradeRemark.grade);
                updatedCount++;
            }
            if (remarkInput) {
                remarkInput.value = gradeRemark.remark;
                console.log('Updated remark input to:', gradeRemark.remark);
                updatedCount++;
            }
        });
        
        console.log('Updated', updatedCount, 'grade/remark fields');
    }
    
    // Function to get grade and remark for marks with comprehensive debugging
    function getGradeAndRemarkForMarks(marks) {
        console.log('=== GRADE MATCHING DEBUG START ===');
        console.log('Input marks:', marks);
        console.log('Marks type:', typeof marks);
        
        // Convert marks to number, handle empty/invalid values
        const numericMarks = parseFloat(marks);
        console.log('Parsed numeric marks:', numericMarks);
        console.log('Is NaN:', isNaN(numericMarks));
        
        // If marks are not a valid number, return empty values
        if (isNaN(numericMarks)) {
            console.log('Marks is not a valid number, returning empty values');
            console.log('=== GRADE MATCHING DEBUG END ===');
            return { grade: '', remark: '' };
        }
        
        // Check if grading rules are loaded
        console.log('Current grading rules count:', currentGradingRules.length);
        console.log('Current grading rules:', currentGradingRules);
        
        if (!currentGradingRules || currentGradingRules.length === 0) {
            console.log('No grading rules available for matching, returning empty values');
            console.log('=== GRADE MATCHING DEBUG END ===');
            return { grade: '', remark: '' };
        }
        
        // Process each rule to find a match
        console.log('Checking', currentGradingRules.length, 'rules for match');
        
        for (let i = 0; i < currentGradingRules.length; i++) {
            const rule = currentGradingRules[i];
            console.log('Checking rule', i, ':', rule);
            
            // Convert rule scores to numbers
            const minScore = parseFloat(rule.min_score);
            const maxScore = parseFloat(rule.max_score);
            
            console.log('Rule scores - Min:', minScore, 'Max:', maxScore);
            console.log('Comparing marks', numericMarks, '>=', minScore, '&&', numericMarks, '<=', maxScore);
            
            // Check if the numeric marks fall within this rule's range (inclusive)
            if (numericMarks >= minScore && numericMarks <= maxScore) {
                console.log('MATCH FOUND! Rule:', rule);
                console.log('Returning grade:', rule.grade, 'remark:', rule.remark);
                console.log('=== GRADE MATCHING DEBUG END ===');
                return { 
                    grade: rule.grade || '', 
                    remark: rule.remark || '' 
                };
            } else {
                console.log('No match for this rule');
            }
        }
        
        // If no rule matches, return empty values
        console.log('No matching rule found, returning empty values');
        console.log('=== GRADE MATCHING DEBUG END ===');
        return { grade: '', remark: '' };
    }
    
    // Simple test function to verify grade matching
    function testGradeMatching() {
        // Simulate the database structure
        currentGradingRules = [
            { id: "3", scale_id: "1", min_score: "80.00", max_score: "100.00", grade: "1", remark: "Excellent", created_at: "2025-10-18 14:23:21", updated_at: "2025-10-18 14:23:21" },
            { id: "4", scale_id: "1", min_score: "70.00", max_score: "79.00", grade: "2", remark: "Very Good", created_at: "2025-10-18 14:24:14", updated_at: "2025-10-18 14:24:14" },
            { id: "5", scale_id: "1", min_score: "60.00", max_score: "69.00", grade: "3", remark: "good", created_at: "2025-10-18 14:25:49", updated_at: "2025-10-18 14:25:49" },
            { id: "6", scale_id: "1", min_score: "50.00", max_score: "59.00", grade: "4", remark: "Fair", created_at: "2025-10-18 14:26:55", updated_at: "2025-10-18 14:26:55" },
            { id: "7", scale_id: "1", min_score: "40.00", max_score: "49.00", grade: "5", remark: "Poor", created_at: "2025-10-18 14:28:07", updated_at: "2025-10-18 14:28:07" },
            { id: "8", scale_id: "1", min_score: "0.00", max_score: "39.00", grade: "6", remark: "Fail", created_at: "2025-10-18 14:28:47", updated_at: "2025-10-18 14:28:47" }
        ];
        
        // Test various marks
        const testCases = [
            { marks: "", expectedGrade: "", expectedRemark: "" },
            { marks: "25", expectedGrade: "6", expectedRemark: "Fail" },
            { marks: "45", expectedGrade: "5", expectedRemark: "Poor" },
            { marks: "55", expectedGrade: "4", expectedRemark: "Fair" },
            { marks: "65", expectedGrade: "3", expectedRemark: "good" },
            { marks: "75", expectedGrade: "2", expectedRemark: "Very Good" },
            { marks: "85", expectedGrade: "1", expectedRemark: "Excellent" },
            { marks: "100", expectedGrade: "1", expectedRemark: "Excellent" }
        ];
        
        console.log("Testing grade matching:");
        testCases.forEach((testCase, index) => {
            const result = getGradeAndRemarkForMarks(testCase.marks);
            const passed = result.grade === testCase.expectedGrade && result.remark === testCase.expectedRemark;
            console.log(`Test ${index + 1}: Marks="${testCase.marks}" -> Grade="${result.grade}", Remark="${result.remark}" ${passed ? "✓" : "✗"}`);
        });
        
        // Reset grading rules
        currentGradingRules = [];
    }
    
    // Add a simple test button
    document.addEventListener('DOMContentLoaded', function() {
        // Add a test button to the page
        const testBtn = document.createElement('button');
        testBtn.textContent = 'Test Grade Matching';
        testBtn.style.position = 'fixed';
        testBtn.style.top = '10px';
        testBtn.style.right = '10px';
        testBtn.style.zIndex = '9999';
        testBtn.style.padding = '5px 10px';
        testBtn.style.backgroundColor = '#007bff';
        testBtn.style.color = 'white';
        testBtn.style.border = 'none';
        testBtn.style.borderRadius = '3px';
        testBtn.style.cursor = 'pointer';
        testBtn.addEventListener('click', testGradeMatching);
        document.body.appendChild(testBtn);
    });
    
    // Test function to verify grade matching with actual data structure
    function testGradeMatchingWithActualData() {
        console.log('Testing grade matching with actual data structure...');
        
        // Simulate actual grading rules from the database (based on what we saw in the test)
        const testRules = [
            { id: "3", scale_id: "1", min_score: "80.00", max_score: "100.00", grade: "1", remark: "Excellent", created_at: "2025-10-18 14:23:21", updated_at: "2025-10-18 14:23:21" },
            { id: "4", scale_id: "1", min_score: "70.00", max_score: "79.00", grade: "2", remark: "Very Good", created_at: "2025-10-18 14:24:14", updated_at: "2025-10-18 14:24:14" },
            { id: "5", scale_id: "1", min_score: "60.00", max_score: "69.00", grade: "3", remark: "good", created_at: "2025-10-18 14:25:49", updated_at: "2025-10-18 14:25:49" },
            { id: "6", scale_id: "1", min_score: "50.00", max_score: "59.00", grade: "4", remark: "Fair", created_at: "2025-10-18 14:26:55", updated_at: "2025-10-18 14:26:55" },
            { id: "7", scale_id: "1", min_score: "40.00", max_score: "49.00", grade: "5", remark: "Poor", created_at: "2025-10-18 14:28:07", updated_at: "2025-10-18 14:28:07" },
            { id: "8", scale_id: "1", min_score: "0.00", max_score: "39.00", grade: "6", remark: "Fail", created_at: "2025-10-18 14:28:47", updated_at: "2025-10-18 14:28:47" }
        ];
        
        // Set test rules as current grading rules
        currentGradingRules = testRules;
        
        // Test various marks
        const testMarks = [0, 25, 39, 40, 45, 50, 55, 60, 65, 70, 75, 80, 90, 100];
        
        console.log('Testing grade matching with actual database structure:');
        testMarks.forEach(marks => {
            const result = getGradeAndRemarkForMarks(marks);
            console.log(`Marks: ${marks} -> Grade: ${result.grade}, Remark: ${result.remark}`);
        });
        
        // Test edge cases
        console.log('Testing edge cases:');
        const edgeCases = [-10, 150, "", null, undefined, "abc"];
        edgeCases.forEach(marks => {
            const result = getGradeAndRemarkForMarks(marks);
            console.log(`Marks: ${marks} -> Grade: ${result.grade}, Remark: ${result.remark}`);
        });
        
        // Reset grading rules
        currentGradingRules = [];
    }
    
    // Uncomment the line below to run the test
    // testGradeMatchingWithActualData();
    
    // Test function to verify the entire grade matching flow
    function testCompleteGradeMatchingFlow() {
        console.log('Testing complete grade matching flow...');
        
        // Simulate selecting a grading scale
        console.log('Simulating grading scale selection...');
        
        // Simulate actual grading rules from the database
        const testRules = [
            { id: "3", scale_id: "1", min_score: "80.00", max_score: "100.00", grade: "1", remark: "Excellent" },
            { id: "4", scale_id: "1", min_score: "70.00", max_score: "79.00", grade: "2", remark: "Very Good" },
            { id: "5", scale_id: "1", min_score: "60.00", max_score: "69.00", grade: "3", remark: "good" },
            { id: "6", scale_id: "1", min_score: "50.00", max_score: "59.00", grade: "4", remark: "Fair" },
            { id: "7", scale_id: "1", min_score: "40.00", max_score: "49.00", grade: "5", remark: "Poor" },
            { id: "8", scale_id: "1", min_score: "0.00", max_score: "39.00", grade: "6", remark: "Fail" }
        ];
        
        // Set test rules as current grading rules
        currentGradingRules = testRules;
        console.log('Grading rules loaded:', currentGradingRules);
        
        // Test various marks to verify they match the correct grade ranges
        const testCases = [
            { marks: 85, expectedGrade: "1", expectedRemark: "Excellent" },
            { marks: 75, expectedGrade: "2", expectedRemark: "Very Good" },
            { marks: 65, expectedGrade: "3", expectedRemark: "good" },
            { marks: 55, expectedGrade: "4", expectedRemark: "Fair" },
            { marks: 45, expectedGrade: "5", expectedRemark: "Poor" },
            { marks: 25, expectedGrade: "6", expectedRemark: "Fail" },
            { marks: 100, expectedGrade: "1", expectedRemark: "Excellent" },
            { marks: 0, expectedGrade: "6", expectedRemark: "Fail" }
        ];
        
        console.log('Testing grade matching for various marks:');
        let allTestsPassed = true;
        
        testCases.forEach((testCase, index) => {
            const result = getGradeAndRemarkForMarks(testCase.marks);
            const gradeMatch = result.grade === testCase.expectedGrade;
            const remarkMatch = result.remark === testCase.expectedRemark;
            
            console.log(`Test ${index + 1}: Marks=${testCase.marks}, Expected=Grade:${testCase.expectedGrade}/Remark:${testCase.expectedRemark}, Actual=Grade:${result.grade}/Remark:${result.remark}, Passed=${gradeMatch && remarkMatch}`);
            
            if (!gradeMatch || !remarkMatch) {
                allTestsPassed = false;
            }
        });
        
        console.log('All tests passed:', allTestsPassed);
        
        // Reset grading rules
        currentGradingRules = [];
    }
    
    // Uncomment the line below to run the complete test
    // testCompleteGradeMatchingFlow();
    
    // Function to show score sheet with proper grading rules handling
    function showScoreSheet(selectedStudents) {
        console.log('=== SHOW SCORE SHEET DEBUG ===');
        console.log('Showing score sheet for', selectedStudents.length, 'selected students');
        
        // Hide students selection
        studentsSelection.classList.add('hidden');
        
        // Show information about selected students
        const infoDiv = document.createElement('div');
        infoDiv.className = 'bg-blue-50 border border-blue-200 rounded-md p-4 mb-4';
        infoDiv.innerHTML = `
            <p class="text-blue-800">
                <strong>Note:</strong> All students from the selected class are displayed below. 
                Only the ${selectedStudents.length} selected student(s) can have marks entered. 
                Unselected students are disabled.
            </p>
        `;
        scoreSheetContent.appendChild(infoDiv);
        
        // Check if we have grading rules, if not, try to load them
        const gradingScaleId = document.getElementById('grading_scale_id').value;
        console.log('Current grading scale ID:', gradingScaleId);
        console.log('Current grading rules count:', currentGradingRules.length);
        
        if (currentGradingRules.length === 0 && gradingScaleId) {
            console.log('No grading rules available, loading them first...');
            // Load grading rules first, then render the score sheet
            loadGradingRules(gradingScaleId).then(() => {
                console.log('Grading rules loaded, rendering score sheet');
                renderScoreSheetContent(selectedStudents);
            });
        } else {
            console.log('Grading rules already available or no scale selected, rendering immediately');
            // We already have grading rules or no scale selected, render immediately
            renderScoreSheetContent(selectedStudents);
        }
        
        console.log('=== END SHOW SCORE SHEET DEBUG ===');
    }
    
    // Helper function to render the actual score sheet content
    function renderScoreSheetContent(selectedStudents) {
        console.log('=== RENDER SCORE SHEET CONTENT DEBUG ===');
        console.log('Rendering score sheet content with', currentGradingRules.length, 'grading rules');
        
        // Build score sheet content with ALL students from the class, not just selected ones
        let scoreSheetHTML = '<div class="grid grid-cols-1 gap-4">';
        
        // Get all student checkboxes to display all students
        const allStudentCheckboxes = studentsList.querySelectorAll('input[type="checkbox"]');
        console.log('Total students to display:', allStudentCheckboxes.length);
        
        allStudentCheckboxes.forEach(checkbox => {
            const studentId = checkbox.value;
            const studentLabel = checkbox.nextElementSibling;
            const studentName = studentLabel.textContent.trim();
            
            // Get initial grade and remark for empty marks using current grading rules
            let initialGradeRemark = { grade: '', remark: '' };
            if (currentGradingRules.length > 0) {
                // For initial display, we show empty grade/remark since no marks are entered yet
                initialGradeRemark = { grade: '', remark: '' };
            }
            
            // Check if this student was selected
            const isSelected = selectedStudents.includes(studentId);
            console.log('Student', studentId, 'isSelected:', isSelected);
            
            scoreSheetHTML += `
                <div class="border border-gray-200 rounded-md p-4 ${isSelected ? '' : 'bg-gray-100'}" data-student-id="${studentId}">
                    <div class="font-medium text-gray-900">${studentName} ${isSelected ? '' : '<span class="text-gray-500 text-sm">(Not selected)</span>'}</div>
                    <input type="hidden" name="student_ids[]" value="${studentId}">
                    <div class="mt-2 grid grid-cols-1 gap-2 sm:grid-cols-3">
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Marks</label>
                            <input type="number" name="marks[]" min="0" max="100" step="0.1" 
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm marks-input ${isSelected ? '' : 'bg-gray-100'}" 
                                   placeholder="Enter marks (0-100)"
                                   data-student-id="${studentId}"
                                   ${isSelected ? '' : 'disabled'}>
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Grade</label>
                            <input type="text" name="grades[]" readonly
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm grade-display ${isSelected ? '' : 'bg-gray-100'}" 
                                   value="${initialGradeRemark.grade}" placeholder="Grade">
                        </div>
                        <div class="sm:col-span-1">
                            <label class="block text-sm font-medium text-gray-700">Remark</label>
                            <input type="text" name="remarks[]" readonly
                                   class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 sm:text-sm remark-display ${isSelected ? '' : 'bg-gray-100'}" 
                                   value="${initialGradeRemark.remark}" placeholder="Remark">
                        </div>
                    </div>
                </div>
            `;
        });
        scoreSheetHTML += '</div>';
        
        const scoreSheetContainer = document.createElement('div');
        scoreSheetContainer.innerHTML = scoreSheetHTML;
        scoreSheetContent.appendChild(scoreSheetContainer);
        
        // Add event listeners to marks inputs for real-time grade and remark calculation
        const marksInputs = document.querySelectorAll('.marks-input:not(:disabled)');
        console.log('Found', marksInputs.length, 'marks input fields to attach event listeners to');
        
        marksInputs.forEach(input => {
            console.log('Attaching event listener to input:', input);
            input.addEventListener('input', function() {
                console.log('=== MARKS INPUT EVENT DEBUG ===');
                const marks = this.value;
                console.log('Marks input changed to:', marks);
                console.log('Input element:', this);
                
                const studentId = this.getAttribute('data-student-id');
                console.log('Student ID:', studentId);
                
                // Get grade and remark for the entered marks
                const gradeRemark = getGradeAndRemarkForMarks(marks);
                console.log('Grade/Remark result:', gradeRemark);
                
                // Find the corresponding grade and remark inputs for this student
                const studentContainer = this.closest('.border');
                console.log('Student container:', studentContainer);
                
                const gradeInput = studentContainer.querySelector('.grade-display');
                const remarkInput = studentContainer.querySelector('.remark-display');
                
                console.log('Grade input field:', gradeInput);
                console.log('Remark input field:', remarkInput);
                
                // Update the grade and remark fields
                if (gradeInput) {
                    gradeInput.value = gradeRemark.grade;
                    console.log('Updated grade field to:', gradeRemark.grade);
                } else {
                    console.log('Could not find grade input field');
                }
                
                if (remarkInput) {
                    remarkInput.value = gradeRemark.remark;
                    console.log('Updated remark field to:', gradeRemark.remark);
                } else {
                    console.log('Could not find remark input field');
                }
                
                console.log('=== END MARKS INPUT EVENT DEBUG ===');
            });
        });
        
        // Show score sheet
        scoreSheet.classList.remove('hidden');
        console.log('Score sheet displayed');
        console.log('=== END RENDER SCORE SHEET CONTENT DEBUG ===');
    }
    
    // Add a verification button to test the exact functionality
    // Manual test function to verify the complete flow
    function manualTestCompleteFlow() {
        console.log('=== MANUAL COMPLETE FLOW TEST ===');
        
        // Step 1: Simulate loading grading rules (what should happen when selecting a grading scale)
        console.log('Step 1: Loading grading rules');
        const sampleRules = [
            { min_score: "80.00", max_score: "100.00", grade: "1", remark: "Excellent" },
            { min_score: "70.00", max_score: "79.00", grade: "2", remark: "Very Good" },
            { min_score: "60.00", max_score: "69.00", grade: "3", remark: "good" },
            { min_score: "50.00", max_score: "59.00", grade: "4", remark: "Fair" },
            { min_score: "40.00", max_score: "49.00", grade: "5", remark: "Poor" },
            { min_score: "0.00", max_score: "39.00", grade: "6", remark: "Fail" }
        ];
        
        currentGradingRules = sampleRules;
        console.log('Loaded sample rules:', currentGradingRules);
        
        // Step 2: Simulate entering marks and getting grade/remark
        console.log('Step 2: Testing grade/remark matching');
        const testMarks = ["25", "45", "55", "65", "75", "85", "100", ""];
        
        testMarks.forEach(mark => {
            const result = getGradeAndRemarkForMarks(mark);
            console.log(`Marks: "${mark}" -> Grade: "${result.grade}", Remark: "${result.remark}"`);
        });
        
        console.log('=== MANUAL COMPLETE FLOW TEST END ===');
    }
    
    // Function to test the actual API call
    function testAPIFetch() {
        console.log('=== TESTING API FETCH ===');
        const gradingScaleId = document.getElementById('grading_scale_id').value;
        
        if (!gradingScaleId) {
            console.log('No grading scale selected');
            alert('Please select a grading scale first');
            console.log('=== END TESTING API FETCH ===');
            return;
        }
        
        console.log('Testing API fetch for grading scale ID:', gradingScaleId);
        
        fetch(`/exam_results/grading-rules?scale_id=${gradingScaleId}`)
            .then(response => {
                console.log('Response status:', response.status);
                console.log('Response OK:', response.ok);
                return response.text();
            })
            .then(text => {
                console.log('Raw response text:', text);
                try {
                    const data = JSON.parse(text);
                    console.log('Parsed JSON:', data);
                    
                    if (data.rules && Array.isArray(data.rules)) {
                        console.log('Successfully received', data.rules.length, 'rules');
                        console.log('Rules:', data.rules);
                        
                        // Test with sample marks
                        if (data.rules.length > 0) {
                            currentGradingRules = data.rules;
                            const testResult = getGradeAndRemarkForMarks(85);
                            console.log('Test with marks 85:', testResult);
                        }
                    }
                } catch (e) {
                    console.error('Error parsing JSON:', e);
                }
                
                console.log('=== END TESTING API FETCH ===');
            })
            .catch(error => {
                console.error('Fetch error:', error);
                console.log('=== END TESTING API FETCH ===');
            });
    }
    
    // Consolidate all DOMContentLoaded event listeners into one
    document.addEventListener('DOMContentLoaded', function() {
        // Remove any existing test buttons first
        const existingButtons = document.querySelectorAll('[data-test-button], [data-test-btn]');
        existingButtons.forEach(btn => btn.remove());
        
        // Add verification button
        const verifyBtn = document.createElement('button');
        verifyBtn.textContent = 'Verify Grade Matching';
        verifyBtn.setAttribute('data-test-button', 'true');
        verifyBtn.style.position = 'fixed';
        verifyBtn.style.top = '10px';
        verifyBtn.style.right = '10px';
        verifyBtn.style.zIndex = '9999';
        verifyBtn.style.padding = '5px 10px';
        verifyBtn.style.backgroundColor = '#28a745';
        verifyBtn.style.color = 'white';
        verifyBtn.style.border = 'none';
        verifyBtn.style.borderRadius = '3px';
        verifyBtn.style.cursor = 'pointer';
        verifyBtn.style.fontSize = '12px';
        verifyBtn.addEventListener('click', manualTestCompleteFlow);
        document.body.appendChild(verifyBtn);
        
        // Add API test button
        const apiTestBtn = document.createElement('button');
        apiTestBtn.textContent = 'Test API Fetch';
        apiTestBtn.setAttribute('data-test-btn', 'true');
        apiTestBtn.style.position = 'fixed';
        apiTestBtn.style.top = '50px';
        apiTestBtn.style.right = '10px';
        apiTestBtn.style.zIndex = '9999';
        apiTestBtn.style.padding = '8px 12px';
        apiTestBtn.style.backgroundColor = '#28a745';
        apiTestBtn.style.color = 'white';
        apiTestBtn.style.border = 'none';
        apiTestBtn.style.borderRadius = '4px';
        apiTestBtn.style.cursor = 'pointer';
        apiTestBtn.style.fontSize = '14px';
        apiTestBtn.style.fontWeight = 'bold';
        apiTestBtn.style.marginTop = '5px';
        apiTestBtn.addEventListener('click', testAPIFetch);
        document.body.appendChild(apiTestBtn);
    });
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>
    //     dbTestBtn.style.zIndex = '9999';
    //     dbTestBtn.addEventListener('click', testWithActualDatabaseStructure);
    //     document.body.appendChild(dbTestBtn);
    // });
    
    // Function to test the actual grading rules loading
    function testActualGradingRulesLoading() {
        console.log('=== TESTING ACTUAL GRADING RULES LOADING ===');
        
        const gradingScaleId = document.getElementById('grading_scale_id').value;
        console.log('Current grading scale ID:', gradingScaleId);
        
        if (!gradingScaleId) {
            console.log('No grading scale selected, cannot test');
            console.log('=== END TESTING ACTUAL GRADING RULES LOADING ===');
            return;
        }
        
        console.log('Testing fetch to:', `/exam_results/grading-rules?scale_id=${gradingScaleId}`);
        
        fetch(`/exam_results/grading-rules?scale_id=${gradingScaleId}`)
            .then(response => {
                console.log('Fetch response status:', response.status);
                console.log('Fetch response headers:', [...response.headers.entries()]);
                return response.text(); // Get raw text first
            })
            .then(text => {
                console.log('Raw response text:', text);
                
                // Try to parse as JSON
                try {
                    const data = JSON.parse(text);
                    console.log('Parsed JSON data:', data);
                    
                    if (data.rules && Array.isArray(data.rules)) {
                        console.log('Rules array length:', data.rules.length);
                        console.log('First rule (if exists):', data.rules[0]);
                        
                        // Set as current grading rules for testing
                        currentGradingRules = data.rules;
                        console.log('Set fetched rules as current grading rules');
                        
                        // Test with a sample mark
                        const testResult = getGradeAndRemarkForMarks(85);
                        console.log('Test result with 85 marks:', testResult);
                    } else {
                        console.log('No rules array found in response');
                    }
                } catch (parseError) {
                    console.error('Failed to parse JSON:', parseError);
                }
                
                console.log('=== END TESTING ACTUAL GRADING RULES LOADING ===');
            })
            .catch(error => {
                console.error('Fetch error:', error);
                console.log('=== END TESTING ACTUAL GRADING RULES LOADING ===');
            });
    }
    
    // Add a button to run this test
    // Uncomment the lines below to add the test button:
    // document.addEventListener('DOMContentLoaded', function() {
    //     const fetchTestBtn = document.createElement('button');
    //     fetchTestBtn.textContent = 'Test Rules Fetch';
    //     fetchTestBtn.style.position = 'fixed';
    //     fetchTestBtn.style.top = '130px';
    //     fetchTestBtn.style.right = '10px';
    //     fetchTestBtn.style.zIndex = '9999';
    //     fetchTestBtn.addEventListener('click', testActualGradingRulesLoading);
    //     document.body.appendChild(fetchTestBtn);
    // });
    
    // Comprehensive end-to-end test
    function runEndToEndTest() {
        console.log('=== RUNNING END-TO-END TEST ===');
        
        // Clear any existing rules
        currentGradingRules = [];
        
        // Simulate the exact process:
        // 1. Load grading rules
        // 2. Enter marks
        // 3. Verify grade/remark updates
        
        console.log('Step 1: Simulating grading scale selection and rules loading');
        
        // Simulate the database structure exactly
        const testRules = [
            { id: "3", scale_id: "1", min_score: "80.00", max_score: "100.00", grade: "1", remark: "Excellent", created_at: "2025-10-18 14:23:21", updated_at: "2025-10-18 14:23:21" },
            { id: "4", scale_id: "1", min_score: "70.00", max_score: "79.00", grade: "2", remark: "Very Good", created_at: "2025-10-18 14:24:14", updated_at: "2025-10-18 14:24:14" },
            { id: "5", scale_id: "1", min_score: "60.00", max_score: "69.00", grade: "3", remark: "good", created_at: "2025-10-18 14:25:49", updated_at: "2025-10-18 14:25:49" },
            { id: "6", scale_id: "1", min_score: "50.00", max_score: "59.00", grade: "4", remark: "Fair", created_at: "2025-10-18 14:26:55", updated_at: "2025-10-18 14:26:55" },
            { id: "7", scale_id: "1", min_score: "40.00", max_score: "49.00", grade: "5", remark: "Poor", created_at: "2025-10-18 14:28:07", updated_at: "2025-10-18 14:28:07" },
            { id: "8", scale_id: "1", min_score: "0.00", max_score: "39.00", grade: "6", remark: "Fail", created_at: "2025-10-18 14:28:47", updated_at: "2025-10-18 14:28:47" }
        ];
        
        currentGradingRules = testRules;
        console.log('Loaded test rules:', currentGradingRules.length, 'rules');
        
        // Test various marks
        const testMarks = [25, 45, 55, 65, 75, 85, 100];
        console.log('Step 2: Testing grade matching for various marks');
        
        testMarks.forEach(mark => {
            const result = getGradeAndRemarkForMarks(mark);
            console.log(`Marks: ${mark} -> Grade: "${result.grade}", Remark: "${result.remark}"`);
        });
        
        // Test edge cases
        console.log('Step 3: Testing edge cases');
        const edgeCases = [0, 39, 40, 49, 50, 59, 60, 69, 70, 79, 80, 100];
        edgeCases.forEach(mark => {
            const result = getGradeAndRemarkForMarks(mark);
            console.log(`Edge case - Marks: ${mark} -> Grade: "${result.grade}", Remark: "${result.remark}"`);
        });
        
        console.log('=== END-TO-END TEST COMPLETE ===');
    }
    
    // Add a visible test button
    document.addEventListener('DOMContentLoaded', function() {
        // Create a test panel
        const testPanel = document.createElement('div');
        testPanel.style.position = 'fixed';
        testPanel.style.top = '10px';
        testPanel.style.right = '10px';
        testPanel.style.zIndex = '9999';
        testPanel.style.backgroundColor = 'white';
        testPanel.style.border = '1px solid #ccc';
        testPanel.style.padding = '10px';
        testPanel.style.borderRadius = '5px';
        testPanel.style.boxShadow = '0 2px 10px rgba(0,0,0,0.1)';
        testPanel.innerHTML = `
            <div style="font-weight: bold; margin-bottom: 5px;">Grade Matching Tests</div>
            <button id="manual-test-btn" style="display: block; margin: 2px 0; padding: 3px 5px; font-size: 12px;">Manual Test</button>
            <button id="db-structure-test-btn" style="display: block; margin: 2px 0; padding: 3px 5px; font-size: 12px;">DB Structure Test</button>
            <button id="fetch-test-btn" style="display: block; margin: 2px 0; padding: 3px 5px; font-size: 12px;">Fetch Test</button>
            <button id="end-to-end-test-btn" style="display: block; margin: 2px 0; padding: 3px 5px; font-size: 12px;">End-to-End Test</button>
        `;
        document.body.appendChild(testPanel);
        
        // Add event listeners
        document.getElementById('manual-test-btn').addEventListener('click', manualTestGradeMatching);
        document.getElementById('db-structure-test-btn').addEventListener('click', testWithActualDatabaseStructure);
        document.getElementById('fetch-test-btn').addEventListener('click', testActualGradingRulesLoading);
        document.getElementById('end-to-end-test-btn').addEventListener('click', runEndToEndTest);
    });
    
    // Simple verification test - this is what the system should do
    function verifyGradeMatchingFunctionality() {
        console.log('=== VERIFYING GRADE MATCHING FUNCTIONALITY ===');
        
        // This is exactly what should happen in the system:
        // 1. Load grading rules from settings page (grading scale)
        // 2. When user enters marks, match against those rules
        // 3. Return corresponding grade and remark
        
        // Simulate loading grading rules from the settings page (grading scale)
        const gradingScaleFromSettings = [
            { min_score: 80.00, max_score: 100.00, grade: "1", remark: "Excellent" },
            { min_score: 70.00, max_score: 79.00, grade: "2", remark: "Very Good" },
            { min_score: 60.00, max_score: 69.00, grade: "3", remark: "good" },
            { min_score: 50.00, max_score: 59.00, grade: "4", remark: "Fair" },
            { min_score: 40.00, max_score: 49.00, grade: "5", remark: "Poor" },
            { min_score: 0.00, max_score: 39.00, grade: "6", remark: "Fail" }
        ];
        
        // Set as current grading rules (this is what loadGradingRules does)
        currentGradingRules = gradingScaleFromSettings;
        
        // Test the exact functionality you want:
        console.log('Testing the exact functionality you requested:');
        
        // Example 1: Marks 85 should return grade "1" and remark "Excellent"
        const result1 = getGradeAndRemarkForMarks(85);
        console.log('Marks 85 -> Grade:', result1.grade, 'Remark:', result1.remark);
        
        // Example 2: Marks 25 should return grade "6" and remark "Fail"
        const result2 = getGradeAndRemarkForMarks(25);
        console.log('Marks 25 -> Grade:', result2.grade, 'Remark:', result2.remark);
        
        // Example 3: Marks 65 should return grade "3" and remark "good"
        const result3 = getGradeAndRemarkForMarks(65);
        console.log('Marks 65 -> Grade:', result3.grade, 'Remark:', result3.remark);
        
        console.log('=== VERIFICATION COMPLETE ===');
        
        // Reset for clean state
        currentGradingRules = [];
    }
    
    // Add a verification button to test the exact functionality
    document.addEventListener('DOMContentLoaded', function() {
        // Remove any existing test buttons first
        const existingButtons = document.querySelectorAll('[data-test-button]');
        existingButtons.forEach(btn => btn.remove());
        
        // Add verification button
        const verifyBtn = document.createElement('button');
        verifyBtn.textContent = 'Verify Grade Matching';
        verifyBtn.setAttribute('data-test-button', 'true');
        verifyBtn.style.position = 'fixed';
        verifyBtn.style.top = '10px';
        verifyBtn.style.right = '10px';
        verifyBtn.style.zIndex = '9999';
        verifyBtn.style.padding = '5px 10px';
        verifyBtn.style.backgroundColor = '#28a745';
        verifyBtn.style.color = 'white';
        verifyBtn.style.border = 'none';
        verifyBtn.style.borderRadius = '3px';
        verifyBtn.style.cursor = 'pointer';
        verifyBtn.style.fontSize = '12px';
        verifyBtn.addEventListener('click', verifyGradeMatchingFunctionality);
        document.body.appendChild(verifyBtn);
    });

    // Manual test function to verify the complete flow
    function manualTestCompleteFlow() {
        console.log('=== MANUAL COMPLETE FLOW TEST ===');
        
        // Step 1: Simulate loading grading rules (what should happen when selecting a grading scale)
        console.log('Step 1: Loading grading rules');
        const sampleRules = [
            { min_score: "80.00", max_score: "100.00", grade: "1", remark: "Excellent" },
            { min_score: "70.00", max_score: "79.00", grade: "2", remark: "Very Good" },
            { min_score: "60.00", max_score: "69.00", grade: "3", remark: "good" },
            { min_score: "50.00", max_score: "59.00", grade: "4", remark: "Fair" },
            { min_score: "40.00", max_score: "49.00", grade: "5", remark: "Poor" },
            { min_score: "0.00", max_score: "39.00", grade: "6", remark: "Fail" }
        ];
        
        currentGradingRules = sampleRules;
        console.log('Loaded sample rules:', currentGradingRules);
        
        // Step 2: Simulate entering marks and getting grade/remark
        console.log('Step 2: Testing grade/remark matching');
        const testMarks = ["25", "45", "55", "65", "75", "85", "100", ""];
        
        testMarks.forEach(mark => {
            const result = getGradeAndRemarkForMarks(mark);
            console.log(`Marks: "${mark}" -> Grade: "${result.grade}", Remark: "${result.remark}"`);
        });
        
        console.log('=== MANUAL COMPLETE FLOW TEST END ===');
    }
    
    // Add test button to the page
    document.addEventListener('DOMContentLoaded', function() {
        // Remove existing test buttons
        const existingTestButtons = document.querySelectorAll('[data-test-btn]');
        existingTestButtons.forEach(btn => btn.remove());
        
        // Add comprehensive test button
        const testBtn = document.createElement('button');
        testBtn.textContent = 'Test Complete Flow';
        testBtn.setAttribute('data-test-btn', 'true');
        testBtn.style.position = 'fixed';
        testBtn.style.top = '10px';
        testBtn.style.right = '10px';
        testBtn.style.zIndex = '9999';
        testBtn.style.padding = '8px 12px';
        testBtn.style.backgroundColor = '#007bff';
        testBtn.style.color = 'white';
        testBtn.style.border = 'none';
        testBtn.style.borderRadius = '4px';
        testBtn.style.cursor = 'pointer';
        testBtn.style.fontSize = '14px';
        testBtn.style.fontWeight = 'bold';
        testBtn.addEventListener('click', manualTestCompleteFlow);
        document.body.appendChild(testBtn);
    });

});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>