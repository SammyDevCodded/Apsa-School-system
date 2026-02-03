<?php
namespace App\Models;

use App\Core\Model;

class Receipt extends Model
{
    protected $table = 'receipts';
    protected $fillable = [
        'payment_id',
        'receipt_data'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Create a new receipt record
     */
    public function createReceipt($paymentId, $receiptData)
    {
        $data = [
            'payment_id' => $paymentId,
            'receipt_data' => json_encode($receiptData)
        ];
        
        return $this->create($data);
    }

    /**
     * Get receipt by payment ID
     */
    public function getByPaymentId($paymentId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE payment_id = :payment_id";
        $result = $this->db->fetchOne($sql, ['payment_id' => $paymentId]);
        
        if ($result) {
            $result['receipt_data'] = json_decode($result['receipt_data'], true);
        }
        
        return $result;
    }
    
    /**
     * Get receipt by transaction ID
     */
    public function getByTransactionId($transactionId)
    {
        // First, get the receipt record (we store it with the first payment ID)
        $sql = "SELECT * FROM {$this->table} WHERE payment_id IN (SELECT id FROM payments WHERE transaction_id = :transaction_id LIMIT 1)";
        $result = $this->db->fetchOne($sql, ['transaction_id' => $transactionId]);
        
        if ($result) {
            $result['receipt_data'] = json_decode($result['receipt_data'], true);
        }
        
        return $result;
    }

    /**
     * Get all receipts with payment and student information
     */
    public function getAllWithPaymentInfo($filters = [], $searchTerm = '', $page = 1, $perPage = 10)
    {
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT r.*, p.amount, p.date, p.method, p.remarks, 
                       s.first_name, s.last_name, s.admission_no, 
                       c.name as class_name, f.name as fee_name
                FROM {$this->table} r
                LEFT JOIN payments p ON r.payment_id = p.id
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                LEFT JOIN fees f ON p.fee_id = f.id";
        
        $countSql = "SELECT COUNT(*) as count 
                     FROM {$this->table} r
                     LEFT JOIN payments p ON r.payment_id = p.id
                     LEFT JOIN students s ON p.student_id = s.id";
        
        $whereConditions = [];
        $countWhereConditions = [];
        $params = [];
        $countParams = [];
        
        // Add search term conditions
        if (!empty($searchTerm)) {
            $whereConditions[] = "(s.first_name LIKE :search_term1 OR 
                                  s.last_name LIKE :search_term2 OR 
                                  s.admission_no LIKE :search_term3 OR
                                  CONCAT(s.first_name, ' ', s.last_name) LIKE :search_term4)";
            $countWhereConditions[] = "(s.first_name LIKE :search_term_count1 OR 
                                      s.last_name LIKE :search_term_count2 OR 
                                      s.admission_no LIKE :search_term_count3 OR
                                      CONCAT(s.first_name, ' ', s.last_name) LIKE :search_term_count4)";
            $params['search_term1'] = '%' . $searchTerm . '%';
            $params['search_term2'] = '%' . $searchTerm . '%';
            $params['search_term3'] = '%' . $searchTerm . '%';
            $params['search_term4'] = '%' . $searchTerm . '%';
            $countParams['search_term_count1'] = '%' . $searchTerm . '%';
            $countParams['search_term_count2'] = '%' . $searchTerm . '%';
            $countParams['search_term_count3'] = '%' . $searchTerm . '%';
            $countParams['search_term_count4'] = '%' . $searchTerm . '%';
        }
        
        // Add filter conditions
        if (!empty($filters['class_id'])) {
            $whereConditions[] = "s.class_id = :class_id";
            $countWhereConditions[] = "s.class_id = :class_id_count";
            $params['class_id'] = $filters['class_id'];
            $countParams['class_id_count'] = $filters['class_id'];
        }
        
        // Add date range filter conditions
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $whereConditions[] = "p.date BETWEEN :date_from AND :date_to";
            $countWhereConditions[] = "p.date BETWEEN :date_from_count AND :date_to_count";
            $params['date_from'] = $filters['date_from'];
            $params['date_to'] = $filters['date_to'];
            $countParams['date_from_count'] = $filters['date_from'];
            $countParams['date_to_count'] = $filters['date_to'];
        } elseif (!empty($filters['date_from'])) {
            $whereConditions[] = "p.date >= :date_from";
            $countWhereConditions[] = "p.date >= :date_from_count";
            $params['date_from'] = $filters['date_from'];
            $countParams['date_from_count'] = $filters['date_from'];
        } elseif (!empty($filters['date_to'])) {
            $whereConditions[] = "p.date <= :date_to";
            $countWhereConditions[] = "p.date <= :date_to_count";
            $params['date_to'] = $filters['date_to'];
            $countParams['date_to_count'] = $filters['date_to'];
        }
        
        // Combine all conditions
        if (!empty($whereConditions)) {
            $whereClause = " WHERE " . implode(' AND ', $whereConditions);
            $countWhereClause = " WHERE " . implode(' AND ', $countWhereConditions);
            $sql .= $whereClause;
            $countSql .= $countWhereClause;
        }
        
        $sql .= " ORDER BY r.created_at DESC";
        
        // Add pagination
        $sql .= " LIMIT :limit OFFSET :offset";
        $params['limit'] = $perPage;
        $params['offset'] = $offset;
        
        // Get total count
        $countResult = $this->db->fetchOne($countSql, $countParams);
        $totalCount = $countResult ? (int)$countResult['count'] : 0;
        
        // Get results
        $results = $this->db->fetchAll($sql, $params);
        
        // Decode receipt data
        foreach ($results as &$result) {
            $result['receipt_data'] = json_decode($result['receipt_data'], true);
        }
        
        // Calculate pagination details
        $totalPages = ceil($totalCount / $perPage);
        
        return [
            'data' => $results,
            'total' => $totalCount,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages
        ];
    }
}