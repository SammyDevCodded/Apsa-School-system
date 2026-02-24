<?php 
$title = 'Class Details - ' . htmlspecialchars($class['name']); 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <!-- Back button -->
        <div class="mb-6">
            <a href="/classes" class="inline-flex items-center text-indigo-600 hover:text-indigo-900">
                <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10 19l-7-7m0 0l7-7m-7 7h18"></path>
                </svg>
                Back to Classes
            </a>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $_SESSION['flash_success'] ?></span>
        </div>
        <?php unset($_SESSION['flash_success']); endif; ?>

        <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $_SESSION['flash_error'] ?></span>
        </div>
        <?php unset($_SESSION['flash_error']); endif; ?>

        <!-- Class Information -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 cursor-pointer" id="class-info-header">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Class Information</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Details about <?= htmlspecialchars($class['name']) ?></p>
                    </div>
                    <div class="flex items-center">
                        <svg id="class-expand-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200 rotate-180" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200" id="class-info-details">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Class Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($class['name']) ?></dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Level</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($class['level']) ?></dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Capacity</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($class['capacity']) ?></dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Current Students</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($class['student_count']) ?></dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Utilization</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?php 
                            $utilizationClass = '';
                            if ($utilization >= 90) {
                                $utilizationClass = 'text-red-600 font-bold';
                            } elseif ($utilization >= 75) {
                                $utilizationClass = 'text-yellow-600';
                            } else {
                                $utilizationClass = 'text-green-600';
                            }
                            ?>
                            <span class="<?= $utilizationClass ?>"><?= $utilization ?>%</span>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Assigned Subjects</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?php if (empty($assignedSubjects)): ?>
                                <span class="text-gray-500">No subjects assigned</span>
                            <?php else: ?>
                                <div class="flex flex-wrap gap-2">
                                    <?php foreach ($assignedSubjects as $subject): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?= htmlspecialchars($subject['name']) ?> (<?= htmlspecialchars($subject['code']) ?>)
                                        </span>
                                    <?php endforeach; ?>
                                </div>
                            <?php endif; ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Created At</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= isset($class['created_at']) ? date('F j, Y g:i A', strtotime($class['created_at'])) : 'N/A' ?>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <button onclick="showTab('academic')" id="tab-academic" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Academic Performance
                </button>
                <button onclick="showTab('financial')" id="tab-financial" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Financial Data
                </button>
                <button onclick="showTab('promotion')" id="tab-promotion" class="tab-button border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm">
                    Promotion Stats
                </button>
            </nav>
        </div>

        <!-- Collapsible Controls -->
        <div class="mb-4 flex justify-end space-x-2">
            <button onclick="toggleAllSections(true)" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                </svg>
                Expand All
            </button>
            <button onclick="toggleAllSections(false)" class="inline-flex items-center px-3 py-1 border border-gray-300 shadow-sm text-sm leading-4 font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-0.5 mr-2 h-4 w-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 15l7-7 7 7" />
                </svg>
                Collapse All
            </button>
        </div>

        <!-- Academic Performance Tab -->
        <div id="tab-content-academic" class="tab-content">
            <!-- Term-Based Performance -->
            <?php if (!empty($termPerformance)): ?>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 cursor-pointer collapsible-header" data-target="term-performance">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Performance by Academic Year & Term</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Academic performance breakdown</p>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-500 transform transition-transform duration-200 rotate-180 collapsible-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-200 px-4 py-5 sm:px-6 collapsible-content" id="term-performance">
                    <div class="space-y-8">
                        <?php foreach ($termPerformance as $year => $terms): ?>
                        <div class="border border-gray-300 rounded-lg overflow-hidden">
                            <div class="bg-gray-100 px-6 py-3 border-b border-gray-300">
                                <h4 class="text-lg font-bold text-gray-800"><?= htmlspecialchars($year) ?></h4>
                            </div>
                            <div class="p-6 space-y-6">
                                <?php foreach ($terms as $term => $performance): ?>
                                <div class="border border-gray-200 rounded-lg p-6 shadow-sm">
                                    <div class="flex justify-between items-start mb-4">
                                        <h5 class="text-md font-bold text-indigo-700">Term <?= htmlspecialchars($term) ?></h5>
                                        <div class="flex space-x-4">
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                                <?= $performance['exams_count'] ?> exams
                                            </span>
                                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                                <?= $performance['total_results'] ?> results
                                            </span>
                                        </div>
                                    </div>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div class="bg-blue-50 p-3 rounded-lg text-center">
                                            <div class="text-sm font-medium text-blue-800">Term Average</div>
                                            <div class="mt-1 text-xl font-bold text-blue-900"><?= $performance['average'] ?></div>
                                        </div>
                                        <div class="bg-green-50 p-3 rounded-lg text-center">
                                            <div class="text-sm font-medium text-green-800">Total Results</div>
                                            <div class="mt-1 text-xl font-bold text-green-900"><?= $performance['total_results'] ?></div>
                                        </div>
                                        <div class="bg-purple-50 p-3 rounded-lg text-center">
                                            <div class="text-sm font-medium text-purple-800">Subjects</div>
                                            <div class="mt-1 text-xl font-bold text-purple-900"><?= count($performance['subject_performance']) ?></div>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($performance['subject_performance'])): ?>
                                    <div>
                                        <h6 class="text-sm font-medium text-gray-700 mb-2">Subject Performance:</h6>
                                        <div class="grid grid-cols-2 md:grid-cols-3 lg:grid-cols-4 gap-2">
                                            <?php foreach ($performance['subject_performance'] as $subject => $subjectPerf): ?>
                                            <div class="bg-gray-50 p-2 rounded text-center">
                                                <div class="text-xs font-medium text-gray-600 truncate" title="<?= htmlspecialchars($subject) ?>"><?= htmlspecialchars($subject) ?></div>
                                                <div class="text-sm font-bold text-indigo-600 mt-1"><?= $subjectPerf['average'] ?></div>
                                                <div class="text-xs text-gray-500"><?= $subjectPerf['count'] ?> results</div>
                                            </div>
                                            <?php endforeach; ?>
                                        </div>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>

            <!-- Exams Section -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 cursor-pointer collapsible-header" data-target="exams-list">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Exams</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Exams conducted for this class</p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                                <?= count($exams) ?> exams
                            </span>
                            <svg class="h-5 w-5 text-gray-500 transform transition-transform duration-200 rotate-180 collapsible-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-200 collapsible-content" id="exams-list">
                    <?php if (empty($exams)): ?>
                    <div class="px-4 py-5 sm:px-6 text-center text-gray-500">
                        No exams found for this class.
                    </div>
                    <?php else: ?>
                    <?php 
                        $groupedExams = [];
                        foreach ($exams as $exam) {
                            $year = $exam['academic_year_name'] ?? 'Unknown Academic Year';
                            $term = $exam['term'] ?? 'Unknown Term';
                            $groupedExams[$year][$term][] = $exam;
                        }
                    ?>
                    <div class="bg-white">
                        <?php foreach ($groupedExams as $year => $terms): ?>
                        <div class="border-b border-gray-200 bg-gray-50 px-4 py-3 sm:px-6">
                            <h4 class="text-md font-bold text-gray-800"><?= htmlspecialchars($year) ?></h4>
                        </div>
                        <?php foreach ($terms as $term => $termExams): ?>
                        <div class="bg-gray-100 px-4 py-2 sm:px-6 border-b border-gray-200">
                            <h5 class="text-sm font-semibold text-gray-700">Term <?= htmlspecialchars($term) ?></h5>
                        </div>
                        <ul class="divide-y divide-gray-200">
                            <?php foreach ($termExams as $exam): ?>
                            <li class="px-4 py-4 sm:px-6">
                                <div class="flex items-center justify-between">
                                    <div class="flex-1 min-w-0">
                                        <div class="flex items-center">
                                            <p class="text-sm font-medium text-indigo-600 truncate">
                                                <?= htmlspecialchars($exam['name']) ?>
                                            </p>
                                        </div>
                                        <div class="mt-1 flex items-center text-sm text-gray-500">
                                            <span><?= isset($exam['date']) ? date('M j, Y', strtotime($exam['date'])) : 'No date set' ?></span>
                                            <?php if (!empty($exam['description'])): ?>
                                            <span class="mx-2">•</span>
                                            <span><?= htmlspecialchars($exam['description']) ?></span>
                                            <?php endif; ?>
                                        </div>
                                    </div>
                                    <div class="ml-4 flex-shrink-0">
                                        <button onclick="openExamResultsModal(<?= $exam['id'] ?>)" class="font-medium text-indigo-600 hover:text-indigo-500">
                                            View Results
                                        </button>
                                    </div>
                                </div>
                            </li>
                            <?php endforeach; ?>
                        </ul>
                        <?php endforeach; ?>
                        <?php endforeach; ?>
                    </div>
                    <?php endif; ?>
                </div>
            </div>
        </div>

        <!-- Financial Data Tab -->
        <div id="tab-content-financial" class="tab-content hidden">
            <?php if (isset($financialStats)): ?>
            <!-- Financial Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-4 gap-6 mb-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Total Bills</h3>
                            <p class="text-2xl font-bold text-gray-900">₵<?= number_format($financialStats['total_bills'], 2) ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Total Paid</h3>
                            <p class="text-2xl font-bold text-gray-900">₵<?= number_format($financialStats['total_paid'], 2) ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-yellow-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-yellow-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Balance</h3>
                            <p class="text-2xl font-bold text-gray-900">₵<?= number_format($financialStats['total_balance'], 2) ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-red-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-red-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Defaulters</h3>
                            <p class="text-2xl font-bold text-gray-900"><?= count($financialStats['fee_defaulters']) ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Termly Breakdown Section -->
            <?php if (!empty($financialStats['termly_breakdown'])): ?>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6 cursor-pointer collapsible-header" data-target="termly-breakdown">
                    <div class="flex justify-between items-center">
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">Termly Financial Breakdown</h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">Summary of bills, payments, and balances per academic term</p>
                        </div>
                        <div class="flex items-center">
                            <svg class="h-5 w-5 text-gray-500 transform transition-transform duration-200 rotate-180 collapsible-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                            </svg>
                        </div>
                    </div>
                </div>
                <div class="border-t border-gray-200 collapsible-content" id="termly-breakdown">
                    <div class="p-6 space-y-8">
                        <?php foreach ($financialStats['termly_breakdown'] as $year => $terms): ?>
                        <div class="border border-gray-300 rounded-lg overflow-hidden">
                            <div class="bg-gray-100 px-6 py-3 border-b border-gray-300">
                                <h4 class="text-lg font-bold text-gray-800"><?= htmlspecialchars($year) ?></h4>
                            </div>
                            <div class="p-4 space-y-6">
                                <?php foreach ($terms as $term => $termStats): ?>
                                <div class="border border-gray-200 rounded-lg p-5 shadow-sm">
                                    <h5 class="text-md font-bold text-indigo-700 mb-4">Term <?= htmlspecialchars($term) ?></h5>
                                    
                                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-4">
                                        <div class="bg-blue-50 p-4 rounded-lg flex items-center justify-between">
                                            <span class="text-sm font-medium text-blue-800">Billed</span>
                                            <span class="text-lg font-bold text-blue-900">₵<?= number_format($termStats['total_billed'], 2) ?></span>
                                        </div>
                                        <div class="bg-green-50 p-4 rounded-lg flex items-center justify-between">
                                            <span class="text-sm font-medium text-green-800">Paid</span>
                                            <span class="text-lg font-bold text-green-900">₵<?= number_format($termStats['total_paid'], 2) ?></span>
                                        </div>
                                        <div class="bg-yellow-50 p-4 rounded-lg flex items-center justify-between">
                                            <span class="text-sm font-medium text-yellow-800">Balance</span>
                                            <span class="text-lg font-bold text-yellow-900">₵<?= number_format($termStats['balance'], 2) ?></span>
                                        </div>
                                    </div>
                                    
                                    <?php if (!empty($termStats['fees'])): ?>
                                    <div class="mt-4">
                                        <h6 class="text-xs font-semibold text-gray-500 uppercase tracking-wider mb-2">Fees Included:</h6>
                                        <ul class="space-y-2">
                                            <?php foreach ($termStats['fees'] as $fee): ?>
                                            <li class="bg-gray-50 p-2 rounded flex justify-between text-sm">
                                                <span class="font-medium text-gray-700"><?= htmlspecialchars($fee['name']) ?></span>
                                                <div class="text-right space-x-3 text-xs md:text-sm">
                                                    <span class="text-gray-500">Billed: ₵<?= number_format($fee['billed'], 2) ?></span>
                                                    <span class="text-green-600 border-l border-gray-300 pl-2 ml-1">Paid: ₵<?= number_format($fee['paid'], 2) ?></span>
                                                    <span class="text-red-500 border-l border-gray-300 pl-2 ml-1">Bal: ₵<?= number_format($fee['balance'], 2) ?></span>
                                                </div>
                                            </li>
                                            <?php endforeach; ?>
                                        </ul>
                                    </div>
                                    <?php endif; ?>
                                </div>
                                <?php endforeach; ?>
                            </div>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
            <?php endif; ?>
            
            <!-- Fee Defaulters Section -->
            <?php if (!empty($financialStats['fee_defaulters'])): ?>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Fee Defaulters</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                            <?= count($financialStats['fee_defaulters']) ?> students
                        </span>
                    </div>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Students with outstanding balances</p>
                </div>
                <div class="border-t border-gray-200">
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
                                        Total Billed
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Amount Paid
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Balance
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Actions
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach (array_slice($financialStats['fee_defaulters'], 0, 10) as $defaulter): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($defaulter['student_info']['first_name'] . ' ' . $defaulter['student_info']['last_name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($defaulter['student_info']['admission_no']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ₵<?= number_format($defaulter['total_billed'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        ₵<?= number_format($defaulter['total_paid'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-red-600">
                                        ₵<?= number_format($defaulter['balance'], 2) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button onclick="openStudentProfileModal(<?= $defaulter['student_info']['id'] ?>, true)" class="text-indigo-600 hover:text-indigo-900">
                                            View Profile
                                        </button>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <?php if (count($financialStats['fee_defaulters']) > 10): ?>
                        <div class="px-6 py-3 bg-gray-50 text-center">
                            <p class="text-sm text-gray-500">
                                Showing top 10 defaulters. <?= count($financialStats['fee_defaulters']) - 10 ?> more students have outstanding balances.
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No Fee Defaulters</h3>
                <p class="mt-1 text-sm text-gray-500">All students in this class are up to date with their payments.</p>
            </div>
            <?php endif; ?>
            
            <?php else: ?>
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <div class="text-gray-500">
                    <p>Financial data not available</p>
                    <p class="text-sm mt-2">No financial records found for this class.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Promotion Stats Tab -->
        <div id="tab-content-promotion" class="tab-content hidden">
            <?php if (isset($promotionStats)): ?>
            <!-- Promotion Summary Cards -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-6">
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-blue-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-blue-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 7h8m0 0v8m0-8l-8 8-4-4-6 6"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Students Promoted Out</h3>
                            <p class="text-2xl font-bold text-gray-900"><?= $promotionStats['students_promoted_out'] ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 bg-green-100 rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 text-green-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M14 10h4.764a2 2 0 011.789 2.894l-3.5 7A2 2 0 0115.263 21h-4.017c-.163 0-.326-.02-.485-.06L7 20m7-10V5a2 2 0 00-2-2h-.095c-.5 0-.905.405-.905.905 0 .714-.211 1.412-.608 2.006L7 11v9m7-10h-2M7 20H5a2 2 0 01-2-2v-6a2 2 0 012-2h2.5"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Students Promoted In</h3>
                            <p class="text-2xl font-bold text-gray-900"><?= $promotionStats['students_promoted_in'] ?></p>
                        </div>
                    </div>
                </div>
                
                <div class="bg-white shadow rounded-lg p-6">
                    <div class="flex items-center">
                        <div class="flex-shrink-0">
                            <div class="w-8 h-8 <?= $promotionStats['net_movement'] >= 0 ? 'bg-green-100' : 'bg-red-100' ?> rounded-full flex items-center justify-center">
                                <svg class="w-5 h-5 <?= $promotionStats['net_movement'] >= 0 ? 'text-green-600' : 'text-red-600' ?>" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="<?= $promotionStats['net_movement'] >= 0 ? 'M13 7h8m0 0v8m0-8l-8 8-4-4-6 6' : 'M13 17h8m0 0v-8m0 8l-8-8-4 4-6-6' ?>"></path>
                                </svg>
                            </div>
                        </div>
                        <div class="ml-4">
                            <h3 class="text-sm font-medium text-gray-500">Net Movement</h3>
                            <p class="text-2xl font-bold <?= $promotionStats['net_movement'] >= 0 ? 'text-green-600' : 'text-red-600' ?>"><?= $promotionStats['net_movement'] >= 0 ? '+' : '' ?><?= $promotionStats['net_movement'] ?></p>
                        </div>
                    </div>
                </div>
            </div>
            
            <!-- Promotion History -->
            <?php if (!empty($promotionStats['promotion_history'])): ?>
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6">
                    <div class="flex justify-between items-center">
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Promotion History</h3>
                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                            <?= count($promotionStats['promotion_history']) ?> records
                        </span>
                    </div>
                    <p class="mt-1 max-w-2xl text-sm text-gray-500">Complete promotion history for this class</p>
                </div>
                <div class="border-t border-gray-200">
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
                                        Action
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
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach (array_slice($promotionStats['promotion_history'], 0, 15) as $record): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= date('M j, Y', strtotime($record['promotion_date'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($record['first_name'] . ' ' . $record['last_name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <?php if ($record['from_class_id'] == $class['id']): ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-red-100 text-red-800">
                                            Promoted Out
                                        </span>
                                        <?php else: ?>
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            Promoted In
                                        </span>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($record['from_class_name'] ?? 'Unknown') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($record['to_class_name'] ?? 'Unknown') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($record['academic_year_name'] ?? 'Unknown') ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        
                        <?php if (count($promotionStats['promotion_history']) > 15): ?>
                        <div class="px-6 py-3 bg-gray-50 text-center">
                            <p class="text-sm text-gray-500">
                                Showing latest 15 records. <?= count($promotionStats['promotion_history']) - 15 ?> more promotion records available.
                            </p>
                        </div>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
            <?php else: ?>
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-gray-900">No Promotion Records</h3>
                <p class="mt-1 text-sm text-gray-500">No promotion history found for this class.</p>
            </div>
            <?php endif; ?>
            
            <?php else: ?>
            <div class="bg-white shadow rounded-lg p-8 text-center">
                <div class="text-gray-500">
                    <p>Promotion data not available</p>
                    <p class="text-sm mt-2">No promotion records found for this class.</p>
                </div>
            </div>
            <?php endif; ?>
        </div>

        <!-- Students Section (always visible) -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
            <div class="px-4 py-5 sm:px-6 cursor-pointer collapsible-header" data-target="students-list">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Students</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Students enrolled in this class</p>
                    </div>
                    <div class="flex items-center space-x-2">
                        <span class="inline-flex items-center px-3 py-1 rounded-full text-sm font-medium bg-blue-100 text-blue-800">
                            <?= count($students) ?> students
                        </span>
                        <svg class="h-5 w-5 text-gray-500 transform transition-transform duration-200 rotate-180 collapsible-icon" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200 collapsible-content" id="students-list">
                <?php if (empty($students)): ?>
                <div class="px-4 py-5 sm:px-6 text-center text-gray-500">
                    No students enrolled in this class.
                </div>
                <?php else: ?>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Admission No
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Name
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Gender
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($student['admission_no']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars(ucfirst($student['gender'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                    <button onclick="openStudentProfileModal(<?= $student['id'] ?>)" class="text-indigo-600 hover:text-indigo-900">
                                        View Profile
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                
                <!-- Students Pagination -->
                <?php if (isset($studentPagination) && $studentPagination['total_pages'] > 1): ?>
                <div class="px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <?php if ($studentPagination['current_page'] > 1): ?>
                        <a href="?student_page=<?= $studentPagination['current_page'] - 1 ?>&student_per_page=<?= $studentPagination['per_page'] ?>" 
                           class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                        <?php endif; ?>
                        
                        <?php if ($studentPagination['current_page'] < $studentPagination['total_pages']): ?>
                        <a href="?student_page=<?= $studentPagination['current_page'] + 1 ?>&student_per_page=<?= $studentPagination['per_page'] ?>" 
                           class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </a>
                        <?php endif; ?>
                    </div>
                    <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                        <div>
                            <p class="text-sm text-gray-700">
                                Showing
                                <span class="font-medium"><?= (($studentPagination['current_page'] - 1) * $studentPagination['per_page']) + 1 ?></span>
                                to
                                <span class="font-medium"><?= min($studentPagination['current_page'] * $studentPagination['per_page'], $studentPagination['total']) ?></span>
                                of
                                <span class="font-medium"><?= $studentPagination['total'] ?></span>
                                results
                            </p>
                        </div>
                        <div class="flex items-center space-x-2">
                            <select onchange="window.location.href='?student_page=1&student_per_page=' + this.value" 
                                    class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                                <option value="10" <?= ($studentPagination['per_page'] ?? 10) == 10 ? 'selected' : '' ?>>10 per page</option>
                                <option value="25" <?= ($studentPagination['per_page'] ?? 10) == 25 ? 'selected' : '' ?>>25 per page</option>
                                <option value="50" <?= ($studentPagination['per_page'] ?? 10) == 50 ? 'selected' : '' ?>>50 per page</option>
                                <option value="100" <?= ($studentPagination['per_page'] ?? 10) == 100 ? 'selected' : '' ?>>100 per page</option>
                            </select>
                            <div class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px">
                                <?php if ($studentPagination['current_page'] > 1): ?>
                                <a href="?student_page=1&student_per_page=<?= $studentPagination['per_page'] ?>" 
                                   class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span>First</span>
                                </a>
                                <a href="?student_page=<?= $studentPagination['current_page'] - 1 ?>&student_per_page=<?= $studentPagination['per_page'] ?>" 
                                   class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <?php endif; ?>
                                
                                <?php 
                                $startPage = max(1, $studentPagination['current_page'] - 2);
                                $endPage = min($studentPagination['total_pages'], $studentPagination['current_page'] + 2);
                                
                                if ($startPage === 1) {
                                    $endPage = min(5, $studentPagination['total_pages']);
                                } elseif ($endPage === $studentPagination['total_pages']) {
                                    $startPage = max(1, $studentPagination['total_pages'] - 4);
                                }
                                
                                for ($i = $startPage; $i <= $endPage; $i++): ?>
                                <a href="?student_page=<?= $i ?>&student_per_page=<?= $studentPagination['per_page'] ?>" 
                                   class="relative inline-flex items-center px-4 py-2 border <?= $i == $studentPagination['current_page'] ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' ?> text-sm font-medium">
                                    <?= $i ?>
                                </a>
                                <?php endfor; ?>
                                
                                <?php if ($studentPagination['current_page'] < $studentPagination['total_pages']): ?>
                                <a href="?student_page=<?= $studentPagination['current_page'] + 1 ?>&student_per_page=<?= $studentPagination['per_page'] ?>" 
                                   class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="?student_page=<?= $studentPagination['total_pages'] ?>&student_per_page=<?= $studentPagination['per_page'] ?>" 
                                   class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span>Last</span>
                                </a>
                                <?php endif; ?>
                            </div>
                        </div>
                    </div>
                </div>
                <?php endif; ?>
                
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<!-- Make financial data available to JavaScript -->
<script>
// Pass financial defaulters data to JavaScript
const financialDefaulters = <?php echo json_encode(isset($financialStats['fee_defaulters']) ? $financialStats['fee_defaulters'] : []); ?>;
</script>

<!-- Student Profile Modal -->
<div id="studentProfileModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="student-profile-title">
                                Student Profile
                            </h3>
                            <button onclick="closeStudentProfileModal()" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <div id="student-profile-content">
                            <!-- Loading spinner -->
                            <div class="flex justify-center items-center py-12">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button onclick="closeStudentProfileModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
                <button id="edit-student-btn" type="button" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Edit Student
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Exam Results Modal -->
<div id="examResultsModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="exam-results-title">
                                Exam Results
                            </h3>
                            <button onclick="closeExamResultsModal()" class="text-gray-400 hover:text-gray-500">
                                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        
                        <div id="exam-results-content">
                            <!-- Loading spinner -->
                            <div class="flex justify-center items-center py-12">
                                <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button onclick="closeExamResultsModal()" type="button" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
function showTab(tabName) {
    // Hide all tab contents
    document.querySelectorAll('.tab-content').forEach(content => {
        content.classList.add('hidden');
    });
    
    // Remove active class from all tabs
    document.querySelectorAll('.tab-button').forEach(button => {
        button.classList.remove('border-indigo-500', 'text-indigo-600');
        button.classList.add('border-transparent', 'text-gray-500');
    });
    
    // Show selected tab content
    document.getElementById('tab-content-' + tabName).classList.remove('hidden');
    
    // Add active class to selected tab
    const activeTab = document.getElementById('tab-' + tabName);
    activeTab.classList.remove('border-transparent', 'text-gray-500');
    activeTab.classList.add('border-indigo-500', 'text-indigo-600');
}

// Initialize first tab as active
document.addEventListener('DOMContentLoaded', function() {
    showTab('academic');
});

// Student Profile Modal Functions
let currentStudentId = null;
let showFinancialInfo = false;

function openStudentProfileModal(studentId, isFeeDefaulter = false) {
    currentStudentId = studentId;
    showFinancialInfo = isFeeDefaulter;
    const modal = document.getElementById('studentProfileModal');
    const content = document.getElementById('student-profile-content');
    
    // Show loading state
    content.innerHTML = `
        <div class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        </div>
    `;
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Load student data
    loadStudentProfileData(studentId);
}

function closeStudentProfileModal() {
    const modal = document.getElementById('studentProfileModal');
    modal.classList.add('hidden');
    currentStudentId = null;
    showFinancialInfo = false; // Reset the financial info flag
}

function loadStudentProfileData(studentId) {
    fetch(`/students/${studentId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayStudentProfile(data.student);
            } else {
                displayError('Failed to load student profile');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            displayError('Error loading student profile');
        });
}

function displayStudentProfile(student) {
    const content = document.getElementById('student-profile-content');
    const editButton = document.getElementById('edit-student-btn');
    
    // Set edit button URL
    editButton.onclick = () => {
        window.location.href = `/students/${student.id}/edit`;
    };
    
    // Define student category labels
    const studentCategoryLabels = {
        'regular_day': 'Regular Student (Day)',
        'regular_boarding': 'Regular Student (Boarding)',
        'international': 'International Student'
    };
    
    // Check if this student is a fee defaulter and get their financial info
    let financialInfo = null;
    if (showFinancialInfo && financialDefaulters) {
        financialInfo = financialDefaulters.find(defaulter => 
            defaulter.student_info && defaulter.student_info.id == student.id
        );
    }
    
    // Build profile HTML
    let profileHTML = `
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <!-- Profile Header -->
            <div class="px-4 py-5 sm:px-6">
                <div class="flex items-center">
                    ${student.profile_picture ? 
                        `<img src="/storage/uploads/${student.profile_picture}" alt="Profile Picture" class="h-16 w-16 object-cover rounded-full mr-4" onerror="this.src='/images/default-profile.png';">` :
                        `<div class="h-16 w-16 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                            <span class="text-gray-500 text-2xl">${student.first_name.charAt(0)}</span>
                        </div>`
                    }
                    <div>
                        <h3 class="text-2xl font-bold text-gray-900">
                            ${student.first_name} ${student.last_name}
                        </h3>
                        <p class="text-gray-500">Admission #: ${student.admission_no}</p>
                        <p class="text-gray-500">Class: ${student.class_name || 'N/A'}</p>
                    </div>
                </div>
            </div>
            
            <!-- Profile Details -->
            <div class="border-t border-gray-200">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Gender</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${student.gender ? student.gender.charAt(0).toUpperCase() + student.gender.slice(1) : 'N/A'}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Date of Birth</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${student.dob || 'N/A'}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Student Category</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${studentCategoryLabels[student.student_category] || 'Regular Student (Day)'}</dd>
                    </div>
                    ${student.student_category_details ? `
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Category Details</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${student.student_category_details}</dd>
                    </div>` : ''}
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Guardian Name</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${student.guardian_name || 'N/A'}</dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Guardian Phone</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${student.guardian_phone || 'N/A'}</dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Address</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${student.address || 'N/A'}</dd>
                    </div>
                    ${student.medical_info ? `
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">Medical Info</dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">${student.medical_info}</dd>
                    </div>` : ''}
                </dl>
            </div>
            
            ${financialInfo ? `
            <!-- Financial Information Section -->
            <div class="border-t border-gray-200 mt-6">
                <div class="px-4 py-5 sm:px-6">
                    <h3 class="text-lg font-medium text-gray-900 mb-4">Financial Information</h3>
                    <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mb-6">
                        <div class="bg-blue-50 p-4 rounded-lg">
                            <p class="text-sm text-blue-600">Total Billed</p>
                            <p class="text-xl font-bold text-blue-900">₵${financialInfo.total_billed ? financialInfo.total_billed.toFixed(2) : '0.00'}</p>
                        </div>
                        <div class="bg-green-50 p-4 rounded-lg">
                            <p class="text-sm text-green-600">Total Paid</p>
                            <p class="text-xl font-bold text-green-900">₵${financialInfo.total_paid ? financialInfo.total_paid.toFixed(2) : '0.00'}</p>
                        </div>
                        <div class="bg-red-50 p-4 rounded-lg">
                            <p class="text-sm text-red-600">Outstanding Balance</p>
                            <p class="text-xl font-bold text-red-900">₵${financialInfo.balance ? financialInfo.balance.toFixed(2) : '0.00'}</p>
                        </div>
                    </div>
                    <div class="text-center">
                        <p class="text-sm text-gray-500">This student appears in the fee defaulters list for this class</p>
                    </div>
                </div>
            </div>` : ''}
        </div>
    `;
    
    content.innerHTML = profileHTML;
}

function displayError(message) {
    const content = document.getElementById('student-profile-content');
    content.innerHTML = `
        <div class="bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Error</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>${message}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Close modal when clicking outside
document.getElementById('studentProfileModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeStudentProfileModal();
    }
});

// Class Information Collapsible Functionality
document.addEventListener('DOMContentLoaded', function() {
    const classInfoHeader = document.getElementById('class-info-header');
    const classInfoDetails = document.getElementById('class-info-details');
    const classExpandIcon = document.getElementById('class-expand-icon');
    
    // Check for saved state in cookie
    const isCollapsed = document.cookie.split(';').some((item) => item.trim().startsWith('class_info_collapsed=true'));
    
    if (isCollapsed) {
        // Start collapsed
        classInfoDetails.classList.add('hidden');
        classExpandIcon.classList.remove('rotate-180');
    }
    
    // Toggle functionality
    classInfoHeader.addEventListener('click', function() {
        const isCurrentlyCollapsed = classInfoDetails.classList.contains('hidden');
        
        if (isCurrentlyCollapsed) {
            // Expand
            classInfoDetails.classList.remove('hidden');
            classExpandIcon.classList.add('rotate-180');
        } else {
            // Collapse
            classInfoDetails.classList.add('hidden');
            classExpandIcon.classList.remove('rotate-180');
        }
        
        // Save state in cookie
        const newState = !isCurrentlyCollapsed;
        document.cookie = `class_info_collapsed=${newState}; path=/`;
    });
});

// Exam Results Modal Functions
function openExamResultsModal(examId) {
    const modal = document.getElementById('examResultsModal');
    const content = document.getElementById('exam-results-content');
    const title = document.getElementById('exam-results-title');
    
    // Show loading state
    content.innerHTML = `
        <div class="flex justify-center items-center py-12">
            <div class="animate-spin rounded-full h-12 w-12 border-b-2 border-indigo-600"></div>
        </div>
    `;
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Load exam results data
    loadExamResultsData(examId);
}

function closeExamResultsModal() {
    const modal = document.getElementById('examResultsModal');
    modal.classList.add('hidden');
}

function loadExamResultsData(examId) {
    fetch(`/exam_results/by-exam/${examId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                displayExamResults(data.exam, data.results);
            } else {
                displayExamResultsError('Failed to load exam results');
            }
        })
        .catch(error => {
            console.error('Error:', error);
            displayExamResultsError('Error loading exam results');
        });
}

function displayExamResults(exam, rankedResults) {
    const content = document.getElementById('exam-results-content');
    const title = document.getElementById('exam-results-title');
    
    // Update title
    title.textContent = `${exam.name} Results (Ranked)`;
    
    // Add print button to modal header if not already present
    // First remove any existing print button to avoid duplicates
    const existingPrintBtn = document.getElementById('print-results-btn');
    if (existingPrintBtn) existingPrintBtn.remove();
    
    // Create new print button
    const printBtn = document.createElement('button');
    printBtn.id = 'print-results-btn';
    printBtn.innerHTML = `
        <svg class="h-5 w-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z"></path>
        </svg>
        Print / Export
    `;
    printBtn.className = 'ml-auto mr-4 inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500';
    printBtn.onclick = () => printExamResults(exam, rankedResults);
    
    // Insert after title
    title.parentNode.insertBefore(printBtn, title.nextSibling);
    
    if (!rankedResults || rankedResults.length === 0) {
        content.innerHTML = `
            <div class="bg-yellow-50 border border-yellow-200 rounded-md p-6 text-center">
                <svg class="mx-auto h-12 w-12 text-yellow-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path>
                </svg>
                <h3 class="mt-2 text-lg font-medium text-yellow-800">No Results Found</h3>
                <p class="mt-1 text-sm text-yellow-700">No exam results have been recorded for this exam yet.</p>
            </div>
        `;
        return;
    }
    
    // Build results table
    let tableHTML = `
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">${exam.name}</h3>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">${rankedResults.length} students have results recorded</p>
                    </div>
                    <div class="text-right text-sm text-gray-500">
                        <p><span class="font-medium text-gray-700">Academic Year:</span> ${exam.academic_year_name || 'N/A'}</p>
                        <p><span class="font-medium text-gray-700">Term:</span> ${exam.term || 'N/A'}</p>
                        <p><span class="font-medium text-gray-700">Class:</span> ${exam.class_name || 'N/A'}</p>
                        <p><span class="font-medium text-gray-700">Date:</span> ${exam.date ? new Date(exam.date).toLocaleDateString() : 'N/A'}</p>
                    </div>
                </div>
            </div>
            <div class="border-t border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Rank
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Student
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Details
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subjects
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Average
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
    `;
    
    rankedResults.forEach(studentData => {
        const student = studentData.student_info;
        
        // Build subjects grid/list
        let subjectsHTML = '<div class="flex flex-wrap gap-1">';
        studentData.results.forEach(res => {
            let gradeColor = 'bg-gray-100 text-gray-800';
            if (res.grade) {
                const grade = res.grade.toUpperCase();
                if (grade.startsWith('A')) gradeColor = 'bg-green-100 text-green-800';
                else if (grade.startsWith('B')) gradeColor = 'bg-blue-100 text-blue-800';
                else if (grade.startsWith('C')) gradeColor = 'bg-yellow-100 text-yellow-800';
                else if (grade.startsWith('D')) gradeColor = 'bg-orange-100 text-orange-800';
                else if (grade === 'F') gradeColor = 'bg-red-100 text-red-800';
            }
            subjectsHTML += `
                <span title="${res.subject_name}: ${res.marks} (${res.grade})" 
                      class="inline-flex items-center px-2 py-0.5 rounded text-xs font-medium ${gradeColor} border border-gray-200 cursor-help">
                    ${res.subject_name.substring(0, 3)}: ${res.marks}
                </span>
            `;
        });
        subjectsHTML += '</div>';

        const rankClass = studentData.rank <= 3 ? 'font-bold text-indigo-700 text-lg' : 'text-gray-900';
        const trophy = studentData.rank === 1 ? '🥇' : (studentData.rank === 2 ? '🥈' : (studentData.rank === 3 ? '🥉' : ''));

        tableHTML += `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm ${rankClass}">
                    ${trophy} ${studentData.rank}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    ${student.first_name} ${student.last_name}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                    ${student.admission_no}
                </td>
                <td class="px-6 py-4 text-sm text-gray-500">
                    ${subjectsHTML}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900">
                    ${parseFloat(studentData.total_marks).toFixed(1)}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    ${studentData.average}
                </td>
            </tr>
        `;
    });
    
    tableHTML += `
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    `;
    
    content.innerHTML = tableHTML;
}

function displayExamResultsError(message) {
    const content = document.getElementById('exam-results-content');
    content.innerHTML = `
        <div class="bg-red-50 border border-red-200 rounded-md p-4">
            <div class="flex">
                <div class="flex-shrink-0">
                    <svg class="h-5 w-5 text-red-400" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zM8.707 7.293a1 1 0 00-1.414 1.414L8.586 10l-1.293 1.293a1 1 0 101.414 1.414L10 11.414l1.293 1.293a1 1 0 001.414-1.414L11.414 10l1.293-1.293a1 1 0 00-1.414-1.414L10 8.586 8.707 7.293z" clip-rule="evenodd" />
                    </svg>
                </div>
                <div class="ml-3">
                    <h3 class="text-sm font-medium text-red-800">Error</h3>
                    <div class="mt-2 text-sm text-red-700">
                        <p>${message}</p>
                    </div>
                </div>
            </div>
        </div>
    `;
}

// Close exam results modal when clicking outside
document.getElementById('examResultsModal').addEventListener('click', function(e) {
    if (e.target === this) {
        closeExamResultsModal();
    }
});

// Collapsible Sections Functionality
document.addEventListener('DOMContentLoaded', function() {
    // Add event listeners to all collapsible headers
    document.querySelectorAll('.collapsible-header').forEach(header => {
        header.addEventListener('click', function() {
            const targetId = this.getAttribute('data-target');
            const content = document.getElementById(targetId);
            const icon = this.querySelector('.collapsible-icon');
            
            if (content && icon) {
                const isCollapsed = content.classList.contains('hidden');
                
                if (isCollapsed) {
                    // Expand
                    content.classList.remove('hidden');
                    icon.classList.add('rotate-180');
                } else {
                    // Collapse
                    content.classList.add('hidden');
                    icon.classList.remove('rotate-180');
                }
            }
        });
    });
});

// Toggle all sections function
function toggleAllSections(expand) {
    document.querySelectorAll('.collapsible-content').forEach(content => {
        const header = document.querySelector(`[data-target="${content.id}"]`);
        const icon = header ? header.querySelector('.collapsible-icon') : null;
        
        if (expand) {
            content.classList.remove('hidden');
            if (icon) icon.classList.add('rotate-180');
        } else {
            content.classList.add('hidden');
            if (icon) icon.classList.remove('rotate-180');
        }
    });
}

// Initialize collapsible sections with default state
// By default, keep Academic Performance tab sections expanded, others collapsed
document.addEventListener('DOMContentLoaded', function() {
    // Collapse all sections except the first one in Academic Performance tab
    setTimeout(() => {
        const academicTabContent = document.getElementById('tab-content-academic');
        if (academicTabContent) {
            const collapsibleContents = academicTabContent.querySelectorAll('.collapsible-content');
            collapsibleContents.forEach((content, index) => {
                // Keep first section (term performance) expanded, collapse others
                if (index > 0) {
                    content.classList.add('hidden');
                    const header = document.querySelector(`[data-target="${content.id}"]`);
                    const icon = header ? header.querySelector('.collapsible-icon') : null;
                    if (icon) icon.classList.remove('rotate-180');
                }
            });
        }
    }, 100);
});
// Function to print exam results
function printExamResults(exam, rankedResults) {
    const printWindow = window.open('', '_blank');
    
    // Build the print content
    let html = `
        <!DOCTYPE html>
        <html>
        <head>
            <title>${exam.name} - Results</title>
            <style>
                body { font-family: Arial, sans-serif; margin: 20px; }
                .header { text-align: center; margin-bottom: 30px; border-bottom: 2px solid #333; padding-bottom: 20px; }
                .header h1 { margin: 0; font-size: 24px; color: #333; }
                .header h2 { margin: 5px 0; font-size: 18px; color: #666; }
                .meta-info { display: flex; justify-content: space-between; margin-bottom: 20px; font-size: 14px; }
                .meta-item { font-weight: bold; }
                table { width: 100%; border-collapse: collapse; margin-top: 10px; }
                th, td { border: 1px solid #ccc; padding: 10px; text-align: left; }
                th { background-color: #f2f2f2; font-weight: bold; }
                .grade-tag { display: inline-block; padding: 2px 6px; border-radius: 4px; border: 1px solid #ddd; font-size: 11px; margin-right: 2px; margin-bottom: 2px; }
                .rank-1 { background-color: #fff9c4; }
                .rank-2 { background-color: #f5f5f5; }
                .rank-3 { background-color: #ffe0b2; }
                .footer { margin-top: 50px; text-align: center; font-size: 12px; color: #999; border-top: 1px solid #ccc; padding-top: 10px; }
                
                @media print {
                    @page { margin: 2cm; }
                    body { -webkit-print-color-adjust: exact; }
                }
            </style>
        </head>
        <body>
            <div class="header">
                <h1>${exam.name}</h1>
                <h2>Ranked Exam Report</h2>
            </div>
            
            <div class="meta-info">
                <div>
                    <div class="meta-item">Class: <span style="font-weight: normal;">${exam.class_name || 'N/A'}</span></div>
                    <div class="meta-item">Academic Year: <span style="font-weight: normal;">${exam.academic_year_name || 'N/A'}</span></div>
                </div>
                <div style="text-align: right;">
                     <div class="meta-item">Term: <span style="font-weight: normal;">${exam.term || 'N/A'}</span></div>
                     <div class="meta-item">Date: <span style="font-weight: normal;">${exam.date ? new Date(exam.date).toLocaleDateString() : 'N/A'}</span></div>
                </div>
            </div>
            
            <table>
                <thead>
                    <tr>
                        <th style="width: 50px; text-align: center;">Rank</th>
                        <th>Student</th>
                        <th>Admiral No</th>
                        <th>Subjects</th>
                        <th style="text-align: center;">Total</th>
                        <th style="text-align: center;">Average</th>
                    </tr>
                </thead>
                <tbody>
    `;
    
    rankedResults.forEach(data => {
        let rankClass = '';
        if (data.rank === 1) rankClass = 'rank-1';
        else if (data.rank === 2) rankClass = 'rank-2';
        else if (data.rank === 3) rankClass = 'rank-3';
        
        let subjectsHTML = '';
        data.results.forEach(res => {
            subjectsHTML += `<span class="grade-tag">${res.subject_name.substring(0, 3)}: ${res.marks} (${res.grade})</span>`;
        });
        
        html += `
            <tr class="${rankClass}">
                <td style="text-align: center; font-weight: bold;">${data.rank}</td>
                <td style="font-weight: bold;">${data.student_info.first_name} ${data.student_info.last_name}</td>
                <td>${data.student_info.admission_no}</td>
                <td>${subjectsHTML}</td>
                <td style="text-align: center; font-weight: bold;">${parseFloat(data.total_marks).toFixed(1)}</td>
                <td style="text-align: center;">${data.average}</td>
            </tr>
        `;
    });
    
    html += `
                </tbody>
            </table>
            
            <div class="footer">
                <p>Report generated on ${new Date().toLocaleString()}</p>
            </div>
        </body>
        </html>
    `;
    
    printWindow.document.write(html);
    printWindow.document.close();
    
    // Wait for content to load before printing
    setTimeout(() => {
        printWindow.focus();
        printWindow.print();
        // Optional: printWindow.close();
    }, 500);
}
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>