<?php $title = 'School Database (Historical Data)'; ob_start(); ?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900">School Database</h1>
            <p class="text-sm text-gray-500">Access historical data from previous academic years</p>
        </div>

        <!-- Academic Year Selector -->
        <div class="bg-white shadow rounded-lg p-6 mb-8">
            <form action="/school-database" method="GET" class="flex flex-col sm:flex-row gap-4 items-end">
                <div class="w-full sm:w-1/3">
                    <label for="academic_year_id" class="block text-sm font-medium text-gray-700 mb-1">Select Academic Year</label>
                    <select name="academic_year_id" id="academic_year_id" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" onchange="this.form.submit()">
                        <option value="">-- Select Year --</option>
                        <?php foreach ($academicYears as $year): ?>
                            <option value="<?= $year['id'] ?>" <?= (isset($selectedYearId) && $selectedYearId == $year['id']) ? 'selected' : '' ?>>
                                <?= htmlspecialchars($year['name']) ?> 
                                <?= ($year['status'] === 'completed') ? '(Completed)' : (($year['status'] === 'active') ? '(Active)' : '') ?>
                            </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <!-- Hidden inputs to preserve other filters if needed, or better, reset them -->
            </form>
        </div>

        <?php if ($selectedYearId): ?>
            <!-- Tabs -->
            <div class="border-b border-gray-200 mb-6">
                <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                    <a href="/school-database?academic_year_id=<?= $selectedYearId ?>&tab=students" 
                       class="<?= $tab === 'students' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Students
                    </a>
                    <a href="/school-database?academic_year_id=<?= $selectedYearId ?>&tab=staff" 
                       class="<?= $tab === 'staff' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Staff
                    </a>
                    <a href="/school-database?academic_year_id=<?= $selectedYearId ?>&tab=financials" 
                       class="<?= $tab === 'financials' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Financial Records
                    </a>
                    <a href="/school-database?academic_year_id=<?= $selectedYearId ?>&tab=academics" 
                       class="<?= $tab === 'academics' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?> whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                        Academic Records
                    </a>
                </nav>
            </div>

            <!-- Search Bar -->
            <div class="mb-6">
                <form action="/school-database" method="GET" class="flex gap-2">
                    <input type="hidden" name="academic_year_id" value="<?= $selectedYearId ?>">
                    <input type="hidden" name="tab" value="<?= $tab ?>">
                    <input type="text" name="search" value="<?= htmlspecialchars($search) ?>" placeholder="Search records..." class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Search
                    </button>
                    <?php if ($search): ?>
                        <a href="/school-database?academic_year_id=<?= $selectedYearId ?>&tab=<?= $tab ?>" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Clear
                        </a>
                    <?php endif; ?>
                </form>
            </div>

            <!-- Data Display -->
            <div class="bg-white shadow overflow-hidden sm:rounded-md">
                <?php if (empty($data)): ?>
                    <div class="p-6 text-center text-gray-500">
                        No records found for this selection.
                    </div>
                <?php else: ?>
                    <ul role="list" class="divide-y divide-gray-200">
                        <?php if ($tab === 'students'): ?>
                            <?php foreach ($data as $student): ?>
                                <li>
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-indigo-600 truncate"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></p>
                                            <div class="ml-2 flex-shrink-0 flex">
                                                <p class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">
                                                    <?= htmlspecialchars($student['admission_no']) ?>
                                                </p>
                                            </div>
                                        </div>
                                        <div class="mt-2 sm:flex sm:justify-between">
                                            <div class="sm:flex">
                                                <p class="flex items-center text-sm text-gray-500">
                                                    <?= htmlspecialchars($student['class_name'] ?? 'No Class') ?>
                                                </p>
                                            </div>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>
                        
                        <?php elseif ($tab === 'financials'): ?>
                             <div class="overflow-x-auto">
                                <table class="min-w-full divide-y divide-gray-200">
                                    <thead class="bg-gray-50">
                                        <tr>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Date</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Method</th>
                                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Fee Type</th>
                                        </tr>
                                    </thead>
                                    <tbody class="bg-white divide-y divide-gray-200">
                                        <?php foreach ($data['data'] ?? $data as $payment): ?>
                                            <tr>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($payment['date']) ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900"><?= htmlspecialchars($payment['first_name'] . ' ' . $payment['last_name']) ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars(number_format($payment['amount'], 2)) ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars(ucfirst(str_replace('_', ' ', $payment['method']))) ?></td>
                                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500"><?= htmlspecialchars($payment['fee_name'] ?? '-') ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                </table>
                            </div>

                        <?php elseif ($tab === 'staff'): ?>
                             <?php foreach ($data as $staff): ?>
                                <li>
                                    <div class="px-4 py-4 sm:px-6">
                                        <div class="flex items-center justify-between">
                                            <p class="text-sm font-medium text-indigo-600 truncate"><?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?></p>
                                            <p class="text-sm text-gray-500"><?= htmlspecialchars($staff['role_name'] ?? 'Staff') ?></p>
                                        </div>
                                    </div>
                                </li>
                            <?php endforeach; ?>

                        <?php else: ?>
                            <div class="p-4 text-gray-500">Module under development.</div>
                        <?php endif; ?>
                    </ul>
                <?php endif; ?>
            </div>
        <?php else: ?>
            <div class="bg-blue-50 border-l-4 border-blue-400 p-4">
                <div class="flex">
                    <div class="flex-shrink-0">
                        <!-- Heroicon name: solid/information-circle -->
                        <svg class="h-5 w-5 text-blue-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M18 10a8 8 0 11-16 0 8 8 0 0116 0zm-7-4a1 1 0 11-2 0 1 1 0 012 0zM9 9a1 1 0 000 2v3a1 1 0 001 1h1a1 1 0 100-2v-3a1 1 0 00-1-1H9z" clip-rule="evenodd" />
                        </svg>
                    </div>
                    <div class="ml-3">
                        <p class="text-sm text-blue-700">
                            Please select an academic year to view its historical data.
                        </p>
                    </div>
                </div>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>
