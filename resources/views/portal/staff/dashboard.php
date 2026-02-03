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

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/portal.php';
?>
