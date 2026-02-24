<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <?php
    require_once ROOT_PATH . '/app/Helpers/TemplateHelper.php';
    $settings = getSchoolSettings();
    ?>
    <title><?= $title ?? 'Portal' ?> - <?= htmlspecialchars($settings['school_name']) ?></title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script>
        tailwind.config = {
            theme: {
                extend: {
                    fontFamily: {
                        sans: ['Inter', 'sans-serif'],
                    },
                    colors: {
                        glass: {
                            100: 'rgba(255, 255, 255, 0.1)',
                            200: 'rgba(255, 255, 255, 0.2)',
                            300: 'rgba(255, 255, 255, 0.3)',
                            400: 'rgba(255, 255, 255, 0.4)',
                        }
                    }
                }
            }
        }
    </script>
    <style>
        body {
            /* Deep, rich gradient background for maximum contrast with glass */
            background: linear-gradient(135deg, #1a2a6c 0%, #b21f1f 50%, #fdbb2d 100%);
            background: linear-gradient(135deg, #0f2027 0%, #203a43 50%, #2c5364 100%); /* Alternative Deep Ocean */
            background-attachment: fixed;
            min-height: 100vh;
        }

        /* High-Legibility Glassmorphism */
        .glass-panel {
            background: rgba(255, 255, 255, 0.1); 
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.15);
            box-shadow: 0 8px 32px 0 rgba(0, 0, 0, 0.37);
        }

        .glass-panel-dark {
            background: rgba(0, 0, 0, 0.2); 
            backdrop-filter: blur(16px);
            -webkit-backdrop-filter: blur(16px);
            border: 1px solid rgba(255, 255, 255, 0.1);
            box-shadow: 0 4px 16px 0 rgba(0, 0, 0, 0.2);
        }

        .glass-input {
            background: rgba(0, 0, 0, 0.2);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            transition: all 0.3s ease;
        }
        .glass-input::placeholder {
            color: rgba(255, 255, 255, 0.6);
        }
        .glass-input:focus {
            background: rgba(0, 0, 0, 0.4);
            border-color: rgba(255, 255, 255, 0.6);
            outline: none;
            box-shadow: 0 0 0 2px rgba(255, 255, 255, 0.1);
        }

        .glass-btn {
            background: rgba(255, 255, 255, 0.15);
            border: 1px solid rgba(255, 255, 255, 0.2);
            color: white;
            transition: all 0.2s ease;
            backdrop-filter: blur(4px);
        }
        .glass-btn:hover {
            background: rgba(255, 255, 255, 0.25);
            transform: translateY(-1px);
        }
        .glass-btn:active {
            transform: translateY(0);
        }

        /* Nav Link Active State */
        .nav-link-active {
            background: rgba(255, 255, 255, 0.2);
            border-bottom: 2px solid rgba(255, 255, 255, 0.8);
        }

        /* Toast Animations */
        @keyframes slideInRight {
            from { transform: translateX(100%); opacity: 0; }
            to { transform: translateX(0); opacity: 1; }
        }
        .toast-enter {
            animation: slideInRight 0.4s ease-out forwards;
        }
    </style>
</head>
<body class="font-sans antialiased text-white flex flex-col">
    
    <!-- Navbar (Only if logged in) -->
    <?php if (isset($_SESSION['student_logged_in']) || isset($_SESSION['parent_logged_in']) || isset($_SESSION['staff_portal_logged_in'])): ?>
    <nav class="glass-panel sticky top-0 z-50 border-b-0 rounded-b-xl mx-2 mt-2 px-4 shadow-lg">
        <div class="max-w-7xl mx-auto">
            <div class="flex items-center justify-between h-16">
                <div class="flex items-center gap-8">
                    <div class="flex-shrink-0 flex items-center gap-3">
                        <!-- Logo Placeholder -->
                        <div class="w-8 h-8 rounded bg-white/20 flex items-center justify-center overflow-hidden">
                            <?php if(!empty($settings['school_logo'])): ?>
                                <img src="<?= htmlspecialchars($settings['school_logo']) ?>" alt="Logo" class="w-full h-full object-cover">
                            <?php else: ?>
                                <span class="font-bold text-lg"><?= substr($settings['school_name'], 0, 1) ?></span>
                            <?php endif; ?>
                        </div>
                        <span class="text-xl font-bold tracking-wider text-white truncate max-w-[200px]"><?= htmlspecialchars($settings['school_name']) ?></span>
                    </div>
                    <div class="hidden md:block">
                        <div class="flex items-baseline space-x-2">
                            <?php 
                                $portalPrefix = '/portal/';
                                if (isset($_SESSION['student_logged_in'])) $portalPrefix .= 'student/';
                                elseif (isset($_SESSION['parent_logged_in'])) $portalPrefix .= 'parent/';
                                elseif (isset($_SESSION['staff_portal_logged_in'])) $portalPrefix .= 'staff/';
                                
                                $currentUri = $_SERVER['REQUEST_URI'];
                            ?>
                            <a href="<?= $portalPrefix ?>dashboard" 
                               class="<?= strpos($currentUri, '/dashboard') !== false ? 'nav-link-active' : '' ?> px-3 py-2 rounded-md text-sm font-medium hover:bg-white/10 transition">
                                Dashboard
                            </a>
                            
                            <?php if (isset($_SESSION['student_logged_in'])): ?>
                                <!-- Profile Link Removed - Moved to Header User Name -->
                            <?php endif; ?>

                            <?php if (isset($_SESSION['student_logged_in']) || isset($_SESSION['parent_logged_in'])): ?>
                                <a href="<?= $portalPrefix ?>academics" 
                                   class="<?= strpos($currentUri, '/academics') !== false ? 'nav-link-active' : '' ?> px-3 py-2 rounded-md text-sm font-medium hover:bg-white/10 transition">
                                    Academics
                                </a>
                                <a href="<?= $portalPrefix ?>fees" 
                                   class="<?= strpos($currentUri, '/fees') !== false ? 'nav-link-active' : '' ?> px-3 py-2 rounded-md text-sm font-medium hover:bg-white/10 transition">
                                    Fees
                                </a>
                            <?php endif; ?>

                            <?php if (isset($_SESSION['staff_portal_logged_in'])): ?>
                                <a href="<?= $portalPrefix ?>timetable" 
                                   class="<?= strpos($currentUri, '/timetable') !== false ? 'nav-link-active' : '' ?> px-3 py-2 rounded-md text-sm font-medium hover:bg-white/10 transition">
                                    Timetable
                                </a>
                                <a href="<?= $portalPrefix ?>academics" 
                                   class="<?= strpos($currentUri, '/academics') !== false ? 'nav-link-active' : '' ?> px-3 py-2 rounded-md text-sm font-medium hover:bg-white/10 transition">
                                    Academics
                                </a>
                            <?php endif; ?>
                        </div>
                    </div>
                </div>
                <div>
                    <div class="ml-4 flex items-center gap-4">
                        <div onclick="openProfileModal()" class="text-right hidden sm:block cursor-pointer group">
                            <p class="text-sm font-medium text-white leading-none group-hover:text-indigo-300 transition-colors">
                                <?php 
                                    if (isset($_SESSION['student_name'])) echo htmlspecialchars($_SESSION['student_name']);
                                    elseif (isset($_SESSION['user_name'])) echo htmlspecialchars($_SESSION['user_name']);
                                ?>
                            </p>
                            <p class="text-xs text-white/60 mt-1 uppercase tracking-wide group-hover:text-white/80 transition-colors">
                                <?= isset($_SESSION['portal_role']) ? ucfirst($_SESSION['portal_role']) : 'User' ?>
                            </p>
                        </div>

                        <!-- Notification Bell -->
                        <div class="relative">
                            <button id="notification-btn" class="glass-btn p-2 rounded-full relative hover:bg-white/20 transition-colors">
                                <svg class="w-5 h-5 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 17h5l-1.405-1.405A2.032 2.032 0 0118 14.158V11a6.002 6.002 0 00-4-5.659V5a2 2 0 10-4 0v.341C7.67 6.165 6 8.388 6 11v3.159c0 .538-.214 1.055-.595 1.436L4 17h5m6 0v1a3 3 0 11-6 0v-1m6 0H9" />
                                </svg>
                                <!-- Counter Badge -->
                                <span id="notification-badge" class="hidden absolute -top-1 -right-1 min-w-[18px] h-[18px] px-1 flex items-center justify-center rounded-full ring-2 ring-white/10 bg-red-500 text-white text-[10px] font-bold leading-none transform"></span>
                            </button>
                            
                            <!-- Notification Dropdown -->
                            <div id="notification-dropdown" class="hidden absolute right-0 mt-2 w-80 bg-slate-900 border border-slate-700 rounded-xl shadow-2xl overflow-hidden z-20 origin-top-right transition-transform">
                                <div class="px-2 py-2 border-b border-slate-700 bg-slate-800">
                                    <h3 class="text-sm font-semibold text-white px-2 mb-2">Notifications</h3>
                                    <!-- Tabs -->
                                    <div class="flex space-x-1 text-xs">
                                        <button onclick="switchNotifTab('all')" id="tab-all" class="flex-1 py-1 px-2 rounded hover:bg-white/10 text-white bg-white/10">All</button>
                                        <button onclick="switchNotifTab('unread')" id="tab-unread" class="flex-1 py-1 px-2 rounded hover:bg-white/10 text-white/60">Unread</button>
                                        <button onclick="switchNotifTab('read')" id="tab-read" class="flex-1 py-1 px-2 rounded hover:bg-white/10 text-white/60">Read</button>
                                        <button onclick="switchNotifTab('archived')" id="tab-archived" class="flex-1 py-1 px-2 rounded hover:bg-white/10 text-white/60">Archived</button>
                                    </div>
                                </div>
                                <div id="notification-list" class="max-h-96 overflow-y-auto min-h-[100px]">
                                    <!-- Listen items will be injected here -->
                                    <div class="p-4 text-center text-white/50 text-sm">Loading...</div>
                                </div>
                            </div>
                        </div>

                        <a href="/portal/logout" id="logout-btn" class="glass-btn px-4 py-2 rounded-md text-sm font-medium hover:bg-red-500/50 hover:border-red-400 group flex items-center gap-2">
                             <span>Logout</span>
                             <svg xmlns="http://www.w3.org/2000/svg" class="h-4 w-4 group-hover:translate-x-1 transition-transform" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 16l4-4m0 0l-4-4m4 4H7m6 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h4a3 3 0 013 3v1" />
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>
    <?php endif; ?>

    <!-- Toast Container -->
    <div id="toast-container" class="fixed top-4 right-4 z-[100] flex flex-col gap-2 pointer-events-none">
        <!-- Toasts injected here -->
    </div>

    <!-- Content -->
    <main class="flex-grow container mx-auto px-4 py-8">
        <?php if (isset($_SESSION['portal_flash_error'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    showToast("<?= $_SESSION['portal_flash_error'] ?>", 'error');
                });
            </script>
            <?php unset($_SESSION['portal_flash_error']); ?>
        <?php endif; ?>

        <?php if (isset($_SESSION['portal_flash_success'])): ?>
            <script>
                document.addEventListener('DOMContentLoaded', () => {
                    showToast("<?= $_SESSION['portal_flash_success'] ?>", 'success');
                });
            </script>
            <?php unset($_SESSION['portal_flash_success']); ?>
        <?php endif; ?>

        <?= $content ?? '' ?>
    </main>

    <footer class="mt-auto py-8">
        <div class="text-center text-white/40 text-sm">
            &copy; <?= date('Y') ?> School Portal System. <span class="mx-2">•</span> Secure Access
        </div>
    </footer>

    <!-- Logout Confirmation Modal -->
    <div id="logout-confirm-modal" class="hidden fixed inset-0 z-[200] flex items-center justify-center p-4 bg-black/60 backdrop-blur-sm transition-opacity duration-300">
        <div class="glass-panel p-6 rounded-xl shadow-2xl w-full max-w-sm transform scale-100 transition-transform duration-300 text-center">
            <div class="mx-auto flex items-center justify-center h-12 w-12 rounded-full bg-red-400/20 mb-4 text-red-100">
                <svg class="h-6 w-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 9v2m0 4h.01m-6.938 4h13.856c1.54 0 2.502-1.667 1.732-3L13.732 4c-.77-1.333-2.694-1.333-3.464 0L3.34 16c-.77 1.333.192 3 1.732 3z" />
                </svg>
            </div>
            <h3 class="text-xl font-bold text-white mb-2">Confirm Logout</h3>
            <p class="text-white/70 text-sm mb-6">
                Are you sure you want to log out of the portal?
            </p>
            <div class="flex justify-center gap-4">
                <button id="cancel-logout" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-medium rounded-lg transition-colors border border-white/10">
                    Cancel
                </button>
                <a href="/portal/logout" class="px-4 py-2 bg-red-500 hover:bg-red-600 text-white text-sm font-medium rounded-lg shadow-lg transition-colors border border-transparent">
                    Logout
                </a>
            </div>
        </div>
    </div>
    
    <!-- Notification View Modal -->
    <div id="notification-view-modal" class="hidden fixed inset-0 z-[250] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 p-0 rounded-xl shadow-2xl w-full max-w-lg overflow-hidden flex flex-col max-h-[80vh]">
            <div class="p-6 border-b border-white/10 bg-white/5">
                <h3 id="notif-modal-title" class="text-xl font-bold text-white leading-tight"></h3>
                <p id="notif-modal-date" class="text-xs text-white/50 mt-1"></p>
            </div>
            <div class="p-6 overflow-y-auto flex-1">
                <div id="notif-modal-body" class="text-white/90 text-sm whitespace-pre-wrap leading-relaxed"></div>
                <div id="notif-modal-attachment" class="mt-6 pt-4 border-t border-white/10 hidden">
                    <a href="#" target="_blank" class="flex items-center gap-2 text-indigo-300 hover:text-white transition-colors p-3 rounded-lg bg-white/5 hover:bg-white/10">
                         <svg class="w-5 h-5 flex-shrink-0" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 10v6m0 0l-3-3m3 3l3-3m2 8H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                        </svg>
                        <span class="truncate">Download Attachment</span>
                    </a>
                </div>
            </div>
            <div class="p-4 bg-black/20 border-t border-white/10 text-right">
                <button onclick="closeNotificationModal()" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-medium rounded-lg transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>
    
    <!-- Profile View Modal -->
    <div id="profile-view-modal" class="hidden fixed inset-0 z-[250] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 p-0 rounded-xl shadow-2xl w-full max-w-4xl overflow-hidden flex flex-col md:flex-row h-[500px]">
            
            <!-- Left Sidebar: Avatar & Tabs -->
            <div class="w-full md:w-64 bg-slate-800/50 border-r border-slate-700 flex flex-col">
                <div class="p-6 flex flex-col items-center border-b border-slate-700">
                    <div class="w-24 h-24 rounded-full bg-slate-800 border-4 border-indigo-500/50 flex items-center justify-center overflow-hidden mb-3 shadow-lg">
                        <img id="prof-img" src="" alt="Profile" class="w-full h-full object-cover hidden">
                        <span id="prof-initials" class="text-3xl font-bold text-white"></span>
                    </div>
                    <h3 id="prof-name" class="text-base font-bold text-white text-center leading-tight mb-1"></h3>
                    <p id="prof-role" class="text-xs text-indigo-400 uppercase tracking-widest font-semibold">Student</p>
                </div>
                
                <!-- Navigation Tabs -->
                <nav class="flex-1 overflow-y-auto py-4 space-y-1 px-3">
                    <button onclick="switchProfileTab('overview')" id="btn-tab-overview" class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-white bg-white/10 transition-colors">
                        <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" /></svg>
                        Overview
                    </button>
                    <button onclick="switchProfileTab('academic')" id="btn-tab-academic" class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                        <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253" /></svg>
                        Academic
                    </button>
                    <button onclick="switchProfileTab('contact')" id="btn-tab-contact" class="w-full flex items-center gap-3 px-3 py-2 text-sm font-medium rounded-lg text-slate-400 hover:text-white hover:bg-white/5 transition-colors">
                        <svg class="w-5 h-5 opacity-70" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" /></svg>
                        Contact Info
                    </button>
                </nav>
                
                <div class="p-4 border-t border-slate-700">
                    <button onclick="closeProfileModal()" class="w-full px-4 py-2 border border-slate-600 hover:bg-slate-700 text-slate-300 text-xs font-bold uppercase tracking-wider rounded-lg transition-colors">
                        Close
                    </button>
                </div>
            </div>
            
            <!-- Main Content -->
            <div class="flex-1 bg-slate-900 overflow-y-auto relative">
                <div id="profile-loading" class="absolute inset-0 flex items-center justify-center bg-slate-900 z-10">
                    <span class="text-white/50 animate-pulse">Loading profile...</span>
                </div>
                
                <div id="profile-content" class="p-8 hidden">
                    
                    <!-- Tab: Overview -->
                    <div id="tab-content-overview" class="space-y-6">
                        <div class="flex justify-between items-center mb-6">
                            <h2 class="text-2xl font-bold text-white">Profile Overview</h2>
                            <span id="prof-status" class="px-3 py-1 rounded-full text-xs font-bold uppercase tracking-wider bg-green-500/20 text-green-400 border border-green-500/30">Active</span>
                        </div>
                        
                        <div class="grid grid-cols-1 gap-6">
                            <div class="glass-btn p-4 rounded-xl border border-white/5 bg-white/5">
                                <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Full Name</p>
                                <p id="prof-full-name" class="text-lg text-white font-medium">-</p>
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <div class="glass-btn p-4 rounded-xl border border-white/5 bg-white/5">
                                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Date of Birth</p>
                                    <p id="prof-dob" class="text-white font-medium">-</p>
                                </div>
                                <div class="glass-btn p-4 rounded-xl border border-white/5 bg-white/5">
                                    <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Gender</p>
                                    <p id="prof-gender" class="text-white font-medium capitalize">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab: Academic -->
                    <div id="tab-content-academic" class="hidden space-y-6">
                        <h2 class="text-2xl font-bold text-white mb-6">Academic Information</h2>
                        <div class="grid grid-cols-1 md:grid-cols-2 gap-6">
                            <div class="glass-btn p-4 rounded-xl border border-white/5 bg-white/5">
                                <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Admission Number</p>
                                <p id="prof-admission" class="text-lg text-white font-mono">-</p>
                            </div>
                            <div class="glass-btn p-4 rounded-xl border border-white/5 bg-white/5">
                                <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Current Class</p>
                                <p id="prof-class" class="text-lg text-white">-</p>
                            </div>
                            <div class="glass-btn p-4 rounded-xl border border-white/5 bg-white/5 col-span-2">
                                <p class="text-xs text-slate-400 uppercase tracking-wider mb-1">Enrollment Date</p>
                                <p id="prof-joined" class="text-white">-</p>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Tab: Contact -->
                    <div id="tab-content-contact" class="hidden space-y-6">
                        <h2 class="text-2xl font-bold text-white mb-6">Contact Details</h2>
                         <div class="space-y-6">
                            <div class="border-b border-slate-700 pb-4">
                                <h4 class="text-sm font-semibold text-indigo-400 mb-4">Guardian Information</h4>
                                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                                    <div>
                                        <p class="text-xs text-slate-500 mb-1">Name</p>
                                        <p id="prof-guardian" class="text-white">-</p>
                                    </div>
                                    <div>
                                        <p class="text-xs text-slate-500 mb-1">Phone Number</p>
                                        <p id="prof-contact" class="text-white">-</p>
                                    </div>
                                </div>
                            </div>
                             <div>
                                <h4 class="text-sm font-semibold text-indigo-400 mb-4">Address</h4>
                                <div class="glass-btn p-4 rounded-xl border border-white/5 bg-white/5">
                                    <p id="prof-address" class="text-white/80 leading-relaxed">-</p>
                                </div>
                            </div>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

    <!-- Staff Subjects Modal -->
    <div id="staff-subjects-modal" class="hidden fixed inset-0 z-[250] flex items-center justify-center p-4 bg-black/80 backdrop-blur-sm">
        <div class="bg-slate-900 border border-slate-700 p-0 rounded-xl shadow-2xl w-full max-w-2xl overflow-hidden flex flex-col max-h-[80vh]">
            <div class="p-6 border-b border-white/10 bg-white/5 flex justify-between items-center">
                <h3 class="text-xl font-bold text-white leading-tight">My Subjects</h3>
                <button onclick="document.getElementById('staff-subjects-modal').classList.add('hidden')" class="text-white/50 hover:text-white">
                    <svg class="w-6 h-6" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                    </svg>
                </button>
            </div>
            <div class="p-6 overflow-y-auto flex-1">
                <div id="staff-subjects-list" class="space-y-3">
                    <!-- Subjects injected here -->
                    <div class="p-4 text-center text-white/50 text-sm">Loading...</div>
                </div>
            </div>
            <div class="p-4 bg-black/20 border-t border-white/10 text-right">
                <button onclick="document.getElementById('staff-subjects-modal').classList.add('hidden')" class="px-4 py-2 bg-white/10 hover:bg-white/20 text-white text-sm font-medium rounded-lg transition-colors">
                    Close
                </button>
            </div>
        </div>
    </div>

    <!-- Toast Logic & Modal Logic -->
    <script>
        // Staff Subjects Modal Logic
        function openStaffSubjectsModal() {
            const modal = document.getElementById('staff-subjects-modal');
            const list = document.getElementById('staff-subjects-list');
            
            modal.classList.remove('hidden');
            list.innerHTML = '<div class="p-4 text-center text-white/50 text-sm">Loading...</div>';
            
            fetch('/portal/staff/subjects-data')
                .then(res => res.json())
                .then(data => {
                    if (data.subjects && data.subjects.length > 0) {
                        list.innerHTML = data.subjects.map(sub => `
                            <div class="bg-white/5 p-4 rounded-lg flex justify-between items-center border border-white/5 hover:bg-white/10 transition">
                                <div class="flex items-center gap-4">
                                    <div class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-300 font-bold text-sm">
                                        ${sub.name.charAt(0)}
                                    </div>
                                    <div>
                                        <p class="font-bold text-white">${escapeHtml(sub.name)}</p>
                                        <p class="text-white/60 text-xs uppercase tracking-wide">${escapeHtml(sub.code)}</p>
                                    </div>
                                </div>
                                ${sub.class_name ? `<span class="px-3 py-1 bg-white/10 text-white rounded-full text-xs font-semibold">${escapeHtml(sub.class_name)}</span>` : ''}
                            </div>
                        `).join('');
                    } else {
                        list.innerHTML = '<div class="p-4 text-center text-white/50 text-sm">No subjects assigned.</div>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    list.innerHTML = '<div class="p-4 text-center text-red-300 text-sm">Failed to load subjects.</div>';
                });
        }
        
        <?php
        // Robust Role Detection Strategy
        $portalRole = 'student'; // Default

        // 0. Priority: Explicit View Variable (Passed from Controller)
        // Check for both camelCase and snake_case versions
        if (isset($portalRole)) {
            // Already set, keep it (camelCase)
        } elseif (isset($portal_role)) {
            $portalRole = $portal_role; // From controller (snake_case)
        } else {
            // 1. URL Context / Session Fallback
            $uri = $_SERVER['REQUEST_URI'] ?? '';
            
            // Case-insensitive check for stronger matching
            if (stripos($uri, '/portal/staff/') !== false) {
                $portalRole = 'staff';
            } elseif (stripos($uri, '/portal/parent/') !== false) {
                $portalRole = 'parent';
            } elseif (stripos($uri, '/portal/student/') !== false) {
                $portalRole = 'student';
            } else {
                // 2. Fallback: Session Flag
                $portalRole = $_SESSION['portal_role'] ?? 'student';
                 if (!isset($_SESSION['portal_role'])) {
                    if (isset($_SESSION['staff_portal_logged_in'])) {
                        $portalRole = 'staff';
                    } elseif (isset($_SESSION['parent_logged_in'])) {
                        $portalRole = 'parent';
                    } elseif (isset($_SESSION['student_logged_in'])) {
                        $portalRole = 'student';
                    }
                }
            }
        }
        ?>
        const portalRole = "<?= $portalRole ?>";
        console.log("Detected Portal Role:", portalRole); // Debug
        // Modal Logic
        const logoutBtn = document.getElementById('logout-btn');
        const logoutModal = document.getElementById('logout-confirm-modal');
        const cancelLogout = document.getElementById('cancel-logout');

        if (logoutBtn) {
            logoutBtn.addEventListener('click', (e) => {
                e.preventDefault();
                logoutModal.classList.remove('hidden');
            });
        }

        if (cancelLogout) {
            cancelLogout.addEventListener('click', () => {
                logoutModal.classList.add('hidden');
            });
        }

        // Close on outside click
        if (logoutModal) {
            logoutModal.addEventListener('click', (e) => {
                if (e.target === logoutModal) {
                    logoutModal.classList.add('hidden');
                }
            });
        }

        function showToast(message, type = 'info') {
            const container = document.getElementById('toast-container');
            const toast = document.createElement('div');
            
            // Icon & Color Logic
            let bgClass = 'bg-white/10';
            let borderClass = 'border-white/20';
            let icon = '';
            
            if (type === 'success') {
                bgClass = 'bg-green-500/30';
                borderClass = 'border-green-400/50';
                icon = `<svg class="w-5 h-5 text-green-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 13l4 4L19 7"></path></svg>`;
            } else if (type === 'error') {
                bgClass = 'bg-red-500/30';
                borderClass = 'border-red-400/50';
                icon = `<svg class="w-5 h-5 text-red-200" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>`;
            }

            toast.className = `toast-enter pointer-events-auto w-80 p-4 rounded-lg shadow-lg backdrop-blur-md border flex items-start gap-3 text-sm text-white ${bgClass} ${borderClass}`;
            toast.innerHTML = `
                <div class="flex-shrink-0 mt-0.5">${icon}</div>
                <div class="flex-1 font-medium">${message}</div>
                <button onclick="this.parentElement.remove()" class="text-white/50 hover:text-white transition">
                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                </button>
            `;

            container.appendChild(toast);

            // Auto dismiss
            setTimeout(() => {
                toast.style.opacity = '0';
                toast.style.transform = 'translateY(-10px)';
                toast.style.transition = 'all 0.3s ease';
                setTimeout(() => toast.remove(), 300);
            }, 5000);
        }
    </script>
    <!-- Suspension/Terminated Overlay -->
    <div id="session-overlay" class="hidden fixed inset-0 z-[300] bg-black/80 backdrop-blur-md flex flex-col items-center justify-center p-6 text-center">
        <div class="glass-panel p-8 rounded-2xl max-w-md w-full border-red-500/30">
            <div class="mx-auto flex items-center justify-center h-16 w-16 rounded-full bg-red-500/20 mb-6 text-red-500 animate-pulse">
                <svg class="h-8 w-8" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z" />
                </svg>
            </div>
            <h2 class="text-2xl font-bold text-white mb-2" id="overlay-title">Session Ended</h2>
            <p class="text-white/70 mb-6" id="overlay-message">Your session has been terminated.</p>
            <div class="w-full bg-gray-700 rounded-full h-1.5 mb-1 overflow-hidden">
                <div class="bg-red-500 h-1.5 rounded-full w-full animate-[width_5s_linear]"></div>
            </div>
            <p class="text-xs text-white/40 mt-2">Logging out in <span id="overlay-timer">5</span>s...</p>
        </div>
    </div>

    <script>
        // Check Status every 10 seconds - Only if logged in
        <?php if (isset($_SESSION['portal_session_token'])): ?>
        setInterval(checkSessionStatus, 10000);
        
        // Initial check
        checkSessionStatus();

        function checkSessionStatus() {
            fetch('/portal/check-status')
                .then(response => response.json())
                .then(data => {
                    if (data.status !== 'ok') {
                        showSessionOverlay(data.status, data.reason);
                    } else if (data.unread_notifications !== undefined) {
                        updateNotificationBadge(data.unread_notifications);
                    }
                })
                .catch(err => console.error('Heartbeat error:', err));
        }
        
        function updateNotificationBadge(count) {
            const badge = document.getElementById('notification-badge');
            if (count > 0) {
                badge.textContent = count > 99 ? '99+' : count;
                badge.classList.remove('hidden');
            } else {
                badge.classList.add('hidden');
            }
        }

        function showSessionOverlay(status, reason) {
            const overlay = document.getElementById('session-overlay');
            const title = document.getElementById('overlay-title');
            const message = document.getElementById('overlay-message');
            
            overlay.classList.remove('hidden');
            
            if (status === 'suspended') {
                title.textContent = 'Account Suspended';
                message.textContent = 'Your account has been suspended or deactivated. Please contact school administration to reactivate your account.';
            } else if (status === 'terminated') {
                title.textContent = 'Session Expired';
                message.textContent = 'Your session has been ended by an administrator or has expired. Please log in again.';
            }

            let seconds = 5;
            const timerSpan = document.getElementById('overlay-timer');
            
            const timer = setInterval(() => {
                seconds--;
                if(timerSpan) timerSpan.textContent = seconds;
                if (seconds <= 0) {
                    clearInterval(timer);
                    window.location.href = '/portal/logout';
                }
            }, 1000);
        }
        
        // Notification Logic
        const notifBtn = document.getElementById('notification-btn');
        const notifDropdown = document.getElementById('notification-dropdown');
        let currentFilter = 'all';
        
        if (notifBtn) {
            notifBtn.addEventListener('click', (e) => {
                e.stopPropagation();
                notifDropdown.classList.toggle('hidden');
                if (!notifDropdown.classList.contains('hidden')) {
                    fetchNotifications();
                }
            });
            
            // Close dropdown when clicking outside
            document.addEventListener('click', (e) => {
                if (!notifDropdown.contains(e.target) && !notifBtn.contains(e.target)) {
                    notifDropdown.classList.add('hidden');
                }
            });
        }

        function switchNotifTab(filter) {
            currentFilter = filter;
            
            // Update Tab UI
            ['all', 'unread', 'read', 'archived'].forEach(f => {
                const tab = document.getElementById('tab-' + f);
                if (f === filter) {
                    tab.classList.remove('text-white/60');
                    tab.classList.add('text-white', 'bg-white/10');
                } else {
                    tab.classList.add('text-white/60');
                    tab.classList.remove('text-white', 'bg-white/10');
                }
            });

            fetchNotifications();
        }
        
        function fetchNotifications() {
            const list = document.getElementById('notification-list');
            list.innerHTML = '<div class="p-4 text-center text-white/50 text-sm">Loading...</div>';
            
            fetch('/portal/notifications/list?filter=' + currentFilter)
                .then(res => res.json())
                .then(data => {
                    if (data.notifications && data.notifications.length > 0) {
                        list.innerHTML = data.notifications.map(n => `
                            <div class="p-3 border-b border-white/5 hover:bg-white/5 group relative ${n.is_read == 0 ? 'bg-white/5 border-l-2 border-l-red-500' : ''}">
                                <div onclick='openNotificationView(${JSON.stringify(n)})' class="cursor-pointer">
                                    <div class="flex justify-between items-start mb-1 pr-6">
                                        <h4 class="text-sm font-semibold text-white truncate">${escapeHtml(n.title)}</h4>
                                    </div>
                                    <p class="text-xs text-white/60 line-clamp-2">${escapeHtml(n.message)}</p>
                                    <span class="text-[10px] text-white/40 whitespace-nowrap mt-1 block">${formatDate(n.created_at)}</span>
                                </div>
                                <div class="absolute top-2 right-2 flex gap-1 opacity-100 sm:opacity-0 group-hover:opacity-100 transition-opacity">
                                    ${currentFilter !== 'archived' ? `
                                        <button onclick="archiveNotification(${n.id})" title="Archive" class="p-1 hover:bg-white/10 rounded text-gray-400 hover:text-white">
                                            <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M5 8h14M5 8a2 2 0 110-4h14a2 2 0 110 4M5 8v10a2 2 0 002 2h10a2 2 0 002-2V8m-9 4h4" />
                                            </svg>
                                        </button>
                                    ` : ''}
                                    <button onclick="deleteNotification(${n.id})" title="Delete" class="p-1 hover:bg-white/10 rounded text-red-400 hover:text-red-300">
                                        <svg class="w-4 h-4" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 7l-.867 12.142A2 2 0 0116.138 21H7.862a2 2 0 01-1.995-1.858L5 7m5 4v6m4-6v6m1-10V4a1 1 0 00-1-1h-4a1 1 0 00-1 1v3M4 7h16" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        `).join('');
                    } else {
                        list.innerHTML = '<div class="p-4 text-center text-white/50 text-sm">No notifications found</div>';
                    }
                })
                .catch(err => {
                    console.error(err);
                    list.innerHTML = '<div class="p-4 text-center text-red-300 text-sm">Failed to load</div>';
                });
        }
        
        function openNotificationView(n) {
            // Populate Modal
            document.getElementById('notif-modal-title').textContent = n.title;
            document.getElementById('notif-modal-date').textContent = new Date(n.created_at).toLocaleString();
            document.getElementById('notif-modal-body').textContent = n.message;
            
            const attachmentDiv = document.getElementById('notif-modal-attachment');
            if (n.attachment_path) {
                attachmentDiv.classList.remove('hidden');
                attachmentDiv.querySelector('a').href = '/storage/uploads/' + n.attachment_path;
            } else {
                attachmentDiv.classList.add('hidden');
            }
            
            document.getElementById('notification-view-modal').classList.remove('hidden');
            document.getElementById('notification-dropdown').classList.add('hidden');
            
            // Mark Read
            if (n.is_read == 0) {
                fetch('/portal/notifications/mark-read', {
                    method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'id=' + n.id
                }).then(() => {
                     // Check status again to update badge
                     checkSessionStatus();
                     // Don't refresh list immediately to avoid "jumping" if filtering by unread
                });
            }
        }
        
        function archiveNotification(id) {
            fetch('/portal/notifications/archive', {
                method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'id=' + id
            }).then(() => {
                fetchNotifications();
                checkSessionStatus();
            });
        }
        
        function deleteNotification(id) {
            if(!confirm('Are you sure you want to delete this notification?')) return;
            
            fetch('/portal/notifications/delete', {
                method: 'POST',
                    headers: {'Content-Type': 'application/x-www-form-urlencoded'},
                    body: 'id=' + id
            }).then(() => {
                fetchNotifications();
                checkSessionStatus();
            });
        }

        function closeNotificationModal() {
            document.getElementById('notification-view-modal').classList.add('hidden');
        }
        
        // Profile Modal Logic
        function openProfileModal() {
            const modal = document.getElementById('profile-view-modal');
            const loading = document.getElementById('profile-loading');
            const content = document.getElementById('profile-content');
            
            modal.classList.remove('hidden');
            loading.classList.remove('hidden');
            content.classList.add('hidden');
            
            // Reset Tabs
            switchProfileTab('overview');
            
            // Determine Endpoint
            let url = '/portal/student/profile-data';
            if (portalRole === 'staff') {
                url = '/portal/staff/profile-data';
            } else if (portalRole === 'parent') {
                url = '/portal/parent/profile-data';
            }
            
            fetch(url)
                .then(res => {
                    if(!res.ok) throw new Error('Failed to load');
                    return res.json();
                })
                .then(data => {
                    const s = data.student || data.staff;
                    const fullName = s.first_name + ' ' + s.last_name;
                    
                    // Specific Logic for Staff vs Student
                    if (portalRole === 'staff') {
                         // Header
                        document.getElementById('prof-name').textContent = fullName;
                        document.getElementById('prof-role').textContent = s.position || 'Staff'; // Update Role Label
                        
                        // Overview Tab
                        document.getElementById('prof-full-name').textContent = fullName;
                        document.getElementById('prof-dob').textContent = 'N/A'; // Staff DOB not exposed
                        document.getElementById('prof-dob').parentElement.classList.add('hidden'); // Hide DOB for staff
                        document.getElementById('prof-gender').textContent = 'N/A';
                        document.getElementById('prof-gender').parentElement.classList.add('hidden'); // Hide Gender for staff

                        // Academic Tab -> Employment Tab
                        document.getElementById('prof-admission').textContent = s.employee_id;
                        document.getElementById('prof-admission').previousElementSibling.textContent = 'Employee ID';
                        
                        document.getElementById('prof-class').textContent = s.department || 'N/A';
                        document.getElementById('prof-class').previousElementSibling.textContent = 'Department';
                        
                        document.getElementById('prof-joined').textContent = s.hire_date || 'N/A';
                        document.getElementById('prof-joined').previousElementSibling.textContent = 'Hire Date';
                        
                        // Contact Tab
                        document.getElementById('prof-guardian').textContent = s.email || 'N/A'; // Use Email slot
                        document.getElementById('prof-guardian').previousElementSibling.textContent = 'Email';
                        
                        document.getElementById('prof-contact').textContent = s.phone || 'N/A';
                        document.getElementById('prof-contact').previousElementSibling.textContent = 'Phone';
                        
                        document.getElementById('prof-address').textContent = 'N/A'; // Staff address not usually exposed here or not in logic yet
                        
                    } else if (portalRole === 'parent') {
                        // PARENT LOGIC
                        document.getElementById('prof-role').textContent = 'Parent';
                        
                        // Header
                        document.getElementById('prof-name').textContent = s.guardian_name || 'Parent';

                        // Overview Tab
                        document.getElementById('prof-full-name').textContent = s.guardian_name || 'N/A';
                        document.getElementById('prof-dob').parentElement.classList.add('hidden'); // Hide DOB
                        document.getElementById('prof-gender').parentElement.classList.add('hidden'); // Hide Gender

                        // Academic Tab -> Ward Info
                        document.getElementById('prof-admission').textContent = s.admission_no;
                        document.getElementById('prof-admission').previousElementSibling.textContent = 'Ward Admission No';
                        
                        document.getElementById('prof-class').textContent = s.class_name || 'N/A';
                        document.getElementById('prof-class').previousElementSibling.textContent = 'Ward Class';
                        
                        document.getElementById('prof-joined').textContent = s.admission_date || 'N/A';
                        document.getElementById('prof-joined').previousElementSibling.textContent = 'Ward Joined Date';
                        
                        // Contact Tab
                        document.getElementById('prof-guardian').textContent = s.guardian_phone || 'N/A';
                        document.getElementById('prof-guardian').previousElementSibling.textContent = 'Phone Number';
                        
                        document.getElementById('prof-contact').parentElement.classList.add('hidden'); // Hide second contact field
                        
                        document.getElementById('prof-address').textContent = s.address || 'N/A';

                    } else {
                        // STUDENT LOGIC (Default)
                        document.getElementById('prof-role').textContent = 'Student';
                        
                        // Reset Hidden Fields
                        document.getElementById('prof-dob').parentElement.classList.remove('hidden');
                        document.getElementById('prof-gender').parentElement.classList.remove('hidden');
                        document.getElementById('prof-contact').parentElement.classList.remove('hidden');

                        // Header
                        document.getElementById('prof-name').textContent = fullName;
                        // Overview Tab
                        document.getElementById('prof-full-name').textContent = fullName;
                        document.getElementById('prof-dob').textContent = s.date_of_birth || 'N/A';
                        document.getElementById('prof-gender').textContent = s.gender || 'N/A';
                        
                        // Academic Tab
                        document.getElementById('prof-admission').textContent = s.admission_no;
                        document.getElementById('prof-admission').previousElementSibling.textContent = 'Admission Number';
                        
                        document.getElementById('prof-class').textContent = s.class_name || 'N/A';
                        document.getElementById('prof-class').previousElementSibling.textContent = 'Current Class';
                        
                        document.getElementById('prof-joined').textContent = s.admission_date || 'N/A';
                        document.getElementById('prof-joined').previousElementSibling.textContent = 'Enrollment Date';
                        
                        // Contact Tab
                        document.getElementById('prof-guardian').textContent = s.guardian_name || 'N/A';
                        document.getElementById('prof-guardian').previousElementSibling.textContent = 'Guardian Name';
                        
                        document.getElementById('prof-contact').textContent = s.guardian_phone || 'N/A';
                        document.getElementById('prof-contact').previousElementSibling.textContent = 'Guardian Phone';
                        
                        document.getElementById('prof-address').textContent = s.address || 'N/A';
                    }
                    
                    // Initials
                    const initials = (s.first_name[0] || '') + (s.last_name[0] || '');
                    document.getElementById('prof-initials').textContent = initials;
                    
                    // Image (if any)
                    const img = document.getElementById('prof-img');
                    if (s.photo_path) {
                        img.src = '/storage/uploads/' + s.photo_path;
                        img.classList.remove('hidden');
                        document.getElementById('prof-initials').classList.add('hidden');
                    } else {
                        img.classList.add('hidden');
                        document.getElementById('prof-initials').classList.remove('hidden');
                    }
                    
                    loading.classList.add('hidden');
                    content.classList.remove('hidden');
                })
                .catch(err => {
                    console.error(err);
                    loading.classList.add('hidden');
                    // Show error state in modal?
                    alert('Failed to load profile data.');
                    closeProfileModal();
                });
        }
        
        function switchProfileTab(tab) {
            // Hide all contents
            ['overview', 'academic', 'contact'].forEach(t => {
                const btn = document.getElementById('btn-tab-' + t);
                const content = document.getElementById('tab-content-' + t);
                
                if (t === tab) {
                    btn.classList.remove('text-slate-400', 'hover:text-white', 'hover:bg-white/5');
                    btn.classList.add('text-white', 'bg-white/10');
                    content.classList.remove('hidden');
                } else {
                    btn.classList.add('text-slate-400', 'hover:text-white', 'hover:bg-white/5');
                    btn.classList.remove('text-white', 'bg-white/10');
                    content.classList.add('hidden');
                }
            });
        }
        
        function closeProfileModal() {
            document.getElementById('profile-view-modal').classList.add('hidden');
        }
        
        // Helpers
        function escapeHtml(text) {
            if (!text) return '';
            return text
                .replace(/&/g, "&amp;")
                .replace(/</g, "&lt;")
                .replace(/>/g, "&gt;")
                .replace(/"/g, "&quot;")
                .replace(/'/g, "&#039;");
        }
        
        function formatDate(dateStr) {
            const date = new Date(dateStr);
            return date.toLocaleDateString() + ' ' + date.toLocaleTimeString([], {hour: '2-digit', minute:'2-digit'});
        }
        <?php endif; ?>
    </script>
</body>
</html>
