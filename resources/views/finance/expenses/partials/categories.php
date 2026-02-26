<!-- Categories Partial -->
<div class="sm:flex sm:items-center sm:justify-between mb-4">
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">Expense Categories</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Manage the categories used to group your school expenses.</p>
    </div>
    <div class="mt-4 sm:mt-0">
        <button onclick="document.getElementById('add-category-modal').classList.remove('hidden')" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Add Category
        </button>
    </div>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-md">
    <ul role="list" class="divide-y divide-gray-200">
        <?php if (empty($categories)): ?>
            <li class="px-4 py-5 sm:px-6 text-center text-gray-500 text-sm">
                No expense categories found.
            </li>
        <?php else: ?>
            <?php foreach ($categories as $category): ?>
                <li>
                    <div class="px-4 py-4 sm:px-6 flex items-center justify-between">
                        <div class="flex-1 min-w-0">
                            <p class="text-sm font-medium text-indigo-600 truncate"><?= htmlspecialchars($category['category_name']) ?></p>
                            <p class="mt-1 text-sm text-gray-500 truncate"><?= htmlspecialchars($category['description'] ?? 'No description') ?></p>
                        </div>
                        <div class="ml-4 flex-shrink-0 flex gap-2">
                            <button onclick="editCategory(<?= $category['id'] ?>, '<?= htmlspecialchars(addslashes($category['category_name'])) ?>', '<?= htmlspecialchars(addslashes($category['description'] ?? '')) ?>')" class="font-medium text-indigo-600 hover:text-indigo-500 text-sm">Edit</button>
                            <form action="/finance/expenses/category/delete" method="POST" class="inline" onsubmit="return confirm('Are you sure you want to delete this category? If it is used in existing expenses, it cannot be deleted.');">
                                <input type="hidden" name="id" value="<?= $category['id'] ?>">
                                <button type="submit" class="font-medium text-red-600 hover:text-red-500 text-sm ml-2">Delete</button>
                            </form>
                        </div>
                    </div>
                </li>
            <?php endforeach; ?>
        <?php endif; ?>
    </ul>
</div>

<!-- Modal: Add/Edit Category -->
<div id="add-category-modal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeCategoryModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="category-form" action="/finance/expenses/category" method="POST">
                <input type="hidden" name="id" id="cat_id" value="">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">Add Expense Category</h3>
                    <div class="mt-4 space-y-4">
                        <div>
                            <label for="category_name" class="block text-sm font-medium text-gray-700">Category Name</label>
                            <input type="text" name="category_name" id="category_name" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                        </div>
                        <div>
                            <label for="description" class="block text-sm font-medium text-gray-700">Description (Optional)</label>
                            <textarea name="description" id="description" rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border"></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Save Category
                    </button>
                    <button type="button" onclick="closeCategoryModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function editCategory(id, name, description) {
    document.getElementById('cat_id').value = id;
    document.getElementById('category_name').value = name;
    document.getElementById('description').value = description;
    document.getElementById('modal-title').innerText = 'Edit Expense Category';
    document.getElementById('add-category-modal').classList.remove('hidden');
}

function closeCategoryModal() {
    document.getElementById('cat_id').value = '';
    document.getElementById('category_form')?.reset();
    document.getElementById('category_name').value = '';
    document.getElementById('description').value = '';
    document.getElementById('modal-title').innerText = 'Add Expense Category';
    document.getElementById('add-category-modal').classList.add('hidden');
}
</script>
