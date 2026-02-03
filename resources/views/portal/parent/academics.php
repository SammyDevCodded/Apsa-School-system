<?php $title = 'Ward Academics'; ob_start(); ?>

<div class="max-w-6xl mx-auto">
    <div class="glass-panel p-8 rounded-2xl mb-8">
        <h1 class="text-3xl font-bold text-white mb-2">Ward's Performance</h1>
        <p class="text-white/70">Detailed exam results for your child.</p>
    </div>

    <div class="glass-panel p-6 rounded-xl overflow-hidden">
        <h3 class="text-xl font-bold text-white mb-4">Exam History</h3>
        
        <?php if (empty($results)): ?>
            <div class="text-center py-10 text-white/50 italic">
                No exam results found for this student.
            </div>
        <?php else: ?>
            <div class="overflow-x-auto">
                <table class="w-full text-left border-collapse">
                    <thead>
                        <tr class="border-b border-white/20 text-white/60 text-sm uppercase tracking-wider">
                            <th class="py-4 px-4 font-semibold">Date</th>
                            <th class="py-4 px-4 font-semibold">Exam</th>
                            <th class="py-4 px-4 font-semibold">Subject</th>
                            <th class="py-4 px-4 font-semibold text-center">Score</th>
                            <th class="py-4 px-4 font-semibold text-center">Grade</th>
                            <th class="py-4 px-4 font-semibold">Remark</th>
                        </tr>
                    </thead>
                    <tbody class="divide-y divide-white/10 text-white">
                        <?php foreach ($results as $result): ?>
                        <tr class="hover:bg-white/5 transition">
                            <td class="py-4 px-4 text-sm whitespace-nowrap"><?= htmlspecialchars($result['exam_date'] ?? 'N/A') ?></td>
                            <td class="py-4 px-4 font-medium"><?= htmlspecialchars($result['exam_name'] ?? 'N/A') ?></td>
                            <td class="py-4 px-4"><?= htmlspecialchars($result['subject_name'] ?? 'N/A') ?></td>
                            <td class="py-4 px-4 text-center font-bold font-mono">
                                <?= $result['marks'] ?>
                            </td>
                            <td class="py-4 px-4 text-center">
                                <span class="px-2 py-1 rounded text-xs font-bold 
                                    <?php 
                                        $g = strtoupper($result['grade']);
                                        if($g=='A' || $g=='A+') echo 'bg-green-500/20 text-green-200 border border-green-500/30';
                                        elseif($g=='F') echo 'bg-red-500/20 text-red-200 border border-red-500/30';
                                        else echo 'bg-blue-500/20 text-blue-200 border border-blue-500/30';
                                    ?>">
                                    <?= htmlspecialchars($result['grade']) ?>
                                </span>
                            </td>
                            <td class="py-4 px-4 text-sm text-white/70 italic"><?= htmlspecialchars($result['remark'] ?? '-') ?></td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/portal.php';
?>
