<?php
namespace App\Models;

use App\Core\Model;

class FeeAssignment extends Model
{
    protected $table = 'fee_assignments';
    protected $fillable = [
        'fee_id',
        'student_id',
        'assigned_date',
        'status'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Get all fee assignments with fee and student details
     */
    public function getAllWithDetails()
    {
        $sql = "SELECT fa.*, f.name as fee_name, f.amount as fee_amount, f.type as fee_type,
                       s.first_name, s.last_name, s.admission_no, c.name as class_name
                FROM {$this->table} fa
                LEFT JOIN fees f ON fa.fee_id = f.id
                LEFT JOIN students s ON fa.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                ORDER BY fa.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    /**
     * Get fee assignments for a specific fee
     */
    public function getByFeeId($feeId)
    {
        $sql = "SELECT fa.*, s.first_name, s.last_name, s.admission_no, s.class_id, c.name as class_name, f.amount as fee_amount, f.type as fee_type, f.name as fee_name, fa.created_at as assigned_date
                FROM {$this->table} fa
                LEFT JOIN students s ON fa.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                LEFT JOIN fees f ON fa.fee_id = f.id
                WHERE fa.fee_id = :fee_id
                ORDER BY s.last_name, s.first_name";
        return $this->db->fetchAll($sql, ['fee_id' => $feeId]);
    }

    /**
     * Get fee assignments for a specific student
     */
    public function getByStudentId($studentId)
    {
        $sql = "SELECT fa.*, f.name as fee_name, f.amount as fee_amount, f.type as fee_type
                FROM {$this->table} fa
                LEFT JOIN fees f ON fa.fee_id = f.id
                WHERE fa.student_id = :student_id
                ORDER BY fa.created_at DESC";
        return $this->db->fetchAll($sql, ['student_id' => $studentId]);
    }

    /**
     * Check if a student is already assigned to a fee
     */
    public function isStudentAssigned($feeId, $studentId)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE fee_id = :fee_id AND student_id = :student_id";
        $result = $this->db->fetchOne($sql, ['fee_id' => $feeId, 'student_id' => $studentId]);
        return ($result['count'] ?? 0) > 0;
    }

    /**
     * Assign multiple students to a fee
     */
    public function assignStudents($feeId, $studentIds)
    {
        $assignedCount = 0;
        
        foreach ($studentIds as $studentId) {
            // Check if already assigned
            if (!$this->isStudentAssigned($feeId, $studentId)) {
                $data = [
                    'fee_id' => $feeId,
                    'student_id' => $studentId,
                    'assigned_date' => date('Y-m-d')
                ];
                
                if ($this->create($data)) {
                    $assignedCount++;
                }
            }
        }
        
        return $assignedCount;
    }

    /**
     * Remove student assignments from a fee
     */
    public function removeStudents($feeId, $studentIds)
    {
        $removedCount = 0;
        
        foreach ($studentIds as $studentId) {
            $sql = "DELETE FROM {$this->table} 
                    WHERE fee_id = :fee_id AND student_id = :student_id";
            
            if ($this->db->execute($sql, ['fee_id' => $feeId, 'student_id' => $studentId])) {
                $removedCount++;
            }
        }
        
        return $removedCount;
    }
    
    /**
     * Get distinct classes associated with a fee through student assignments
     * This method returns all classes that have students assigned to the fee
     */
    public function getClassesByFeeId($feeId)
    {
        $sql = "SELECT DISTINCT c.id, c.name
                FROM {$this->table} fa
                LEFT JOIN students s ON fa.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE fa.fee_id = :fee_id
                ORDER BY c.name";
        return $this->db->fetchAll($sql, ['fee_id' => $feeId]);
    }
    
    /**
     * Get the earliest assigned classes for a fee
     * This attempts to get classes from the earliest student assignments
     * which should correspond to the original fee creation
     */
    public function getOriginalClassesByFeeId($feeId)
    {
        // Get the earliest assignment date for this fee
        $earliestDateSql = "SELECT MIN(created_at) as earliest_date 
                           FROM {$this->table} 
                           WHERE fee_id = :fee_id";
        $earliestResult = $this->db->fetchOne($earliestDateSql, ['fee_id' => $feeId]);
        
        if (!$earliestResult || !$earliestResult['earliest_date']) {
            return [];
        }
        
        // Get all assignments from the earliest date (within a small time window)
        $earliestDate = $earliestResult['earliest_date'];
        $sql = "SELECT DISTINCT c.id, c.name
                FROM {$this->table} fa
                LEFT JOIN students s ON fa.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE fa.fee_id = :fee_id
                AND fa.created_at <= DATE_ADD(:earliest_date, INTERVAL 1 SECOND)
                ORDER BY c.name";
        
        return $this->db->fetchAll($sql, [
            'fee_id' => $feeId,
            'earliest_date' => $earliestDate
        ]);
    }
    
    /**
     * Get count of students assigned to a fee
     */
    /**
     * Get count of students assigned to a fee
     */
    public function getCountByFeeId($feeId)
    {
        $sql = "SELECT COUNT(*) as count
                FROM {$this->table}
                WHERE fee_id = :fee_id";
        $result = $this->db->fetchOne($sql, ['fee_id' => $feeId]);
        return $result['count'] ?? 0;
    }

    /**
     * Get count of students assigned to a fee who belong to a specific class
     */
    public function getCountByFeeAndClass($feeId, $classId)
    {
        $sql = "SELECT COUNT(fa.id) as count
                FROM {$this->table} fa
                JOIN students s ON fa.student_id = s.id
                WHERE fa.fee_id = :fee_id AND s.class_id = :class_id";
        $result = $this->db->fetchOne($sql, [
            'fee_id' => $feeId,
            'class_id' => $classId
        ]);
        return $result['count'] ?? 0;
    }

    /**
     * Get all student bills (assignments) with details and filters
     */
    public function getAllStudentBills($page = 1, $perPage = 10, $filters = [], $searchTerm = '')
    {
        $offset = ($page - 1) * $perPage;
        $params = [];
        $whereClause = "WHERE 1=1";

        // Filter by Class
        if (!empty($filters['class_id'])) {
            $whereClause .= " AND s.class_id = :class_id";
            $params['class_id'] = $filters['class_id'];
        }

        // Filter by Academic Year (via Fee)
        if (!empty($filters['academic_year_id'])) {
            $whereClause .= " AND f.academic_year_id = :academic_year_id";
            $params['academic_year_id'] = $filters['academic_year_id'];
        }

        // Filter by Term
        if (!empty($filters['term'])) {
            $whereClause .= " AND f.term = :term";
            $params['term'] = $filters['term'];
        }

        // Search Term
        if (!empty($searchTerm)) {
            $whereClause .= " AND (
                s.first_name LIKE :search1 
                OR s.last_name LIKE :search2 
                OR s.admission_no LIKE :search3 
                OR f.name LIKE :search4
            )";
            $params['search1'] = "%{$searchTerm}%";
            $params['search2'] = "%{$searchTerm}%";
            $params['search3'] = "%{$searchTerm}%";
            $params['search4'] = "%{$searchTerm}%";
        }

        // Main Query
        $sql = "SELECT fa.*, 
                       s.first_name, s.last_name, s.admission_no, c.name as class_name,
                       f.name as fee_name, f.amount as fee_amount, f.type as fee_type,
                       (SELECT COALESCE(SUM(p.amount), 0) FROM payments p WHERE p.student_id = fa.student_id AND p.fee_id = fa.fee_id) as total_paid
                FROM {$this->table} fa
                JOIN students s ON fa.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                JOIN fees f ON fa.fee_id = f.id
                $whereClause
                ORDER BY fa.created_at DESC
                LIMIT " . (int)$offset . ", " . (int)$perPage;

        // Count Query
        $countSql = "SELECT COUNT(*) as total
                     FROM {$this->table} fa
                     JOIN students s ON fa.student_id = s.id
                     JOIN fees f ON fa.fee_id = f.id
                     $whereClause";

        // Execute Count
        $totalResult = $this->db->fetchOne($countSql, $params);
        $total = $totalResult['total'] ?? 0;

        // Execute Main Query
        // Using db->fetchAll wrapper instead of direct PDO access
        $data = $this->db->fetchAll($sql, $params);

        return [
            'data' => $data,
            'total' => $total,
            'per_page' => $perPage,
            'current_page' => $page,
            'total_pages' => ceil($total / $perPage)
        ];
    }
}