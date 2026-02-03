<?php 
$title = 'Student Attendance Report'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Student Attendance Report</h1>
            <div class="flex space-x-2">
                <a href="/attendance/report" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                    Back to Reports
                </a>
            </div>
        </div>

        <!-- Student Information -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-3">
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Student Name</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <?= htmlspecialchars(($student['first_name'] ?? '') . ' ' . ($student['last_name'] ?? '')) ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Admission No</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <?= htmlspecialchars($student['admission_no'] ?? 'N/A') ?>
                        </p>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-500">Class</label>
                        <p class="mt-1 text-sm text-gray-900">
                            <?= htmlspecialchars($class['name'] ?? 'N/A') ?>
                        </p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Date Range Selector -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="/attendance/student/<?= $student['id'] ?>/report" class="flex flex-wrap items-end space-x-4">
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
                    <div class="flex items-end">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Report
                        </button>
                    </div>
                </form>
            </div>
        </div>
        
        <!-- Export button moved completely outside the container with overflow-hidden -->
        <div class="relative inline-block text-left mb-6" id="export-container">
            <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 focus:outline-none" id="export-menu-button">
                Export
            </button>
            <!-- Dropdown menu with fixed positioning to ensure it's always fully visible -->
            <div class="hidden fixed mt-2 w-56 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 z-50" id="export-menu">
                <div class="py-1" role="menu" aria-orientation="vertical" aria-labelledby="export-menu-button">
                    <a href="/attendance/student/<?= $student['id'] ?>/report?<?= http_build_query(array_filter(['start_date' => $startDate ?? date('Y-m-01'), 'end_date' => $endDate ?? date('Y-m-t'), 'export' => 1])) ?>" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem">
                        Export to CSV
                    </a>
                    <a href="/attendance/student/<?= $student['id'] ?>/report?<?= http_build_query(array_filter(['start_date' => $startDate ?? date('Y-m-01'), 'end_date' => $endDate ?? date('Y-m-t'), 'export' => 1, 'print' => 1])) ?>" 
                       class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" role="menuitem" target="_blank">
                        Print/Export to PDF
                    </a>
                </div>
            </div>
        </div>

        <!-- Attendance Summary -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Attendance Summary</h3>
                <?php 
                $total = $attendanceStats['total'] ?? 0;
                $present = $attendanceStats['present'] ?? 0;
                $absent = $attendanceStats['absent'] ?? 0;
                $late = $attendanceStats['late'] ?? 0;
                $rate = $total > 0 ? round(($present / $total) * 100, 1) : 0;
                ?>
                <div class="grid grid-cols-1 gap-4 sm:grid-cols-5">
                    <div class="bg-green-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-green-800">Present</p>
                        <p class="text-2xl font-bold text-green-900"><?= $present ?></p>
                    </div>
                    <div class="bg-red-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-red-800">Absent</p>
                        <p class="text-2xl font-bold text-red-900"><?= $absent ?></p>
                    </div>
                    <div class="bg-yellow-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-yellow-800">Late</p>
                        <p class="text-2xl font-bold text-yellow-900"><?= $late ?></p>
                    </div>
                    <div class="bg-blue-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-blue-800">Total</p>
                        <p class="text-2xl font-bold text-blue-900"><?= $total ?></p>
                    </div>
                    <div class="bg-purple-50 p-4 rounded-lg">
                        <p class="text-sm font-medium text-purple-800">Attendance Rate</p>
                        <p class="text-2xl font-bold text-purple-900"><?= $rate ?>%</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Attendance Records -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Detailed Attendance Records</h3>
                <p class="text-sm text-gray-500 mb-4">
                    Period: <?= date('M j, Y', strtotime($startDate)) ?> to <?= date('M j, Y', strtotime($endDate)) ?>
                </p>
                
                <?php if (empty($attendanceRecords)): ?>
                    <p class="text-center text-gray-500 py-8">No attendance records found for the selected period.</p>
                <?php else: ?>
                    <div class="overflow-x-auto">
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
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
                                <?php foreach ($attendanceRecords as $record): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= date('M j, Y', strtotime($record['date'])) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?php 
                                                switch($record['status']) {
                                                    case 'present': echo 'bg-green-100 text-green-800'; break;
                                                    case 'absent': echo 'bg-red-100 text-red-800'; break;
                                                    case 'late': echo 'bg-yellow-100 text-yellow-800'; break;
                                                    default: echo 'bg-gray-100 text-gray-800';
                                                }
                                                ?>">
                                                <?= htmlspecialchars(ucfirst($record['status'])) ?>
                                            </span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= htmlspecialchars($record['remarks'] ?? 'N/A') ?>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
    // Export dropdown functionality with fixed positioning
    document.addEventListener('DOMContentLoaded', function() {
        const exportButton = document.getElementById('export-menu-button');
        const exportMenu = document.getElementById('export-menu');
        const exportContainer = document.getElementById('export-container');
        
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
    });
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>