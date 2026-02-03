<?php 
$title = 'Add Exam'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Add Exam</h1>
            <a href="/exams" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Exams
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form action="/exams" method="POST" class="space-y-6" id="examForm">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-700">Exam Name</label>
                            <input type="text" name="name" id="name" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="academic_year_id" class="block text-sm font-medium text-gray-700">Academic Year</label>
                            <select name="academic_year_id" id="academic_year_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Academic Year</option>
                                <?php if (isset($currentAcademicYear)): ?>
                                    <option value="<?= $currentAcademicYear['id'] ?>" selected>
                                        <?= htmlspecialchars($currentAcademicYear['name']) ?>
                                    </option>
                                <?php endif; ?>
                            </select>
                            <?php if (isset($currentAcademicYear)): ?>
                                <input type="hidden" id="current_academic_year_id" value="<?= $currentAcademicYear['id'] ?>">
                                <input type="hidden" id="current_academic_year_name" value="<?= htmlspecialchars($currentAcademicYear['name']) ?>">
                            <?php endif; ?>
                        </div>

                        <div>
                            <label for="term" class="block text-sm font-medium text-gray-700">Term</label>
                            <select name="term" id="term" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Term</option>
                                <?php if (isset($currentAcademicYear) && !empty($currentAcademicYear['term'])): ?>
                                    <option value="<?= htmlspecialchars($currentAcademicYear['term']) ?>" selected>
                                        <?= htmlspecialchars($currentAcademicYear['term']) ?>
                                    </option>
                                <?php else: ?>
                                    <option value="1st Term" <?= (!isset($currentAcademicYear) || empty($currentAcademicYear['term'])) ? 'selected' : '' ?>>1st Term</option>
                                    <option value="2nd Term">2nd Term</option>
                                    <option value="3rd Term">3rd Term</option>
                                <?php endif; ?>
                                <option value="other">Other (specify)</option>
                            </select>
                            <input type="text" id="custom_term" name="custom_term" placeholder="Enter custom term" 
                                class="mt-2 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                style="display: none;">
                            <?php if (isset($currentAcademicYear) && !empty($currentAcademicYear['term'])): ?>
                                <input type="hidden" id="current_term" value="<?= htmlspecialchars($currentAcademicYear['term']) ?>">
                            <?php endif; ?>
                        </div>

                        <div>
                            <label class="block text-sm font-medium text-gray-700">Classes</label>
                            <div class="mt-1 border border-gray-300 rounded-md shadow-sm p-3 max-h-60 overflow-y-auto">
                                <?php if (isset($classes) && !empty($classes)): ?>
                                    <?php foreach ($classes as $class): ?>
                                        <div class="flex items-center mb-2">
                                            <input type="checkbox" name="class_ids[]" value="<?= $class['id'] ?>" id="class_<?= $class['id'] ?>"
                                                class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                            <label for="class_<?= $class['id'] ?>" class="ml-2 block text-sm text-gray-900">
                                                <?= htmlspecialchars($class['name'] . ' (' . $class['level'] . ')') ?>
                                            </label>
                                        </div>
                                    <?php endforeach; ?>
                                <?php else: ?>
                                    <p class="text-sm text-gray-500">No classes available</p>
                                <?php endif; ?>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Select one or more classes for this exam</p>
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" id="date" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="description" class="block text-sm font-medium text-gray-700">Description</label>
                            <textarea name="description" id="description" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>

                        <div>
                            <label for="grading_scale_id" class="block text-sm font-medium text-gray-700">Grading Scale</label>
                            <select name="grading_scale_id" id="grading_scale_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Grading Scale</option>
                                <?php if (isset($gradingScales) && !empty($gradingScales)): ?>
                                    <?php foreach ($gradingScales as $scale): ?>
                                        <option value="<?= $scale['id'] ?>">
                                            <?= htmlspecialchars($scale['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                <?php endif; ?>
                            </select>
                            <p class="mt-1 text-sm text-gray-500">Select the grading scale to be used for this exam</p>
                        </div>

                        <!-- Classwork Section -->
                        <div class="sm:col-span-2">
                            <div class="flex items-center">
                                <input type="checkbox" name="has_classwork" id="has_classwork" value="1"
                                    class="h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                <label for="has_classwork" class="ml-2 block text-sm font-medium text-gray-700">
                                    Enable Classwork Component
                                </label>
                            </div>
                            <p class="mt-1 text-sm text-gray-500">Check this box to include classwork scores for this exam</p>
                            
                            <div id="classwork-fields" class="mt-4 hidden">
                                <label for="classwork_percentage" class="block text-sm font-medium text-gray-700">
                                    Classwork Percentage (%)
                                </label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="number" name="classwork_percentage" id="classwork_percentage" min="0" max="100" step="0.1"
                                        class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pr-12 sm:text-sm border-gray-300 rounded-md"
                                        placeholder="0.00">
                                    <div class="absolute inset-y-0 right-0 flex items-center">
                                        <span class="h-5 w-5 text-gray-500 pr-2" aria-hidden="true">%</span>
                                    </div>
                                </div>
                                <p class="mt-1 text-sm text-gray-500">
                                    Enter the percentage of the total grade that classwork will contribute (0-100%)
                                </p>
                            </div>
                        </div>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Exam
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const academicYearSelect = document.getElementById('academic_year_id');
    const termSelect = document.getElementById('term');
    const customTermInput = document.getElementById('custom_term');
    const examForm = document.getElementById('examForm');
    const classCheckboxes = document.querySelectorAll('input[name="class_ids[]"]');
    const hasClassworkCheckbox = document.getElementById('has_classwork');
    const classworkFields = document.getElementById('classwork-fields');
    
    // Get current values
    const currentAcademicYearId = document.getElementById('current_academic_year_id')?.value;
    const currentAcademicYearName = document.getElementById('current_academic_year_name')?.value;
    const currentTerm = document.getElementById('current_term')?.value;
    
    // Handle term selection change
    termSelect.addEventListener('change', function() {
        if (this.value === 'other') {
            customTermInput.style.display = 'block';
            customTermInput.focus();
        } else {
            customTermInput.style.display = 'none';
        }
    });
    
    // Handle academic year change with warning
    if (academicYearSelect && currentAcademicYearId) {
        academicYearSelect.addEventListener('change', function() {
            if (this.value !== currentAcademicYearId) {
                const selectedOption = this.options[this.selectedIndex];
                const selectedYearName = selectedOption.text;
                const confirmChange = confirm(`You are about to change from the current academic year "${currentAcademicYearName}" to "${selectedYearName}". This may affect data consistency. Are you sure you want to proceed?`);
                if (!confirmChange) {
                    this.value = currentAcademicYearId;
                }
            }
        });
    }
    
    // Handle term change with warning
    if (termSelect && currentTerm) {
        termSelect.addEventListener('change', function() {
            let newTerm = this.value;
            if (newTerm === 'other') {
                return; // We'll handle this on form submission
            }
            if (newTerm !== currentTerm) {
                const confirmChange = confirm(`You are about to change from the current term "${currentTerm}" to "${newTerm}". This may affect data consistency. Are you sure you want to proceed?`);
                if (!confirmChange) {
                    this.value = currentTerm;
                }
            }
        });
    }
    
    // Handle form submission
    examForm.addEventListener('submit', function(e) {
        // Check if at least one class is selected
        let selectedClasses = document.querySelectorAll('input[name="class_ids[]"]:checked');
        if (selectedClasses.length === 0) {
            alert('Please select at least one class for this exam.');
            e.preventDefault();
            return;
        }
        
        // Check if term is selected
        if (!termSelect.value) {
            alert('Please select a term for this exam.');
            e.preventDefault();
            return;
        }
        
        // If "Other" term is selected, use the custom term value
        if (termSelect.value === 'other') {
            if (customTermInput.value.trim() === '') {
                alert('Please enter a custom term name.');
                e.preventDefault();
                return;
            }
            // Create a hidden input to submit the custom term
            let hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'term';
            hiddenInput.value = customTermInput.value.trim();
            examForm.appendChild(hiddenInput);
        } else {
            // Create a hidden input to submit the selected term
            let hiddenInput = document.createElement('input');
            hiddenInput.type = 'hidden';
            hiddenInput.name = 'term';
            hiddenInput.value = termSelect.value;
            examForm.appendChild(hiddenInput);
        }
    });

    // Handle classwork checkbox change
    hasClassworkCheckbox.addEventListener('change', function() {
        if (this.checked) {
            classworkFields.style.display = 'block';
        } else {
            classworkFields.style.display = 'none';
        }
    });

});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>