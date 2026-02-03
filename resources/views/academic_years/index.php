<?php 
$title = 'Academic Years'; 
ob_start(); 
?>

<!-- Air Datepicker Assets -->
<link href="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.5/air-datepicker.min.css" rel="stylesheet">
<script src="https://cdn.jsdelivr.net/npm/air-datepicker@3.3.5/air-datepicker.min.js"></script>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Academic Years</h1>
            <button type="button" id="openAddModal" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                Add Academic Year
            </button>
        </div>



        <!-- Academic Years Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Academic Year List</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">A list of all academic years in the system.</p>
            </div>
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Year
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Term
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Start Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                End Date
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Status
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Current
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($academicYears)): ?>
                        <tr>
                            <td colspan="8" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No academic years found.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($academicYears as $year): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= htmlspecialchars($year['name']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php 
                                // Extract year from the academic year name (e.g., "2025-2026" -> 2025)
                                $yearParts = explode('-', $year['name']);
                                echo htmlspecialchars($yearParts[0]);
                                ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($year['term'] ?? 'Not set') ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($year['start_date']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($year['end_date']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                    <?= $year['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                                       ($year['status'] === 'completed' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') ?>">
                                    <?= ucfirst(htmlspecialchars($year['status'])) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php if ($year['is_current'] == 1): ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                    Current
                                </span>
                                <?php else: ?>
                                <form action="/academic_years/<?= $year['id'] ?>/set_current" method="POST" class="inline">
                                    <button type="submit" class="text-indigo-600 hover:text-indigo-900 text-xs font-medium">
                                        Set as Current
                                    </button>
                                </form>
                                <?php endif; ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <div class="flex items-center justify-end space-x-3">
                                    <?php if ($year['status'] !== 'completed'): ?>
                                    <form action="/academic_years/<?= $year['id'] ?>/update_status" method="POST" class="inline-flex" onsubmit="return confirm('Are you sure you want to mark academic year <?= $year['name'] ?> as COMPLETED?\n\nThis will make all data immutable. This action can only be reversed by a Super Admin.');">
                                        <input type="hidden" name="status" value="completed">
                                        <button type="submit" class="text-green-600 hover:text-green-900 text-xs font-medium">
                                            Complete
                                        </button>
                                    </form>
                                    <?php else: ?>
                                    <span class="text-gray-500 text-xs font-medium cursor-not-allowed">
                                        Completed
                                    </span>
                                    <?php endif; ?>
                                    <button 
                                        type="button"
                                        class="text-indigo-600 hover:text-indigo-900 edit-year-btn"
                                        data-id="<?= $year['id'] ?>"
                                        data-name="<?= htmlspecialchars($year['name']) ?>"
                                        data-year="<?= explode('-', $year['name'])[0] ?>"
                                        data-start="<?= $year['start_date'] ?>"
                                        data-end="<?= $year['end_date'] ?>"
                                        data-status="<?= $year['status'] ?>"
                                    >
                                        Edit
                                    </button>
                                    <?php if ($year['is_current'] != 1): ?>
                                    <a href="/academic_years/<?= $year['id'] ?>/delete" 
                                       class="text-red-600 hover:text-red-900"
                                       onclick="return confirm('Are you sure you want to delete this academic year?')">
                                        Delete
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Current Academic Year Term Selection -->
        <?php 
        $currentAcademicYear = null;
        foreach ($academicYears as $year) {
            if ($year['is_current'] == 1) {
                $currentAcademicYear = $year;
                break;
            }
        }
        ?>
        
        <?php if ($currentAcademicYear): ?>
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mt-6">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Current Academic Year Term</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Set the current term for the active academic year: <strong><?= htmlspecialchars($currentAcademicYear['name']) ?></strong></p>
            </div>
            <div class="border-t border-gray-200">
                <form action="/academic_years/update_term" method="POST" class="px-4 py-5 sm:p-6" id="termForm">
                    <input type="hidden" name="academic_year_id" value="<?= $currentAcademicYear['id'] ?>">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-6">
                            <label for="term" class="block text-sm font-medium text-gray-700">Current Term</label>
                            <div class="mt-1">
                                <select name="term" id="term" class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    <option value="1st Term" <?= ($currentAcademicYear['term'] ?? '') === '1st Term' ? 'selected' : '' ?>>1st Term</option>
                                    <option value="2nd Term" <?= ($currentAcademicYear['term'] ?? '') === '2nd Term' ? 'selected' : '' ?>>2nd Term</option>
                                    <option value="3rd Term" <?= ($currentAcademicYear['term'] ?? '') === '3rd Term' ? 'selected' : '' ?>>3rd Term</option>
                                    <option value="other">Other (specify below)</option>
                                </select>
                                <input type="text" id="custom_term" name="custom_term" placeholder="Enter custom term" 
                                    class="mt-2 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"
                                    <?= !in_array($currentAcademicYear['term'] ?? '', ['1st Term', '2nd Term', '3rd Term']) && !empty($currentAcademicYear['term']) ? 'value="' . htmlspecialchars($currentAcademicYear['term']) . '"' : 'style="display: none;"' ?>>
                            </div>
                            <p class="mt-2 text-sm text-gray-500">Select a term or enter a custom term name</p>
                        </div>
                    </div>
                    <div class="mt-6 flex items-center justify-end">
                        <button type="submit"
                            class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Term
                        </button>
                    </div>
                </form>
            </div>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Add Academic Year Modal -->
<div id="addAcademicYearModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" id="modalOverlay"></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Add Academic Year
                        </h3>
                        <div class="mt-2">
                            <form action="/academic_years" method="POST" id="academicYearForm">
                                <div class="grid grid-cols-1 gap-y-4">
                                    <div>
                                        <label for="year" class="block text-sm font-medium text-gray-700">Year</label>
                                        <div class="relative mt-1">
                                            <input type="text" name="year" id="year" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Select Year" data-language="en" autocomplete="off">
                                            <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                                <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                                                </svg>
                                            </div>
                                        </div>
                                    </div>

                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Name</label>
                                        <input type="text" name="name" id="name" required readonly class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md bg-gray-50">
                                    </div>

                                    <div>
                                        <label for="start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                        <input type="date" name="start_date" id="start_date" required class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>

                                    <div>
                                        <label for="end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                        <input type="date" name="end_date" id="end_date" required class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>

                                    <div>
                                        <label for="status" class="block text-sm font-medium text-gray-700">Status</label>
                                        <select name="status" id="status" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                </div>


                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="document.getElementById('academicYearForm').submit()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Save
                </button>
                <button type="button" id="closeAddModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<!-- Edit Academic Year Modal -->
<div id="editAcademicYearModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" id="editModalOverlay"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="edit-modal-title">
                            Edit Academic Year
                        </h3>
                        <div class="mt-2">
                            <form action="" method="POST" id="editAcademicYearForm">
                                <input type="hidden" name="_method" value="PUT">
                                <div class="grid grid-cols-1 gap-y-4">
                                    <div>
                                        <label for="edit_year" class="block text-sm font-medium text-gray-700">Year</label>
                                        <div class="relative mt-1">
                                            <input type="text" name="year" id="edit_year" required class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md" placeholder="Select Year" data-language="en" autocomplete="off">
                                        </div>
                                    </div>

                                    <div>
                                        <label for="edit_name" class="block text-sm font-medium text-gray-700">Name</label>
                                        <input type="text" name="name" id="edit_name" required readonly class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md bg-gray-50">
                                    </div>

                                    <div>
                                        <label for="edit_start_date" class="block text-sm font-medium text-gray-700">Start Date</label>
                                        <input type="date" name="start_date" id="edit_start_date" required class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>

                                    <div>
                                        <label for="edit_end_date" class="block text-sm font-medium text-gray-700">End Date</label>
                                        <input type="date" name="end_date" id="edit_end_date" required class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>

                                    <div>
                                        <label for="edit_status" class="block text-sm font-medium text-gray-700">Status</label>
                                        <select name="status" id="edit_status" class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="active">Active</option>
                                            <option value="inactive">Inactive</option>
                                            <option value="completed">Completed</option>
                                        </select>
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="document.getElementById('editAcademicYearForm').submit()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Update
                </button>
                <button type="button" id="closeEditModal" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>


<script>
document.addEventListener('DOMContentLoaded', function() {
    // Modal Logic
    const openAddModalBtn = document.getElementById('openAddModal');
    const closeAddModalBtn = document.getElementById('closeAddModal');
    const modalOverlay = document.getElementById('modalOverlay'); 
    const addModal = document.getElementById('addAcademicYearModal');

    function openModal() {
        addModal.classList.remove('hidden');
    }

    function closeModal() {
        addModal.classList.add('hidden');
    }

    if(openAddModalBtn) openAddModalBtn.addEventListener('click', openModal);
    if(closeAddModalBtn) closeAddModalBtn.addEventListener('click', closeModal);
    if(modalOverlay) modalOverlay.addEventListener('click', closeModal);


    // Add Academic Year Form Logic
    const yearInput = document.getElementById('year');
    const nameInput = document.getElementById('name');
    const startDateInput = document.getElementById('start_date');
    const endDateInput = document.getElementById('end_date');
    const currentYear = new Date().getFullYear();

    // Initialize Air Datepicker
    if (yearInput) {
        new AirDatepicker('#year', {
            view: 'years',
            minView: 'years',
            dateFormat: 'yyyy',
            onSelect: function({date, formattedDate, datepicker}) {
                if (!date) return;
                
                const selectedYear = date.getFullYear();
                updateAcademicYearInfo(selectedYear);

                // If user is changing from current year, show warning (optional, logic adapted from previous)
                if (selectedYear !== currentYear) {
                    // Logic to warn user if needed, but the visual picker makes it clear
                }
            }
        });
        
        // Set initial value
        yearInput.value = currentYear;
        updateAcademicYearInfo(currentYear);
    }

    // Function to update the name and dates based on selected year
    function updateAcademicYearInfo(selectedYear) {
        if(!selectedYear) return;
        const nextYear = selectedYear + 1;
        
        // Update name field
        if(nameInput) nameInput.value = selectedYear + '-' + nextYear;
        
        // Update dates (assuming academic year starts in September and ends in June)
        if(startDateInput) startDateInput.value = selectedYear + '-09-01';
        if(endDateInput) endDateInput.value = nextYear + '-06-30';
    }

    // Existing Term Update Logic
    const termSelect = document.getElementById('term');
    const customTermInput = document.getElementById('custom_term');
    const termForm = document.getElementById('termForm');
    const currentTerm = '<?= $currentAcademicYear['term'] ?? '' ?>';
    
    if (termSelect && customTermInput) {
        // Show custom term input if "Other" is selected or if there's a custom term
        function toggleCustomTermInput() {
            if (termSelect.value === 'other' || 
                (!['1st Term', '2nd Term', '3rd Term'].includes(currentTerm) && currentTerm !== '')) {
                customTermInput.style.display = 'block';
                if (!['1st Term', '2nd Term', '3rd Term'].includes(currentTerm) && currentTerm !== '') {
                    customTermInput.value = currentTerm;
                }
            } else {
                customTermInput.style.display = 'none';
            }
        }
        
        // Initialize the custom term input visibility
        toggleCustomTermInput();
        
        // Add event listener for term selection change
        termSelect.addEventListener('change', function() {
            if (this.value === 'other') {
                customTermInput.style.display = 'block';
                customTermInput.value = '';
                customTermInput.focus();
            } else {
                customTermInput.style.display = 'none';
            }
        });
    }
    
    // Handle form submission
    if (termForm) {
        termForm.addEventListener('submit', function(e) {
            let selectedTerm = '';
            
            // If "Other" is selected, use the custom term value
            if (termSelect.value === 'other') {
                if (customTermInput.value.trim() === '') {
                    alert('Please enter a custom term name.');
                    e.preventDefault();
                    return;
                }
                selectedTerm = customTermInput.value.trim();
            } else {
                selectedTerm = termSelect.value;
            }
            
            // Add confirmation when changing term
            if (selectedTerm !== currentTerm) {
                const confirmChange = confirm(`You are about to change the current term from "${currentTerm || 'Not set'}" to "${selectedTerm}". This will affect all data entry forms. Are you sure you want to proceed?`);
                if (!confirmChange) {
                    e.preventDefault();
                    return;
                }
            }
            
            // Update the form to submit the correct term value
            if (termSelect.value === 'other') {
                // Create a hidden input to submit the custom term
                let hiddenInput = document.querySelector('input[name="term"][type="hidden"]');
                if (!hiddenInput) {
                    hiddenInput = document.createElement('input');
                    hiddenInput.type = 'hidden';
                    hiddenInput.name = 'term';
                    termForm.appendChild(hiddenInput);
                }
                hiddenInput.value = customTermInput.value.trim();
            }
        });
    }
    // Edit Modal Logic
    const editModal = document.getElementById('editAcademicYearModal');
    const editModalOverlay = document.getElementById('editModalOverlay');
    const closeEditModalBtn = document.getElementById('closeEditModal');
    const editForm = document.getElementById('editAcademicYearForm');
    const editBtns = document.querySelectorAll('.edit-year-btn');
    
    // Edit Form Inputs
    const editYearInput = document.getElementById('edit_year');
    const editNameInput = document.getElementById('edit_name');
    const editStartDateInput = document.getElementById('edit_start_date');
    const editEndDateInput = document.getElementById('edit_end_date');
    const editStatusSelect = document.getElementById('edit_status');

    function openEditModal() {
        editModal.classList.remove('hidden');
    }

    function closeEditModal() {
        editModal.classList.add('hidden');
    }

    if(closeEditModalBtn) closeEditModalBtn.addEventListener('click', closeEditModal);
    if(editModalOverlay) editModalOverlay.addEventListener('click', closeEditModal);

    // Initialize Air Datepicker for Edit Modal
    if (editYearInput) {
        new AirDatepicker('#edit_year', {
            view: 'years',
            minView: 'years',
            dateFormat: 'yyyy',
            onSelect: function({date, formattedDate, datepicker}) {
                if (!date) return;
                const selectedYear = date.getFullYear();
                updateEditAcademicYearInfo(selectedYear);
            }
        });
    }

    function updateEditAcademicYearInfo(selectedYear) {
        if(!selectedYear) return;
        const nextYear = selectedYear + 1;
        if(editNameInput) editNameInput.value = selectedYear + '-' + nextYear;
        // We don't auto-update start/end dates in edit mode to preserve custom dates unless user manually changes year, currently logic updates it
        // A better UX might be ONLY update if year changes significantly, but simpler is consistent with Add
        if(editStartDateInput) editStartDateInput.value = selectedYear + '-09-01';
        if(editEndDateInput) editEndDateInput.value = nextYear + '-06-30';
    }

    editBtns.forEach(btn => {
        btn.addEventListener('click', function() {
            const id = this.getAttribute('data-id');
            const name = this.getAttribute('data-name');
            const yearStr = this.getAttribute('data-year');
            const start = this.getAttribute('data-start');
            const end = this.getAttribute('data-end');
            const status = this.getAttribute('data-status');

            // Populate Form
            editForm.action = `/academic_years/${id}`; // This submits as POST, but _method=PUT handles it
            
            if(editYearInput) {
                editYearInput.value = yearStr;
                // Update datepicker instance if needed (AirDatepicker might need manual update if initialized)
                // Assuming simple value set works or re-init might be needed if visual calendar doesn't sync
            }
            if(editNameInput) editNameInput.value = name;
            if(editStartDateInput) editStartDateInput.value = start;
            if(editEndDateInput) editEndDateInput.value = end;
            if(editStatusSelect) editStatusSelect.value = status;

            openEditModal();
        });
    });
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>