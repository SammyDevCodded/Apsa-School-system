<?php 
$title = 'Subjects'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Subjects</h1>
            <button id="add-subject-btn" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                Add Subject
            </button>
        </div>

        <!-- Search and Filters -->
        <div class="mb-6 bg-white shadow rounded-lg p-4">
            <form method="GET" class="flex flex-col sm:flex-row gap-4">
                <div class="flex-grow">
                    <div class="relative rounded-md shadow-sm">
                        <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                            <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search subjects by code, name, class, or description..." class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md">
                    </div>
                </div>
                <div class="flex items-center space-x-2">
                    <select name="per_page" class="block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="10" <?= ($perPage ?? 10) == 10 ? 'selected' : '' ?>>10 per page</option>
                        <option value="25" <?= ($perPage ?? 10) == 25 ? 'selected' : '' ?>>25 per page</option>
                        <option value="50" <?= ($perPage ?? 10) == 50 ? 'selected' : '' ?>>50 per page</option>
                        <option value="100" <?= ($perPage ?? 10) == 100 ? 'selected' : '' ?>>100 per page</option>
                    </select>
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Search
                    </button>
                    <?php if (!empty($search)): ?>
                    <a href="/subjects" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Clear
                    </a>
                    <?php endif; ?>
                </div>
            </form>
        </div>

        <!-- Subjects Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Code
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Class
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Description
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($subjects)): ?>
                            <tr>
                                <td colspan="5" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No subjects found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($subjects as $subject): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($subject['code']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($subject['name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($subject['class_name'] ?? 'N/A') ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?= htmlspecialchars(substr($subject['description'] ?? 'N/A', 0, 50)) ?><?= strlen($subject['description'] ?? '') > 50 ? '...' : '' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <button data-subject-id="<?= $subject['id'] ?>" class="view-subject-btn text-indigo-600 hover:text-indigo-900 mr-3">
                                            View
                                        </button>
                                        <button data-subject-id="<?= $subject['id'] ?>" class="edit-subject-btn text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </button>
                                        <a href="/subjects/<?= $subject['id'] ?>/delete" class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('Are you sure you want to delete this subject?')">
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
            <?php if (isset($pagination) && $pagination['total_pages'] > 1): ?>
            <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                <div class="flex-1 flex justify-between sm:hidden">
                    <?php if ($pagination['current_page'] > 1): ?>
                    <a href="?page=<?= $pagination['current_page'] - 1 ?>&per_page=<?= $pagination['per_page'] ?>&search=<?= urlencode($search ?? '') ?>" 
                       class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                    <a href="?page=<?= $pagination['current_page'] + 1 ?>&per_page=<?= $pagination['per_page'] ?>&search=<?= urlencode($search ?? '') ?>" 
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
                            <span class="font-medium"><?= min($pagination['current_page'] * $pagination['per_page'], $pagination['total']) ?></span>
                            of
                            <span class="font-medium"><?= $pagination['total'] ?></span>
                            results
                        </p>
                    </div>
                    <div>
                        <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                            <?php if ($pagination['current_page'] > 1): ?>
                            <a href="?page=1&per_page=<?= $pagination['per_page'] ?>&search=<?= urlencode($search ?? '') ?>" 
                               class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span>First</span>
                            </a>
                            <a href="?page=<?= $pagination['current_page'] - 1 ?>&per_page=<?= $pagination['per_page'] ?>&search=<?= urlencode($search ?? '') ?>" 
                               class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <?php endif; ?>
                            
                            <?php 
                            $startPage = max(1, $pagination['current_page'] - 2);
                            $endPage = min($pagination['total_pages'], $pagination['current_page'] + 2);
                            
                            if ($startPage === 1) {
                                $endPage = min(5, $pagination['total_pages']);
                            } elseif ($endPage === $pagination['total_pages']) {
                                $startPage = max(1, $pagination['total_pages'] - 4);
                            }
                            
                            for ($i = $startPage; $i <= $endPage; $i++): ?>
                            <a href="?page=<?= $i ?>&per_page=<?= $pagination['per_page'] ?>&search=<?= urlencode($search ?? '') ?>" 
                               class="relative inline-flex items-center px-4 py-2 border <?= $i == $pagination['current_page'] ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' ?> text-sm font-medium">
                                <?= $i ?>
                            </a>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['current_page'] < $pagination['total_pages']): ?>
                            <a href="?page=<?= $pagination['current_page'] + 1 ?>&per_page=<?= $pagination['per_page'] ?>&search=<?= urlencode($search ?? '') ?>" 
                               class="relative inline-flex items-center px-2 py-2 border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                    <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                                </svg>
                            </a>
                            <a href="?page=<?= $pagination['total_pages'] ?>&per_page=<?= $pagination['per_page'] ?>&search=<?= urlencode($search ?? '') ?>" 
                               class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span>Last</span>
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

<!-- Add Subject Modal -->
<div id="add-subject-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white max-h-[80vh] overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add Subject</h3>
                <button id="close-add-modal" class="text-gray-400 hover:text-gray-500">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            <div id="add-subject-content">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- View Subject Modal -->
<div id="view-subject-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white max-h-[80vh] overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Subject Details</h3>
                <button id="close-view-modal" class="text-gray-400 hover:text-gray-500">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            <div id="view-subject-content">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Edit Subject Modal -->
<div id="edit-subject-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white max-h-[80vh] overflow-y-auto">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Subject</h3>
                <button id="close-edit-modal" class="text-gray-400 hover:text-gray-500">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            <div id="edit-subject-content">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add Subject Modal
    const addSubjectBtn = document.getElementById('add-subject-btn');
    const addSubjectModal = document.getElementById('add-subject-modal');
    const closeAddModal = document.getElementById('close-add-modal');
    const addSubjectContent = document.getElementById('add-subject-content');

    // View Subject Modal
    const viewSubjectModal = document.getElementById('view-subject-modal');
    const closeViewModal = document.getElementById('close-view-modal');
    const viewSubjectContent = document.getElementById('view-subject-content');
    const viewSubjectBtns = document.querySelectorAll('.view-subject-btn');

    // Edit Subject Modal
    const editSubjectModal = document.getElementById('edit-subject-modal');
    const closeEditModal = document.getElementById('close-edit-modal');
    const editSubjectContent = document.getElementById('edit-subject-content');
    const editSubjectBtns = document.querySelectorAll('.edit-subject-btn');

    // Open Add Subject Modal
    if (addSubjectBtn) {
        addSubjectBtn.addEventListener('click', function() {
            // Load the create form content via AJAX
            fetch('/subjects/create')
                .then(response => response.text())
                .then(html => {
                    // Extract only the form content
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const formContent = doc.querySelector('.bg-white.shadow.overflow-hidden');
                    if (formContent) {
                        addSubjectContent.innerHTML = formContent.innerHTML;
                        addSubjectModal.classList.remove('hidden');
                        
                        // Re-initialize any JavaScript needed for the form
                        initializeCreateForm();
                    }
                })
                .catch(error => {
                    console.error('Error loading add subject form:', error);
                    addSubjectContent.innerHTML = '<p class="text-red-500">Error loading form. Please try again.</p>';
                    addSubjectModal.classList.remove('hidden');
                });
        });
    }

    // Close Add Subject Modal
    if (closeAddModal) {
        closeAddModal.addEventListener('click', function() {
            addSubjectModal.classList.add('hidden');
        });
    }

    // Close Add Subject Modal when clicking outside
    if (addSubjectModal) {
        addSubjectModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    }

    // Open View Subject Modal
    viewSubjectBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const subjectId = this.getAttribute('data-subject-id');
            // Load the view content via AJAX
            fetch(`/subjects/${subjectId}`)
                .then(response => response.text())
                .then(html => {
                    // Extract only the content part
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const content = doc.querySelector('.bg-white.shadow.overflow-hidden');
                    if (content) {
                        viewSubjectContent.innerHTML = content.innerHTML;
                        viewSubjectModal.classList.remove('hidden');
                    }
                })
                .catch(error => {
                    console.error('Error loading subject details:', error);
                    viewSubjectContent.innerHTML = '<p class="text-red-500">Error loading details. Please try again.</p>';
                    viewSubjectModal.classList.remove('hidden');
                });
        });
    });

    // Close View Subject Modal
    if (closeViewModal) {
        closeViewModal.addEventListener('click', function() {
            viewSubjectModal.classList.add('hidden');
        });
    }

    // Close View Subject Modal when clicking outside
    if (viewSubjectModal) {
        viewSubjectModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    }

    // Open Edit Subject Modal
    editSubjectBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const subjectId = this.getAttribute('data-subject-id');
            // Load the edit form content via AJAX
            fetch(`/subjects/${subjectId}/edit`)
                .then(response => response.text())
                .then(html => {
                    // Extract only the form content
                    const parser = new DOMParser();
                    const doc = parser.parseFromString(html, 'text/html');
                    const formContent = doc.querySelector('.bg-white.shadow.overflow-hidden');
                    if (formContent) {
                        editSubjectContent.innerHTML = formContent.innerHTML;
                        editSubjectModal.classList.remove('hidden');
                        
                        // Re-initialize any JavaScript needed for the form
                        initializeEditForm();
                    }
                })
                .catch(error => {
                    console.error('Error loading edit subject form:', error);
                    editSubjectContent.innerHTML = '<p class="text-red-500">Error loading form. Please try again.</p>';
                    editSubjectModal.classList.remove('hidden');
                });
        });
    });

    // Close Edit Subject Modal
    if (closeEditModal) {
        closeEditModal.addEventListener('click', function() {
            editSubjectModal.classList.add('hidden');
        });
    }

    // Close Edit Subject Modal when clicking outside
    if (editSubjectModal) {
        editSubjectModal.addEventListener('click', function(e) {
            if (e.target === this) {
                this.classList.add('hidden');
            }
        });
    }

    // Function to initialize JavaScript for create form
    function initializeCreateForm() {
        const form = document.getElementById('create-subject-form');
        const cancelBtn = document.getElementById('cancel-create-btn');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(form);
                
                // Submit via AJAX
                fetch('/subjects', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the modal and refresh the subject list
                        addSubjectModal.classList.add('hidden');
                        location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'Unknown error occurred'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving the subject.');
                });
            });
        }
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                addSubjectModal.classList.add('hidden');
            });
        }
    }

    // Function to initialize JavaScript for edit form
    function initializeEditForm() {
        const form = document.getElementById('edit-subject-form');
        const cancelBtn = document.getElementById('cancel-edit-btn');
        
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                // Get form data
                const formData = new FormData(form);
                
                // Get subject ID from form action attribute
                const formAction = form.getAttribute('action');
                const subjectId = formAction.split('/').pop();
                
                // Submit via AJAX
                fetch(`/subjects/${subjectId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Close the modal and refresh the subject list
                        editSubjectModal.classList.add('hidden');
                        location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'Unknown error occurred'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the subject.');
                });
            });
        }
        
        if (cancelBtn) {
            cancelBtn.addEventListener('click', function() {
                editSubjectModal.classList.add('hidden');
            });
        }
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>