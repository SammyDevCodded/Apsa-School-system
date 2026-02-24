<?php $title = 'My Academics'; ob_start(); ?>

    <div class="flex justify-between items-center mb-8">
        <div>
            <h1 class="text-3xl font-bold text-white mb-2">My Academics</h1>
            <p class="text-white/60">Overview of subjects and assessment history.</p>
        </div>
        <button onclick="openStaffSubjectsModal()" class="glass-btn px-4 py-2 rounded-lg flex items-center gap-2">
            <svg class="w-5 h-5 text-indigo-300" fill="none" viewBox="0 0 24 24" stroke="currentColor"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 11H5m14 0a2 2 0 012 2v6a2 2 0 01-2 2H5a2 2 0 01-2-2v-6a2 2 0 012-2m14 0V9a2 2 0 00-2-2M5 11V9a2 2 0 012-2m0 0V5a2 2 0 012-2h6a2 2 0 012 2v2M7 7h10" /></svg>
            <span>View Subjects Modal</span>
        </button>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Detailed Subject List -->
        <div class="glass-panel p-6 rounded-xl">
            <h3 class="text-xl font-bold text-white mb-6">Assigned Subjects</h3>
            <?php if (empty($my_subjects)): ?>
                <p class="text-white/60 italic">No subjects assigned.</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($my_subjects as $sub): ?>
                    <div class="bg-white/5 p-4 rounded-lg flex justify-between items-center border border-white/5 hover:bg-white/10 transition group">
                        <div class="flex items-center gap-4">
                            <div class="w-10 h-10 rounded-full bg-indigo-500/20 flex items-center justify-center text-indigo-300 font-bold text-sm">
                                <?= substr($sub['name'], 0, 1) ?>
                            </div>
                            <div>
                                <p class="font-bold text-white"><?= htmlspecialchars($sub['name']) ?></p>
                                <p class="text-white/60 text-xs uppercase tracking-wide"><?= htmlspecialchars($sub['code']) ?></p>
                            </div>
                        </div>
                        <?php if (!empty($sub['class_name'])): ?>
                        <span class="px-3 py-1 bg-white/10 text-white rounded-full text-xs font-semibold">
                            <?= htmlspecialchars($sub['class_name']) ?>
                        </span>
                        <?php endif; ?>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Extended Recent Exams -->
        <div class="glass-panel p-6 rounded-xl">
            <h3 class="text-xl font-bold text-white mb-6">Recent Assessments</h3>
            <?php if (empty($recent_exams)): ?>
                 <p class="text-white/60 italic">No recent exams found for your subjects.</p>
            <?php else: ?>
                <div class="space-y-4">
                    <?php foreach ($recent_exams as $exam): ?>
                    <div class="bg-white/5 p-4 rounded-lg border-l-4 border-green-500 hover:bg-white/10 transition">
                         <div class="flex justify-between items-start mb-2">
                            <h4 class="font-bold text-white text-lg"><?= htmlspecialchars($exam['name']) ?></h4>
                             <span class="text-xs bg-black/30 px-2 py-1 rounded text-white/50 border border-white/10">
                                <?= date('M d, Y', strtotime($exam['date'])) ?>
                            </span>
                        </div>
                        <div class="grid grid-cols-2 gap-2 text-sm mt-2">
                            <div>
                                <span class="text-white/40 text-xs block">Term</span>
                                <span class="text-white/80"><?= htmlspecialchars($exam['academic_year_name'] ?? 'N/A') ?> - <?= htmlspecialchars($exam['term'] ?? '') ?></span>
                            </div>
                            <div>
                                <span class="text-white/40 text-xs block">Class</span>
                                <span class="text-white/80"><?= htmlspecialchars($exam['class_name'] ?? 'Multiple Classes') ?></span>
                            </div>
                        </div>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>

    <!-- Student Performance History -->
    <?php if (!empty($student_performance)): ?>
    <div class="glass-panel p-6 rounded-xl mt-8">
        <h3 class="text-xl font-bold text-white mb-6">Student Performance History</h3>
        
        <?php foreach ($student_performance as $year => $terms): ?>
            <!-- Year Accordion -->
            <div class="mb-4 border border-white/10 rounded-lg overflow-hidden">
                <button class="w-full flex justify-between items-center p-4 bg-white/5 hover:bg-white/10 transition text-left" onclick="toggleAccordion('year-<?= md5($year) ?>')">
                    <span class="font-bold text-lg text-white"><?= htmlspecialchars($year) ?></span>
                    <svg class="w-5 h-5 text-white/60 transform transition-transform" id="icon-year-<?= md5($year) ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                    </svg>
                </button>
                
                <div id="year-<?= md5($year) ?>" class="hidden">
                    <div class="p-4 bg-white/5 space-y-4">
                        <?php foreach ($terms as $term => $subjects): ?>
                            <!-- Term Accordion -->
                             <div class="border border-white/10 rounded-lg overflow-hidden">
                                <button class="w-full flex justify-between items-center p-3 bg-white/5 hover:bg-white/10 transition text-left" onclick="toggleAccordion('term-<?= md5($year . $term) ?>')">
                                    <span class="font-semibold text-white"><?= htmlspecialchars($term) ?></span>
                                    <svg class="w-4 h-4 text-white/60 transform transition-transform" id="icon-term-<?= md5($year . $term) ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                                    </svg>
                                </button>
                                
                                <div id="term-<?= md5($year . $term) ?>" class="hidden">
                                    <div class="p-4 bg-black/20 space-y-4">
                                        <?php foreach ($subjects as $subject => $results): ?>
                                            <div>
                                                <h4 class="font-bold text-indigo-300 mb-2 border-b border-white/10 pb-1"><?= htmlspecialchars($subject) ?></h4>
                                                <div class="overflow-x-auto">
                                                    <table class="w-full text-sm text-left text-white/80">
                                                        <thead class="text-xs text-white/50 uppercase bg-white/5">
                                                            <tr>
                                                                <th class="px-3 py-2">Student</th>
                                                                <th class="px-3 py-2">Admission No</th>
                                                                <th class="px-3 py-2">Exam</th>
                                                                <th class="px-3 py-2">Marks</th>
                                                                <th class="px-3 py-2">Grade</th>
                                                            </tr>
                                                        </thead>
                                                        <tbody>
                                                            <?php foreach ($results as $result): ?>
                                                                <tr class="border-b border-white/5 hover:bg-white/5">
                                                                    <td class="px-3 py-2 font-medium"><?= htmlspecialchars($result['first_name'] . ' ' . $result['last_name']) ?></td>
                                                                    <td class="px-3 py-2 text-white/60"><?= htmlspecialchars($result['admission_no']) ?></td>
                                                                    <td class="px-3 py-2"><?= htmlspecialchars($result['exam_name']) ?></td>
                                                                    <td class="px-3 py-2"><?= htmlspecialchars($result['marks']) ?></td>
                                                                    <td class="px-3 py-2">
                                                                        <span class="px-2 py-0.5 rounded text-xs font-bold 
                                                                            <?= $result['grade'] === 'A' || $result['grade'] === 'A+' ? 'bg-green-500/20 text-green-300' : 
                                                                               ($result['grade'] === 'F' ? 'bg-red-500/20 text-red-300' : 'bg-blue-500/20 text-blue-300') ?>">
                                                                            <?= htmlspecialchars($result['grade']) ?>
                                                                        </span>
                                                                    </td>
                                                                </tr>
                                                            <?php endforeach; ?>
                                                        </tbody>
                                                    </table>
                                                </div>
                                            </div>
                                        <?php endforeach; ?>
                                    </div>
                                </div>
                            </div>
                        <?php endforeach; ?>
                    </div>
                </div>
            </div>
        <?php endforeach; ?>
    </div>
    <?php endif; ?>

    <script>
    function toggleAccordion(id) {
        const element = document.getElementById(id);
        const icon = document.getElementById('icon-' + id);
        if (element.classList.contains('hidden')) {
            element.classList.remove('hidden');
            icon.classList.add('rotate-180');
        } else {
            element.classList.add('hidden');
            icon.classList.remove('rotate-180');
        }
    }
    </script>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/portal.php';
?>
