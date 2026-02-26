<?php 
$title = 'Login'; 
require_once ROOT_PATH . '/app/Helpers/TemplateHelper.php';
$settings = getSchoolSettings();
ob_start(); 
?>

<div class="min-h-[80vh] flex flex-col justify-center py-12 sm:px-6 lg:px-8">
    <div class="sm:mx-auto sm:w-full sm:max-w-md">
        <div class="text-center">
            <h2 class="mt-6 text-3xl font-extrabold text-white drop-shadow-md">
                Welcome to <?= htmlspecialchars($settings['school_name']) ?> Portal
            </h2>
            <p class="mt-2 text-sm text-white/80">
                Access your dashboard securely
            </p>
        </div>

        <div class="mt-8">
            <div class="glass-panel py-8 px-4 shadow sm:rounded-lg sm:px-10">
                <form class="space-y-6" action="/portal/login" method="POST">
                    <div>
                        <label for="identity" class="block text-sm font-medium text-white/90">
                            Admission No / Username
                        </label>
                        <div class="mt-1">
                            <input id="identity" name="identity" type="text" autocomplete="username" required 
                                class="glass-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-white/50 focus:outline-none sm:text-sm"
                                placeholder="Enter ID or Username">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="block text-sm font-medium text-white/90">
                            Password
                        </label>
                        <div class="mt-1">
                            <input id="password" name="password" type="password" autocomplete="current-password" required 
                                class="glass-input appearance-none block w-full px-3 py-2 rounded-md shadow-sm placeholder-white/50 focus:outline-none sm:text-sm"
                                placeholder="Enter Password">
                        </div>
                    </div>

                    <div class="flex items-center justify-between">
                        <div class="flex items-center">
                            <input id="remember-me" name="remember-me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded bg-white/20">
                            <label for="remember-me" class="ml-2 block text-sm text-white/90">
                                Remember me
                            </label>
                        </div>

                        <div class="text-sm">
                            <a href="#" class="font-medium text-white hover:text-indigo-100 underline decoration-indigo-300">
                                Forgot password?
                            </a>
                        </div>
                    </div>

                    <div>
                        <button type="submit" class="w-full flex justify-center py-2 px-4 border border-transparent rounded-md shadow-sm text-sm font-medium text-indigo-900 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 transition-colors">
                            Sign in
                        </button>
                    </div>
                </form>
            </div>
            
            <div class="mt-6 text-center">
                <p class="text-xs text-white/60">
                    Use your Admission Number for Students/Parents.<br>
                    Use System Username for Staff.
                </p>
            </div>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Unconditionally clear lock state for all users when arriving at login
        Object.keys(localStorage).forEach(key => {
            if (key.startsWith('system_is_locked')) {
                localStorage.removeItem(key);
            }
        });
    });
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/portal.php';
?>
