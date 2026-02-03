<?php 
$title = 'Add Timetable Entry'; 
// Check if this is an AJAX request
$isAjax = !empty($_SERVER['HTTP_X_REQUESTED_WITH']) && strtolower($_SERVER['HTTP_X_REQUESTED_WITH']) === 'xmlhttprequest';

if (!$isAjax) {
    ob_start();
}
?>

<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-semibold text-gray-900">Add Timetable Entry</h1>
    <button id="close-add-form" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
        Close
    </button>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:p-6">
        <form action="/timetables" method="POST" id="add-timetable-form" class="space-y-6">
            <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                <div>
                    <label for="academic_year_id" class="block text-sm font-medium text-gray-700">Academic Year</label>
                    <select name="academic_year_id" id="academic_year_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select Academic Year</option>
                        <?php foreach ($academicYears ?? [] as $year): ?>
                            <option value="<?= $year['id'] ?>" 
                                data-name="<?= htmlspecialchars($year['name']) ?>"
                                <?= (isset($currentAcademicYear) && $currentAcademicYear && $currentAcademicYear['id'] == $year['id'] && !isset($_POST['academic_year_id'])) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($year['name']) ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                    <input type="hidden" id="current_academic_year_id" value="<?= isset($currentAcademicYear) ? $currentAcademicYear['id'] : '' ?>">
                    <input type="hidden" id="current_academic_year_name" value="<?= isset($currentAcademicYear) ? htmlspecialchars($currentAcademicYear['name']) : '' ?>">
                </div>

                <div>
                    <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                    <select name="class_id" id="class_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select Class</option>
                        <?php foreach ($classes ?? [] as $class): ?>
                            <option value="<?= $class['id'] ?>" <?= (isset($_POST['class_id']) && $_POST['class_id'] == $class['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($class['name'] . ' (' . $class['level'] . ')') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>

                <div>
                    <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                    <select name="subject_id" id="subject_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select a class first</option>
                    </select>
                </div>

                <div>
                    <label for="staff_id" class="block text-sm font-medium text-gray-700">Teacher</label>
                    <select name="staff_id" id="staff_id" required
                        class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select a subject first</option>
                    </select>
                </div>

                <div>
                    <label for="day_of_week" class="block text-sm font-medium text-gray-700">Day of Week</label>
                    <select name="day_of_week" id="day_of_week" required class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Select a day</option>
                        <option value="monday" <?= (isset($_POST['day_of_week']) && $_POST['day_of_week'] == 'monday') ? 'selected' : '' ?>>Monday</option>
                        <option value="tuesday" <?= (isset($_POST['day_of_week']) && $_POST['day_of_week'] == 'tuesday') ? 'selected' : '' ?>>Tuesday</option>
                        <option value="wednesday" <?= (isset($_POST['day_of_week']) && $_POST['day_of_week'] == 'wednesday') ? 'selected' : '' ?>>Wednesday</option>
                        <option value="thursday" <?= (isset($_POST['day_of_week']) && $_POST['day_of_week'] == 'thursday') ? 'selected' : '' ?>>Thursday</option>
                        <option value="friday" <?= (isset($_POST['day_of_week']) && $_POST['day_of_week'] == 'friday') ? 'selected' : '' ?>>Friday</option>
                        <option value="saturday" <?= (isset($_POST['day_of_week']) && $_POST['day_of_week'] == 'saturday') ? 'selected' : '' ?>>Saturday</option>
                        <option value="sunday" <?= (isset($_POST['day_of_week']) && $_POST['day_of_week'] == 'sunday') ? 'selected' : '' ?>>Sunday</option>
                    </select>
                </div>

                <div>
                    <label for="start_time" class="block text-sm font-medium text-gray-700">Start Time</label>
                    <input type="time" name="start_time" id="start_time" required value="<?= $_POST['start_time'] ?? '' ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="end_time" class="block text-sm font-medium text-gray-700">End Time</label>
                    <input type="time" name="end_time" id="end_time" required value="<?= $_POST['end_time'] ?? '' ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>

                <div>
                    <label for="room" class="block text-sm font-medium text-gray-700">Room</label>
                    <input type="text" name="room" id="room" value="<?= $_POST['room'] ?? '' ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                </div>
            </div>

            <div class="mt-6 flex justify-end space-x-3">
                <button type="button" id="cancel-add" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                    Cancel
                </button>
                <button type="submit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                    Create Timetable Entry
                </button>
            </div>
        </form>
    </div>
</div>

<script>
// This script will be executed when the modal content is loaded
document.addEventListener('DOMContentLoaded', function() {
    // This is handled in the parent page now
});

// But we still need the dynamic selects functionality
function initializeAddTimetableForm() {
    const classSelect = document.getElementById('class_id');
    const subjectSelect = document.getElementById('subject_id');
    const staffSelect = document.getElementById('staff_id');
    const academicYearSelect = document.getElementById('academic_year_id');
    const currentAcademicYearId = document.getElementById('current_academic_year_id').value;
    const currentAcademicYearName = document.getElementById('current_academic_year_name').value;
    
    // Close button functionality
    const closeAddForm = document.getElementById('close-add-form');
    const cancelAdd = document.getElementById('cancel-add');
    
    if (closeAddForm) {
        closeAddForm.addEventListener('click', function() {
            document.getElementById('add-timetable-modal').classList.add('hidden');
        });
    }
    
    if (cancelAdd) {
        cancelAdd.addEventListener('click', function() {
            document.getElementById('add-timetable-modal').classList.add('hidden');
        });
    }
    
    // When class is selected, load subjects for that class
    if (classSelect) {
        classSelect.addEventListener('change', function() {
            const classId = this.value;
            
            // Reset subject and staff selects
            if (subjectSelect) {
                subjectSelect.innerHTML = '<option value="">Select a class first</option>';
            }
            if (staffSelect) {
                staffSelect.innerHTML = '<option value="">Select a subject first</option>';
            }
            
            if (classId) {
                // Load subjects via AJAX
                fetch(`/timetables/subjects/class/${classId}`)
                    .then(response => response.json())
                    .then(subjects => {
                        if (subjectSelect) {
                            subjectSelect.innerHTML = '<option value="">Select a subject</option>';
                            subjects.forEach(subject => {
                                const option = document.createElement('option');
                                option.value = subject.id;
                                option.textContent = subject.name + ' (' + subject.code + ')';
                                subjectSelect.appendChild(option);
                            });
                        }
                    })
                    .catch(error => {
                        console.error('Error loading subjects:', error);
                    });
            }
        });
    }
    
    // When subject is selected, load teachers for that subject
    if (subjectSelect) {
        subjectSelect.addEventListener('change', function() {
            const subjectId = this.value;
            
            // Reset staff select
            if (staffSelect) {
                staffSelect.innerHTML = '<option value="">Select a subject first</option>';
            }
            
            if (subjectId && staffSelect) {
                // Load teachers via AJAX
                fetch(`/timetables/teachers/subject/${subjectId}`)
                    .then(response => response.json())
                    .then(teachers => {
                        staffSelect.innerHTML = '<option value="">Select a teacher</option>';
                        teachers.forEach(teacher => {
                            const option = document.createElement('option');
                            option.value = teacher.id;
                            option.textContent = teacher.first_name + ' ' + teacher.last_name;
                            staffSelect.appendChild(option);
                        });
                    })
                    .catch(error => {
                        console.error('Error loading teachers:', error);
                    });
            }
        });
    }
    
    // Warning when changing from current academic year
    if (academicYearSelect) {
        academicYearSelect.addEventListener('change', function() {
            const selectedOption = this.options[this.selectedIndex];
            const selectedYearId = this.value;
            const selectedYearName = selectedOption.getAttribute('data-name');
            
            // If we're changing from the current academic year, show a warning
            if (currentAcademicYearId && selectedYearId !== currentAcademicYearId) {
                const confirmChange = confirm(`You are about to switch from the current academic year "${currentAcademicYearName}" to "${selectedYearName}". Are you sure you want to proceed?`);
                if (!confirmChange) {
                    // Revert to the current academic year
                    this.value = currentAcademicYearId;
                }
            }
        });
    }
}

// Initialize the form when loaded in modal
if (document.getElementById('add-timetable-form')) {
    initializeAddTimetableForm();
}
</script>

<?php 
if (!$isAjax) {
    $content = ob_get_clean();
    include RESOURCES_PATH . '/layouts/app.php';
}
?>