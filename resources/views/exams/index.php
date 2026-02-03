<?php 
$title = 'Exams'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Exams</h1>
            <div class="flex space-x-3">
                <a href="/exam_results/create" class="bg-green-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-green-700">
                    Record Exam Results
                </a>
                <a href="/exams/create" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                    Add Exam
                </a>
            </div>
        </div>

        <!-- Exams Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="overflow-x-auto">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Exam Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Term
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Classes
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Date
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
                        <?php if (empty($exams)): ?>
                            <tr>
                                <td colspan="6" class="px-6 py-4 text-center text-sm text-gray-500">
                                    No exams found.
                                </td>
                            </tr>
                        <?php else: ?>
                            <?php foreach ($exams as $exam): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= htmlspecialchars($exam['name']) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?php 
                                            // Determine styling based on the term value
                                            if (stripos($exam['term'], '1st') !== false || stripos($exam['term'], 'first') !== false) {
                                                echo 'bg-blue-100 text-blue-800';
                                            } elseif (stripos($exam['term'], '2nd') !== false || stripos($exam['term'], 'second') !== false) {
                                                echo 'bg-green-100 text-green-800';
                                            } elseif (stripos($exam['term'], '3rd') !== false || stripos($exam['term'], 'third') !== false) {
                                                echo 'bg-purple-100 text-purple-800';
                                            } else {
                                                echo 'bg-gray-100 text-gray-800';
                                            }
                                            ?>">
                                            <?= htmlspecialchars($exam['term']) ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?php if (!empty($exam['assigned_classes'])): ?>
                                            <?= htmlspecialchars($exam['assigned_classes']) ?>
                                        <?php else: ?>
                                            <?= htmlspecialchars($exam['class_name'] ?? 'N/A') ?>
                                        <?php endif; ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= date('M j, Y', strtotime($exam['date'])) ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-900">
                                        <?= htmlspecialchars(substr($exam['description'] ?? 'N/A', 0, 50)) ?><?= strlen($exam['description'] ?? '') > 50 ? '...' : '' ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                        <!-- Changed to use JavaScript functions for modals -->
                                        <a href="#" onclick="openViewModal(<?= $exam['id'] ?>); return false;" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            View
                                        </a>
                                        <a href="#" onclick="openEditModal(<?= $exam['id'] ?>); return false;" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Edit
                                        </a>
                                        <a href="/exams/<?= $exam['id'] ?>/delete" class="text-red-600 hover:text-red-900"
                                           onclick="return confirm('Are you sure you want to delete this exam?')">
                                            Delete
                                        </a>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>

<!-- View Modal -->
<div id="viewModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="viewModalTitle">
                                Exam Details
                            </h3>
                            <button type="button" class="close-view-modal text-gray-400 hover:text-gray-500">
                                <span class="text-2xl">&times;</span>
                            </button>
                        </div>
                        <div id="viewModalContent" class="mt-2">
                            <!-- Content will be loaded here via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Edit Modal -->
<div id="editModal" class="fixed inset-0 z-50 hidden overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 transition-opacity" aria-hidden="true">
            <div class="absolute inset-0 bg-gray-500 opacity-75"></div>
        </div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-3xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex justify-between items-center mb-4">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="editModalTitle">
                                Edit Exam
                            </h3>
                            <button type="button" class="close-edit-modal text-gray-400 hover:text-gray-500">
                                <span class="text-2xl">&times;</span>
                            </button>
                        </div>
                        <div id="editModalContent" class="mt-2">
                            <!-- Content will be loaded here via AJAX -->
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<script>
// Function to open view modal
function openViewModal(examId) {
    const modal = document.getElementById('viewModal');
    const modalContent = document.getElementById('viewModalContent');
    
    // Show loading indicator
    modalContent.innerHTML = '<div class="text-center py-8"><div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-indigo-600 border-t-transparent"></div></div>';
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Fetch content via AJAX
    fetch(`/exams/${examId}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Parse the HTML to extract only the content we need
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const content = doc.querySelector('.max-w-7xl');
        
        if (content) {
            modalContent.innerHTML = content.innerHTML;
            
            // Add close button functionality to the view modal content
            const backButton = modalContent.querySelector('a[href="/exams"]');
            if (backButton) {
                backButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeViewModal();
                });
            }
        } else {
            modalContent.innerHTML = '<div class="text-center py-8 text-red-500">Error loading content</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        modalContent.innerHTML = '<div class="text-center py-8 text-red-500">Error loading content</div>';
    });
}

// Function to open edit modal
function openEditModal(examId) {
    const modal = document.getElementById('editModal');
    const modalContent = document.getElementById('editModalContent');
    
    // Show loading indicator
    modalContent.innerHTML = '<div class="text-center py-8"><div class="spinner-border animate-spin inline-block w-8 h-8 border-4 rounded-full text-indigo-600 border-t-transparent"></div></div>';
    
    // Show modal
    modal.classList.remove('hidden');
    
    // Fetch content via AJAX
    fetch(`/exams/${examId}/edit`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.text())
    .then(html => {
        // Parse the HTML to extract only the content we need
        const parser = new DOMParser();
        const doc = parser.parseFromString(html, 'text/html');
        const content = doc.querySelector('.max-w-7xl');
        
        if (content) {
            modalContent.innerHTML = content.innerHTML;
            
            // Handle form submission within the modal
            const form = modalContent.querySelector('form');
            if (form) {
                form.addEventListener('submit', function(e) {
                    e.preventDefault();
                    
                    // Show loading state
                    const submitBtn = form.querySelector('button[type="submit"]');
                    const originalText = submitBtn ? submitBtn.textContent : '';
                    if (submitBtn) {
                        submitBtn.disabled = true;
                        submitBtn.textContent = 'Updating...';
                    }
                    
                    // Submit form via AJAX
                    const formData = new FormData(form);
                    
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
                            // Close modal and show success message
                            closeEditModal();
                            alert(data.message);
                            // Reload the page to show updated data
                            location.reload();
                        } else {
                            // Show error message
                            alert(data.message || 'Failed to update exam');
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('An error occurred while updating the exam');
                    })
                    .finally(() => {
                        // Restore button state
                        if (submitBtn) {
                            submitBtn.disabled = false;
                            submitBtn.textContent = originalText;
                        }
                    });
                });
            }
            
            // Add close button functionality to the edit modal content
            const backButton = modalContent.querySelector('a[href="/exams"]');
            if (backButton) {
                backButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    closeEditModal();
                });
            }
        } else {
            modalContent.innerHTML = '<div class="text-center py-8 text-red-500">Error loading content</div>';
        }
    })
    .catch(error => {
        console.error('Error:', error);
        modalContent.innerHTML = '<div class="text-center py-8 text-red-500">Error loading content</div>';
    });
}

// Function to close view modal
function closeViewModal() {
    const modal = document.getElementById('viewModal');
    modal.classList.add('hidden');
}

// Function to close edit modal
function closeEditModal() {
    const modal = document.getElementById('editModal');
    modal.classList.add('hidden');
}

// Add event listeners for close buttons
document.addEventListener('DOMContentLoaded', function() {
    // Close view modal
    const closeViewButtons = document.querySelectorAll('.close-view-modal');
    closeViewButtons.forEach(button => {
        button.addEventListener('click', closeViewModal);
    });
    
    // Close edit modal
    const closeEditButtons = document.querySelectorAll('.close-edit-modal');
    closeEditButtons.forEach(button => {
        button.addEventListener('click', closeEditModal);
    });
    
    // Close modals when clicking outside
    window.addEventListener('click', function(event) {
        const viewModal = document.getElementById('viewModal');
        const editModal = document.getElementById('editModal');
        
        if (event.target === viewModal) {
            closeViewModal();
        }
        
        if (event.target === editModal) {
            closeEditModal();
        }
    });
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>