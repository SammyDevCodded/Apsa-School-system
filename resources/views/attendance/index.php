<?php 
$title = 'Attendance'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Attendance</h1>
            <div class="flex space-x-2">
                <a href="/attendance/create?date=<?= date('Y-m-d') ?>" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                    Record Attendance
                </a>
                <a href="/attendance/report" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700">
                    Attendance Report
                </a>
            </div>
        </div>

        <!-- Tabs for Date Picker and History -->
        <div class="mb-6">
            <div class="border-b border-gray-200">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <button data-tab="date-picker" class="tab-button border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Date Picker
                    </button>
                    <button data-tab="history" class="tab-button text-gray-500 hover:text-gray-700 whitespace-nowrap py-4 px-1 font-medium text-sm">
                        History
                    </button>
                </nav>
            </div>
        </div>

        <!-- Date Picker Tab -->
        <div id="date-picker-tab" class="tab-content">
            <!-- Date Selector -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <form method="GET" action="/attendance" class="flex items-center space-x-4">
                        <div>
                            <label for="top-date" class="block text-sm font-medium text-gray-700">Select Date</label>
                            <input type="date" name="date" id="top-date" value="<?= htmlspecialchars($date ?? date('Y-m-d')) ?>"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        <div class="flex items-end">
                            <button type="submit"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Load Attendance
                            </button>
                        </div>
                    </form>
                </div>
            </div>

            <!-- Month Selector -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center space-x-4">
                        <div>
                            <label for="month" class="block text-sm font-medium text-gray-700">Select Month</label>
                            <select name="month" id="month" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <?php foreach ($monthsWithAttendance as $month): ?>
                                    <option value="<?= htmlspecialchars($month['month']) ?>" <?= $selectedMonth === $month['month'] ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($month['month_name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <div class="flex items-end">
                            <button id="load-month-btn"
                                class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Load Month
                            </button>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Calendar View -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Attendance Calendar - <?= date('F Y', strtotime($selectedMonth)) ?></h3>
                    <div id="attendance-calendar" class="calendar-view">
                        <!-- Calendar will be populated with JavaScript -->
                    </div>
                </div>
            </div>
        </div>

        <!-- History Tab -->
        <div id="history-tab" class="tab-content hidden">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4">Attendance History</h3>
                    
                    <!-- Filter Form -->
                    <div class="mb-6 bg-gray-50 p-4 rounded-md">
                        <form method="GET" action="/attendance" class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                            <!-- Ensure we stay on the history tab -->
                            <input type="hidden" name="tab" value="history">
                            
                            <!-- Class Filter -->
                            <div class="sm:col-span-2">
                                <label for="history_class_id" class="block text-sm font-medium text-gray-700">Class</label>
                                <select id="history_class_id" name="history_class_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="">All Classes</option>
                                    <?php foreach ($classes as $class): ?>
                                        <option value="<?= $class['id'] ?>" <?= ($filters['class_id'] == $class['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($class['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>
                            
                            <!-- Student Filter -->
                            <div class="sm:col-span-2">
                                <label for="history_student" class="block text-sm font-medium text-gray-700">Student Name/ID</label>
                                <input type="text" name="history_student" id="history_student" value="<?= htmlspecialchars($filters['student']) ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md" placeholder="Search student...">
                            </div>
                            
                            <!-- Period Filter -->
                            <div class="sm:col-span-2">
                                <label for="history_period" class="block text-sm font-medium text-gray-700">Period</label>
                                <select id="history_period" name="history_period" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                    <option value="today" <?= ($filters['period'] == 'today') ? 'selected' : '' ?>>Today</option>
                                    <option value="week" <?= ($filters['period'] == 'week') ? 'selected' : '' ?>>This Week</option>
                                    <option value="month" <?= ($filters['period'] == 'month') ? 'selected' : '' ?>>This Month</option>
                                    <option value="year" <?= ($filters['period'] == 'year') ? 'selected' : '' ?>>Current Academic Year</option>
                                    <option value="custom" <?= ($filters['period'] == 'custom') ? 'selected' : '' ?>>Custom Range</option>
                                </select>
                            </div>
                            
                            <!-- Custom Date Range (Hidden by default) -->
                            <div id="custom_date_range" class="sm:col-span-6 grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6 <?= ($filters['period'] == 'custom') ? '' : 'hidden' ?>">
                                <div class="sm:col-span-3">
                                    <label for="history_start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                    <input type="date" name="history_start_date" id="history_start_date" value="<?= htmlspecialchars($filters['start_date']) ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                                <div class="sm:col-span-3">
                                    <label for="history_end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                    <input type="date" name="history_end_date" id="history_end_date" value="<?= htmlspecialchars($filters['end_date']) ?>" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                                </div>
                            </div>
                            
                            <div class="sm:col-span-6 flex justify-end">
                                <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Filter History
                                </button>
                                <a href="/attendance?tab=history" class="ml-3 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Reset
                                </a>
                            </div>
                        </form>
                    </div>

                    <?php if (empty($historyRecords)): ?>
                        <p class="text-center text-gray-500">No attendance records found matching your filters.</p>
                    <?php else: ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Student
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Class
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Status
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($historyRecords as $record): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= date('M j, Y', strtotime($record['date'])) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= htmlspecialchars($record['first_name'] . ' ' . $record['last_name']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                <?= htmlspecialchars($record['class_name'] ?? 'N/A') ?>
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
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Attendance Records -->
        <?php if (empty($attendanceByClass)): ?>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <p class="text-center text-gray-500">No attendance records found for the selected date.</p>
                </div>
            </div>
        <?php else: ?>
            <?php foreach ($attendanceByClass as $className => $records): ?>
                <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                    <div class="px-4 py-5 sm:px-6">
                        <h3 class="text-lg leading-6 font-medium text-gray-900"><?= htmlspecialchars($className) ?></h3>
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
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($records as $record): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= htmlspecialchars($record['first_name'] . ' ' . $record['last_name']) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= htmlspecialchars($record['admission_no']) ?>
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
                                        <td class="px-6 py-4 text-sm text-gray-900">
                                            <?= htmlspecialchars($record['remarks'] ?? 'N/A') ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="/attendance/<?= $record['id'] ?>/edit" class="text-indigo-600 hover:text-indigo-900">
                                                Edit
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
// Tab switching functionality
document.addEventListener('DOMContentLoaded', function() {
    const tabButtons = document.querySelectorAll('.tab-button');
    const tabContents = document.querySelectorAll('.tab-content');
    
    tabButtons.forEach(button => {
        button.addEventListener('click', () => {
            // Remove active classes
            tabButtons.forEach(btn => {
                btn.classList.remove('border-indigo-500', 'text-indigo-600');
                btn.classList.add('text-gray-500', 'hover:text-gray-700');
            });
            
            // Add active classes to clicked button
            button.classList.remove('text-gray-500', 'hover:text-gray-700');
            button.classList.add('border-indigo-500', 'text-indigo-600');
            
            // Hide all tab contents
            tabContents.forEach(content => {
                content.classList.add('hidden');
            });
            
            // Show the selected tab content
            const tabId = button.getAttribute('data-tab') + '-tab';
            document.getElementById(tabId).classList.remove('hidden');
        });
    });
    
    // Month selection functionality
    document.getElementById('load-month-btn').addEventListener('click', function() {
        const selectedMonth = document.getElementById('month').value;
        window.location.href = '/attendance?month=' + selectedMonth;
    });
    
    // Calendar functionality
    const attendanceDates = <?php echo json_encode(array_column($attendanceDates ?? [], 'date')); ?>;
    const selectedMonth = '<?php echo $selectedMonth; ?>';
    initializeCalendar(selectedMonth, attendanceDates);
    
    // History Period Filter Toggle
    const historyPeriodSelect = document.getElementById('history_period');
    const customDateRangeDiv = document.getElementById('custom_date_range');
    
    if (historyPeriodSelect) {
        historyPeriodSelect.addEventListener('change', function() {
            if (this.value === 'custom') {
                customDateRangeDiv.classList.remove('hidden');
            } else {
                customDateRangeDiv.classList.add('hidden');
            }
        });
    }
});

function initializeCalendar(selectedMonth, attendanceDates) {
    const calendarEl = document.getElementById('attendance-calendar');
    if (!calendarEl) return;
    
    // Parse the selected month (format: YYYY-MM)
    const [year, month] = selectedMonth.split('-').map(Number);
    
    // Create calendar
    const calendar = createCalendar(year, month - 1, attendanceDates);
    calendarEl.innerHTML = calendar;
    
    // Add click event to days with attendance
    addCalendarEventListeners();
}

function addCalendarEventListeners() {
    document.querySelectorAll('.calendar-day.has-attendance').forEach(day => {
        day.addEventListener('click', function() {
            const date = this.getAttribute('data-date');
            // Update the date input field
            document.getElementById('top-date').value = date;
            // Redirect to show attendance for the selected date
            window.location.href = '/attendance?date=' + date;
        });
    });
}

function createCalendar(year, month, attendanceDates) {
    // Days of the week
    const daysOfWeek = ['Sun', 'Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat'];
    
    // First day of the month
    const firstDay = new Date(year, month, 1);
    
    // Last day of the month
    const lastDay = new Date(year, month + 1, 0);
    
    // Number of days in the month
    const daysInMonth = lastDay.getDate();
    
    // Starting day of the week (0 = Sunday, 1 = Monday, etc.)
    const startDay = firstDay.getDay();
    
    // Create calendar HTML
    let html = '<div class="grid grid-cols-7 gap-1">';
    
    // Add day headers
    daysOfWeek.forEach(day => {
        html += `<div class="text-center font-medium text-gray-700 py-2">${day}</div>`;
    });
    
    // Add empty cells for days before the first day of the month
    for (let i = 0; i < startDay; i++) {
        html += '<div class="h-16 border border-gray-200"></div>';
    }
    
    // Add cells for each day of the month
    for (let day = 1; day <= daysInMonth; day++) {
        const dateStr = `${year}-${String(month + 1).padStart(2, '0')}-${String(day).padStart(2, '0')}`;
        const hasAttendance = attendanceDates && attendanceDates.includes(dateStr);
        
        let cellClass = 'h-16 border border-gray-200 p-1 text-center relative';
        if (hasAttendance) {
            cellClass += ' bg-blue-50 calendar-day has-attendance cursor-pointer hover:bg-blue-100';
        } else {
            cellClass += ' cursor-default';
        }
        
        html += `<div class="${cellClass}" data-date="${dateStr}">
                    <div class="text-sm">${day}</div>
                    ${hasAttendance ? '<div class="absolute bottom-1 right-1 w-2 h-2 bg-blue-500 rounded-full"></div>' : ''}
                 </div>`;
    }
    
    html += '</div>';
    
    return html;
}
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>