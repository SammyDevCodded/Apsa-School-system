<?php 
$title = 'Student Profile'; 
ob_start(); 

// Define student category labels
$studentCategoryLabels = [
    'regular_day' => 'Regular Student (Day)',
    'regular_boarding' => 'Regular Student (Boarding)',
    'international' => 'International Student'
];

// Define term labels
$termLabels = [
    'first' => 'First Term',
    'second' => 'Second Term',
    'third' => 'Third Term'
];

// Define payment status labels and colors
$paymentStatusLabels = [
    'fully_paid' => 'Fully Paid',
    'partly_paid' => 'Partly Paid',
    'pending' => 'Pending'
];

$paymentStatusColors = [
    'fully_paid' => 'bg-green-100 text-green-800',
    'partly_paid' => 'bg-yellow-100 text-yellow-800',
    'pending' => 'bg-red-100 text-red-800'
];

// Determine active tab from URL parameter, default to 'academic'
$activeTab = $_GET['tab'] ?? 'academic';
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Student Profile</h1>
            <div>
                <a href="/students/<?= $student['id'] ?>/edit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 mr-2">
                    Edit
                </a>
                <a href="/students" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                    Back to Students
                </a>
            </div>
        </div>

        <!-- Collapsible Profile Details Section -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 cursor-pointer" id="profile-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <?php if (!empty($student['profile_picture'])): ?>
                            <img src="/storage/uploads/<?= htmlspecialchars($student['profile_picture']) ?>" alt="Profile Picture" class="h-12 w-12 object-cover rounded-full mr-4" onerror="this.src='/images/default-profile.png';">
                        <?php else: ?>
                            <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                                <span class="text-gray-500 text-xl"><?= substr($student['first_name'], 0, 1) ?></span>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Admission Number: <?= htmlspecialchars($student['admission_no']) ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <svg id="expand-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200 <?= isset($_COOKIE['profile_collapsed']) && $_COOKIE['profile_collapsed'] === 'true' ? '' : 'rotate-180' ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
            <div id="profile-details" class="border-t border-gray-200 <?= isset($_COOKIE['profile_collapsed']) && $_COOKIE['profile_collapsed'] === 'true' ? 'hidden' : '' ?>">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Admission Number
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['admission_no']) ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Full Name
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Date of Birth
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['dob'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Gender
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars(ucfirst($student['gender'] ?? 'N/A')) ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Class
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['class_name'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <!-- Student Category Information -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Student Category
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($studentCategoryLabels[$student['student_category'] ?? 'regular_day']) ?>
                        </dd>
                    </div>
                    <?php if (!empty($student['student_category_details'])): ?>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Category Details
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['student_category_details']) ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Guardian Name
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['guardian_name'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Guardian Phone
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['guardian_phone'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Address
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['address'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <?php if (!empty($student['medical_info'])): ?>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Medical Information
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['medical_info']) ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Admission Date
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= !empty($student['admission_date']) ? htmlspecialchars(date('M j, Y', strtotime($student['admission_date']))) : 'N/A' ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Academic Year
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= !empty($student['academic_year_name']) ? htmlspecialchars($student['academic_year_name']) : 'N/A' ?>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="?tab=academic" class="<?= $activeTab === 'academic' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Academic Records
                    </a>
                    <a href="?tab=attendance" class="<?= $activeTab === 'attendance' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Attendance
                    </a>
                    <a href="?tab=financial" class="<?= $activeTab === 'financial' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Financial History
                    </a>
            </nav>
        </div>

        <!-- Tab Content -->
        <!-- Attendance Tab -->
        <div id="attendance" class="<?= $activeTab === 'attendance' ? '' : 'hidden' ?>">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Attendance History</h3>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Attendance records grouped by academic year and term.</p>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:p-6">
                    <?php if (empty($groupedAttendance)): ?>
                        <p class="text-gray-500 text-center py-4">No attendance records found.</p>
                    <?php else: ?>
                        <div class="space-y-6">
                            <?php foreach ($groupedAttendance as $groupName => $data): ?>
                                <div class="bg-gray-50 rounded-lg p-4">
                                    <h4 class="text-md font-bold text-gray-800 mb-2 border-b pb-2"><?= htmlspecialchars($groupName) ?></h4>
                                    <div class="grid grid-cols-2 md:grid-cols-4 gap-4 mb-4">
                                        <div class="bg-white p-3 rounded shadow-sm text-center">
                                            <span class="block text-xs text-gray-500 uppercase">Present</span>
                                            <span class="block text-xl font-bold text-green-600"><?= $data['present'] ?></span>
                                        </div>
                                        <div class="bg-white p-3 rounded shadow-sm text-center">
                                            <span class="block text-xs text-gray-500 uppercase">Absent</span>
                                            <span class="block text-xl font-bold text-red-600"><?= $data['absent'] ?></span>
                                        </div>
                                        <div class="bg-white p-3 rounded shadow-sm text-center">
                                            <span class="block text-xs text-gray-500 uppercase">Late</span>
                                            <span class="block text-xl font-bold text-yellow-600"><?= $data['late'] ?></span>
                                        </div>
                                        <div class="bg-white p-3 rounded shadow-sm text-center">
                                            <span class="block text-xs text-gray-500 uppercase">Attendance Rate</span>
                                            <?php 
                                            $rate = $data['total'] > 0 ? round(($data['present'] / $data['total']) * 100, 1) : 0;
                                            $colorClass = $rate >= 90 ? 'text-green-600' : ($rate >= 75 ? 'text-yellow-600' : 'text-red-600');
                                            ?>
                                            <span class="block text-xl font-bold <?= $colorClass ?>"><?= $rate ?>%</span>
                                        </div>
                                    </div>
                                    
                                    <!-- Toggle Details Button -->
                                    <button class="text-indigo-600 hover:text-indigo-800 text-sm font-medium focus:outline-none" onclick="document.getElementById('details-<?= md5($groupName) ?>').classList.toggle('hidden')">
                                        Toggle Detailed Records
                                    </button>
                                    
                                    <!-- Detailed Records (Hidden by default) -->
                                    <div id="details-<?= md5($groupName) ?>" class="hidden mt-4 overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-100">
                                                <tr>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                                    <th scope="col" class="px-3 py-2 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Remarks</th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <?php foreach ($data['records'] as $record): ?>
                                                    <tr>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-900"><?= date('M j, Y', strtotime($record['date'])) ?></td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm">
                                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                                <?= $record['status'] === 'present' ? 'bg-green-100 text-green-800' : 
                                                                   ($record['status'] === 'absent' ? 'bg-red-100 text-red-800' : 'bg-yellow-100 text-yellow-800') ?>">
                                                                <?= ucfirst($record['status']) ?>
                                                            </span>
                                                        </td>
                                                        <td class="px-3 py-2 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($record['remarks'] ?? '-') ?></td>
                                                    </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            <?php endforeach; ?>
                        </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Academic Information Tab -->
        <div id="academic" class="bg-white shadow overflow-hidden sm:rounded-lg <?= $activeTab === 'academic' ? '' : 'hidden' ?>">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Academic Information</h3>
                    <div class="flex space-x-2">
                        <button onclick="printSection('academic-records-container', 'Academic Records')" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1.5 rounded-md text-sm font-medium transition flex items-center">
                            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print
                        </button>
                        <button onclick="exportGroupedTableToCSV('academic-records-container', 'academic_records_<?= $student['admission_no'] ?>')" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-md text-sm font-medium transition flex items-center">
                            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export
                        </button>
                    </div>
                </div>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Recorded academics of student</p>
            </div>
            <div class="border-t border-gray-200">
                <?php if (empty($groupedAcademicRecords)): ?>
                <div class="px-4 py-5 sm:px-6 text-sm text-gray-500">
                    No academic records found for this student.
                </div>
                <?php else: ?>
                <div id="academic-records-container">
                    <?php foreach ($groupedAcademicRecords as $academicYear => $terms): ?>
                    <div class="px-4 py-3 bg-gray-50 border-t border-b border-gray-200">
                        <h5 class="text-sm font-bold text-gray-700"><?= htmlspecialchars($academicYear) ?></h5>
                    </div>
                    <?php foreach ($terms as $term => $exams): ?>
                    <div class="border-b border-gray-200">
                        <div class="px-4 py-2 bg-white">
                            <h6 class="text-sm font-medium text-gray-600 ml-4 border-l-2 border-indigo-500 pl-2"><?= htmlspecialchars($termLabels[$term] ?? $term) ?></h6>
                        </div>
                        
                        <div class="px-4 pb-4">
                            <?php foreach ($exams as $examName => $records): 
                                $examId = md5($academicYear . $term . $examName);
                            ?>
                            <div class="mb-4 bg-white border border-gray-200 rounded-lg shadow-sm overflow-hidden">
                                <!-- Exam Header / Toggle -->
                                <button type="button" 
                                        onclick="toggleExamDetails('<?= $examId ?>')"
                                        class="w-full flex items-center justify-between px-4 py-3 bg-gray-50 hover:bg-gray-100 focus:outline-none transition-colors duration-150">
                                    <div class="flex items-center">
                                        <svg id="icon-<?= $examId ?>" class="h-5 w-5 text-gray-500 transform transition-transform duration-200 -rotate-90" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                        </svg>
                                        <span class="ml-2 text-sm font-semibold text-gray-800"><?= htmlspecialchars($examName) ?></span>
                                        <span class="ml-2 px-2 py-0.5 text-xs rounded-full bg-indigo-100 text-indigo-800">
                                            <?= count($records) ?> Subjects
                                        </span>
                                    </div>
                                    <div class="text-xs text-gray-500">
                                        Click to view details
                                    </div>
                                </button>
                                
                                <!-- Exam Details (Collapsible) -->
                                <div id="details-<?= $examId ?>" class="hidden border-t border-gray-200">
                                    <div class="overflow-x-auto">
                                        <table class="min-w-full divide-y divide-gray-200">
                                            <thead class="bg-gray-50">
                                                <tr>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Subject
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Marks
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Grade
                                                    </th>
                                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                                        Remarks
                                                    </th>
                                                </tr>
                                            </thead>
                                            <tbody class="bg-white divide-y divide-gray-200">
                                                <?php foreach ($records as $record): ?>
                                                <tr>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                                        <?= htmlspecialchars($record['subject_name']) ?> <span class="text-gray-500 text-xs">(<?= htmlspecialchars($record['subject_code']) ?>)</span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <?= htmlspecialchars($record['marks']) ?>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                                            <?= htmlspecialchars($record['grade']) ?>
                                                        </span>
                                                    </td>
                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                        <?= htmlspecialchars($record['remark'] ?? '-') ?>
                                                    </td>
                                                </tr>
                                                <?php endforeach; ?>
                                            </tbody>
                                        </table>
                                    </div>
                                </div>
                            </div>
                            <?php endforeach; ?>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endforeach; ?>

                    <script>
                        function toggleExamDetails(id) {
                            const details = document.getElementById('details-' + id);
                            const icon = document.getElementById('icon-' + id);
                            
                            if (details.classList.contains('hidden')) {
                                details.classList.remove('hidden');
                                icon.classList.remove('-rotate-90');
                            } else {
                                details.classList.add('hidden');
                                icon.classList.add('-rotate-90');
                            }
                        }
                    </script>
                </div>
                <?php endif; ?>
                
                <!-- Promotion History Section -->
                <div class="border-t border-gray-200 mt-6">
                    <div class="px-4 py-5 sm:px-6">
                        <h4 class="text-md font-medium text-gray-900">Promotion History</h4>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Class promotions and movements</p>
                    </div>
                    <div class="overflow-x-auto">
                        <?php if (empty($promotionHistory)): ?>
                        <div class="px-4 py-5 sm:px-6 text-sm text-gray-500 text-center">
                            No promotion history found for this student.
                        </div>
                        <?php else: ?>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        From Class
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        To Class
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Academic Year
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Promoted By
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Remarks
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($promotionHistory as $promotion): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= date('M j, Y', strtotime($promotion['promotion_date'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <?= htmlspecialchars($promotion['from_class_name'] ?? 'N/A') ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <?= htmlspecialchars($promotion['to_class_name'] ?? 'N/A') ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($promotion['academic_year_name'] ?? 'N/A') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($promotion['promoted_by_username'] ?? 'N/A') ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?= htmlspecialchars($promotion['remarks'] ?? 'N/A') ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Information Tab -->
        <div id="financial-tab-content" class="bg-white shadow sm:rounded-lg tab-content <?= $activeTab === 'financial' ? 'block' : 'hidden' ?>">

            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Financial Information</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Payments and outstanding bills</p>
            </div>
            <div class="border-t border-gray-200">
                <!-- DEBUG: -->
                <!-- Debug line removed -->
                <?php if (empty($financialInfo['fees'])): ?>
                <div class="px-4 py-5 sm:px-6 text-sm text-gray-500">
                    No financial records found for this student.
                </div>
                <?php else: ?>
                <!-- Fee Summary -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fee Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Paid Amount
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Balance
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Action
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($financialInfo['fees'] as $info): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($info['name']) ?>
                                    <div class="text-gray-500 text-xs">
                                        <?= htmlspecialchars(ucfirst($info['type'])) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars(number_format($info['amount'], 2)) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars(number_format($info['paid_amount'], 2)) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars(number_format($info['balance'], 2)) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $paymentStatusColors[$info['status']] ?>">
                                        <?= htmlspecialchars($paymentStatusLabels[$info['status']]) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?php 
                                    // Filter payments for this fee
                                    $feePayments = array_filter($financialInfo['payments'], function($p) use ($info) {
                                        return $p['fee_id'] == $info['id'];
                                    });
                                    // Re-index array
                                    $feePayments = array_values($feePayments);
                                    
                                    $feeData = [
                                        'id' => $info['id'],
                                        'name' => $info['name'],
                                        'type' => ucfirst($info['type']),
                                        'amount' => $info['amount'],
                                        'paid_amount' => $info['paid_amount'],
                                        'balance' => $info['balance'],
                                        'status' => $paymentStatusLabels[$info['status']],
                                        'status_color' => $paymentStatusColors[$info['status']],
                                        'description' => $info['description'] ?? 'No description available.',
                                        'payments' => $feePayments
                                    ];
                                    ?>
                                    <button type="button" 
                                            onclick="openFeeDetails(this)"
                                            data-fee='<?= htmlspecialchars(json_encode($feeData), ENT_QUOTES, 'UTF-8') ?>'
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Details
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td class="px-6 py-3 text-sm font-medium text-gray-900">
                                    Total
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">
                                    <?= htmlspecialchars(number_format(array_sum(array_column($financialInfo['fees'], 'amount')), 2)) ?>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">
                                    <?= htmlspecialchars(number_format($financialInfo['total_payments'], 2)) ?>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">
                                    <?= htmlspecialchars(number_format(array_sum(array_column($financialInfo['fees'], 'balance')), 2)) ?>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">
                                    <?php 
                                    $totalFees = array_sum(array_column($financialInfo['fees'], 'amount'));
                                    $totalPaid = $financialInfo['total_payments'];
                                    $totalBalance = array_sum(array_column($financialInfo['fees'], 'balance'));
                                    
                                    if ($totalBalance <= 0 && $totalPaid >= $totalFees) {
                                        $totalStatus = 'Fully Paid';
                                        $totalStatusColor = 'bg-green-100 text-green-800';
                                        echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Fully Paid</span>';
                                    } else if ($totalPaid > 0) {
                                        $totalStatus = 'Partly Paid';
                                        $totalStatusColor = 'bg-yellow-100 text-yellow-800';
                                        echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Partly Paid</span>';
                                    } else {
                                        $totalStatus = 'Pending';
                                        $totalStatusColor = 'bg-red-100 text-red-800';
                                        echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Pending</span>';
                                    }
                                    ?>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">
                                    <?php
                                    $totalData = [
                                        'id' => 'total',
                                        'name' => 'Consolidated Financials',
                                        'type' => 'All Fees',
                                        'amount' => $totalFees,
                                        'paid_amount' => $totalPaid,
                                        'balance' => $totalBalance,
                                        'status' => $totalStatus,
                                        'status_color' => $totalStatusColor,
                                        'description' => 'Consolidated view of all fees and payments.',
                                        'payments' => array_values($financialInfo['payments']) // All payments
                                    ];
                                    ?>
                                    <button type="button" 
                                            onclick="openFeeDetails(this)"
                                            data-fee='<?= htmlspecialchars(json_encode($totalData), ENT_QUOTES, 'UTF-8') ?>'
                                            class="inline-flex items-center px-3 py-1.5 border border-transparent text-xs font-medium rounded-md text-indigo-700 bg-indigo-100 hover:bg-indigo-200 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                        Details
                                    </button>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>

                <!-- Fee Details Modal -->
                <div id="fee-details-modal" class="fixed inset-0 z-50 hidden overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
                    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
                        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeFeeDetails()"></div>
                        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
                        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
                            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                                <div class="sm:flex sm:items-start">
                                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                                        <div class="flex justify-between items-center w-full">
                                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Fee Details</h3>
                                            <div class="text-sm text-gray-500">
                                                Student: <span class="font-medium text-gray-900"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></span> (<?= htmlspecialchars($student['admission_no']) ?>)
                                            </div>
                                        </div>
                                        
                                        <!-- Header Info -->
                                        <div class="mt-4 grid grid-cols-2 gap-4 border-b pb-4">
                                            <div>
                                                <p class="text-sm text-gray-500">Fee Name</p>
                                                <p class="font-bold text-gray-900" id="modal-fee-name"></p>
                                            </div>
                                            <div>
                                                <p class="text-sm text-gray-500">Fee Type</p>
                                                <p class="font-bold text-gray-900" id="modal-fee-type"></p>
                                            </div>
                                            <div class="col-span-2">
                                                <p class="text-sm text-gray-500">Description</p>
                                                <p class="text-gray-900" id="modal-fee-desc"></p>
                                            </div>
                                        </div>

                                        <!-- Financial Summary -->
                                        <div class="mt-4 grid grid-cols-4 gap-2 bg-gray-50 p-3 rounded">
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 uppercase">Amount</p>
                                                <p class="font-bold text-gray-900" id="modal-fee-amount"></p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 uppercase">Paid</p>
                                                <p class="font-bold text-green-600" id="modal-fee-paid"></p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 uppercase">Balance</p>
                                                <p class="font-bold text-red-600" id="modal-fee-balance"></p>
                                            </div>
                                            <div class="text-center">
                                                <p class="text-xs text-gray-500 uppercase">Status</p>
                                                <p class="font-bold" id="modal-fee-status"></p>
                                            </div>
                                        </div>

                                        <!-- Payment History -->
                                        <div class="mt-6">
                                            <h4 class="text-md font-bold text-gray-900 mb-2">Payment History</h4>
                                            <div class="overflow-x-auto border rounded">
                                                <table class="min-w-full divide-y divide-gray-200" id="modal-payments-table">
                                                    <thead class="bg-gray-100">
                                                        <tr>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                                                            <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Ref</th>
                                                        </tr>
                                                    </thead>
                                                    <tbody class="bg-white divide-y divide-gray-200 text-sm">
                                                        <!-- Populated by JS -->
                                                    </tbody>
                                                </table>
                                                <p id="modal-no-payments" class="text-center text-gray-500 py-4 hidden">No payments recorded for this fee.</p>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                                <button type="button" onclick="closeFeeDetails()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Close
                                </button>
                                <button type="button" onclick="printFeeDetails()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                                    Print
                                </button>
                                <button type="button" onclick="exportFeeDetailsCSV()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                                    Export CSV
                                </button>
                            </div>
                        </div>
                    </div>
                </div>

                <script>
                    let currentFeeData = null;

                    function openFeeDetails(button) {
                        const feeData = JSON.parse(button.getAttribute('data-fee'));
                        currentFeeData = feeData;

                        // Populate Modal Fields
                        document.getElementById('modal-fee-name').textContent = feeData.name;
                        document.getElementById('modal-fee-type').textContent = feeData.type;
                        document.getElementById('modal-fee-desc').textContent = feeData.description;
                        document.getElementById('modal-fee-amount').textContent = parseFloat(feeData.amount).toLocaleString('en-US', {minimumFractionDigits: 2});
                        document.getElementById('modal-fee-paid').textContent = parseFloat(feeData.paid_amount).toLocaleString('en-US', {minimumFractionDigits: 2});
                        document.getElementById('modal-fee-balance').textContent = parseFloat(feeData.balance).toLocaleString('en-US', {minimumFractionDigits: 2});
                        
                        const statusEl = document.getElementById('modal-fee-status');
                        statusEl.textContent = feeData.status;
                        statusEl.className = 'font-bold px-2 inline-flex text-xs leading-5 rounded-full ' + feeData.status_color;

                        // Populate Payments Table
                        const tbody = document.querySelector('#modal-payments-table tbody');
                        tbody.innerHTML = '';
                        
                        if (feeData.payments.length > 0) {
                            document.getElementById('modal-no-payments').classList.add('hidden');
                            document.getElementById('modal-payments-table').classList.remove('hidden');
                            
                            feeData.payments.forEach(payment => {
                                const row = `
                                    <tr>
                                        <td class="px-4 py-2 whitespace-nowrap">${new Date(payment.date).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'})}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">${parseFloat(payment.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">${payment.payment_method || '-'}</td>
                                        <td class="px-4 py-2 whitespace-nowrap">${payment.reference_number || '-'}</td>
                                    </tr>
                                `;
                                tbody.insertAdjacentHTML('beforeend', row);
                            });
                        } else {
                            document.getElementById('modal-no-payments').classList.remove('hidden');
                            document.getElementById('modal-payments-table').classList.add('hidden');
                        }

                        // Show Modal
                        document.getElementById('fee-details-modal').classList.remove('hidden');
                    }

                    function closeFeeDetails() {
                        document.getElementById('fee-details-modal').classList.add('hidden');
                    }

                    function printFeeDetails() {
                        if (!currentFeeData) return;

                        const printWindow = window.open('', '_blank');
                        let paymentsHtml = '';
                        
                        if (currentFeeData.payments.length > 0) {
                            paymentsHtml = `
                                <table style="width:100%; border-collapse: collapse; margin-top: 20px;">
                                    <thead>
                                        <tr style="background-color: #f3f4f6;">
                                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Date</th>
                                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Amount</th>
                                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Method</th>
                                            <th style="border: 1px solid #ddd; padding: 8px; text-align: left;">Reference</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        ${currentFeeData.payments.map(p => `
                                            <tr>
                                                <td style="border: 1px solid #ddd; padding: 8px;">${new Date(p.date).toLocaleDateString('en-US', {year: 'numeric', month: 'short', day: 'numeric'})}</td>
                                                <td style="border: 1px solid #ddd; padding: 8px;">${parseFloat(p.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</td>
                                                <td style="border: 1px solid #ddd; padding: 8px;">${p.payment_method || '-'}</td>
                                                <td style="border: 1px solid #ddd; padding: 8px;">${p.reference_number || '-'}</td>
                                            </tr>
                                        `).join('')}
                                    </tbody>
                                </table>
                            `;
                        } else {
                            paymentsHtml = '<p style="text-align: center; color: #666; margin-top: 20px;">No payments recorded for this fee.</p>';
                        }

                        printWindow.document.write(`
                            <html>
                            <head>
                                <title>Fee Details - ${currentFeeData.name}</title>
                                <style>
                                    body { font-family: Arial, sans-serif; padding: 20px; }
                                    .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 10px; }
                                    .info-grid { display: grid; grid-template-columns: 1fr 1fr; gap: 20px; margin-bottom: 20px; }
                                    .summary-box { background-color: #f9f9f9; padding: 15px; border-radius: 5px; display: grid; grid-template-columns: repeat(4, 1fr); gap: 10px; text-align: center; }
                                    .label { font-size: 12px; color: #666; text-transform: uppercase; }
                                    .value { font-weight: bold; font-size: 16px; margin-top: 5px; }
                                </style>
                            </head>
                            <body>
                                <div class="header">
                                    <h2>Fee Details Report</h2>
                                    <h3 style="margin: 10px 0;">Student: <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?> (<?= htmlspecialchars($student['admission_no']) ?>)</h3>
                                    <p>Date: ${new Date().toLocaleDateString()}</p>
                                </div>
                                <div class="info-grid">
                                    <div><strong>Fee Name:</strong> ${currentFeeData.name}</div>
                                    <div><strong>Fee Type:</strong> ${currentFeeData.type}</div>
                                    <div style="grid-column: span 2;"><strong>Description:</strong> ${currentFeeData.description}</div>
                                </div>
                                <div class="summary-box">
                                    <div><div class="label">Amount</div><div class="value">${parseFloat(currentFeeData.amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</div></div>
                                    <div><div class="label">Paid</div><div class="value" style="color: green;">${parseFloat(currentFeeData.paid_amount).toLocaleString('en-US', {minimumFractionDigits: 2})}</div></div>
                                    <div><div class="label">Balance</div><div class="value" style="color: red;">${parseFloat(currentFeeData.balance).toLocaleString('en-US', {minimumFractionDigits: 2})}</div></div>
                                    <div><div class="label">Status</div><div class="value">${currentFeeData.status}</div></div>
                                </div>
                                <h3>Payment History</h3>
                                ${paymentsHtml}
                                <script>
                                    setTimeout(function() {
                                        window.print();
                                        window.close();
                                    }, 250);
                                <\/script>
                            </body>
                            </html>
                        `);
                        printWindow.document.close();
                        printWindow.focus();
                    }

                    function exportFeeDetailsCSV() {
                        if (!currentFeeData) return;

                        let csvContent = "data:text/csv;charset=utf-8,";
                        csvContent += "Fee Name,Type,Amount,Paid,Balance,Status\n";
                        csvContent += `"${currentFeeData.name}","${currentFeeData.type}",${currentFeeData.amount},${currentFeeData.paid_amount},${currentFeeData.balance},"${currentFeeData.status}"\n\n`;
                        
                        csvContent += "Payment History\n";
                        csvContent += "Date,Amount,Method,Reference,Remarks\n";
                        
                        if (currentFeeData.payments.length > 0) {
                            currentFeeData.payments.forEach(p => {
                                csvContent += `"${p.date}",${p.amount},"${p.payment_method || ''}","${p.reference_number || ''}","${p.remarks || ''}"\n`;
                            });
                        } else {
                            csvContent += "No payments recorded\n";
                        }

                        const encodedUri = encodeURI(csvContent);
                        const link = document.createElement("a");
                        link.setAttribute("href", encodedUri);
                        link.setAttribute("download", `fee_details_${currentFeeData.name.replace(/\s+/g, '_')}_${new Date().toISOString().slice(0,10)}.csv`);
                        document.body.appendChild(link);
                        link.click();
                        document.body.removeChild(link);
                    }
                </script>

                
                <!-- Payment History Grouped by Academic Year and Term -->
                <?php if (!empty($financialInfo['grouped_payments'])): ?>
                <div class="border-t border-gray-200 mt-6">
                    <div class="px-4 py-5 sm:px-6">
                        <div class="flex justify-between items-center">
                            <h4 class="text-md font-medium text-gray-900">Payment History by Academic Year</h4>
                            <div class="flex space-x-2">
                                <button onclick="printSection('payment-history-container', 'Payment History')" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1.5 rounded-md text-sm font-medium transition flex items-center">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Print
                                </button>
                                <button onclick="exportPaymentHistoryGroups('payment-history-container', 'payment_history_<?= $student['admission_no'] ?>')" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-md text-sm font-medium transition flex items-center">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Export
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="payment-history-container">
                    <?php foreach ($financialInfo['grouped_payments'] as $academicYear => $terms): ?>
                    <div class="px-4 py-3 bg-gray-50">
                        <h5 class="text-sm font-medium text-gray-700"><?= htmlspecialchars($academicYear) ?></h5>
                    </div>
                    <?php foreach ($terms as $term => $payments): ?>
                    <div class="border-b border-gray-200">
                        <div class="px-4 py-2 bg-white">
                            <h6 class="text-sm font-medium text-gray-600 ml-4"><?= htmlspecialchars($termLabels[$term] ?? $term) ?></h6>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fee
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Method
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Remarks
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= htmlspecialchars(date('M j, Y', strtotime($payment['date']))) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php 
                                            // Get fee name for this payment
                                            $feeName = 'Unknown Fee';
                                            foreach ($financialInfo['fees'] as $fee) {
                                                if ($fee['id'] == $payment['fee_id']) {
                                                    $feeName = $fee['name'];
                                                    break;
                                                }
                                            }
                                            echo htmlspecialchars($feeName);
                                            ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars(number_format($payment['amount'], 2)) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $payment['method']))) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <?= htmlspecialchars($payment['remarks'] ?? 'N/A') ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile collapse/expand functionality
    const profileHeader = document.getElementById('profile-header');
    const profileDetails = document.getElementById('profile-details');
    const expandIcon = document.getElementById('expand-icon');
    
    // Toggle profile details visibility
    profileHeader.addEventListener('click', function() {
        const isCollapsed = profileDetails.classList.contains('hidden');
        
        if (isCollapsed) {
            // Expand
            profileDetails.classList.remove('hidden');
            expandIcon.classList.add('rotate-180');
        } else {
            // Collapse
            profileDetails.classList.add('hidden');
            expandIcon.classList.remove('rotate-180');
        }
        
        // Store state in cookie
        document.cookie = `profile_collapsed=${!isCollapsed}; path=/`;
    });
    
    // Tab switching functionality
    const academicTabLink = document.querySelector('.academic-tab-link');
    const financialTabLink = document.querySelector('.financial-tab-link');
    const academicTabContent = document.getElementById('academic-tab-content');
    const financialTabContent = document.getElementById('financial-tab-content');
    
    // Function to switch tabs
    function switchTab(tabName) {
        if (tabName === 'academic') {
            // Show academic tab
            academicTabContent.classList.remove('hidden');
            academicTabContent.classList.add('block');
            financialTabContent.classList.remove('block');
            financialTabContent.classList.add('hidden');
            
            // Update tab link styling
            academicTabLink.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            academicTabLink.classList.add('border-indigo-500', 'text-indigo-600');
            financialTabLink.classList.remove('border-indigo-500', 'text-indigo-600');
            financialTabLink.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        } else if (tabName === 'financial') {
            // Show financial tab
            financialTabContent.classList.remove('hidden');
            financialTabContent.classList.add('block');
            academicTabContent.classList.remove('block');
            academicTabContent.classList.add('hidden');
            
            // Update tab link styling
            financialTabLink.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            financialTabLink.classList.add('border-indigo-500', 'text-indigo-600');
            academicTabLink.classList.remove('border-indigo-500', 'text-indigo-600');
            academicTabLink.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        }
        
        // Update URL without page refresh
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.pushState({tab: tabName}, '', url);
    }
    
    // Add event listeners
    if (academicTabLink) {
        academicTabLink.addEventListener('click', function(e) {
            e.preventDefault();
            switchTab('academic');
        });
    }
    
    if (financialTabLink) {
        financialTabLink.addEventListener('click', function(e) {
            e.preventDefault();
            switchTab('financial');
        });
    }
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        const tab = event.state && event.state.tab ? event.state.tab : 'academic';
        switchTab(tab);
    });
});

// Print Functionality
function printSection(elementId, title) {
    const content = document.getElementById(elementId).innerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    
    printWindow.document.write('<html><head><title>' + title + '</title>');
    printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">');
    printWindow.document.write('</head><body class="p-8">');
    printWindow.document.write('<h1 class="text-2xl font-bold mb-4">' + title + '</h1>');
    printWindow.document.write('<div><h2 class="text-lg font-semibold mb-2">Student: <?= htmlspecialchars($student['first_name'] . " " . $student['last_name']) ?> (<?= htmlspecialchars($student['admission_no']) ?>)</h2></div>');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    printWindow.focus();
    
    // Timer to allow styles to load with a fallback
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 1000);
}

// Export Helper Function
function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], { type: "text/csv" });
    const downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Export Single Table to CSV
function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    let csv = [];
    const rows = table.querySelectorAll("tr");
    
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (let j = 0; j < cols.length; j++) {
            // Get clean text content
            let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
            // Escape double quotes
            data = data.replace(/"/g, '""');
            // Wrap in double quotes
            row.push('"' + data + '"');
        }
        
        csv.push(row.join(","));
    }
    
    downloadCSV(csv.join("\n"), filename + ".csv");
}

// Export Payment History Groups to CSV
function exportPaymentHistoryGroups(containerId, filename) {
    exportGroupedTableToCSV(containerId, filename);
}

// Generic Export Grouped Tables to CSV
function exportGroupedTableToCSV(containerId, filename) {
    const container = document.getElementById(containerId);
    let csv = [];
    
    // Header for the CSV - Detect if it's payment or academic based on first table headers
    // But simplest is to check the containerID or just export what we find
    // For academic records: Exam, Subject, Marks, Grade
    // For payment: Date, Fee, Amount, Method, Remarks
    
    // Let's try to get headers dynamically from the first table found
    const firstTable = container.querySelector('table');
    let headerRow = [];
    if (firstTable) {
        headerRow.push('"Academic Year"');
        headerRow.push('"Term"');
        const ths = firstTable.querySelectorAll('thead th');
        ths.forEach(th => {
            headerRow.push('"' + th.innerText.trim() + '"');
        });
        csv.push(headerRow.join(','));
    } else {
        // Fallback or specific handling if needed
        csv.push('"Academic Year","Term","Date","Fee","Amount","Method","Remarks"');
    }
    
    // Iterate through academic years (h5) and their following content
    const yearHeaders = container.querySelectorAll('h5');
    
    yearHeaders.forEach(yearHeader => {
        const year = yearHeader.innerText.trim();
        
        // Let's traverse the DOM relative to the year header to find sibling term sections until the next year header
        let sibling = yearHeader.parentElement.nextElementSibling;
        
        while (sibling && !sibling.querySelector('h5')) { 
            // Check if this sibling is a term section
            const termHeader = sibling.querySelector('h6');
            if (termHeader) {
                const term = termHeader.innerText.trim();
                const table = sibling.querySelector('table');
                if (table) {
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const cols = row.querySelectorAll('td');
                        if (cols.length > 0) {
                            let rowData = [
                                '"' + year + '"',
                                '"' + term + '"'
                            ];
                            
                            cols.forEach(col => {
                                let data = col.innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
                                data = data.replace(/"/g, '""');
                                rowData.push('"' + data + '"');
                            });
                            
                            csv.push(rowData.join(","));
                        }
                    });
                }
            }
            sibling = sibling.nextElementSibling;
        }
    });
    
    if (csv.length <= 1) {
        alert('No data found to export.');
        return;
    }
    
    downloadCSV(csv.join("\n"), filename + ".csv");
}
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>