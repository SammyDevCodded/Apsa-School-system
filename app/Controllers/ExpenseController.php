<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\Expense;
use App\Models\ExpenseCategory;
use App\Models\PaymentRequest;
use App\Models\CashBook;
use App\Models\Setting;

class ExpenseController extends Controller {
    private $expenseModel;
    private $categoryModel;
    private $requestModel;
    private $cashBookModel;
    
    public function __construct() {

        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        // Standard expense/finance access checking could be added here
        
        $this->expenseModel = new Expense();
        $this->categoryModel = new ExpenseCategory();
        $this->requestModel = new PaymentRequest();
        $this->cashBookModel = new CashBook();
    }
    
    /**
     * Display the main expenses dashboard/tabs
     */
    public function index() {
        $tab = $_GET['tab'] ?? 'dashboard';
        
        $data = [
            'activeTab' => $tab,
            'title' => 'Expense Tracking'
        ];
        
        // Load partial data depending on tab
        switch ($tab) {
            case 'dashboard':
                $data['stats'] = $this->expenseModel->getDashboardStats();
                $data['pendingRequests'] = $this->requestModel->countPendingRequests();
                $data['currentBalance'] = $this->cashBookModel->getCurrentBalance();
                break;
            case 'expenses':
                $search = trim($_GET['search'] ?? '');
                $filters = [
                    'start_date' => $_GET['start_date'] ?? '',
                    'end_date' => $_GET['end_date'] ?? '',
                    'category_id' => $_GET['category_id'] ?? ''
                ];
                
                $page = max(1, intval($_GET['page'] ?? 1));
                $limit = max(10, intval($_GET['limit'] ?? 50));
                $offset = ($page - 1) * $limit;
                
                $totalItems = $this->expenseModel->countAllWithDetails($filters, $search);
                $totalPages = ceil($totalItems / $limit);
                
                $data['expenses'] = $this->expenseModel->getAllWithDetails($filters, $search, $limit, $offset);
                $data['categories'] = $this->categoryModel->getAll();
                
                $data['search'] = $search;
                $data['filters'] = $filters;
                $data['page'] = $page;
                $data['limit'] = $limit;
                $data['totalPages'] = $totalPages;
                $data['totalItems'] = $totalItems;
                
                // Need staff list for modal
                $staffModel = new \App\Models\Staff();
                $data['staffs'] = $staffModel->all();
                
                $settingModel = new \App\Models\Setting();
                $data['settings'] = $settingModel->getSettings();
                break;
            case 'categories':
                 $data['categories'] = $this->categoryModel->getAll();
                 break;
            case 'requests':
                $data['requests'] = $this->requestModel->getAllWithDetails();
                // Load staff list for 'Request On Behalf' modal
                $staffModel = new \App\Models\Staff();
                $data['staffs'] = $staffModel->all();
                
                // Track Proxy authorization boolean natively via Controller scope
                $data['can_request_on_behalf'] = $this->hasAnyRole(['super_admin', 'accountant']);
                break;
            case 'cashbook':
                $search = trim($_GET['search'] ?? '');
                $filters = [
                    'start_date' => $_GET['start_date'] ?? '',
                    'end_date' => $_GET['end_date'] ?? '',
                    'transaction_type' => $_GET['transaction_type'] ?? ''
                ];
                
                $page = max(1, intval($_GET['page'] ?? 1));
                $limit = max(10, intval($_GET['limit'] ?? 50));
                $offset = ($page - 1) * $limit;
                
                $totalItems = $this->cashBookModel->countLedgerWithDetails($filters, $search);
                $totalPages = ceil($totalItems / $limit);

                $data['ledger'] = $this->cashBookModel->getLedgerWithDetails($filters, $search, $limit, $offset);
                $data['ledgerTotals'] = $this->cashBookModel->getLedgerTotals($filters, $search);
                $data['currentBalance'] = $this->cashBookModel->getCurrentBalance();
                
                $data['search'] = $search;
                $data['filters'] = $filters;
                $data['page'] = $page;
                $data['limit'] = $limit;
                $data['totalPages'] = $totalPages;
                $data['totalItems'] = $totalItems;
                
                $settingModel = new \App\Models\Setting();
                $data['settings'] = $settingModel->getSettings();
                break;
            case 'reports':
                // Report filtering setup
                break;
        }
        
        $this->view('finance/expenses/index', $data);
    }
    
    /**
     * Category CRUD
     */
    public function saveCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $data = [
                'category_name' => trim($_POST['category_name']),
                'description' => trim($_POST['description'] ?? '')
            ];
            
            if (empty($data['category_name'])) {
                $_SESSION['flash_error'] = "Category name is required.";
                $this->redirect('/finance/expenses?tab=categories');
            }
            
            try {
                if (!empty($id)) {
                    $this->categoryModel->update($id, $data);
                    $_SESSION['flash_success'] = "Category updated successfully.";
                } else {
                    $this->categoryModel->create($data);
                    $_SESSION['flash_success'] = "Category added successfully.";
                }
            } catch (\Exception $e) {
                $_SESSION['flash_error'] = "Database error: " . $e->getMessage();
            }
        }
        $this->redirect('/finance/expenses?tab=categories');
    }
    
    public function deleteCategory() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            if ($this->categoryModel->delete($_POST['id'])) {
                $_SESSION['flash_success'] = "Category deleted successfully.";
            } else {
                $_SESSION['flash_error'] = "Cannot delete category because it is in use.";
            }
        }
        $this->redirect('/finance/expenses?tab=categories');
    }
    
    /**
     * Expense CRUD
     */
    public function saveExpense() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $categoryId = $_POST['category_id'] ?? '';
            $amount = floatval($_POST['amount'] ?? 0);
            
            $data = [
                'category_id' => $categoryId,
                'title' => trim($_POST['title']),
                'description' => trim($_POST['description'] ?? ''),
                'amount' => $amount,
                'payment_method' => $_POST['payment_method'] ?? 'cash',
                'expense_date' => $_POST['expense_date'] ?? date('Y-m-d'),
                'status' => 'approved', // Direct expenses are pre-approved
                'added_by' => $_SESSION['user']['id']
            ];
            
            // Check for Staff Pay category linkage
            $categoryInfo = $this->categoryModel->getById($categoryId);
            if ($categoryInfo && stripos($categoryInfo['category_name'], 'Staff') !== false) {
                $data['staff_id'] = !empty($_POST['staff_id']) ? $_POST['staff_id'] : null;
            } else {
                $data['staff_id'] = null;
            }
            
            if (empty($data['title']) || $amount <= 0 || empty($categoryId)) {
                $_SESSION['flash_error'] = "Missing required fields or invalid amount.";
                $this->redirect('/finance/expenses?tab=expenses');
            }
            
            try {
                if (!empty($id)) {
                    $oldExpense = $this->expenseModel->getById($id);
                    $this->expenseModel->update($id, $data);
                    
                    // Update cashbook - simplified by removing old, adding new
                    $this->cashBookModel->removeTransaction('expense', $id);
                    $this->cashBookModel->recordTransaction('debit', 'expense', $id, $amount, $data['expense_date']);
                    
                    $_SESSION['flash_success'] = "Expense updated successfully.";
                } else {
                    $newId = $this->expenseModel->create($data);
                    // Add direct debit to cash book
                    $this->cashBookModel->recordTransaction('debit', 'expense', $newId, $amount, $data['expense_date']);
                    
                    $_SESSION['flash_success'] = "Expense logged successfully.";
                }
            } catch (\Exception $e) {
                $_SESSION['flash_error'] = "Database error: " . $e->getMessage();
            }
        }
        $this->redirect('/finance/expenses?tab=expenses');
    }
    
    public function deleteExpense() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            if ($this->expenseModel->delete($id)) {
                $this->cashBookModel->removeTransaction('expense', $id);
                $_SESSION['flash_success'] = "Expense deleted successfully.";
            } else {
                $_SESSION['flash_error'] = "Failed to delete expense.";
            }
        }
        $this->redirect('/finance/expenses?tab=expenses');
    }
    
    /**
     * Payment Requests CRUD & Workflow
     */
    public function savePaymentRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $data = [
                'amount' => floatval($_POST['amount'] ?? 0),
                'purpose' => trim($_POST['purpose']),
                'requested_by' => $_SESSION['user']['id']
            ];
            
            
            // Link to staff id if the requester is a staff
            $db = \App\Core\Database::getInstance();
            
            // "Request On Behalf" functionality for Administrators
            $requestOnBehalf = isset($_POST['request_on_behalf']) && $_POST['request_on_behalf'] === '1';
            $onBehalfStaffId = $_POST['on_behalf_staff_id'] ?? null;
            $canProxy = $this->hasAnyRole(['super_admin', 'accountant']);
            
            if ($requestOnBehalf && $canProxy && !empty($onBehalfStaffId)) {
                 $data['staff_id'] = $onBehalfStaffId;
            } else {
                 // Standard flow: tie the current user's profile automatically if they are a staff
                 $staff = $db->fetchOne("SELECT id FROM staff WHERE user_id = ?", [$_SESSION['user']['id']]);
                 if ($staff) {
                     $data['staff_id'] = $staff['id'];
                 }
            }
            
            if ($data['amount'] <= 0 || empty($data['purpose'])) {
                $_SESSION['flash_error'] = "Valid amount and purpose are required.";
                $this->redirect('/finance/expenses?tab=requests');
            }
            
            try {
                if (!empty($id)) {
                    // Only update if it's pending
                    $req = $this->requestModel->getById($id);
                    if ($req && $req['status'] === 'pending') {
                        $this->requestModel->update($id, $data);
                        $_SESSION['flash_success'] = "Request updated successfully.";
                    } else {
                        $_SESSION['flash_error'] = "Cannot update parsed requests.";
                    }
                } else {
                    $this->requestModel->create($data);
                    $_SESSION['flash_success'] = "Payment request submitted.";
                }
            } catch (\Exception $e) {
                $_SESSION['flash_error'] = "Database error: " . $e->getMessage();
            }
        }
        $this->redirect('/finance/expenses?tab=requests');
    }
    
    public function updatePaymentRequestStatus() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST') {
            $id = $_POST['id'] ?? '';
            $status = $_POST['status'] ?? '';
            
            if (empty($id) || !in_array($status, ['approved', 'rejected', 'paid'])) {
                $this->redirect('/finance/expenses?tab=requests');
            }
            
            try {
                $req = $this->requestModel->getById($id);
                if (!$req) {
                    throw new \Exception("Request not found.");
                }
                
                $updateData = ['status' => $status];
                
                if (in_array($status, ['approved', 'paid'])) {
                    $updateData['approved_by'] = $_SESSION['user']['id'];
                }
                
                $this->requestModel->update($id, $updateData);
                
                // If moving to PAID, deduct from CashBook automatically
                if ($status === 'paid' && $req['status'] !== 'paid') {
                    $this->cashBookModel->recordTransaction('debit', 'payment_request', $id, $req['amount']);
                }
                
                // If rolling back from PAID to something else, remove the transaction
                if ($status !== 'paid' && $req['status'] === 'paid') {
                    $this->cashBookModel->removeTransaction('payment_request', $id);
                }
                
                $_SESSION['flash_success'] = "Request status updated to " . ucfirst($status);
                
            } catch (\Exception $e) {
                $_SESSION['flash_error'] = "Action failed: " . $e->getMessage();
            }
        }
        $this->redirect('/finance/expenses?tab=requests');
    }
    
    public function deletePaymentRequest() {
        if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['id'])) {
            $id = $_POST['id'];
            $req = $this->requestModel->getById($id);
            if ($req && $req['status'] === 'pending') {
                if ($this->requestModel->delete($id)) {
                    $_SESSION['flash_success'] = "Payment request deleted safely.";
                }
            } else {
                $_SESSION['flash_error'] = "Can only delete pending requests.";
            }
        }
        $this->redirect('/finance/expenses?tab=requests');
    }
}
