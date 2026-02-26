<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= APP_NAME ?> - <?= $title ?? 'School Management System' ?></title>
    <link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/css/app.css">
    <style>
        /* Dropdown hover logic is now handled by JS to avoid overflow clipping */
        /* .dropdown:hover .dropdown-menu { display: block; } */
        
        /* Improved glass morphism effect for better text readability */
        .glass-morphism {
            background: rgba(255, 255, 255, 0.65);
            backdrop-filter: blur(12px);
            -webkit-backdrop-filter: blur(12px);
            border: 1px solid rgba(255, 255, 255, 0.3);
            box-shadow: 0 4px 6px -1px rgba(0, 0, 0, 0.1), 0 2px 4px -1px rgba(0, 0, 0, 0.06);
        }
        
        /* Custom scrollbar for notifications */
        .notification-scrollbar::-webkit-scrollbar {
            width: 6px;
        }
        
        .notification-scrollbar::-webkit-scrollbar-track {
            background: rgba(255, 255, 255, 0.1);
        }
        
        .notification-scrollbar::-webkit-scrollbar-thumb {
            background: rgba(255, 255, 255, 0.3);
            border-radius: 3px;
        }
        
        .notification-scrollbar::-webkit-scrollbar-thumb:hover {
            background: rgba(255, 255, 255, 0.5);
        }
        
        /* Enhanced text styles for maximum readability */
        .notification-title {
            font-size: 1.25rem;
            font-weight: 700;
            color: #000000;
            text-shadow: 0 1px 2px rgba(255, 255, 255, 0.8), 0 -1px 2px rgba(255, 255, 255, 0.8), 1px 0 2px rgba(255, 255, 255, 0.8), -1px 0 2px rgba(255, 255, 255, 0.8);
        }
        
        .notification-message {
            font-size: 0.95rem;
            font-weight: 500;
            color: #000000;
            line-height: 1.4;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.9), 0 -1px 1px rgba(255, 255, 255, 0.9), 1px 0 1px rgba(255, 255, 255, 0.9), -1px 0 1px rgba(255, 255, 255, 0.9);
        }
        
        .notification-meta {
            font-size: 0.8rem;
            color: #000000;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.9), 0 -1px 1px rgba(255, 255, 255, 0.9), 1px 0 1px rgba(255, 255, 255, 0.9), -1px 0 1px rgba(255, 255, 255, 0.9);
        }
        
        .notification-badge {
            font-weight: 700;
            color: #000000;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.9), 0 -1px 1px rgba(255, 255, 255, 0.9), 1px 0 1px rgba(255, 255, 255, 0.9), -1px 0 1px rgba(255, 255, 255, 0.9);
        }
        
        .notification-action {
            font-weight: 600;
            color: #000000;
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.9), 0 -1px 1px rgba(255, 255, 255, 0.9), 1px 0 1px rgba(255, 255, 255, 0.9), -1px 0 1px rgba(255, 255, 255, 0.9);
        }
        
        /* Close button style */
        #close-notification-modal {
            text-shadow: 0 1px 1px rgba(255, 255, 255, 0.9), 0 -1px 1px rgba(255, 255, 255, 0.9), 1px 0 1px rgba(255, 255, 255, 0.9), -1px 0 1px rgba(255, 255, 255, 0.9);
        }
        /* Hide scrollbar for Chrome, Safari and Opera */
        .scrollbar-hide::-webkit-scrollbar {
            display: none;
        }

        /* Hide scrollbar for IE, Edge and Firefox */
        .scrollbar-hide {
            -ms-overflow-style: none;  /* IE and Edge */
            scrollbar-width: none;  /* Firefox */
        }
        
        .nav-scroll-btn {
            background: rgba(255, 255, 255, 0.85);
            backdrop-filter: blur(4px);
            box-shadow: 0 0 10px rgba(0,0,0,0.1);
            z-index: 20;
            transition: all 0.2s ease;
        }
        
        .nav-scroll-btn:hover {
            background: rgba(255, 255, 255, 1);
            transform: scale(1.1);
        }

        /* Toast Notifications */
        #toast-container {
            position: fixed;
            top: 1rem;
            right: 1rem;
            z-index: 100;
            display: flex;
            flex-direction: column;
            gap: 0.5rem;
        }

        .toast {
            background: rgba(255, 255, 255, 0.95);
            backdrop-filter: blur(10px);
            border-left: 4px solid;
            box-shadow: 0 10px 15px -3px rgba(0, 0, 0, 0.1), 0 4px 6px -2px rgba(0, 0, 0, 0.05);
            border-radius: 0.5rem;
            padding: 1rem;
            min-width: 320px;
            max-width: 400px;
            transform: translateX(120%);
            transition: transform 0.4s cubic-bezier(0.68, -0.55, 0.27, 1.55);
            display: flex;
            align-items: center;
            justify-content: space-between;
        }
        
        .toast.show {
            transform: translateX(0);
        }

        .toast-success { border-color: #10B981; }
        .toast-success .toast-icon { color: #10B981; }
        
        .toast-error { border-color: #EF4444; }
        .toast-error .toast-icon { color: #EF4444; }
    </style>
</head>
<body class="bg-gray-100">
    <?php 
    // Include template helper functions
    require_once ROOT_PATH . '/app/Helpers/TemplateHelper.php';
    ?>
    
    <?php if (isset($_SESSION['user'])): ?>
    <!-- Notification Dropdown Modal -->
    <div id="notification-modal" class="hidden fixed inset-0 z-50 flex items-start justify-end p-4 pt-20">
        <div class="glass-morphism rounded-lg shadow-xl w-full max-w-md">
            <div class="border-b border-gray-200 border-opacity-30">
                <div class="flex justify-between items-center p-4">
                    <h3 class="notification-title">Notifications</h3>
                    <button id="close-notification-modal" class="text-gray-800 hover:text-black">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div id="notifications-container" class="notification-scrollbar max-h-96 overflow-y-auto p-3">
                <!-- Notifications will be loaded here -->
                <div class="text-center py-8 text-gray-700">
                    <p>Loading notifications...</p>
                </div>
            </div>
            <div class="border-t border-gray-200 border-opacity-30 p-4">
                <div class="flex justify-between">
                    <button id="mark-all-read" class="notification-action hover:text-black">
                        Mark all as read
                    </button>
                    <a href="/notifications" class="notification-action hover:text-black">
                        View all
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Profile Dropdown Modal -->
    <div id="profile-modal" class="hidden fixed inset-0 z-50 flex items-start justify-end p-4 pt-20">
        <div class="glass-morphism rounded-lg shadow-xl w-full max-w-md">
            <div class="border-b border-gray-200 border-opacity-30">
                <div class="flex justify-between items-center p-4">
                    <h3 class="notification-title">Profile</h3>
                    <button id="close-profile-modal" class="text-gray-800 hover:text-black">
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
            <div class="p-4">
                <div class="flex items-center mb-4">
                    <div class="flex-shrink-0">
                        <div class="h-12 w-12 rounded-full bg-indigo-100 text-indigo-800 flex items-center justify-center text-xl font-bold">
                            <?= substr($_SESSION['user']['username'] ?? 'U', 0, 1) ?>
                        </div>
                    </div>
                    <div class="ml-4">
                        <h4 class="notification-title text-lg"><?= htmlspecialchars($_SESSION['user']['first_name'] ?? '') ?> <?= htmlspecialchars($_SESSION['user']['last_name'] ?? '') ?></h4>
                        <p class="notification-meta">@<?= htmlspecialchars($_SESSION['user']['username'] ?? '') ?></p>
                        <p class="notification-meta"><?= ucfirst(htmlspecialchars($_SESSION['user']['role'] ?? '')) ?></p>
                    </div>
                </div>
                <div class="border-t border-gray-200 border-opacity-30 pt-4">
                    <ul class="space-y-2">
                        <li>
                            <a href="/profile" class="notification-action block py-2 px-4 rounded hover:bg-white hover:bg-opacity-20 transition">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                    </svg>
                                    My Profile
                                </div>
                            </a>
                        </li>
                        <li>
                            <a href="/profile/change_password" class="notification-action block py-2 px-4 rounded hover:bg-white hover:bg-opacity-20 transition">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                                    </svg>
                                    Change Password
                                </div>
                            </a>
                        </li>
                        <?php if (hasRole('super_admin')): ?>
                        <li>
                            <a href="/settings" class="notification-action block py-2 px-4 rounded hover:bg-white hover:bg-opacity-20 transition">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Settings
                                </div>
                            </a>
                        </li>
                        <?php endif; ?>
                        <li>
                            <button id="profile-logout-link" class="w-full text-left notification-action block py-2 px-4 rounded hover:bg-white hover:bg-opacity-20 transition text-red-600 hover:text-red-700">
                                <div class="flex items-center">
                                    <svg class="h-5 w-5 mr-3" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                                    </svg>
                                    Logout
                                </div>
                            </button>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        </div>
    </div>
    
    <!-- Logout Confirmation Modal -->
    <div id="logout-confirm-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 backdrop-filter backdrop-blur-sm transition-opacity duration-300">
        <div class="glass-morphism rounded-lg shadow-2xl w-full max-w-sm transform scale-100 transition-transform duration-300">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-100 mb-4">
                    <svg class="h-6 w-6 text-red-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">Logout Confirmation</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to logout? Any unsaved changes may be lost.
                    </p>
                </div>
                <div class="flex justify-center gap-4 mt-6">
                    <button id="cancel-logout" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition-colors duration-200">
                        Cancel
                    </button>
                    <a href="/logout" class="px-4 py-2 bg-red-600 hover:bg-red-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors duration-200">
                        Logout
                    </a>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Lock Confirmation Modal -->
    <div id="lock-confirm-modal" class="hidden fixed inset-0 z-50 flex items-center justify-center p-4 bg-black bg-opacity-50 backdrop-filter backdrop-blur-sm transition-opacity duration-300">
        <div class="glass-morphism rounded-lg shadow-2xl w-full max-w-sm transform scale-100 transition-transform duration-300">
            <div class="p-6 text-center">
                <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-yellow-100 mb-4">
                    <svg class="h-6 w-6 text-yellow-600" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                </div>
                <h3 class="text-lg leading-6 font-medium text-gray-900 mb-2">Lock System</h3>
                <div class="mt-2 px-7 py-3">
                    <p class="text-sm text-gray-500">
                        Are you sure you want to lock the system? You will need your password to unlock.
                    </p>
                </div>
                <div class="flex justify-center gap-4 mt-6">
                    <button onclick="document.getElementById('lock-confirm-modal').classList.add('hidden')" class="px-4 py-2 bg-gray-200 hover:bg-gray-300 text-gray-800 text-sm font-medium rounded-md transition-colors duration-200">
                        Cancel
                    </button>
                    <button onclick="document.getElementById('lock-confirm-modal').classList.add('hidden'); if(typeof window.lockSystem === 'function') window.lockSystem();" class="px-4 py-2 bg-yellow-600 hover:bg-yellow-700 text-white text-sm font-medium rounded-md shadow-sm transition-colors duration-200">
                        Lock System
                    </button>
                </div>
            </div>
        </div>
    </div>
    
    <!-- Navigation -->
    <!-- Navigation -->
    <nav class="glass-morphism sticky top-0 z-40 w-full transition-all duration-300 print:hidden">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex">
                    <div class="flex-shrink-0 flex items-center">
                        <h1 class="text-xl font-bold text-indigo-600"><?= APP_NAME ?></h1>
                    </div>
                    <div class="hidden sm:ml-6 sm:flex sm:items-center flex-1 max-w-4xl relative group" id="desk-nav-container">
                        <!-- Left Scroll Button -->
                        <button id="nav-scroll-left" class="nav-scroll-btn absolute left-0 top-1/2 transform -translate-y-1/2 p-1 rounded-full text-indigo-600 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-300 hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7" />
                            </svg>
                        </button>

                        <!-- Scrollable Navigation Items -->
                        <div id="nav-scroll-wrapper" class="flex space-x-8 overflow-x-auto scrollbar-hide scroll-smooth h-16 items-center px-8">
                            <?php $isDashboardActive = isActiveRoute('/dashboard'); ?>
                            <a href="/dashboard" class="<?= $isDashboardActive ? 'border-indigo-500 text-indigo-700' : 'border-transparent text-gray-900 hover:border-gray-300 hover:text-indigo-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold transition-colors duration-200 whitespace-nowrap flex-shrink-0">
                                <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6" />
                                </svg>
                                Dashboard
                            </a>
                            
                            <!-- Students Dropdown -->
                            <div class="dropdown relative h-full flex items-center flex-shrink-0" data-dropdown="students">
                                <?php $isStudentsActive = isActiveGroup(['/students', '/attendance']); ?>
                                <button class="dropdown-trigger <?= $isStudentsActive ? 'border-indigo-500 text-indigo-700' : 'border-transparent text-gray-800 hover:border-gray-300 hover:text-indigo-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold h-full transition-colors duration-200 whitespace-nowrap">
                                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4.354a4 4 0 110 5.292M15 21H3v-1a6 6 0 0112 0v1zm0 0h6v-1a6 6 0 00-9-5.197M13 7a4 4 0 11-8 0 4 4 0 018 0z" />
                                    </svg>
                                    Students
                                    <svg class="dropdown-arrow ml-1 h-4 w-4 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div class="dropdown-menu dropdown-content absolute top-full left-0 hidden glass-morphism rounded-md mt-1 py-1 w-48 z-50 shadow-lg">
                                    <a href="/students" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Student List</a>
                                    <a href="/attendance" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Attendance</a>
                                </div>
                            </div>
                            
                            <!-- Academics Dropdown -->
                            <div class="dropdown relative h-full flex items-center flex-shrink-0" data-dropdown="academics">
                                <?php $isAcademicsActive = isActiveGroup(['/classes', '/staff', '/subjects', '/exams', '/exam_results', '/academic-reports', '/promotions', '/timetables']); ?>
                                <button class="dropdown-trigger <?= $isAcademicsActive ? 'border-indigo-500 text-indigo-700' : 'border-transparent text-gray-800 hover:border-gray-300 hover:text-indigo-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold h-full transition-colors duration-200 whitespace-nowrap">
                                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" />
                                    </svg>
                                    Academics
                                    <svg class="dropdown-arrow ml-1 h-4 w-4 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div class="dropdown-menu dropdown-content absolute top-full left-0 hidden glass-morphism rounded-md mt-1 py-1 w-48 z-50 shadow-lg">
                                    <a href="/classes" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Classes</a>
                                    <a href="/staff" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Staff</a>
                                    <a href="/subjects" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Subjects</a>
                                    <a href="/exams" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Exams</a>
                                    <a href="/exam_results" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Results</a>
                                    <a href="/exam_results/submission-status" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">- Submission Status</a>
                                    <a href="/academic-reports" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Academic Reports</a>
                                    <a href="/promotions" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Promotions</a>
                                    <a href="/timetables" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Timetables</a>
                                </div>
                            </div>
                            
                            <!-- Finance Dropdown -->
                            <div class="dropdown relative h-full flex items-center flex-shrink-0" data-dropdown="finance">
                                <?php $isFinanceActive = isActiveGroup(['/fees', '/finance', '/finance/expenses']); ?>
                                <button class="dropdown-trigger <?= $isFinanceActive ? 'border-indigo-500 text-indigo-700' : 'border-transparent text-gray-800 hover:border-gray-300 hover:text-indigo-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold h-full transition-colors duration-200 whitespace-nowrap">
                                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                                    </svg>
                                    Finance
                                    <svg class="dropdown-arrow ml-1 h-4 w-4 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div class="dropdown-menu dropdown-content absolute top-full left-0 hidden glass-morphism rounded-md mt-1 py-1 w-48 z-50 shadow-lg">
                                    <a href="/fees" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Fees</a>
                                    <a href="/finance" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Finance Records</a>
                                    <a href="/finance/expenses" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Expense Tracking</a>
                                </div>
                            </div>
                            
                            <!-- Reports Dropdown -->
                            <div class="dropdown relative h-full flex items-center flex-shrink-0" data-dropdown="reports">
                                <?php $isReportsActive = isActiveGroup(['/reports']); ?>
                                <button class="dropdown-trigger <?= $isReportsActive ? 'border-indigo-500 text-indigo-700' : 'border-transparent text-gray-800 hover:border-gray-300 hover:text-indigo-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold h-full transition-colors duration-200 whitespace-nowrap">
                                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 17v-2m3 2v-4m3 4v-6m2 10H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                                    </svg>
                                    Reports
                                    <svg class="dropdown-arrow ml-1 h-4 w-4 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div class="dropdown-menu dropdown-content absolute top-full left-0 hidden glass-morphism rounded-md mt-1 py-1 w-48 z-50 shadow-lg">
                                    <a href="/reports/analytics" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Analytics</a>
                                    <a href="/reports" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">General Reports</a>
                                </div>
                            </div>
                            
                            <!-- Administration Dropdown -->
                            <div class="dropdown relative h-full flex items-center flex-shrink-0" data-dropdown="admin">
                                <?php $isAdminActive = isActiveGroup(['/academic_years', '/archives', '/audit_logs', '/backups', '/users', '/settings']); ?>
                                <button class="dropdown-trigger <?= $isAdminActive ? 'border-indigo-500 text-indigo-700' : 'border-transparent text-gray-800 hover:border-gray-300 hover:text-indigo-700' ?> inline-flex items-center px-1 pt-1 border-b-2 text-sm font-bold h-full transition-colors duration-200 whitespace-nowrap">
                                    <svg class="mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    </svg>
                                    Administration
                                    <svg class="dropdown-arrow ml-1 h-4 w-4 transform transition-transform duration-200" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                                        <path fill-rule="evenodd" d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </button>
                                <div class="dropdown-menu dropdown-content absolute top-full left-0 hidden glass-morphism rounded-md mt-1 py-1 w-48 z-50 shadow-lg">
                                    <a href="/academic_years" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Academic Years</a>
                                    <a href="/archives" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">School Database</a>
                                    <?php if (hasAnyRole(['admin', 'super_admin'])): ?>
                                    <a href="/audit_logs" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Audit Logs</a>
                                    <a href="/backups" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Backups</a>
                                    <a href="/users" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Users</a>
                                    <a href="/admin/portal" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150">Portal Management</a>
                                    <?php endif; ?>
                                    <?php if (hasRole('super_admin')): ?>
                                    <a href="/settings" class="block px-4 py-2 text-sm text-gray-800 font-medium hover:bg-indigo-50 hover:text-indigo-700 transition-colors duration-150 bg-yellow-50 bg-opacity-50">
                                        <svg class="w-4 h-4 mr-1 inline" fill="none" stroke="currentColor" viewBox="0 0 24 24" xmlns="http://www.w3.org/2000/svg">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M10.325 4.317c.426-1.756 2.924-1.756 3.35 0a1.724 1.724 0 002.573 1.066c1.543-.94 3.31.826 2.37 2.37a1.724 1.724 0 001.065 2.572c1.756.426 1.756 2.924 0 3.35a1.724 1.724 0 00-1.066 2.573c.94 1.543-.826 3.31-2.37 2.37a1.724 1.724 0 00-2.572 1.065c-.426 1.756-2.924 1.756-3.35 0a1.724 1.724 0 00-2.573-1.066c-1.543.94-3.31-.826-2.37-2.37a1.724 1.724 0 00-1.065-2.572c-1.756-.426-1.756-2.924 0-3.35a1.724 1.724 0 001.066-2.573c-.94-1.543.826-3.31 2.37-2.37.996.608 2.296.07 2.572-1.065z"></path>
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        </svg>
                                        Settings
                                    </a>
                                    <?php endif; ?>
                                </div>
                            </div>
                        </div>

                        <!-- Right Scroll Button -->
                        <button id="nav-scroll-right" class="nav-scroll-btn absolute right-0 top-1/2 transform -translate-y-1/2 p-1 rounded-full text-indigo-600 opacity-0 pointer-events-none group-hover:opacity-100 transition-opacity duration-300 hidden">
                            <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                            </svg>
                        </button>
                    </div>
                </div>
                <div class="hidden sm:ml-6 sm:flex sm:items-center">
                    <div class="ml-3 relative">
                        <?php if (isset($_SESSION['user'])): ?>
                        <div>
                            <button id="profile-button" class="flex text-sm rounded-full focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 hover:ring-indigo-500 transition-shadow cursor-pointer">
                                <span class="sr-only">Open user menu</span>
                                <span class="h-8 w-8 rounded-full bg-indigo-100 text-indigo-800 flex items-center justify-center font-bold">
                                    <?= substr($_SESSION['user']['username'] ?? 'U', 0, 1) ?>
                                </span>
                            </button>
                        </div>
                        <?php endif; ?>
                    </div>
                    <div class="ml-3 relative">
                        <button id="notification-button" class="text-sm text-gray-700 hover:text-gray-900 mr-4 relative">
                            Notifications
                            <span id="notification-count" class="absolute -top-2 -right-2 bg-red-500 text-white text-xs rounded-full h-5 w-5 flex items-center justify-center hidden">0</span>
                        </button>
                    </div>
                    <div class="ml-3 relative">
                        <?php if (isset($_SESSION['user'])): ?>
                        <button onclick="document.getElementById('lock-confirm-modal').classList.remove('hidden');" class="text-sm text-yellow-600 hover:text-yellow-800 mr-4 flex items-center font-bold" title="Instant Lock">
                            <svg class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                            </svg>
                            Lock
                        </button>
                        <?php endif; ?>
                    </div>
                    <div class="ml-3 relative">
                        <!-- Logout button removed from here -->
                    </div>
                </div>
                
                <!-- Mobile menu button -->
                <div class="-mr-2 flex items-center sm:hidden">
                    <button type="button" class="glass-morphism inline-flex items-center justify-center p-2 rounded-md text-gray-700 hover:text-black hover:bg-white hover:bg-opacity-40 focus:outline-none" aria-controls="mobile-menu" aria-expanded="false" id="mobile-menu-btn">
                        <span class="sr-only">Open main menu</span>
                        <svg class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" id="menu-icon-open">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                        </svg>
                        <svg class="hidden h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true" id="menu-icon-close">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                        </svg>
                    </button>
                </div>
            </div>
        </div>

        <!-- Mobile Menu, show/hide based on menu state. -->
        <div class="sm:hidden hidden glass-morphism border-t border-gray-200 border-opacity-30" id="mobile-menu">
            <div class="pt-2 pb-3 space-y-1 px-2">
                <a href="/dashboard" class="text-gray-900 block px-3 py-2 rounded-md text-base font-medium hover:bg-white hover:bg-opacity-20">Dashboard</a>
                
                <!-- Students -->
                <div>
                    <button type="button" class="w-full text-left text-gray-900 block px-3 py-2 rounded-md text-base font-medium hover:bg-white hover:bg-opacity-20 flex justify-between items-center" onclick="toggleMobileSubmenu('mobile-students')">
                        Students
                        <svg class="h-5 w-5 transform transition-transform duration-200" id="arrow-mobile-students" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div class="hidden pl-4 space-y-1" id="mobile-students">
                         <a href="/students" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Student List</a>
                         <a href="/attendance" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Attendance</a>
                    </div>
                </div>

                <!-- Academics -->
                <div>
                    <button type="button" class="w-full text-left text-gray-900 block px-3 py-2 rounded-md text-base font-medium hover:bg-white hover:bg-opacity-20 flex justify-between items-center" onclick="toggleMobileSubmenu('mobile-academics')">
                        Academics
                        <svg class="h-5 w-5 transform transition-transform duration-200" id="arrow-mobile-academics" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div class="hidden pl-4 space-y-1" id="mobile-academics">
                        <a href="/classes" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Classes</a>
                        <a href="/staff" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Staff</a>
                        <a href="/subjects" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Subjects</a>
                        <a href="/exams" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Exams</a>
                        <a href="/exam_results" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Results</a>
                        <a href="/exam_results/submission-status" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Submission Status</a>
                        <a href="/academic-reports" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Academic Reports</a>
                        <a href="/promotions" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Promotions</a>
                        <a href="/timetables" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Timetables</a>
                    </div>
                </div>

                <!-- Finance -->
                <div>
                    <button type="button" class="w-full text-left text-gray-900 block px-3 py-2 rounded-md text-base font-medium hover:bg-white hover:bg-opacity-20 flex justify-between items-center" onclick="toggleMobileSubmenu('mobile-finance')">
                        Finance
                        <svg class="h-5 w-5 transform transition-transform duration-200" id="arrow-mobile-finance" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div class="hidden pl-4 space-y-1" id="mobile-finance">
                        <a href="/fees" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Fees</a>
                        <a href="/finance" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Finance Records</a>
                        <a href="/finance/expenses" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Expense Tracking</a>
                    </div>
                </div>

                <!-- Reports -->
                <div>
                    <button type="button" class="w-full text-left text-gray-900 block px-3 py-2 rounded-md text-base font-medium hover:bg-white hover:bg-opacity-20 flex justify-between items-center" onclick="toggleMobileSubmenu('mobile-reports')">
                        Reports
                        <svg class="h-5 w-5 transform transition-transform duration-200" id="arrow-mobile-reports" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div class="hidden pl-4 space-y-1" id="mobile-reports">
                        <a href="/reports/analytics" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Analytics</a>
                        <a href="/reports" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">General Reports</a>
                    </div>
                </div>
                
                <!-- Administration -->
                <div>
                    <button type="button" class="w-full text-left text-gray-900 block px-3 py-2 rounded-md text-base font-medium hover:bg-white hover:bg-opacity-20 flex justify-between items-center" onclick="toggleMobileSubmenu('mobile-admin')">
                        Administration
                        <svg class="h-5 w-5 transform transition-transform duration-200" id="arrow-mobile-admin" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" /></svg>
                    </button>
                    <div class="hidden pl-4 space-y-1" id="mobile-admin">
                         <a href="/academic_years" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Academic Years</a>
                         <a href="/archives" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">School Database</a>
                         <?php if (hasAnyRole(['admin', 'super_admin'])): ?>
                         <a href="/audit_logs" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Audit Logs</a>
                         <a href="/backups" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Backups</a>
                         <a href="/users" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Users</a>
                         <?php endif; ?>
                         <?php if (hasRole('super_admin')): ?>
                         <a href="/settings" class="block px-3 py-2 rounded-md text-sm text-gray-700 hover:bg-white hover:bg-opacity-20">Settings</a>
                         <?php endif; ?>
                    </div>
                </div>
                
                <a href="/about" class="text-gray-900 block px-3 py-2 rounded-md text-base font-medium hover:bg-white hover:bg-opacity-20">About</a>
            </div>
            
            <!-- Mobile Profile Section -->
            <?php if (isset($_SESSION['user'])): ?>
            <div class="pt-4 pb-4 border-t border-gray-200 border-opacity-30">
                <div class="flex items-center px-4">
                    <div class="flex-shrink-0">
                        <div class="h-10 w-10 rounded-full bg-indigo-100 text-indigo-800 flex items-center justify-center font-bold">
                            <?= substr($_SESSION['user']['username'] ?? 'U', 0, 1) ?>
                        </div>
                    </div>
                    <div class="ml-3">
                        <div class="text-base font-medium text-gray-800"><?= htmlspecialchars($_SESSION['user']['first_name'] ?? '') ?></div>
                        <div class="text-sm font-medium text-gray-500">@<?= htmlspecialchars($_SESSION['user']['username'] ?? '') ?></div>
                    </div>
                    <button class="ml-auto flex-shrink-0 bg-white bg-opacity-30 p-1 rounded-full text-gray-600 hover:text-black focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 relative" onclick="document.getElementById('notification-modal').classList.remove('hidden'); loadNotifications();">
                        <span class="sr-only">View notifications</span>
                        <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0 3 3 0 016 0z" />
                        </svg>
                        <div id="mobile-notification-dot" class="absolute top-0 right-0 h-3 w-3 rounded-full ring-2 ring-white bg-red-400 hidden"></div>
                    </button>
                </div>
                <div class="mt-3 px-2 space-y-1">
                    <a href="/profile" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-white hover:bg-opacity-20">Your Profile</a>
                     <a href="/profile/change_password" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-white hover:bg-opacity-20">Change Password</a>
                    <a href="/logout" class="block px-3 py-2 rounded-md text-base font-medium text-gray-700 hover:bg-white hover:bg-opacity-20">Sign out</a>
                </div>
            </div>
            <?php endif; ?>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Toast Container -->
    <div id="toast-container"></div>

    <!-- Flash Messages (Converted to Toasts) -->
    <?php if (isset($_SESSION['flash_success'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast("<?= addslashes($_SESSION['flash_success']) ?>", 'success');
        });
    </script>
    <?php unset($_SESSION['flash_success']); endif; ?>

    <?php if (isset($_SESSION['flash_error'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast("<?= addslashes($_SESSION['flash_error']) ?>", 'error');
        });
    </script>
    <?php unset($_SESSION['flash_error']); endif; ?>

    <?php if (isset($_SESSION['flash_warning'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast("<?= addslashes($_SESSION['flash_warning']) ?>", 'warning');
        });
    </script>
    <?php unset($_SESSION['flash_warning']); endif; ?>

    <?php if (isset($_SESSION['flash_info'])): ?>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            showToast("<?= addslashes($_SESSION['flash_info']) ?>", 'info');
        });
    </script>
    <?php unset($_SESSION['flash_info']); endif; ?>

    <!-- Main Content -->
    <main>
        <?= $content ?? '' ?>
    </main>

    <!-- Footer -->
    <footer class="bg-white mt-8 print:hidden">
        <div class="max-w-7xl mx-auto py-6 px-4 sm:px-6 lg:px-8">
            <p class="text-center text-sm text-gray-500">
                &copy; <?= date('Y') ?> <?= APP_NAME ?>. All rights reserved.
            </p>
        </div>
    </footer>
    <script>
        // Toast Notification System
        function showToast(message, type = 'success') {
            const container = document.getElementById('toast-container');
            if (!container) return; // Guard clause

            const toast = document.createElement('div');
            
            let iconCode = '';
            if (type === 'success') {
                iconCode = '<svg class="w-6 h-6 mr-3 toast-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>';
            } else if (type === 'warning') {
                iconCode = '<svg class="w-6 h-6 mr-3 toast-icon text-yellow-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z"></path></svg>';
            } else if (type === 'info') {
                iconCode = '<svg class="w-6 h-6 mr-3 toast-icon text-blue-500 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            } else {
                iconCode = '<svg class="w-6 h-6 mr-3 toast-icon flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4m0 4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>';
            }
            
            toast.className = `toast toast-${type}`;
            toast.innerHTML = `
                <div class="flex items-center">
                    ${iconCode}
                    <p class="font-medium text-gray-800 text-sm">${message}</p>
                </div>
                <button onclick="this.parentElement.classList.remove('show'); setTimeout(() => this.parentElement.remove(), 400);" class="ml-4 text-gray-400 hover:text-gray-600 focus:outline-none">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;
            
            container.appendChild(toast);
            
            // Trigger animation
            requestAnimationFrame(() => {
                toast.classList.add('show');
            });
            
            // Auto remove
            setTimeout(() => {
                if (toast.parentElement) {
                    toast.classList.remove('show');
                    setTimeout(() => {
                        if (toast.parentElement) toast.remove();
                    }, 400); // Wait for transition
                }
            }, 5000);
        }

        // Function to update notification count
        function updateNotificationCount() {
            fetch('/notifications/unread_count')
                .then(response => response.json())
                .then(data => {
                    const countElement = document.getElementById('notification-count');
                    if (data.count > 0) {
                        countElement.textContent = data.count;
                        countElement.classList.remove('hidden');
                    } else {
                        countElement.classList.add('hidden');
                    }
                })
                .catch(error => console.error('Error fetching notification count:', error));
        }
        
        // Function to load notifications
        function loadNotifications() {
            const container = document.getElementById('notifications-container');
            container.innerHTML = '<div class="text-center py-8 text-gray-700"><p>Loading notifications...</p></div>';
            
            fetch('/notifications/get_notifications')
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        if (data.notifications.length > 0) {
                            let html = '';
                            data.notifications.forEach(notification => {
                                const isNew = !parseInt(notification.is_read);
                                const bgColor = isNew ? 'bg-blue-100 bg-opacity-50' : 'bg-white bg-opacity-30';
                                const messageClass = isNew ? 'notification-message font-bold' : 'notification-message';
                                const newBadge = isNew ? 
                                    '<span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-bold bg-blue-200 text-black ml-2 notification-badge">' +
                                    'New' +
                                    '</span>' : '';
                                    
                                html += `
                                    <div class="${bgColor} rounded-md p-3 mb-2 border border-gray-200 border-opacity-30">
                                        <div class="flex items-start justify-between">
                                            <p class="${messageClass}">
                                                ${notification.message}
                                            </p>
                                            ${newBadge}
                                        </div>
                                        <div class="mt-1 flex items-center notification-meta">
                                            <span>
                                                ${formatDate(notification.created_at)}
                                            </span>
                                            ${notification.type ? 
                                                '<span class="ml-2 inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium bg-gray-200 text-black notification-meta">' +
                                                capitalizeFirstLetter(notification.type) +
                                                '</span>' : ''}
                                        </div>
                                    </div>
                                `;
                            });
                            container.innerHTML = html;
                        } else {
                            container.innerHTML = '<div class="text-center py-8 text-gray-700"><p>No notifications found.</p></div>';
                        }
                    } else {
                        container.innerHTML = '<div class="text-center py-8 text-red-600"><p>Error loading notifications.</p></div>';
                    }
                })
                .catch(error => {
                    console.error('Error fetching notifications:', error);
                    container.innerHTML = '<div class="text-center py-8 text-red-600"><p>Error loading notifications.</p></div>';
                });
        }
        
        // Helper function to format dates
        function formatDate(dateString) {
            const date = new Date(dateString);
            return date.toLocaleDateString('en-US', { 
                month: 'short', 
                day: 'numeric', 
                year: 'numeric',
                hour: '2-digit',
                minute: '2-digit'
            });
        }
        
        // Helper function to capitalize first letter
        function capitalizeFirstLetter(string) {
            return string.charAt(0).toUpperCase() + string.slice(1);
        }
        
        // Function to mark all notifications as read
        function markAllAsRead() {
            fetch('/notifications/mark_all_as_read', {
                method: 'POST',
                headers: {
                    'Content-Type': 'application/json',
                }
            })
            .then(response => response.json())
            .then(data => {
                if (data.success) {
                    // Update notification count
                    document.getElementById('notification-count').classList.add('hidden');
                    // Reload notifications
                    loadNotifications();
                }
            })
            .catch(error => console.error('Error marking all as read:', error));
        }
        
        // Event listeners
        document.addEventListener('DOMContentLoaded', function() {
            updateNotificationCount();
            
            // Update notification count every 30 seconds
            setInterval(updateNotificationCount, 30000);
            
            // Notification button click event
            const notificationButton = document.getElementById('notification-button');
            const notificationModal = document.getElementById('notification-modal');
            const closeNotificationModal = document.getElementById('close-notification-modal');
            const markAllReadButton = document.getElementById('mark-all-read');
            
            if (notificationButton) {
                notificationButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    notificationModal.classList.remove('hidden');
                    loadNotifications();
                });
            }
            
            if (closeNotificationModal) {
                closeNotificationModal.addEventListener('click', function() {
                    notificationModal.classList.add('hidden');
                });
            }
            
            if (markAllReadButton) {
                markAllReadButton.addEventListener('click', function() {
                    markAllAsRead();
                });
            }
            
            // Profile button click event
            const profileButton = document.getElementById('profile-button');
            const profileModal = document.getElementById('profile-modal');
            const closeProfileModal = document.getElementById('close-profile-modal');
            
            if (profileButton) {
                profileButton.addEventListener('click', function(e) {
                    e.preventDefault();
                    profileModal.classList.remove('hidden');
                });
            }
            
            if (closeProfileModal) {
                closeProfileModal.addEventListener('click', function() {
                    profileModal.classList.add('hidden');
                });
            }
            
            // Close modals when clicking outside
            window.addEventListener('click', function(e) {
                if (e.target === notificationModal) {
                    notificationModal.classList.add('hidden');
                }
                if (e.target === profileModal) {
                    profileModal.classList.add('hidden');
                }
                const logoutModal = document.getElementById('logout-confirm-modal');
                if (e.target === logoutModal) {
                    logoutModal.classList.add('hidden');
                }
            });
            
            // Logout Confirmation Logic
            const profileLogoutLink = document.getElementById('profile-logout-link');
            const logoutConfirmModal = document.getElementById('logout-confirm-modal');
            const cancelLogoutBtn = document.getElementById('cancel-logout');
            
            if (profileLogoutLink && logoutConfirmModal) {
                profileLogoutLink.addEventListener('click', function(e) {
                    e.preventDefault();
                    // Close profile modal if open
                    if (profileModal) profileModal.classList.add('hidden');
                    // Show logout confirm modal
                    logoutConfirmModal.classList.remove('hidden');
                });
            }
            
            if (cancelLogoutBtn && logoutConfirmModal) {
                cancelLogoutBtn.addEventListener('click', function() {
                    logoutConfirmModal.classList.add('hidden');
                });
            }
            // Mobile Menu Logic
            const btn = document.getElementById('mobile-menu-btn');
            const menu = document.getElementById('mobile-menu');
            const iconOpen = document.getElementById('menu-icon-open');
            const iconClose = document.getElementById('menu-icon-close');

            if(btn && menu) {
                btn.addEventListener('click', () => {
                    const isHidden = menu.classList.contains('hidden');
                    if (isHidden) {
                        menu.classList.remove('hidden');
                        iconOpen.classList.add('hidden');
                        iconClose.classList.remove('hidden');
                    } else {
                        menu.classList.add('hidden');
                        iconOpen.classList.remove('hidden');
                        iconClose.classList.add('hidden');
                    }
                });
            }

            // Global generic toggle function if not already present
            window.toggleMobileSubmenu = function(id) {
                const el = document.getElementById(id);
                const arrow = document.getElementById('arrow-' + id);
                if(el) {
                    if(el.classList.contains('hidden')) {
                        el.classList.remove('hidden');
                        if(arrow) arrow.classList.add('rotate-180');
                    } else {
                        el.classList.add('hidden');
                        if(arrow) arrow.classList.remove('rotate-180');
                    }
                }
            };
            
            // Desktop Navigation Scroll Logic
            const navScrollWrapper = document.getElementById('nav-scroll-wrapper');
            const navLeftBtn = document.getElementById('nav-scroll-left');
            const navRightBtn = document.getElementById('nav-scroll-right');
            const deskNavContainer = document.getElementById('desk-nav-container');

            if (navScrollWrapper && navLeftBtn && navRightBtn && deskNavContainer) {
                // Check if buttons should be visible
                function checkScrollButtons() {
                    const maxScrollLeft = navScrollWrapper.scrollWidth - navScrollWrapper.clientWidth;
                    
                    // Show/hide left button
                    if (navScrollWrapper.scrollLeft > 0) {
                        navLeftBtn.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
                        navLeftBtn.classList.add('opacity-100');
                    } else {
                        navLeftBtn.classList.add('opacity-0', 'pointer-events-none');
                        setTimeout(() => { if(navLeftBtn.classList.contains('opacity-0')) navLeftBtn.classList.add('hidden'); }, 300);
                    }

                    // Show/hide right button
                    if (navScrollWrapper.scrollLeft < maxScrollLeft - 1) { // -1 buffer for sub-pixel issues
                        navRightBtn.classList.remove('hidden', 'opacity-0', 'pointer-events-none');
                        navRightBtn.classList.add('opacity-100');
                    } else {
                        navRightBtn.classList.add('opacity-0', 'pointer-events-none');
                        setTimeout(() => { if(navRightBtn.classList.contains('opacity-0')) navRightBtn.classList.add('hidden'); }, 300);
                    }
                }

                // Initial check and on resize
                window.addEventListener('resize', checkScrollButtons);
                navScrollWrapper.addEventListener('scroll', checkScrollButtons);
                
                // Wait for layout to stabilize
                setTimeout(checkScrollButtons, 100);

                // Scroll Left
                navLeftBtn.addEventListener('click', () => {
                    navScrollWrapper.scrollBy({ left: -200, behavior: 'smooth' });
                });

                // Scroll Right
                navRightBtn.addEventListener('click', () => {
                    navScrollWrapper.scrollBy({ left: 200, behavior: 'smooth' });
                });
                
                // Mouse wheel horizontal smooth scroll support
                navScrollWrapper.addEventListener('wheel', (evt) => {
                   if (evt.deltaY !== 0) {
                       evt.preventDefault();
                       navScrollWrapper.scrollLeft += evt.deltaY;
                   }
                });
            }

            // Desktop Dropdown/Hover Logic
            const dropdowns = document.querySelectorAll('.dropdown[data-dropdown]');
            
            dropdowns.forEach(dropdown => {
                const trigger = dropdown.querySelector('.dropdown-trigger');
                const menu = dropdown.querySelector('.dropdown-content');
                const arrow = dropdown.querySelector('.dropdown-arrow');
                let timeoutId;
                
                if (!trigger || !menu) return;
                
                // Show Menu Function
                const showMenu = () => {
                   clearTimeout(timeoutId); // Cancel any hide timer
                   const rect = dropdown.getBoundingClientRect();
                   menu.classList.remove('hidden');
                   menu.style.position = 'fixed';
                   menu.style.top = (rect.bottom + 5) + 'px'; // 5px buffer
                   menu.style.left = rect.left + 'px';
                   // Ensure it doesn't go off screen
                   if (rect.left + 192 > window.innerWidth) { // 192 is w-48
                       menu.style.left = (rect.right - 192) + 'px';
                   }
                   if (arrow) arrow.classList.add('rotate-180');
                };
                
                // Hide Menu Function
                const hideMenu = () => {
                    menu.classList.add('hidden');
                    if (arrow) arrow.classList.remove('rotate-180');
                };

                // Hide with Delay
                const hideMenuWithDelay = () => {
                    timeoutId = setTimeout(hideMenu, 300); // 300ms delay
                };
                
                // Event Listeners for Trigger/Parent
                dropdown.addEventListener('mouseenter', showMenu);
                dropdown.addEventListener('mouseleave', hideMenuWithDelay);

                // Event Listeners for the Menu itself (since it's fixed positioned)
                menu.addEventListener('mouseenter', () => clearTimeout(timeoutId));
                menu.addEventListener('mouseleave', hideMenuWithDelay);
                
                // Hide on scroll to prevent detached menus (immediate)
                window.addEventListener('scroll', hideMenu, true);
                if (window.navScrollWrapper) {
                    window.navScrollWrapper.addEventListener('scroll', hideMenu);
                }
            });

        });
    </script>
        <!-- System Idle Timeout Overlay -->
        <?php
        $appSettingsForTimeout = (new \App\Models\Setting())->getSettings();
        $idleTimeoutMinutes = (int)($appSettingsForTimeout['idle_timeout_minutes'] ?? 0);
        
        // Only output if user is logged in
        if (isset($_SESSION['user'])):
        ?>
        <div id="idle-timeout-overlay" class="fixed inset-0 z-[9999] hidden glass-morphism flex items-center justify-center transition-opacity duration-300" style="z-index: 9999;">
            <div class="bg-white p-8 rounded-lg shadow-2xl max-w-sm w-full mx-4 transform transition-all">
                <div class="text-center mb-6">
                    <svg class="mx-auto h-12 w-12 text-indigo-600 mb-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"/>
                    </svg>
                    <h3 class="text-xl font-bold text-gray-900">System Locked</h3>
                    <p class="text-sm text-gray-500 mt-2">The system was locked due to inactivity. Please enter your password to unlock.</p>
                </div>
                
                <form id="unlock-form" class="space-y-4">
                    <div>
                        <label for="unlock_password" class="sr-only">Password</label>
                        <input type="password" id="unlock_password" placeholder="Enter Password" required
                            class="appearance-none rounded-md relative block w-full px-3 py-2 border border-gray-300 placeholder-gray-500 text-gray-900 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 focus:z-10 sm:text-sm">
                    </div>
                    <div id="unlock-error" class="hidden text-sm text-red-600 bg-red-50 p-2 rounded"></div>
                    <button type="submit" id="unlock-submit"
                        class="group relative w-full flex justify-center py-2 px-4 border border-transparent text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                        Unlock System
                    </button>
                </form>
            </div>
        </div>

        <div id="idle-warning-toast" class="fixed bottom-4 right-4 z-[9998] hidden bg-yellow-50 border border-yellow-200 p-4 rounded-lg shadow-lg">
            <div class="flex flex-col sm:flex-row items-center">
                <div class="flex-shrink-0 mb-2 sm:mb-0">
                    <svg class="h-6 w-6 text-yellow-500" viewBox="0 0 20 20" fill="currentColor">
                        <path fill-rule="evenodd" d="M8.257 3.099c.765-1.36 2.722-1.36 3.486 0l5.58 9.92c.75 1.334-.213 2.98-1.742 2.98H4.42c-1.53 0-2.493-1.646-1.743-2.98l5.58-9.92zM11 13a1 1 0 11-2 0 1 1 0 012 0zm-1-8a1 1 0 00-1 1v3a1 1 0 002 0V6a1 1 0 00-1-1z" clip-rule="evenodd"/>
                    </svg>
                </div>
                <div class="ml-0 sm:ml-3 text-center sm:text-left">
                    <h3 class="text-sm font-bold text-yellow-800">Inactivity Warning</h3>
                    <div class="mt-1 text-sm text-yellow-700">
                        <p>System will lock in <span id="idle-countdown" class="font-bold text-lg text-red-600">20</span> seconds.</p>
                    </div>
                </div>
            </div>
        </div>

        <script>
            window.SYSTEM_TIMEOUT_MINUTES = <?= $idleTimeoutMinutes ?>;
            window.SYSTEM_IS_LOCKED = false;
            
            (function() {
                const LOCK_KEY = 'system_is_locked_<?= $_SESSION['user']['id'] ?? 'none' ?>';
                const TIMEOUT_MS = window.SYSTEM_TIMEOUT_MINUTES * 60 * 1000;
                // Using 20s warning duration
                const WARNING_MS = 20 * 1000; 
                let lastActivity = Date.now();
                let checkInterval;

                const overlay = document.getElementById('idle-timeout-overlay');
                const warningToast = document.getElementById('idle-warning-toast');
                const countdownSpan = document.getElementById('idle-countdown');
                const form = document.getElementById('unlock-form');
                const passwordInput = document.getElementById('unlock_password');
                const errorDiv = document.getElementById('unlock-error');
                const submitBtn = document.getElementById('unlock-submit');

                function updateActivity() {
                    if (!window.SYSTEM_IS_LOCKED) {
                        lastActivity = Date.now();
                        hideWarning();
                    }
                }

                // Global function for manual instant lock
                window.lockSystem = function() {
                    localStorage.setItem(LOCK_KEY, Date.now().toString());
                    showLock();
                };

                function showLock() {
                    if (window.SYSTEM_IS_LOCKED) return;
                    window.SYSTEM_IS_LOCKED = true;
                    hideWarning();
                    overlay.classList.remove('hidden');
                    // Ensure full scroll lock
                    document.body.style.overflow = 'hidden';
                    passwordInput.value = '';
                    passwordInput.focus();
                }

                function showWarning(timeLeft) {
                    if (window.SYSTEM_IS_LOCKED) return;
                    warningToast.classList.remove('hidden');
                    countdownSpan.textContent = Math.ceil(timeLeft / 1000);
                }

                function hideWarning() {
                    warningToast.classList.add('hidden');
                }

                function checkIdle() {
                    if (window.SYSTEM_IS_LOCKED) return;
                    
                    // Check localstorage for cross-tab locking
                    if (localStorage.getItem(LOCK_KEY)) {
                        showLock();
                        return;
                    }

                    const idleTime = Date.now() - lastActivity;
                    
                    if (idleTime >= TIMEOUT_MS) {
                        window.lockSystem(); // Triggers across tabs
                    } else if (idleTime >= (TIMEOUT_MS - WARNING_MS)) {
                        showWarning(TIMEOUT_MS - idleTime);
                    } else {
                        hideWarning();
                    }
                }

                // Event Listeners for Activity
                if (window.SYSTEM_TIMEOUT_MINUTES > 0) {
                    ['mousedown', 'mousemove', 'keydown', 'scroll', 'touchstart'].forEach(evt => {
                        document.addEventListener(evt, updateActivity, {passive: true});
                    });
                }

                // Listen for locks/unlocks from other tabs
                window.addEventListener('storage', (e) => {
                    if (e.key === LOCK_KEY) {
                        if (e.newValue) {
                            showLock();
                        } else {
                            // Unlocked from another tab
                            window.SYSTEM_IS_LOCKED = false;
                            overlay.classList.add('hidden');
                            document.body.style.overflow = '';
                            lastActivity = Date.now();
                        }
                    }
                });

                // Initial Setup
                if (localStorage.getItem(LOCK_KEY)) {
                    showLock();
                } 
                
                if (window.SYSTEM_TIMEOUT_MINUTES > 0) {
                    checkInterval = setInterval(checkIdle, 1000);
                }

                // Unlock Form Submission
                form.addEventListener('submit', async (e) => {
                    e.preventDefault();
                    errorDiv.classList.add('hidden');
                    submitBtn.disabled = true;
                    submitBtn.innerHTML = 'Unlocking...';

                    try {
                        const response = await fetch('/auth/unlock', {
                            method: 'POST',
                            headers: {
                                'Content-Type': 'application/x-www-form-urlencoded',
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: 'password=' + encodeURIComponent(passwordInput.value)
                        });
                        
                        const result = await response.json();
                        
                        if (result.success) {
                            // Unlock success
                            localStorage.removeItem(LOCK_KEY);
                            window.SYSTEM_IS_LOCKED = false;
                            overlay.classList.add('hidden');
                            document.body.style.overflow = '';
                            lastActivity = Date.now();
                        } else {
                            errorDiv.textContent = result.message || 'Invalid password';
                            errorDiv.classList.remove('hidden');
                        }
                    } catch (err) {
                        errorDiv.textContent = 'Error verifying password. Check connection.';
                        errorDiv.classList.remove('hidden');
                    } finally {
                        submitBtn.disabled = false;
                        submitBtn.innerHTML = 'Unlock System';
                        passwordInput.value = '';
                        passwordInput.focus();
                    }
                });
            })();
        </script>
        <?php endif; ?>
</body>
</html>