<?php
namespace App\Models;

use App\Core\Model;
use App\Helpers\IdGeneratorHelper;

class Student extends Model
{
    protected $table = 'students';
    protected $fillable = [
        'admission_no',
        'first_name',
        'last_name',
        'dob',
        'gender',
        'class_id',
        'guardian_name',
        'guardian_phone',
        'address',
        'profile_picture',
        'student_category',
        'student_category_details',
        'admission_date',
        'academic_year_id',
        'medical_info'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllWithClass()
    {
        $sql = "SELECT s.*, c.name as class_name, ay.name as academic_year_name
                FROM {$this->table} s 
                LEFT JOIN classes c ON s.class_id = c.id
                LEFT JOIN academic_years ay ON s.academic_year_id = ay.id
                ORDER BY s.created_at DESC";
        return $this->db->fetchAll($sql);
    }

    public function getByIdWithClass($id)
    {
        $sql = "SELECT s.*, c.name as class_name, c.level as class_level, ay.name as academic_year_name
                FROM {$this->table} s 
                LEFT JOIN classes c ON s.class_id = c.id
                LEFT JOIN academic_years ay ON s.academic_year_id = ay.id
                WHERE s.id = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    public function getByClassId($classId)
    {
        $sql = "SELECT s.*, c.name as class_name 
                FROM {$this->table} s 
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE s.class_id = :class_id
                ORDER BY s.last_name, s.first_name";
        return $this->db->fetchAll($sql, ['class_id' => $classId]);
    }

    public function isDuplicate($admissionNo, $firstName, $lastName, $dob)
    {
        $sql = "SELECT id FROM {$this->table} 
                WHERE admission_no = :admission_no 
                OR (first_name = :first_name AND last_name = :last_name AND dob = :dob)
                LIMIT 1";
                
        $params = [
            'admission_no' => $admissionNo,
            'first_name' => $firstName,
            'last_name' => $lastName,
            'dob' => $dob
        ];
        
        $result = $this->db->fetchOne($sql, $params);
        return $result !== false;
    }

    
    public function getCountByClassId($classId)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} 
                WHERE class_id = :class_id";
        $result = $this->db->fetchOne($sql, ['class_id' => $classId]);
        return $result ? (int)$result['count'] : 0;
    }
    
    public function getByClassIdPaginated($classId, $page = 1, $perPage = 10)
    {
        $sql = "SELECT s.*, c.name as class_name 
                FROM {$this->table} s 
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE s.class_id = :class_id";
        
        $countSql = "SELECT COUNT(*) as count 
                     FROM {$this->table} s 
                     WHERE s.class_id = :class_id";
        
        $orderBy = " ORDER BY s.last_name, s.first_name";
        
        // Add pagination
        $offset = ($page - 1) * $perPage;
        $paginatedSql = $sql . $orderBy . " LIMIT :limit OFFSET :offset";
        
        // Parameters for paginated query
        $params = [
            'class_id' => $classId,
            'limit' => (int)$perPage,
            'offset' => (int)$offset
        ];
        
        // Parameters for count query
        $countParams = ['class_id' => $classId];
        
        // Get total count
        $countResult = $this->db->fetchOne($countSql, $countParams);
        $totalCount = $countResult ? (int)$countResult['count'] : 0;
        
        // Get paginated results
        $results = $this->db->fetchAll($paginatedSql, $params);
        
        return [
            'data' => $results,
            'pagination' => [
                'current_page' => (int)$page,
                'per_page' => (int)$perPage,
                'total' => $totalCount,
                'total_pages' => $totalCount > 0 ? (int)ceil($totalCount / $perPage) : 1
            ]
        ];
    }
    
    // Get students by class with more details for academic reports
    public function getStudentsByClass($classId)
    {
        $sql = "SELECT s.*, c.name as class_name, c.level as class_level
                FROM {$this->table} s 
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE s.class_id = :class_id
                ORDER BY s.last_name, s.first_name";
        return $this->db->fetchAll($sql, ['class_id' => $classId]);
    }
    
    public function searchWithClass($searchTerm = '', $filters = [])
    {
        $sql = "SELECT s.*, c.name as class_name 
                FROM {$this->table} s 
                LEFT JOIN classes c ON s.class_id = c.id";
        
        $whereConditions = [];
        $params = [];
        
        // Add search term conditions
        if (!empty($searchTerm)) {
            $whereConditions[] = "(s.admission_no LIKE :search_term1 OR 
                                  s.first_name LIKE :search_term2 OR 
                                  s.last_name LIKE :search_term3 OR 
                                  CONCAT(s.first_name, ' ', s.last_name) LIKE :search_term4)";
            $params['search_term1'] = '%' . $searchTerm . '%';
            $params['search_term2'] = '%' . $searchTerm . '%';
            $params['search_term3'] = '%' . $searchTerm . '%';
            $params['search_term4'] = '%' . $searchTerm . '%';
        }
        
        // Add filter conditions
        if (!empty($filters['admission_no'])) {
            $whereConditions[] = "s.admission_no LIKE :admission_no";
            $params['admission_no'] = '%' . $filters['admission_no'] . '%';
        }
        
        if (!empty($filters['name'])) {
            $whereConditions[] = "(s.first_name LIKE :name1 OR s.last_name LIKE :name2 OR CONCAT(s.first_name, ' ', s.last_name) LIKE :name3)";
            $params['name1'] = '%' . $filters['name'] . '%';
            $params['name2'] = '%' . $filters['name'] . '%';
            $params['name3'] = '%' . $filters['name'] . '%';
        }
        
        if (!empty($filters['class_id'])) {
            $whereConditions[] = "s.class_id = :class_id";
            $params['class_id'] = $filters['class_id'];
        }
        
        if (!empty($filters['category'])) {
            $whereConditions[] = "s.student_category = :category";
            $params['category'] = $filters['category'];
        }
        
        if (!empty($filters['guardian'])) {
            $whereConditions[] = "s.guardian_name LIKE :guardian";
            $params['guardian'] = '%' . $filters['guardian'] . '%';
        }
        
        // Add date range filter conditions (same as in searchWithClassPaginated)
        if (!empty($filters['date_mode']) && $filters['date_mode'] === 'enabled') {
            if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
                $whereConditions[] = "DATE(s.created_at) BETWEEN :date_from AND :date_to";
                $params['date_from'] = $filters['date_from'];
                $params['date_to'] = $filters['date_to'];
            } elseif (!empty($filters['date_from'])) {
                $whereConditions[] = "DATE(s.created_at) >= :date_from";
                $params['date_from'] = $filters['date_from'];
            } elseif (!empty($filters['date_to'])) {
                $whereConditions[] = "DATE(s.created_at) <= :date_to";
                $params['date_to'] = $filters['date_to'];
            }
        }
        
        // Combine all conditions
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(' AND ', $whereConditions);
        }
        
        $sql .= " ORDER BY s.created_at DESC";
        
        return $this->db->fetchAll($sql, $params);
    }

    public function searchWithClassPaginated($searchTerm = '', $filters = [], $page = 1, $perPage = 10)
    {
        $sql = "SELECT s.*, c.name as class_name 
                FROM {$this->table} s 
                LEFT JOIN classes c ON s.class_id = c.id";
        
        $countSql = "SELECT COUNT(*) as count 
                     FROM {$this->table} s 
                     LEFT JOIN classes c ON s.class_id = c.id";
        
        $whereConditions = [];
        $countWhereConditions = [];
        $params = [];
        $countParams = [];
        
        // Add search term conditions
        if (!empty($searchTerm)) {
            $whereConditions[] = "(s.admission_no LIKE :search_term1 OR 
                                  s.first_name LIKE :search_term2 OR 
                                  s.last_name LIKE :search_term3 OR 
                                  CONCAT(s.first_name, ' ', s.last_name) LIKE :search_term4)";
            $countWhereConditions[] = "(s.admission_no LIKE :search_term_count1 OR 
                                      s.first_name LIKE :search_term_count2 OR 
                                      s.last_name LIKE :search_term_count3 OR 
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
        if (!empty($filters['admission_no'])) {
            $whereConditions[] = "s.admission_no LIKE :admission_no";
            $countWhereConditions[] = "s.admission_no LIKE :admission_no_count";
            $params['admission_no'] = '%' . $filters['admission_no'] . '%';
            $countParams['admission_no_count'] = '%' . $filters['admission_no'] . '%';
        }
        
        if (!empty($filters['name'])) {
            $whereConditions[] = "(s.first_name LIKE :name1 OR s.last_name LIKE :name2 OR CONCAT(s.first_name, ' ', s.last_name) LIKE :name3)";
            $countWhereConditions[] = "(s.first_name LIKE :name_count1 OR s.last_name LIKE :name_count2 OR CONCAT(s.first_name, ' ', s.last_name) LIKE :name_count3)";
            $params['name1'] = '%' . $filters['name'] . '%';
            $params['name2'] = '%' . $filters['name'] . '%';
            $params['name3'] = '%' . $filters['name'] . '%';
            $countParams['name_count1'] = '%' . $filters['name'] . '%';
            $countParams['name_count2'] = '%' . $filters['name'] . '%';
            $countParams['name_count3'] = '%' . $filters['name'] . '%';
        }
        
        if (!empty($filters['class_id'])) {
            $whereConditions[] = "s.class_id = :class_id";
            $countWhereConditions[] = "s.class_id = :class_id_count";
            $params['class_id'] = $filters['class_id'];
            $countParams['class_id_count'] = $filters['class_id'];
        }
        
        if (!empty($filters['category'])) {
            $whereConditions[] = "s.student_category = :category";
            $countWhereConditions[] = "s.student_category = :category_count";
            $params['category'] = $filters['category'];
            $countParams['category_count'] = $filters['category'];
        }
        
        if (!empty($filters['guardian'])) {
            $whereConditions[] = "s.guardian_name LIKE :guardian";
            $countWhereConditions[] = "s.guardian_name LIKE :guardian_count";
            $params['guardian'] = '%' . $filters['guardian'] . '%';
            $countParams['guardian_count'] = '%' . $filters['guardian'] . '%';
        }
        
        // Add date range filter conditions
        if (!empty($filters['date_mode']) && $filters['date_mode'] === 'enabled') {
            if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
                $whereConditions[] = "DATE(s.created_at) BETWEEN :date_from AND :date_to";
                $countWhereConditions[] = "DATE(s.created_at) BETWEEN :date_from_count AND :date_to_count";
                $params['date_from'] = $filters['date_from'];
                $params['date_to'] = $filters['date_to'];
                $countParams['date_from_count'] = $filters['date_from'];
                $countParams['date_to_count'] = $filters['date_to'];
            } elseif (!empty($filters['date_from'])) {
                $whereConditions[] = "DATE(s.created_at) >= :date_from";
                $countWhereConditions[] = "DATE(s.created_at) >= :date_from_count";
                $params['date_from'] = $filters['date_from'];
                $countParams['date_from_count'] = $filters['date_from'];
            } elseif (!empty($filters['date_to'])) {
                $whereConditions[] = "DATE(s.created_at) <= :date_to";
                $countWhereConditions[] = "DATE(s.created_at) <= :date_to_count";
                $params['date_to'] = $filters['date_to'];
                $countParams['date_to_count'] = $filters['date_to'];
            }
        }
        
        // Combine all conditions
        if (!empty($whereConditions)) {
            $whereClause = " WHERE " . implode(' AND ', $whereConditions);
            $countWhereClause = " WHERE " . implode(' AND ', $countWhereConditions);
            $sql .= $whereClause;
            $countSql .= $countWhereClause;
        }
        
        $sql .= " ORDER BY s.created_at DESC";
        
        // Add pagination
        $offset = ($page - 1) * $perPage;
        $sql .= " LIMIT :limit OFFSET :offset";
        
        // Add limit and offset to params (not to countParams)
        $params['limit'] = (int)$perPage;
        $params['offset'] = (int)$offset;
        
        // Get total count
        $countResult = $this->db->fetchOne($countSql, $countParams);
        $totalCount = $countResult ? (int)$countResult['count'] : 0;
        
        // Get paginated results
        $results = $this->db->fetchAll($sql, $params);
        
        return [
            'data' => $results,
            'total' => $totalCount,
            'page' => (int)$page,
            'per_page' => (int)$perPage,
            'total_pages' => $totalCount > 0 ? (int)ceil($totalCount / $perPage) : 1
        ];
    }
    
    public function getAcademicRecords($studentId, $academicYearId = null)
    {
        $sql = "SELECT er.*, e.name as exam_name, e.term, e.academic_year_id, ay.name as academic_year_name, s.name as subject_name, s.code as subject_code
                FROM exam_results er
                JOIN exams e ON er.exam_id = e.id
                JOIN subjects s ON er.subject_id = s.id
                LEFT JOIN academic_years ay ON e.academic_year_id = ay.id
                WHERE er.student_id = :student_id";
        
        $params = ['student_id' => $studentId];
        
        if ($academicYearId) {
            $sql .= " AND e.academic_year_id = :academic_year_id";
            $params['academic_year_id'] = $academicYearId;
        }
        
        $sql .= " ORDER BY ay.name DESC, e.term, s.name";
        
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getFinancialRecords($studentId)
    {
        // Get all fees assigned to this student through fee_assignments table
        $sql = "SELECT f.*, c.name as class_name,
                       fa.custom_amount, fa.billing_description,
                       COALESCE(fa.custom_amount, f.amount) as effective_amount
                FROM fees f
                LEFT JOIN classes c ON f.class_id = c.id
                INNER JOIN fee_assignments fa ON f.id = fa.fee_id
                WHERE fa.student_id = :student_id AND fa.status = 'active'
                ORDER BY f.type, f.name";
        
        $fees = $this->db->fetchAll($sql, ['student_id' => $studentId]);
        
        // Get all payments made by the student with academic year and term information
        $sql = "SELECT p.*, ay.name as academic_year_name, p.term
                FROM payments p
                LEFT JOIN academic_years ay ON p.academic_year_id = ay.id
                WHERE p.student_id = :student_id
                ORDER BY ay.name DESC, p.term, p.date DESC";
        
        $payments = $this->db->fetchAll($sql, ['student_id' => $studentId]);
        
        // Calculate payment status for each fee using effective_amount (custom or standard)
        foreach ($fees as &$fee) {
            $paidAmount = 0;
            foreach ($payments as $payment) {
                if ($payment['fee_id'] == $fee['id']) {
                    $paidAmount += $payment['amount'];
                }
            }
            $effectiveAmount = floatval($fee['effective_amount']);
            $fee['paid_amount'] = $paidAmount;
            // Keep original amount for reference, use effective_amount for balance/status
            $fee['amount'] = $effectiveAmount;
            $fee['balance'] = $effectiveAmount - $paidAmount;
            $fee['status'] = $paidAmount >= $effectiveAmount ? 'fully_paid' : ($paidAmount > 0 ? 'partly_paid' : 'pending');
        }
        
        // Group payments by academic year and term
        $groupedPayments = [];
        foreach ($payments as $payment) {
            $academicYear = !empty($payment['academic_year_name']) ? $payment['academic_year_name'] : 'Unknown Year';
            $term = !empty($payment['term']) ? $payment['term'] : 'Unknown Term';
            
            if (!isset($groupedPayments[$academicYear])) {
                $groupedPayments[$academicYear] = [];
            }
            
            if (!isset($groupedPayments[$academicYear][$term])) {
                $groupedPayments[$academicYear][$term] = [];
            }
            
            $groupedPayments[$academicYear][$term][] = $payment;
        }
        
        // Calculate total payments
        $totalPayments = array_sum(array_column($payments, 'amount'));
        
        return [
            'fees' => $fees,
            'payments' => $payments,
            'grouped_payments' => $groupedPayments,
            'total_payments' => $totalPayments
        ];
    }
    
    public function findByAdmissionNo($admissionNo)
    {
        $sql = "SELECT * FROM {$this->table} WHERE admission_no = :admission_no";
        return $this->db->fetchAll($sql, ['admission_no' => $admissionNo]);
    }
    
    // Get student count by class
    public function getCountByClass()
    {
        $sql = "SELECT c.name as class_name, COUNT(s.id) as student_count
                FROM classes c
                LEFT JOIN students s ON c.id = s.class_id
                GROUP BY c.id, c.name
                ORDER BY c.name";
        return $this->db->fetchAll($sql);
    }
    
    // Get student statistics
    public function getStatistics()
    {
        $sql = "SELECT 
                    COUNT(*) as total_students,
                    COUNT(CASE WHEN gender = 'Male' THEN 1 END) as male_students,
                    COUNT(CASE WHEN gender = 'Female' THEN 1 END) as female_students,
                    COUNT(CASE WHEN student_category = 'regular_day' THEN 1 END) as day_students,
                    COUNT(CASE WHEN student_category = 'regular_boarding' THEN 1 END) as boarding_students,
                    COUNT(CASE WHEN student_category = 'international' THEN 1 END) as international_students
                FROM {$this->table}";
        return $this->db->fetchOne($sql);
    }
    
    /**
     * Get students who are assigned to fee structures
     */
    public function getStudentsWithFeeAssignments()
    {
        $sql = "SELECT s.*, c.name as class_name, fa.fee_id, f.type as fee_type, f.name as fee_name
                FROM {$this->table} s
                LEFT JOIN classes c ON s.class_id = c.id
                LEFT JOIN fee_assignments fa ON s.id = fa.student_id
                LEFT JOIN fees f ON fa.fee_id = f.id
                WHERE fa.student_id IS NOT NULL
                ORDER BY s.last_name, s.first_name";
        
        $students = $this->db->fetchAll($sql);
        
        // Group fee assignments by student
        $groupedStudents = [];
        foreach ($students as $student) {
            $studentId = $student['id'];
            if (!isset($groupedStudents[$studentId])) {
                $groupedStudents[$studentId] = [
                    'id' => $student['id'],
                    'first_name' => $student['first_name'],
                    'last_name' => $student['last_name'],
                    'admission_no' => $student['admission_no'],
                    'class_id' => $student['class_id'],
                    'class_name' => $student['class_name'],
                    'fee_assignments' => []
                ];
            }
            
            // Add fee assignment if it exists
            if ($student['fee_id']) {
                $groupedStudents[$studentId]['fee_assignments'][] = [
                    'fee_id' => $student['fee_id'],
                    'fee_type' => $student['fee_type'],
                    'fee_name' => $student['fee_name']
                ];
            }
        }
        
        return array_values($groupedStudents);
    }
    
    /**
     * Get classes that have students with fee assignments
     */
    public function getClasssWithStudentsWithFees()
    {
        $sql = "SELECT c.id, c.name, COUNT(DISTINCT s.id) as student_count
                FROM classes c
                LEFT JOIN students s ON c.id = s.class_id
                LEFT JOIN fee_assignments fa ON s.id = fa.student_id
                WHERE fa.student_id IS NOT NULL
                GROUP BY c.id, c.name
                ORDER BY c.name";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get distinct fee types
     */
    public function getFeeTypes()
    {
        $sql = "SELECT DISTINCT f.type
                FROM fees f
                LEFT JOIN fee_assignments fa ON f.id = fa.fee_id
                WHERE fa.fee_id IS NOT NULL
                ORDER BY f.type";
        $types = $this->db->fetchAll($sql);
        
        // Format for dropdown
        $formattedTypes = [];
        foreach ($types as $type) {
            $formattedTypes[] = [
                'type' => $type['type']
            ];
        }
        
        return $formattedTypes;
    }
    
    /**
     * Get all students with class and fee assignment information
     */
    public function getAllWithClassAndFeeAssignments()
    {
        $sql = "SELECT s.*, c.name as class_name, fa.fee_id, f.type as fee_type, f.name as fee_name
                FROM {$this->table} s
                LEFT JOIN classes c ON s.class_id = c.id
                LEFT JOIN fee_assignments fa ON s.id = fa.student_id
                LEFT JOIN fees f ON fa.fee_id = f.id
                ORDER BY s.last_name, s.first_name";
        
        $students = $this->db->fetchAll($sql);
        
        // Group fee assignments by student
        $groupedStudents = [];
        foreach ($students as $student) {
            $studentId = $student['id'];
            if (!isset($groupedStudents[$studentId])) {
                $groupedStudents[$studentId] = [
                    'id' => $student['id'],
                    'first_name' => $student['first_name'],
                    'last_name' => $student['last_name'],
                    'admission_no' => $student['admission_no'],
                    'class_id' => $student['class_id'],
                    'class_name' => $student['class_name'],
                    'fee_assignments' => []
                ];
            }
            
            // Add fee assignment if it exists
            if ($student['fee_id']) {
                $groupedStudents[$studentId]['fee_assignments'][] = [
                    'fee_id' => $student['fee_id'],
                    'fee_type' => $student['fee_type'],
                    'fee_name' => $student['fee_name']
                ];
            }
        }
        
        return array_values($groupedStudents);
    }
    
    /**
     * Get all fee types in the system
     */
    public function getAllFeeTypes()
    {
        $sql = "SELECT DISTINCT type FROM fees ORDER BY type";
        $types = $this->db->fetchAll($sql);
        
        // Format for dropdown
        $formattedTypes = [];
        foreach ($types as $type) {
            $formattedTypes[] = [
                'type' => $type['type']
            ];
        }
        
        return $formattedTypes;
    }
    
    /**
     * Get fee allocations for a specific student
     */
    public function getStudentFeeAllocations($studentId)
    {
        // Get fee allocations for a student with payment details
        $sql = "SELECT fa.*, f.name as fee_name, f.amount as fee_amount, 
                       COALESCE(SUM(p.amount), 0) as paid_amount
                FROM fee_assignments fa
                JOIN fees f ON fa.fee_id = f.id
                LEFT JOIN payments p ON fa.id = p.fee_assignment_id
                WHERE fa.student_id = :student_id
                GROUP BY fa.id, f.name, f.amount
                ORDER BY f.name";
        
        try {
            $allocations = $this->db->fetchAll($sql, ['student_id' => $studentId]);
            
            // Calculate balance for each allocation
            foreach ($allocations as &$allocation) {
                $amount = floatval($allocation['amount']);
                $paid_amount = floatval($allocation['paid_amount'] ?? 0);
                $allocation['paid_amount'] = $paid_amount;
                $allocation['balance'] = $amount - $paid_amount;
            }
            
            return $allocations;
        } catch (\Exception $e) {
            error_log("Error fetching student fee allocations: " . $e->getMessage());
            return [];
        }
    }
    
    public function getTotalCount()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $result = $this->db->fetchOne($sql);
        return $result ? (int)$result['count'] : 0;
    }
}