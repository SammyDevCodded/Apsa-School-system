<?php
namespace App\Models;

use App\Core\Model;

class Payment extends Model
{
    protected $table = 'payments';
    protected $fillable = [
        'transaction_id',
        'student_id',
        'fee_id',
        'academic_year_id',
        'term',
        'amount',
        'method',
        'date',
        'remarks',
        'cash_payer_name',
        'cash_payer_phone',
        'mobile_money_sender_name',
        'mobile_money_sender_number',
        'mobile_money_reference',
        'bank_transfer_sender_bank',
        'bank_transfer_invoice_number',
        'bank_transfer_details',
        'cheque_bank',
        'cheque_number',
        'cheque_details'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        $sql = "SELECT p.*, s.first_name, s.last_name, s.admission_no, f.name as fee_name 
                FROM {$this->table} p 
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN fees f ON p.fee_id = f.id
                ORDER BY p.date DESC";
        return $this->db->fetchAll($sql);
    }

    public function getByStudentId($studentId)
    {
        $sql = "SELECT p.*, s.first_name, s.last_name, s.admission_no 
                FROM {$this->table} p 
                LEFT JOIN students s ON p.student_id = s.id
                WHERE p.student_id = :student_id
                ORDER BY p.date DESC";
        return $this->db->fetchAll($sql, ['student_id' => $studentId]);
    }

    public function getTotalPayments()
    {
        $sql = "SELECT SUM(amount) as total FROM {$this->table}";
        $result = $this->db->fetchOne($sql);
        return $result['total'] ?? 0;
    }
    
    /**
     * Get payments by transaction ID
     */
    public function getByTransactionId($transactionId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE transaction_id = :transaction_id ORDER BY id ASC";
        return $this->db->fetchAll($sql, ['transaction_id' => $transactionId]);
    }
    
    /**
     * Get payments with student and fee information for filtering
     */
    public function getAllWithDetails($filters = [], $searchTerm = '', $page = 1, $perPage = 10)
    {
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT p.*, s.first_name, s.last_name, s.admission_no, c.name as class_name, f.name as fee_name, ay.name as academic_year_name, p.term
                FROM {$this->table} p 
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                LEFT JOIN fees f ON p.fee_id = f.id
                LEFT JOIN academic_years ay ON p.academic_year_id = ay.id";
        
        $countSql = "SELECT COUNT(*) as count 
                     FROM {$this->table} p 
                     LEFT JOIN students s ON p.student_id = s.id
                     LEFT JOIN classes c ON s.class_id = c.id
                     LEFT JOIN fees f ON p.fee_id = f.id
                     LEFT JOIN academic_years ay ON p.academic_year_id = ay.id";
        
        $whereConditions = [];
        $countWhereConditions = [];
        $params = [];
        $countParams = [];
        
        // Add search term conditions (enhanced to include guardian name)
        if (!empty($searchTerm)) {
            $whereConditions[] = "(s.first_name LIKE :search_term1 OR 
                                  s.last_name LIKE :search_term2 OR 
                                  s.admission_no LIKE :search_term3 OR
                                  CONCAT(s.first_name, ' ', s.last_name) LIKE :search_term4 OR
                                  s.guardian_name LIKE :search_term5)";
            $countWhereConditions[] = "(s.first_name LIKE :search_term_count1 OR 
                                      s.last_name LIKE :search_term_count2 OR 
                                      s.admission_no LIKE :search_term_count3 OR
                                      CONCAT(s.first_name, ' ', s.last_name) LIKE :search_term_count4 OR
                                      s.guardian_name LIKE :search_term_count5)";
            $params['search_term1'] = '%' . $searchTerm . '%';
            $params['search_term2'] = '%' . $searchTerm . '%';
            $params['search_term3'] = '%' . $searchTerm . '%';
            $params['search_term4'] = '%' . $searchTerm . '%';
            $params['search_term5'] = '%' . $searchTerm . '%';
            $countParams['search_term_count1'] = '%' . $searchTerm . '%';
            $countParams['search_term_count2'] = '%' . $searchTerm . '%';
            $countParams['search_term_count3'] = '%' . $searchTerm . '%';
            $countParams['search_term_count4'] = '%' . $searchTerm . '%';
            $countParams['search_term_count5'] = '%' . $searchTerm . '%';
        }
        
        // Add filter conditions
        if (!empty($filters['class_id'])) {
            $whereConditions[] = "s.class_id = :class_id";
            $countWhereConditions[] = "s.class_id = :class_id_count";
            $params['class_id'] = $filters['class_id'];
            $countParams['class_id_count'] = $filters['class_id'];
        }
        
        // Add academic year filter
        if (!empty($filters['academic_year_id'])) {
            $whereConditions[] = "p.academic_year_id = :academic_year_id";
            $countWhereConditions[] = "p.academic_year_id = :academic_year_id_count";
            $params['academic_year_id'] = $filters['academic_year_id'];
            $countParams['academic_year_id_count'] = $filters['academic_year_id'];
        }
        
        // Add term filter
        if (!empty($filters['term'])) {
            $whereConditions[] = "p.term = :term";
            $countWhereConditions[] = "p.term = :term_count";
            $params['term'] = $filters['term'];
            $countParams['term_count'] = $filters['term'];
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
        
        $sql .= " ORDER BY p.date DESC";
        
        // Add pagination
        $sql .= " LIMIT :limit OFFSET :offset";
        $params['limit'] = $perPage;
        $params['offset'] = $offset;
        
        // Get total count
        $countResult = $this->db->fetchOne($countSql, $countParams);
        $totalCount = $countResult ? (int)$countResult['count'] : 0;
        
        // Get results
        $results = $this->db->fetchAll($sql, $params);
        
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
    
    public function getByIdWithDetails($id)
    {
        $sql = "SELECT p.*, s.first_name, s.last_name, s.admission_no, c.name as class_name, f.name as fee_name, ay.name as academic_year_name, p.term
                FROM {$this->table} p 
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                LEFT JOIN fees f ON p.fee_id = f.id
                LEFT JOIN academic_years ay ON p.academic_year_id = ay.id
                WHERE p.id = :id";
        
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    /**
     * Get payments by student ID and fee ID
     */
    public function getByStudentIdAndFeeId($studentId, $feeId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE student_id = :student_id AND fee_id = :fee_id 
                ORDER BY date DESC";
        return $this->db->fetchAll($sql, [
            'student_id' => $studentId,
            'fee_id' => $feeId
        ]);
    }

    /**
     * Get total amount paid by student for a specific fee
     */
    public function getTotalPaidByStudentAndFee($studentId, $feeId)
    {
        $sql = "SELECT SUM(amount) as total FROM {$this->table} 
                WHERE student_id = :student_id AND fee_id = :fee_id";
        $result = $this->db->fetchOne($sql, [
            'student_id' => $studentId,
            'fee_id' => $feeId
        ]);
        return $result['total'] ?? 0;
    }
    
    /**
     * Get recent payments for analytics dashboard
     */
    public function getRecentPayments($days = 30)
    {
        $sql = "SELECT p.*, s.first_name, s.last_name, s.admission_no, f.name as fee_name
                FROM {$this->table} p 
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN fees f ON p.fee_id = f.id
                WHERE p.date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                ORDER BY p.date DESC";
        return $this->db->fetchAll($sql, ['days' => $days]);
    }
    
    /**
     * Get daily report data
     */
    public function getDailyReport($filters = [], $searchTerm = '')
    {
        $sql = "SELECT DATE(p.date) as report_date, SUM(p.amount) as total_amount, COUNT(p.id) as transaction_count
                FROM {$this->table} p 
                LEFT JOIN students s ON p.student_id = s.id";
        
        $whereConditions = [];
        $params = [];
        
        // Add search term conditions
        if (!empty($searchTerm)) {
            $whereConditions[] = "(s.first_name LIKE :search_term1 OR 
                                  s.last_name LIKE :search_term2 OR 
                                  s.admission_no LIKE :search_term3 OR
                                  CONCAT(s.first_name, ' ', s.last_name) LIKE :search_term4 OR
                                  s.guardian_name LIKE :search_term5)";
            $params['search_term1'] = '%' . $searchTerm . '%';
            $params['search_term2'] = '%' . $searchTerm . '%';
            $params['search_term3'] = '%' . $searchTerm . '%';
            $params['search_term4'] = '%' . $searchTerm . '%';
            $params['search_term5'] = '%' . $searchTerm . '%';
        }
        
        // Add filter conditions
        if (!empty($filters['academic_year_id'])) {
            $whereConditions[] = "p.academic_year_id = :academic_year_id";
            $params['academic_year_id'] = $filters['academic_year_id'];
        }
        
        // Add class_id filter
        if (!empty($filters['class_id'])) {
            $whereConditions[] = "s.class_id = :class_id";
            $params['class_id'] = $filters['class_id'];
        }
        
        // Add date range filter conditions
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $whereConditions[] = "p.date BETWEEN :date_from AND :date_to";
            $params['date_from'] = $filters['date_from'];
            $params['date_to'] = $filters['date_to'];
        } elseif (!empty($filters['date_from'])) {
            $whereConditions[] = "p.date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        } elseif (!empty($filters['date_to'])) {
            $whereConditions[] = "p.date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        // Combine all conditions
        if (!empty($whereConditions)) {
            $whereClause = " WHERE " . implode(' AND ', $whereConditions);
            $sql .= $whereClause;
        }
        
        $sql .= " GROUP BY DATE(p.date) ORDER BY p.date DESC LIMIT 30";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get weekly report data
     */
    public function getWeeklyReport($filters = [], $searchTerm = '')
    {
        $sql = "SELECT YEARWEEK(p.date) as week_number, 
                       DATE_FORMAT(MIN(p.date), '%Y-%m-%d') as start_date,
                       DATE_FORMAT(MAX(p.date), '%Y-%m-%d') as end_date,
                       SUM(p.amount) as total_amount, 
                       COUNT(p.id) as transaction_count
                FROM {$this->table} p 
                LEFT JOIN students s ON p.student_id = s.id";
        
        $whereConditions = [];
        $params = [];
        
        // Add search term conditions
        if (!empty($searchTerm)) {
            $whereConditions[] = "(s.first_name LIKE :search_term1 OR 
                                  s.last_name LIKE :search_term2 OR 
                                  s.admission_no LIKE :search_term3 OR
                                  CONCAT(s.first_name, ' ', s.last_name) LIKE :search_term4 OR
                                  s.guardian_name LIKE :search_term5)";
            $params['search_term1'] = '%' . $searchTerm . '%';
            $params['search_term2'] = '%' . $searchTerm . '%';
            $params['search_term3'] = '%' . $searchTerm . '%';
            $params['search_term4'] = '%' . $searchTerm . '%';
            $params['search_term5'] = '%' . $searchTerm . '%';
        }
        
        // Add filter conditions
        if (!empty($filters['academic_year_id'])) {
            $whereConditions[] = "p.academic_year_id = :academic_year_id";
            $params['academic_year_id'] = $filters['academic_year_id'];
        }

        // Add class_id filter
        if (!empty($filters['class_id'])) {
            $whereConditions[] = "s.class_id = :class_id";
            $params['class_id'] = $filters['class_id'];
        }
        
        // Add date range filter conditions
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $whereConditions[] = "p.date BETWEEN :date_from AND :date_to";
            $params['date_from'] = $filters['date_from'];
            $params['date_to'] = $filters['date_to'];
        } elseif (!empty($filters['date_from'])) {
            $whereConditions[] = "p.date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        } elseif (!empty($filters['date_to'])) {
            $whereConditions[] = "p.date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        // Combine all conditions
        if (!empty($whereConditions)) {
            $whereClause = " WHERE " . implode(' AND ', $whereConditions);
            $sql .= $whereClause;
        }
        
        $sql .= " GROUP BY YEARWEEK(p.date) ORDER BY MIN(p.date) DESC LIMIT 12";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get monthly report data
     */
    public function getMonthlyReport($filters = [], $searchTerm = '')
    {
        $sql = "SELECT DATE_FORMAT(p.date, '%Y-%m') as month, 
                       DATE_FORMAT(p.date, '%M %Y') as month_name,
                       SUM(p.amount) as total_amount, 
                       COUNT(p.id) as transaction_count
                FROM {$this->table} p 
                LEFT JOIN students s ON p.student_id = s.id";
        
        $whereConditions = [];
        $params = [];
        
        // Add search term conditions
        if (!empty($searchTerm)) {
            $whereConditions[] = "(s.first_name LIKE :search_term1 OR 
                                  s.last_name LIKE :search_term2 OR 
                                  s.admission_no LIKE :search_term3 OR
                                  CONCAT(s.first_name, ' ', s.last_name) LIKE :search_term4 OR
                                  s.guardian_name LIKE :search_term5)";
            $params['search_term1'] = '%' . $searchTerm . '%';
            $params['search_term2'] = '%' . $searchTerm . '%';
            $params['search_term3'] = '%' . $searchTerm . '%';
            $params['search_term4'] = '%' . $searchTerm . '%';
            $params['search_term5'] = '%' . $searchTerm . '%';
        }
        
        // Add filter conditions
        if (!empty($filters['academic_year_id'])) {
            $whereConditions[] = "p.academic_year_id = :academic_year_id";
            $params['academic_year_id'] = $filters['academic_year_id'];
        }

        // Add class_id filter
        if (!empty($filters['class_id'])) {
            $whereConditions[] = "s.class_id = :class_id";
            $params['class_id'] = $filters['class_id'];
        }
        
        // Add date range filter conditions
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $whereConditions[] = "p.date BETWEEN :date_from AND :date_to";
            $params['date_from'] = $filters['date_from'];
            $params['date_to'] = $filters['date_to'];
        } elseif (!empty($filters['date_from'])) {
            $whereConditions[] = "p.date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        } elseif (!empty($filters['date_to'])) {
            $whereConditions[] = "p.date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        // Combine all conditions
        if (!empty($whereConditions)) {
            $whereClause = " WHERE " . implode(' AND ', $whereConditions);
            $sql .= $whereClause;
        }
        
        $sql .= " GROUP BY DATE_FORMAT(p.date, '%Y-%m') ORDER BY DATE_FORMAT(p.date, '%Y-%m') DESC LIMIT 12";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get termly report data
     */
    public function getTermlyReport($filters = [], $searchTerm = '')
    {
        $sql = "SELECT p.academic_year_id, ay.name as academic_year_name, p.term,
                       SUM(p.amount) as total_amount, 
                       COUNT(p.id) as transaction_count
                FROM {$this->table} p 
                LEFT JOIN students s ON p.student_id = s.id
                LEFT JOIN academic_years ay ON p.academic_year_id = ay.id";
        
        $whereConditions = [];
        $params = [];
        
        // Add search term conditions
        if (!empty($searchTerm)) {
            $whereConditions[] = "(s.first_name LIKE :search_term1 OR 
                                  s.last_name LIKE :search_term2 OR 
                                  s.admission_no LIKE :search_term3 OR
                                  CONCAT(s.first_name, ' ', s.last_name) LIKE :search_term4 OR
                                  s.guardian_name LIKE :search_term5)";
            $params['search_term1'] = '%' . $searchTerm . '%';
            $params['search_term2'] = '%' . $searchTerm . '%';
            $params['search_term3'] = '%' . $searchTerm . '%';
            $params['search_term4'] = '%' . $searchTerm . '%';
            $params['search_term5'] = '%' . $searchTerm . '%';
        }
        
        // Add filter conditions
        if (!empty($filters['academic_year_id'])) {
            $whereConditions[] = "p.academic_year_id = :academic_year_id";
            $params['academic_year_id'] = $filters['academic_year_id'];
        }

        // Add class_id filter
        if (!empty($filters['class_id'])) {
            $whereConditions[] = "s.class_id = :class_id";
            $params['class_id'] = $filters['class_id'];
        }
        
        // Add date range filter conditions
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $whereConditions[] = "p.date BETWEEN :date_from AND :date_to";
            $params['date_from'] = $filters['date_from'];
            $params['date_to'] = $filters['date_to'];
        } elseif (!empty($filters['date_from'])) {
            $whereConditions[] = "p.date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        } elseif (!empty($filters['date_to'])) {
            $whereConditions[] = "p.date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        // Combine all conditions
        if (!empty($whereConditions)) {
            $whereClause = " WHERE " . implode(' AND ', $whereConditions);
            $sql .= $whereClause;
        }
        
        $sql .= " GROUP BY p.academic_year_id, ay.name, p.term 
                  HAVING p.term IS NOT NULL AND p.term != ''
                  ORDER BY ay.name DESC, p.term ASC";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    /**
     * Get yearly report data
     */
    public function getYearlyReport($filters = [], $searchTerm = '')
    {
        $sql = "SELECT YEAR(p.date) as year, 
                       SUM(p.amount) as total_amount, 
                       COUNT(p.id) as transaction_count
                FROM {$this->table} p 
                LEFT JOIN students s ON p.student_id = s.id";
        
        $whereConditions = [];
        $params = [];
        
        // Add search term conditions
        if (!empty($searchTerm)) {
            $whereConditions[] = "(s.first_name LIKE :search_term1 OR 
                                  s.last_name LIKE :search_term2 OR 
                                  s.admission_no LIKE :search_term3 OR
                                  CONCAT(s.first_name, ' ', s.last_name) LIKE :search_term4 OR
                                  s.guardian_name LIKE :search_term5)";
            $params['search_term1'] = '%' . $searchTerm . '%';
            $params['search_term2'] = '%' . $searchTerm . '%';
            $params['search_term3'] = '%' . $searchTerm . '%';
            $params['search_term4'] = '%' . $searchTerm . '%';
            $params['search_term5'] = '%' . $searchTerm . '%';
        }
        
        // Add filter conditions
        if (!empty($filters['academic_year_id'])) {
            $whereConditions[] = "p.academic_year_id = :academic_year_id";
            $params['academic_year_id'] = $filters['academic_year_id'];
        }

        // Add class_id filter
        if (!empty($filters['class_id'])) {
            $whereConditions[] = "s.class_id = :class_id";
            $params['class_id'] = $filters['class_id'];
        }
        
        // Add date range filter conditions
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $whereConditions[] = "p.date BETWEEN :date_from AND :date_to";
            $params['date_from'] = $filters['date_from'];
            $params['date_to'] = $filters['date_to'];
        } elseif (!empty($filters['date_from'])) {
            $whereConditions[] = "p.date >= :date_from";
            $params['date_from'] = $filters['date_from'];
        } elseif (!empty($filters['date_to'])) {
            $whereConditions[] = "p.date <= :date_to";
            $params['date_to'] = $filters['date_to'];
        }
        
        // Combine all conditions
        if (!empty($whereConditions)) {
            $whereClause = " WHERE " . implode(' AND ', $whereConditions);
            $sql .= $whereClause;
        }
        
        $sql .= " GROUP BY YEAR(p.date) ORDER BY YEAR(p.date) DESC LIMIT 10";
        
        return $this->db->fetchAll($sql, $params);
    }
}