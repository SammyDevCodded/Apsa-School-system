<?php
namespace App\Models;

use App\Core\Model;

class Subject extends Model
{
    protected $table = 'subjects';
    protected $fillable = [
        'name',
        'code',
        'class_id',
        'description'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllWithClass()
    {
        $sql = "SELECT s.*, c.name as class_name 
                FROM {$this->table} s 
                LEFT JOIN classes c ON s.class_id = c.id";
        return $this->db->fetchAll($sql);
    }
    
    public function getAllWithClassPaginated($search = '', $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        // Build search condition
        $searchCondition = '';
        $countParams = [];
        $dataParams = [];
        
        if (!empty($search)) {
            $searchCondition = "WHERE s.code LIKE ? OR s.name LIKE ? OR s.description LIKE ? OR c.name LIKE ?";
            $searchParam = "%{$search}%";
            $countParams = [$searchParam, $searchParam, $searchParam, $searchParam];
            $dataParams = [$searchParam, $searchParam, $searchParam, $searchParam];
        }
        
        // Get total count
        $countSql = "SELECT COUNT(*) as total 
                     FROM {$this->table} s 
                     LEFT JOIN classes c ON s.class_id = c.id 
                     {$searchCondition}";
        $totalCount = $this->db->fetchOne($countSql, $countParams);
        $total = $totalCount ? (int)$totalCount['total'] : 0;
        
        // Get paginated data
        $sql = "SELECT s.*, c.name as class_name 
                FROM {$this->table} s 
                LEFT JOIN classes c ON s.class_id = c.id 
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

    public function findByCode($code)
    {
        return $this->where('code', $code);
    }
    
    // Get subjects by class ID
    public function getByClassId($classId)
    {
        return $this->where('class_id', $classId);
    }
    
    // Get assigned subjects for a class based on class_subject_assignments
    public function getAssignedSubjectsByClass($classId)
    {
        $sql = "SELECT DISTINCT s.*, c.name as class_name 
                FROM subjects s
                JOIN class_subject_assignments csa ON s.id = csa.subject_id
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE csa.class_id = :class_id
                ORDER BY s.name";
        return $this->db->fetchAll($sql, ['class_id' => $classId]);
    }
    
    // Get all staff members who teach this subject
    public function getStaff($subjectId)
    {
        $sql = "SELECT s.* 
                FROM staff s
                JOIN staff_subjects ss ON s.id = ss.staff_id
                WHERE ss.subject_id = :subject_id";
        return $this->db->fetchAll($sql, ['subject_id' => $subjectId]);
    }
    
    // Get comprehensive subject details including class, staff, and student information
    public function getSubjectDetails($subjectId)
    {
        // Get subject with class information
        $sql = "SELECT s.*, c.name as class_name, c.id as class_id
                FROM subjects s
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE s.id = :subject_id";
        $subject = $this->db->fetchOne($sql, ['subject_id' => $subjectId]);
        
        if (!$subject) {
            return null;
        }
        
        // Get staff members teaching this subject
        $sql = "SELECT st.*
                FROM staff st
                JOIN staff_subjects ss ON st.id = ss.staff_id
                WHERE ss.subject_id = :subject_id";
        $subject['teachers'] = $this->db->fetchAll($sql, ['subject_id' => $subjectId]);
        
        // Get number of students taking this subject based on assignments
        $subject['student_count'] = $this->getStudentCountForSubject($subjectId, $subject['class_id']);
        
        // Get assigned classes and students for this subject
        $subject['assignments'] = $this->getSubjectAssignments($subjectId);
        
        return $subject;
    }
    
    // Get subject assignments (classes and students)
    public function getSubjectAssignments($subjectId)
    {
        $sql = "SELECT csa.*, c.name as class_name, s.first_name, s.last_name, s.admission_no
                FROM class_subject_assignments csa
                LEFT JOIN classes c ON csa.class_id = c.id
                LEFT JOIN students s ON csa.student_id = s.id
                WHERE csa.subject_id = :subject_id
                ORDER BY c.name, s.first_name, s.last_name";
        return $this->db->fetchAll($sql, ['subject_id' => $subjectId]);
    }
    
    // Get student count for a subject based on assignments
    public function getStudentCountForSubject($subjectId, $classId)
    {
        // First check if there are any class-wide assignments for this subject
        $sql = "SELECT COUNT(*) as count
                FROM class_subject_assignments 
                WHERE subject_id = :subject_id AND class_id = :class_id AND student_id IS NULL";
        $classWideAssignment = $this->db->fetchOne($sql, ['subject_id' => $subjectId, 'class_id' => $classId]);
        
        if ($classWideAssignment && $classWideAssignment['count'] > 0) {
            // If subject is assigned to entire class, count all students in the class
            $sql = "SELECT COUNT(s.id) as student_count
                    FROM students s
                    WHERE s.class_id = :class_id";
            $studentCount = $this->db->fetchOne($sql, ['class_id' => $classId]);
            return $studentCount ? $studentCount['student_count'] : 0;
        } else {
            // If subject is assigned to specific students only, count those students
            $sql = "SELECT COUNT(DISTINCT csa.student_id) as student_count
                    FROM class_subject_assignments csa
                    WHERE csa.subject_id = :subject_id AND csa.class_id = :class_id AND csa.student_id IS NOT NULL";
            $studentCount = $this->db->fetchOne($sql, ['subject_id' => $subjectId, 'class_id' => $classId]);
            return $studentCount ? $studentCount['student_count'] : 0;
        }
    }
}