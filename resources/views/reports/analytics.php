<?php 
$title = 'Analytics Dashboard'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Analytics Dashboard</h1>
        
        <!-- Key Metrics -->
        <div class="grid grid-cols-1 gap-6 sm:grid-cols-3 mb-8">
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Students</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-lg font-semibold text-gray-900">
                                        <?= $totalStudents ?? 0 ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.656.126-1.283.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Staff</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-lg font-semibold text-gray-900">
                                        <?= $totalStaff ?? 0 ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>

            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 bg-purple-500 rounded-md p-3">
                            <svg class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                            </svg>
                        </div>
                        <div class="ml-5 w-0 flex-1">
                            <dl>
                                <dt class="text-sm font-medium text-gray-500 truncate">Total Classes</dt>
                                <dd class="flex items-baseline">
                                    <div class="text-lg font-semibold text-gray-900">
                                        <?= $totalClasses ?? 0 ?>
                                    </div>
                                </dd>
                            </dl>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Section -->
        <div class="grid grid-cols-1 gap-6 lg:grid-cols-2 mb-8">
            <!-- Recent Payments Chart -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Payments</h2>
                    <?php if (!empty($recentPayments)): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach (array_slice($recentPayments, 0, 5) as $payment): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?= htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']) ?></div>
                                                <div class="text-sm text-gray-500"><?= htmlspecialchars($payment['admission_no']) ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= htmlspecialchars($payment['amount']) ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= date('M j, Y', strtotime($payment['date'])) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500">No recent payments found.</p>
                    <?php endif; ?>
                </div>
            </div>

            <!-- Recent Attendance Chart -->
            <div class="bg-white overflow-hidden shadow rounded-lg">
                <div class="px-4 py-5 sm:p-6">
                    <h2 class="text-lg font-medium text-gray-900 mb-4">Recent Attendance</h2>
                    <?php if (!empty($recentAttendance)): ?>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach (array_slice($recentAttendance, 0, 5) as $attendance): ?>
                                        <tr>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <div class="text-sm text-gray-900"><?= htmlspecialchars($attendance['first_name'] . ' ' . $attendance['last_name']) ?></div>
                                                <div class="text-sm text-gray-500"><?= htmlspecialchars($attendance['admission_no']) ?></div>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= htmlspecialchars($attendance['class_name'] ?? 'N/A') ?>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap">
                                                <?php
                                                $statusClass = '';
                                                switch ($attendance['status']) {
                                                    case 'present':
                                                        $statusClass = 'bg-green-100 text-green-800';
                                                        break;
                                                    case 'absent':
                                                        $statusClass = 'bg-red-100 text-red-800';
                                                        break;
                                                    case 'late':
                                                        $statusClass = 'bg-yellow-100 text-yellow-800';
                                                        break;
                                                    default:
                                                        $statusClass = 'bg-gray-100 text-gray-800';
                                                }
                                                ?>
                                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $statusClass ?>">
                                                    <?= ucfirst(htmlspecialchars($attendance['status'])) ?>
                                                </span>
                                            </td>
                                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                                <?= date('M j, Y', strtotime($attendance['date'])) ?>
                                            </td>
                                        </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    <?php else: ?>
                        <p class="text-gray-500">No recent attendance records found.</p>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Exam Statistics -->
        <div class="bg-white overflow-hidden shadow rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <h2 class="text-lg font-medium text-gray-900 mb-4">Exam Statistics</h2>
                <?php if (!empty($examStats)): ?>
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-3">
                        <div class="border rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-500">Total Exams</h3>
                            <p class="text-3xl font-bold text-gray-900"><?= $examStats['total_exams'] ?? 0 ?></p>
                        </div>
                        <div class="border rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-500">Students with Results</h3>
                            <p class="text-3xl font-bold text-gray-900"><?= $examStats['total_students'] ?? 0 ?></p>
                        </div>
                        <div class="border rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-500">Subjects</h3>
                            <p class="text-3xl font-bold text-gray-900"><?= $examStats['total_subjects'] ?? 0 ?></p>
                        </div>
                        <div class="border rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-500">Average Score</h3>
                            <p class="text-3xl font-bold text-gray-900"><?= number_format($examStats['average_marks'] ?? 0, 2) ?></p>
                        </div>
                        <div class="border rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-500">Highest Score</h3>
                            <p class="text-3xl font-bold text-green-600"><?= $examStats['highest_marks'] ?? 0 ?></p>
                        </div>
                        <div class="border rounded-lg p-4">
                            <h3 class="text-sm font-medium text-gray-500">Lowest Score</h3>
                            <p class="text-3xl font-bold text-red-600"><?= $examStats['lowest_marks'] ?? 0 ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <p class="text-gray-500">No exam statistics available.</p>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>