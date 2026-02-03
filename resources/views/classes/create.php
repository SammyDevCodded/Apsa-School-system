<?php 
$title = 'Add Class'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Add Class</h1>
            <a href="/classes" class="text-indigo-600 hover:text-indigo-900">
                Back to Classes
            </a>
        </div>

        <!-- Flash Messages -->
        <?php if (isset($_SESSION['flash_error'])): ?>
        <div class="bg-red-100 border border-red-400 text-red-700 px-4 py-3 rounded relative mb-4" role="alert">
            <span class="block sm:inline"><?= $_SESSION['flash_error'] ?></span>
        </div>
        <?php unset($_SESSION['flash_error']); endif; ?>

        <!-- Create Class Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Class Information</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Enter the details for the new class.</p>
            </div>
            <div class="border-t border-gray-200">
                <form action="/classes" method="POST" class="px-4 py-5 sm:p-6">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Class Name</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
                            <div class="mt-1">
                                <select name="level" id="level" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
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
                        </div>

                        <div class="sm:col-span-3">
                            <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                            <div class="mt-1">
                                <input type="number" name="capacity" id="capacity" min="1" value="30" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                            Create Class
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const levelSelect = document.getElementById('level');
    const customLevelInput = document.getElementById('custom_level');
    const customLevelLink = document.getElementById('custom-level-link');
    
    if (customLevelLink) {
        customLevelLink.addEventListener('click', function(e) {
            e.preventDefault();
            levelSelect.classList.add('hidden');
            customLevelInput.classList.remove('hidden');
            customLevelInput.required = true;
            levelSelect.required = false;
            customLevelInput.focus();
        });
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>