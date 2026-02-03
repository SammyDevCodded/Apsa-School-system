<?php 
$title = 'Attendance Report'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Attendance Report</h1>
            <a href="/attendance" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Attendance
            </a>
        </div>

        <!-- Filters and Date Range Selector -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="/attendance/report" class="space-y-4" id="attendance-report-form">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <div>
                            <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                            <input type="date" name="start_date" id="start_date" value="<?= htmlspecialchars($startDate ?? date('Y-m-01')) ?>"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                            <input type="date" name="end_date" id="end_date" value="<?= htmlspecialchars($endDate ?? date('Y-m-t')) ?>"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="search" class="block text-sm font-medium text-gray-700">Search Student</label>
                            <input type="text" name="search" id="search" value="<?= htmlspecialchars($searchTerm ?? '') ?>"
                                placeholder="Name or Admission No"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div>
                            <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                            <select name="class_id" id="class_id"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Classes</option>
                                <?php foreach ($classes as $class): ?>
                                    <option value="<?= $class['id'] ?>" <?= (isset($classId) && $classId == $class['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($class['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                    </div>
                    <div class="flex justify-between items-center">
                        <div class="flex space-x-2">
                            <button type="submit" id="generate-report-btn"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Generate Report
                            </button>
                            <?php if (!empty($searchTerm) || !empty($classId) || (isset($startDate) && $startDate != date('Y-m-01')) || (isset($endDate) && $endDate != date('Y-m-t'))): ?>
                                <a href="/attendance/report" 
                                   class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Clear Filters
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Export button moved outside the container with overflow-hidden -->
        <div class="relative inline-block text-left mb-6" id="export-container">
            <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 focus:outline-none" id="export-menu-button">
                Export
            </button>
            <!-- Dropdown menu with fixed positioning to ensure it's always fully visible -->
            <div class="hidden fixed mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50" id="export-menu">
                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="export-menu-button">
                    <a href="/attendance/report?<?= http_build_query(array_filter(['start_date' => $startDate ?? date('Y-m-01'), 'end_date' => $endDate ?? date('Y-m-t'), 'search' => $searchTerm ?? '', 'class_id' => $classId ?? '', 'export' => 1])) ?>" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Export to CSV
                    </a>
                    <a href="/attendance/report?<?= http_build_query(array_filter(['start_date' => $startDate ?? date('Y-m-01'), 'end_date' => $endDate ?? date('Y-m-t'), 'search' => $searchTerm ?? '', 'class_id' => $classId ?? '', 'export' => 1, 'print' => 1])) ?>" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" target="_blank">
                        Print/Export to PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Report Summary -->
        <?php if (empty($summaryByClass)): ?>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <p class="text-center text-gray-500">No attendance records found for the selected criteria.</p>
                </div>
            </div>
        <?php else: ?>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Report Summary</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">
                        Period: <?= date('M j, Y', strtotime($startDate)) ?> to <?= date('M j, Y', strtotime($endDate)) ?>
                        <?php if (!empty($searchTerm)): ?>
                            | Search: "<?= htmlspecialchars($searchTerm) ?>"
                        <?php endif; ?>
                        <?php if (!empty($classId)): ?>
                            <?php 
                            $selectedClass = array_filter($classes, function($c) use ($classId) { return $c['id'] == $classId; });
                            $className = !empty($selectedClass) ? reset($selectedClass)['name'] : 'Unknown';
                            ?>
                            | Class: <?= htmlspecialchars($className) ?>
                        <?php endif; ?>
                    </p>
                </div>
            </div>
            
            <?php foreach ($summaryByClass as $className => $students): ?>
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900"><?= htmlspecialchars($className) ?></h3>
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
                                        Present
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Absent
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Late
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Total
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Attendance Rate
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
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
                                            <?= $student['present'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= $student['absent'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= $student['late'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= $student['total'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?php 
                                            $total = $student['total'];
                                            $present = $student['present'];
                                            $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                                            ?>
                                            <span class="<?= $rate >= 90 ? 'text-green-600' : ($rate >= 75 ? 'text-yellow-600' : 'text-red-600') ?>">
                                                <?= $rate ?>%
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <a href="/attendance/student/<?= $student['student_id'] ?>/report?start_date=<?= $startDate ?>&end_date=<?= $endDate ?>" 
                                               class="text-indigo-600 hover:text-indigo-900">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
</div>

<script>
    // Export dropdown functionality with fixed positioning
    document.addEventListener('DOMContentLoaded', function() {
        const exportButton = document.getElementById('export-menu-button');
        const exportMenu = document.getElementById('export-menu');
        const exportContainer = document.getElementById('export-container');
        const form = document.getElementById('attendance-report-form');
        const generateBtn = document.getElementById('generate-report-btn');
        
        if (exportButton && exportMenu && exportContainer) {
            // Toggle dropdown visibility
            exportButton.addEventListener('click', function(e) {
                e.stopPropagation();
                exportMenu.classList.toggle('hidden');
                
                // Position the dropdown relative to the button when visible
                if (!exportMenu.classList.contains('hidden')) {
                    const buttonRect = exportButton.getBoundingClientRect();
                    exportMenu.style.top = (buttonRect.bottom + window.scrollY) + 'px';
                    exportMenu.style.left = (buttonRect.left + window.scrollX) + 'px';
                }
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', function(event) {
                if (!exportButton.contains(event.target) && !exportMenu.contains(event.target)) {
                    exportMenu.classList.add('hidden');
                }
            });
            
            // Prevent dropdown from closing when clicking inside
            exportMenu.addEventListener('click', function(e) {
                e.stopPropagation();
            });
        }
        
        // Form submission handler
        if (form && generateBtn) {
            generateBtn.addEventListener('click', function(e) {
                // Prevent default form submission to add debugging
                console.log('Generate Report button clicked');
                console.log('Form action:', form.action);
                console.log('Form method:', form.method);
                
                // Submit the form
                form.submit();
            });
        }
    });
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>