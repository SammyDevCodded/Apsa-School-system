<?php 
$title = 'Ward Finances'; 
require_once ROOT_PATH . '/app/Helpers/TemplateHelper.php';
$settings = getSchoolSettings();
$currency = $settings['currency_symbol'] ?? '$';
ob_start(); 
?>

<div class="max-w-6xl mx-auto">
    
    <!-- Balance Header -->
    <div class="mb-8 grid grid-cols-1 md:grid-cols-3 gap-6">
        <!-- Billed -->
        <div class="glass-panel p-6 rounded-xl relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-white/60 text-sm font-medium uppercase tracking-wide">Total Billed</p>
                <h2 class="text-3xl font-bold text-white mt-1"><?= $currency ?><?= number_format($total_billed, 2) ?></h2>
            </div>
            <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-blue-500/20 to-transparent"></div>
        </div>

        <!-- Paid -->
        <div class="glass-panel p-6 rounded-xl relative overflow-hidden group">
            <div class="relative z-10">
                <p class="text-white/60 text-sm font-medium uppercase tracking-wide">Total Paid</p>
                <h2 class="text-3xl font-bold text-green-300 mt-1"><?= $currency ?><?= number_format($total_paid, 2) ?></h2>
            </div>
            <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l from-green-500/20 to-transparent"></div>
        </div>

        <!-- Balance -->
        <div class="glass-panel p-6 rounded-xl relative overflow-hidden ring-1 <?php echo $balance > 0 ? 'ring-red-500/50' : 'ring-green-500/50'; ?>">
            <div class="relative z-10">
                <p class="text-white/60 text-sm font-medium uppercase tracking-wide">Outstanding Balance</p>
                <h2 class="text-4xl font-bold mt-1 <?php echo $balance > 0 ? 'text-red-300' : 'text-green-300'; ?>">
                    <?= $currency ?><?= number_format($balance, 2) ?>
                </h2>
            </div>
             <div class="absolute right-0 top-0 h-full w-24 bg-gradient-to-l <?php echo $balance > 0 ? 'from-red-500/20' : 'from-green-500/20'; ?> to-transparent"></div>
        </div>
    </div>

    <div class="grid grid-cols-1 lg:grid-cols-2 gap-8">
        
        <!-- Fee Assignments -->
        <div class="glass-panel p-6 rounded-xl">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5H7a2 2 0 00-2 2v12a2 2 0 002 2h10a2 2 0 002-2V7a2 2 0 00-2-2h-2M9 5a2 2 0 002 2h2a2 2 0 002-2M9 5a2 2 0 012-2h2a2 2 0 012 2"></path></svg>
                Fee Structure
            </h3>
            
            <?php if (empty($assignments)): ?>
                <p class="text-white/50 italic text-sm">No fees assigned yet.</p>
            <?php else: ?>
                <div class="space-y-3">
                    <?php foreach ($assignments as $fee): ?>
                    <div class="bg-white/5 p-4 rounded-lg flex justify-between items-center border border-white/5 hover:bg-white/10 transition">
                        <div>
                            <p class="font-medium text-white"><?= htmlspecialchars($fee['fee_name']) ?></p>
                            <span class="text-xs text-white/50 bg-white/10 px-1.5 py-0.5 rounded capitalize"><?= htmlspecialchars($fee['fee_type']) ?></span>
                        </div>
                        <p class="font-bold text-white"><?= $currency ?><?= number_format($fee['fee_amount'], 2) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>

        <!-- Payment History -->
        <div class="glass-panel p-6 rounded-xl">
            <h3 class="text-xl font-bold text-white mb-4 flex items-center gap-2">
                 <svg class="w-5 h-5 text-white/70" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                Payment History
            </h3>
            
            <?php if (empty($payments)): ?>
                <p class="text-white/50 italic text-sm">No payments recorded yet.</p>
            <?php else: ?>
                 <div class="space-y-3">
                    <?php foreach ($payments as $payment): ?>
                    <div class="bg-white/5 p-4 rounded-lg flex justify-between items-center border border-white/5 hover:bg-white/10 transition">
                        <div>
                            <p class="font-medium text-white"><?= htmlspecialchars($payment['date']) ?></p>
                            <p class="text-xs text-white/50 mt-0.5">Method: <?= htmlspecialchars($payment['method']) ?></p>
                            <?php if(!empty($payment['transaction_id'])): ?>
                                <p class="text-[10px] text-white/30 font-mono">ID: <?= htmlspecialchars($payment['transaction_id']) ?></p>
                            <?php endif; ?>
                        </div>
                        <p class="font-bold text-green-300">+<?= $currency ?><?= number_format($payment['amount'], 2) ?></p>
                    </div>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/portal.php';
?>
