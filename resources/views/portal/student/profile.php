<?php $title = 'My Profile'; ob_start(); ?>

<div class="max-w-4xl mx-auto">
    <!-- Header -->
    <div class="glass-panel p-8 rounded-2xl mb-8 flex items-center gap-6">
        <div class="w-24 h-24 rounded-full bg-white/20 flex items-center justify-center text-4xl font-bold text-white shadow-inner">
            <?= substr($student['first_name'], 0, 1) . substr($student['last_name'], 0, 1) ?>
        </div>
        <div>
            <h1 class="text-3xl font-bold text-white"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></h1>
            <p class="text-white/70 text-lg">Admission No: <span class="text-white font-mono bg-white/10 px-2 py-0.5 rounded"><?= htmlspecialchars($student['admission_no']) ?></span></p>
        </div>
    </div>

    <div class="grid grid-cols-1 md:grid-cols-2 gap-8">
        <!-- Personal Information -->
        <div class="glass-panel p-6 rounded-xl">
            <h3 class="text-xl font-bold text-white mb-6 border-b border-white/10 pb-2">Personal Information</h3>
            <div class="space-y-4">
                <div>
                    <span class="block text-xs uppercase tracking-wide text-white/50 mb-1">Date of Birth</span>
                    <span class="text-lg text-white font-medium"><?= htmlspecialchars($student['dob'] ?? 'N/A') ?></span>
                </div>
                <div>
                    <span class="block text-xs uppercase tracking-wide text-white/50 mb-1">Gender</span>
                    <span class="text-lg text-white font-medium"><?= htmlspecialchars(ucfirst($student['gender'] ?? 'N/A')) ?></span>
                </div>
                <div>
                    <span class="block text-xs uppercase tracking-wide text-white/50 mb-1">Class</span>
                    <span class="text-lg text-white font-medium"><?= htmlspecialchars($student['class_name'] ?? 'N/A') ?></span>
                </div>
                 <div>
                    <span class="block text-xs uppercase tracking-wide text-white/50 mb-1">Address</span>
                    <span class="text-lg text-white font-medium"><?= htmlspecialchars($student['address'] ?? 'N/A') ?></span>
                </div>
            </div>
        </div>

        <!-- Parent / Guardian Information -->
        <div class="glass-panel p-6 rounded-xl">
            <h3 class="text-xl font-bold text-white mb-6 border-b border-white/10 pb-2">Guardian Details</h3>
            <div class="space-y-4">
                <div>
                    <span class="block text-xs uppercase tracking-wide text-white/50 mb-1">Guardian Name</span>
                    <span class="text-lg text-white font-medium"><?= htmlspecialchars($student['guardian_name'] ?? 'N/A') ?></span>
                </div>
                <div>
                    <span class="block text-xs uppercase tracking-wide text-white/50 mb-1">Phone Number</span>
                    <span class="text-lg text-white font-medium"><?= htmlspecialchars($student['guardian_phone'] ?? 'N/A') ?></span>
                </div>
                <div>
                    <span class="block text-xs uppercase tracking-wide text-white/50 mb-1">Email</span>
                    <span class="text-lg text-white font-medium break-all"><?= htmlspecialchars($student['guardian_email'] ?? 'N/A') ?></span>
                </div>
                 <div>
                    <span class="block text-xs uppercase tracking-wide text-white/50 mb-1">Relationship</span>
                    <span class="text-lg text-white font-medium">Parent / Guardian</span>
                </div>
            </div>
        </div>
    </div>
</div>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/portal.php';
?>
