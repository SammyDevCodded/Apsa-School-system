<?php 
$title = 'System Wipe'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">System Wipe</h1>
            <a href="/settings" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Settings
            </a>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $_SESSION['flash_success'] ?></span>
        </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $_SESSION['flash_error'] ?></span>
        </div>
        <?php endif; ?>

        <!-- System Wipe Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Wipe System Data</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Select which sections of the system to wipe. This action cannot be undone.</p>
            </div>
            <div class="border-t border-gray-200">
                <form action="/settings/wipe" method="POST" class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-2">
                        <div class="sm:col-span-2">
                            <div class="flex justify-between items-center mb-4">
                                <h4 class="text-md font-medium text-gray-800">Select Sections to Wipe</h4>
                                <button type="button" id="select-all-btn" class="text-sm text-indigo-600 hover:text-indigo-800 font-medium">
                                    Select All / Factory Reset
                                </button>
                            </div>
                            <div class="space-y-4">
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="users" name="sections[]" type="checkbox" value="users" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="users" class="font-medium text-red-700">Users (Except You)</label>
                                        <p class="text-gray-500">All user accounts except your own</p>
                                    </div>
                                </div>

                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="students" name="sections[]" type="checkbox" value="students" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="students" class="font-medium text-gray-700">Students</label>
                                        <p class="text-gray-500">All student records and related data</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="staff" name="sections[]" type="checkbox" value="staff" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="staff" class="font-medium text-gray-700">Staff</label>
                                        <p class="text-gray-500">All staff records and related data</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="subjects" name="sections[]" type="checkbox" value="subjects" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="subjects" class="font-medium text-gray-700">Subjects</label>
                                        <p class="text-gray-500">All subject records</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="classes" name="sections[]" type="checkbox" value="classes" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="classes" class="font-medium text-gray-700">Classes</label>
                                        <p class="text-gray-500">All class records</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="fees" name="sections[]" type="checkbox" value="fees" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="fees" class="font-medium text-gray-700">Fees</label>
                                        <p class="text-gray-500">All fee records</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="payments" name="sections[]" type="checkbox" value="payments" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="payments" class="font-medium text-gray-700">Payments</label>
                                        <p class="text-gray-500">All payment records</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="exams" name="sections[]" type="checkbox" value="exams" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="exams" class="font-medium text-gray-700">Exams</label>
                                        <p class="text-gray-500">All exam records and exam results</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="attendance" name="sections[]" type="checkbox" value="attendance" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="attendance" class="font-medium text-gray-700">Attendance</label>
                                        <p class="text-gray-500">All attendance records</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="timetables" name="sections[]" type="checkbox" value="timetables" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="timetables" class="font-medium text-gray-700">Timetables</label>
                                        <p class="text-gray-500">All timetable records</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="reports" name="sections[]" type="checkbox" value="reports" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="reports" class="font-medium text-gray-700">Reports</label>
                                        <p class="text-gray-500">Report card settings and configurations</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="academic_years" name="sections[]" type="checkbox" value="academic_years" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="academic_years" class="font-medium text-gray-700">Academic Years</label>
                                        <p class="text-gray-500">All academic year records</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="notifications" name="sections[]" type="checkbox" value="notifications" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="notifications" class="font-medium text-gray-700">Notifications</label>
                                        <p class="text-gray-500">All notification records</p>
                                    </div>
                                </div>
                                
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="audit_logs" name="sections[]" type="checkbox" value="audit_logs" class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="audit_logs" class="font-medium text-gray-700">Audit Logs</label>
                                        <p class="text-gray-500">All audit log records</p>
                                    </div>
                                </div>
                            </div>
                        </div>
                        
                        <div class="sm:col-span-2 mt-6">
                            <div class="flex items-start">
                                <div class="flex items-center h-5">
                                    <input id="confirm_wipe" name="confirm_wipe" type="checkbox" value="yes" required class="focus:ring-indigo-500 h-4 w-4 text-indigo-600 border-gray-300 rounded">
                                </div>
                                <div class="ml-3 text-sm">
                                    <label for="confirm_wipe" class="font-medium text-red-600">Confirm Wipe Action</label>
                                    <p class="text-gray-500">I understand that this action will permanently delete the selected data and cannot be undone.</p>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-red-600 hover:bg-red-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-red-500">
                            Wipe Selected Data
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Warning Message -->
        <div class="mt-8 bg-yellow-50 border-l-4 border-yellow-400 p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-yellow-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <p class="text-sm text-yellow-700">
                        <strong>Warning:</strong> This action will permanently delete the selected data. 
                        Please ensure you have backed up any important information before proceeding.
                    </p>
                </div>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    const selectAllBtn = document.getElementById('select-all-btn');
    const checkboxes = document.querySelectorAll('input[name="sections[]"]');
    
    if (selectAllBtn) {
        selectAllBtn.addEventListener('click', function() {
            const allChecked = Array.from(checkboxes).every(cb => cb.checked);
            
            checkboxes.forEach(cb => {
                cb.checked = !allChecked;
            });
            
            this.textContent = allChecked ? "Select All / Factory Reset" : "Deselect All";
        });
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>