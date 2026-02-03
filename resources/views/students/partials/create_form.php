<form action="/students" method="POST" class="space-y-6" enctype="multipart/form-data">
    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
        <div>
            <label for="admission_no" class="block text-sm font-medium text-gray-700">Admission Number</label>
            <div class="mt-1 flex rounded-md shadow-sm">
                <input type="text" name="admission_no" id="admission_no" value="<?= htmlspecialchars($defaultAdmissionNo ?? '') ?>" required
                    class="flex-1 min-w-0 block w-full px-3 py-2 rounded-l-md border border-gray-300 focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <button type="button" id="generate-admission-btn" class="inline-flex items-center px-3 py-2 border border-l-0 border-gray-300 rounded-r-md text-sm font-medium text-gray-700 bg-gray-50 hover:bg-gray-100 focus:outline-none focus:ring-1 focus:ring-indigo-500 focus:border-indigo-500">
                    Generate
                </button>
            </div>
            <p class="text-xs text-gray-500 mt-1">Format: [Prefix][HHMMSS] - Example: EPI-143025</p>
        </div>

        <div>
            <label for="admission_date" class="block text-sm font-medium text-gray-700">Admission Date</label>
            <input type="date" name="admission_date" id="admission_date" value="<?= date('Y-m-d') ?>"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
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
            <label for="dob" class="block text-sm font-medium text-gray-700">Date of Birth</label>
            <input type="date" name="dob" id="dob"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div>
            <label for="gender" class="block text-sm font-medium text-gray-700">Gender</label>
            <select name="gender" id="gender"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Select Gender</option>
                <option value="male">Male</option>
                <option value="female">Female</option>
                <option value="other">Other</option>
            </select>
        </div>

        <div>
            <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
            <select name="class_id" id="class_id"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="">Select Class</option>
                <?php foreach ($classes ?? [] as $class): ?>
                    <option value="<?= $class['id'] ?>">
                        <?= htmlspecialchars($class['name'] . ' (' . $class['level'] . ')') ?>
                    </option>
                <?php endforeach; ?>
            </select>
        </div>

        <div>
            <label for="guardian_name" class="block text-sm font-medium text-gray-700">Guardian Name</label>
            <input type="text" name="guardian_name" id="guardian_name"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div>
            <label for="guardian_phone" class="block text-sm font-medium text-gray-700">Guardian Phone</label>
            <input type="text" name="guardian_phone" id="guardian_phone"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
        </div>

        <div>
            <label for="profile_picture" class="block text-sm font-medium text-gray-700">Profile Picture</label>
            <input type="file" name="profile_picture" id="profile_picture" accept="image/*"
                class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
        </div>
        
        <!-- Student Category Fields -->
        <div>
            <label for="student_category" class="block text-sm font-medium text-gray-700">Student Category</label>
            <select name="student_category" id="student_category"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                <option value="regular_day">Regular Student (Day)</option>
                <option value="regular_boarding">Regular Student (Boarding)</option>
                <option value="international">International Student</option>
            </select>
        </div>

        <div>
            <label for="student_category_details" class="block text-sm font-medium text-gray-700">Category Details</label>
            <textarea name="student_category_details" id="student_category_details" rows="3"
                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                placeholder="Additional details about the student's category (e.g., country for international students, dormitory for boarding students)"></textarea>
        </div>
    </div>

    <div>
        <label for="address" class="block text-sm font-medium text-gray-700">Address</label>
        <textarea name="address" id="address" rows="3"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
    </div>

    <div>
        <label for="medical_info" class="block text-sm font-medium text-gray-700">Medical Information</label>
        <textarea name="medical_info" id="medical_info" rows="3"
            class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
            placeholder="Enter any medical conditions, allergies, or special health needs that require attention"></textarea>
        <p class="text-xs text-gray-500 mt-1">Include details about allergies, chronic conditions, medications, or any special medical needs.</p>
    </div>

    <div class="flex justify-end">
        <button type="button" id="closeAddStudentModal" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
            Cancel
        </button>
        <button type="submit"
            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            Save Student
        </button>
    </div>
</form>

<?php if (!empty($currentAcademicYear)): ?>
<div class="mt-4 p-4 bg-blue-50 border border-blue-200 rounded-md">
    <p class="text-sm text-blue-800">
        <strong>Current Academic Year:</strong> <?= htmlspecialchars($currentAcademicYear['name']) ?> 
        (<?= htmlspecialchars(date('M j, Y', strtotime($currentAcademicYear['start_date']))) ?> - 
        <?= htmlspecialchars(date('M j, Y', strtotime($currentAcademicYear['end_date']))) ?>)
    </p>
</div>
<?php endif; ?>

<script>
// Re-attach the generate admission number functionality for the modal
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
    
    // Handle cancel button in modal
    const cancelBtn = document.getElementById('closeAddStudentModal');
    if (cancelBtn) {
        cancelBtn.addEventListener('click', function() {
            document.getElementById('addStudentModal').classList.add('hidden');
        });
    }
});
</script>