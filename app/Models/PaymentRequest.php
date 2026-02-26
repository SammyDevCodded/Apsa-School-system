<?php
namespace App\Models;

use App\Core\Database;

class PaymentRequest {
    private $db;
    
    public function __construct() {
        $this->db = \App\Core\Database::getInstance();
    }
    
    public function getAllWithDetails($filters = []) {
        $sql = "SELECT pr.*, 
                rq_u.first_name as rq_first, rq_u.last_name as rq_last,
                ap_u.first_name as ap_first, ap_u.last_name as ap_last,
                s.first_name as staff_first, s.last_name as staff_last
                FROM payment_requests pr 
                LEFT JOIN users rq_u ON pr.requested_by = rq_u.id
                LEFT JOIN users ap_u ON pr.approved_by = ap_u.id
                LEFT JOIN staff s ON pr.staff_id = s.id
                WHERE 1=1";
                
        $params = [];
        
        if (!empty($filters['status'])) {
            $sql .= " AND pr.status = ?";
            $params[] = $filters['status'];
        }
        
        if (!empty($filters['requested_by'])) {
            $sql .= " AND pr.requested_by = ?";
            $params[] = $filters['requested_by'];
        }
        
        $sql .= " ORDER BY pr.created_at DESC, pr.id DESC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getById($id) {
        return $this->db->fetchOne("
            SELECT pr.*, 
                rq_u.first_name as rq_first, rq_u.last_name as rq_last,
                ap_u.first_name as ap_first, ap_u.last_name as ap_last,
                s.first_name as staff_first, s.last_name as staff_last
            FROM payment_requests pr 
            LEFT JOIN users rq_u ON pr.requested_by = rq_u.id
            LEFT JOIN users ap_u ON pr.approved_by = ap_u.id
            LEFT JOIN staff s ON pr.staff_id = s.id
            WHERE pr.id = ?", [$id]);
    }
    
    public function countPendingRequests() {
        $result = $this->db->fetchOne("SELECT count(*) as cnt FROM payment_requests WHERE status = 'pending'");
        return $result['cnt'] ?? 0;
    }
    
    public function generateRequestCode() {
        $prefix = 'REQ';
        $yearMonth = date('Ym');
        $random = strtoupper(substr(uniqid(), -4));
        return $prefix . '-' . $yearMonth . '-' . $random;
    }
    
    public function create($data) {
        if (empty($data['request_code'])) {
            $data['request_code'] = $this->generateRequestCode();
        }
        return $this->db->insert('payment_requests', $data);
    }
    
    public function update($id, $data) {
        return $this->db->update('payment_requests', $data, "id = $id");
    }
    
    public function delete($id) {
        return $this->db->delete('payment_requests', "id = $id");
    }
}
