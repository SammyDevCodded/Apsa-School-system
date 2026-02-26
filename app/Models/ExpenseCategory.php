<?php
namespace App\Models;

use App\Core\Database;

class ExpenseCategory {
    private $db;
    
    public function __construct() {
        $this->db = \App\Core\Database::getInstance();
    }
    
    public function getAll() {
        return $this->db->fetchAll("SELECT * FROM expense_categories ORDER BY category_name ASC");
    }
    
    public function getById($id) {
        return $this->db->fetchOne("SELECT * FROM expense_categories WHERE id = ?", [$id]);
    }
    
    public function create($data) {
        return $this->db->insert('expense_categories', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('expense_categories', $data, "id = $id");
    }
    
    public function delete($id) {
        // Only delete if not in use by expenses
        $inUse = $this->db->fetchOne("SELECT count(*) as cnt FROM expenses WHERE category_id = ?", [$id]);
        if ($inUse && $inUse['cnt'] > 0) {
            return false;
        }
        return $this->db->delete('expense_categories', "id = $id");
    }
}
