<?php 
$title = 'Student Profile'; 
ob_start(); 

// Define student category labels
$studentCategoryLabels = [
    'regular_day' => 'Regular Student (Day)',
    'regular_boarding' => 'Regular Student (Boarding)',
    'international' => 'International Student'
];

// Define term labels
$termLabels = [
    'first' => 'First Term',
    'second' => 'Second Term',
    'third' => 'Third Term'
];

// Define payment status labels and colors
$paymentStatusLabels = [
    'fully_paid' => 'Fully Paid',
    'partly_paid' => 'Partly Paid',
    'pending' => 'Pending'
];

$paymentStatusColors = [
    'fully_paid' => 'bg-green-100 text-green-800',
    'partly_paid' => 'bg-yellow-100 text-yellow-800',
    'pending' => 'bg-red-100 text-red-800'
];

// Determine active tab from URL parameter, default to 'academic'
$activeTab = $_GET['tab'] ?? 'academic';
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Student Profile</h1>
            <div>
                <a href="/students/<?= $student['id'] ?>/edit" class="bg-indigo-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-indigo-700 mr-2">
                    Edit
                </a>
                <a href="/students" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                    Back to Students
                </a>
            </div>
        </div>

        <!-- Collapsible Profile Details Section -->
        <div class="bg-white shadow overflow-hidden sm:rounded-lg mb-6">
            <div class="px-4 py-5 sm:px-6 cursor-pointer" id="profile-header">
                <div class="flex items-center justify-between">
                    <div class="flex items-center">
                        <?php if (!empty($student['profile_picture'])): ?>
                            <img src="/storage/uploads/<?= htmlspecialchars($student['profile_picture']) ?>" alt="Profile Picture" class="h-12 w-12 object-cover rounded-full mr-4" onerror="this.src='/images/default-profile.png';">
                        <?php else: ?>
                            <div class="h-12 w-12 rounded-full bg-gray-200 flex items-center justify-center mr-4">
                                <span class="text-gray-500 text-xl"><?= substr($student['first_name'], 0, 1) ?></span>
                            </div>
                        <?php endif; ?>
                        <div>
                            <h3 class="text-lg leading-6 font-medium text-gray-900">
                                <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                            </h3>
                            <p class="mt-1 max-w-2xl text-sm text-gray-500">
                                Admission Number: <?= htmlspecialchars($student['admission_no']) ?>
                            </p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <svg id="expand-icon" class="h-5 w-5 text-gray-500 transform transition-transform duration-200 <?= isset($_COOKIE['profile_collapsed']) && $_COOKIE['profile_collapsed'] === 'true' ? '' : 'rotate-180' ?>" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7" />
                        </svg>
                    </div>
                </div>
            </div>
            <div id="profile-details" class="border-t border-gray-200 <?= isset($_COOKIE['profile_collapsed']) && $_COOKIE['profile_collapsed'] === 'true' ? 'hidden' : '' ?>">
                <dl>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Admission Number
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['admission_no']) ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Full Name
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Date of Birth
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['dob'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Gender
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars(ucfirst($student['gender'] ?? 'N/A')) ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Class
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['class_name'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <!-- Student Category Information -->
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Student Category
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($studentCategoryLabels[$student['student_category'] ?? 'regular_day']) ?>
                        </dd>
                    </div>
                    <?php if (!empty($student['student_category_details'])): ?>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Category Details
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['student_category_details']) ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Guardian Name
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['guardian_name'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Guardian Phone
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['guardian_phone'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Address
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['address'] ?? 'N/A') ?>
                        </dd>
                    </div>
                    <?php if (!empty($student['medical_info'])): ?>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Medical Information
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= htmlspecialchars($student['medical_info']) ?>
                        </dd>
                    </div>
                    <?php endif; ?>
                    <div class="bg-white px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Admission Date
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= !empty($student['admission_date']) ? htmlspecialchars(date('M j, Y', strtotime($student['admission_date']))) : 'N/A' ?>
                        </dd>
                    </div>
                    <div class="bg-gray-50 px-4 py-5 sm:grid sm:grid-cols-3 sm:gap-4 sm:px-6">
                        <dt class="text-sm font-medium text-gray-500">
                            Academic Year
                        </dt>
                        <dd class="mt-1 text-sm text-gray-900 sm:mt-0 sm:col-span-2">
                            <?= !empty($student['academic_year_name']) ? htmlspecialchars($student['academic_year_name']) : 'N/A' ?>
                        </dd>
                    </div>
                </dl>
            </div>
        </div>

        <!-- Tab Navigation -->
        <div class="border-b border-gray-200 mb-6">
            <nav class="-mb-px flex space-x-8" aria-label="Tabs">
                <a href="#" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm academic-tab-link <?= $activeTab === 'academic' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>" data-tab="academic">
                    Academic Information
                </a>
                <a href="#" class="whitespace-nowrap py-4 px-1 border-b-2 font-medium text-sm financial-tab-link <?= $activeTab === 'financial' ? 'border-indigo-500 text-indigo-600' : 'border-transparent text-gray-500 hover:text-gray-700 hover:border-gray-300' ?>" data-tab="financial">
                    Financial Information
                </a>
            </nav>
        </div>

        <!-- Tab Content -->
        <!-- Academic Information Tab -->
        <div id="academic-tab-content" class="bg-white shadow overflow-hidden sm:rounded-lg tab-content <?= $activeTab === 'academic' ? 'block' : 'hidden' ?>">
            <div class="px-4 py-5 sm:px-6">
                <div class="flex justify-between items-center">
                    <h3 class="text-lg leading-6 font-medium text-gray-900">Academic Information</h3>
                    <div class="flex space-x-2">
                        <button onclick="printSection('academic-records-container', 'Academic Records')" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1.5 rounded-md text-sm font-medium transition flex items-center">
                            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                            </svg>
                            Print
                        </button>
                        <button onclick="exportTableToCSV('academic-records-table', 'academic_records_<?= $student['admission_no'] ?>')" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-md text-sm font-medium transition flex items-center">
                            <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                            </svg>
                            Export
                        </button>
                    </div>
                </div>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Recorded academics of student</p>
            </div>
            <div class="border-t border-gray-200">
                <?php if (empty($academicRecords)): ?>
                <div class="px-4 py-5 sm:px-6 text-sm text-gray-500">
                    No academic records found for this student.
                </div>
                <?php else: ?>
                <div class="overflow-x-auto" id="academic-records-container">
                    <table id="academic-records-table" class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Exam
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Term
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Subject
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Marks
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Grade
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($academicRecords as $record): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($record['exam_name']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($termLabels[$record['term']] ?? $record['term']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($record['subject_name']) ?> (<?= htmlspecialchars($record['subject_code']) ?>)
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($record['marks']) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($record['grade']) ?>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                    </table>
                </div>
                <?php endif; ?>
                
                <!-- Promotion History Section -->
                <div class="border-t border-gray-200 mt-6">
                    <div class="px-4 py-5 sm:px-6">
                        <h4 class="text-md font-medium text-gray-900">Promotion History</h4>
                        <p class="mt-1 max-w-2xl text-sm text-gray-500">Class promotions and movements</p>
                    </div>
                    <div class="overflow-x-auto">
                        <?php if (empty($promotionHistory)): ?>
                        <div class="px-4 py-5 sm:px-6 text-sm text-gray-500 text-center">
                            No promotion history found for this student.
                        </div>
                        <?php else: ?>
                        <table class="min-w-full divide-y divide-gray-200">
                            <thead class="bg-gray-50">
                                <tr>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Date
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        From Class
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        To Class
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Academic Year
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Promoted By
                                    </th>
                                    <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                        Remarks
                                    </th>
                                </tr>
                            </thead>
                            <tbody class="bg-white divide-y divide-gray-200">
                                <?php foreach ($promotionHistory as $promotion): ?>
                                <tr>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                        <?= date('M j, Y', strtotime($promotion['promotion_date'])) ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-yellow-100 text-yellow-800">
                                            <?= htmlspecialchars($promotion['from_class_name'] ?? 'N/A') ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap">
                                        <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium bg-green-100 text-green-800">
                                            <?= htmlspecialchars($promotion['to_class_name'] ?? 'N/A') ?>
                                        </span>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($promotion['academic_year_name'] ?? 'N/A') ?>
                                    </td>
                                    <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                        <?= htmlspecialchars($promotion['promoted_by_username'] ?? 'N/A') ?>
                                    </td>
                                    <td class="px-6 py-4 text-sm text-gray-500">
                                        <?= htmlspecialchars($promotion['remarks'] ?? 'N/A') ?>
                                    </td>
                                </tr>
                                <?php endforeach; ?>
                            </tbody>
                        </table>
                        <?php endif; ?>
                    </div>
                </div>
            </div>
        </div>

        <!-- Financial Information Tab -->
        <div id="financial-tab-content" class="bg-white shadow sm:rounded-lg tab-content <?= $activeTab === 'financial' ? 'block' : 'hidden' ?>">

            <div class="px-4 py-5 sm:px-6">
                <h3 class="text-lg leading-6 font-medium text-gray-900">Financial Information</h3>
                <p class="mt-1 max-w-2xl text-sm text-gray-500">Payments and outstanding bills</p>
            </div>
            <div class="border-t border-gray-200">
                <!-- DEBUG: -->
                <!-- Debug line removed -->
                <?php if (empty($financialInfo['fees'])): ?>
                <div class="px-4 py-5 sm:px-6 text-sm text-gray-500">
                    No financial records found for this student.
                </div>
                <?php else: ?>
                <!-- Fee Summary -->
                <div class="overflow-x-auto">
                    <table class="min-w-full divide-y divide-gray-200">
                        <thead class="bg-gray-50">
                            <tr>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Fee Type
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Amount
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Paid Amount
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Balance
                                </th>
                                <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                    Status
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($financialInfo['fees'] as $info): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                    <?= htmlspecialchars($info['name']) ?>
                                    <div class="text-gray-500 text-xs">
                                        <?= htmlspecialchars(ucfirst($info['type'])) ?>
                                    </div>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars(number_format($info['amount'], 2)) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars(number_format($info['paid_amount'], 2)) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars(number_format($info['balance'], 2)) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full <?= $paymentStatusColors[$info['status']] ?>">
                                        <?= htmlspecialchars($paymentStatusLabels[$info['status']]) ?>
                                    </span>
                                </td>
                            </tr>
                            <?php endforeach; ?>
                        </tbody>
                        <tfoot class="bg-gray-50">
                            <tr>
                                <td class="px-6 py-3 text-sm font-medium text-gray-900">
                                    Total
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">
                                    <?= htmlspecialchars(number_format(array_sum(array_column($financialInfo['fees'], 'amount')), 2)) ?>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">
                                    <?= htmlspecialchars(number_format($financialInfo['total_payments'], 2)) ?>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">
                                    <?= htmlspecialchars(number_format(array_sum(array_column($financialInfo['fees'], 'balance')), 2)) ?>
                                </td>
                                <td class="px-6 py-3 text-sm text-gray-500">
                                    <?php 
                                    $totalFees = array_sum(array_column($financialInfo['fees'], 'amount'));
                                    $totalPaid = $financialInfo['total_payments'];
                                    if ($totalPaid >= $totalFees) {
                                        echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-green-100 text-green-800">Fully Paid</span>';
                                    } else if ($totalPaid > 0) {
                                        echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-yellow-100 text-yellow-800">Partly Paid</span>';
                                    } else {
                                        echo '<span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-red-100 text-red-800">Pending</span>';
                                    }
                                    ?>
                                </td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
                
                <!-- Payment History Grouped by Academic Year and Term -->
                <?php if (!empty($financialInfo['grouped_payments'])): ?>
                <div class="border-t border-gray-200 mt-6">
                    <div class="px-4 py-5 sm:px-6">
                        <div class="flex justify-between items-center">
                            <h4 class="text-md font-medium text-gray-900">Payment History by Academic Year</h4>
                            <div class="flex space-x-2">
                                <button onclick="printSection('payment-history-container', 'Payment History')" class="bg-gray-100 text-gray-700 hover:bg-gray-200 px-3 py-1.5 rounded-md text-sm font-medium transition flex items-center">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 17h2a2 2 0 002-2v-4a2 2 0 00-2-2H5a2 2 0 00-2 2v4a2 2 0 002 2h2m2 4h6a2 2 0 002-2v-4a2 2 0 00-2-2H9a2 2 0 00-2 2v4a2 2 0 002 2zm8-12V5a2 2 0 00-2-2H9a2 2 0 00-2 2v4h10z" />
                                    </svg>
                                    Print
                                </button>
                                <button onclick="exportPaymentHistoryGroups('payment-history-container', 'payment_history_<?= $student['admission_no'] ?>')" class="bg-indigo-50 text-indigo-700 hover:bg-indigo-100 px-3 py-1.5 rounded-md text-sm font-medium transition flex items-center">
                                    <svg class="h-4 w-4 mr-1.5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16v1a3 3 0 003 3h10a3 3 0 003-3v-1m-4-4l-4 4m0 0l-4-4m4 4V4" />
                                    </svg>
                                    Export
                                </button>
                            </div>
                        </div>
                    </div>
                    <div id="payment-history-container">
                    <?php foreach ($financialInfo['grouped_payments'] as $academicYear => $terms): ?>
                    <div class="px-4 py-3 bg-gray-50">
                        <h5 class="text-sm font-medium text-gray-700"><?= htmlspecialchars($academicYear) ?></h5>
                    </div>
                    <?php foreach ($terms as $term => $payments): ?>
                    <div class="border-b border-gray-200">
                        <div class="px-4 py-2 bg-white">
                            <h6 class="text-sm font-medium text-gray-600 ml-4"><?= htmlspecialchars($termLabels[$term] ?? $term) ?></h6>
                        </div>
                        <div class="overflow-x-auto">
                            <table class="min-w-full divide-y divide-gray-200">
                                <thead class="bg-gray-50">
                                    <tr>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Date
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Fee
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Amount
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Method
                                        </th>
                                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                                            Remarks
                                        </th>
                                    </tr>
                                </thead>
                                <tbody class="bg-white divide-y divide-gray-200">
                                    <?php foreach ($payments as $payment): ?>
                                    <tr>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                                            <?= htmlspecialchars(date('M j, Y', strtotime($payment['date']))) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?php 
                                            // Get fee name for this payment
                                            $feeName = 'Unknown Fee';
                                            foreach ($financialInfo['fees'] as $fee) {
                                                if ($fee['id'] == $payment['fee_id']) {
                                                    $feeName = $fee['name'];
                                                    break;
                                                }
                                            }
                                            echo htmlspecialchars($feeName);
                                            ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars(number_format($payment['amount'], 2)) ?>
                                        </td>
                                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                            <?= htmlspecialchars(ucfirst(str_replace('_', ' ', $payment['method']))) ?>
                                        </td>
                                        <td class="px-6 py-4 text-sm text-gray-500">
                                            <?= htmlspecialchars($payment['remarks'] ?? 'N/A') ?>
                                        </td>
                                    </tr>
                                    <?php endforeach; ?>
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <?php endforeach; ?>
                    <?php endforeach; ?>
                    </div>
                </div>
                <?php endif; ?>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    // Profile collapse/expand functionality
    const profileHeader = document.getElementById('profile-header');
    const profileDetails = document.getElementById('profile-details');
    const expandIcon = document.getElementById('expand-icon');
    
    // Toggle profile details visibility
    profileHeader.addEventListener('click', function() {
        const isCollapsed = profileDetails.classList.contains('hidden');
        
        if (isCollapsed) {
            // Expand
            profileDetails.classList.remove('hidden');
            expandIcon.classList.add('rotate-180');
        } else {
            // Collapse
            profileDetails.classList.add('hidden');
            expandIcon.classList.remove('rotate-180');
        }
        
        // Store state in cookie
        document.cookie = `profile_collapsed=${!isCollapsed}; path=/`;
    });
    
    // Tab switching functionality
    const academicTabLink = document.querySelector('.academic-tab-link');
    const financialTabLink = document.querySelector('.financial-tab-link');
    const academicTabContent = document.getElementById('academic-tab-content');
    const financialTabContent = document.getElementById('financial-tab-content');
    
    // Function to switch tabs
    function switchTab(tabName) {
        if (tabName === 'academic') {
            // Show academic tab
            academicTabContent.classList.remove('hidden');
            academicTabContent.classList.add('block');
            financialTabContent.classList.remove('block');
            financialTabContent.classList.add('hidden');
            
            // Update tab link styling
            academicTabLink.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            academicTabLink.classList.add('border-indigo-500', 'text-indigo-600');
            financialTabLink.classList.remove('border-indigo-500', 'text-indigo-600');
            financialTabLink.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        } else if (tabName === 'financial') {
            // Show financial tab
            financialTabContent.classList.remove('hidden');
            financialTabContent.classList.add('block');
            academicTabContent.classList.remove('block');
            academicTabContent.classList.add('hidden');
            
            // Update tab link styling
            financialTabLink.classList.remove('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
            financialTabLink.classList.add('border-indigo-500', 'text-indigo-600');
            academicTabLink.classList.remove('border-indigo-500', 'text-indigo-600');
            academicTabLink.classList.add('border-transparent', 'text-gray-500', 'hover:text-gray-700', 'hover:border-gray-300');
        }
        
        // Update URL without page refresh
        const url = new URL(window.location);
        url.searchParams.set('tab', tabName);
        window.history.pushState({tab: tabName}, '', url);
    }
    
    // Add event listeners
    if (academicTabLink) {
        academicTabLink.addEventListener('click', function(e) {
            e.preventDefault();
            switchTab('academic');
        });
    }
    
    if (financialTabLink) {
        financialTabLink.addEventListener('click', function(e) {
            e.preventDefault();
            switchTab('financial');
        });
    }
    
    // Handle browser back/forward buttons
    window.addEventListener('popstate', function(event) {
        const tab = event.state && event.state.tab ? event.state.tab : 'academic';
        switchTab(tab);
    });
});

// Print Functionality
function printSection(elementId, title) {
    const content = document.getElementById(elementId).innerHTML;
    const printWindow = window.open('', '', 'height=600,width=800');
    
    printWindow.document.write('<html><head><title>' + title + '</title>');
    printWindow.document.write('<link href="https://cdn.jsdelivr.net/npm/tailwindcss@2.2.19/dist/tailwind.min.css" rel="stylesheet">');
    printWindow.document.write('</head><body class="p-8">');
    printWindow.document.write('<h1 class="text-2xl font-bold mb-4">' + title + '</h1>');
    printWindow.document.write('<div><h2 class="text-lg font-semibold mb-2">Student: <?= htmlspecialchars($student['first_name'] . " " . $student['last_name']) ?> (<?= htmlspecialchars($student['admission_no']) ?>)</h2></div>');
    printWindow.document.write(content);
    printWindow.document.write('</body></html>');
    
    printWindow.document.close();
    printWindow.focus();
    
    // Timer to allow styles to load with a fallback
    setTimeout(() => {
        printWindow.print();
        printWindow.close();
    }, 1000);
}

// Export Helper Function
function downloadCSV(csv, filename) {
    const csvFile = new Blob([csv], { type: "text/csv" });
    const downloadLink = document.createElement("a");
    downloadLink.download = filename;
    downloadLink.href = window.URL.createObjectURL(csvFile);
    downloadLink.style.display = "none";
    document.body.appendChild(downloadLink);
    downloadLink.click();
    document.body.removeChild(downloadLink);
}

// Export Single Table to CSV
function exportTableToCSV(tableId, filename) {
    const table = document.getElementById(tableId);
    let csv = [];
    const rows = table.querySelectorAll("tr");
    
    for (let i = 0; i < rows.length; i++) {
        let row = [], cols = rows[i].querySelectorAll("td, th");
        
        for (let j = 0; j < cols.length; j++) {
            // Get clean text content
            let data = cols[j].innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
            // Escape double quotes
            data = data.replace(/"/g, '""');
            // Wrap in double quotes
            row.push('"' + data + '"');
        }
        
        csv.push(row.join(","));
    }
    
    downloadCSV(csv.join("\n"), filename + ".csv");
}

// Export Payment History Groups to CSV
function exportPaymentHistoryGroups(containerId, filename) {
    const container = document.getElementById(containerId);
    let csv = [];
    
    // Header for the CSV
    csv.push('"Academic Year","Term","Date","Fee","Amount","Method","Remarks"');
    
    // Iterate through academic years (h5) and their following content
    const yearHeaders = container.querySelectorAll('h5');
    
    yearHeaders.forEach(yearHeader => {
        const year = yearHeader.innerText.trim();
        // The container structure: h5(year) -> div(term wrapper) -> div(term header) + div(table wrapper)
        // This structure is a bit flat in the loop, so we need to be careful. 
        // Based on PHP loop:
        // div.px-4.py-3 (Year)
        // div.border-b (Term + Table)
        
        // Let's traverse the DOM relative to the year header to find sibling term sections until the next year header
        let sibling = yearHeader.parentElement.nextElementSibling;
        
        while (sibling && !sibling.querySelector('h5')) { 
            // Check if this sibling is a term section
            const termHeader = sibling.querySelector('h6');
            if (termHeader) {
                const term = termHeader.innerText.trim();
                const table = sibling.querySelector('table');
                if (table) {
                    const rows = table.querySelectorAll('tbody tr');
                    rows.forEach(row => {
                        const cols = row.querySelectorAll('td');
                        if (cols.length >= 5) {
                            let rowData = [
                                '"' + year + '"',
                                '"' + term + '"'
                            ];
                            
                            cols.forEach(col => {
                                let data = col.innerText.replace(/(\r\n|\n|\r)/gm, " ").trim();
                                data = data.replace(/"/g, '""');
                                rowData.push('"' + data + '"');
                            });
                            
                            csv.push(rowData.join(","));
                        }
                    });
                }
            }
            sibling = sibling.nextElementSibling;
        }
    });
    
    if (csv.length <= 1) {
        alert('No payment history data found to export.');
        return;
    }
    
    downloadCSV(csv.join("\n"), filename + ".csv");
}
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>