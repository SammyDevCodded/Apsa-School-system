<?php 
$title = 'Student Dashboard'; 
require_once ROOT_PATH . '/app/Helpers/TemplateHelper.php';
$settings = getSchoolSettings();
$currency = $settings['currency_symbol'] ?? '$';
ob_start(); 
?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Welcome Card -->
    <div class="md:col-span-3 glass-panel p-6 rounded-2xl relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl font-bold text-white mb-2">Welcome back, <?= htmlspecialchars($student['first_name']) ?>!</h1>
            <p class="text-white/80">Here's what's happening with your academic progress today.</p>
        </div>
        <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-white/10 to-transparent pointer-events-none"></div>
    </div>

    <!-- Quick Stat 1 -->
    <div class="glass-panel p-6 rounded-xl flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-white/70">Attendance</p>
            <p class="text-2xl font-bold text-white">95%</p>
        </div>
        <div class="p-3 bg-white/20 rounded-full text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

    <!-- Quick Stat 2 -->
    <div class="glass-panel p-6 rounded-xl flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-white/70">Recent Results</p>
            <p class="text-lg font-bold text-white"><?= $exam_count ?? 0 ?> Exams</p>
            <p class="text-xs text-white/60">Released</p>
        </div>
        <div class="p-3 bg-white/20 rounded-full text-white">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
        </div>
    </div>
    
     <!-- Quick Stat 3 -->
    <div class="glass-panel p-6 rounded-xl flex items-center justify-between">
        <div>
            <p class="text-sm font-medium text-white/70">Fee Balance</p>
            <p class="text-2xl font-bold <?= ($fee_balance ?? 0) > 0 ? 'text-red-300' : 'text-green-300' ?>"><?= $currency ?><?= number_format($fee_balance ?? 0, 2) ?></p>
        </div>
        <div class="p-3 bg-white/20 rounded-full text-white">
             <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
        </div>
    </div>

</div>

<!-- Recent Activity / Info -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <div class="glass-panel p-6 rounded-xl">
        <h3 class="text-lg font-bold text-white mb-4">Recent Announcements</h3>
        <ul class="space-y-4">
             <li class="border-b border-white/10 pb-2">
                <p class="text-white font-medium">Mid-term break dates announced</p>
                <p class="text-xs text-white/60">2 days ago</p>
            </li>
             <li class="border-b border-white/10 pb-2">
                <p class="text-white font-medium">Library books due</p>
                <p class="text-xs text-white/60">1 week ago</p>
            </li>
        </ul>
    </div>
    
     <div class="glass-panel p-6 rounded-xl">
        <h3 class="text-lg font-bold text-white mb-4">Timetable Today</h3>
         <div class="space-y-3">
            <div class="flex justify-between items-center bg-white/10 p-3 rounded-lg">
                <span class="text-white font-medium">08:00 AM</span>
                <span class="text-white/80">Mathematics</span>
            </div>
             <div class="flex justify-between items-center bg-white/10 p-3 rounded-lg">
                <span class="text-white font-medium">10:00 AM</span>
                <span class="text-white/80">English Literature</span>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/portal.php';
?>
