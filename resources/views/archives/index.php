<?php 
$title = 'School Database'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">School Database</h1>
        </div>

        <!-- Tabs -->
        <div class="mb-6">
            <div class="sm:hidden">
                <label for="tabs" class="sr-only">Select a tab</label>
                <select id="tabs" name="tabs" class="block w-full focus:ring-indigo-500 focus:border-indigo-500 border-gray-300 rounded-md" onchange="window.location.href=this.value">
                    <option value="?tab=audit_logs" <?= $tab === 'audit_logs' ? 'selected' : '' ?>>Audit Logs</option>
                    <option value="?tab=promotions" <?= $tab === 'promotions' ? 'selected' : '' ?>>Promotions</option>
                    <option value="?tab=financial" <?= $tab === 'financial' ? 'selected' : '' ?>>Financial Records</option>
                    <option value="?tab=academic" <?= $tab === 'academic' ? 'selected' : '' ?>>Academic Records</option>
                    <option value="?tab=students" <?= $tab === 'students' ? 'selected' : '' ?>>Students</option>
                    <option value="?tab=staff" <?= $tab === 'staff' ? 'selected' : '' ?>>Staff</option>
                </select>
            </div>
            <div class="hidden sm:block">
                <nav class="flex space-x-4 bg-white p-2 rounded-lg shadow-sm" aria-label="Tabs">
                    <?php 
                    $tabsArray = [
                        'audit_logs' => 'Audit Logs',
                        'promotions' => 'Promotions',
                        'financial' => 'Financial Records',
                        'academic' => 'Academic Records',
                        'students' => 'Students',
                        'staff' => 'Staff'
                    ];
                    foreach ($tabsArray as $k => $label):
                        $active = ($tab === $k);
                    ?>
                    <a href="?tab=<?= $k ?>" class="<?= $active ? 'bg-indigo-100 text-indigo-700' : 'text-gray-500 hover:text-gray-700 hover:bg-gray-50' ?> px-3 py-2 font-medium text-sm rounded-md transition-colors">
                        <?= $label ?>
                    </a>
                    <?php endforeach; ?>
                </nav>
            </div>
        </div>

        <!-- Filter Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Filter Records</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Search and filter historical data.</p>
            </div>
            <div class="border-t border-gray-200">
                <form method="GET" class="px-4 py-5 sm:p-6">
                    <input type="hidden" name="tab" value="<?= htmlspecialchars($tab) ?>">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                        <?php if ($tab === 'audit_logs'): ?>
                        <div>
                            <label for="module_id" class="block text-sm font-medium text-gray-700">Module</label>
                            <select name="module_id" id="module_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Modules</option>
                                <?php foreach ($modules ?? [] as $module): ?>
                                    <option value="<?= htmlspecialchars($module) ?>" <?= (isset($moduleId) && $moduleId == $module) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars(ucwords(str_replace('_', ' ', $module))) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        <?php endif; ?>
                        
                        <div>
                            <label for="academic_year_id" class="block text-sm font-medium text-gray-700">Academic Year</label>
                            <select name="academic_year_id" id="academic_year_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Academic Years</option>
                                <?php foreach ($academicYears ?? [] as $year): ?>
                                    <option value="<?= $year['id'] ?>" <?= (isset($academicYearId) && $academicYearId == $year['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($year['name']) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="term" class="block text-sm font-medium text-gray-700">Term</label>
                            <select name="term" id="term" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Terms</option>
                                <?php foreach ($terms ?? [] as $termOption): ?>
                                    <option value="<?= htmlspecialchars($termOption) ?>" <?= (isset($term) && $term == $termOption) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars($termOption) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <div>
                            <label for="user_id" class="block text-sm font-medium text-gray-700">User</label>
                            <select name="user_id" id="user_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">All Users</option>
                                <?php foreach ($users ?? [] as $user): ?>
                                    <option value="<?= $user['id'] ?>" <?= (isset($userId) && $userId == $user['id']) ? 'selected' : '' ?>>
                                        <?= htmlspecialchars(($user['first_name'] ?? '') . ' ' . ($user['last_name'] ?? $user['username'])) ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>
                        
                        <?php if (in_array($tab, ['financial', 'academic'])): ?>
                            <div>
                                <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                                <select name="class_id" id="class_id" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="">All Classes</option>
                                    <?php foreach ($classes ?? [] as $class): ?>
                                        <option value="<?= $class['id'] ?>" <?= (isset($classId) && $classId == $class['id']) ? 'selected' : '' ?>>
                                            <?= htmlspecialchars($class['name']) ?>
                                        </option>
                                    <?php endforeach; ?>
                                </select>
                            </div>

                            <div>
                                <label for="search" class="block text-sm font-medium text-gray-700">Search</label>
                                <input type="text" name="search" id="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Name or admission no" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            </div>
                        <?php endif; ?>
                        
                        <div>
                            <label for="date_from" class="block text-sm font-medium text-gray-700">Date From</label>
                            <input type="date" name="date_from" id="date_from" value="<?= htmlspecialchars($dateFrom ?? '') ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="date_to" class="block text-sm font-medium text-gray-700">Date To</label>
                            <input type="date" name="date_to" id="date_to" value="<?= htmlspecialchars($dateTo ?? '') ?>" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>
                        
                        <div>
                            <label for="per_page" class="block text-sm font-medium text-gray-700">Records Per Page</label>
                            <select name="per_page" id="per_page" class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="10" <?= $perPage == 10 ? 'selected' : '' ?>>10</option>
                                <option value="25" <?= $perPage == 25 ? 'selected' : '' ?>>25</option>
                                <option value="50" <?= $perPage == 50 ? 'selected' : '' ?>>50</option>
                                <option value="100" <?= $perPage == 100 ? 'selected' : '' ?>>100</option>
                            </select>
                        </div>
                        
                        <div class="sm:col-span-2 flex items-end">
                            <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Filter
                            </button>
                            <a href="/archives?tab=<?= htmlspecialchars($tab) ?>" class="ml-3 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Clear
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Archive Records Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900"><?= htmlspecialchars($tabsArray[$tab] ?? 'Records') ?></h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Historical data organized by criteria.</p>
            </div>
            <div class="border-t border-gray-200 overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <?php if ($tab === 'audit_logs'): ?>
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                User
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Action
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Module
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Academic Year
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Term
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Record ID
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                IP Address
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                        <?php elseif ($tab === 'promotions'): ?>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">From Class</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">To Class</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Year</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Promoted By</th>
                        </tr>
                        <?php elseif ($tab === 'financial'): ?>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Category</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount Paid</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                        </tr>
                        <?php elseif ($tab === 'academic'): ?>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subject</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Grade</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Total Marks</th>
                        </tr>
                        <?php elseif ($tab === 'students'): ?>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined</th>
                        </tr>
                        <?php elseif ($tab === 'staff'): ?>
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Phone</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Joined Date</th>
                        </tr>
                        <?php endif; ?>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($records)): ?>
                        <tr>
                            <td colspan="10" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No records found.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php if ($tab === 'academic'): 
                            $groupedRecords = [];
                            foreach ($records as $log) {
                                $yearTermKey = ($log['academic_year_name'] ?? 'N/A') . ' - ' . ($log['term'] ?? 'N/A');
                                $examName = $log['exam_name'] ?? 'Undefined Exam';
                                $groupedRecords[$yearTermKey][$examName][] = $log;
                            }
                        ?>
                            <tr>
                                <td colspan="10" class="p-0 border-none">
                                    <?php foreach ($groupedRecords as $yearTerm => $exams): ?>
                                        <div class="bg-gray-50 px-6 py-3 border-b border-gray-200">
                                            <h4 class="text-sm font-semibold text-gray-700 uppercase tracking-wider"><?= htmlspecialchars($yearTerm) ?></h4>
                                        </div>
                                        <?php foreach ($exams as $examName => $examLogs): ?>
                                            <div x-data="{ open: false }" class="bg-white border-b border-gray-200 last:border-b-0">
                                                <button @click="open = !open" class="w-full px-6 py-4 flex justify-between items-center focus:outline-none hover:bg-gray-50 transition-colors duration-150 relative">
                                                    <div class="flex items-center space-x-3">
                                                        <svg class="h-5 w-5 text-gray-400 transform transition-transform duration-200" :class="{ 'rotate-90': open }" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                                                        </svg>
                                                        <span class="text-sm font-medium text-gray-900"><?= htmlspecialchars($examName) ?></span>
                                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-indigo-100 text-indigo-800">
                                                            <?= count($examLogs) ?> results
                                                        </span>
                                                    </div>
                                                </button>
                                                <div x-show="open" x-collapse x-cloak>
                                                    <table class="min-w-full divide-y divide-gray-200 border-t border-gray-200">
                                                        <thead class="bg-gray-50 uppercase text-xs text-gray-500 font-medium">
                                                            <tr>
                                                                <th class="px-6 py-3 text-left pl-14">Student</th>
                                                                <th class="px-6 py-3 text-left">Subject</th>
                                                                <th class="px-6 py-3 text-left">Grade</th>
                                                                <th class="px-6 py-3 text-left">Marks</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody class="divide-y divide-gray-200 bg-white">
                                                            <?php foreach ($examLogs as $log): ?>
                                                                <tr class="hover:bg-gray-50">
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900 pl-14">
                                                                        <?= htmlspecialchars(($log['first_name'] ?? '') . ' ' . ($log['last_name'] ?? '')) ?>
                                                                        <div class="text-xs text-gray-500 font-normal"><?= htmlspecialchars($log['admission_no'] ?? '') ?></div>
                                                                    </td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['subject_name'] ?? 'N/A') ?></td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-700"><?= htmlspecialchars($log['grade'] ?? 'N/A') ?></td>
                                                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['marks'] ?? '0') ?>/100</td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    <?php endforeach; ?>
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($records as $log): ?>
                            <tr>
                                <?php if ($tab === 'audit_logs'): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($log['username'] ?? 'N/A') ?>
                                    <?php if ($log['first_name'] || $log['last_name']): ?>
                                    <div class="text-gray-500 text-xs">
                                        <?= htmlspecialchars(($log['first_name'] ?? '') . ' ' . ($log['last_name'] ?? '')) ?>
                                    </div>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                        <?php 
                                        switch ($log['action']) {
                                            case 'create':
                                                echo 'bg-green-100 text-green-800';
                                                break;
                                            case 'update':
                                                echo 'bg-blue-100 text-blue-800';
                                                break;
                                            case 'delete':
                                                echo 'bg-red-100 text-red-800';
                                                break;
                                            default:
                                                echo 'bg-gray-100 text-gray-800';
                                        }
                                        ?>">
                                        <?= ucfirst(htmlspecialchars($log['action'])) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars(ucwords(str_replace('_', ' ', $log['table_name'] ?? 'N/A'))) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($log['academic_year_name'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($log['term'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($log['record_id'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($log['ip_address'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M j, Y g:i A', strtotime($log['created_at'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <button onclick="viewAuditDetails(<?= $log['id'] ?>)" class="text-indigo-600 hover:text-indigo-900 focus:outline-none">
                                        View
                                    </button>
                                </td>
                                <?php elseif ($tab === 'promotions'): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars(($log['first_name'] ?? '') . ' ' . ($log['last_name'] ?? '')) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['from_class_name'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['to_class_name'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['academic_year_name'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('M j, Y', strtotime($log['promotion_date'])) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['actor_name'] ?? 'System') ?></td>
                                
                                <?php elseif ($tab === 'financial'): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars(($log['first_name'] ?? '') . ' ' . ($log['last_name'] ?? '')) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['fee_name'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-green-600">GH₵ <?= number_format($log['amount'] ?? 0, 2) ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800"><?= htmlspecialchars(ucfirst($log['method'] ?? 'N/A')) ?></span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('M j, Y', strtotime($log['date'])) ?></td>
                                
                                <?php elseif ($tab === 'students'): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars(($log['first_name'] ?? '') . ' ' . ($log['last_name'] ?? '')) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['admission_no'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['class_name'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('M j, Y', strtotime($log['admission_date'] ?? $log['created_at'])) ?></td>
                                
                                <?php elseif ($tab === 'staff'): ?>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars(($log['first_name'] ?? '') . ' ' . ($log['last_name'] ?? '')) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-indigo-100 text-indigo-800"><?= htmlspecialchars($log['role'] ?? 'N/A') ?></span></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['department'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($log['phone'] ?? 'N/A') ?></td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= date('M j, Y', strtotime($log['hire_date'] ?? $log['created_at'])) ?></td>
                                <?php endif; ?>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                        <?php endif; ?>
                    </tbody>
                    <?php if ($tab === 'financial' && !empty($records)): ?>
                    <tfoot class="bg-gray-50 border-t border-gray-200">
                        <tr>
                            <td colspan="2" class="px-6 py-4 whitespace-nowrap text-sm font-semibold text-gray-900 text-right">
                                Total Amount for Filtered Records:
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-green-700">
                                GH₵ <?= number_format($pagination['total_amount'] ?? 0, 2) ?>
                            </td>
                            <td colspan="2"></td>
                        </tr>
                    </tfoot>
                    <?php endif; ?>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?php if ($pagination['current_page'] > 1): ?>
                    <a href="<?= buildPaginationUrl($pagination['current_page'] - 1) ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <a href="<?= buildPaginationUrl($pagination['current_page'] + 1) ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Next
                    </a>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium"><?= (($pagination['current_page'] - 1) * $pagination['per_page']) + 1 ?></span>
                            to
                            <span class="font-medium"><?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total']) ?></span>
                            of
                            <span class="font-medium"><?= $pagination['total'] ?></span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <?php if ($pagination['current_page'] > 1): ?>
                            <a href="<?= buildPaginationUrl($pagination['current_page'] - 1) ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <?php endif; ?>
                            
                            <?php for ($i = max(1, $pagination['current_page'] - 2); $i <= min($pagination['total_pages'], $pagination['current_page'] + 2); $i++): ?>
                            <a href="<?= buildPaginationUrl($i) ?>" class="relative inline-flex items-center px-4 py-2 border <?= $i == $pagination['current_page'] ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' ?> text-sm font-medium">
                                <?= $i ?>
                            </a>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <a href="<?= buildPaginationUrl($pagination['current_page'] + 1) ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
// Function to build pagination URLs
function buildPaginationUrl($page) {
    $params = $_GET;
    $params['page'] = $page;
    
    // Ensure default tab is present if missing
    if (!isset($params['tab'])) {
        $params['tab'] = 'audit_logs';
    }
    
    return '/archives?' . http_build_query($params);
}
?>

<!-- Audit Log Modal -->
<div id="auditModal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeAuditModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900 mb-4" id="modal-title">
                            Historical Record Details
                        </h3>
                        <div id="auditModalContent" class="overflow-x-auto text-sm text-gray-700">
                            <!-- Content injected via JS -->
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm" onclick="closeAuditModal()">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function viewAuditDetails(id) {
    document.getElementById('auditModal').classList.remove('hidden');
    document.getElementById('auditModalContent').innerHTML = '<div class="text-center py-4"><svg class="animate-spin h-6 w-6 text-indigo-600 mx-auto" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg></div>';
    
    fetch('/archives/' + id + '?ajax=1')
        .then(res => res.json())
        .then(json => {
            if (json.success) {
                const data = json.data;
                let html = '<div class="grid grid-cols-1 md:grid-cols-2 gap-4">';
                
                const userText = data.username ? data.username + (data.first_name ? ' ('+data.first_name+' '+data.last_name+')' : '') : 'N/A';
                
                html += '<div class="bg-gray-50 p-3 rounded"><dt class="font-medium text-gray-500">User</dt><dd>' + userText + '</dd></div>';
                html += '<div class="bg-gray-50 p-3 rounded"><dt class="font-medium text-gray-500">Action</dt><dd class="capitalize">' + (data.action || 'N/A') + '</dd></div>';
                html += '<div class="bg-gray-50 p-3 rounded"><dt class="font-medium text-gray-500">Module</dt><dd>' + (data.table_name || 'N/A') + '</dd></div>';
                html += '<div class="bg-gray-50 p-3 rounded"><dt class="font-medium text-gray-500">Record ID</dt><dd>' + (data.record_id || 'N/A') + '</dd></div>';
                html += '<div class="bg-gray-50 p-3 rounded"><dt class="font-medium text-gray-500">Time</dt><dd>' + (data.created_at || 'N/A') + '</dd></div>';
                html += '<div class="bg-gray-50 p-3 rounded"><dt class="font-medium text-gray-500">IP Address</dt><dd>' + (data.ip_address || 'N/A') + '</dd></div>';
                html += '</div>';
                
                if (data.old_values) {
                    html += '<h4 class="font-medium mt-4 mb-2">Previous Data</h4>';
                    html += '<pre class="bg-gray-100 p-3 rounded overflow-x-auto text-xs">' + JSON.stringify(data.old_values, null, 2) + '</pre>';
                }
                
                if (data.new_values) {
                    html += '<h4 class="font-medium mt-4 mb-2">New/Updated Data</h4>';
                    html += '<pre class="bg-gray-100 p-3 rounded overflow-x-auto text-xs">' + JSON.stringify(data.new_values, null, 2) + '</pre>';
                }
                
                document.getElementById('auditModalContent').innerHTML = html;
            } else {
                document.getElementById('auditModalContent').innerHTML = '<div class="text-red-500 py-4">Failed to load internal record details.</div>';
            }
        })
        .catch(err => {
            document.getElementById('auditModalContent').innerHTML = '<div class="text-red-500 py-4">Network error loading record details.</div>';
        });
}

function closeAuditModal() {
    document.getElementById('auditModal').classList.add('hidden');
}
</script>

<?php
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>