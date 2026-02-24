<?php $title = 'Staff Dashboard'; ob_start(); ?>

<div class="grid grid-cols-1 md:grid-cols-3 gap-6 mb-8">
    <!-- Welcome Card -->
    <div class="md:col-span-3 glass-panel p-6 rounded-2xl relative overflow-hidden">
        <div class="relative z-10">
            <h1 class="text-3xl font-bold text-white mb-2">Welcome, <?= htmlspecialchars($staff['first_name'] ?? $_SESSION['user_name']) ?>!</h1>
            <p class="text-white/80">Manage your classes and schedule efficiently.</p>
        </div>
        <div class="absolute right-0 top-0 h-full w-1/3 bg-gradient-to-l from-white/10 to-transparent pointer-events-none"></div>
    </div>
</div>

<div class="grid grid-cols-1 lg:grid-cols-3 gap-8">
    
    <!-- Today's Schedule -->
    <div class="lg:col-span-1 glass-panel p-6 rounded-xl h-fit">
        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
             <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8v4l3 3m6-3a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
            Today's Schedule
        </h3>
        
        <?php if (empty($today_schedule)): ?>
            <div class="bg-white/5 p-4 rounded-lg text-center border border-white/5">
                <p class="text-white/60">No classes scheduled for today.</p>
            </div>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($today_schedule as $slot): ?>
                <div class="bg-white/10 p-4 rounded-lg border-l-4 border-indigo-400">
                    <div class="flex justify-between items-start">
                        <div>
                            <p class="font-bold text-white text-lg"><?= htmlspecialchars($slot['class_name']) ?></p>
                            <p class="text-white/80 text-sm"><?= htmlspecialchars($slot['subject_name']) ?></p>
                        </div>
                        <span class="bg-black/20 text-white/90 text-xs px-2 py-1 rounded font-mono">
                            <?= date('H:i', strtotime($slot['start_time'])) ?> - <?= date('H:i', strtotime($slot['end_time'])) ?>
                        </span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Weekly Timetable Overview -->
    <div class="lg:col-span-2 glass-panel p-6 rounded-xl">
        <h3 class="text-xl font-bold text-white mb-6">Your Weekly Schedule</h3>
        
        <?php 
        $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
        ?>
        
        <div class="space-y-6">
            <?php foreach ($days as $day): ?>
                <?php if (!empty($my_timetable[$day])): ?>
                <div>
                    <h4 class="text-sm font-bold text-white/50 uppercase tracking-wide mb-3 border-b border-white/10 pb-1"><?= $day ?></h4>
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                        <?php foreach ($my_timetable[$day] as $slot): ?>
                        <div class="bg-white/5 p-3 rounded border border-white/5 hover:bg-white/10 transition flex justify-between items-center px-4">
                            <div>
                                <p class="text-white font-semibold"><?= htmlspecialchars($slot['subject_name']) ?></p>
                                <p class="text-white/60 text-xs"><?= htmlspecialchars($slot['class_name']) ?></p>
                            </div>
                             <span class="text-white/80 text-xs font-mono">
                                <?= date('H:i', strtotime($slot['start_time'])) ?>
                            </span>
                        </div>
                        <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
            <?php endforeach; ?>
            
            <?php if(empty($my_timetable)): ?>
                <p class="text-white/50 italic text-center py-4">No timetable found.</p>
            <?php endif; ?>
        </div>
    </div>
        </div>
    </div>
</div>

<!-- Academic Data Section -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-8 mt-8">
    <!-- My Subjects -->
    <div class="glass-panel p-6 rounded-xl">
        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6.253v13m0-13C10.832 5.477 9.246 5 7.5 5S4.168 5.477 3 6.253v13C4.168 18.477 5.754 18 7.5 18s3.332.477 4.5 1.253m0-13C13.168 5.477 14.754 5 16.5 5c1.747 0 3.332.477 4.5 1.253v13C19.832 18.477 18.247 18 16.5 18c-1.746 0-3.332.477-4.5 1.253"></path></svg>
            My Subjects
        </h3>
        <?php if (empty($my_subjects)): ?>
            <p class="text-white/60 italic">No subjects assigned.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($my_subjects as $sub): ?>
                <div class="bg-white/5 p-4 rounded-lg flex justify-between items-center border border-white/5 hover:bg-white/10 transition">
                    <div>
                        <p class="font-bold text-white"><?= htmlspecialchars($sub['name']) ?></p>
                        <p class="text-white/60 text-xs uppercase tracking-wide"><?= htmlspecialchars($sub['code']) ?></p>
                    </div>
                    <?php if (!empty($sub['class_name'])): ?>
                    <span class="px-2 py-1 bg-indigo-500/20 text-indigo-300 rounded text-xs">
                        <?= htmlspecialchars($sub['class_name']) ?>
                    </span>
                    <?php endif; ?>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

    <!-- Recent Exams -->
    <div class="glass-panel p-6 rounded-xl">
        <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
            <svg class="w-5 h-5 text-green-400" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2m-6 9l2 2 4-4"></path></svg>
            Recent Exams
        </h3>
        <?php if (empty($recent_exams)): ?>
            <p class="text-white/60 italic">No recent exams found for your subjects.</p>
        <?php else: ?>
            <div class="space-y-3">
                <?php foreach ($recent_exams as $exam): ?>
                <div class="bg-white/5 p-4 rounded-lg border-l-4 border-green-500 hover:bg-white/10 transition">
                    <div class="flex justify-between items-start mb-1">
                        <h4 class="font-bold text-white"><?= htmlspecialchars($exam['name']) ?></h4>
                        <span class="text-xs text-white/50"><?= date('M d, Y', strtotime($exam['date'])) ?></span>
                    </div>
                    <div class="flex justify-between items-center">
                        <span class="text-sm text-white/80"><?= htmlspecialchars($exam['academic_year_name'] ?? 'N/A') ?> - <?= htmlspecialchars($exam['term'] ?? '') ?></span>
                        <span class="text-xs px-2 py-1 rounded bg-white/10 text-white/70"><?= htmlspecialchars($exam['class_name'] ?? 'Multiple Classes') ?></span>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/portal.php';
?>
