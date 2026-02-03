<?php 
$title = 'Pay Fees'; 
ob_start(); 
?>

<div class="max-w-7xl mx-auto py-6 sm:px-6 lg:px-8">
    <div class="px-4 py-6 sm:px-0">
        <div class="flex justify-between items-center mb-6">
            <h1 class="text-2xl font-semibold text-gray-900">Pay Fees</h1>
            <a href="/payments" class="bg-gray-600 text-white px-4 py-2 rounded-md text-sm font-medium hover:bg-gray-700">
                Back to Payments
            </a>
        </div>

        <div class="bg-white shadow overflow-hidden sm:rounded-lg">
            <div class="px-4 py-5 sm:p-6">
                <form id="pay-fees-form" class="space-y-6">
                    <div class="grid grid-cols-1 gap-6 sm:grid-cols-2">
                        <div>
                            <label for="student_id" class="block text-sm font-medium text-gray-700">Student</label>
                            <select name="student_id" id="student_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Student</option>
                                <?php foreach ($allStudents ?? [] as $student): ?>
                                    <option value="<?= $student['id'] ?>">
                                        <?= htmlspecialchars($student['first_name'] . ' ' . $student['last_name']) ?> 
                                        (<?= htmlspecialchars($student['admission_no']) ?>) - 
                                        <?= htmlspecialchars($student['class_name'] ?? 'No Class') ?>
                                    </option>
                                <?php endforeach; ?>
                            </select>
                        </div>

                        <div>
                            <label for="fee_id" class="block text-sm font-medium text-gray-700">Fee</label>
                            <select name="fee_id" id="fee_id" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Fee</option>
                                <!-- Options will be populated dynamically based on selected student -->
                            </select>
                        </div>

                        <div>
                            <label for="amount" class="block text-sm font-medium text-gray-700">Amount</label>
                            <input type="number" step="0.01" name="amount" id="amount" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div>
                            <label for="method" class="block text-sm font-medium text-gray-700">Payment Method</label>
                            <select name="method" id="method" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                                <option value="">Select Method</option>
                                <option value="cash">Cash</option>
                                <option value="cheque">Cheque</option>
                                <option value="mobile_money">Mobile Money</option>
                                <option value="bank_transfer">Bank Transfer</option>
                                <option value="other">Other</option>
                            </select>
                        </div>

                        <div>
                            <label for="date" class="block text-sm font-medium text-gray-700">Date</label>
                            <input type="date" name="date" id="date" value="<?= date('Y-m-d') ?>" required
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        </div>

                        <div class="sm:col-span-2">
                            <label for="remarks" class="block text-sm font-medium text-gray-700">Remarks</label>
                            <textarea name="remarks" id="remarks" rows="3"
                                class="mt-1 block w-full border border-gray-300 rounded-md shadow-sm py-2 px-3 focus:outline-none focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm"></textarea>
                        </div>
                    </div>

                    <div id="payment-error" class="hidden bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline" id="payment-error-message"></span>
                    </div>

                    <div id="payment-success" class="hidden bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded relative" role="alert">
                        <span class="block sm:inline" id="payment-success-message"></span>
                    </div>

                    <div class="flex justify-end">
                        <button type="submit"
                            class="ml-3 inline-flex justify-center py-2 px-4 border border-transparent shadow-sm text-sm font-medium rounded-md text-white bg-indigo-600 hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            Record Payment
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<script>
// Handle form submission
document.getElementById('pay-fees-form').addEventListener('submit', function(e) {
    e.preventDefault();
    
    // Get form data
    const formData = new FormData(this);
    
    // Show loading state
    const submitBtn = this.querySelector('button[type="submit"]');
    const originalText = submitBtn.textContent;
    submitBtn.textContent = 'Processing...';
    submitBtn.disabled = true;
    
    // Hide previous messages
    document.getElementById('payment-error').classList.add('hidden');
    document.getElementById('payment-success').classList.add('hidden');
    
    // Submit form via AJAX
    fetch('/payments', {
        method: 'POST',
        body: formData,
        headers: {
            'X-Requested-With': 'XMLHttpRequest'
        }
    })
    .then(response => response.json())
    .then(data => {
        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        
        if (data.success) {
            // Show success message
            document.getElementById('payment-success-message').textContent = data.message || 'Payment recorded successfully';
            document.getElementById('payment-success').classList.remove('hidden');
            
            // Reset form
            document.getElementById('pay-fees-form').reset();
        } else {
            // Show error message
            document.getElementById('payment-error-message').textContent = data.error || 'Failed to record payment';
            document.getElementById('payment-error').classList.remove('hidden');
        }
    })
    .catch(error => {
        // Reset button state
        submitBtn.textContent = originalText;
        submitBtn.disabled = false;
        
        // Show error message
        document.getElementById('payment-error-message').textContent = 'An error occurred while recording the payment. Please try again.';
        document.getElementById('payment-error').classList.remove('hidden');
    });
});

// Handle student selection to populate fees
document.getElementById('student_id').addEventListener('change', function() {
    const studentId = this.value;
    const feeSelect = document.getElementById('fee_id');
    
    // Clear existing options
    feeSelect.innerHTML = '<option value="">Select Fee</option>';
    
    if (studentId) {
        // Fetch student's fees via AJAX
        fetch(`/payments/student-fees/${studentId}`, {
            headers: {
                'X-Requested-With': 'XMLHttpRequest'
            }
        })
        .then(response => response.json())
        .then(data => {
            if (data.success && data.fee_allocations) {
                data.fee_allocations.forEach(allocation => {
                    const option = document.createElement('option');
                    option.value = allocation.fee_id;
                    option.textContent = `${allocation.fee_name} - Balance: ₵${(allocation.amount - allocation.paid_amount).toFixed(2)}`;
                    option.dataset.balance = (allocation.amount - allocation.paid_amount).toFixed(2);
                    feeSelect.appendChild(option);
                });
            }
        })
        .catch(error => {
            console.error('Error fetching student fees:', error);
        });
    }
});

// Handle fee selection to populate amount
document.getElementById('fee_id').addEventListener('change', function() {
    const selectedOption = this.options[this.selectedIndex];
    const amountInput = document.getElementById('amount');
    
    if (selectedOption && selectedOption.dataset.balance) {
        amountInput.value = selectedOption.dataset.balance;
    } else {
        amountInput.value = '';
    }
});
</script>

<?php 
$content = ob_get_clean();
include RESOURCES_PATH . '/layouts/app.php';
?>