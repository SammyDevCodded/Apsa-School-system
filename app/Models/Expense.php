<?php
namespace App\Models;

use App\Core\Database;

class Expense {
    private $db;
    
    public function __construct() {
        $this->db = \App\Core\Database::getInstance();
    }
    
    public function getAllWithDetails($filters = [], $search = '', $limit = 50, $offset = 0) {
        $sql = "SELECT e.*, c.category_name, u.first_name, u.last_name, s.first_name as staff_first, s.last_name as staff_last 
                FROM expenses e 
                JOIN expense_categories c ON e.category_id = c.id
                JOIN users u ON e.added_by = u.id
                LEFT JOIN staff s ON e.staff_id = s.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (e.title LIKE ? OR e.expense_code LIKE ? OR c.category_name LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($filters['start_date'])) {
            $sql .= " AND e.expense_date >= ?";
            $params[] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $sql .= " AND e.expense_date <= ?";
            $params[] = $filters['end_date'];
        }
        
        if (!empty($filters['category_id'])) {
            $sql .= " AND e.category_id = ?";
            $params[] = $filters['category_id'];
        }
        
        $sql .= " ORDER BY e.expense_date DESC, e.id DESC LIMIT ? OFFSET ?";
        $params[] = (int)$limit;
        $params[] = (int)$offset;
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function countAllWithDetails($filters = [], $search = '') {
        $sql = "SELECT count(e.id) as cnt
                FROM expenses e 
                JOIN expense_categories c ON e.category_id = c.id
                JOIN users u ON e.added_by = u.id
                LEFT JOIN staff s ON e.staff_id = s.id
                WHERE 1=1";
        
        $params = [];
        
        if (!empty($search)) {
            $sql .= " AND (e.title LIKE ? OR e.expense_code LIKE ? OR c.category_name LIKE ?)";
            $params[] = "%$search%";
            $params[] = "%$search%";
            $params[] = "%$search%";
        }
        
        if (!empty($filters['start_date'])) {
            $sql .= " AND e.expense_date >= ?";
            $params[] = $filters['start_date'];
        }
        
        if (!empty($filters['end_date'])) {
            $sql .= " AND e.expense_date <= ?";
            $params[] = $filters['end_date'];
        }
        
        if (!empty($filters['category_id'])) {
            $sql .= " AND e.category_id = ?";
            $params[] = $filters['category_id'];
        }
        
        $res = $this->db->fetchOne($sql, $params);
        return $res['cnt'] ?? 0;
    }
    
    public function getById($id) {
        return $this->db->fetchOne("
            SELECT e.*, c.category_name, u.first_name, u.last_name, s.first_name as staff_first, s.last_name as staff_last 
            FROM expenses e 
            JOIN expense_categories c ON e.category_id = c.id
            JOIN users u ON e.added_by = u.id
            LEFT JOIN staff s ON e.staff_id = s.id
            WHERE e.id = ?", [$id]);
    }
    
    public function getDashboardStats() {
        $today = date('Y-m-d');
        $firstDayOfMonth = date('Y-m-01');
        
        $todayExpenses = $this->db->fetchOne("SELECT SUM(amount) as total FROM expenses WHERE expense_date = ? AND status = 'approved'", [$today]);
        $monthExpenses = $this->db->fetchOne("SELECT SUM(amount) as total FROM expenses WHERE expense_date >= ? AND status = 'approved'", [$firstDayOfMonth]);
        
        return [
            'today' => $todayExpenses['total'] ?? 0,
            'month' => $monthExpenses['total'] ?? 0
        ];
    }
    
    public function generateExpenseCode() {
        $prefix = 'EXP';
        $yearMonth = date('Ym');
        $random = strtoupper(substr(uniqid(), -4));
        return $prefix . '-' . $yearMonth . '-' . $random;
    }
    
    public function create($data) {
        if (empty($data['expense_code'])) {
            $data['expense_code'] = $this->generateExpenseCode();
        }
        return $this->db->insert('expenses', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('expenses', $data, "id = $id");
    }
    
    public function delete($id) {
        return $this->db->delete('expenses', "id = $id");
    }
}
