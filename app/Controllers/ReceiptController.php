<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Payment;
use App\Models\Student;
use App\Models\Fee;
use App\Models\Receipt;

class ReceiptController extends Controller
{
    /**
     * Generate and store receipt for a payment or transaction
     */
    public function generate($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->jsonResponse(['success' => false, 'error' => 'Unauthorized'], 401);
            return;
        }
        
        $paymentModel = new Payment();
        
        // Get the payment by ID with academic year information
        $payment = $paymentModel->getByIdWithDetails($id);
        
        if (!$payment) {
            $this->jsonResponse(['success' => false, 'error' => 'Payment not found'], 404);
            return;
        }
        
        // Get student information
        $studentModel = new Student();
        $student = $studentModel->find($payment['student_id']);
        
        // Get fee information if fee_id exists
        $fee = null;
        if (!empty($payment['fee_id'])) {
            $feeModel = new Fee();
            $fee = $feeModel->find($payment['fee_id']);
            $payment['fee_name'] = $fee['name'] ?? '';
        }
        
        // Format payment method
        $methodLabels = [
            'cash' => 'Cash',
            'cheque' => 'Cheque',
            'bank_transfer' => 'Bank Transfer',
            'mobile_money' => 'Mobile Money',
            'other' => 'Other'
        ];
        $method = $payment['method'] ?? 'other';
        $methodLabel = $methodLabels[$method] ?? ucfirst(str_replace('_', ' ', $method));
        
        // Format date
        $paymentDate = date('M j, Y', strtotime($payment['date'] ?? ''));
        
        // Get payment method specific details
        $methodDetails = [];
        if ($method === 'cash' && ($payment['cash_payer_name'] || $payment['cash_payer_phone'])) {
            $methodDetails = [
                'payer_name' => $payment['cash_payer_name'],
                'payer_phone' => $payment['cash_payer_phone']
            ];
        } elseif ($method === 'mobile_money' && ($payment['mobile_money_sender_name'] || $payment['mobile_money_sender_number'] || $payment['mobile_money_reference'])) {
            $methodDetails = [
                'sender_name' => $payment['mobile_money_sender_name'],
                'sender_number' => $payment['mobile_money_sender_number'],
                'reference' => $payment['mobile_money_reference']
            ];
        } elseif ($method === 'bank_transfer' && ($payment['bank_transfer_sender_bank'] || $payment['bank_transfer_invoice_number'] || $payment['bank_transfer_details'])) {
            $methodDetails = [
                'sender_bank' => $payment['bank_transfer_sender_bank'],
                'invoice_number' => $payment['bank_transfer_invoice_number'],
                'details' => $payment['bank_transfer_details']
            ];
        } elseif ($method === 'cheque' && ($payment['cheque_bank'] || $payment['cheque_number'] || $payment['cheque_details'])) {
            $methodDetails = [
                'bank' => $payment['cheque_bank'],
                'cheque_number' => $payment['cheque_number'],
                'details' => $payment['cheque_details']
            ];
        }
        
        // Prepare receipt data with single payment
        $receiptData = [
            'payments' => [$payment], // Wrap in array for consistency
            'student' => $student,
            'method_label' => $methodLabel,
            'payment_date' => $paymentDate,
            'method_details' => $methodDetails,
            'generated_at' => date('Y-m-d H:i:s')
        ];
        
        // Store receipt with the payment ID
        $receiptModel = new Receipt();
        $receiptId = $receiptModel->createReceipt($payment['id'], $receiptData);
        
        if ($receiptId) {
            $this->jsonResponse([
                'success' => true,
                'message' => 'Receipt generated successfully',
                'receipt_id' => $receiptId
            ]);
        } else {
            $this->jsonResponse(['success' => false, 'error' => 'Failed to generate receipt'], 500);
        }
    }
    
    /**
     * Show receipt by ID
     */
    public function show($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        $receiptModel = new Receipt();
        $receipt = $receiptModel->find($id);
        
        if (!$receipt) {
            $this->flash('error', 'Receipt not found');
            $this->redirect('/payments');
        }
        
        // Decode receipt data
        $receiptData = json_decode($receipt['receipt_data'], true);
        
        $this->view('receipts/show', [
            'receipt' => $receipt,
            'receiptData' => $receiptData
        ]);
    }
}