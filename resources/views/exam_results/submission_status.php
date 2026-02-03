<?php 
$title = 'Exam Submission Status'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Exam Submission Status</h1>
        </div>

        <!-- Filter Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Filter Options</h3>
                <p class="mt-1 text-sm text-gray-500">Select criteria to view submission status</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <form method="GET" action="/exam_results/submission-status" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-4">
                    <div>
                        <label for="academic_year_id" class="block text-sm font-medium text-gray-700">Academic Year</label>
                        <select name="academic_year_id" id="academic_year_id" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Academic Years</option>
                            <?php foreach ($academic_years as $year): ?>
                                <option value="<?= $year['id'] ?>" <?= (isset($academicYearId) && $academicYearId == $year['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($year['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                        <select name="class_id" id="class_id"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Classes</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>" <?= (isset($classId) && $classId == $class['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($class['name'] . ' ' . $class['level']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                        <select name="subject_id" id="subject_id"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Subjects</option>
                            <?php foreach ($subjects as $subject): ?>
                                <option value="<?= $subject['id'] ?>" <?= (isset($subjectId) && $subjectId == $subject['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($subject['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="term" class="block text-sm font-medium text-gray-700">Term</label>
                        <select name="term" id="term"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Terms</option>
                            <?php foreach ($terms as $t): ?>
                                <option value="<?= $t ?>" <?= (isset($term) && $term == $t) ? 'selected' : '' ?>>
                                    <?= ucfirst(htmlspecialchars($t)) ?> Term
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                </form>
            </div>
        </div>
        
        <!-- Search and Pagination Controls -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex flex-col sm:flex-row justify-between items-start sm:items-center space-y-4 sm:space-y-0 sm:space-x-4">
                    <!-- Search Form -->
                    <form method="GET" class="flex-grow max-w-md">
                        <!-- Preserve existing filter parameters -->
                        <?php if (isset($classId)): ?>
                            <input type="hidden" name="class_id" value="<?= htmlspecialchars($classId) ?>">
                        <?php endif; ?>
                        <?php if (isset($subjectId)): ?>
                            <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subjectId) ?>">
                        <?php endif; ?>
                        <?php if (isset($term)): ?>
                            <input type="hidden" name="term" value="<?= htmlspecialchars($term) ?>">
                        <?php endif; ?>
                        <?php if (isset($academicYearId)): ?>
                            <input type="hidden" name="academic_year_id" value="<?= htmlspecialchars($academicYearId) ?>">
                        <?php endif; ?>
                        
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search exams or classes..." class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md">
                            <div class="absolute inset-y-0 right-0 flex items-center">
                                <button type="submit" class="h-full px-3 bg-indigo-600 text-white rounded-r-md hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    Search
                                </button>
                            </div>
                        </div>
                    </form>
                    
                    <!-- Per Page Selector -->
                    <form method="GET" class="flex items-center space-x-2">
                        <!-- Preserve existing parameters -->
                        <?php if (isset($classId)): ?>
                            <input type="hidden" name="class_id" value="<?= htmlspecialchars($classId) ?>">
                        <?php endif; ?>
                        <?php if (isset($subjectId)): ?>
                            <input type="hidden" name="subject_id" value="<?= htmlspecialchars($subjectId) ?>">
                        <?php endif; ?>
                        <?php if (isset($term)): ?>
                            <input type="hidden" name="term" value="<?= htmlspecialchars($term) ?>">
                        <?php endif; ?>
                        <?php if (isset($academicYearId)): ?>
                            <input type="hidden" name="academic_year_id" value="<?= htmlspecialchars($academicYearId) ?>">
                        <?php endif; ?>
                        <?php if (isset($search)): ?>
                            <input type="hidden" name="search" value="<?= htmlspecialchars($search) ?>">
                        <?php endif; ?>
                        
                        <label for="per_page" class="text-sm font-medium text-gray-700">Per Page:</label>
                        <select name="per_page" id="per_page" onchange="this.form.submit()" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                            <option value="10" <?= ($per_page ?? 10) == 10 ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= ($per_page ?? 10) == 25 ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= ($per_page ?? 10) == 50 ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= ($per_page ?? 10) == 100 ? 'selected' : '' ?>>100</option>
                        </select>
                    </form>
                </div>
            </div>
        </div>

        <?php if (isset($examStats)): ?>
        <!-- Summary Statistics -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Overall Submission Summary</h3>
                <div class="mt-4 grid grid-cols-1 gap-5 sm:grid-cols-4">
                    <div class="bg-blue-50 overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-blue-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Total Exams</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900"><?= count($examStats) ?></div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <?php 
                    // Calculate overall statistics
                    $totalStudentsOverall = 0;
                    $totalSubmittedOverall = 0;
                    $totalUnsubmittedOverall = 0;
                    
                    foreach ($examStats as $stat) {
                        $totalStudentsOverall += $stat['totalStudents'];
                        $totalSubmittedOverall += $stat['submittedCount'];
                        $totalUnsubmittedOverall += $stat['unsubmittedCount'];
                    }
                    
                    $completionRateOverall = $totalStudentsOverall > 0 ? round(($totalSubmittedOverall / $totalStudentsOverall) * 100, 1) : 0;
                    ?>
                    
                    <div class="bg-green-50 overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-green-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Submitted Results</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900"><?= $totalSubmittedOverall ?></div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-red-50 overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-red-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Pending Results</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900"><?= $totalUnsubmittedOverall ?></div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="bg-indigo-50 overflow-hidden shadow rounded-lg">
                        <div class="px-4 py-5 sm:p-6">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 bg-indigo-500 rounded-md p-3">
                                    <svg class="h-6 w-6 text-white" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                                    </svg>
                                </div>
                                <div class="ml-5 w-0 flex-1">
                                    <dl>
                                        <dt class="text-sm font-medium text-gray-500 truncate">Completion Rate</dt>
                                        <dd class="flex items-baseline">
                                            <div class="text-2xl font-semibold text-gray-900"><?= $completionRateOverall ?>%</div>
                                        </dd>
                                    </dl>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Exams List with Submission Status -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Exams Submission Status</h3>
                <p class="mt-1 text-sm text-gray-500">Showing submission status for all exams</p>
            </div>
            <div class="border-t border-gray-200">
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Exam
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Class
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subject
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Term
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Date
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Total Students
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Submitted
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Pending
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Completion
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Actions
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php if (empty($examStats)): ?>
                                <tr>
                                    <td colspan="10" class="px-6 py-4 text-center text-sm text-gray-500">
                                        No exams found matching your criteria.
                                    </td>
                                </tr>
                            <?php else: ?>
                                <?php foreach ($examStats as $stat): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                            <?= htmlspecialchars($stat['exam']['name'] ?? 'N/A') ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars(($stat['class']['name'] ?? 'N/A') . ' ' . ($stat['class']['level'] ?? '')) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars($stat['subject']['name'] ?? 'All Subjects') ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= ucfirst(htmlspecialchars($stat['exam']['term'] ?? 'N/A')) ?> Term
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= !empty($stat['exam']['date']) ? htmlspecialchars(date('M j, Y', strtotime($stat['exam']['date']))) : 'N/A' ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= $stat['totalStudents'] ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="text-green-600 font-medium"><?= $stat['submittedCount'] ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <span class="text-red-600 font-medium"><?= $stat['unsubmittedCount'] ?></span>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap">
                                            <div class="flex items-center">
                                                <div class="w-24 bg-gray-200 rounded-full h-2 mr-2">
                                                    <div class="bg-green-600 h-2 rounded-full" style="width: <?= $stat['completionPercentage'] ?>%"></div>
                                                </div>
                                                <span class="text-sm text-gray-500"><?= $stat['completionPercentage'] ?>%</span>
                                            </div>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                            <a href="/exam_results/submission-status/details?exam_id=<?= $stat['exam']['id'] ?>&subject_id=<?= $stat['subject']['id'] ?? '' ?>" 
                                               class="text-indigo-600 hover:text-indigo-900 mr-3 view-details-btn">
                                                View Details
                                            </a>
                                            <a href="/exam_results/submission-status/details?exam_id=<?= $stat['exam']['id'] ?>&subject_id=<?= $stat['subject']['id'] ?? '' ?>" 
                                               class="text-indigo-600 hover:text-indigo-900 view-results-btn">
                                                Results
                                            </a>
                                        </td>
                                    </tr>
                                <?php endforeach; ?>
                            <?php endif; ?>
                        </tbody>
                    </table>
                </div>
            </div>
            
            <!-- Pagination Controls -->
            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
            <div class="px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?php if ($pagination['page'] > 1): ?>
                        <a href="?page=<?= $pagination['page'] - 1 ?>&per_page=<?= $pagination['per_page'] ?><?php if (isset($search)): ?>&search=<?= urlencode($search) ?><?php endif; ?><?php if (isset($classId)): ?>&class_id=<?= $classId ?><?php endif; ?><?php if (isset($subjectId)): ?>&subject_id=<?= $subjectId ?><?php endif; ?><?php if (isset($term)): ?>&term=<?= $term ?><?php endif; ?><?php if (isset($academicYearId)): ?>&academic_year_id=<?= $academicYearId ?><?php endif; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Previous
                        </a>
                    <?php else: ?>
                        <span class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-gray-100 cursor-not-allowed">
                            Previous
                        </span>
                    <?php endif; ?>
                    
                    <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                        <a href="?page=<?= $pagination['page'] + 1 ?>&per_page=<?= $pagination['per_page'] ?><?php if (isset($search)): ?>&search=<?= urlencode($search) ?><?php endif; ?><?php if (isset($classId)): ?>&class_id=<?= $classId ?><?php endif; ?><?php if (isset($subjectId)): ?>&subject_id=<?= $subjectId ?><?php endif; ?><?php if (isset($term)): ?>&term=<?= $term ?><?php endif; ?><?php if (isset($academicYearId)): ?>&academic_year_id=<?= $academicYearId ?><?php endif; ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Next
                        </a>
                    <?php else: ?>
                        <span class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-300 bg-gray-100 cursor-not-allowed">
                            Next
                        </span>
                    <?php endif; ?>
                </div>
                <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
                    <div>
                        <p class="text-sm text-gray-700">
                            Showing
                            <span class="font-medium"><?= (($pagination['page'] - 1) * $pagination['per_page']) + 1 ?></span>
                            to
                            <span class="font-medium"><?= min($pagination['page'] * $pagination['per_page'], $pagination['total']) ?></span>
                            of
                            <span class="font-medium"><?= $pagination['total'] ?></span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <?php if ($pagination['page'] > 1): ?>
                                <a href="?page=1&per_page=<?= $pagination['per_page'] ?><?php if (isset($search)): ?>&search=<?= urlencode($search) ?><?php endif; ?><?php if (isset($classId)): ?>&class_id=<?= $classId ?><?php endif; ?><?php if (isset($subjectId)): ?>&subject_id=<?= $subjectId ?><?php endif; ?><?php if (isset($term)): ?>&term=<?= $term ?><?php endif; ?><?php if (isset($academicYearId)): ?>&academic_year_id=<?= $academicYearId ?><?php endif; ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">First</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="?page=<?= $pagination['page'] - 1 ?>&per_page=<?= $pagination['per_page'] ?><?php if (isset($search)): ?>&search=<?= urlencode($search) ?><?php endif; ?><?php if (isset($classId)): ?>&class_id=<?= $classId ?><?php endif; ?><?php if (isset($subjectId)): ?>&subject_id=<?= $subjectId ?><?php endif; ?><?php if (isset($term)): ?>&term=<?= $term ?><?php endif; ?><?php if (isset($academicYearId)): ?>&academic_year_id=<?= $academicYearId ?><?php endif; ?>" class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            <?php else: ?>
                                <span class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                    <span class="sr-only">First</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M15.707 15.707a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 010 1.414zm-6 0a1 1 0 01-1.414 0l-5-5a1 1 0 010-1.414l5-5a1 1 0 011.414 1.414L5.414 10l4.293 4.293a1 1 0 010 1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <span class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                    <span class="sr-only">Previous</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            <?php endif; ?>
                            
                            <?php 
                            // Show page numbers
                            $startPage = max(1, $pagination['page'] - 2);
                            $endPage = min($pagination['total_pages'], $pagination['page'] + 2);
                            
                            for ($i = $startPage; $i <= $endPage; $i++):
                                if ($i == $pagination['page']):
                            ?>
                                <span class="relative inline-flex items-center px-4 py-2 border border-indigo-500 bg-indigo-50 text-sm font-medium text-indigo-600">
                                    <?= $i ?>
                                </span>
                            <?php else: ?>
                                <a href="?page=<?= $i ?>&per_page=<?= $pagination['per_page'] ?><?php if (isset($search)): ?>&search=<?= urlencode($search) ?><?php endif; ?><?php if (isset($classId)): ?>&class_id=<?= $classId ?><?php endif; ?><?php if (isset($subjectId)): ?>&subject_id=<?= $subjectId ?><?php endif; ?><?php if (isset($term)): ?>&term=<?= $term ?><?php endif; ?><?php if (isset($academicYearId)): ?>&academic_year_id=<?= $academicYearId ?><?php endif; ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <?= $i ?>
                                </a>
                            <?php 
                                endif;
                            endfor;
                            ?>
                            
                            <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                                <a href="?page=<?= $pagination['page'] + 1 ?>&per_page=<?= $pagination['per_page'] ?><?php if (isset($search)): ?>&search=<?= urlencode($search) ?><?php endif; ?><?php if (isset($classId)): ?>&class_id=<?= $classId ?><?php endif; ?><?php if (isset($subjectId)): ?>&subject_id=<?= $subjectId ?><?php endif; ?><?php if (isset($term)): ?>&term=<?= $term ?><?php endif; ?><?php if (isset($academicYearId)): ?>&academic_year_id=<?= $academicYearId ?><?php endif; ?>" class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                                <a href="?page=<?= $pagination['total_pages'] ?>&per_page=<?= $pagination['per_page'] ?><?php if (isset($search)): ?>&search=<?= urlencode($search) ?><?php endif; ?><?php if (isset($classId)): ?>&class_id=<?= $classId ?><?php endif; ?><?php if (isset($subjectId)): ?>&subject_id=<?= $subjectId ?><?php endif; ?><?php if (isset($term)): ?>&term=<?= $term ?><?php endif; ?><?php if (isset($academicYearId)): ?>&academic_year_id=<?= $academicYearId ?><?php endif; ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                    <span class="sr-only">Last</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L13.586 11H3a1 1 0 110-2h10.586l-3.293-3.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </a>
                            <?php else: ?>
                                <span class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                    <span class="sr-only">Next</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                                <span class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-gray-100 text-sm font-medium text-gray-400 cursor-not-allowed">
                                    <span class="sr-only">Last</span>
                                    <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M10.293 15.707a1 1 0 010-1.414L13.586 11H3a1 1 0 110-2h10.586l-3.293-3.293a1 1 0 111.414-1.414l5 5a1 1 0 010 1.414l-5 5a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                        <path fill-rule="evenodd" d="M4.293 4.293a1 1 0 011.414 0L10 8.586l4.293-4.293a1 1 0 111.414 1.414L11.414 10l4.293 4.293a1 1 0 01-1.414 1.414L10 11.414l-4.293 4.293a1 1 0 01-1.414-1.414L8.586 10 4.293 5.707a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </span>
                            <?php endif; ?>
                        </nav>
                    </div>
                </div>
            </div>
            <?php endif; ?>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Modal Container -->
<div id="details-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" id="modal-backdrop"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-4xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full" id="modal-content">
                        <!-- Content will be loaded here -->
                        <div class="flex justify-center items-center py-10">
                            <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                                <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                                <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                            </svg>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" id="close-modal-btn" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const modal = document.getElementById('details-modal');
    const modalBackdrop = document.getElementById('modal-backdrop');
    const modalContent = document.getElementById('modal-content');
    const closeModalBtn = document.getElementById('close-modal-btn');
    
    // Function to open modal
    function openModal(url) {
        modal.classList.remove('hidden');
        document.body.style.overflow = 'hidden'; // Prevent body scrolling
        
        // Show loading state
        modalContent.innerHTML = `
            <div class="flex justify-center items-center py-10">
                <svg class="animate-spin h-8 w-8 text-indigo-600" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24">
                    <circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle>
                    <path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path>
                </svg>
            </div>
        `;
        
        // Fetch content
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.text())
        .then(html => {
            modalContent.innerHTML = html;
        })
        .catch(error => {
            console.error('Error loading content:', error);
            modalContent.innerHTML = `
                <div class="text-center py-10 text-red-600">
                    <p>Failed to load content. Please try again.</p>
                </div>
            `;
        });
    }
    
    // Function to close modal
    function closeModal() {
        modal.classList.add('hidden');
        document.body.style.overflow = ''; // Restore body scrolling
    }
    
    // Event listeners for View Details buttons
    document.querySelectorAll('.view-details-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            openModal(this.href);
        });
    });

    // Event listeners for Results buttons
    document.querySelectorAll('.view-results-btn').forEach(btn => {
        btn.addEventListener('click', function(e) {
            e.preventDefault();
            openModal(this.href);
        });
    });
    
    // Close modal events
    closeModalBtn.addEventListener('click', closeModal);
    modalBackdrop.addEventListener('click', closeModal);
    
    // Close on escape key
    document.addEventListener('keydown', function(e) {
        if (e.key === 'Escape' && !modal.classList.contains('hidden')) {
            closeModal();
        }
    });
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>