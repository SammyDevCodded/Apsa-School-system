<?php
namespace App\Models;

use App\Core\Database;

class CashBook {
    private $db;
    
    public function __construct() {
        $this->db = \App\Core\Database::getInstance();
    }
    
    public function getLedger($limit = 100, $offset = 0) {
        return $this->db->fetchAll("
            SELECT * FROM cash_book 
            ORDER BY transaction_date DESC, id DESC 
            LIMIT ? OFFSET ?
        ", [$limit, $offset]);
    }

    public function getLedgerWithDetails($filters = [], $search = '', $limit = 50, $offset = 0) {
        $sql = "SELECT cb.*, 
                       e.title as expense_title, 
                       pr.purpose as request_purpose
                FROM cash_book cb
                LEFT JOIN expenses e ON cb.reference_type = 'expense' AND cb.reference_id = e.id
                LEFT JOIN payment_requests pr ON cb.reference_type = 'payment_request' AND cb.reference_id = pr.id
                WHERE 1=1";
                
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (cb.transaction_code LIKE ? OR e.title LIKE ? OR pr.purpose LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($filters['start_date'])) {
            $sql .= " AND cb.transaction_date >= ?";
            $params[] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $sql .= " AND cb.transaction_date <= ?";
            $params[] = $filters['end_date'];
        }
        
        if (!empty($filters['transaction_type']) && in_array($filters['transaction_type'], ['credit', 'debit'])) {
            $sql .= " AND cb.transaction_type = ?";
            $params[] = $filters['transaction_type'];
        }
        
        $sql .= " ORDER BY cb.transaction_date DESC, cb.id DESC LIMIT ? OFFSET ?";
        
        // Add limit and offset
        $params[] = (int)$limit;
        $params[] = (int)$offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function countLedgerWithDetails($filters = [], $search = '') {
        $sql = "SELECT count(cb.id) as cnt
                FROM cash_book cb
                LEFT JOIN expenses e ON cb.reference_type = 'expense' AND cb.reference_id = e.id
                LEFT JOIN payment_requests pr ON cb.reference_type = 'payment_request' AND cb.reference_id = pr.id
                WHERE 1=1";
                
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (cb.transaction_code LIKE ? OR e.title LIKE ? OR pr.purpose LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($filters['start_date'])) {
            $sql .= " AND cb.transaction_date >= ?";
            $params[] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $sql .= " AND cb.transaction_date <= ?";
            $params[] = $filters['end_date'];
        }
        
        if (!empty($filters['transaction_type']) && in_array($filters['transaction_type'], ['credit', 'debit'])) {
            $sql .= " AND cb.transaction_type = ?";
            $params[] = $filters['transaction_type'];
        }
        
        $res = $this->db->fetchOne($sql, $params);
        return $res['cnt'] ?? 0;
    }
    
    public function getLedgerTotals($filters = [], $search = '') {
        $sql = "SELECT 
                    SUM(CASE WHEN cb.transaction_type = 'credit' THEN cb.amount ELSE 0 END) as total_credit,
                    SUM(CASE WHEN cb.transaction_type = 'debit' THEN cb.amount ELSE 0 END) as total_debit
                FROM cash_book cb
                LEFT JOIN expenses e ON cb.reference_type = 'expense' AND cb.reference_id = e.id
                LEFT JOIN payment_requests pr ON cb.reference_type = 'payment_request' AND cb.reference_id = pr.id
                WHERE 1=1";
                
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (cb.transaction_code LIKE ? OR e.title LIKE ? OR pr.purpose LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($filters['start_date'])) {
            $sql .= " AND cb.transaction_date >= ?";
            $params[] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $sql .= " AND cb.transaction_date <= ?";
            $params[] = $filters['end_date'];
        }
        
        if (!empty($filters['transaction_type']) && in_array($filters['transaction_type'], ['credit', 'debit'])) {
            $sql .= " AND cb.transaction_type = ?";
            $params[] = $filters['transaction_type'];
        }
        
        $res = $this->db->fetchOne($sql, $params);
        
        return [
            'total_credit' => $res['total_credit'] ?? 0.00,
            'total_debit' => $res['total_debit'] ?? 0.00
        ];
    }
    
    public function getCurrentBalance() {
        $latest = $this->db->fetchOne("SELECT balance_after FROM cash_book ORDER BY id DESC LIMIT 1");
        return $latest['balance_after'] ?? 0.00;
    }
    
    public function generateTransactionCode($type) {
        $prefix = ($type == 'credit') ? 'CR' : 'DR';
        $timeStr = date('YmdHis');
        $random = rand(100, 999);
        return $prefix . '-' . $timeStr . '-' . $random;
    }
    
    /**
     * Record a new transaction in the cash book.
     * $type: 'credit' (income) or 'debit' (expense)
     * $refType: 'expense', 'payment_request', 'fee_payment', 'other_income'
     */
    public function recordTransaction($type, $refType, $refId, $amount, $date = null) {
        if (!$date) {
            $date = date('Y-m-d');
        }
        
        $currentBalance = $this->getCurrentBalance();
        
        if ($type === 'credit') {
            $balanceAfter = $currentBalance + $amount;
        } else {
            // debit
            $balanceAfter = $currentBalance - $amount;
        }
        
        $data = [
            'transaction_code' => $this->generateTransactionCode($type),
            'transaction_type' => $type,
            'reference_type' => $refType,
            'reference_id' => $refId,
            'amount' => $amount,
            'balance_after' => $balanceAfter,
            'transaction_date' => $date
        ];
        
        return $this->db->insert('cash_book', $data);
    }
    
    /**
     * Delete a transaction (if an expense or fee is deleted) and recalculate the downstream ledger balances
     * Note: In a real accounting system you "void" a transaction by creating an offsetting one, 
     * but for simplicity we allow deletes and recalculate if required.
     */
    public function removeTransaction($refType, $refId) {
        $transaction = $this->db->fetchOne("SELECT id FROM cash_book WHERE reference_type = ? AND reference_id = ?", [$refType, $refId]);
        if ($transaction) {
            $this->db->delete('cash_book', "id = " . $transaction['id']);
            $this->recalculateBalances();
            return true;
        }
        return false;
    }
    
    /**
     * Overwrites all balances chronologically - intensive operation, used only for adjustments
     */
    private function recalculateBalances() {
        $ledger = $this->db->query("SELECT id, transaction_type, amount FROM cash_book ORDER BY transaction_date ASC, id ASC")->fetchAll(\PDO::FETCH_ASSOC);
        
        $runningBalance = 0.00;
        foreach ($ledger as $item) {
            if ($item['transaction_type'] === 'credit') {
                $runningBalance += $item['amount'];
            } else {
                $runningBalance -= $item['amount'];
            }
            $this->db->update('cash_book', ['balance_after' => $runningBalance], 'id = ' . $item['id']);
        }
    }
}
