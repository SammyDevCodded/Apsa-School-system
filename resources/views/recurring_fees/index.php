<?php
ob_start();
?>
<div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">

    <!-- Page Header -->
    <div class="flex justify-between items-start mb-6">
        <div>
            <h1 class="text-2xl font-bold text-gray-900">Recurring Fees</h1>
            <p class="mt-1 text-sm text-gray-500">
                Manage recurring service charges (food, bus, etc.) with daily billing and correction support.
                <?php if (!empty($currentYear)): ?>
                    <span class="ml-1 text-indigo-600 font-medium"><?= htmlspecialchars($currentYear['name']) ?></span>
                <?php endif; ?>
            </p>
        </div>
        <button id="openCreateFeeModal"
            class="inline-flex items-center px-4 py-2 border-2 border-green-700 text-sm font-semibold rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 shadow-sm transition-colors">
            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 4v16m8-8H4"/>
            </svg>
            New Recurring Fee
        </button>
    </div>

    <!-- Tabs -->
    <div class="border-b border-gray-200 mb-6">
        <nav class="-mb-px flex space-x-8" id="rf-tabs">
            <button data-tab="services" class="rf-tab border-b-2 border-indigo-500 text-indigo-600 whitespace-nowrap pb-3 px-1 text-sm font-semibold">
                Fee Services
            </button>
            <button data-tab="enrollments" class="rf-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap pb-3 px-1 text-sm font-medium">
                Enrollments
            </button>
            <button data-tab="bills" class="rf-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap pb-3 px-1 text-sm font-medium">
                Bills &amp; Corrections
            </button>
            <button data-tab="pay" class="rf-tab border-b-2 border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300 whitespace-nowrap pb-3 px-1 text-sm font-medium">
                💳 Pay
            </button>
        </nav>
    </div>

    <!-- ── Tab: Fee Services ───────────────────────────────────────────── -->
    <div id="tab-services" class="rf-panel">
        <?php if (empty($fees)): ?>
        <div class="text-center py-16 bg-white rounded-xl border border-dashed border-gray-300">
            <svg class="mx-auto h-12 w-12 text-gray-400" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="1.5" d="M9 14l6-6m-5.5.5h.01m4.99 5h.01M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16l3.5-2 3.5 2 3.5-2 3.5 2z"/>
            </svg>
            <p class="mt-2 text-sm text-gray-600">No recurring fees yet. Click <strong>New Recurring Fee</strong> to get started.</p>
        </div>
        <?php else: ?>
        <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-4">
            <?php foreach ($fees as $fee): ?>
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm hover:shadow-md transition-shadow p-5 flex flex-col gap-3"
                 id="fee-card-<?= $fee['id'] ?>">
                <div class="flex items-start justify-between">
                    <div>
                        <h3 class="font-bold text-gray-900 text-base"><?= htmlspecialchars($fee['name']) ?></h3>
                        <?php if ($fee['description']): ?>
                        <p class="text-xs text-gray-500 mt-0.5"><?= htmlspecialchars($fee['description']) ?></p>
                        <?php endif; ?>
                    </div>
                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-xs font-medium <?= $fee['active'] ? 'bg-green-100 text-green-800' : 'bg-gray-100 text-gray-500' ?>">
                        <?= $fee['active'] ? 'Active' : 'Inactive' ?>
                    </span>
                </div>
                <div class="flex items-center gap-4 text-sm">
                    <div>
                        <span class="text-gray-400 text-xs uppercase tracking-wide">Amount</span>
                        <p class="font-semibold text-indigo-700 text-lg"><?= htmlspecialchars($currencySymbol) ?><?= number_format($fee['amount_per_unit'], 2) ?></p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs uppercase tracking-wide">Cycle</span>
                        <p class="font-medium text-gray-700 capitalize"><?= htmlspecialchars($fee['billing_cycle']) ?></p>
                    </div>
                    <div>
                        <span class="text-gray-400 text-xs uppercase tracking-wide">Scope</span>
                        <p class="font-medium text-gray-700 capitalize"><?= htmlspecialchars($fee['scope']) ?></p>
                    </div>
                </div>
                <div class="flex gap-2 pt-1 border-t border-gray-100">
                    <button onclick="openEnrollModal(<?= $fee['id'] ?>, <?= htmlspecialchars(json_encode($fee['name'])) ?>)"
                        class="flex-1 text-xs py-1.5 px-3 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-medium transition-colors">
                        Enroll Students
                    </button>
                    <button onclick="openGenerateModal(<?= $fee['id'] ?>, <?= htmlspecialchars(json_encode($fee)) ?>)"
                        class="flex-1 text-xs py-1.5 px-3 rounded-lg bg-emerald-50 text-emerald-700 hover:bg-emerald-100 font-medium transition-colors">
                        Generate Bills
                    </button>
                    <button onclick="openBillsTab(<?= $fee['id'] ?>, <?= htmlspecialchars(json_encode($fee['name'])) ?>)"
                        class="flex-1 text-xs py-1.5 px-3 rounded-lg bg-orange-50 text-orange-700 hover:bg-orange-100 font-medium transition-colors">
                        View Bills
                    </button>
                    <button onclick="toggleFee(<?= $fee['id'] ?>, <?= $fee['active'] ?>)"
                        class="text-xs py-1.5 px-3 rounded-lg <?= $fee['active'] ? 'bg-red-50 text-red-600 hover:bg-red-100' : 'bg-green-50 text-green-700 hover:bg-green-100' ?> font-medium transition-colors">
                        <?= $fee['active'] ? 'Disable' : 'Enable' ?>
                    </button>
                </div>
            </div>
            <?php endforeach; ?>
        </div>
        <?php endif; ?>
    </div>

    <!-- ── Tab: Enrollments ───────────────────────────────────────────── -->
    <div id="tab-enrollments" class="rf-panel hidden">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <div class="flex items-center gap-4 mb-4">
                <div class="flex-1">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Recurring Fee</label>
                    <select id="enrollFeeSelect" class="w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Choose a fee to view enrollments --</option>
                        <?php foreach ($fees as $fee): ?>
                        <option value="<?= $fee['id'] ?>"><?= htmlspecialchars($fee['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div class="pt-5">
                    <button id="loadEnrollmentsBtn"
                        class="inline-flex items-center px-4 py-2 text-sm font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm transition-colors">
                        Load
                    </button>
                </div>
            </div>
            <div id="enrollmentsTableWrap">
                <div class="text-center py-8 text-gray-400 text-sm">Select a fee above to view its enrollments.</div>
            </div>
        </div>
    </div>

    <!-- ── Tab: Bills & Corrections ──────────────────────────────────── -->
    <div id="tab-bills" class="rf-panel hidden">
        <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-6">
            <!-- Filter bar -->
            <div class="grid grid-cols-2 md:grid-cols-5 gap-3 mb-5">
                <div class="col-span-2 md:col-span-1">
                    <label class="block text-xs font-medium text-gray-600 mb-1">Fee Service</label>
                    <select id="billFeeSelect" class="w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">-- Select fee --</option>
                        <?php foreach ($fees as $fee): ?>
                        <option value="<?= $fee['id'] ?>"><?= htmlspecialchars($fee['name']) ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">From Date</label>
                    <input type="date" id="billFromDate" value="<?= date('Y-m-01') ?>"
                        class="w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">To Date</label>
                    <input type="date" id="billToDate" value="<?= date('Y-m-d') ?>"
                        class="w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                </div>
                <div>
                    <label class="block text-xs font-medium text-gray-600 mb-1">Status</label>
                    <select id="billStatusFilter" class="w-full border border-gray-300 rounded-lg shadow-sm py-2 px-3 text-sm focus:outline-none focus:ring-indigo-500 focus:border-indigo-500">
                        <option value="">All</option>
                        <option value="pending">Pending</option>
                        <option value="paid">Paid</option>
                        <option value="waived">Waived</option>
                    </select>
                </div>
                <div class="flex items-end">
                    <button id="loadBillsBtn"
                        class="w-full inline-flex justify-center items-center px-4 py-2 text-sm font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 shadow-sm transition-colors">
                        <svg class="h-4 w-4 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 4a1 1 0 011-1h16a1 1 0 010 2H4a1 1 0 01-1-1zm3 4h10M5 12h14M7 16h10"/>
                        </svg>
                        Load Bills
                    </button>
                </div>
            </div>

            <!-- Summary cards -->
            <div id="billSummaryCards" class="hidden grid grid-cols-2 md:grid-cols-4 gap-3 mb-5">
                <div class="bg-yellow-50 rounded-lg p-3 border border-yellow-200">
                    <p class="text-xs text-yellow-600 font-medium uppercase">Pending</p>
                    <p id="summPending" class="text-xl font-bold text-yellow-800 mt-0.5">0.00</p>
                </div>
                <div class="bg-green-50 rounded-lg p-3 border border-green-200">
                    <p class="text-xs text-green-600 font-medium uppercase">Paid</p>
                    <p id="summPaid" class="text-xl font-bold text-green-800 mt-0.5">0.00</p>
                </div>
                <div class="bg-gray-50 rounded-lg p-3 border border-gray-200">
                    <p class="text-xs text-gray-500 font-medium uppercase">Waived</p>
                    <p id="summWaived" class="text-xl font-bold text-gray-700 mt-0.5">0.00</p>
                </div>
                <div class="bg-blue-50 rounded-lg p-3 border border-blue-200">
                    <p class="text-xs text-blue-600 font-medium uppercase">Days Waived</p>
                    <p id="summWaivedDays" class="text-xl font-bold text-blue-800 mt-0.5">0</p>
                </div>
            </div>

            <!-- Bills table -->
            <div id="billsTableWrap">
                <div class="text-center py-8 text-gray-400 text-sm">Select a fee and click Load Bills to see entries.</div>
            </div>
        </div>
    </div>
</div>

<!-- ────────────────────── Create Fee Modal ──────────────────────────── -->
<div id="createFeeModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-60" id="createFeeOverlay"></div>
        <div class="inline-block align-middle bg-white rounded-xl text-left shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full relative">
            <div class="bg-gradient-to-r from-green-600 to-emerald-600 px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-bold text-white">New Recurring Fee</h3>
                <p class="text-green-100 text-sm">Define a recurring service charge (e.g. School Lunch, Bus)</p>
            </div>
            <div class="p-6 space-y-4">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Service Name <span class="text-red-500">*</span></label>
                    <input type="text" id="cf_name" placeholder="e.g. School Lunch"
                        class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Description</label>
                    <textarea id="cf_description" rows="2" placeholder="Optional details"
                        class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500"></textarea>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Amount Per Day <span class="text-red-500">*</span></label>
                        <input type="number" id="cf_amount" min="0.01" step="0.01" placeholder="0.00"
                            class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Default Billing Cycle</label>
                        <select id="cf_cycle" class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Default Scope</label>
                    <select id="cf_scope" class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        <option value="individual">Individual (assign per student)</option>
                        <option value="class">Class (assign per class)</option>
                        <option value="school">School-wide (all students)</option>
                    </select>
                </div>
            </div>
            <div class="px-6 pb-6 flex justify-end gap-3">
                <button id="cancelCreateFee" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors">Cancel</button>
                <button id="submitCreateFee"
                    class="px-5 py-2 border-2 border-green-700 text-sm font-semibold rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                    Create Fee Service
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ────────────────────── Enroll Modal ─────────────────────────────── -->
<div id="enrollModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-60" id="enrollOverlay"></div>
        <div class="inline-block align-middle bg-white rounded-xl text-left shadow-xl transform transition-all sm:my-8 sm:max-w-lg sm:w-full relative">
            <div class="bg-gradient-to-r from-indigo-600 to-purple-600 px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-bold text-white">Enroll Students</h3>
                <p class="text-indigo-100 text-sm" id="enrollModalSubtitle">Assign students to this recurring fee</p>
            </div>
            <div class="p-6 space-y-4">
                <input type="hidden" id="enroll_fee_id">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Enrollment Type</label>
                    <select id="enroll_type" class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="individual">Individual Students</option>
                        <option value="class">Entire Class</option>
                        <option value="school">All Students (School-wide)</option>
                    </select>
                </div>
                <div id="enroll_class_row" class="hidden">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Select Class</label>
                    <select id="enroll_class_id" class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                        <option value="">-- Select a class --</option>
                        <?php foreach ($classes as $class): ?>
                        <option value="<?= $class['id'] ?>"><?= htmlspecialchars($class['name'] . ' (' . ($class['level'] ?? '') . ')') ?></option>
                        <?php endforeach; ?>
                    </select>
                </div>
                <div id="enroll_students_row">
                    <label class="block text-sm font-medium text-gray-700 mb-1">Search &amp; Select Students</label>
                    <input type="text" id="studentSearchInput" placeholder="Type name or admission no..."
                        class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mb-2">
                    <div id="studentSearchResults" class="max-h-40 overflow-y-auto border border-gray-200 rounded-lg bg-gray-50 p-2 text-sm text-gray-500">
                        Type to search students...
                    </div>
                    <div id="selectedStudentsList" class="mt-2 flex flex-wrap gap-1"></div>
                    <input type="hidden" id="enroll_student_ids_json" value="[]">
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Billing Cycle</label>
                        <select id="enroll_billing_cycle" class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                            <option value="daily">Daily</option>
                            <option value="weekly">Weekly</option>
                            <option value="monthly">Monthly</option>
                        </select>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">Start Date</label>
                        <input type="date" id="enroll_start_date" value="<?= date('Y-m-d') ?>"
                            class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    </div>
                </div>
            </div>
            <div class="px-6 pb-6 flex justify-end gap-3">
                <button id="cancelEnroll" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors">Cancel</button>
                <button id="submitEnroll"
                    class="px-5 py-2 border-2 border-indigo-700 text-sm font-semibold rounded-lg text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-500 transition-colors">
                    Enroll
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ────────────────────── Generate Bills Modal ──────────────────────── -->
<div id="generateModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-60" id="generateOverlay"></div>
        <div class="inline-block align-middle bg-white rounded-xl text-left shadow-xl transform transition-all sm:my-8 sm:max-w-md sm:w-full relative">
            <div class="bg-gradient-to-r from-emerald-600 to-teal-600 px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-bold text-white">Generate Bills</h3>
                <p class="text-emerald-100 text-sm" id="generateModalSubtitle">Create daily entries for enrolled students</p>
            </div>
            <div class="p-6 space-y-4">
                <input type="hidden" id="generate_fee_id">
                <div class="bg-blue-50 border border-blue-200 rounded-lg p-3">
                    <p class="text-xs text-blue-700">This will create a bill entry for <strong>each day</strong> in the selected range for all enrolled students. Existing entries are not duplicated.</p>
                </div>
                <div class="grid grid-cols-2 gap-4">
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">From Date</label>
                        <input type="date" id="gen_from_date" value="<?= date('Y-m-01') ?>"
                            class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-1">To Date</label>
                        <input type="date" id="gen_to_date" value="<?= date('Y-m-d') ?>"
                            class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                    </div>
                </div>
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Amount Per Day <span class="text-gray-400 text-xs">(leave blank to use default)</span></label>
                    <input type="number" id="gen_amount" min="0.01" step="0.01" placeholder="Uses fee default if blank"
                        class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-emerald-500">
                </div>
            </div>
            <div class="px-6 pb-6 flex justify-end gap-3">
                <button id="cancelGenerate" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors">Cancel</button>
                <button id="submitGenerate"
                    class="px-5 py-2 border-2 border-green-700 text-sm font-semibold rounded-lg text-white bg-green-600 hover:bg-green-700 focus:outline-none focus:ring-2 focus:ring-green-500 transition-colors">
                    Generate Bills
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ────────────────────── Waive Entry Modal ─────────────────────────── -->
<div id="waiveModal" class="fixed inset-0 z-50 overflow-y-auto hidden">
    <div class="flex items-center justify-center min-h-screen pt-4 px-4 pb-20 text-center sm:block sm:p-0">
        <div class="fixed inset-0 bg-gray-600 bg-opacity-60" id="waiveOverlay"></div>
        <div class="inline-block align-middle bg-white rounded-xl text-left shadow-xl transform transition-all sm:my-8 sm:max-w-md sm:w-full relative">
            <div class="bg-gradient-to-r from-orange-500 to-amber-500 px-6 py-4 rounded-t-xl">
                <h3 class="text-lg font-bold text-white">Waive Entry (Correction)</h3>
                <p class="text-orange-100 text-sm" id="waiveModalSubtitle">Mark this day as not charged</p>
            </div>
            <div class="p-6 space-y-4">
                <input type="hidden" id="waive_entry_id">
                <div>
                    <label class="block text-sm font-medium text-gray-700 mb-1">Reason for Waiver <span class="text-red-500">*</span></label>
                    <textarea id="waive_reason" rows="3"
                        placeholder="e.g. Student was absent, service not used, public holiday..."
                        class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-orange-500"></textarea>
                </div>
            </div>
            <div class="px-6 pb-6 flex justify-end gap-3">
                <button id="cancelWaive" class="px-4 py-2 text-sm font-medium rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-700 transition-colors">Cancel</button>
                <button id="submitWaive"
                    class="px-5 py-2 border-2 border-orange-600 text-sm font-semibold rounded-lg text-white bg-orange-500 hover:bg-orange-600 focus:outline-none focus:ring-2 focus:ring-orange-400 transition-colors">
                    Confirm Waiver
                </button>
            </div>
        </div>
    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════════════ -->
<!-- PAY TAB PANEL                                                              -->
<!-- ══════════════════════════════════════════════════════════════════════════ -->
<div id="tab-pay" class="rf-panel hidden">
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-5">

        <!-- COL 1 — Fee Selector + Enrolled Students List ──────────────────── -->
        <div class="space-y-4">
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <h3 class="font-semibold text-gray-700 text-xs uppercase tracking-wide mb-2">Fee Service</h3>
                <select id="payFeeSelect" class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500">
                    <option value="">— All / Search by Student —</option>
                    <?php foreach ($fees as $f): ?>
                    <option value="<?= $f['id'] ?>"><?= htmlspecialchars($f['name']) ?> (<?= htmlspecialchars($f['billing_cycle']) ?>)</option>
                    <?php endforeach; ?>
                </select>
            </div>

            <!-- Enrolled Students List — appears when a fee is selected -->
            <div id="payEnrolledPanel" class="hidden bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <h3 class="font-semibold text-gray-700 text-xs uppercase tracking-wide mb-2">Enrolled Students
                    <span id="payEnrolledCount" class="ml-1 text-indigo-500 font-bold"></span>
                </h3>
                <div id="payEnrolledLoading" class="text-center py-4 text-gray-400 text-sm">Loading…</div>
                <div id="payEnrolledList"
                    class="hidden max-h-80 overflow-y-auto divide-y divide-gray-100 -mx-1">
                </div>
            </div>
        </div>

        <!-- COL 2 — Student Search + Bills ─────────────────────────────────── -->
        <div class="space-y-4">
            <!-- Student Search -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <h3 class="font-semibold text-gray-700 text-xs uppercase tracking-wide mb-2">Search &amp; Select Student</h3>
                <input type="text" id="payStudentSearch" autocomplete="off"
                    placeholder="Name or admission no…"
                    class="w-full border border-gray-300 rounded-lg py-2 px-3 text-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 mb-1">
                <div id="payStudentResults"
                    class="border border-gray-200 rounded-lg bg-white shadow-sm max-h-44 overflow-y-auto hidden">
                </div>
                <!-- Selected student badge -->
                <div id="paySelectedStudent" class="mt-2 hidden">
                    <div class="flex items-center justify-between bg-indigo-50 rounded-lg px-3 py-2">
                        <div>
                            <span id="payStudentName"  class="font-semibold text-sm text-indigo-900"></span>
                            <span id="payStudentAdm"   class="ml-2 text-xs text-indigo-500"></span>
                            <span id="payStudentClass" class="ml-1 text-xs text-indigo-400"></span>
                        </div>
                        <button onclick="clearPayStudent()" class="text-indigo-300 hover:text-indigo-600 text-xl leading-none">&times;</button>
                    </div>
                </div>
                <input type="hidden" id="payStudentId" value="">
            </div>

            <!-- Student's Fee Picker — appears when student selected without a fee chosen -->
            <div id="payStudentFeesPanel" class="hidden bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <h3 class="font-semibold text-gray-700 text-xs uppercase tracking-wide mb-2">Student's Pending Fees</h3>
                <div id="payStudentFeesList" class="space-y-2"></div>
            </div>

            <!-- Outstanding Bills Summary -->
            <div id="payBillsSummary" class="hidden bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center justify-between mb-2">
                    <div>
                        <h3 class="font-semibold text-gray-700 text-xs uppercase tracking-wide">Outstanding Bills</h3>
                        <div class="text-base font-bold text-red-600"><span class="pay-currency"></span><span id="payPendingTotal">0.00</span></div>
                    </div>
                    <button id="openLedgerBtn" onclick="openLedger()"
                        class="text-xs px-3 py-1.5 rounded-lg bg-violet-100 text-violet-700 hover:bg-violet-200 font-medium transition-colors whitespace-nowrap">
                        📋 Ledger
                    </button>
                </div>
                <div id="payBillsLoading" class="text-center py-3 text-gray-400 text-sm">Loading…</div>
                <div id="payBillsContent" class="hidden">
                    <p class="text-xs text-gray-400 mb-2"><span id="payPendingCount">0</span> daily entries pending</p>
                    <div id="payPendingList" class="max-h-40 overflow-y-auto text-xs border border-gray-100 rounded-lg divide-y divide-gray-100"></div>
                </div>
                <div id="payNoBills" class="hidden text-center py-2 text-green-600 text-sm font-medium">✅ No outstanding bills.</div>
            </div>
        </div>

        <!-- COL 3 — Payment Form + History ──────────────────────────────────── -->
        <div class="space-y-4">
            <!-- Payment Form -->
            <div id="payFormPanel" class="hidden bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <h3 class="font-semibold text-gray-700 text-xs uppercase tracking-wide mb-3">Record Payment</h3>
                <div class="space-y-3">
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Amount <span class="text-red-500">*</span></label>
                            <input type="number" id="payAmount" min="0.01" step="0.01"
                                class="w-full border border-gray-300 rounded-lg py-1.5 px-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Date <span class="text-red-500">*</span></label>
                            <input type="date" id="payDate"
                                class="w-full border border-gray-300 rounded-lg py-1.5 px-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    <div class="grid grid-cols-2 gap-2">
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Method</label>
                            <select id="payMethod" class="w-full border border-gray-300 rounded-lg py-1.5 px-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                                <option value="cash">Cash</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="cheque">Cheque</option>
                                <option value="mobile_money">Mobile Money</option>
                            </select>
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-600 mb-1">Reference</label>
                            <input type="text" id="payRef" placeholder="Optional"
                                class="w-full border border-gray-300 rounded-lg py-1.5 px-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500">
                        </div>
                    </div>
                    <textarea id="payNotes" rows="2" placeholder="Notes (optional)…"
                        class="w-full border border-gray-300 rounded-lg py-1.5 px-2 text-sm focus:outline-none focus:ring-2 focus:ring-green-500 resize-none"></textarea>
                    <button id="submitPayment"
                        class="w-full py-2.5 border-2 border-green-700 text-sm font-semibold rounded-lg text-white bg-green-600 hover:bg-green-700 active:scale-95 transition-all focus:outline-none focus:ring-2 focus:ring-green-400">
                        💾 Record Payment
                    </button>
                </div>
            </div>

            <!-- Payment History -->
            <div class="bg-white rounded-xl border border-gray-200 shadow-sm p-4">
                <div class="flex items-center justify-between mb-3">
                    <h3 class="font-semibold text-gray-700 text-xs uppercase tracking-wide">Payment History</h3>
                    <button id="loadPayHistoryBtn"
                        class="text-xs px-2.5 py-1 rounded-lg bg-indigo-50 text-indigo-700 hover:bg-indigo-100 font-medium transition-colors">↻</button>
                </div>
                <div id="payHistoryWrap">
                    <p class="text-gray-400 text-xs text-center py-6">Select a fee to view payment history.</p>
                </div>
                <div id="payHistoryTotal" class="hidden mt-2 pt-2 border-t border-gray-100 flex justify-between text-sm font-semibold text-gray-700">
                    <span>Total Collected</span>
                    <span id="payHistoryTotalAmt" class="text-green-600"></span>
                </div>
            </div>
        </div>

    </div>
</div>

<!-- ══════════════════════════════════════════════════════════════════════════ -->
<!-- LEDGER MODAL                                                               -->
<!-- ══════════════════════════════════════════════════════════════════════════ -->
<div id="ledgerModal" class="fixed inset-0 z-50 hidden flex items-start justify-center pt-6 pb-6 px-4" style="background:rgba(15,23,42,0.55);backdrop-filter:blur(4px)">
    <div class="bg-white rounded-2xl shadow-2xl w-full max-w-5xl max-h-[90vh] flex flex-col overflow-hidden">

        <!-- Modal Header -->
        <div class="flex items-center justify-between px-6 py-4 border-b border-gray-100 bg-gradient-to-r from-violet-600 to-indigo-600">
            <div>
                <h2 class="text-lg font-bold text-white">📋 Account Ledger</h2>
                <p id="ledgerSubtitle" class="text-violet-200 text-xs mt-0.5"></p>
            </div>
            <div class="flex items-center gap-3">
                <button onclick="printLedger()"
                    class="text-xs px-3 py-1.5 rounded-lg bg-white/20 text-white hover:bg-white/30 font-medium transition-colors">
                    🖨️ Print
                </button>
                <button onclick="closeLedger()" class="text-white/70 hover:text-white text-2xl leading-none">&times;</button>
            </div>
        </div>

        <!-- Summary Cards -->
        <div id="ledgerSummary" class="grid grid-cols-2 sm:grid-cols-4 gap-3 px-6 py-4 bg-gray-50 border-b border-gray-100">
            <div class="bg-white rounded-xl border border-gray-200 p-3 text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total Billed</p>
                <p id="lsBilled" class="text-xl font-bold text-gray-800"></p>
                <p id="lsTotalDays" class="text-xs text-gray-400 mt-0.5"></p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-3 text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Waived</p>
                <p id="lsWaived" class="text-xl font-bold text-amber-600"></p>
                <p id="lsWaivedDays" class="text-xs text-gray-400 mt-0.5"></p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-3 text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Total Paid</p>
                <p id="lsPaid" class="text-xl font-bold text-green-600"></p>
                <p id="lsPaidDays" class="text-xs text-gray-400 mt-0.5"></p>
            </div>
            <div class="bg-white rounded-xl border border-gray-200 p-3 text-center">
                <p class="text-xs text-gray-500 uppercase tracking-wide mb-1">Outstanding</p>
                <p id="lsOutstanding" class="text-xl font-bold text-red-600"></p>
                <p id="lsPendingDays" class="text-xs text-gray-400 mt-0.5"></p>
            </div>
        </div>

        <!-- Ledger Table -->
        <div class="flex-1 overflow-y-auto px-6 py-4" id="ledgerTableWrap">
            <div class="flex justify-center py-10">
                <div class="animate-spin rounded-full h-8 w-8 border-b-2 border-violet-600"></div>
            </div>
        </div>

    </div>
</div>




<script>
// Currency symbol from school settings
const CURRENCY = <?= json_encode($currencySymbol) ?>;

// ─── Reliable notify helper (works even before layout scripts load) ───────────
function notify(msg, type) {
    if (typeof window.showToast === 'function') {
        window.showToast(msg, type || 'info');
    } else {
        alert(msg);
    }
}

// ─── Tab Switching ────────────────────────────────────────────────────────────
const tabs    = document.querySelectorAll('.rf-tab');
const panels  = document.querySelectorAll('.rf-panel');

function switchTab(name) {
    tabs.forEach(t => {
        const active = t.dataset.tab === name;
        t.classList.toggle('border-indigo-500', active);
        t.classList.toggle('text-indigo-600',   active);
        t.classList.toggle('font-semibold',     active);
        t.classList.toggle('border-transparent', !active);
        t.classList.toggle('text-gray-500',     !active);
        t.classList.toggle('font-medium',       !active);
    });
    panels.forEach(p => p.classList.toggle('hidden', p.id !== 'tab-' + name));
}

tabs.forEach(t => t.addEventListener('click', () => switchTab(t.dataset.tab)));

// ─── Modal helpers ────────────────────────────────────────────────────────────
function openModal(id)  { document.getElementById(id).classList.remove('hidden'); }
function closeModal(id) { document.getElementById(id).classList.add('hidden'); }

// ─── Generic fetch wrapper ────────────────────────────────────────────────────
function doFetch(url, method, body, onSuccess) {
    const opts = { method, headers: { 'X-Requested-With': 'XMLHttpRequest' } };
    if (body) { opts.body = body; }
    fetch(url, opts)
        .then(r => {
            if (!r.ok) {
                return r.text().then(txt => {
                    console.error('Server error ' + r.status + ':', txt);
                    throw new Error('Server returned ' + r.status);
                });
            }
            return r.json();
        })
        .then(data => {
            console.log('Response:', data);
            if (data.success) {
                notify(data.message || 'Done', 'success');
                onSuccess(data);
            } else {
                notify(data.error || 'An error occurred.', 'error');
            }
        })
        .catch(err => {
            console.error('Fetch error:', err);
            notify('Request failed: ' + err.message, 'error');
        });
}

// ─── Create Fee ───────────────────────────────────────────────────────────────
document.getElementById('openCreateFeeModal').addEventListener('click', () => openModal('createFeeModal'));
document.getElementById('cancelCreateFee').addEventListener('click',    () => closeModal('createFeeModal'));
document.getElementById('createFeeOverlay').addEventListener('click',   () => closeModal('createFeeModal'));

document.getElementById('submitCreateFee').addEventListener('click', () => {
    const name   = document.getElementById('cf_name').value.trim();
    const amount = document.getElementById('cf_amount').value.trim();
    if (!name) { notify('Please enter a service name.', 'error'); return; }
    if (!amount || parseFloat(amount) <= 0) { notify('Please enter a valid amount greater than 0.', 'error'); return; }

    const fd = new FormData();
    fd.append('name',            name);
    fd.append('description',     document.getElementById('cf_description').value);
    fd.append('amount_per_unit', amount);
    fd.append('billing_cycle',   document.getElementById('cf_cycle').value);
    fd.append('scope',           document.getElementById('cf_scope').value);

    const btn = document.getElementById('submitCreateFee');
    btn.disabled = true;
    btn.textContent = 'Creating...';

    doFetch('/finance/recurring-fees', 'POST', fd, () => location.reload());
    setTimeout(() => { btn.disabled = false; btn.textContent = 'Create Fee Service'; }, 5000);
});

// ─── Toggle (Activate/Deactivate) ─────────────────────────────────────────────
function toggleFee(id, currentlyActive) {
    const msg = currentlyActive
        ? 'Disable this fee? Students will still have existing entries.'
        : 'Enable this fee?';
    if (!confirm(msg)) return;
    const fd = new FormData();
    doFetch(`/finance/recurring-fees/${id}/toggle`, 'POST', fd, () => location.reload());
}

// ─── Enroll Modal ─────────────────────────────────────────────────────────────
let selectedStudentIds = [];

function openEnrollModal(feeId, feeName) {
    document.getElementById('enroll_fee_id').value = feeId;
    document.getElementById('enrollModalSubtitle').textContent = 'Enrolling students into: ' + feeName;
    selectedStudentIds = [];
    document.getElementById('selectedStudentsList').innerHTML = '';
    document.getElementById('enroll_student_ids_json').value = '[]';
    document.getElementById('studentSearchInput').value = '';
    document.getElementById('studentSearchResults').innerHTML = 'Type to search students...';
    document.getElementById('enroll_type').value = 'individual';
    updateEnrollTypeUI();
    openModal('enrollModal');
}

document.getElementById('cancelEnroll').addEventListener('click',  () => closeModal('enrollModal'));
document.getElementById('enrollOverlay').addEventListener('click', () => closeModal('enrollModal'));
document.getElementById('enroll_type').addEventListener('change', updateEnrollTypeUI);

function updateEnrollTypeUI() {
    const type = document.getElementById('enroll_type').value;
    document.getElementById('enroll_class_row').classList.toggle('hidden',    type !== 'class');
    document.getElementById('enroll_students_row').classList.toggle('hidden', type !== 'individual');
}

let searchTimer = null;
document.getElementById('studentSearchInput').addEventListener('input', function() {
    clearTimeout(searchTimer);
    const q = this.value.trim();
    const results = document.getElementById('studentSearchResults');
    if (q.length < 2) {
        results.innerHTML = '<p class="text-gray-400 p-2 text-xs">Type at least 2 characters to search...</p>';
        return;
    }
    results.innerHTML = '<p class="text-gray-400 p-2 text-xs animate-pulse">Searching...</p>';
    searchTimer = setTimeout(() => {
        fetch('/finance/recurring-fees/students/search?q=' + encodeURIComponent(q), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(students => {
            if (!Array.isArray(students) || !students.length) {
                results.innerHTML = '<p class="text-gray-400 p-2 text-xs">No students found matching "' + q + '".</p>';
                return;
            }
            results.innerHTML = students.map(s => `
                <div class="flex items-center justify-between py-2 px-2 hover:bg-indigo-50 rounded-lg cursor-pointer border-b border-gray-100 last:border-0"
                     onclick='addStudent(${s.id}, ${JSON.stringify(s.first_name + " " + s.last_name + " (" + s.admission_no + ")")})'>
                    <div>
                        <span class="font-medium text-sm text-gray-800">${s.first_name} ${s.last_name}</span>
                        <span class="ml-2 text-xs text-gray-400">${s.admission_no}</span>
                        ${s.class_name ? `<span class="ml-1 text-xs text-indigo-400">&bull; ${s.class_name}</span>` : ''}
                    </div>
                    <span class="text-indigo-500 text-xs font-medium">+ Add</span>
                </div>`).join('');
        })
        .catch(() => {
            results.innerHTML = '<p class="text-red-400 p-2 text-xs">Search failed. Please try again.</p>';
        });
    }, 250);
});


function addStudent(id, label) {
    if (selectedStudentIds.includes(id)) return;
    selectedStudentIds.push(id);
    document.getElementById('enroll_student_ids_json').value = JSON.stringify(selectedStudentIds);
    const badge = document.createElement('span');
    badge.className = 'inline-flex items-center px-2 py-0.5 rounded-full text-xs bg-indigo-100 text-indigo-800 gap-1';
    badge.innerHTML = label + `<button type="button" onclick="removeStudent(${id}, this)" class="text-indigo-400 hover:text-indigo-700">&times;</button>`;
    document.getElementById('selectedStudentsList').appendChild(badge);
}

function removeStudent(id, btn) {
    selectedStudentIds = selectedStudentIds.filter(s => s !== id);
    document.getElementById('enroll_student_ids_json').value = JSON.stringify(selectedStudentIds);
    btn.closest('span').remove();
}

document.getElementById('submitEnroll').addEventListener('click', () => {
    const feeId = document.getElementById('enroll_fee_id').value;
    const type  = document.getElementById('enroll_type').value;
    const fd    = new FormData();
    fd.append('enroll_type',   type);
    fd.append('billing_cycle', document.getElementById('enroll_billing_cycle').value);
    fd.append('start_date',    document.getElementById('enroll_start_date').value);

    if (type === 'class') {
        const classId = document.getElementById('enroll_class_id').value;
        if (!classId) { notify('Please select a class.', 'error'); return; }
        fd.append('class_id', classId);
    } else if (type === 'individual') {
        if (!selectedStudentIds.length) { notify('Please select at least one student.', 'error'); return; }
        selectedStudentIds.forEach(id => fd.append('student_ids[]', id));
    }
    doFetch(`/finance/recurring-fees/${feeId}/enroll`, 'POST', fd, () => closeModal('enrollModal'));
});

// ─── Generate Bills Modal ─────────────────────────────────────────────────────
function openGenerateModal(feeId, fee) {
    document.getElementById('generate_fee_id').value = feeId;
    document.getElementById('generateModalSubtitle').textContent = 'Generating bills for: ' + fee.name;
    document.getElementById('gen_amount').placeholder = 'Default: ' + CURRENCY + parseFloat(fee.amount_per_unit).toFixed(2);
    document.getElementById('gen_amount').value = '';
    openModal('generateModal');
}

document.getElementById('cancelGenerate').addEventListener('click',   () => closeModal('generateModal'));
document.getElementById('generateOverlay').addEventListener('click',  () => closeModal('generateModal'));

document.getElementById('submitGenerate').addEventListener('click', () => {
    const feeId = document.getElementById('generate_fee_id').value;
    const from  = document.getElementById('gen_from_date').value;
    const to    = document.getElementById('gen_to_date').value;
    if (!from || !to) { notify('Please select a date range.', 'error'); return; }
    const fd = new FormData();
    fd.append('from_date', from);
    fd.append('to_date',   to);
    const customAmt = document.getElementById('gen_amount').value;
    if (customAmt) fd.append('amount', customAmt);

    const btn = document.getElementById('submitGenerate');
    btn.disabled = true; btn.textContent = 'Generating...';
    doFetch(`/finance/recurring-fees/${feeId}/generate`, 'POST', fd, (data) => {
        closeModal('generateModal');
        btn.disabled = false; btn.textContent = 'Generate Bills';
    });
    setTimeout(() => { btn.disabled = false; btn.textContent = 'Generate Bills'; }, 15000);
});

// ─── Bills Tab ────────────────────────────────────────────────────────────────
function openBillsTab(feeId, feeName) {
    document.getElementById('billFeeSelect').value = feeId;
    switchTab('bills');
    loadBills();
}

document.getElementById('loadBillsBtn').addEventListener('click', loadBills);

function loadBills() {
    const feeId  = document.getElementById('billFeeSelect').value;
    if (!feeId) { notify('Please select a fee.', 'error'); return; }
    const from   = document.getElementById('billFromDate').value;
    const to     = document.getElementById('billToDate').value;
    const status = document.getElementById('billStatusFilter').value;

    const url = `/finance/recurring-fees/${feeId}/entries?from_date=${from}&to_date=${to}&status=${status}`;
    document.getElementById('billsTableWrap').innerHTML = '<div class="flex justify-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div></div>';

    fetch(url, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            if (!data.success) { notify('Could not load bills.', 'error'); return; }
            renderBillsTable(data.entries);
            renderSummary(data.summary);
        })
        .catch(err => notify('Failed to load bills: ' + err.message, 'error'));
}

function renderSummary(summary) {
    const cards = document.getElementById('billSummaryCards');
    let pending = 0, paid = 0, waived = 0, waivedDays = 0;
    summary.forEach(r => {
        pending    += parseFloat(r.pending_amount || 0);
        paid       += parseFloat(r.paid_amount    || 0);
        waived     += parseFloat(r.waived_amount  || 0);
        waivedDays += parseInt(r.waived_days      || 0);
    });
    document.getElementById('summPending').textContent     = pending.toFixed(2);
    document.getElementById('summPaid').textContent        = paid.toFixed(2);
    document.getElementById('summWaived').textContent      = waived.toFixed(2);
    document.getElementById('summWaivedDays').textContent  = waivedDays;
    cards.classList.remove('hidden');
}

function renderBillsTable(entries) {
    const wrap = document.getElementById('billsTableWrap');
    if (!entries.length) {
        wrap.innerHTML = '<div class="text-center py-8 text-gray-400 text-sm">No entries found for the selected filters.</div>';
        return;
    }
    const statusBadge = s => {
        if (s === 'pending') return '<span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">Pending</span>';
        if (s === 'paid')    return '<span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">Paid</span>';
        return '<span class="inline-flex px-2 py-0.5 rounded-full text-xs font-medium bg-gray-100 text-gray-600">Waived</span>';
    };
    const rows = entries.map(e => `
        <tr class="hover:bg-gray-50 ${e.status === 'waived' ? 'opacity-60' : ''}">
            <td class="px-4 py-2 text-sm text-gray-900">${e.first_name} ${e.last_name}</td>
            <td class="px-4 py-2 text-xs text-gray-500">${e.admission_no}</td>
            <td class="px-4 py-2 text-xs text-gray-500">${e.class_name || '—'}</td>
            <td class="px-4 py-2 text-sm text-gray-700">${e.service_date}</td>
            <td class="px-4 py-2 text-sm font-semibold text-gray-900">${CURRENCY}${parseFloat(e.amount).toFixed(2)}</td>
            <td class="px-4 py-2">${statusBadge(e.status)}</td>
            <td class="px-4 py-2 text-xs text-gray-400 max-w-xs truncate" title="${e.waive_reason || ''}">${e.waive_reason || '—'}</td>
            <td class="px-4 py-2 text-right">
                ${e.status === 'pending'
                    ? `<button onclick="openWaiveModal(${e.id}, '${e.first_name} ${e.last_name}', '${e.service_date}')"
                           class="text-xs px-3 py-1 rounded-lg bg-orange-50 text-orange-600 hover:bg-orange-100 font-medium transition-colors">Waive</button>`
                    : e.status === 'waived'
                    ? `<button onclick="unwaive(${e.id})"
                           class="text-xs px-3 py-1 rounded-lg bg-gray-100 text-gray-600 hover:bg-gray-200 font-medium transition-colors">Restore</button>`
                    : ''}
            </td>
        </tr>`).join('');

    wrap.innerHTML = `
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50"><tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Adm. No</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Status</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Waive Reason</th>
                    <th class="px-4 py-2 text-right text-xs font-medium text-gray-500 uppercase">Action</th>
                </tr></thead>
                <tbody class="bg-white divide-y divide-gray-100">${rows}</tbody>
            </table>
        </div>`;
}

// ─── Load Enrollments Tab ─────────────────────────────────────────────────────
document.getElementById('loadEnrollmentsBtn').addEventListener('click', () => {
    const feeId = document.getElementById('enrollFeeSelect').value;
    if (!feeId) { notify('Please select a fee.', 'error'); return; }
    const wrap = document.getElementById('enrollmentsTableWrap');
    wrap.innerHTML = '<div class="flex justify-center py-8"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-indigo-600"></div></div>';
    fetch(`/finance/recurring-fees/${feeId}`, { headers: { 'X-Requested-With': 'XMLHttpRequest' } })
        .then(r => r.json())
        .then(data => {
            if (!data.success || !data.enrollments.length) {
                wrap.innerHTML = '<div class="text-center py-6 text-gray-400 text-sm">No enrollments found for this fee.</div>';
                return;
            }
            const cycleBadge = c => `<span class="px-2 py-0.5 rounded-full text-xs font-medium bg-blue-100 text-blue-700 capitalize">${c}</span>`;
            const rows = data.enrollments.map(e => `
                <tr class="hover:bg-gray-50">
                    <td class="px-4 py-2 text-sm text-gray-900">${e.first_name} ${e.last_name}</td>
                    <td class="px-4 py-2 text-xs text-gray-500">${e.admission_no}</td>
                    <td class="px-4 py-2 text-xs text-gray-500">${e.class_name || '—'}</td>
                    <td class="px-4 py-2">${cycleBadge(e.billing_cycle)}</td>
                    <td class="px-4 py-2 text-xs text-gray-500">${e.start_date}</td>
                </tr>`).join('');
            wrap.innerHTML = `<div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200 text-sm">
                <thead class="bg-gray-50"><tr>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Adm. No</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Class</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Cycle</th>
                    <th class="px-4 py-2 text-left text-xs font-medium text-gray-500 uppercase">Since</th>
                </tr></thead>
                <tbody class="bg-white divide-y divide-gray-100">${rows}</tbody>
            </table></div>`;
        })
        .catch(err => notify('Failed to load enrollments: ' + err.message, 'error'));
});

// ─── Waive Modal ──────────────────────────────────────────────────────────────
function openWaiveModal(entryId, studentName, date) {
    document.getElementById('waive_entry_id').value = entryId;
    document.getElementById('waiveModalSubtitle').textContent = `Waive ${date} for ${studentName}`;
    document.getElementById('waive_reason').value = '';
    openModal('waiveModal');
}
document.getElementById('cancelWaive').addEventListener('click',  () => closeModal('waiveModal'));
document.getElementById('waiveOverlay').addEventListener('click', () => closeModal('waiveModal'));

document.getElementById('submitWaive').addEventListener('click', () => {
    const entryId = document.getElementById('waive_entry_id').value;
    const reason  = document.getElementById('waive_reason').value.trim();
    if (!reason) { notify('Please enter a reason for the waiver.', 'error'); return; }
    const fd = new FormData();
    fd.append('entry_id', entryId);
    fd.append('reason',   reason);
    doFetch('/finance/recurring-fees/waive', 'POST', fd, () => {
        closeModal('waiveModal');
        loadBills();
    });
});

function unwaive(entryId) {
    if (!confirm('Restore this entry to pending status?')) return;
    const fd = new FormData();
    fd.append('entry_id', entryId);
    doFetch('/finance/recurring-fees/unwaive', 'POST', fd, () => loadBills());
}

// ─── PAY TAB ─────────────────────────────────────────────────────────────────
let paySearchTimer = null;
let paySelectedStudentId = null;
let payActiveFeeId = null;

document.getElementById('payDate').value = new Date().toISOString().split('T')[0];

// ── Helper: reset the bills + form panels ────────────────────────────────────
function resetPayBillsArea() {
    ['payBillsSummary','payFormPanel','payStudentFeesPanel'].forEach(id => {
        document.getElementById(id).classList.add('hidden');
    });
}

// ── Helper: set CURRENCY spans ───────────────────────────────────────────────
function setCurrencySpans() {
    document.querySelectorAll('.pay-currency').forEach(el => el.textContent = CURRENCY);
}

// ── FLOW 1: Fee selected → load enrolled students list ───────────────────────
function loadEnrolledStudentsForFee(feeId) {
    const panel = document.getElementById('payEnrolledPanel');
    const list  = document.getElementById('payEnrolledList');
    const load  = document.getElementById('payEnrolledLoading');
    const count = document.getElementById('payEnrolledCount');
    panel.classList.remove('hidden');
    load.classList.remove('hidden');
    list.classList.add('hidden');
    resetPayBillsArea();
    clearPayStudent();

    fetch(`/finance/recurring-fees/${feeId}/enrolled-students-pay`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        load.classList.add('hidden');
        if (!data.success || !data.students.length) {
            list.classList.remove('hidden');
            list.innerHTML = '<p class="text-gray-400 text-xs p-3">No students enrolled in this fee.</p>';
            count.textContent = '(0)';
            return;
        }
        count.textContent = `(${data.students.length})`;
        list.innerHTML = data.students.map(s => `
            <div class="flex items-center justify-between px-3 py-2 hover:bg-indigo-50 cursor-pointer transition-colors rounded-lg"
                 onclick='selectPayStudentFromList(${s.id}, "${s.first_name} ${s.last_name}", "${s.admission_no}", "${s.class_name || ""}")'>
                <div>
                    <span class="font-medium text-sm text-gray-800">${s.first_name} ${s.last_name}</span>
                    <span class="block text-xs text-gray-400">${s.admission_no}${s.class_name ? ' · ' + s.class_name : ''}</span>
                </div>
                <div class="text-right shrink-0 ml-2">
                    ${parseFloat(s.pending_total) > 0
                        ? `<span class="text-xs font-bold text-red-600">${CURRENCY}${parseFloat(s.pending_total).toFixed(2)}</span>
                           <span class="block text-xs text-gray-400">${s.pending_count} day${s.pending_count !== '1' ? 's' : ''}</span>`
                        : `<span class="text-xs text-green-500 font-medium">Paid up</span>`}
                </div>
            </div>`).join('');
        list.classList.remove('hidden');
    })
    .catch(() => { load.classList.add('hidden'); notify('Failed to load students.', 'error'); });
}

// Clicking a student from the fee's enrolled list
function selectPayStudentFromList(id, name, admNo, className) {
    paySelectedStudentId = id;
    payActiveFeeId = document.getElementById('payFeeSelect').value;
    document.getElementById('payStudentId').value    = id;
    document.getElementById('payStudentName').textContent  = name;
    document.getElementById('payStudentAdm').textContent   = admNo;
    document.getElementById('payStudentClass').textContent = className ? '· ' + className : '';
    document.getElementById('paySelectedStudent').classList.remove('hidden');
    document.getElementById('payStudentSearch').value = '';
    document.getElementById('payStudentResults').classList.add('hidden');
    document.getElementById('payStudentFeesPanel').classList.add('hidden');
    loadPayStudentBills();
    loadPayHistory();
}

// ── Fee select change handler ────────────────────────────────────────────────
document.getElementById('payFeeSelect').addEventListener('change', function () {
    payActiveFeeId = this.value;
    if (payActiveFeeId) {
        loadEnrolledStudentsForFee(payActiveFeeId);
        loadPayHistory();
    } else {
        document.getElementById('payEnrolledPanel').classList.add('hidden');
        resetPayBillsArea();
    }
});

// ── FLOW 2: Student search ───────────────────────────────────────────────────
document.getElementById('payStudentSearch').addEventListener('input', function () {
    clearTimeout(paySearchTimer);
    const q = this.value.trim();
    const resultsEl = document.getElementById('payStudentResults');
    if (q.length < 2) { resultsEl.classList.add('hidden'); return; }
    paySearchTimer = setTimeout(() => {
        fetch('/finance/recurring-fees/students/search?q=' + encodeURIComponent(q), {
            headers: { 'X-Requested-With': 'XMLHttpRequest' }
        })
        .then(r => r.json())
        .then(students => {
            if (!Array.isArray(students) || !students.length) {
                resultsEl.innerHTML = '<p class="text-gray-400 p-2 text-xs">No students found.</p>';
                resultsEl.classList.remove('hidden'); return;
            }
            resultsEl.innerHTML = students.map(s => `
                <div class="flex items-center justify-between py-2 px-3 hover:bg-indigo-50 cursor-pointer border-b border-gray-100 last:border-0"
                     onclick='selectPayStudent(${s.id}, "${s.first_name} ${s.last_name}", "${s.admission_no}", "${s.class_name || ""}")'>
                    <div>
                        <span class="font-medium text-sm text-gray-800">${s.first_name} ${s.last_name}</span>
                        <span class="ml-2 text-xs text-gray-400">${s.admission_no}</span>
                        ${s.class_name ? `<span class="ml-1 text-xs text-indigo-400">&bull; ${s.class_name}</span>` : ''}
                    </div>
                    <span class="text-indigo-500 text-xs font-medium">Select</span>
                </div>`).join('');
            resultsEl.classList.remove('hidden');
        })
        .catch(() => { resultsEl.innerHTML = '<p class="text-red-400 p-2 text-xs">Search failed.</p>'; resultsEl.classList.remove('hidden'); });
    }, 250);
});

function selectPayStudent(id, name, admNo, className) {
    paySelectedStudentId = id;
    document.getElementById('payStudentId').value = id;
    document.getElementById('payStudentSearch').value = '';
    document.getElementById('payStudentResults').classList.add('hidden');
    document.getElementById('payStudentName').textContent  = name;
    document.getElementById('payStudentAdm').textContent   = admNo;
    document.getElementById('payStudentClass').textContent = className ? '· ' + className : '';
    document.getElementById('paySelectedStudent').classList.remove('hidden');

    const feeId = document.getElementById('payFeeSelect').value;
    if (feeId) {
        // Fee already selected → go straight to bills
        payActiveFeeId = feeId;
        loadPayStudentBills();
        loadPayHistory();
    } else {
        // No fee selected → show which recurring fees this student has pending bills for
        loadStudentFees(id);
    }
}

function loadStudentFees(studentId) {
    const panel = document.getElementById('payStudentFeesPanel');
    const list  = document.getElementById('payStudentFeesList');
    panel.classList.remove('hidden');
    list.innerHTML = '<p class="text-gray-400 text-xs py-2">Loading fees…</p>';
    resetPayBillsArea(); panel.classList.remove('hidden'); // re-show after reset

    fetch('/finance/recurring-fees/student-fees?student_id=' + studentId, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success || !data.fees.length) {
            list.innerHTML = '<p class="text-green-600 text-xs py-2">✅ No outstanding recurring fees for this student.</p>';
            return;
        }
        list.innerHTML = data.fees.map(f => `
            <button onclick="selectFeeFromStudent(${f.id})"
                class="w-full text-left px-3 py-2 rounded-lg border border-gray-200 hover:border-indigo-400 hover:bg-indigo-50 transition-colors">
                <div class="flex justify-between items-center">
                    <span class="font-medium text-sm text-gray-800">${f.name}</span>
                    <span class="text-xs font-bold text-red-600 shrink-0 ml-2">${CURRENCY}${parseFloat(f.pending_total).toFixed(2)}</span>
                </div>
                <span class="text-xs text-gray-400">${f.billing_cycle} · ${f.pending_count} day${f.pending_count !== '1' ? 's' : ''} pending</span>
            </button>`).join('');
    })
    .catch(() => { list.innerHTML = '<p class="text-red-400 text-xs py-2">Failed to load fees.</p>'; });
}

function selectFeeFromStudent(feeId) {
    payActiveFeeId = feeId;
    // Update fee dropdown to reflect this
    document.getElementById('payFeeSelect').value = feeId;
    // Hide fee picker, load bills
    document.getElementById('payStudentFeesPanel').classList.add('hidden');
    loadPayStudentBills();
    loadEnrolledStudentsForFee(feeId);
    loadPayHistory();
}

function clearPayStudent() {
    paySelectedStudentId = null;
    document.getElementById('payStudentId').value = '';
    document.getElementById('payStudentSearch').value = '';
    document.getElementById('paySelectedStudent').classList.add('hidden');
    resetPayBillsArea();
}

// ── Load pending bills for selected student + fee ────────────────────────────
function loadPayStudentBills() {
    const feeId     = payActiveFeeId || document.getElementById('payFeeSelect').value;
    const studentId = document.getElementById('payStudentId').value;
    if (!feeId || !studentId) return;

    const summaryEl = document.getElementById('payBillsSummary');
    summaryEl.classList.remove('hidden');
    document.getElementById('payBillsLoading').classList.remove('hidden');
    document.getElementById('payBillsContent').classList.add('hidden');
    document.getElementById('payNoBills').classList.add('hidden');
    document.getElementById('payFormPanel').classList.add('hidden');

    fetch(`/finance/recurring-fees/${feeId}/student-bills?student_id=${studentId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        document.getElementById('payBillsLoading').classList.add('hidden');
        if (!data.success) { notify(data.error || 'Could not load bills.', 'error'); return; }
        setCurrencySpans();

        if (!data.pending.length) {
            document.getElementById('payNoBills').classList.remove('hidden');
            return;
        }
        document.getElementById('payPendingCount').textContent = data.pending.length;
        document.getElementById('payPendingTotal').textContent = parseFloat(data.total_pending).toFixed(2);
        document.getElementById('payPendingList').innerHTML = data.pending.map(e => `
            <div class="flex justify-between items-center px-3 py-1.5">
                <span class="text-gray-600">${e.service_date}</span>
                <span class="font-semibold text-gray-800">${CURRENCY}${parseFloat(e.amount).toFixed(2)}</span>
            </div>`).join('');
        document.getElementById('payBillsContent').classList.remove('hidden');
        document.getElementById('payAmount').value = parseFloat(data.total_pending).toFixed(2);
        document.getElementById('payFormPanel').classList.remove('hidden');
    })
    .catch(err => {
        document.getElementById('payBillsLoading').classList.add('hidden');
        notify('Failed to load bills: ' + err.message, 'error');
    });
}

// ── Record Payment ───────────────────────────────────────────────────────────
document.getElementById('submitPayment').addEventListener('click', () => {
    const feeId     = payActiveFeeId || document.getElementById('payFeeSelect').value;
    const studentId = document.getElementById('payStudentId').value;
    const amount    = parseFloat(document.getElementById('payAmount').value);
    const date      = document.getElementById('payDate').value;

    if (!feeId)     { notify('Please select a fee service.', 'error'); return; }
    if (!studentId) { notify('Please select a student.', 'error'); return; }
    if (!amount || amount <= 0) { notify('Please enter a valid payment amount.', 'error'); return; }
    if (!date)      { notify('Please enter a payment date.', 'error'); return; }

    const fd = new FormData();
    fd.append('student_id',     studentId);
    fd.append('amount_paid',    amount);
    fd.append('payment_date',   date);
    fd.append('payment_method', document.getElementById('payMethod').value);
    fd.append('reference_no',   document.getElementById('payRef').value);
    fd.append('notes',          document.getElementById('payNotes').value);

    const btn = document.getElementById('submitPayment');
    btn.disabled = true; btn.textContent = 'Recording…';

    doFetch(`/finance/recurring-fees/${feeId}/pay`, 'POST', fd, () => {
        document.getElementById('payRef').value   = '';
        document.getElementById('payNotes').value = '';
        loadPayStudentBills();
        if (feeId) loadEnrolledStudentsForFee(feeId);
        loadPayHistory();
        btn.disabled = false; btn.textContent = '💾 Record Payment';
    });
    setTimeout(() => { btn.disabled = false; btn.textContent = '💾 Record Payment'; }, 10000);
});

// ── Payment History ──────────────────────────────────────────────────────────
function loadPayHistory() {
    const feeId = payActiveFeeId || document.getElementById('payFeeSelect').value;
    if (!feeId) return;
    const wrap = document.getElementById('payHistoryWrap');
    wrap.innerHTML = '<div class="flex justify-center py-5"><div class="animate-spin rounded-full h-6 w-6 border-b-2 border-indigo-600"></div></div>';
    fetch(`/finance/recurring-fees/${feeId}/payments`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success || !data.payments.length) {
            wrap.innerHTML = '<p class="text-gray-400 text-xs text-center py-5">No payments recorded yet.</p>';
            document.getElementById('payHistoryTotal').classList.add('hidden'); return;
        }
        const ml = m => ({cash:'Cash',bank_transfer:'Bank Transfer',cheque:'Cheque',mobile_money:'Mobile Money'}[m]||m);
        const rows = data.payments.map(p => `
            <tr class="hover:bg-gray-50">
                <td class="px-2 py-1.5 text-xs font-medium text-gray-800">${p.first_name} ${p.last_name}</td>
                <td class="px-2 py-1.5 text-xs text-gray-400">${p.admission_no}</td>
                <td class="px-2 py-1.5 text-xs text-gray-600">${p.payment_date}</td>
                <td class="px-2 py-1.5 text-xs font-bold text-green-700">${CURRENCY}${parseFloat(p.amount_paid).toFixed(2)}</td>
                <td class="px-2 py-1.5 text-xs text-gray-500">${ml(p.payment_method)}</td>
                <td class="px-2 py-1.5 text-xs text-gray-400">${p.reference_no||'—'}</td>
            </tr>`).join('');
        wrap.innerHTML = `<div class="overflow-x-auto"><table class="min-w-full divide-y divide-gray-200">
            <thead class="bg-gray-50"><tr>
                <th class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase">Student</th>
                <th class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase">Adm.</th>
                <th class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase">Date</th>
                <th class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase">Amount</th>
                <th class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase">Method</th>
                <th class="px-2 py-1.5 text-left text-xs font-medium text-gray-500 uppercase">Ref.</th>
            </tr></thead>
            <tbody class="bg-white divide-y divide-gray-100">${rows}</tbody>
        </table></div>`;
        document.getElementById('payHistoryTotalAmt').textContent = CURRENCY + parseFloat(data.total_paid).toFixed(2);
        document.getElementById('payHistoryTotal').classList.remove('hidden');
    })
    .catch(() => { wrap.innerHTML = '<p class="text-red-400 text-xs text-center py-4">Failed to load.</p>'; });
}

document.getElementById('loadPayHistoryBtn').addEventListener('click', () => {
    const feeId = payActiveFeeId || document.getElementById('payFeeSelect').value;
    if (!feeId) { notify('Please select a fee service first.', 'error'); return; }
    loadPayHistory();
});

// ── LEDGER MODAL ─────────────────────────────────────────────────────────────

let ledgerData = null; // cache for print

function openLedger() {
    const feeId     = payActiveFeeId || document.getElementById('payFeeSelect').value;
    const studentId = document.getElementById('payStudentId').value;
    if (!feeId)     { notify('Please select a fee service.', 'error'); return; }
    if (!studentId) { notify('Please select a student.', 'error'); return; }

    const modal = document.getElementById('ledgerModal');
    modal.classList.remove('hidden');
    document.body.style.overflow = 'hidden';

    // Show loading spinner
    document.getElementById('ledgerTableWrap').innerHTML =
        '<div class="flex justify-center py-10"><div class="animate-spin rounded-full h-8 w-8 border-b-2 border-violet-600"></div></div>';
    ['lsBilled','lsWaived','lsPaid','lsOutstanding','lsTotalDays','lsWaivedDays','lsPaidDays','lsPendingDays','ledgerSubtitle']
        .forEach(id => { const el = document.getElementById(id); if(el) el.textContent = '…'; });

    fetch(`/finance/recurring-fees/${feeId}/ledger?student_id=${studentId}`, {
        headers: { 'X-Requested-With': 'XMLHttpRequest' }
    })
    .then(r => r.json())
    .then(data => {
        if (!data.success) { notify(data.error || 'Could not load ledger.', 'error'); closeLedger(); return; }
        ledgerData = data;
        renderLedger(data);
    })
    .catch(err => {
        document.getElementById('ledgerTableWrap').innerHTML =
            '<p class="text-red-400 text-sm text-center py-8">Failed to load ledger.</p>';
    });
}

function renderLedger(data) {
    const s   = data.summary;
    const fmt = v => CURRENCY + parseFloat(v).toFixed(2);
    const d   = n => `${n} day${n !== 1 && n !== '1' ? 's' : ''}`;

    // Subtitle
    document.getElementById('ledgerSubtitle').textContent =
        `${data.student.first_name} ${data.student.last_name} (${data.student.admission_no})`
        + (data.student.class_name ? ` • ${data.student.class_name}` : '')
        + ` — ${data.fee.name}`;

    // Summary cards
    document.getElementById('lsBilled').textContent      = fmt(s.total_billed);
    document.getElementById('lsTotalDays').textContent   = d(s.total_days) + ' total';
    document.getElementById('lsWaived').textContent      = fmt(s.total_waived);
    document.getElementById('lsWaivedDays').textContent  = d(s.waived_days) + ' waived';
    document.getElementById('lsPaid').textContent        = fmt(s.total_paid);
    document.getElementById('lsPaidDays').textContent    = d(s.paid_days) + ' settled';
    document.getElementById('lsOutstanding').textContent = fmt(s.outstanding);
    document.getElementById('lsPendingDays').textContent = d(s.pending_days) + ' pending';

    // Row colours by type
    const rowStyle = {
        bill:    'bg-white hover:bg-gray-50',
        waiver:  'bg-amber-50 hover:bg-amber-100',
        payment: 'bg-green-50 hover:bg-green-100',
    };
    const typeLabel = {
        bill:    '<span class="px-2 py-0.5 rounded-full bg-blue-100 text-blue-700 text-xs font-medium">Bill</span>',
        waiver:  '<span class="px-2 py-0.5 rounded-full bg-amber-100 text-amber-700 text-xs font-medium">Waiver</span>',
        payment: '<span class="px-2 py-0.5 rounded-full bg-green-100 text-green-700 text-xs font-medium">Payment</span>',
    };
    const statusLabel = {
        pending: '<span class="text-red-500 font-medium">Pending</span>',
        paid:    '<span class="text-green-600 font-medium">Paid</span>',
        waived:  '<span class="text-amber-600 font-medium">Waived</span>',
        payment: '',
    };

    if (!data.rows.length) {
        document.getElementById('ledgerTableWrap').innerHTML =
            '<p class="text-gray-400 text-sm text-center py-10">No ledger entries found.</p>';
        return;
    }

    const rows = data.rows.map((r, i) => `
        <tr class="${rowStyle[r.type] || 'bg-white'} border-b border-gray-100">
            <td class="px-3 py-2 text-xs text-gray-500 whitespace-nowrap">${r.date}</td>
            <td class="px-3 py-2">${typeLabel[r.type] || r.type}</td>
            <td class="px-3 py-2 text-xs text-gray-700">${r.detail}</td>
            <td class="px-3 py-2 text-xs text-right font-medium text-gray-800">${r.billed  ? fmt(r.billed)  : '—'}</td>
            <td class="px-3 py-2 text-xs text-right font-medium text-amber-600">${r.waived  ? fmt(r.waived)  : '—'}</td>
            <td class="px-3 py-2 text-xs text-right font-medium text-green-700">${r.paid_in ? fmt(r.paid_in) : '—'}</td>
            <td class="px-3 py-2 text-xs text-right font-bold ${parseFloat(r.balance) > 0 ? 'text-red-600' : parseFloat(r.balance) < 0 ? 'text-green-700' : 'text-gray-400'}">${fmt(r.balance)}</td>
            <td class="px-3 py-2 text-xs">${statusLabel[r.status] || r.status}</td>
        </tr>`).join('');

    document.getElementById('ledgerTableWrap').innerHTML = `
        <table class="min-w-full text-xs">
            <thead class="sticky top-0 bg-gray-100 z-10">
                <tr>
                    <th class="px-3 py-2 text-left font-semibold text-gray-600 uppercase tracking-wide">Date</th>
                    <th class="px-3 py-2 text-left font-semibold text-gray-600 uppercase tracking-wide">Type</th>
                    <th class="px-3 py-2 text-left font-semibold text-gray-600 uppercase tracking-wide">Details</th>
                    <th class="px-3 py-2 text-right font-semibold text-gray-600 uppercase tracking-wide">Billed</th>
                    <th class="px-3 py-2 text-right font-semibold text-gray-600 uppercase tracking-wide">Waived</th>
                    <th class="px-3 py-2 text-right font-semibold text-gray-600 uppercase tracking-wide">Paid</th>
                    <th class="px-3 py-2 text-right font-semibold text-gray-600 uppercase tracking-wide">Balance</th>
                    <th class="px-3 py-2 text-left font-semibold text-gray-600 uppercase tracking-wide">Status</th>
                </tr>
            </thead>
            <tbody>${rows}</tbody>
        </table>`;
}

function closeLedger() {
    document.getElementById('ledgerModal').classList.add('hidden');
    document.body.style.overflow = '';
}

// Close on overlay click
document.getElementById('ledgerModal').addEventListener('click', function(e) {
    if (e.target === this) closeLedger();
});

function printLedger() {
    if (!ledgerData) return;
    const s       = ledgerData.summary;
    const fmt     = v => CURRENCY + parseFloat(v).toFixed(2);
    const student = ledgerData.student;
    const fee     = ledgerData.fee;

    const rowBg = { bill:'#ffffff', waiver:'#fffbeb', payment:'#f0fdf4' };
    const typeLabel = { bill:'Bill', waiver:'Waiver', payment:'Payment' };

    const rows = ledgerData.rows.map(r => `
        <tr style="background:${rowBg[r.type]||'#fff'}">
            <td>${r.date}</td>
            <td>${typeLabel[r.type] || r.type}</td>
            <td>${r.detail}</td>
            <td style="text-align:right">${r.billed  ? fmt(r.billed)  : '—'}</td>
            <td style="text-align:right;color:#b45309">${r.waived  ? fmt(r.waived)  : '—'}</td>
            <td style="text-align:right;color:#15803d">${r.paid_in ? fmt(r.paid_in) : '—'}</td>
            <td style="text-align:right;font-weight:bold;color:${parseFloat(r.balance)>0?'#dc2626':parseFloat(r.balance)<0?'#15803d':'#6b7280'}">${fmt(r.balance)}</td>
            <td>${r.status.charAt(0).toUpperCase()+r.status.slice(1)}</td>
        </tr>`).join('');

    const html = `<!DOCTYPE html>
<html><head>
<meta charset="UTF-8">
<title>Ledger — ${fee.name}</title>
<style>
  body { font-family: Arial, sans-serif; font-size: 12px; color: #111; margin: 20px; }
  h2  { margin: 0 0 4px; font-size: 16px; }
  p   { margin: 0 0 3px; }
  .meta { font-size: 10px; color: #666; margin-bottom: 14px; }
  .summary { display: flex; gap: 12px; margin-bottom: 14px; }
  .card { border: 1px solid #ddd; border-radius: 6px; padding: 8px 12px; flex:1; text-align:center; }
  .card .label { font-size: 9px; text-transform: uppercase; color: #888; }
  .card .val   { font-size: 14px; font-weight: bold; margin-top: 2px; }
  table { width: 100%; border-collapse: collapse; }
  th, td { border: 1px solid #ddd; padding: 5px 8px; }
  th { background: #f3f4f6; font-size: 10px; text-transform: uppercase; }
  tfoot td { font-weight: bold; background: #eff6ff; }
  @media print { body { margin: 10px; } }
</style>
</head><body>
<h2>Account Ledger &mdash; ${fee.name}</h2>
<p><strong>${student.first_name} ${student.last_name}</strong> &nbsp;|&nbsp; ${student.admission_no}${student.class_name ? ' &nbsp;|&nbsp; '+student.class_name : ''}</p>
<p class="meta">Printed: ${new Date().toLocaleString()}</p>

<div class="summary">
  <div class="card"><div class="label">Billed</div><div class="val">${fmt(s.total_billed)}</div><div style="font-size:9px;color:#888">${s.total_days} day(s)</div></div>
  <div class="card"><div class="label">Waived</div><div class="val" style="color:#b45309">${fmt(s.total_waived)}</div><div style="font-size:9px;color:#888">${s.waived_days} day(s)</div></div>
  <div class="card"><div class="label">Paid</div><div class="val" style="color:#15803d">${fmt(s.total_paid)}</div><div style="font-size:9px;color:#888">${s.paid_days} day(s)</div></div>
  <div class="card"><div class="label">Outstanding</div><div class="val" style="color:#dc2626">${fmt(s.outstanding)}</div><div style="font-size:9px;color:#888">${s.pending_days} pending</div></div>
</div>

<table>
  <thead><tr><th>Date</th><th>Type</th><th>Details</th><th style="text-align:right">Billed</th><th style="text-align:right">Waived</th><th style="text-align:right">Paid</th><th style="text-align:right">Balance</th><th>Status</th></tr></thead>
  <tbody>${rows}</tbody>
  <tfoot><tr>
    <td colspan="3">Totals</td>
    <td style="text-align:right">${fmt(s.total_billed)}</td>
    <td style="text-align:right;color:#b45309">${fmt(s.total_waived)}</td>
    <td style="text-align:right;color:#15803d">${fmt(s.total_paid)}</td>
    <td style="text-align:right;color:${parseFloat(s.outstanding)>0?'#dc2626':'#15803d'}">${fmt(s.outstanding)}</td>
    <td></td>
  </tr></tfoot>
</table>
</body></html>`;

    const win = window.open('', '_blank', 'width=900,height=700');
    win.document.write(html);
    win.document.close();
    win.focus();
    setTimeout(() => { win.print(); }, 400);
}
</script>



<?php
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>
