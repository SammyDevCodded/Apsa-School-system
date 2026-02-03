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
                <div class="relative inline-block text-left">
                    <button type="button" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700 focus:outline-none" id="export-menu-button">
                        Export
                    </button>
                    <div class="origin-top-right absolute right-0 mt-2 w-40 rounded-md shadow-lg bg-white ring-1 ring-black ring-opacity-5 hidden" id="export-menu">
                        <div class="py-1">
                            <a href="/students/export?<?= http_build_query(array_filter(array_merge($filters, ['search' => $searchTerm]))) ?>" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100">
                                Export to CSV
                            </a>
                            <a href="/students/export?<?= http_build_query(array_filter(array_merge($filters, ['search' => $searchTerm, 'print' => 1]))) ?>" 
                               class="block px-4 py-2 text-sm text-gray-700 hover:bg-gray-100" target="_blank">
                                Print/Export to PDF
                            </a>
                        </div>
                    </div>
                </div>
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
                            <a href="?page=<?= $pagination['current_page'] - 1 ?>&<?= http_build_query(array_filter(array_merge($filters, ['search' => $searchTerm]))) ?>" 
                               class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </a>
                        <?php endif; ?>
                        
                        <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <a href="?page=<?= $pagination['current_page'] + 1 ?>&<?= http_build_query(array_filter(array_merge($filters, ['search' => $searchTerm]))) ?>" 
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
                                    <a href="?page=<?= $pagination['current_page'] - 1 ?>&<?= http_build_query(array_filter(array_merge($filters, ['search' => $searchTerm]))) ?>" 
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
                                    <a href="?page=<?= $i ?>&<?= http_build_query(array_filter(array_merge($filters, ['search' => $searchTerm]))) ?>" 
                                       class="relative inline-flex items-center px-4 py-2 border <?= $i == $pagination['current_page'] ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' ?> text-sm font-medium">
                                        <?= $i ?>
                                    </a>
                                <?php endfor; ?>
                                
                                <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                                    <a href="?page=<?= $pagination['current_page'] + 1 ?>&<?= http_build_query(array_filter(array_merge($filters, ['search' => $searchTerm]))) ?>" 
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

<script>
document.addEventListener('DOMContentLoaded', function() {
    const openModalBtn = document.getElementById('openAddStudentModal');
    const closeModalBtn = document.getElementById('closeAddStudentModal');
    const modal = document.getElementById('addStudentModal');
    const modalContent = document.getElementById('addStudentModalContent');
    
    // Open modal
    openModalBtn.addEventListener('click', function() {
        modal.classList.remove('hidden');
        // Load the form content via AJAX
        loadAddStudentForm();
    });
    
    // Close modal
    closeModalBtn.addEventListener('click', function() {
        modal.classList.add('hidden');
    });
    
    // Close modal when clicking outside
    modal.addEventListener('click', function(e) {
        if (e.target === modal) {
            modal.classList.add('hidden');
        }
    });
    
    // Load add student form via AJAX
    function loadAddStudentForm() {
        fetch('/students/create')
            .then(response => response.text())
            .then(html => {
                // Create a temporary div to parse the HTML
                const parser = new DOMParser();
                const doc = parser.parseFromString(html, 'text/html');
                
                // Extract the form content
                const formElement = doc.querySelector('form');
                if (formElement) {
                    modalContent.innerHTML = formElement.outerHTML;
                    
                    // Add event listeners for the form
                    const form = modalContent.querySelector('form');
                    form.addEventListener('submit', handleFormSubmit);
                    
                    // Re-attach the generate admission number functionality
                    const generateBtn = modalContent.querySelector('#generate-admission-btn');
                    const admissionInput = modalContent.querySelector('#admission_no');
                    
                    if (generateBtn && admissionInput) {
                        generateBtn.addEventListener('click', function() {
                            fetch('/settings/generate/admission')
                                .then(response => response.json())
                                .then(data => {
                                    if (data.admission_number) {
                                        admissionInput.value = data.admission_number;
                                    }
                                })
                                .catch(error => {
                                    console.error('Error generating admission number:', error);
                                    // Fallback to client-side generation
                                    const prefix = 'EPI'; // Default prefix
                                    const timestamp = new Date().toLocaleTimeString('en-GB', { hour12: false }).replace(/:/g, '');
                                    admissionInput.value = prefix + '-' + timestamp;
                                });
                        });
                    }
                } else {
                    modalContent.innerHTML = '<p>Error loading form. Please try again.</p>';
                }
            })
            .catch(error => {
                console.error('Error:', error);
                modalContent.innerHTML = '<p>Error loading form. Please try again.</p>';
            });
    }
    
    // Handle form submission
    function handleFormSubmit(e) {
        e.preventDefault();
        
        const form = e.target;
        const formData = new FormData(form);
        const submitBtn = form.querySelector('button[type="submit"]');
        const originalBtnText = submitBtn.innerHTML;
        
        // Show loading state
        submitBtn.innerHTML = '<svg class="animate-spin -ml-1 mr-2 h-4 w-4 text-white inline" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg> Saving...';
        submitBtn.disabled = true;
        
        fetch(form.action, {
            method: 'POST',
            body: formData,
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success) {
                // Show success message
                alert('Student added successfully!');
                // Close modal
                modal.classList.add('hidden');
                // Reload the page to show the new student
                location.reload();
            } else {
                // Show error message
                alert(data.error || 'Failed to add student. Please try again.');
                // Reset button
                submitBtn.innerHTML = originalBtnText;
                submitBtn.disabled = false;
            }
        })
        .catch(error => {
            console.error('Error:', error);
            alert('An error occurred. Please try again.');
            // Reset button
            submitBtn.innerHTML = originalBtnText;
            submitBtn.disabled = false;
        });
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>