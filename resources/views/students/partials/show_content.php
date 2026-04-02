<?php
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
?>
<div class="flex items-center mb-6">
    <?php if (!empty($student['profile_picture'])): ?>
        <img src="/storage/uploads/<?= htmlspecialchars($student['profile_picture']) ?>" alt="Profile Picture" class="h-20 w-20 object-cover rounded-full mr-4" onerror="this.src='/images/default-profile.png';">
    <?php else: ?>
        <div class="h-20 w-20 rounded-full bg-gray-200 flex items-center justify-center mr-4">
            <span class="text-gray-500 text-2xl"><?= substr($student['first_name'], 0, 1) ?></span>
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

<div class="border-t border-gray-200">
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

<!-- Academic Information -->
<div class="mt-6">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Academic Information</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Recorded academics of student</p>
    </div>
    <div class="border-t border-gray-200">
        <?php if (empty($academicRecords)): ?>
        <div class="px-4 py-5 sm:px-6 text-sm text-gray-500">
            No academic records found for this student.
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Exam
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Subject
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Score
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Grade
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Term
                        </th>
                    </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                    <?php foreach ($academicRecords as $record): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <?= htmlspecialchars($record['exam_name']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= htmlspecialchars($record['subject_name']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= htmlspecialchars($record['score']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= htmlspecialchars($record['grade']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <?= htmlspecialchars($termLabels[$record['term']] ?? ucfirst($record['term'])) ?>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        <?php endif; ?>
    </div>
</div>

<!-- Financial Information -->
<div class="mt-6">
    <div class="px-4 py-5 sm:px-6">
        <h3 class="text-lg leading-6 font-medium text-gray-900">Financial Information</h3>
        <p class="mt-1 max-w-2xl text-sm text-gray-500">Fee structures and payment records</p>
    </div>
    <div class="border-t border-gray-200">
        <?php if (empty($financialInfo['fees'])): ?>
        <div class="px-4 py-5 sm:px-6 text-sm text-gray-500">
            No financial records found for this student.
        </div>
        <?php else: ?>
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                    <tr>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Fee Structure
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Amount
                        </th>
                        <th scope="col" class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                            Paid
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
                    <?php foreach ($financialInfo['fees'] as $fee): ?>
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                            <?= htmlspecialchars($fee['name']) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ¢<?= number_format($fee['amount'], 2) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ¢<?= number_format($fee['paid_amount'], 2) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            ¢<?= number_format($fee['balance'], 2) ?>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium <?= $paymentStatusColors[$fee['status']] ?? 'bg-gray-100 text-gray-800' ?>">
                                <?= htmlspecialchars($paymentStatusLabels[$fee['status']] ?? ucfirst($fee['status'])) ?>
                            </span>
                        </td>
                    </tr>
                    <?php endforeach; ?>
                </tbody>
            </table>
        </div>
        
        <?php if (!empty($financialInfo['grouped_payments'])): ?>
        <div class="mt-6">
            <h4 class="text-md font-medium text-gray-900 px-6 py-3">Payment History by Academic Year</h4>
            <?php foreach ($financialInfo['grouped_payments'] as $academicYear => $terms): ?>
            <div class="px-6 py-3 bg-gray-50">
                <h5 class="text-sm font-medium text-gray-700"><?= htmlspecialchars($academicYear) ?></h5>
            </div>
            <?php foreach ($terms as $term => $payments): ?>
            <div class="border-b border-gray-200">
                <div class="px-6 py-2 bg-white">
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
                                    Reference
                                </th>
                            </tr>
                        </thead>
                        <tbody class="bg-white divide-y divide-gray-200">
                            <?php foreach ($payments as $payment): ?>
                            <tr>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
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
                                    ¢<?= number_format($payment['amount'], 2) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars(ucfirst($payment['method'])) ?>
                                </td>
                                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                                    <?= htmlspecialchars($payment['reference'] ?? 'N/A') ?>
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
        <?php endif; ?>
        <?php endif; ?>
    </div>
</div>