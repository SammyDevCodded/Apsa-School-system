<?php 
$title = 'Students'; 
ob_start(); 

// Define student category labels
$studentCategoryLabels = [
    'regular_day' => 'Day',
    'regular_boarding' => 'Boarding',
    'international' => 'Intl'
];
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Students</h1>
            <div class="flex space-x-2">
                <a href="/students/export?<?= http_build_query(array_filter(array_merge($filters, ['search' => $searchTerm, 'print' => 1]))) ?>" 
                   target="_blank"
                   class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 flex items-center">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                    </svg>
                    Print
                </a>
                <a href="/students/export?<?= http_build_query(array_filter(array_merge($filters, ['search' => $searchTerm]))) ?>" 
                   class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 flex items-center">
                    <svg class="h-4 w-4 mr-2" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                    </svg>
                    Export CSV
                </a>
                <!-- Changed from link to button to open modal -->
                <button type="button" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700" id="openAddStudentModal">
                    Add Student
                </button>
            </div>
        </div>

        <!-- Search and Filter Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Search and Filter</h3>
                <p class="mt-1 text-sm text-gray-500">Search students by various criteria</p>
            </div>
            <div class="border-t border-gray-200 px-4 py-5 sm:px-6">
                <form id="searchForm" method="GET" action="/students" class="grid grid-cols-1 gap-6 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-6">
                    <div>
                        <label for="admission_no" class="block text-sm font-medium text-gray-700">Admission No</label>
                        <input type="text" name="admission_no" id="admission_no" 
                               value="<?= htmlspecialchars($filters['admission_no'] ?? '') ?>" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                        <input type="text" name="name" id="name" 
                               value="<?= htmlspecialchars($filters['name'] ?? '') ?>" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700">Class</label>
                        <select name="class_id" id="class_id" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Classes</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>" <?= (isset($filters['class_id']) && $filters['class_id'] == $class['id']) ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($class['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    
                    <div>
                        <label for="category" class="block text-sm font-medium text-gray-700">Category</label>
                        <select name="category" id="category" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Categories</option>
                            <option value="regular_day" <?= (isset($filters['category']) && $filters['category'] == 'regular_day') ? 'selected' : '' ?>>Day</option>
                            <option value="regular_boarding" <?= (isset($filters['category']) && $filters['category'] == 'regular_boarding') ? 'selected' : '' ?>>Boarding</option>
                            <option value="international" <?= (isset($filters['category']) && $filters['category'] == 'international') ? 'selected' : '' ?>>International</option>
                        </select>
                    </div>
                    
                    <div>
                        <label for="guardian" class="block text-sm font-medium text-gray-700">Guardian</label>
                        <input type="text" name="guardian" id="guardian" 
                               value="<?= htmlspecialchars($filters['guardian'] ?? '') ?>" 
                               class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                    </div>
                    
                    <!-- Date Range Filter -->
                    <div class="sm:col-span-6">
                        <div class="flex items-center mb-2">
                            <input type="checkbox" name="date_mode" id="date_mode" value="enabled" 
                                   <?= (isset($filters['date_mode']) && $filters['date_mode'] === 'enabled') ? 'checked' : '' ?>
                                   class="h-4 w-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                            <label for="date_mode" class="ml-2 block text-sm font-medium text-gray-700">
                                Enable Date Range Filter
                            </label>
                        </div>
                        
                        <div id="dateRangeFields" class="<?= (isset($filters['date_mode']) && $filters['date_mode'] === 'enabled') ? '' : 'hidden' ?>">
                            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 md:grid-cols-4">
                                <div>
                                    <label for="date_from" class="block text-sm font-medium text-gray-700">From Date</label>
                                    <input type="date" name="date_from" id="date_from" 
                                           value="<?= htmlspecialchars($filters['date_from'] ?? '') ?>" 
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                                
                                <div>
                                    <label for="date_to" class="block text-sm font-medium text-gray-700">To Date</label>
                                    <input type="date" name="date_to" id="date_to" 
                                           value="<?= htmlspecialchars($filters['date_to'] ?? '') ?>" 
                                           class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div>
                        <label for="per_page" class="block text-sm font-medium text-gray-700">Per Page</label>
                        <select name="per_page" id="per_page" 
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="10" <?= ($pagination['per_page'] ?? 10) == 10 ? 'selected' : '' ?>>10</option>
                            <option value="25" <?= ($pagination['per_page'] ?? 10) == 25 ? 'selected' : '' ?>>25</option>
                            <option value="50" <?= ($pagination['per_page'] ?? 10) == 50 ? 'selected' : '' ?>>50</option>
                            <option value="100" <?= ($pagination['per_page'] ?? 10) == 100 ? 'selected' : '' ?>>100</option>
                        </select>
                    </div>
                    


                    <div class="flex items-end">
                        <button type="submit" class="w-full bg-indigo-600 text-white py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Search
                        </button>
                    </div>
                </form>
                
                <?php if (!empty(array_filter($filters)) || !empty($searchTerm)): ?>
                    <div class="mt-4">
                        <a href="/students" class="text-sm text-indigo-600 hover:text-indigo-500">
                            Clear all filters
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Students Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Profile
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Admission No
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Class
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Category
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Guardian
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($students)): ?>
                            <tr>
                                <td colspan="7" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No students found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($students as $student): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?php if (!empty($student['profile_picture'])): ?>
                                            <img src="/storage/uploads/<?= htmlspecialchars($student['profile_picture']) ?>" alt="Profile Picture" class="h-10 w-10 object-cover rounded-full" onerror="this.src='/images/default-profile.png';">
                                        <?php else: ?>
                                            <div class="h-10 w-10 rounded-full bg-gray-200 flex items-center justify-center">
                                                <span class="text-gray-500 text-sm"><?= substr($student['first_name'], 0, 1) ?></span>
                                            </div>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($student['admission_no']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($student['class_name'] ?? 'N/A') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-800">
                                            <?= htmlspecialchars($studentCategoryLabels[$student['student_category'] ?? 'regular_day']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($student['guardian_name'] ?? 'N/A') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <a href="/students/<?= $student['id'] ?>" class="text-indigo-600 hover:text-indigo-900">View</a>
                                        <a href="/students/<?= $student['id'] ?>/edit" class="ml-4 text-indigo-600 hover:text-indigo-900">Edit</a>
                                        <a href="/students/<?= $student['id'] ?>/delete" class="ml-4 text-red-600 hover:text-red-900"
                                           onclick="return confirm('Are you sure you want to delete this student? This action cannot be undone.')">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            
            <!-- Pagination -->
            <?php if (!empty($pagination) && $pagination['total_pages'] > 1): ?>
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between sm:hidden">
                        <?php if ($pagination['current_page'] > 1): ?>
                            <a href="?page=<?= $pagination['current_page'] - 1 ?>&<?= http_build_query(array_filter(array_merge($filters, ['search' => $searchTerm, 'per_page' => $pagination['per_page']]))) ?>" 
                               class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <a href="?page=<?= $pagination['current_page'] + 1 ?>&<?= http_build_query(array_filter(array_merge($filters, ['search' => $searchTerm, 'per_page' => $pagination['per_page']]))) ?>" 
                               class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                                <span class="font-medium"><?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total_records']) ?></span>
                                of
                                <span class="font-medium"><?= $pagination['total_records'] ?></span>
                                results
                            </p>
                        </div>
                        <div>
                            <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                                <?php if ($pagination['current_page'] > 1): ?>
                                    <a href="?page=<?= $pagination['current_page'] - 1 ?>&<?= http_build_query(array_filter(array_merge($filters, ['search' => $searchTerm, 'per_page' => $pagination['per_page']]))) ?>" 
                                       class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                        <span class="sr-only">Previous</span>
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                        </svg>
                                    </a>
                                <?php endif; ?>
                                
                                <?php 
                                // Calculate start and end page numbers to display
                                $startPage = max(1, $pagination['current_page'] - 2);
                                $endPage = min($pagination['total_pages'], $pagination['current_page'] + 2);
                                
                                // Adjust if at the beginning or end
                                if ($startPage === 1) {
                                    $endPage = min(5, $pagination['total_pages']);
                                } elseif ($endPage === $pagination['total_pages']) {
                                    $startPage = max(1, $pagination['total_pages'] - 4);
                                }
                                
                                for ($i = $startPage; $i <= $endPage; $i++): ?>
                                    <a href="?page=<?= $i ?>&<?= http_build_query(array_filter(array_merge($filters, ['search' => $searchTerm, 'per_page' => $pagination['per_page']]))) ?>" 
                                       class="relative inline-flex items-center px-4 py-2 border <?= $i == $pagination['current_page'] ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' ?> text-sm font-medium">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                    <a href="?page=<?= $pagination['current_page'] + 1 ?>&<?= http_build_query(array_filter(array_merge($filters, ['search' => $searchTerm, 'per_page' => $pagination['per_page']]))) ?>" 
                                       class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
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

<!-- Add Student Modal -->
<div id="addStudentModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
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
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                                Add Student
                            </h3>
                            <button type="button" class="text-gray-400 hover:text-gray-500" id="closeAddStudentModal">
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-2">
                            <!-- Modal form content will be loaded here via AJAX -->
                            <div id="addStudentModalContent">
                                <!-- Loading indicator -->
                                <div class="flex justify-center items-center h-32">
                                    <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Custom Billing Modal -->
<div id="customBillingModal" class="fixed inset-0 z-60 overflow-y-auto hidden">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-900 opacity-80"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-xl text-left overflow-hidden shadow-2xl transform transition-all sm:my-8 sm:align-middle sm:max-w-2xl sm:w-full">
            <!-- Header -->
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <div class="flex-shrink-0 w-10 h-10 rounded-full bg-white bg-opacity-20 flex items-center justify-center mr-3">
                            <svg class="h-5 w-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 9V7a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2m2 4h10a2 2 0 002-2v-6a2 2 0 00-2-2H9a2 2 0 00-2 2v6a2 2 0 002 2zm7-5a2 2 0 11-4 0 2 2 0 014 0z"/>
                            </svg>
                        </div>
                        <div>
                            <h3 class="text-lg font-bold text-white" id="billingModalTitle">Custom Billing</h3>
                            <p class="text-emerald-100 text-sm" id="billingModalSubtitle">Set partial fee amounts for this student</p>
                        </div>
                    </div>
                    <button type="button" id="closeCustomBillingModal" class="text-white hover:text-emerald-200 transition-colors">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"/>
                        </svg>
                    </button>
                </div>
            </div>

            <!-- Body -->
            <div class="px-6 py-5">
                <!-- Info banner -->
                <div class="bg-amber-50 border border-amber-200 rounded-lg p-3 mb-5 flex items-start">
                    <svg class="h-5 w-5 text-amber-500 mr-2 flex-shrink-0 mt-0.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                    </svg>
                    <p class="text-sm text-amber-700">
                        This student was admitted mid-term. Set the partial fee amounts they are expected to pay. Leave at the full amount to charge normally.
                    </p>
                </div>

                <!-- Fee list -->
                <div id="billingFeesList">
                    <div class="flex justify-center items-center h-20">
                        <div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-600"></div>
                    </div>
                </div>

                <!-- Description field -->
                <div class="mt-5">
                    <label for="billingDescription" class="block text-sm font-medium text-gray-700 mb-1">
                        Reason / Description <span class="text-red-500">*</span>
                    </label>
                    <textarea id="billingDescription" rows="3"
                        class="w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-emerald-500 focus:border-emerald-500 sm:text-sm"
                        placeholder="e.g. Student admitted in week 3 of Term 1; charged 60% of full fee..."></textarea>
                </div>
            </div>

            <!-- Footer -->
            <div class="bg-gray-50 px-6 py-4 flex items-center justify-between border-t border-gray-200">
                <button type="button" id="skipBillingBtn"
                    class="text-sm font-medium text-gray-600 hover:text-gray-800 underline transition-colors">
                    Skip &amp; use standard amounts
                </button>
                <div class="flex gap-3">
                    <button type="button" id="confirmBillingBtn"
                        class="inline-flex items-center px-5 py-2 border-2 border-green-700 text-sm font-semibold rounded-lg shadow-sm text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500 transition-colors">
                        <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"/>
                        </svg>
                        Confirm Billing
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const openModalBtn = document.getElementById('openAddStudentModal');
    const closeModalBtn = document.getElementById('closeAddStudentModal');
    const modal = document.getElementById('addStudentModal');
    const modalContent = document.getElementById('addStudentModalContent');

    // â”€â”€â”€ Custom billing state â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    let currentStudentId = null;
    const billingModal = document.getElementById('customBillingModal');
    
    // â”€â”€â”€ Open / close add-student modal â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    openModalBtn.addEventListener('click', function() {
        modal.classList.remove('hidden');
        loadAddStudentForm();
    });
    closeModalBtn.addEventListener('click', closeAddStudentModal);
    modal.addEventListener('click', function(e) {
        if (e.target === modal) closeAddStudentModal();
    });
    function closeAddStudentModal() {
        modal.classList.add('hidden');
    }

    // â”€â”€â”€ Required-fields tracking (shows Proceed to Billing) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    const REQUIRED_IDS = ['first_name','last_name','dob','gender','class_id','guardian_phone'];
    function checkRequiredFields() {
        const proceedBtn = modalContent.querySelector('#proceedToBillingBtn');
        if (!proceedBtn) return;
        const allFilled = REQUIRED_IDS.every(id => {
            const el = modalContent.querySelector('#' + id);
            return el && el.value.trim() !== '';
        });
        proceedBtn.style.display = allFilled ? 'inline-flex' : 'none';
    }

    // â”€â”€â”€ Normal form submit handler â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    let isSubmitting = false;
    function handleFormSubmit(e) {
        e.preventDefault();
        submitStudentForm(e.target, false);
    }

    // â”€â”€â”€ Shared submit function (proceedToBilling flag) â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    function submitStudentForm(form, proceedToBilling) {
        if (isSubmitting) return;
        isSubmitting = true;

        const formData = new FormData(form);
        const submitBtn  = form.querySelector('#saveStudentBtn');
        const billingBtn = form.querySelector('#proceedToBillingBtn');
        const originalSubmitText  = submitBtn ? submitBtn.innerHTML : '';
        const originalBillingText = billingBtn ? billingBtn.innerHTML : '';
        const spinner = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>';

        if (submitBtn)  { submitBtn.innerHTML = spinner + ' Saving...'; submitBtn.disabled = true; }
        if (billingBtn) { billingBtn.innerHTML = spinner + ' Saving...'; billingBtn.disabled = true; }

        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success) {
                if (proceedToBilling && data.student_id) {
                    // Save the student ID, close the add modal, open billing modal
                    currentStudentId = data.student_id;
                    closeAddStudentModal();
                    openCustomBillingModal(data.student_id);
                } else {
                    closeAddStudentModal();
                    location.reload();
                }
            } else {
                if (typeof window.showToast === 'function') {
                    window.showToast(data.error || 'Failed to add student. Please try again.', 'error');
                } else {
                    alert(data.error || 'Failed to add student. Please try again.');
                }
                if (submitBtn)  { submitBtn.innerHTML = originalSubmitText;  submitBtn.disabled  = false; }
                if (billingBtn) { billingBtn.innerHTML = originalBillingText; billingBtn.disabled = false; }
                isSubmitting = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            if (typeof window.showToast === 'function') {
                window.showToast('An error occurred. Please try again.', 'error');
            } else {
                alert('An error occurred. Please try again.');
            }
            if (submitBtn)  { submitBtn.innerHTML = originalSubmitText;  submitBtn.disabled  = false; }
            if (billingBtn) { billingBtn.innerHTML = originalBillingText; billingBtn.disabled = false; }
            isSubmitting = false;
        });
    }

    // â”€â”€â”€ Load add student form via AJAX â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    function loadAddStudentForm() {
        modalContent.innerHTML = '<div class="flex justify-center items-center h-32"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div></div>';
        isSubmitting = false;

        // Send the X-Requested-With header so the controller returns ONLY the form partial
        fetch('/students/create', {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
            .then(r => r.text())
            .then(html => {
                // Controller returns the form partial directly when AJAX header is set
                modalContent.innerHTML = html;
                const form = modalContent.querySelector('form');

                if (!form) {
                    modalContent.innerHTML = '<p class="text-red-500">Error loading form. Please try again.</p>';
                    return;
                }

                    // Normal submit
                    form.addEventListener('submit', handleFormSubmit);

                    // â”€â”€ Proceed to Billing button â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
                    const billingBtn = modalContent.querySelector('#proceedToBillingBtn');
                    if (billingBtn) {
                        billingBtn.addEventListener('click', function() {
                            submitStudentForm(form, true);
                        });
                    }

                    // â”€â”€ Watch required fields â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
                    REQUIRED_IDS.forEach(id => {
                        const el = form.querySelector('#' + id);
                        if (el) {
                            el.addEventListener('input',  checkRequiredFields);
                            el.addEventListener('change', checkRequiredFields);
                        }
                    });
                    checkRequiredFields(); // Run once on load

                    // â”€â”€ Generate admission number â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
                    const generateBtn   = modalContent.querySelector('#generate-admission-btn');
                    const admissionInput = modalContent.querySelector('#admission_no');
                    const admissionHint  = modalContent.querySelector('#admission_format_hint');
                    if (generateBtn && admissionInput) {
                        generateBtn.addEventListener('click', function() {
                            fetch('/settings/generate/admission')
                                .then(r => r.json())
                                .then(data => {
                                    if (data.admission_number) admissionInput.value = data.admission_number;
                                    if (admissionHint && data.format_description) admissionHint.textContent = data.format_description;
                                    checkRequiredFields();
                                })
                                .catch(() => {
                                    const ts = new Date().toLocaleTimeString('en-GB', { hour12: false }).replace(/:/g, '');
                                    admissionInput.value = 'EPI-' + ts;
                                    checkRequiredFields();
                                });
                        });
                    }

                    // â”€â”€ Category details logic â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
                    const categorySelect     = modalContent.querySelector('#student_category');
                    const categoryContainer  = modalContent.querySelector('#category_details_container');
                    const categoryInput      = modalContent.querySelector('#student_category_details');
                    const categoryAsterisk   = modalContent.querySelector('#category_details_asterisk');
                    function updateCategoryDetails() {
                        if (!categorySelect || !categoryContainer || !categoryInput) return;
                        const type = categorySelect.value;
                        if (type === 'regular_day') {
                            categoryContainer.style.display = 'none';
                            categoryInput.required = false;
                        } else if (type === 'regular_boarding') {
                            categoryContainer.style.display = 'block';
                            categoryInput.required = false;
                            if (categoryAsterisk) categoryAsterisk.classList.add('hidden');
                        } else if (type === 'international') {
                            categoryContainer.style.display = 'block';
                            categoryInput.required = true;
                            if (categoryAsterisk) categoryAsterisk.classList.remove('hidden');
                        }
                    }
                    if (categorySelect) {
                        categorySelect.addEventListener('change', updateCategoryDetails);
                        updateCategoryDetails();
                    }

                    // â”€â”€ Cancel button in form â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
                    const cancelBtn = modalContent.querySelector('#closeAddStudentModal');
                    if (cancelBtn) {
                        cancelBtn.addEventListener('click', closeAddStudentModal);
                    }
            })
            .catch(() => {
                modalContent.innerHTML = '<p class="text-red-500">Error loading form. Please try again.</p>';
            });
    }

    // â”€â”€â”€ Custom Billing Modal â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€â”€
    function openCustomBillingModal(studentId) {
        billingModal.classList.remove('hidden');
        document.getElementById('billingFeesList').innerHTML = '<div class="flex justify-center items-center h-20"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-emerald-600"></div></div>';
        document.getElementById('billingDescription').value = '';

        // Load student fees
        fetch('/payments/student-fees/' + studentId, {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            if (data.success && data.fee_allocations && data.fee_allocations.length > 0) {
                renderBillingFees(data.fee_allocations);
            } else {
                document.getElementById('billingFeesList').innerHTML =
                    '<p class="text-sm text-gray-500 text-center py-4">No fees are currently assigned to this student\'s class. Custom billing is not needed.</p>';
                document.getElementById('confirmBillingBtn').disabled = true;
            }
        })
        .catch(() => {
            document.getElementById('billingFeesList').innerHTML =
                '<p class="text-red-500 text-sm">Failed to load fee data.</p>';
        });
    }

    function renderBillingFees(fees) {
        let html = '<div class="space-y-3">';
        html += '<p class="text-sm text-gray-600 mb-3 font-medium">Assigned fees for this student\'s class:</p>';
        fees.forEach(function(fee) {
            const fullAmt = parseFloat(fee.amount).toFixed(2);
            html += `
            <div class="flex items-center justify-between bg-gray-50 border border-gray-200 rounded-lg px-4 py-3">
                <div class="flex-1 mr-4">
                    <div class="font-medium text-gray-800 text-sm">${escapeHtml(fee.fee_name)}</div>
                    <div class="text-xs text-gray-500 mt-0.5">Full amount: <span class="font-semibold text-gray-700">${fullAmt}</span></div>
                </div>
                <div class="flex items-center gap-2 flex-shrink-0">
                    <label class="text-xs text-gray-500 whitespace-nowrap">Custom Amount:</label>
                    <input type="number"
                        class="billing-fee-input w-32 border border-gray-300 rounded-md py-1.5 px-2.5 text-sm focus:ring-emerald-500 focus:border-emerald-500"
                        data-fee-id="${fee.fee_id}"
                        data-original="${fullAmt}"
                        value="${fullAmt}"
                        min="0"
                        step="0.01"
                        placeholder="${fullAmt}">
                </div>
            </div>`;
        });
        html += '</div>';
        document.getElementById('billingFeesList').innerHTML = html;
    }

    function escapeHtml(str) {
        const div = document.createElement('div');
        div.appendChild(document.createTextNode(str));
        return div.innerHTML;
    }

    // Close billing modal
    document.getElementById('closeCustomBillingModal').addEventListener('click', function() {
        billingModal.classList.add('hidden');
        location.reload();
    });
    document.getElementById('skipBillingBtn').addEventListener('click', function() {
        billingModal.classList.add('hidden');
        location.reload();
    });
    billingModal.addEventListener('click', function(e) {
        if (e.target === billingModal) {
            billingModal.classList.add('hidden');
            location.reload();
        }
    });

    // Confirm billing submission
    document.getElementById('confirmBillingBtn').addEventListener('click', function() {
        const description = document.getElementById('billingDescription').value.trim();
        if (!description) {
            alert('Please enter a reason/description for the custom billing.');
            document.getElementById('billingDescription').focus();
            return;
        }

        const inputs = document.querySelectorAll('.billing-fee-input');
        if (inputs.length === 0) {
            billingModal.classList.add('hidden');
            location.reload();
            return;
        }

        const feeAmounts = {};
        let hasChanges = false;
        inputs.forEach(function(input) {
            const feeId = input.dataset.feeId;
            const originalAmt = parseFloat(input.dataset.original);
            const customAmt = parseFloat(input.value);
            feeAmounts[feeId] = isNaN(customAmt) ? originalAmt : customAmt;
            if (!isNaN(customAmt) && Math.abs(customAmt - originalAmt) > 0.001) hasChanges = true;
        });

        // Build form data
        const formData = new FormData();
        formData.append('student_id', currentStudentId);
        formData.append('description', description);
        for (const [feeId, amt] of Object.entries(feeAmounts)) {
            formData.append('fee_amounts[' + feeId + ']', amt);
        }

        const confirmBtn = document.getElementById('confirmBillingBtn');
        confirmBtn.disabled = true;
        confirmBtn.innerHTML = '<svg class="animate-spin h-4 w-4 mr-1.5 text-white" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';

        fetch('/students/set-custom-billing', {
            method: 'POST',
            body: formData,
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(data => {
            billingModal.classList.add('hidden');
            if (data.success) {
                if (typeof window.showToast === 'function') {
                    window.showToast('Student added and custom billing set successfully!', 'success');
                }
                setTimeout(() => location.reload(), 800);
            } else {
                if (typeof window.showToast === 'function') {
                    window.showToast(data.error || 'Failed to save custom billing.', 'error');
                } else {
                    alert(data.error || 'Failed to save custom billing.');
                }
                location.reload();
            }
        })
        .catch(() => {
            billingModal.classList.add('hidden');
            location.reload();
        });
    });
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>
