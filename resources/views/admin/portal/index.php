<?php 
$title = 'Portal Management'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <h1 class="text-2xl font-semibold text-gray-900 mb-6">Portal Access Management</h1>

        <!-- Tabs -->
        <div class="mb-6 border-b border-gray-200">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="#access" id="tab-access" class="border-indigo-500 text-indigo-600 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors" onclick="switchTab('access')">
                    Access Management
                </a>
                <a href="#staff" id="tab-staff" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors" onclick="switchTab('staff')">
                    Staff Access
                </a>
                <a href="#trails" id="tab-trails" class="border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm transition-colors" onclick="switchTab('trails')">
                    Login Trails
                    <span class="ml-2 bg-gray-100 text-gray-900 py-0.5 px-2.5 rounded-full text-xs font-medium inline-block"><?= count($trails ?? []) ?></span>
                </a>
            </nav>
        </div>

        <!-- Tab Content: Access Management -->
        <div id="content-access">
            <!-- Filters & Search -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <form action="/admin/portal" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <div class="md:col-span-2">
                        <label for="search" class="block text-sm font-medium text-gray-700">Search Student</label>
                        <input type="text" name="search" id="search" value="<?= htmlspecialchars($search) ?>" placeholder="Name or Admission No..." 
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div>
                        <label for="class_id" class="block text-sm font-medium text-gray-700">Filter by Class</label>
                        <select name="class_id" id="class_id" 
                                class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                            <option value="">All Classes</option>
                            <?php foreach ($classes as $class): ?>
                                <option value="<?= $class['id'] ?>" <?= $classId == $class['id'] ? 'selected' : '' ?>>
                                    <?= htmlspecialchars($class['name']) ?>
                                </option>
                            <?php endforeach; ?>
                        </select>
                    </div>
                    <div class="flex flex-col space-y-2">
                         <div class="flex gap-2">
                            <button type="submit" class="flex-1 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Filter
                            </button>
                            <?php if(!empty($search) || !empty($classId)): ?>
                                <a href="/admin/portal" class="flex-1 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                    Clear
                                </a>
                            <?php endif; ?>
                         </div>
                    </div>
                </form>
            </div>
    
            <!-- Student List -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Admission No</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Class</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Student Access</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Parent Access</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($students)): ?>
                            <tr><td colspan="6" class="px-6 py-4 text-center text-gray-500">No students found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($students as $student): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900">
                                        <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($student['admission_no']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($student['class_name'] ?? 'N/A') ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <?php if (!empty($student['password'])): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?= ($student['student_portal_status'] ?? 'active') === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                <?= ucfirst($student['student_portal_status'] ?? 'active') ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                        <?php endif; ?>
                                        
                                        <!-- Message Student Icon -->
                                        <button onclick="openMessageModal('<?= $student['id'] ?>', 'student', '<?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>')" 
                                                class="text-gray-400 hover:text-indigo-600 transition-colors" title="Message Student">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center gap-2">
                                        <?php if (!empty($student['parent_password'])): ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                                <?= ($student['parent_portal_status'] ?? 'active') === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                                <?= ucfirst($student['parent_portal_status'] ?? 'active') ?>
                                            </span>
                                        <?php else: ?>
                                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Inactive</span>
                                        <?php endif; ?>

                                        <!-- Message Parent Icon -->
                                        <button onclick="openMessageModal('<?= $student['id'] ?>', 'parent', 'Parent of <?= htmlspecialchars($student['first_name']) ?>')" 
                                                class="text-gray-400 hover:text-indigo-600 transition-colors" title="Message Parent">
                                            <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.89 5.26a2 2 0 002.22 0L21 8M5 19h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v10a2 2 0 002 2z" />
                                            </svg>
                                        </button>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <button onclick="openManageModal(<?= $student['id'] ?>, '<?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>', '<?= $student['student_portal_status'] ?? 'active' ?>', '<?= $student['parent_portal_status'] ?? 'active' ?>')" 
                                            class="text-indigo-600 hover:text-indigo-900">
                                        Manage Access
                                    </button>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <!-- Pagination -->
                <?php if (isset($pagination['total_pages']) && $pagination['total_pages'] > 1): ?>
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <!-- Simple pagination links -->
                    <div class="flex-1 flex justify-between">
                        <?php if ($pagination['page'] > 1): ?>
                            <a href="?page=<?= $pagination['page'] - 1 ?>&search=<?= urlencode($search) ?>&class_id=<?= urlencode($classId) ?>" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </a>
                        <?php else: ?>
                            <span></span>
                        <?php endif; ?>
    
                        <?php if ($pagination['page'] < $pagination['total_pages']): ?>
                            <a href="?page=<?= $pagination['page'] + 1 ?>&search=<?= urlencode($search) ?>&class_id=<?= urlencode($classId) ?>" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        </div>

        <!-- Tab Content: Staff Access -->
        <div id="content-staff" class="hidden">
            <!-- Filters & Search -->
            <div class="bg-white shadow rounded-lg p-6 mb-6">
                <form action="/admin/portal" method="GET" class="grid grid-cols-1 md:grid-cols-4 gap-4 items-end">
                    <input type="hidden" name="tab" value="staff"> <!-- Maintain tab state if possible, though JS handles hash -->
                    <div class="md:col-span-3">
                        <label for="staff_search" class="block text-sm font-medium text-gray-700">Search Staff</label>
                        <input type="text" name="staff_search" id="staff_search" value="<?= htmlspecialchars($staff_search ?? '') ?>" placeholder="Name, Employee ID, Department..." 
                               class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md">
                    </div>
                    <div class="flex flex-col space-y-2">
                         <div class="flex gap-2">
                            <button type="submit" class="flex-1 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                                Filter
                            </button>
                            <?php if(!empty($staff_search)): ?>
                                <a href="/admin/portal#staff" class="flex-1 inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none">
                                    Clear
                                </a>
                            <?php endif; ?>
                         </div>
                    </div>
                </form>
            </div>
    
            <!-- Staff List -->
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Staff Member</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Department / Position</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Username</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                            <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Actions</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if (empty($staff_list)): ?>
                            <tr><td colspan="5" class="px-6 py-4 text-center text-gray-500">No staff found.</td></tr>
                        <?php else: ?>
                            <?php foreach ($staff_list as $staff): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="flex items-center">
                                        <div>
                                            <div class="text-sm font-medium text-gray-900">
                                                <?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?>
                                            </div>
                                            <div class="text-xs text-gray-500">ID: <?= htmlspecialchars($staff['employee_id']) ?></div>
                                        </div>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm text-gray-900"><?= htmlspecialchars($staff['department']) ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($staff['position']) ?></div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= !empty($staff['user_id']) ? htmlspecialchars($staff['username']) : '<span class="text-gray-400 italic">No Account</span>' ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <?php if (!empty($staff['user_id'])): ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full 
                                            <?= ($staff['status'] ?? 'active') === 'active' ? 'bg-green-100 text-green-800' : 'bg-red-100 text-red-800' ?>">
                                            <?= ucfirst($staff['status'] ?? 'active') ?>
                                        </span>
                                    <?php else: ?>
                                        <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-gray-100 text-gray-800">Not Registered</span>
                                    <?php endif; ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                    <?php if (empty($staff['user_id'])): ?>
                                        <button onclick="openGrantStaffModal(
                                            '<?= $staff['id'] ?>', 
                                            '<?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?>',
                                            '<?= htmlspecialchars($staff['email'] ?? '') ?>',
                                            '<?= htmlspecialchars($staff['employee_id']) ?>'
                                        )" 
                                                class="text-indigo-600 hover:text-indigo-900">
                                            Grant Access
                                        </button>
                                    <?php else: ?>
                                        <button onclick="openManageStaffModal(
                                            '<?= $staff['id'] ?>', 
                                            '<?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?>',
                                            '<?= htmlspecialchars($staff['username']) ?>',
                                            '<?= $staff['status'] ?? 'active' ?>',
                                            '<?= $staff['user_id'] ?>'
                                        )" 
                                                class="text-indigo-600 hover:text-indigo-900 mr-3">
                                            Manage Access
                                        </button>
                                    <?php endif; ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
                
                <!-- Pagination (Staff) -->
                <?php if (isset($staff_pagination['total_pages']) && $staff_pagination['total_pages'] > 1): ?>
                <div class="bg-white px-4 py-3 flex items-center justify-between border-t border-gray-200 sm:px-6">
                    <div class="flex-1 flex justify-between">
                        <?php if ($staff_pagination['current_page'] > 1): ?>
                            <a href="?page=<?= $staff_pagination['current_page'] - 1 ?>&staff_search=<?= urlencode($staff_search) ?>#staff" class="relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Previous
                            </a>
                        <?php else: ?>
                            <span></span>
                        <?php endif; ?>
    
                        <?php if ($staff_pagination['current_page'] < $staff_pagination['total_pages']): ?>
                            <a href="?page=<?= $staff_pagination['current_page'] + 1 ?>&staff_search=<?= urlencode($staff_search) ?>#staff" class="ml-3 relative inline-flex items-center px-4 py-2 border border-gray-300 text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50">
                                Next
                            </a>
                        <?php endif; ?>
                    </div>
                </div>
                <?php endif; ?>
            </div>
        </div>

        <!-- Tab Content: Login Trails -->
        <div id="content-trails" class="hidden">
            <div class="bg-white shadow overflow-hidden sm:rounded-lg">
                <div class="px-4 py-5 sm:px-6 border-b border-gray-200">
                     <h3 class="text-lg leading-6 font-medium text-gray-900">Active Sessions</h3>
                     <p class="mt-1 text-sm text-gray-500">Real-time view of currently logged-in portal users.</p>
                </div>
                <table class="min-w-full divide-y divide-gray-200">
                    <thead class="bg-gray-50">
                        <tr>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">User</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Type / Role</th>
                            <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">IP Address</th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Login Time</th>
                             <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Last Activity</th>
                             <th class="px-6 py-3 text-right text-xs font-medium text-gray-500 uppercase tracking-wider">Action</th>
                        </tr>
                    </thead>
                    <tbody class="bg-white divide-y divide-gray-200">
                        <?php if(empty($trails)): ?>
                             <tr><td colspan="6" class="px-6 py-12 text-center text-gray-500 italic">No active sessions found.</td></tr>
                        <?php else: ?>
                            <?php foreach($trails as $trail): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap">
                                    <div class="text-sm font-medium text-gray-900"><?= htmlspecialchars($trail['user_name']) ?></div>
                                    <div class="text-xs text-gray-500"><?= htmlspecialchars($trail['identity']) ?></div>
                                </td>
                                 <td class="px-6 py-4 whitespace-nowrap">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800 uppercase">
                                        <?= htmlspecialchars($trail['user_type']) ?>
                                    </span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 font-mono">
                                    <?= htmlspecialchars($trail['ip_address']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('M j, H:i', strtotime($trail['login_time'])) ?>
                                </td>
                                 <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= date('H:i:s', strtotime($trail['last_activity'])) ?>
                                    <span class="text-gray-400 text-xs">(<?= round((time() - strtotime($trail['last_activity'])) / 60) ?>m ago)</span>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-right text-sm">
                                    <div class="flex justify-end gap-2">
                                        <button onclick="openMessageModal('<?= $trail['user_id'] ?>', '<?= $trail['user_type'] ?>', '<?= htmlspecialchars($trail['user_name']) ?>')" 
                                                class="text-indigo-600 hover:text-indigo-900 font-medium hover:underline">
                                            Message
                                        </button>
                                        <form action="/admin/portal/end-session" method="POST" onsubmit="return confirm('Are you sure you want to end this session? The user will be logged out immediately.');" class="inline">
                                            <input type="hidden" name="session_id" value="<?= $trail['id'] ?>">
                                            <button type="submit" class="text-red-600 hover:text-red-900 font-medium hover:underline">
                                                End Session
                                            </button>
                                        </form>
                                    </div>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
        </div>

<!-- Send Message Modal -->
<div id="message-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-filter backdrop-blur-sm" aria-hidden="true" onclick="closeMessageModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Send Message to <span id="msg-user-name"></span>
                </h3>
                
                <form action="/admin/portal/send-message" method="POST" enctype="multipart/form-data" class="mt-4">
                    <input type="hidden" name="user_id" id="msg-user-id">
                    <input type="hidden" name="user_type" id="msg-user-type">
                    
                    <div class="grid grid-cols-1 gap-y-3">
                        <div>
                            <label for="msg-title" class="block text-sm font-medium text-gray-700">Subject</label>
                            <input type="text" name="title" id="msg-title" required
                                   class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                        </div>
                        <div>
                            <label for="msg-message" class="block text-sm font-medium text-gray-700">Message</label>
                            <textarea name="message" id="msg-message" rows="4" required
                                      class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md"></textarea>
                        </div>
                        <div>
                            <label for="msg-attachment" class="block text-sm font-medium text-gray-700">Attachment (Optional)</label>
                            <input type="file" name="attachment" id="msg-attachment" 
                                   class="mt-1 block w-full text-sm text-gray-500 file:mr-4 file:py-2 file:px-4 file:rounded-full file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6 mt-6 flex justify-end">
                        <button type="button" class="mr-3 bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="closeMessageModal()">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Send Message
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Manage Access Modal -->
<div id="manage-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-filter backdrop-blur-sm" aria-hidden="true" onclick="closeManageModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900" id="modal-title">
                    Manage Access: <span id="modal-student-name"></span>
                </h3>
                
                <form action="/admin/portal/update-access" method="POST" class="mt-4">
                    <input type="hidden" name="student_id" id="form-student-id">
                    
                    <!-- Student Settings -->
                    <div class="border-t border-gray-200 pt-4">
                         <h4 class="text-sm font-bold text-gray-900 mb-3">Student Portal</h4>
                         <div class="grid grid-cols-1 gap-y-3">
                            <div>
                                <label for="student-status-select" class="block text-xs font-medium text-gray-700">Access Status</label>
                                <select name="student_status" id="student-status-select" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                    <option value="deactivated">Deactivated</option>
                                </select>
                            </div>
                            <div>
                                <label for="student-password" class="block text-xs font-medium text-gray-700">Set New Password (Optional)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="password" name="student_password" id="student-password" placeholder="Leave blank to keep current" 
                                           class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md pr-10">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <button type="button" onclick="togglePasswordVisibility('student-password')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>

                    <!-- Parent Settings -->
                    <div class="border-t border-gray-200 pt-4 mt-4">
                         <h4 class="text-sm font-bold text-gray-900 mb-3">Parent Portal</h4>
                         <div class="grid grid-cols-1 gap-y-3">
                            <div>
                                <label for="parent-status-select" class="block text-xs font-medium text-gray-700">Access Status</label>
                                <select name="parent_status" id="parent-status-select" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                    <option value="active">Active</option>
                                    <option value="suspended">Suspended</option>
                                    <option value="deactivated">Deactivated</option>
                                </select>
                            </div>
                            <div>
                                <label for="parent-password" class="block text-xs font-medium text-gray-700">Set New Password (Optional)</label>
                                <div class="mt-1 relative rounded-md shadow-sm">
                                    <input type="password" name="parent_password" id="parent-password" placeholder="Leave blank to keep current" 
                                           class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md pr-10">
                                    <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                        <button type="button" onclick="togglePasswordVisibility('parent-password')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                            <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                            </svg>
                                        </button>
                                    </div>
                                </div>
                            </div>
                         </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6 mt-6 flex justify-end">
                        <button type="button" class="mr-3 bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="closeManageModal()">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Save Changes
                        </button>
                    </div>
                </form>
            </div>
            <!-- Removed Close button since we have Cancel in the form now -->
            </div>
        </div>
    </div>
</div>
        </div>
    </div>
</div>

<!-- Grant Staff Access Modal -->
<div id="grant-staff-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-filter backdrop-blur-sm" aria-hidden="true" onclick="closeGrantStaffModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Grant Portal Access: <span id="grant-staff-name"></span>
                </h3>
                <form action="/admin/portal/grant-staff-access" method="POST" class="mt-4">
                    <input type="hidden" name="staff_id" id="grant-staff-id">
                    
                    <div class="grid grid-cols-1 gap-y-3">
                        <div>
                            <label for="grant-username" class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" name="username" id="grant-username" required
                                   class="mt-1 shadow-sm focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md">
                            <p class="mt-1 text-xs text-gray-500">Defaults to email or Employee ID.</p>
                        </div>
                        <div>
                            <label for="grant-password" class="block text-sm font-medium text-gray-700">Password</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="password" name="password" id="grant-password" required
                                       class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md pr-10">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" onclick="togglePasswordVisibility('grant-password')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6 mt-6 flex justify-end">
                        <button type="button" class="mr-3 bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="closeGrantStaffModal()">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Create Account
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<!-- Manage Staff Access Modal -->
<div id="manage-staff-modal" class="fixed inset-0 z-50 overflow-y-auto hidden" aria-labelledby="modal-title" role="dialog" aria-modal="true">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity backdrop-filter backdrop-blur-sm" aria-hidden="true" onclick="closeManageStaffModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen" aria-hidden="true">&#8203;</span>
        
        <div class="inline-block align-bottom bg-white rounded-lg px-4 pt-5 pb-4 text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full sm:p-6">
            <div>
                <h3 class="text-lg leading-6 font-medium text-gray-900">
                    Manage Access: <span id="manage-staff-name"></span>
                </h3>
                <form action="/admin/portal/update-staff-access" method="POST" class="mt-4">
                    <input type="hidden" name="staff_id" id="manage-staff-id">
                    <input type="hidden" name="user_id" id="manage-user-id">
                    
                    <div class="grid grid-cols-1 gap-y-3">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Username</label>
                            <input type="text" id="manage-username" disabled
                                   class="mt-1 bg-gray-100 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md text-gray-500">
                        </div>
                        <div>
                            <label for="manage-status" class="block text-sm font-medium text-gray-700">Status</label>
                            <select name="status" id="manage-status" class="mt-1 block w-full py-2 px-3 border border-gray-300 bg-white rounded-md shadow-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="active">Active</option>
                                <option value="suspended">Suspended</option>
                                <option value="deactivated">Deactivated</option>
                            </select>
                        </div>
                        <div>
                            <label for="manage-password" class="block text-sm font-medium text-gray-700">New Password (Optional)</label>
                            <div class="mt-1 relative rounded-md shadow-sm">
                                <input type="password" name="password" id="manage-password" placeholder="Leave blank to keep current"
                                       class="focus:ring-indigo-500 focus:border-indigo-500 block w-full sm:text-sm border-gray-300 rounded-md pr-10">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center">
                                    <button type="button" onclick="togglePasswordVisibility('manage-password')" class="text-gray-400 hover:text-gray-500 focus:outline-none">
                                        <svg class="h-5 w-5" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="border-t border-gray-200 pt-6 mt-6 flex justify-end">
                        <button type="button" class="mr-3 bg-white py-2 px-4 border border-gray-300 rounded-md shadow-sm text-sm font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500" onclick="closeManageStaffModal()">
                            Cancel
                        </button>
                        <button type="submit" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Update Access
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
function togglePasswordVisibility(inputId) {
    const input = document.getElementById(inputId);
    if (input.type === 'password') {
        input.type = 'text';
    } else {
        input.type = 'password';
    }
}

function openMessageModal(userId, userType, userName) {
    document.getElementById('msg-user-name').textContent = userName;
    document.getElementById('msg-user-id').value = userId;
    document.getElementById('msg-user-type').value = userType;
    document.getElementById('msg-title').value = '';
    document.getElementById('msg-message').value = '';
    document.getElementById('msg-attachment').value = '';
    
    document.getElementById('message-modal').classList.remove('hidden');
}

function closeMessageModal() {
    document.getElementById('message-modal').classList.add('hidden');
}

function openManageModal(id, name, studentStatus, parentStatus) {
    document.getElementById('modal-student-name').textContent = name;
    document.getElementById('form-student-id').value = id;
    
    // Set status
    document.getElementById('student-status-select').value = studentStatus;
    document.getElementById('parent-status-select').value = parentStatus;
    
    // Clear passwords
    document.getElementById('student-password').value = '';
    document.getElementById('parent-password').value = '';

    document.getElementById('manage-modal').classList.remove('hidden');
}

function closeManageModal() {
    document.getElementById('manage-modal').classList.add('hidden');
}

function switchTab(tabId) {
    // Buttons
    document.getElementById('tab-access').classList.remove('border-indigo-500', 'text-indigo-600');
    document.getElementById('tab-access').classList.add('border-transparent', 'text-gray-500');
    
    document.getElementById('tab-trails').classList.remove('border-indigo-500', 'text-indigo-600');
    document.getElementById('tab-trails').classList.add('border-transparent', 'text-gray-500');
    
    document.getElementById('tab-staff').classList.remove('border-indigo-500', 'text-indigo-600');
    document.getElementById('tab-staff').classList.add('border-transparent', 'text-gray-500');

    // Active Button
    document.getElementById('tab-' + tabId).classList.remove('border-transparent', 'text-gray-500');
    document.getElementById('tab-' + tabId).classList.add('border-indigo-500', 'text-indigo-600');
    
    // Content
    document.getElementById('content-access').classList.add('hidden');
    document.getElementById('content-staff').classList.add('hidden');
    document.getElementById('content-trails').classList.add('hidden');
    
    document.getElementById('content-' + tabId).classList.remove('hidden');
}

function openGrantStaffModal(id, name, email, employeeId) {
    document.getElementById('grant-staff-id').value = id;
    document.getElementById('grant-staff-name').textContent = name;
    
    // Auto-fill username if possible
    let suggestedUsername = email || employeeId;
    document.getElementById('grant-username').value = suggestedUsername;
    document.getElementById('grant-password').value = ''; // Clean slate
    
    document.getElementById('grant-staff-modal').classList.remove('hidden');
}

function closeGrantStaffModal() {
    document.getElementById('grant-staff-modal').classList.add('hidden');
}

function openManageStaffModal(id, name, username, status, userId) {
    document.getElementById('manage-staff-id').value = id;
    document.getElementById('manage-user-id').value = userId;
    document.getElementById('manage-staff-name').textContent = name;
    document.getElementById('manage-username').value = username;
    document.getElementById('manage-status').value = status.toLowerCase(); // Ensure lowercase match
    document.getElementById('manage-password').value = '';
    
    document.getElementById('manage-staff-modal').classList.remove('hidden');
}

function closeManageStaffModal() {
    document.getElementById('manage-staff-modal').classList.add('hidden');
}

// Init Tab from Hash or Query Param
document.addEventListener('DOMContentLoaded', () => {
    const urlParams = new URLSearchParams(window.location.search);
    const tabParam = urlParams.get('tab');
    const hash = window.location.hash;

    if (hash === '#trails' || tabParam === 'trails') {
        switchTab('trails');
    } else if (hash === '#staff' || tabParam === 'staff') {
        switchTab('staff');
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>
