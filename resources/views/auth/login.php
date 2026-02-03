<?php 
$title = 'Login'; 
ob_start(); 
?>

<style>
    /* Gradient Background */
    body {
        background: linear-gradient(135deg, #667eea 0%, #764ba2 100%);
        min-height: 100vh;
    }
    
    /* Glassmorphism Card */
    .glass-card {
        background: rgba(255, 255, 255, 0.25);
        backdrop-filter: blur(16px);
        -webkit-backdrop-filter: blur(16px);
        border: 1px solid rgba(255, 255, 255, 0.4);
        box-shadow: 0 8px 32px 0 rgba(31, 38, 135, 0.37);
    }
    
    /* Input Fields */
    .glass-input {
        background: rgba(255, 255, 255, 0.3);
        border: 1px solid rgba(255, 255, 255, 0.5);
        color: #1f2937;
        backdrop-filter: blur(4px);
        transition: all 0.3s ease;
    }
    
    .glass-input:focus {
        background: rgba(255, 255, 255, 0.6);
        border-color: #6366f1;
        outline: none;
        box-shadow: 0 0 0 3px rgba(99, 102, 241, 0.2);
    }
    
    .glass-input::placeholder {
        color: #4b5563;
    }
    
    /* Animations */
    @keyframes shake {
        0%, 100% { transform: translateX(0); }
        10%, 30%, 50%, 70%, 90% { transform: translateX(-5px); }
        20%, 40%, 60%, 80% { transform: translateX(5px); }
    }
    
    .shake {
        animation: shake 0.5s cubic-bezier(.36,.07,.19,.97) both;
    }
    
    @keyframes fadeIn {
        from { opacity: 0; transform: translateY(20px); }
        to { opacity: 1; transform: translateY(0); }
    }
    
    .fade-in {
        animation: fadeIn 0.8s ease-out forwards;
    }
    
    /* Button */
    .glass-button {
        background: linear-gradient(135deg, #6366f1 0%, #4f46e5 100%);
        box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        transition: all 0.3s ease;
    }
    
    .glass-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
    }
    
    /* Success Overlay */
    #success-overlay {
        transition: all 0.5s ease;
    }
</style>

<div class="min-h-screen flex items-center justify-center p-4">
    <div id="login-card" class="glass-card rounded-2xl p-8 max-w-md w-full fade-in relative overflow-hidden">
        
        <!-- Success Overlay -->
        <div id="success-overlay" class="absolute inset-0 z-50 bg-indigo-600 flex flex-col items-center justify-center transform translate-y-full">
            <svg class="h-16 w-16 text-white mb-4 animate-bounce" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12l2 2 4-4m6 2a9 9 0 11-18 0 9 9 0 0118 0z" />
            </svg>
            <h2 class="text-2xl font-bold text-white tracking-wide">Welcome Back!</h2>
            <p class="text-indigo-200 mt-2">Logging in...</p>
        </div>

        <div class="space-y-6">
            <div class="text-center">
                <?php 
                require_once ROOT_PATH . '/app/Helpers/TemplateHelper.php';
                $settings = getSchoolSettings();
                ?>
                
                <?php if (!empty($settings['school_logo'])): ?>
                    <div class="flex justify-center mb-4">
                        <div class="rounded-full bg-white bg-opacity-30 p-2 shadow-inner">
                            <img src="<?= htmlspecialchars($settings['school_logo']) ?>" alt="<?= htmlspecialchars($settings['school_name'] ?? 'School') ?> Logo" class="h-20 w-20 rounded-full shadow-lg">
                        </div>
                    </div>
                <?php endif; ?>
                
                <h2 class="text-3xl font-extrabold text-gray-800 tracking-tight">
                    <?php if (!empty($settings['school_name'])): ?>
                        <?= htmlspecialchars($settings['school_name']) ?>
                    <?php else: ?>
                        Sign In
                    <?php endif; ?>
                </h2>
                <p class="mt-2 text-sm text-gray-600 font-medium bg-white bg-opacity-40 py-1 px-3 rounded-full inline-block">
                    Access your dashboard
                </p>
            </div>

            <form id="login-form" class="space-y-6" action="/login" method="POST">
                
                <!-- Display Error Hook -->
                <?php if(isset($_SESSION['flash_error'])): ?>
                    <div id="error-message" class="bg-red-100 bg-opacity-90 border border-red-400 text-red-700 px-4 py-3 rounded relative shake" role="alert">
                         <span class="block sm:inline"><?= $_SESSION['flash_error'] ?></span>
                    </div>
                <?php endif; ?>

                <div class="space-y-4">
                    <div>
                        <label for="username" class="text-sm font-medium text-gray-700 pl-1">Username</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>
                            </div>
                            <input id="username" name="username" type="text" required class="glass-input block w-full pl-10 pr-3 py-3 rounded-lg sm:text-sm placeholder-gray-500" placeholder="Enter your username">
                        </div>
                    </div>

                    <div>
                        <label for="password" class="text-sm font-medium text-gray-700 pl-1">Password</label>
                        <div class="mt-1 relative rounded-md shadow-sm">
                            <div class="absolute inset-y-0 left-0 pl-3 flex items-center pointer-events-none">
                                <svg class="h-5 w-5 text-gray-500" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                </svg>
                            </div>
                            <input id="password" name="password" type="password" required class="glass-input block w-full pl-10 pr-3 py-3 rounded-lg sm:text-sm placeholder-gray-500" placeholder="Enter your password">
                        </div>
                    </div>
                </div>

                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <input id="remember_me" name="remember_me" type="checkbox" class="h-4 w-4 text-indigo-600 focus:ring-indigo-500 border-gray-300 rounded cursor-pointer">
                        <label for="remember_me" class="ml-2 block text-sm text-gray-700 cursor-pointer select-none">
                            Remember me
                        </label>
                    </div>

                    <div class="text-sm">
                        <a href="#" class="font-bold text-indigo-700 hover:text-indigo-900 transition-colors duration-200">
                            Forgot password?
                        </a>
                    </div>
                </div>

                <div>
                    <button type="submit" class="glass-button w-full flex justify-center py-3 px-4 border border-transparent rounded-lg text-sm font-bold text-white focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 tracking-wide uppercase">
                        Sign In
                    </button>
                    
                    <div class="mt-4 text-center">
                        <a href="/portal/login" class="text-sm font-medium text-indigo-700 hover:text-indigo-900 transition-colors duration-200">
                            Go to Student/Parent/Staff Portal &rarr;
                        </a>
                    </div>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
    document.addEventListener('DOMContentLoaded', function() {
        // Shake animation for error message if present
        const errorMessage = document.getElementById('error-message');
        if (errorMessage) {
            errorMessage.classList.add('shake');
            // Remove class after animation to allow re-triggering if needed (though page usually reloads)
            setTimeout(() => {
                errorMessage.classList.remove('shake');
            }, 500);
        }

        // Form Submission Interception
        const loginForm = document.getElementById('login-form');
        const successOverlay = document.getElementById('success-overlay');

        loginForm.addEventListener('submit', function(e) {
            // Optional: Basic client-side validation could go here
            
            // Note: In a real SPA, we'd do an AJAX request. 
            // Since this is likely a standard form submission that reloads the page on error,
            // we will show the overlay. If login fails, the page reloads and overlay disappears.
            // If it succeeds, the overlay stays until redirect.
            // Improve UX: Only show overlay if we are reasonably sure, or just show "Logging in..." state.
            // For this specific request "modern login successful message overlay just before the login is done completely",
            // showing it on submit fits the visual requirement.
            
            successOverlay.classList.remove('translate-y-full');
            
            // Small delay to allow overlay animation to start before form submits (if synchronous)
            // But form submit is usually fast. If we want to guarantee the animation is seen,
            // we can preventDefault, wait, then submit.
            // e.preventDefault();
            // setTimeout(() => {
            //     loginForm.submit();
            // }, 800); 
            // However, verify if double submission is an issue. better to just let it submit.
            // To make it look "successful", we are making a visual assumption. 
            // Only if validation fails server-side will the user see the page reload.
        });
    });
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>