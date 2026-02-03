<?php 
$title = 'Edit Class'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Edit Class</h1>
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

        <!-- Edit Class Form -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Class Information</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Update the class details.</p>
            </div>
            <div class="border-t border-gray-200">
                <form action="/classes/<?= $class['id'] ?>" method="POST" class="px-4 py-5 sm:p-6">
                    <input type="hidden" name="_method" value="PUT">
                    <div class="grid grid-cols-1 gap-y-6 gap-x-4 sm:grid-cols-6">
                        <div class="sm:col-span-3">
                            <label for="name" class="block text-sm font-medium text-gray-700">Class Name</label>
                            <div class="mt-1">
                                <input type="text" name="name" id="name" value="<?= htmlspecialchars($class['name']) ?>" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="level" class="block text-sm font-medium text-gray-700">Level</label>
                            <div class="mt-1">
                                <input type="text" name="level" id="level" value="<?= htmlspecialchars($class['level']) ?>" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label for="capacity" class="block text-sm font-medium text-gray-700">Capacity</label>
                            <div class="mt-1">
                                <input type="number" name="capacity" id="capacity" min="1" value="<?= htmlspecialchars($class['capacity']) ?>" required
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            </div>
                        </div>

                        <div class="sm:col-span-3">
                            <label class="block text-sm font-medium text-gray-700">Current Students</label>
                            <div class="mt-1">
                                <input type="text" value="<?= htmlspecialchars($class['student_count']) ?>" disabled
                                    class="shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md bg-gray-100">
                            </div>
                        </div>
                    </div>

                    <div class="mt-6">
                        <button type="submit"
                            class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700">
                            Update Class
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>