<?php 
$title = 'Record Attendance'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Record Attendance</h1>
            <a href="/attendance?date=<?= htmlspecialchars($date ?? date('Y-m-d')) ?>" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Attendance
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form id="attendanceForm" method="GET" action="/attendance/create" class="space-y-6 mb-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Attendance Date</label>
                            <input type="date" name="date" id="date" value="<?= htmlspecialchars($date ?? date('Y-m-d')) ?>" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700">Select Class</label>
                            <select name="class_id" id="class_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Choose a class...</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class['id'] ?>" <?= (isset($selectedClassId) && $selectedClassId == $class['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($class['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    
                    <div class="flex justify-end">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Load Class
                        </button>
                    </div>
                </form>

                <?php if (isset($selectedClassId) && !empty($selectedClassId)): ?>
                    <form action="/attendance" method="POST" class="space-y-6">
                        <input type="hidden" name="date" value="<?= htmlspecialchars($date ?? date('Y-m-d')) ?>">
                        
                        <?php if (empty($studentsByClass)): ?>
                            <p class="text-center text-gray-500">No students found in the selected class.</p>
                        <?php else: ?>
                            <?php foreach ($studentsByClass as $className => $students): ?>
                                <div class="border border-gray-200 rounded-lg mb-6">
                                    <div class="bg-gray-50 px-4 py-3">
                                        <h3 class="text-lg font-medium text-gray-900"><?= htmlspecialchars($className) ?></h3>
                                        <p class="text-sm text-gray-500 mt-1"><?= count($students) ?> students</p>
                                    </div>
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Student
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Admission No
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Status
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Remarks
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <?php foreach ($students as $student): ?>
                                                    <tr>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            <?= htmlspecialchars($student['admission_no']) ?>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            <select name="attendance[<?= $student['id'] ?>][status]"
                                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                                                <option value="present">Present</option>
                                                                <option value="absent">Absent</option>
                                                                <option value="late">Late</option>
                                                            </select>
                                                        </td>
                                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                            <input type="text" name="attendance[<?= $student['id'] ?>][remarks]"
                                                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"
                                                                placeholder="Remarks (optional)">
                                                        </td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        <?php endif; ?>

                        <?php if (!empty($studentsByClass)): ?>
                            <div class="flex justify-end">
                                <button type="submit"
                                    class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Save Attendance
                                </button>
                            </div>
                        <?php endif; ?>
                    </form>
                <?php else: ?>
                    <div class="bg-blue-50 border border-blue-200 rounded-md p-4">
                        <p class="text-sm text-blue-800">
                            Please select a class and click "Load Class" to begin recording attendance.
                        </p>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Auto-submit form when class is selected
    document.addEventListener('DOMContentLoaded', function() {
        const classSelect = document.getElementById('class_id');
        const dateInput = document.getElementById('date');
        
        if (classSelect) {
            classSelect.addEventListener('change', function() {
                if (this.value) {
                    document.getElementById('attendanceForm').submit();
                }
            });
        }
        
        // Also submit when date changes if a class is already selected
        if (dateInput) {
            dateInput.addEventListener('change', function() {
                const selectedClass = document.getElementById('class_id');
                if (selectedClass && selectedClass.value) {
                    document.getElementById('attendanceForm').submit();
                }
            });
        }
    });
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>