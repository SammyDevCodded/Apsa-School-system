<?php 
$title = 'Edit Student'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Student</h1>
            <a href="/students" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Students
            </a>
        </div>

        <?php if (!empty($currentAcademicYear)): ?>
        <div class="mb-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
            <p class="text-sm text-blue-800">
                <strong>Current Academic Year:</strong> <?= htmlspecialchars($currentAcademicYear['name']) ?> 
                (<?= htmlspecialchars(date('M j, Y', strtotime($currentAcademicYear['start_date']))) ?> - 
                <?= htmlspecialchars(date('M j, Y', strtotime($currentAcademicYear['end_date']))) ?>)
            </p>
        </div>
        <?php endif; ?>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                
                <form action="/students/<?= $student['id'] ?>" method="POST" class="space-y-6" enctype="multipart/form-data">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="admission_no" class="block text-sm font-medium text-gray-700">Admission Number</label>
                            <div class="mt-1 flex rounded-md shadow-sm">
                                <input type="text" name="admission_no" id="admission_no" value="<?= htmlspecialchars($student['admission_no'] ?? '') ?>" required
                                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <button type="button" id="generate-admission-btn" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 rounded-r-md text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                                    Generate
                                </button>
                            </div>
                            <p id="admission_format_hint" class="text-xs text-gray-500 mt-1"><?= htmlspecialchars($formatDescription ?? 'Format: [Prefix]-[HHMMSS]') ?></p>
                        </div>

                        <div>
                            <label for="admission_date" class="block text-sm font-medium text-gray-700">Admission Date</label>
                            <input type="date" name="admission_date" id="admission_date" value="<?= htmlspecialchars($student['admission_date'] ?? date('Y-m-d')) ?>"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="academic_year_id" class="block text-sm font-medium text-gray-700">Academic Year (Optional)</label>
                            <select name="academic_year_id" id="academic_year_id"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">None (Keep existing value)</option>
                                <?php 
                                $academicYearModel = new \App\Models\AcademicYear();
                                $academicYears = $academicYearModel->getAll();
                                foreach ($academicYears as $year): ?>
                                    <option value="<?= $year['id'] ?>" <?= (isset($student['academic_year_id']) && $student['academic_year_id'] == $year['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($year['name']) ?> 
                                        (<?= htmlspecialchars(date('M j, Y', strtotime($year['start_date']))) ?> - 
                                        <?= htmlspecialchars(date('M j, Y', strtotime($year['end_date']))) ?>)
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="first_name" class="block text-sm font-medium text-gray-700">First Name</label>
                            <input type="text" name="first_name" id="first_name" value="<?= htmlspecialchars($student['first_name'] ?? '') ?>" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="last_name" class="block text-sm font-medium text-gray-700">Last Name</label>
                            <input type="text" name="last_name" id="last_name" value="<?= htmlspecialchars($student['last_name'] ?? '') ?>" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
                            <input type="date" name="dob" id="dob" value="<?= htmlspecialchars($student['dob'] ?? '') ?>" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
                            <select name="gender" id="gender" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Gender</option>
                                <option value="male" <?= (isset($student['gender']) && $student['gender'] == 'male') ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= (isset($student['gender']) && $student['gender'] == 'female') ? 'selected' : '' ?>>Female</option>
                                <option value="other" <?= (isset($student['gender']) && $student['gender'] == 'other') ? 'selected' : '' ?>>Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                            <select name="class_id" id="class_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Class</option>
                                <?php foreach ($classes ?? [] as $class): ?>
                                    <option value="<?= $class['id'] ?>" <?= (isset($student['class_id']) && $student['class_id'] == $class['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($class['name'] . ' (' . $class['level'] . ')') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="guardian_name" class="block text-sm font-medium text-gray-700">Guardian Name</label>
                            <input type="text" name="guardian_name" id="guardian_name" value="<?= htmlspecialchars($student['guardian_name'] ?? '') ?>"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="guardian_phone" class="block text-sm font-medium text-gray-700">Guardian Phone</label>
                            <input type="text" name="guardian_phone" id="guardian_phone" value="<?= htmlspecialchars($student['guardian_phone'] ?? '') ?>" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="profile_picture" class="block text-sm font-medium text-gray-700">Profile Picture</label>
                            <input type="file" name="profile_picture" id="profile_picture" accept="image/*"
                                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            <?php if (!empty($student['profile_picture'])): ?>
                                <div class="mt-2">
                                    <img src="/storage/uploads/<?= htmlspecialchars($student['profile_picture']) ?>" alt="Current Profile Picture" class="h-20 w-20 object-cover rounded-full">
                                </div>
                            <?php endif; ?>
                        </div>
                        
                        <!-- Student Category Fields -->
                        <div>
                            <label for="student_category" class="block text-sm font-medium text-gray-700">Student Category</label>
                            <select name="student_category" id="student_category"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="regular_day" <?= (isset($student['student_category']) && $student['student_category'] == 'regular_day') ? 'selected' : '' ?>>Regular Student (Day)</option>
                                <option value="regular_boarding" <?= (isset($student['student_category']) && $student['student_category'] == 'regular_boarding') ? 'selected' : '' ?>>Regular Student (Boarding)</option>
                                <option value="international" <?= (isset($student['student_category']) && $student['student_category'] == 'international') ? 'selected' : '' ?>>International Student</option>
                            </select>
                        </div>

                        <div id="category_details_container" style="display: none;">
                            <label for="student_category_details" class="block text-sm font-medium text-gray-700">Category Details <span id="category_details_asterisk" class="text-red-500 hidden">*</span></label>
                            <textarea name="student_category_details" id="student_category_details" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                placeholder="Additional details about the student's category (e.g., country for international students, dormitory for boarding students)"><?= htmlspecialchars($student['student_category_details'] ?? '') ?></textarea>
                        </div>
                    </div>

                    <div>
                        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
                        <textarea name="address" id="address" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"><?= htmlspecialchars($student['address'] ?? '') ?></textarea>
                    </div>

                    <div>
                        <label for="medical_info" class="block text-sm font-medium text-gray-700">Medical Information</label>
                        <textarea name="medical_info" id="medical_info" rows="3"
                            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                            placeholder="Enter any medical conditions, allergies, or special health needs that require attention"><?= htmlspecialchars($student['medical_info'] ?? '') ?></textarea>
                        <p class="text-xs text-gray-500 mt-1">Include details about allergies, chronic conditions, medications, or any special medical needs.</p>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Student
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const generateBtn = document.getElementById('generate-admission-btn');
    const admissionInput = document.getElementById('admission_no');
    
    if (generateBtn && admissionInput) {
        generateBtn.addEventListener('click', function() {
            // Make AJAX request to generate new admission number
            fetch('/settings/generate/admission')
                .then(response => response.json())
                .then(data => {
                    if (data.admission_number) {
                        admissionInput.value = data.admission_number;
                    }
                })
                .catch(error => {
                    console.error('Error generating admission number:', error);
                    // Fallback to client-side generation
                    const prefix = 'EPI'; // Default prefix
                    const timestamp = new Date().toLocaleTimeString('en-GB', { hour12: false }).replace(/:/g, '');
                    admissionInput.value = prefix + '-' + timestamp;
                });
        });
    }

    // Handle student category changes
    const categorySelect = document.getElementById('student_category');
    const categoryDetailsContainer = document.getElementById('category_details_container');
    const categoryDetailsInput = document.getElementById('student_category_details');
    const categoryDetailsAsterisk = document.getElementById('category_details_asterisk');

    function updateCategoryDetails() {
        if (!categorySelect || !categoryDetailsContainer || !categoryDetailsInput) return;
        
        const type = categorySelect.value;
        if (type === 'regular_day') {
            categoryDetailsContainer.style.display = 'none';
            categoryDetailsInput.required = false;
        } else if (type === 'regular_boarding') {
            categoryDetailsContainer.style.display = 'block';
            categoryDetailsInput.required = false;
            if (categoryDetailsAsterisk) categoryDetailsAsterisk.classList.add('hidden');
        } else if (type === 'international') {
            categoryDetailsContainer.style.display = 'block';
            categoryDetailsInput.required = true;
            if (categoryDetailsAsterisk) categoryDetailsAsterisk.classList.remove('hidden');
        }
    }

    if (categorySelect) {
        categorySelect.addEventListener('change', updateCategoryDetails);
        // initialize on load
        updateCategoryDetails();
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>