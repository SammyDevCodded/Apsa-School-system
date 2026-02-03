<?php 
$title = 'Assign Subjects - ' . htmlspecialchars($class['name']); 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Back button -->
        <div class="mb-6">
            <a href="/classes/<?= $class['id'] ?>" class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Class Details
            </a>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $_SESSION['flash_success'] ?></span>
        </div>
        <?php unset($_SESSION['flash_success']); endif; ?>

        <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $_SESSION['flash_error'] ?></span>
        </div>
        <?php unset($_SESSION['flash_error']); endif; ?>

        <!-- Assignment Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Assign Subjects to <?= htmlspecialchars($class['name']) ?></h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Select subjects and students to assign subjects to the class.</p>
            </div>
            <div class="border-t border-gray-200">
                <form action="/classes/<?= $class['id'] ?>/assign-subjects" method="POST" id="assignmentForm">
                    <div class="px-4 py-5 sm:px-6">
                        <!-- Subjects Selection -->
                        <div class="mb-8">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Select Subjects</h4>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <?php if (empty($subjects)): ?>
                                    <p class="text-gray-500">No subjects available.</p>
                                <?php else: ?>
                                    <?php foreach ($subjects as $subject): ?>
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                   id="subject_<?= $subject['id'] ?>" 
                                                   name="subjects[]" 
                                                   value="<?= $subject['id'] ?>"
                                                   class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500"
                                                   <?php if (in_array($subject['id'], $assignedSubjectIds)): ?>checked<?php endif; ?>>
                                            <label for="subject_<?= $subject['id'] ?>" class="ml-3 block text-sm font-medium text-gray-700">
                                                <?= htmlspecialchars($subject['name']) ?> (<?= htmlspecialchars($subject['code']) ?>)
                                                <?php if (in_array($subject['id'], $assignedSubjectIds)): ?>
                                                    <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                        Assigned
                                                    </span>
                                                <?php endif; ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Assignment Type -->
                        <div class="mb-8">
                            <h4 class="text-md font-medium text-gray-900 mb-4">Assignment Type</h4>
                            <div class="flex items-center mb-4">
                                <input type="radio" 
                                       id="assign_to_all" 
                                       name="assign_to_all" 
                                       value="1" 
                                       class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500"
                                       checked>
                                <label for="assign_to_all" class="ml-3 block text-sm font-medium text-gray-700">
                                    Assign to entire class
                                </label>
                            </div>
                            <div class="flex items-center">
                                <input type="radio" 
                                       id="assign_to_specific" 
                                       name="assign_to_all" 
                                       value="0" 
                                       class="h-4 w-4 text-indigo-600 border-gray-300 focus:ring-indigo-500">
                                <label for="assign_to_specific" class="ml-3 block text-sm font-medium text-gray-700">
                                    Assign to specific students
                                </label>
                            </div>
                        </div>

                        <!-- Students Selection (Hidden by default) -->
                        <div id="studentsSelection" class="mb-8 hidden">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-md font-medium text-gray-900">Select Students</h4>
                                <button type="button" id="selectAllStudents" class="text-sm text-indigo-600 hover:text-indigo-900">
                                    Select All
                                </button>
                            </div>
                            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
                                <?php if (empty($students)): ?>
                                    <p class="text-gray-500">No students in this class.</p>
                                <?php else: ?>
                                    <?php foreach ($students as $student): ?>
                                        <div class="flex items-center">
                                            <input type="checkbox" 
                                                   id="student_<?= $student['id'] ?>" 
                                                   name="students[]" 
                                                   value="<?= $student['id'] ?>"
                                                   class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500 student-checkbox">
                                            <label for="student_<?= $student['id'] ?>" class="ml-3 block text-sm font-medium text-gray-700">
                                                <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?> 
                                                (<?= htmlspecialchars($student['admission_no']) ?>)
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </div>
                        </div>

                        <!-- Submit Button -->
                        <div class="flex justify-end">
                            <button type="submit" 
                                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Save Assignments
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
    // Toggle students selection based on assignment type
    document.addEventListener('DOMContentLoaded', function() {
        const assignToAll = document.getElementById('assign_to_all');
        const assignToSpecific = document.getElementById('assign_to_specific');
        const studentsSelection = document.getElementById('studentsSelection');
        const selectAllStudents = document.getElementById('selectAllStudents');
        const studentCheckboxes = document.querySelectorAll('.student-checkbox');

        assignToSpecific.addEventListener('change', function() {
            if (this.checked) {
                studentsSelection.classList.remove('hidden');
            }
        });

        assignToAll.addEventListener('change', function() {
            if (this.checked) {
                studentsSelection.classList.add('hidden');
            }
        });

        // Select all students functionality
        selectAllStudents.addEventListener('click', function() {
            const allChecked = Array.from(studentCheckboxes).every(checkbox => checkbox.checked);
            
            studentCheckboxes.forEach(function(checkbox) {
                checkbox.checked = !allChecked;
            });
            
            this.textContent = allChecked ? 'Deselect All' : 'Select All';
        });

        // Update select all button text based on checkbox states
        studentCheckboxes.forEach(function(checkbox) {
            checkbox.addEventListener('change', function() {
                const allChecked = Array.from(studentCheckboxes).every(cb => cb.checked);
                selectAllStudents.textContent = allChecked ? 'Deselect All' : 'Select All';
            });
        });
    });
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>