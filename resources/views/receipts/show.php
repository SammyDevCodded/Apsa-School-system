<?php 
$title = 'View Receipt'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Payment Receipt</h1>
            <div class="flex space-x-3">
                <button id="print-receipt-btn" class="inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Print Receipt
                </button>
                <a href="/receipts" class="inline-flex justify-center py-2 px-4 border border-gray-300 shadow-sm text-sm font-medium rounded-md text-gray-700 bg-white hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                    Back to Receipts
                </a>
            </div>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <?php if (isset($receipt) && $receipt): ?>
                    <?php 
                    // Check if receiptData is already decoded or needs to be decoded
                    if (isset($receiptData)) {
                        // Already decoded (from controller)
                        $data = $receiptData;
                    } else {
                        // Need to decode from receipt
                        $data = json_decode($receipt['receipt_data'], true);
                    }
                    
                    $payments = $data['payments'];
                    $student = $data['student'];
                    $methodLabel = $data['method_label'];
                    $paymentDate = $data['payment_date'];
                    $methodDetails = $data['method_details'];
                    
                    // Calculate total amount
                    $totalAmount = 0;
                    foreach ($payments as $payment) {
                        $totalAmount += $payment['amount'];
                    }
                    ?>
                    
                    <div class="max-w-2xl mx-auto bg-white p-8 border border-gray-300">
                        <div class="text-center mb-6">
                            <h2 class="text-2xl font-bold text-gray-800">PAYMENT RECEIPT</h2>
                            <p class="text-gray-600 mt-2">Official Receipt for Payment</p>
                        </div>
                        
                        <div class="border-t border-b border-gray-300 py-4 mb-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Receipt No:</p>
                                    <p class="font-medium">#<?= $payments[0]['id'] ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Date:</p>
                                    <p class="font-medium"><?= $paymentDate ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Student:</p>
                                    <p class="font-medium"><?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?></p>
                                </div>
                                <div>
                                    <p class="text-sm text-gray-600">Admission No:</p>
                                    <p class="font-medium"><?= htmlspecialchars($student['admission_no']) ?></p>
                                </div>
                                <?php if (!empty($payments[0]['academic_year_name']) || !empty($payments[0]['term'])): ?>
                                <div>
                                    <p class="text-sm text-gray-600">Academic Year:</p>
                                    <p class="font-medium"><?= htmlspecialchars($payments[0]['academic_year_name'] ?? 'N/A') ?></p>
                                    <?php if (!empty($payments[0]['term'])): ?>
                                    <p class="text-sm text-gray-600"><?= htmlspecialchars($payments[0]['term']) ?></p>
                                    <?php endif; ?>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <h3 class="text-lg font-medium text-gray-800 mb-3">Payment Details</h3>
                            <div class="border border-gray-300 rounded-md">
                                <table class="min-w-full">
                                    <thead>
                                        <tr class="bg-gray-50">
                                            <th class="px-4 py-2 text-left text-sm font-medium text-gray-700">Description</th>
                                            <th class="px-4 py-2 text-right text-sm font-medium text-gray-700">Amount (₵)</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <?php foreach ($payments as $payment): ?>
                                            <?php 
                                            // Get fee name if fee_id exists
                                            if (!empty($payment['fee_id'])) {
                                                $feeModel = new \App\Models\Fee();
                                                $fee = $feeModel->find($payment['fee_id']);
                                                $feeName = $fee['name'] ?? 'Payment';
                                            } else {
                                                $feeName = 'Payment';
                                            }
                                            ?>
                                            <tr>
                                                <td class="px-4 py-2 text-sm text-gray-900"><?= htmlspecialchars($feeName) ?></td>
                                                <td class="px-4 py-2 text-sm text-gray-900 text-right">₵<?= number_format($payment['amount'], 2) ?></td>
                                            </tr>
                                        <?php endforeach; ?>
                                    </tbody>
                                    <tfoot>
                                        <tr class="border-t border-gray-300">
                                            <td class="px-4 py-2 text-sm font-medium text-gray-900">Total</td>
                                            <td class="px-4 py-2 text-sm font-medium text-gray-900 text-right">₵<?= number_format($totalAmount, 2) ?></td>
                                        </tr>
                                    </tfoot>
                                </table>
                            </div>
                        </div>
                        
                        <div class="mb-6">
                            <div class="grid grid-cols-2 gap-4">
                                <div>
                                    <p class="text-sm text-gray-600">Payment Method:</p>
                                    <p class="font-medium"><?= $methodLabel ?></p>
                                    <?php if (!empty($methodDetails)): ?>
                                        <div class="mt-2">
                                            <?php foreach ($methodDetails as $key => $value): ?>
                                                <?php if (!empty($value)): ?>
                                                    <p><span class="font-medium"><?= ucfirst(str_replace('_', ' ', $key)) ?>:</span> <?= htmlspecialchars($value) ?></p>
                                                <?php endif; ?>
                                            <?php endforeach; ?>
                                        </div>
                                    <?php endif; ?>
                                </div>
                                <?php 
                                // Get remarks from the first payment that has remarks
                                $remarks = '';
                                foreach ($payments as $payment) {
                                    if (!empty($payment['remarks'])) {
                                        $remarks = $payment['remarks'];
                                        break;
                                    }
                                }
                                ?>
                                <?php if (!empty($remarks)): ?>
                                <div>
                                    <p class="text-sm text-gray-600">Remarks:</p>
                                    <p class="font-medium"><?= htmlspecialchars($remarks) ?></p>
                                </div>
                                <?php endif; ?>
                            </div>
                        </div>
                        
                        <div class="text-center text-xs text-gray-500 mt-8">
                            <p>Thank you for your payment. This is an official receipt.</p>
                            <p class="mt-1">Generated on <?= date('M j, Y g:i A', strtotime($receipt['created_at'])) ?></p>
                        </div>
                    </div>
                <?php else: ?>
                    <div class="text-center py-12">
                        <p class="text-gray-500">Receipt not found.</p>
                        <a href="/receipts" class="mt-4 inline-flex items-center px-4 py-2 border border-transparent text-sm font-medium rounded-md shadow-sm text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Back to Receipts
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </div>
    </div>
</div>

<script>
// Handle print receipt button click
document.getElementById('print-receipt-btn').addEventListener('click', function() {
    window.print();
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>