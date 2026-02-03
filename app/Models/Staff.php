<?php
namespace App\Models;

use App\Core\Model;

class Staff extends Model
{
    protected $table = 'staff';
    protected $fillable = [
        'user_id',
        'employee_id',
        'first_name',
        'last_name',
        'position',
        'department',
        'email',
        'phone',
        'hire_date',
        'salary',
        'status'
        // Note: subject_id is removed from fillable since we're using the many-to-many relationship
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllWithUser()
    {
        $sql = "SELECT s.*, u.username 
                FROM {$this->table} s 
                LEFT JOIN users u ON s.user_id = u.id";
        return $this->db->fetchAll($sql);
    }
    
    public function getAllWithUserPaginated($search = '', $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        // Build search condition
        $searchCondition = '';
        $countParams = [];
        $dataParams = [];
        
        if (!empty($search)) {
            $searchCondition = "WHERE s.employee_id LIKE ? OR s.first_name LIKE ? OR s.last_name LIKE ? OR s.position LIKE ? OR s.department LIKE ? OR s.email LIKE ?";
            $searchParam = "%{$search}%";
            $countParams = [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam];
            $dataParams = [$searchParam, $searchParam, $searchParam, $searchParam, $searchParam, $searchParam];
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total 
                     FROM {$this->table} s 
                     LEFT JOIN users u ON s.user_id = u.id 
                     {$searchCondition}";
        $totalCount = $this->db->fetchOne($countSql, $countParams);
        $total = $totalCount ? (int)$totalCount['total'] : 0;
        
        // Get paginated data
        $sql = "SELECT s.*, u.username 
                FROM {$this->table} s 
                LEFT JOIN users u ON s.user_id = u.id 
                {$searchCondition}
                ORDER BY s.created_at DESC
                LIMIT ? OFFSET ?";
        
        $dataParams[] = (int)$perPage;
        $dataParams[] = (int)$offset;
        
        $data = $this->db->fetchAll($sql, $dataParams);
        
        return [
            'data' => $data,
            'pagination' => [
                'current_page' => (int)$page,
                'per_page' => (int)$perPage,
                'total' => $total,
                'total_pages' => $total > 0 ? (int)ceil($total / $perPage) : 1
            ]
        ];
    }

    public function findByEmployeeId($employeeId)
    {
        return $this->where('employee_id', $employeeId);
    }
    
    // Get all subjects assigned to this staff member
    public function getSubjects($staffId)
    {
        $sql = "SELECT s.*, c.name as class_name
                FROM subjects s
                JOIN staff_subjects ss ON s.id = ss.subject_id
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE ss.staff_id = :staff_id";
        return $this->db->fetchAll($sql, ['staff_id' => $staffId]);
    }
    
    // Assign subjects to this staff member
    public function assignSubjects($staffId, $subjectIds)
    {
        // First, remove all existing subject assignments for this staff member
        $deleteSql = "DELETE FROM staff_subjects WHERE staff_id = :staff_id";
        $this->db->execute($deleteSql, ['staff_id' => $staffId]);
        
        // Then, add the new subject assignments
        if (!empty($subjectIds)) {
            $insertSql = "INSERT INTO staff_subjects (staff_id, subject_id) VALUES (:staff_id, :subject_id)";
            $stmt = $this->db->getConnection()->prepare($insertSql);
            
            foreach ($subjectIds as $subjectId) {
                $stmt->execute([
                    'staff_id' => $staffId,
                    'subject_id' => $subjectId
                ]);
            }
        }
        
        return true;
    }
    
    // Get staff members who teach a specific subject
    public function getBySubject($subjectId)
    {
        $sql = "SELECT s.* 
                FROM staff s
                JOIN staff_subjects ss ON s.id = ss.staff_id
                WHERE ss.subject_id = :subject_id";
        return $this->db->fetchAll($sql, ['subject_id' => $subjectId]);
    }
    
    // Get teachers (staff with teaching positions) who teach a specific subject
    public function getTeachersBySubject($subjectId)
    {
        $sql = "SELECT s.* 
                FROM staff s
                JOIN staff_subjects ss ON s.id = ss.staff_id
                WHERE ss.subject_id = :subject_id 
                AND (s.position LIKE '%Teacher%' OR s.position LIKE '%Instructor%' OR s.position LIKE '%Professor%' OR s.department = 'Academics')";
        return $this->db->fetchAll($sql, ['subject_id' => $subjectId]);
    }
    
    // Get all teaching staff (staff with teaching positions or assigned subjects)
    public function getTeachingStaff()
    {
        $sql = "SELECT DISTINCT s.* 
                FROM staff s
                LEFT JOIN staff_subjects ss ON s.id = ss.staff_id
                WHERE (s.position LIKE '%Teacher%' OR s.position LIKE '%Instructor%' OR s.position LIKE '%Professor%' OR s.department = 'Academics') 
                OR ss.staff_id IS NOT NULL
                ORDER BY s.first_name, s.last_name";
        return $this->db->fetchAll($sql);
    }
    
    public function getTotalCount()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $result = $this->db->fetchOne($sql);
        return $result ? (int)$result['count'] : 0;
    }
}