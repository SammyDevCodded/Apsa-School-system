<?php 
$title = 'Edit Academic Year'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Academic Year</h1>
            <a href="/academic_years" class="text-indigo-600 hover:text-indigo-900">
                Back to Academic Years
            </a>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $_SESSION['flash_error'] ?></span>
        </div>
        <?php endif; ?>

        <!-- Edit Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Academic Year Information</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Update the details for this academic year.</p>
            </div>
            <div class="border-t border-gray-200">
                <form action="/academic_years/<?= $academicYear['id'] ?>" method="POST" class="px-4 py-5 sm:p-6" id="academicYearForm">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                            <div class="mt-1">
                                <select name="year" id="year" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <?php 
                                    // Extract year from the academic year name (e.g., "2025-2026" -> 2025)
                                    $currentYearInName = explode('-', $academicYear['name'])[0];
                                    $currentYear = date('Y');
                                    for ($i = $currentYear - 5; $i <= $currentYear + 10; $i++): ?>
                                    <option value="<?= $i ?>" <?= $i == $currentYearInName ? 'selected' : '' ?>><?= $i ?></option>
                                    <?php endfor; ?>
                                </select>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Select the year for this academic year</p>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="<?= htmlspecialchars($academicYear['name']) ?>" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" readonly>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">e.g., 2025-2026 (automatically generated)</p>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <div class="mt-1">
                                <input type="date" name="start_date" id="start_date" value="<?= htmlspecialchars($academicYear['start_date']) ?>" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <div class="mt-1">
                                <input type="date" name="end_date" id="end_date" value="<?= htmlspecialchars($academicYear['end_date']) ?>" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-6">
                            <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                            <div class="mt-1">
                                <select name="status" id="status"
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="active" <?= $academicYear['status'] === 'active' ? 'selected' : '' ?>>Active</option>
                                    <option value="inactive" <?= $academicYear['status'] === 'inactive' ? 'selected' : '' ?>>Inactive</option>
                                    <option value="completed" <?= $academicYear['status'] === 'completed' ? 'selected' : '' ?>>Completed</option>
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mt-6 flex items-center justify-end">
                        <a href="/academic_years" class="bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Cancel
                        </a>
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const yearSelect = document.getElementById('year');
    const nameInput = document.getElementById('name');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const currentYearInName = '<?= explode('-', $academicYear['name'])[0] ?>';
    
    // Function to update the name and dates based on selected year
    function updateAcademicYearInfo() {
        const selectedYear = parseInt(yearSelect.value);
        const nextYear = selectedYear + 1;
        
        // Update name field
        nameInput.value = selectedYear + '-' + nextYear;
        
        // Update dates (assuming academic year starts in September and ends in June)
        startDateInput.value = selectedYear + '-09-01';
        endDateInput.value = nextYear + '-06-30';
    }
    
    // Add event listener for year change
    yearSelect.addEventListener('change', function() {
        const selectedYear = parseInt(this.value);
        const originalYear = parseInt(currentYearInName);
        
        // If user is changing from the original year, show warning
        if (selectedYear !== originalYear) {
            const confirmChange = confirm(`You are about to change this academic year from ${originalYear}-${originalYear + 1} to ${selectedYear}-${selectedYear + 1}. Are you sure you want to proceed?`);
            if (!confirmChange) {
                // Revert to original year
                this.value = originalYear;
                return;
            }
        }
        
        // Update the academic year info
        updateAcademicYearInfo();
    });
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>