<?php 
$title = 'Academic Analytics'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex flex-col md:flex-row justify-between items-center mb-6">
            <h1 class="text-3xl font-bold text-gray-900 tracking-tight">Academic Analytics</h1>
            <div class="flex space-x-3 mt-4 md:mt-0">
                <a href="/reports" class="bg-indigo-600 text-white px-5 py-2 rounded-md font-medium hover:bg-indigo-700 shadow-sm transition-colors">
                    Back to Reports
                </a>
            </div>
        </div>

        <!-- Filter Controls -->
        <div class="bg-white shadow-md rounded-lg mb-8 border border-gray-200">
            <div class="px-6 py-4 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                <h3 class="text-lg font-medium text-gray-900">Advanced Filters</h3>
                <button onclick="resetFilters()" class="text-sm text-indigo-600 hover:text-indigo-900 font-medium">Reset All</button>
            </div>
            <div class="p-6">
                <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-5 gap-6">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Academic Year</label>
                        <select id="filter-year" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="loadAnalytics()">
                            <option value="">All Years</option>
                            <?php foreach ($filters['academic_years'] as $year): ?>
                                <option value="<?= $year['id'] ?>"><?= htmlspecialchars($year['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Term</label>
                        <select id="filter-term" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="loadAnalytics()">
                            <option value="">All Terms</option>
                            <?php foreach ($filters['terms'] as $term): ?>
                                <option value="<?= htmlspecialchars($term) ?>"><?= htmlspecialchars($term) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Class</label>
                        <select id="filter-class" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="loadAnalytics()">
                            <option value="">All Classes</option>
                            <?php foreach ($filters['classes'] as $class): ?>
                                <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name'] . ' ' . $class['level']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Subject</label>
                        <select id="filter-subject" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="loadAnalytics()">
                            <option value="">All Subjects</option>
                            <?php foreach ($filters['subjects'] as $subject): ?>
                                <option value="<?= $subject['id'] ?>"><?= htmlspecialchars($subject['name']) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Specific Exam</label>
                        <select id="filter-exam" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="loadAnalytics()">
                            <option value="">All Exams</option>
                            <?php foreach ($filters['exams'] as $exam): ?>
                                <?php 
                                    $label = $exam['name'];
                                    if (!empty($exam['date'])) {
                                        $label .= ' (' . date('M j, Y', strtotime($exam['date'])) . ')';
                                    }
                                    if (!empty($exam['description'])) {
                                        $desc = $exam['description'];
                                        if (strlen($desc) > 50) $desc = substr($desc, 0, 50) . '...';
                                        $label .= ' - ' . $desc;
                                    }
                                ?>
                                <option value="<?= $exam['id'] ?>"><?= htmlspecialchars($label) ?></option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    </div>
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-1">Per Page</label>
                        <select id="filter-per-page" class="mt-1 block w-full rounded-md border-gray-300 shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50" onchange="loadAnalytics(1)">
                            <option value="10">10</option>
                            <option value="25">25</option>
                            <option value="50">50</option>
                            <option value="100">100</option>
                        </select>
                    </div>
            </div>
        </div>

        <!-- Analytics Tabs -->
        <div class="mb-4">
            <nav class="flex space-x-4" aria-label="Tabs">
                <button onclick="switchTab('ranking')" id="tab-ranking" class="bg-indigo-100 text-indigo-700 px-3 py-2 font-medium text-sm rounded-md transition-colors hover:bg-indigo-200">
                    Performance Rankings
                </button>
                <button onclick="switchTab('exams')" id="tab-exams" class="text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm rounded-md transition-colors hover:bg-gray-100">
                    Exams
                </button>
            </nav>
        </div>

        <!-- Ranking View -->
        <div id="view-ranking" class="block">
            <div class="bg-white shadow overflow-hidden rounded-lg border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50 flex justify-between items-center">
                    <div>
                        <h3 class="text-lg leading-6 font-medium text-gray-900">Ranked Student Performance</h3>
                        <p class="mt-1 text-sm text-gray-500">Ranking based on raw score aggregates for selected filters.</p>
                    </div>
                    <div class="flex space-x-2">
                        <button onclick="exportRanking('csv')" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Export CSV
                        </button>
                        <button onclick="exportRanking('print')" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Print Table
                        </button>
                    </div>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Rank</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Subjects</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Total Score</th>
                                <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Average</th>
                            </tr>
                        </thead>
                        <tbody id="ranking-table-body" class="bg-white divide-y divide-gray-200">
                            <!-- Dynamic Content -->
                            <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading data...</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Exams View -->
        <div id="view-exams" class="hidden">
            <div class="bg-white shadow rounded-lg border border-gray-200">
                <div class="px-6 py-5 border-b border-gray-200 bg-gray-50">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Exams List</h3>
                    <p class="mt-1 text-sm text-gray-500">List of exams with performance summaries.</p>
                </div>
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Exam Name</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Academic Year</th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Term</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Avg Score</th>
                                <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Candidates</th>
                                <th scope="col" class="relative px-6 py-3">
                                    <span class="sr-only">Actions</span>
                                </th>
                            </tr>
                        </thead>
                        <tbody id="exams-table-body" class="bg-white divide-y divide-gray-200">
                            <!-- Populated by JS -->
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>
    </div>

    <!-- Pagination Controls -->
    <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6 rounded-lg shadow-md mt-4" id="pagination-container">
        <!-- Populated by JS -->
    </div>
</div>

<!-- Exam Details Modal -->
<div id="exam-details-modal" class="fixed z-10 inset-0 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeExamModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-6xl sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <div class="flex justify-between items-start">
                            <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Exam Details</h3>
                            <button onclick="closeExamModal()" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                <span class="sr-only">Close</span>
                                <svg class="h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                                </svg>
                            </button>
                        </div>
                        <div class="mt-2 text-sm text-gray-500" id="modal-exam-info">
                            <!-- Populated JS -->
                        </div>
                        
                        <div class="mt-4 flex justify-between items-center bg-gray-50 p-3 rounded-md">
                            <div class="flex items-center space-x-4">
                                <div class="flex items-center">
                                    <input id="show-subjects" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" onchange="toggleColumn('col-subjects')">
                                    <label for="show-subjects" class="ml-2 block text-sm text-gray-900">
                                        Subjects
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="show-total" type="checkbox" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" onchange="toggleColumn('col-total')">
                                    <label for="show-total" class="ml-2 block text-sm text-gray-900">
                                        Total
                                    </label>
                                </div>
                                <div class="flex items-center">
                                    <input id="show-avg" type="checkbox" checked class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded" onchange="toggleColumn('col-avg')">
                                    <label for="show-avg" class="ml-2 block text-sm text-gray-900">
                                        Avg
                                    </label>
                                </div>
                            </div>
                            <div class="flex space-x-2">
                                <button onclick="exportExamDetailsCSV()" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                                    <svg class="h-4 w-4 mr-1 text-green-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Export CSV
                                </button>
                                <button onclick="printExamDetails()" class="inline-flex items-center px-3 py-1.5 border border-gray-300 shadow-sm text-xs font-medium rounded text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                    <svg class="h-4 w-4 mr-1 text-gray-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Print Results
                                </button>
                            </div>
                        </div>

                        <div id="print-area" class="mt-4 overflow-x-auto max-h-[70vh]">
                            <table class="min-w-full divide-y divide-gray-200" id="exam-details-table">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-16">Rank</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider w-24">Class</th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider hidden col-subjects">Subjects</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-24 col-total">Total</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-24 col-avg">Avg</th>
                                        <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider w-20 action-col">Action</th>
                                    </tr>
                                </thead>
                                <tbody id="modal-details-body" class="bg-white divide-y divide-gray-200">
                                    <!-- Populated by JS -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="closeExamModal()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none sm:ml-3 sm:w-auto sm:text-sm">
                    Close
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    // Injected variables
    const schoolName = "<?php echo isset($school_name) ? addslashes($school_name) : 'School Name'; ?>";
    const schoolLogo = "<?php echo isset($school_logo) ? addslashes($school_logo) : ''; ?>";
    
    // ... rest of script ...

let currentTab = 'ranking';
let currentPage = 1;
let currentPerPage = 10;

document.addEventListener('DOMContentLoaded', () => {
    // Initial load
    loadAnalytics();
    
    // Add event listeners for dynamic filtering interaction
    ['filter-year', 'filter-term', 'filter-class', 'filter-subject', 'filter-exam'].forEach(id => {
        document.getElementById(id).addEventListener('change', (e) => {
            updateFilterOptions(e.target.id);
            loadAnalytics(1); // Reset to page 1 on filter change
        });
    });
});

function switchTab(tab) {
    currentTab = tab;
    
    // Update Ranking Tab Style
    document.getElementById('tab-ranking').className = tab === 'ranking' 
        ? 'bg-indigo-100 text-indigo-700 px-3 py-2 font-medium text-sm rounded-md transition-colors hover:bg-indigo-200' 
        : 'text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm rounded-md transition-colors hover:bg-gray-100';
    
    // Update Exams Tab Style
    document.getElementById('tab-exams').className = tab === 'exams' 
        ? 'bg-indigo-100 text-indigo-700 px-3 py-2 font-medium text-sm rounded-md transition-colors hover:bg-indigo-200' 
        : 'text-gray-500 hover:text-gray-700 px-3 py-2 font-medium text-sm rounded-md transition-colors hover:bg-gray-100';

    // Toggle Views
    document.getElementById('view-ranking').classList.toggle('hidden', tab !== 'ranking');
    document.getElementById('view-exams').classList.toggle('hidden', tab !== 'exams');
    
    document.getElementById('view-ranking').classList.toggle('hidden', tab !== 'ranking');
    document.getElementById('view-exams').classList.toggle('hidden', tab !== 'exams');
    
    loadAnalytics(1); // Reset to page 1 when switching tabs
}

function resetFilters() {
    document.getElementById('filter-year').value = '';
    document.getElementById('filter-term').value = '';
    document.getElementById('filter-class').value = '';
    document.getElementById('filter-subject').value = '';
    document.getElementById('filter-exam').value = '';
    updateFilterOptions(); // Reset options
    document.getElementById('filter-year').value = '';
    document.getElementById('filter-term').value = '';
    document.getElementById('filter-class').value = '';
    document.getElementById('filter-subject').value = '';
    document.getElementById('filter-exam').value = '';
    updateFilterOptions(); // Reset options
    loadAnalytics(1);
}

/**
 * Fetch available options based on current selection
 * @param {string} changedId - The ID of the filter that changed
 */
function updateFilterOptions(changedId = null) {
    const filters = {
        academic_year_id: document.getElementById('filter-year').value,
        term: document.getElementById('filter-term').value,
        class_id: document.getElementById('filter-class').value,
        subject_id: document.getElementById('filter-subject').value,
        exam_id: document.getElementById('filter-exam').value
    };

    const params = new URLSearchParams(filters).toString();

    fetch(`/reports/analytics-filter-options?${params}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(data => {
        // Helper to update select options
        const updateSelect = (id, options, labelKey = 'name', valueKey = 'id') => {
            const select = document.getElementById(id);
            const currentValue = select.value;
            
            // Don't update the dropdown that triggered the change (to keep focus/selection stable)
            // Unless it's a reset or broad change, but usually we want to restrict *other* dropdowns.
            // Actually, if we restrict others, we should let them update.
            if (id === changedId && currentValue) return;

            // Save current value to restore if valid
            const savedValue = select.value;

            // Clear options except default
            select.innerHTML = select.options[0].outerHTML;

            options.forEach(opt => {
                const option = document.createElement('option');
                // Handle simple array (terms) vs object array
                if (typeof opt === 'string' || typeof opt === 'number') {
                    option.value = opt;
                    option.textContent = opt;
                } else if (labelKey === 'complex_exam') {
                    option.value = opt[valueKey];
                    // Format: Name (Date) - Description
                    let label = opt.name;
                    if (opt.date) {
                         // Simple date formatting if needed, or just use raw if passing YYYY-MM-DD
                         label += ` (${opt.date})`; 
                    }
                    if (opt.description) {
                        let desc = opt.description;
                        if (desc.length > 50) desc = desc.substring(0, 50) + '...';
                        label += ` - ${desc}`;
                    }
                    option.textContent = label;
                } else {
                    option.value = opt[valueKey];
                    option.textContent = labelKey === 'complex_name' ? (opt.name + ' ' + (opt.level || '')) : opt[labelKey];
                }
                select.appendChild(option);
            });

            // Restore selection if it still exists in new options, otherwise it effectively resets (which is correct behavior for invalid combos)
            // But we need to check if the option exists
            if (savedValue && Array.from(select.options).some(o => o.value == savedValue)) {
                select.value = savedValue;
            } else {
                select.value = ""; // Reset if invalid
            }
        };

        // Update specific dropdowns
        if (data.classes) updateSelect('filter-class', data.classes, 'complex_name');
        if (data.subjects) updateSelect('filter-subject', data.subjects);
        if (data.exams) updateSelect('filter-exam', data.exams, 'complex_exam');
        // We can also update terms and years if needed, but usually those are top-level
        if (data.terms) updateSelect('filter-term', data.terms);
        // if (data.academic_years) updateSelect('filter-year', data.academic_years); 
    })
    .catch(console.error);
}

function exportRanking(type) {
    const filters = {
        academic_year_id: document.getElementById('filter-year').value,
        term: document.getElementById('filter-term').value,
        class_id: document.getElementById('filter-class').value,
        subject_id: document.getElementById('filter-subject').value,
        exam_id: document.getElementById('filter-exam').value
    };
    
    // Add export specific params
    if (type === 'print') {
        filters.print = 1;
    }
    
    const params = new URLSearchParams(filters).toString();
    
    // Redirect to export URL
    window.location.href = `/reports/export/analytics_ranking?${params}`;
}

function loadAnalytics(page = 1) {
    currentPage = page;
    currentPerPage = document.getElementById('filter-per-page').value;

    const filters = {
        academic_year_id: document.getElementById('filter-year').value,
        term: document.getElementById('filter-term').value,
        class_id: document.getElementById('filter-class').value,
        subject_id: document.getElementById('filter-subject').value,
        exam_id: document.getElementById('filter-exam').value,
        type: currentTab === 'exams' ? 'trend' : currentTab, 
        dimension: 'exam',
        page: currentPage,
        per_page: currentPerPage
    };
    
    const tbody = currentTab === 'ranking' ? document.getElementById('ranking-table-body') : document.getElementById('exams-table-body');
    tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500"><svg class="animate-spin h-5 w-5 mr-3 inline-block text-indigo-500" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"><circle class="opacity-25" cx="12" cy="12" r="10" stroke="currentColor" stroke-width="4"></circle><path class="opacity-75" fill="currentColor" d="M4 12a8 8 0 018-8V0C5.373 0 0 5.373 0 12h4zm2 5.291A7.962 7.962 0 014 12H0c0 3.042 1.135 5.824 3 7.938l3-2.647z"></path></svg>Updating data...</td></tr>';

    const params = new URLSearchParams(filters).toString();

    fetch(`/reports/analytics-data?${params}`, {
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(result => {
        if (!result.success) {
            tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error: ${result.message}</td></tr>`;
            return;
        }

        if (currentTab === 'ranking') {
            renderRankingTable(result.data.data);
        } else {
            renderExamsView(result.data.data);
        }
        renderPagination(result.data);
    })
    .catch(error => {
        console.error('Error:', error);
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Failed to load data. Please try again.</td></tr>';
    });
}

function renderRankingTable(data) {
    const tbody = document.getElementById('ranking-table-body');
    
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No records found for the selected criteria.</td></tr>';
        return;
    }

    let html = '';
    // Calculate offset based on current page to show correct rank
    const offset = (currentPage - 1) * currentPerPage;
    
    data.forEach((row, index) => {
        const rank = offset + index + 1;
        let rankBadge = `<span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-100 text-xs font-medium text-gray-800">${rank}</span>`;
        
        if (rank === 1) rankBadge = `<span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-yellow-100 text-xs font-bold text-yellow-800">🥇</span>`;
        if (rank === 2) rankBadge = `<span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-gray-100 text-xs font-bold text-gray-600">🥈</span>`;
        if (rank === 3) rankBadge = `<span class="inline-flex items-center justify-center h-6 w-6 rounded-full bg-orange-100 text-xs font-bold text-orange-800">🥉</span>`;

        html += `
            <tr class="hover:bg-gray-50 transition-colors">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${rankBadge}</td>
                <td class="px-6 py-4 whitespace-nowrap">
                    <div class="text-sm font-medium text-indigo-600">${row.first_name} ${row.last_name}</div>
                    <div class="text-xs text-gray-500">${row.admission_no}</div>
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.class_name || 'N/A'}</td>
                <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate" title="${row.subjects_taken}">
                    ${row.subjects_count} Subjects
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-bold text-gray-900 text-right">${parseFloat(row.total_score).toFixed(1)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 text-right">${parseFloat(row.average_score).toFixed(1)}</td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

function renderExamsView(data) {
    const tbody = document.getElementById('exams-table-body');
    if (data.length === 0) {
        tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No exams found for the selected filters.</td></tr>';
        return;
    }

    let html = '';
    data.forEach(row => {
        // row.academic_year, row.term, row.class_names from updated model
        const meta = JSON.stringify({
            year: row.academic_year || 'N/A',
            term: row.term || 'N/A',
            class: row.class_names || 'All Classes'
        }).replace(/"/g, "&quot;");

        html += `
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-indigo-600">${row.label}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">${row.academic_year || 'N/A'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${row.term || 'N/A'}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-gray-900">${parseFloat(row.average_score).toFixed(1)}</td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-right text-gray-500">${row.student_count}</td>
                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                    <button onclick="openExamDetails('${row.exam_ids}', '${row.label.replace(/'/g, "\\'")}', ${meta})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded-md transition-colors">
                        View Details
                    </button>
                </td>
            </tr>
        `;
    });
    tbody.innerHTML = html;
}

// Store current exam modal data globally for export
let currentExamData = [];
let currentExamMeta = {};
let currentExamIds = null;

function openExamDetails(examIds, examName, meta = {}) {
    const modal = document.getElementById('exam-details-modal');
    const title = document.getElementById('modal-title');
    const info = document.getElementById('modal-exam-info');
    const tbody = document.getElementById('modal-details-body');
    
    currentExamMeta = { name: examName, ...meta };
    currentExamIds = examIds;
    
    // Reset Checkboxes
    document.getElementById('show-subjects').checked = false;
    document.getElementById('show-total').checked = true;
    document.getElementById('show-avg').checked = true;
    
    // Initial toggle to set correct visibility
    toggleColumn('col-subjects'); 
    toggleColumn('col-total'); 
    toggleColumn('col-avg'); 

    title.textContent = "Exam Details: " + examName;
    // Format Header Info
    const infoParts = [];
    if(meta.year) infoParts.push(`<span class="font-medium text-gray-900">Year:</span> ${meta.year}`);
    if(meta.term) infoParts.push(`<span class="font-medium text-gray-900">Term:</span> ${meta.term}`);
    if(meta.class) infoParts.push(`<span class="font-medium text-gray-900">Classes:</span> ${meta.class}`);
    info.innerHTML = infoParts.join(' <span class="mx-2 text-gray-300">|</span> ');

    tbody.innerHTML = '<tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">Loading...</td></tr>';
    
    modal.classList.remove('hidden');

    const filters = {
        type: 'ranking',
        exam_id: examIds, // Now passing comma-separated IDs
        academic_year_id: document.getElementById('filter-year').value,
        term: document.getElementById('filter-term').value, 
        class_id: document.getElementById('filter-class').value,
        subject_id: document.getElementById('filter-subject').value,
        per_page: -1 // Get all records for the modal
    };
    
    const params = new URLSearchParams(filters).toString();
    fetch(`/reports/analytics-data?${params}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(response => response.json())
    .then(result => {
        if (!result.success) {
            tbody.innerHTML = `<tr><td colspan="6" class="px-6 py-4 text-center text-red-500">Error: ${result.message}</td></tr>`;
            return;
        }
        
        // Result structure is { success: true, data: { data: [...], total: ... } }
        const responseData = result.data;
        currentExamData = responseData.data || []; // Handle case where it might be empty or direct array (fallback)
        const data = currentExamData;

        let html = '';
        data.forEach((row, index) => {
           let grade = 'F';
           const score = parseFloat(row.average_score);
           if(score >= 80) grade = 'A';
           else if(score >= 70) grade = 'B';
           else if(score >= 60) grade = 'C';
           else if(score >= 50) grade = 'D';
           else if(score >= 40) grade = 'E';

           const rank = index + 1;
           const subjects = row.subjects_taken || '-';

           html += `
                <tr>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">#${rank}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">${row.first_name} ${row.last_name}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">${row.class_name || 'N/A'}</td>
                    <td class="px-6 py-4 text-sm text-gray-500 hidden col-subjects max-w-sm break-words">${subjects}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-medium text-gray-900 col-total">${parseFloat(row.total_score).toFixed(1)}</td>
                    <td class="px-6 py-4 whitespace-nowrap text-sm text-right font-bold text-indigo-600 col-avg">
                        ${parseFloat(row.average_score).toFixed(1)}
                        <span class="ml-2 inline-flex items-center px-2 py-0.5 rounded text-xs font-medium bg-gray-100 text-gray-800">${grade}</span>
                    </td>
                    <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium action-col">
                        <button onclick="printStudentReport(${row.student_id})" class="text-indigo-600 hover:text-indigo-900 bg-indigo-50 hover:bg-indigo-100 px-3 py-1 rounded-md transition-colors text-xs">
                            Print
                        </button>
                    </td>
                </tr>
           `; 
        });
        tbody.innerHTML = html;
        
        // Re-apply visibility after data load (since new rows are added)
        toggleColumn('col-subjects');
        toggleColumn('col-total');
        toggleColumn('col-avg');
    })
    .catch(err => {
        console.error(err);
        tbody.innerHTML = '<tr><td colspan="7" class="px-6 py-4 text-center text-red-500">Failed to load details.</td></tr>';
    });
}

function printStudentReport(studentId) {
    if (!currentExamData || !currentExamMeta) return;
    
    // Construct URL Params
    // We need the exam ID(s). The modal was opened with `examIds` which is passed to `openExamDetails`.
    // But `openExamDetails` variables are local.
    // I stored `currentExamMeta` but not the ID.
    // I should store `currentExamIds` globally or in `currentExamMeta`.
    
    // I'll grab it from the meta if I saved it, or I need to save it.
    // Let's rely on the global `currentExamIds` (I need to create it).
    
    const url = `/report-cards/print?student_id=${studentId}&exam_id=${currentExamIds}`;
    window.open(url, '_blank', 'height=800,width=1000');
}


function toggleColumn(className) {
    let show = false;
    if(className === 'col-subjects') show = document.getElementById('show-subjects').checked;
    if(className === 'col-total') show = document.getElementById('show-total').checked;
    if(className === 'col-avg') show = document.getElementById('show-avg').checked;

    const cols = document.querySelectorAll('.' + className);
    cols.forEach(col => {
        if(show) col.classList.remove('hidden');
        else col.classList.add('hidden');
    });
}

function printExamDetails() {
    const printWindow = window.open('', '', 'height=600,width=800');
    const tableHtml = document.getElementById('exam-details-table').outerHTML;
    const title = document.getElementById('modal-title').innerText;
    const info = document.getElementById('modal-exam-info').innerHTML;

    printWindow.document.write('<html><head><title>Exam Results</title>');
    printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">'); 
    printWindow.document.write('<style>body { -webkit-print-color-adjust: exact; } .action-col { display: none !important; }</style>');
    printWindow.document.write('</head><body class="p-8">');
    
    // Header with Logo
    printWindow.document.write(`
        <div class="flex items-center justify-between mb-6 border-b pb-4">
            <div class="flex items-center">
                <img src="${schoolLogo}" alt="Logo" class="h-16 w-auto mr-4" onerror="this.style.display='none'">
                <div>
                    <h1 class="text-2xl font-bold text-gray-900">${schoolName}</h1>
                    <div class="text-sm text-gray-500">Academic Performance Report</div>
                </div>
            </div>
            <div class="text-right text-sm text-gray-500">
                Printed: ${new Date().toLocaleDateString()}
            </div>
        </div>
    `);

    printWindow.document.write(`<h2 class="text-xl font-bold mb-2">${title}</h2>`);
    printWindow.document.write(`<div class="mb-6 text-sm text-gray-600">${info}</div>`);
    printWindow.document.write(tableHtml);
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    printWindow.focus();
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 500);
}

function exportExamDetailsCSV() {
    if (!currentExamData || currentExamData.length === 0) {
        alert("No data to export.");
        return;
    }

    let csvContent = "data:text/csv;charset=utf-8,";
    
    // Header Info
    csvContent += `Exam,${currentExamMeta.name || ''}\n`;
    csvContent += `Year,${currentExamMeta.year || ''}\n`;
    csvContent += `Term,${currentExamMeta.term || ''}\n`;
    csvContent += `Classes,${(currentExamMeta.class || '').replace(/,/g, ';')}\n\n`; // Replace comma in class list with semicolon to avoid CSV break

    // Column Headers
    csvContent += "Rank,First Name,Last Name,Class,Subjects,Total Score,Average Score,Grade\n";

    currentExamData.forEach((row, index) => {
        let grade = 'F';
        const score = parseFloat(row.average_score);
        if(score >= 80) grade = 'A';
        else if(score >= 70) grade = 'B';
        else if(score >= 60) grade = 'C';
        else if(score >= 50) grade = 'D';
        else if(score >= 40) grade = 'E';

        const subjects = (row.subjects_taken || '').replace(/,/g, ';'); // Escape commas

        const rowData = [
            index + 1,
            row.first_name,
            row.last_name,
            row.class_name,
            `"${subjects}"`, // Quote subjects
            parseFloat(row.total_score).toFixed(1),
            parseFloat(row.average_score).toFixed(1),
            grade
        ];
        csvContent += rowData.join(",") + "\n";
    });

    const encodedUri = encodeURI(csvContent);
    const link = document.createElement("a");
    link.setAttribute("href", encodedUri);
    const filename = `Exam_Results_${(currentExamMeta.name || 'report').replace(/[^a-z0-9]/gi, '_').toLowerCase()}.csv`;
    link.setAttribute("download", filename);
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
}

function closeExamModal() {
    document.getElementById('exam-details-modal').classList.add('hidden');
}

function renderPagination(meta) {
    const container = document.getElementById('pagination-container');
    
    const { total, current_page, per_page, total_pages } = { 
        total: meta.total, 
        current_page: parseInt(meta.page), 
        per_page: parseInt(meta.per_page), 
        total_pages: meta.total_pages 
    };

    if (total === 0) {
        container.innerHTML = '';
        container.classList.add('hidden');
        return;
    }
    
    container.classList.remove('hidden');

    const start = (current_page - 1) * per_page + 1;
    const end = Math.min(current_page * per_page, total);

    container.innerHTML = `
        <div class="hidden sm:flex-1 sm:flex sm:items-center sm:justify-between">
            <div>
                <p class="text-sm text-gray-700">
                    Showing <span class="font-medium">${start}</span> to <span class="font-medium">${end}</span> of <span class="font-medium">${total}</span> results
                </p>
            </div>
            <div>
                <nav class="relative z-0 inline-flex rounded-md shadow-sm -space-x-px" aria-label="Pagination">
                    <button onclick="loadAnalytics(${current_page - 1})" ${current_page === 1 ? 'disabled' : ''} class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ${(current_page === 1) ? 'opacity-50 cursor-not-allowed' : ''}">
                        <span class="sr-only">Previous</span>
                        <!-- Heroicon name: solid/chevron-left -->
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M12.707 5.293a1 1 0 010 1.414L9.414 10l3.293 3.293a1 1 0 01-1.414 1.414l-4-4a1 1 0 010-1.414l4-4a1 1 0 011.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                    ${generatePageNumbers(current_page, total_pages)}
                    <button onclick="loadAnalytics(${current_page + 1})" ${current_page === total_pages ? 'disabled' : ''} class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50 ${(current_page === total_pages) ? 'opacity-50 cursor-not-allowed' : ''}">
                        <span class="sr-only">Next</span>
                        <!-- Heroicon name: solid/chevron-right -->
                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                            <path fill-rule="evenodd" d="M7.293 14.707a1 1 0 010-1.414L10.586 10 7.293 6.707a1 1 0 011.414-1.414l4 4a1 1 0 010 1.414l-4 4a1 1 0 01-1.414 0z" clip-rule="evenodd" />
                        </svg>
                    </button>
                </nav>
            </div>
        </div>
        <!-- Mobile View -->
        <div class="flex items-center justify-between sm:hidden w-full">
            <button onclick="loadAnalytics(${current_page - 1})" ${current_page === 1 ? 'disabled' : ''} class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 ${(current_page === 1) ? 'opacity-50 cursor-not-allowed' : ''}">
                Previous
            </button>
            <div class="text-sm text-gray-700">
                Page ${current_page} of ${total_pages}
            </div>
            <button onclick="loadAnalytics(${current_page + 1})" ${current_page === total_pages ? 'disabled' : ''} class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 ${(current_page === total_pages) ? 'opacity-50 cursor-not-allowed' : ''}">
                Next
            </button>
        </div>
    `;
}

function generatePageNumbers(currentPage, totalPages) {
    let html = '';
    const maxVisible = 5;
    
    let startPage = Math.max(1, currentPage - Math.floor(maxVisible / 2));
    let endPage = Math.min(totalPages, startPage + maxVisible - 1);
    
    if (endPage - startPage + 1 < maxVisible) {
        startPage = Math.max(1, endPage - maxVisible + 1);
    }
    
    for (let i = startPage; i <= endPage; i++) {
        html += `
            <button onclick="loadAnalytics(${i})" class="relative inline-flex items-center px-4 py-2 border border-gray-300 bg-white text-sm font-medium ${i === currentPage ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'text-gray-500 hover:bg-gray-50'}">
                ${i}
            </button>
        `;
    }
    
    return html;
}
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>