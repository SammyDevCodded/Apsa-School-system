<?php 
$title = 'Classes'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Classes</h1>
            <button type="button" onclick="openAddClassModal()" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                Add Class
            </button>
        </div>

        <!-- Search and Filters -->
        <div class="bg-white shadow sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:p-6">
                <form method="GET" action="/classes" class="flex flex-col sm:flex-row gap-4">
                    <div class="flex-grow">
                        <div class="relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                                </svg>
                            </div>
                            <input type="text" name="search" value="<?= htmlspecialchars($search ?? '') ?>" placeholder="Search classes by name or level..." class="focus:ring-indigo-500 focus:border-indigo-500 block w-full pl-10 pr-12 sm:text-sm border-gray-300 rounded-md">
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
                            Filter
                        </button>
                        <?php if (!empty($search)): ?>
                        <a href="/classes" class="inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                            Clear
                        </a>
                        <?php endif; ?>
                    </div>
                </form>
            </div>
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

        <!-- Classes Table -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Class List</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">A list of all classes in the system.</p>
            </div>
            <div class="border-t border-gray-200">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Name
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Level
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Students
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Capacity
                            </th>
                            <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Utilization
                            </th>
                            <th scope="col" class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">
                                Actions
                            </th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($classes)): ?>
                        <tr>
                            <td colspan="6" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">
                                No classes found.
                            </td>
                        </tr>
                        <?php else: ?>
                        <?php foreach ($classes as $class): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= htmlspecialchars($class['name']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($class['level']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($class['student_count']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?= htmlspecialchars($class['capacity']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                <?php 
                                $utilization = $class['capacity'] > 0 ? round(($class['student_count'] / $class['capacity']) * 100, 1) : 0;
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
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <a href="/classes/<?= $class['id'] ?>" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    View
                                </a>
                                <a href="/classes/<?= $class['id'] ?>/edit" class="text-indigo-600 hover:text-indigo-900 mr-3">
                                    Edit
                                </a>
                                <?php if ($class['student_count'] == 0): ?>
                                <a href="/classes/<?= $class['id'] ?>/delete" 
                                   class="text-red-600 hover:text-red-900"
                                   onclick="return confirm('Are you sure you want to delete this class?')">
                                    Delete
                                </a>
                                <?php endif; ?>
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
                    <?php if ($pagination['has_previous']): ?>
                    <a href="?page=<?= $pagination['previous_page'] ?>&search=<?= urlencode($search ?? '') ?>&per_page=<?= $perPage ?? 10 ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                        Previous
                    </a>
                    <?php endif; ?>
                    
                    <?php if ($pagination['has_next']): ?>
                    <a href="?page=<?= $pagination['next_page'] ?>&search=<?= urlencode($search ?? '') ?>&per_page=<?= $perPage ?? 10 ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
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
                            <?php if ($pagination['has_previous']): ?>
                            <a href="?page=<?= $pagination['previous_page'] ?>&search=<?= urlencode($search ?? '') ?>&per_page=<?= $perPage ?? 10 ?>" class="relative inline-flex items-center px-2 py-2 rounded-l-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Previous</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                                </svg>
                            </a>
                            <?php endif; ?>
                            
                            <?php 
                            // Show page numbers
                            $startPage = max(1, $pagination['current_page'] - 2);
                            $endPage = min($pagination['total_pages'], $pagination['current_page'] + 2);
                            
                            for ($i = $startPage; $i <= $endPage; $i++):
                            ?>
                            <a href="?page=<?= $i ?>&search=<?= urlencode($search ?? '') ?>&per_page=<?= $perPage ?? 10 ?>" 
                               class="relative inline-flex items-center px-4 py-2 border text-sm font-medium <?= $i == $pagination['current_page'] ? 'z-10 bg-indigo-50 border-indigo-500 text-indigo-600' : 'bg-white border-gray-300 text-gray-500 hover:bg-gray-50' ?>">
                                <?= $i ?>
                            </a>
                            <?php endfor; ?>
                            
                            <?php if ($pagination['has_next']): ?>
                            <a href="?page=<?= $pagination['next_page'] ?>&search=<?= urlencode($search ?? '') ?>&per_page=<?= $perPage ?? 10 ?>" class="relative inline-flex items-center px-2 py-2 rounded-r-md border border-gray-300 bg-white text-sm font-medium text-gray-500 hover:bg-gray-50">
                                <span class="sr-only">Next</span>
                                <svg class="h-5 w-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
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

<?php 
// Include template helper functions (already included in layout really, but consistent)
?>

<!-- Add Class Modal -->
<div id="addClassModal" class="hidden fixed z-10 inset-0 overflow-y-auto" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <!-- Background overlay -->
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" aria-hidden="true" onclick="closeAddClassModal()"></div>

        <!-- This element is to trick the browser into centering the modal contents. -->
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>

        <!-- Modal panel -->
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                <div class="sm:flex sm:items-start">
                    <div class="mt-3 text-center sm:mt-0 sm:ml-4 sm:text-left w-full">
                        <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                            Add Class
                        </h3>
                        <div class="mt-2">
                            <form action="/classes" method="POST" id="addClassForm">
                                <div class="grid grid-cols-1 gap-y-4">
                                    <div>
                                        <label for="name" class="block text-sm font-medium text-gray-700">Class Name</label>
                                        <input type="text" name="name" id="name" required placeholder="e.g. Grade 1A"
                                            class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
    
                                    <div>
                                        <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
                                        <select name="level" id="level" required
                                            class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                            <option value="">Select Level</option>
                                            <option value="Elementary">Elementary</option>
                                            <option value="Middle School">Middle School</option>
                                            <option value="Junior High">Junior High</option>
                                        </select>
                                        <p class="mt-1 text-sm text-gray-500">Or <a href="#" id="custom-level-link" class="text-indigo-600 hover:text-indigo-900">type a custom level</a></p>
                                        <input type="text" name="custom_level" id="custom_level" 
                                            class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md hidden"
                                            placeholder="Type custom level">
                                    </div>
    
                                    <div>
                                        <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                                        <input type="number" name="capacity" id="capacity" min="1" value="30" required
                                            class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
            <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                <button type="button" onclick="document.getElementById('addClassForm').submit()" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                    Create Class
                </button>
                <button type="button" onclick="closeAddClassModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                    Cancel
                </button>
            </div>
        </div>
    </div>
</div>

<script>
    function openAddClassModal() {
        document.getElementById('addClassModal').classList.remove('hidden');
    }

    function closeAddClassModal() {
        document.getElementById('addClassModal').classList.add('hidden');
    }

    document.addEventListener('DOMContentLoaded', function() {
        const levelSelect = document.getElementById('level');
        const customLevelInput = document.getElementById('custom_level');
        const customLevelLink = document.getElementById('custom-level-link');
        
        if (customLevelLink) {
            customLevelLink.addEventListener('click', function(e) {
                e.preventDefault();
                if (customLevelInput.classList.contains('hidden')) {
                    levelSelect.classList.add('hidden');
                    customLevelInput.classList.remove('hidden');
                    customLevelInput.required = true;
                    levelSelect.required = false;
                    customLevelInput.focus();
                    this.textContent = 'select from list';
                } else {
                    levelSelect.classList.remove('hidden');
                    customLevelInput.classList.add('hidden');
                    customLevelInput.required = false;
                    levelSelect.required = true;
                    levelSelect.focus();
                    this.textContent = 'type a custom level';
                }
            });
        }
    });
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>