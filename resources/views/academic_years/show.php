<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Academic Year Details</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Details and status configuration.</p>
    </div>
    <div class="border-t border-gray-200">
        <dl>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Academic Year</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($academicYear['name']) ?></dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Start Date</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($academicYear['start_date']) ?></dd>
            </div>
            <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">End Date</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2"><?= htmlspecialchars($academicYear['end_date']) ?></dd>
            </div>
            <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                <dt class="text-sm font-medium text-gray-500">Current Status</dt>
                <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                        <?= $academicYear['status'] === 'active' ? 'bg-green-100 text-green-800' : 
                           ($academicYear['status'] === 'completed' ? 'bg-gray-100 text-gray-800' : 'bg-red-100 text-red-800') ?>">
                        <?= ucfirst(htmlspecialchars($academicYear['status'])) ?>
                    </span>
                    <?php if ($academicYear['is_current']): ?>
                        <span class="ml-2 px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">Current Active Year</span>
                    <?php endif; ?>
                </dd>
            </div>
        </dl>
    </div>
    
    <div class="px-4 py-3 bg-gray-50 text-right sm:px-6">
        <form id="updateStatusForm" action="/academic_years/<?= $academicYear['id'] ?>/update_status" method="POST" class="inline-flex">
            <?php if ($academicYear['status'] !== 'completed'): ?>
                <button type="button" onclick="confirmCompletion()" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-green-500">
                    Mark as Completed
                </button>
            <?php else: ?>
                <?php if ($canEditCompleted): ?>
                <button type="submit" name="status" value="active" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Re-activate Year
                </button>
                <?php else: ?>
                    <p class="text-sm text-gray-500 italic">Only Super Admin can reactivate completed years.</p>
                <?php endif; ?>
            <?php endif; ?>
        </form>
    </div>
</div>

<script>
function confirmCompletion() {
    if (confirm('Are you sure you want to mark this academic year as COMPLETED? \n\nWARNING: This will make the academic year data immutable (unchangeable). Only Super Admins can revert this.')) {
        // Create a hidden input to send the status
        const form = document.getElementById('updateStatusForm');
        const input = document.createElement('input');
        input.type = 'hidden';
        input.name = 'status';
        input.value = 'completed';
        form.appendChild(input);
        form.submit();
    }
}
</script>
