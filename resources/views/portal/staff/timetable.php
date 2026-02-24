<?php $title = 'My Timetable'; ob_start(); ?>

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">My Timetable</h1>
            <p class="text-white/60">Manage your weekly teaching schedule.</p>
        </div>
        <button onclick="openStaffTimetableModal()" class="glass-btn px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 8V4m0 0h4M4 4l5 5m11-1V4m0 0h-4m4 4l-5 5M4 16v4m0 0h4m-4 0l5-5m11 5l-5-5m5 5v-4m0 4h-4" /></svg>
            <span>View Fullscreen Modal</span>
        </button>
    </div>

    <!-- Weekly Timetable Grid -->
    <div class="glass-panel p-6 rounded-xl">
        <h3 class="text-xl font-bold text-white mb-6">Weekly Schedule</h3>
        
        <?php if(empty($my_timetable)): ?>
            <p class="text-white/50 italic text-center py-12">No classes scheduled yet.</p>
        <?php else: ?>
            <div class="grid grid-cols-1 md:grid-cols-5 gap-4">
                <?php 
                $days = ['Monday', 'Tuesday', 'Wednesday', 'Thursday', 'Friday'];
                foreach ($days as $day): 
                ?>
                <div class="bg-white/5 rounded-lg overflow-hidden flex flex-col h-full border border-white/5">
                    <div class="bg-indigo-600/20 p-3 text-center border-b border-indigo-500/20">
                        <h4 class="font-bold text-white"><?= $day ?></h4>
                    </div>
                    <div class="p-3 space-y-3 flex-1">
                        <?php if (!empty($my_timetable[$day])): ?>
                            <?php foreach ($my_timetable[$day] as $slot): ?>
                            <div class="bg-white/5 p-3 rounded border border-white/10 hover:bg-white/10 transition group">
                                <p class="text-xs text-indigo-300 mb-1 font-mono">
                                    <?= date('H:i', strtotime($slot['start_time'])) ?> - <?= date('H:i', strtotime($slot['end_time'])) ?>
                                </p>
                                <p class="font-bold text-white text-sm"><?= htmlspecialchars($slot['subject_name']) ?></p>
                                <p class="text-xs text-white/60 mt-1"><?= htmlspecialchars($slot['class_name']) ?></p>
                            </div>
                            <?php endforeach; ?>
                        <?php else: ?>
                            <div class="h-full flex items-center justify-center">
                                <span class="text-white/10 text-xs italic">Free</span>
                            </div>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endforeach; ?>
            </div>
        <?php endif; ?>
    </div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/portal.php';
?>
