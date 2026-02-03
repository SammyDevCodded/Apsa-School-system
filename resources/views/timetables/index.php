<?php 
$title = 'Timetables'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Timetables</h1>
            <button id="add-timetable-btn" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                Add Timetable Entry
            </button>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash_success'])): ?>
        <div class="bg-green-100 border border-green-400 text-green-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $_SESSION['flash_success'] ?></span>
        </div>
        <?php endif; ?>

        <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $_SESSION['flash_error'] ?></span>
        </div>
        <?php endif; ?>

        <!-- Filter Section -->
        <div class="mb-6 bg-white shadow sm:rounded-lg p-6">
            <h3 class="text-lg font-medium text-gray-900 mb-4">Filter Timetable Entries</h3>
            <form id="filter-form" class="grid grid-cols-1 gap-6 sm:grid-cols-4">
                <div>
                    <label for="filter_class_id" class="block text-sm font-medium text-gray-700">Class</label>
                    <select name="class_id" id="filter_class_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Classes</option>
                        <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>" <?= (isset($_GET['class_id']) && $_GET['class_id'] == $class['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($class['name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label for="filter_staff_id" class="block text-sm font-medium text-gray-700">Staff (Teacher)</label>
                    <select name="staff_id" id="filter_staff_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Staff</option>
                        <?php foreach ($staff as $staffMember): ?>
                        <option value="<?= $staffMember['id'] ?>" <?= (isset($_GET['staff_id']) && $_GET['staff_id'] == $staffMember['id']) ? 'selected' : '' ?>>
                            <?= htmlspecialchars($staffMember['first_name'] . ' ' . $staffMember['last_name']) ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div>
                    <label for="filter_subject_id" class="block text-sm font-medium text-gray-700">Subject</label>
                    <select name="subject_id" id="filter_subject_id" class="mt-1 block w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md">
                        <option value="">All Subjects</option>
                        <?php foreach ($subjects as $subject): ?>
                        <option value="<?= $subject['id'] ?>" <?= (isset($_GET['subject_id']) && $_GET['subject_id'] == $subject['id']) ? 'selected' : '' ?> data-class="<?= $subject['class_id'] ?>">
                            <?= htmlspecialchars($subject['name'] . ' (' . $subject['code'] . ')') ?>
                        </option>
                        <?php endforeach; ?>
                    </select>
                </div>
                
                <div class="flex items-end">
                    <button type="submit" class="inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Filter
                    </button>
                    <button type="button" id="clear-filters" class="ml-2 inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md shadow-sm text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Clear
                    </button>
                </div>
            </form>
        </div>

        <!-- Timetables Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Timetable Entries</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">A list of all timetable entries in the system.</p>
            </div>
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Class
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Subject
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Staff
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Day
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Time
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Room
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200" id="timetable-entries">
                        <?php if (empty($timetables)): ?>
                        <tr>
                            <td colspan="7" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No timetable entries found.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($timetables as $entry): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= htmlspecialchars($entry['class_name'] ?? 'N/A') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($entry['subject_name'] ?? 'N/A') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars(($entry['staff_first_name'] ?? '') . ' ' . ($entry['staff_last_name'] ?? '')) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= ucfirst(htmlspecialchars($entry['day_of_week'])) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($entry['start_time']) ?> - <?= htmlspecialchars($entry['end_time']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($entry['room'] ?? 'N/A') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <button class="edit-timetable-btn text-indigo-600 hover:text-indigo-900 mr-3" data-id="<?= $entry['id'] ?>">
                                    Edit
                                </button>
                                <button class="delete-timetable-btn text-red-600 hover:text-red-900" data-id="<?= $entry['id'] ?>">
                                    Delete
                                </button>
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

<!-- Add Timetable Modal -->
<div id="add-timetable-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Add Timetable Entry</h3>
                <button id="close-add-modal" class="text-gray-400 hover:text-gray-500">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            <div id="add-timetable-content">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<!-- Edit Timetable Modal -->
<div id="edit-timetable-modal" class="fixed inset-0 bg-gray-600 bg-opacity-50 overflow-y-auto h-full w-full hidden z-50">
    <div class="relative top-20 mx-auto p-5 border w-11/12 md:w-3/4 lg:w-1/2 shadow-lg rounded-md bg-white">
        <div class="mt-3">
            <div class="flex justify-between items-center mb-4">
                <h3 class="text-lg font-medium text-gray-900">Edit Timetable Entry</h3>
                <button id="close-edit-modal" class="text-gray-400 hover:text-gray-500">
                    <span class="text-2xl">&times;</span>
                </button>
            </div>
            <div id="edit-timetable-content">
                <!-- Content will be loaded via AJAX -->
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Add timetable button
    const addTimetableBtn = document.getElementById('add-timetable-btn');
    const addTimetableModal = document.getElementById('add-timetable-modal');
    const closeAddModal = document.getElementById('close-add-modal');
    const addTimetableContent = document.getElementById('add-timetable-content');
    
    // Edit timetable buttons
    const editTimetableBtns = document.querySelectorAll('.edit-timetable-btn');
    const editTimetableModal = document.getElementById('edit-timetable-modal');
    const closeEditModal = document.getElementById('close-edit-modal');
    const editTimetableContent = document.getElementById('edit-timetable-content');
    
    // Delete timetable buttons
    const deleteTimetableBtns = document.querySelectorAll('.delete-timetable-btn');
    
    // Filter form
    const filterForm = document.getElementById('filter-form');
    const clearFiltersBtn = document.getElementById('clear-filters');
    
    // Open add timetable modal
    if (addTimetableBtn) {
        console.log('Add timetable button found');
        addTimetableBtn.addEventListener('click', function() {
            console.log('Add timetable button clicked');
            fetch('/timetables/create', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                console.log('Received data:', data);
                if (data.html) {
                    addTimetableContent.innerHTML = data.html;
                    addTimetableModal.classList.remove('hidden');
                    // Initialize form after content is loaded
                    initializeAddForm();
                }
            })
            .catch(error => {
                console.error('Error loading add timetable form:', error);
                alert('Error loading form. Please try again.');
            });
        });
    } else {
        console.error('Add timetable button not found');
    }
    
    // Close add timetable modal
    closeAddModal.addEventListener('click', function() {
        addTimetableModal.classList.add('hidden');
    });
    
    // Close modal when clicking outside
    window.addEventListener('click', function(event) {
        if (event.target === addTimetableModal) {
            addTimetableModal.classList.add('hidden');
        }
        if (event.target === editTimetableModal) {
            editTimetableModal.classList.add('hidden');
        }
    });
    
    // Open edit timetable modal
    editTimetableBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const timetableId = this.getAttribute('data-id');
            fetch(`/timetables/${timetableId}/edit`, {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest'
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.html) {
                    editTimetableContent.innerHTML = data.html;
                    editTimetableModal.classList.remove('hidden');
                    // Initialize form after content is loaded
                    initializeEditForm();
                }
            })
            .catch(error => {
                console.error('Error loading edit timetable form:', error);
                alert('Error loading form. Please try again.');
            });
        });
    });
    
    // Close edit timetable modal
    closeEditModal.addEventListener('click', function() {
        editTimetableModal.classList.add('hidden');
    });
    
    // Delete timetable entry
    deleteTimetableBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const timetableId = this.getAttribute('data-id');
            if (confirm('Are you sure you want to delete this timetable entry?')) {
                fetch(`/timetables/${timetableId}/delete`, {
                    method: 'POST',
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest',
                        'Content-Type': 'application/x-www-form-urlencoded',
                    },
                    body: '_method=DELETE'
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Reload the page to show updated data
                        location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'Unknown error occurred'));
                    }
                })
                .catch(error => {
                    console.error('Error deleting timetable entry:', error);
                    alert('An error occurred while deleting the timetable entry.');
                });
            }
        });
    });
    
    // Filter form submission
    filterForm.addEventListener('submit', function(e) {
        e.preventDefault();
        const formData = new FormData(this);
        const params = new URLSearchParams();
        
        for (const [key, value] of formData.entries()) {
            if (value) {
                params.append(key, value);
            }
        }
        
        const url = params.toString() ? `/timetables?${params.toString()}` : '/timetables';
        
        fetch(url, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => {
            if (!response.ok) {
                throw new Error('Network response was not ok');
            }
            return response.json();
        })
        .then(data => {
            console.log('Filter response data:', data);
            if (data.html) {
                // Create a temporary element to parse the HTML
                const tempElement = document.createElement('div');
                tempElement.innerHTML = data.html;
                
                // Extract only the timetable entries table body
                const newTableBody = tempElement.querySelector('#timetable-entries');
                if (newTableBody) {
                    document.querySelector('#timetable-entries').innerHTML = newTableBody.innerHTML;
                    // Re-initialize event listeners
                    initializeEventListeners();
                }
            } else {
                console.error('No HTML content in response:', data);
                alert('Error filtering entries. Please try again.');
            }
        })
        .catch(error => {
            console.error('Error filtering timetable entries:', error);
            alert('Error filtering entries. Please try again.');
        });
    });
    
    // Clear filters
    clearFiltersBtn.addEventListener('click', function() {
        document.getElementById('filter_class_id').value = '';
        document.getElementById('filter_staff_id').value = '';
        document.getElementById('filter_subject_id').value = '';
        filterForm.dispatchEvent(new Event('submit'));
    });
    
    // Initialize add form
    function initializeAddForm() {
        const form = document.querySelector('#add-timetable-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const formData = new FormData(form);
                
                fetch('/timetables', {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        addTimetableModal.classList.add('hidden');
                        location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'Unknown error occurred'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while saving the timetable entry.');
                });
            });
        }
        
        // Add dynamic behavior for class/subject/staff selection
        initializeDynamicSelects();
    }
    
    // Initialize edit form
    function initializeEditForm() {
        const form = document.querySelector('#edit-timetable-form');
        if (form) {
            form.addEventListener('submit', function(e) {
                e.preventDefault();
                
                const timetableId = form.action.split('/').pop();
                const formData = new FormData(form);
                
                fetch(`/timetables/${timetableId}`, {
                    method: 'POST',
                    body: formData,
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        editTimetableModal.classList.add('hidden');
                        location.reload();
                    } else {
                        alert('Error: ' + (data.error || 'Unknown error occurred'));
                    }
                })
                .catch(error => {
                    console.error('Error:', error);
                    alert('An error occurred while updating the timetable entry.');
                });
            });
        }
        
        // Add dynamic behavior for class/subject/staff selection
        initializeDynamicSelects();
    }
    
    // Initialize dynamic selects for class/subject/staff
    function initializeDynamicSelects() {
        const classSelect = document.getElementById('class_id');
        const subjectSelect = document.getElementById('subject_id');
        const staffSelect = document.getElementById('staff_id');
        
        if (classSelect && subjectSelect) {
            classSelect.addEventListener('change', function() {
                const classId = this.value;
                
                // Reset subject and staff selects
                subjectSelect.innerHTML = '<option value="">Select a class first</option>';
                if (staffSelect) {
                    staffSelect.innerHTML = '<option value="">Select a subject first</option>';
                }
                
                if (classId) {
                    // Load subjects via AJAX
                    fetch(`/timetables/subjects/class/${classId}`)
                        .then(response => response.json())
                        .then(subjects => {
                            subjectSelect.innerHTML = '<option value="">Select a subject</option>';
                            subjects.forEach(subject => {
                                const option = document.createElement('option');
                                option.value = subject.id;
                                option.textContent = subject.name + ' (' + subject.code + ')';
                                subjectSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error loading subjects:', error);
                        });
                }
            });
        }
        
        if (subjectSelect && staffSelect) {
            subjectSelect.addEventListener('change', function() {
                const subjectId = this.value;
                
                // Reset staff select
                staffSelect.innerHTML = '<option value="">Select a subject first</option>';
                
                if (subjectId) {
                    // Load teachers via AJAX
                    fetch(`/timetables/teachers/subject/${subjectId}`)
                        .then(response => response.json())
                        .then(teachers => {
                            staffSelect.innerHTML = '<option value="">Select a teacher</option>';
                            teachers.forEach(teacher => {
                                const option = document.createElement('option');
                                option.value = teacher.id;
                                option.textContent = teacher.first_name + ' ' + teacher.last_name;
                                staffSelect.appendChild(option);
                            });
                        })
                        .catch(error => {
                            console.error('Error loading teachers:', error);
                        });
                }
            });
        }
    }
    
    // Re-initialize event listeners after AJAX content load
    function initializeEventListeners() {
        // Re-bind edit and delete buttons
        document.querySelectorAll('.edit-timetable-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const timetableId = this.getAttribute('data-id');
                fetch(`/timetables/${timetableId}/edit`, {
                    headers: {
                        'X-Requested-With': 'XMLHttpRequest'
                    }
                })
                .then(response => response.json())
                .then(data => {
                    if (data.html) {
                        editTimetableContent.innerHTML = data.html;
                        editTimetableModal.classList.remove('hidden');
                        initializeEditForm();
                    }
                })
                .catch(error => {
                    console.error('Error loading edit timetable form:', error);
                    alert('Error loading form. Please try again.');
                });
            });
        });
        
        document.querySelectorAll('.delete-timetable-btn').forEach(btn => {
            btn.addEventListener('click', function() {
                const timetableId = this.getAttribute('data-id');
                if (confirm('Are you sure you want to delete this timetable entry?')) {
                    fetch(`/timetables/${timetableId}/delete`, {
                        method: 'POST',
                        headers: {
                            'X-Requested-With': 'XMLHttpRequest',
                            'Content-Type': 'application/x-www-form-urlencoded',
                        },
                        body: '_method=DELETE'
                    })
                    .then(response => response.json())
                    .then(data => {
                        if (data.success) {
                            location.reload();
                        } else {
                            alert('Error: ' + (data.error || 'Unknown error occurred'));
                        }
                    })
                    .catch(error => {
                        console.error('Error deleting timetable entry:', error);
                        alert('An error occurred while deleting the timetable entry.');
                    });
                }
            });
        });
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>