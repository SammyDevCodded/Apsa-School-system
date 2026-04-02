<!-- Payment Requests Partial -->
<div class="sm:flex sm:items-center sm:justify-between mb-4">
    <div>
        <h3 class="text-lg leading-6 font-medium text-gray-900">Payment Requests</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Staff members can submit payment requests for approval.</p>
    </div>
    <div class="mt-4 sm:mt-0 flex space-x-3">
        <?php if (!empty($can_request_on_behalf)): ?>
            <button onclick="openProxyRequestModal()" class="inline-flex items-center px-4 py-2 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                <svg class="-ml-1 mr-2 h-5 w-5 text-gray-500" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                    <path fill-rule="evenodd" d="M10 9a3 3 0 100-6 3 3 0 000 6zm-7 9a7 7 0 1114 0H3z" clip-rule="evenodd" />
                </svg>
                Request on Behalf
            </button>
        <?php endif; ?>
        <button onclick="openRequestModal()" class="inline-flex items-center px-4 py-2 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
            <svg class="-ml-1 mr-2 h-5 w-5" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
                <path fill-rule="evenodd" d="M10 5a1 1 0 011 1v3h3a1 1 0 110 2h-3v3a1 1 0 11-2 0v-3H6a1 1 0 110-2h3V6a1 1 0 011-1z" clip-rule="evenodd" />
            </svg>
            Create Request
        </button>
    </div>
</div>

<div class="bg-white shadow overflow-hidden sm:rounded-lg">
    <div class="overflow-x-auto">
        <table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50">
                <tr>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Request Date</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Applicant & Purpose</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Amount</th>
                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Status</th>
                    <th scope="col" class="relative px-6 py-3">
                        <span class="sr-only">Actions</span>
                    </th>
                </tr>
            </thead>
            <tbody class="bg-white divide-y divide-gray-200">
                <?php if (empty($requests)): ?>
                    <tr>
                        <td colspan="5" class="px-6 py-4 whitespace-nowrap text-sm text-gray-500 text-center">No payment requests found.</td>
                    </tr>
                <?php else: ?>
                    <?php foreach ($requests as $req): ?>
                        <tr>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900"><?= date('M j, Y H:i', strtotime($req['created_at'])) ?></td>
                            <td class="px-6 py-4">
                                <div class="text-sm font-medium text-gray-900">
                                    <?= htmlspecialchars($req['rq_first'] . ' ' . $req['rq_last']) ?>
                                </div>
                                <div class="text-xs text-gray-500 mt-1 flex items-center flex-wrap gap-2">
                                    <span><?= htmlspecialchars($req['purpose']) ?></span>
                                    <?php if (!empty($req['staff_first']) && $req['requested_by'] != $req['staff_user_id']): ?>
                                        <span class="inline-flex items-center px-2 py-0.5 rounded text-[10px] font-medium bg-indigo-50 text-indigo-700 border border-indigo-100">
                                            On behalf of <?= htmlspecialchars($req['staff_first'] . ' ' . $req['staff_last']) ?>
                                        </span>
                                    <?php endif; ?>
                                </div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                                <?= \App\Helpers\CurrencyHelper::formatAmount($req['amount']) ?>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <?php
                                $statusColors = [
                                    'pending' => 'bg-yellow-100 text-yellow-800',
                                    'approved' => 'bg-blue-100 text-blue-800',
                                    'rejected' => 'bg-red-100 text-red-800',
                                    'paid' => 'bg-green-100 text-green-800'
                                ];
                                $colorClass = $statusColors[$req['status']] ?? 'bg-gray-100 text-gray-800';
                                ?>
                                <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $colorClass ?>">
                                    <?= ucfirst($req['status']) ?>
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-right text-sm font-medium">
                                <?php if ($req['status'] === 'pending' && $req['requested_by'] == $_SESSION['user']['id']): ?>
                                    <button onclick='editRequest(<?= json_encode($req) ?>)' class="text-indigo-600 hover:text-indigo-900 mr-2">Edit</button>
                                <?php endif; ?>
                                
                                <?php if ($req['status'] === 'pending'): ?>
                                    <form action="/finance/expenses/request/status" method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                        <input type="hidden" name="status" value="approved">
                                        <button type="submit" class="text-blue-600 hover:text-blue-900 mr-2" onclick="return confirm('Approve this request?');">Approve</button>
                                    </form>
                                    <form action="/finance/expenses/request/status" method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                        <input type="hidden" name="status" value="rejected">
                                        <button type="submit" class="text-red-600 hover:text-red-900 mr-2" onclick="return confirm('Reject this request?');">Reject</button>
                                    </form>
                                <?php endif; ?>
                                
                                <?php if ($req['status'] === 'approved'): ?>
                                    <form action="/finance/expenses/request/status" method="POST" class="inline">
                                        <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                        <input type="hidden" name="status" value="paid">
                                        <button type="submit" class="text-green-600 hover:text-green-900 font-bold" onclick="return confirm('Mark as Paid? This will debit the Cash Book.');">Mark Paid</button>
                                    </form>
                                <?php endif; ?>
                                
                                <?php if ($req['status'] === 'pending' && $req['requested_by'] == $_SESSION['user']['id']): ?>
                                    <form action="/finance/expenses/request/delete" method="POST" class="inline ml-2">
                                        <input type="hidden" name="id" value="<?= $req['id'] ?>">
                                        <button type="submit" class="text-red-600 hover:text-red-900" onclick="return confirm('Delete this request entirely?');">Delete</button>
                                    </form>
                                <?php endif; ?>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php endif; ?>
            </tbody>
        </table>
    </div>
</div>

<!-- Modal: Add/Edit Request -->
<div id="add-request-modal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeRequestModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="request-form" action="/finance/expenses/request" method="POST">
                <input type="hidden" name="id" id="req_id" value="">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900" id="req-modal-title">Create Payment Request</h3>
                    <div class="mt-4 space-y-4">

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount Required (<?= \App\Helpers\CurrencyHelper::getCurrencySymbol() ?>) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" min="0.01" name="amount" id="req_amount" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                        </div>
                        <div>
                            <label for="purpose" class="block text-sm font-medium text-gray-700">Purpose / Details <span class="text-red-500">*</span></label>
                            <textarea name="purpose" id="req_purpose" required rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border" placeholder="Please provide clear reasons identifying the need for this payment..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Submit Request
                    </button>
                    <button type="button" onclick="closeRequestModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal: Proxy Request On Behalf -->
<?php if (!empty($can_request_on_behalf)): ?>
<div id="add-proxy-request-modal" class="hidden fixed z-50 inset-0 overflow-y-auto">
    <div class="flex items-end justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-500 bg-opacity-75 transition-opacity" onclick="closeProxyRequestModal()"></div>
        <span class="hidden sm:inline-block sm:align-middle sm:h-screen">&#8203;</span>
        <div class="inline-block align-bottom bg-white rounded-lg text-left overflow-hidden shadow-xl transform transition-all sm:my-8 sm:align-middle sm:max-w-lg sm:w-full">
            <form id="proxy-request-form" action="/finance/expenses/request" method="POST">
                <input type="hidden" name="request_on_behalf" value="1">
                <div class="bg-white px-4 pt-5 pb-4 sm:p-6 sm:pb-4">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Request Payment On Behalf</h3>
                    <div class="mt-4 space-y-4">
                        <div id="staff_select_container" class="relative mb-4">
                            <label for="on_behalf_staff_id" class="block text-sm font-medium text-gray-700 mb-1">Select Target Staff Member <span class="text-red-500">*</span></label>
                            <input type="hidden" name="on_behalf_staff_id" id="on_behalf_staff_id" value="" required>
                            <div class="relative">
                                <input type="text" id="staff_search_input" required class="w-full pl-3 pr-10 py-2 text-base border-gray-300 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm rounded-md border" placeholder="Search staff to request for...">
                                <div class="absolute inset-y-0 right-0 pr-3 flex items-center pointer-events-none">
                                    <svg class="h-5 w-5 text-gray-400" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor" aria-hidden="true">
                                        <path fill-rule="evenodd" d="M10 3a1 1 0 01.707.293l3 3a1 1 0 01-1.414 1.414L10 5.414 7.707 7.707a1 1 0 01-1.414-1.414l3-3A1 1 0 0110 3zm-3.707 9.293a1 1 0 011.414 0L10 14.586l2.293-2.293a1 1 0 011.414 1.414l-3 3a1 1 0 01-1.414 0l-3-3a1 1 0 010-1.414z" clip-rule="evenodd" />
                                    </svg>
                                </div>
                            </div>
                            <ul id="staff_dropdown_list" class="absolute z-10 mt-1 w-full bg-white shadow-lg max-h-60 rounded-md py-1 text-base ring-1 ring-black ring-opacity-5 overflow-auto focus:outline-none sm:text-sm hidden" tabindex="-1" role="listbox">
                                 <?php if (!empty($staffs)): ?>
                                     <?php foreach ($staffs as $staff): ?>
                                         <li class="staff-option text-gray-900 cursor-default select-none relative py-2 pl-3 pr-9 hover:bg-indigo-600 hover:text-white" id="staff-option-<?= $staff['id'] ?>" role="option" data-value="<?= $staff['id'] ?>" data-name="<?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?>">
                                             <span class="font-normal block truncate"><?= htmlspecialchars($staff['first_name'] . ' ' . $staff['last_name']) ?></span>
                                         </li>
                                     <?php endforeach; ?>
                                 <?php else: ?>
                                     <li class="text-gray-500 cursor-default select-none relative py-2 pl-3 pr-9">No staff found</li>
                                 <?php endif; ?>
                            </ul>
                        </div>
                        <div>
                            <label for="proxy_req_amount" class="block text-sm font-medium text-gray-700">Amount Required (<?= \App\Helpers\CurrencyHelper::getCurrencySymbol() ?>) <span class="text-red-500">*</span></label>
                            <input type="number" step="0.01" min="0.01" name="amount" id="proxy_req_amount" required class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border">
                        </div>
                        <div>
                            <label for="proxy_req_purpose" class="block text-sm font-medium text-gray-700">Purpose / Details <span class="text-red-500">*</span></label>
                            <textarea name="purpose" id="proxy_req_purpose" required rows="3" class="mt-1 focus:ring-indigo-500 focus:border-indigo-500 block w-full shadow-sm sm:text-sm border-gray-300 rounded-md py-2 px-3 border" placeholder="Explain the rationale for filing this on their behalf..."></textarea>
                        </div>
                    </div>
                </div>
                <div class="bg-gray-50 px-4 py-3 sm:px-6 sm:flex sm:flex-row-reverse">
                    <button type="submit" class="w-full inline-flex justify-center rounded-md border border-transparent shadow-sm px-4 py-2 bg-indigo-600 text-base font-medium text-white hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:ml-3 sm:w-auto sm:text-sm">
                        Submit Proxy Request
                    </button>
                    <button type="button" onclick="closeProxyRequestModal()" class="mt-3 w-full inline-flex justify-center rounded-md border border-gray-300 shadow-sm px-4 py-2 bg-white text-base font-medium text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500 sm:mt-0 sm:ml-3 sm:w-auto sm:text-sm">
                        Cancel
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>
<?php endif; ?>

<script>
function openRequestModal() {
    document.getElementById('request-form').reset();
    document.getElementById('req_id').value = '';
    document.getElementById('req-modal-title').innerText = 'Create Payment Request';
    
    document.getElementById('add-request-modal').classList.remove('hidden');
}

function openProxyRequestModal() {
    document.getElementById('proxy-request-form').reset();
    document.getElementById('on_behalf_staff_id').value = '';
    
    // Hide dropdown if open
    const dropdownList = document.getElementById('staff_dropdown_list');
    if (dropdownList) dropdownList.classList.add('hidden');
    
    document.getElementById('add-proxy-request-modal').classList.remove('hidden');
}

function closeProxyRequestModal() {
    document.getElementById('add-proxy-request-modal').classList.add('hidden');
}

function editRequest(req) {
    document.getElementById('req_id').value = req.id;
    document.getElementById('req_amount').value = req.amount;
    document.getElementById('req_purpose').value = req.purpose;
    document.getElementById('req-modal-title').innerText = 'Edit Payment Request';
    
    document.getElementById('add-request-modal').classList.remove('hidden');
}

function closeRequestModal() {
    document.getElementById('add-request-modal').classList.add('hidden');
}

// Custom Dropdown Logic Initialization
document.addEventListener('DOMContentLoaded', () => {
    // Custom Dropdown Logic
    const searchInput = document.getElementById('staff_search_input');
    const dropdownList = document.getElementById('staff_dropdown_list');
    const hiddenInput = document.getElementById('on_behalf_staff_id');

    if (searchInput && dropdownList && hiddenInput) {
        // Show dropdown on click
        searchInput.addEventListener('click', function(e) {
            e.stopPropagation();
            dropdownList.classList.toggle('hidden');
            filterDropdown(''); // Show all on click initially
        });

        // Filter on input
        searchInput.addEventListener('input', function(e) {
            dropdownList.classList.remove('hidden');
            filterDropdown(e.target.value.toLowerCase());
        });

        // Handle selection
        const options = dropdownList.querySelectorAll('.staff-option');
        options.forEach(option => {
            option.addEventListener('click', function(e) {
                e.stopPropagation();
                
                // Update hidden input
                hiddenInput.value = this.dataset.value;
                
                // Update visible text
                searchInput.value = this.dataset.name;
                
                // Hide dropdown
                dropdownList.classList.add('hidden');
            });
        });

        // Close dropdown when clicking outside
        document.addEventListener('click', function(e) {
            if (!searchInput.contains(e.target) && !dropdownList.contains(e.target)) {
                dropdownList.classList.add('hidden');
                
                // If they clicked away without selecting, and it's required (but not filled), we can handle validation here
                // For now, if the hidden ID is empty but they typed something, we clear it so it's not confusing
                if (!hiddenInput.value && searchInput.value) {
                    searchInput.value = '';
                }
            }
        });

        function filterDropdown(searchTerm) {
            let visibleCount = 0;
            options.forEach(option => {
                const text = option.dataset.name.toLowerCase();
                if (text.includes(searchTerm)) {
                    option.style.display = 'block';
                    visibleCount++;
                } else {
                    option.style.display = 'none';
                }
            });
            
            // Handle no results
            const noResultsItem = dropdownList.querySelector('.no-results-item');
            if (visibleCount === 0) {
                if (!noResultsItem) {
                    const li = document.createElement('li');
                    li.className = 'text-gray-500 cursor-default select-none relative py-2 pl-3 pr-9 no-results-item';
                    li.innerText = 'No matches found';
                    dropdownList.appendChild(li);
                }
            } else if (noResultsItem) {
                noResultsItem.remove();
            }
        }
    }

    const urlParams = new URLSearchParams(window.location.search);
    if (urlParams.get('action') === 'add' && urlParams.get('tab') === 'requests') {
        openRequestModal();
    }
});
</script>
