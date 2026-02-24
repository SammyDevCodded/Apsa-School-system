<?php
namespace App\Models;

use App\Core\Model;

class Exam extends Model
{
    protected $table = 'exams';
    protected $fillable = [
        'name',
        'term',
        'class_id',
        'academic_year_id',
        'date',
        'description',
        'grading_scale_id',
        'has_classwork',
        'classwork_percentage'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllWithClass()
    {
        $sql = "SELECT e.*, c.name as class_name 
                FROM {$this->table} e 
                LEFT JOIN classes c ON e.class_id = c.id
                ORDER BY e.date DESC";
        return $this->db->fetchAll($sql);
    }
    
    public function getAllWithClassAndAcademicYear()
    {
        $sql = "SELECT e.*, c.name as class_name, ay.name as academic_year_name
                FROM {$this->table} e 
                LEFT JOIN classes c ON e.class_id = c.id
                LEFT JOIN academic_years ay ON e.academic_year_id = ay.id
                ORDER BY e.date DESC";
        return $this->db->fetchAll($sql);
    }

    public function getByClassId($classId)
    {
        $sql = "SELECT e.*, c.name as class_name, ay.name as academic_year_name 
                FROM {$this->table} e 
                LEFT JOIN classes c ON e.class_id = c.id
                LEFT JOIN academic_years ay ON e.academic_year_id = ay.id
                WHERE e.class_id = :class_id
                ORDER BY e.date DESC";
        return $this->db->fetchAll($sql, ['class_id' => $classId]);
    }

    public function getUpcomingExams($limit = 5)
    {
        $sql = "SELECT e.*, c.name as class_name 
                FROM {$this->table} e 
                LEFT JOIN classes c ON e.class_id = c.id
                WHERE e.date >= CURDATE()
                ORDER BY e.date ASC
                LIMIT :limit";
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }
    
    // Get all classes assigned to this exam (for many-to-many relationship)
    public function getClasses($examId)
    {
        $sql = "SELECT c.* 
                FROM classes c
                JOIN exam_classes ec ON c.id = ec.class_id
                WHERE ec.exam_id = :exam_id
                ORDER BY c.name";
        return $this->db->fetchAll($sql, ['exam_id' => $examId]);
    }
    
    // Get classes by exam with more details for academic reports
    public function getClassesByExam($examId)
    {
        $sql = "SELECT c.*, ay.name as academic_year_name
                FROM classes c
                JOIN exam_classes ec ON c.id = ec.class_id
                LEFT JOIN exams e ON ec.exam_id = e.id
                LEFT JOIN academic_years ay ON e.academic_year_id = ay.id
                WHERE ec.exam_id = :exam_id
                GROUP BY c.id
                ORDER BY c.name";
        return $this->db->fetchAll($sql, ['exam_id' => $examId]);
    }
    
    // Assign classes to this exam (for many-to-many relationship)
    public function assignClasses($examId, $classIds)
    {
        // First, remove all existing class assignments for this exam
        $deleteSql = "DELETE FROM exam_classes WHERE exam_id = :exam_id";
        $this->db->execute($deleteSql, ['exam_id' => $examId]);
        
        // Then, add the new class assignments
        if (!empty($classIds) && is_array($classIds)) {
            $insertSql = "INSERT INTO exam_classes (exam_id, class_id) VALUES (:exam_id, :class_id)";
            $stmt = $this->db->getConnection()->prepare($insertSql);
            
            foreach ($classIds as $classId) {
                $stmt->execute([
                    'exam_id' => $examId,
                    'class_id' => $classId
                ]);
            }
        }
        
        return true;
    }
    
    // Get exams with all assigned classes (for display purposes)
    public function getAllWithClasses()
    {
        $sql = "SELECT e.*, 
                       c.name as primary_class_name,
                       GROUP_CONCAT(c2.name ORDER BY c2.name) as assigned_classes
                FROM {$this->table} e 
                LEFT JOIN classes c ON e.class_id = c.id
                LEFT JOIN exam_classes ec ON e.id = ec.exam_id
                LEFT JOIN classes c2 ON ec.class_id = c2.id
                GROUP BY e.id, c.name
                ORDER BY e.date DESC";
        return $this->db->fetchAll($sql);
    }
    
    // Get grading scale for this exam
    public function getGradingScale($examId)
    {
        $sql = "SELECT gs.* 
                FROM grading_scales gs
                JOIN exams e ON gs.id = e.grading_scale_id
                WHERE e.id = :exam_id";
        return $this->db->fetchOne($sql, ['exam_id' => $examId]);
    }
    
    // Get grading rules for this exam's grading scale
    public function getGradingRulesForExam($examId)
    {
        $sql = "SELECT gr.*
                FROM grading_rules gr
                JOIN exams e ON gr.scale_id = e.grading_scale_id
                WHERE e.id = :exam_id
                ORDER BY gr.min_score DESC";
        return $this->db->fetchAll($sql, ['exam_id' => $examId]);
    }
    
    // Get exams with class and academic year information with pagination and search
    public function getAllWithClassAndAcademicYearPaginated($filters = [], $page = 1, $perPage = 10, $searchTerm = '')
    {
        $offset = ($page - 1) * $perPage;
        
        // Base query
        $sql = "SELECT e.*, c.name as class_name, ay.name as academic_year_name
                FROM {$this->table} e 
                LEFT JOIN classes c ON e.class_id = c.id
                LEFT JOIN academic_years ay ON e.academic_year_id = ay.id";
        
        // Count query
        $countSql = "SELECT COUNT(*) as total
                     FROM {$this->table} e 
                     LEFT JOIN classes c ON e.class_id = c.id
                     LEFT JOIN academic_years ay ON e.academic_year_id = ay.id";
        
        // Build conditions
        $conditions = [];
        $params = [];
        
        // Add search condition
        if (!empty($searchTerm)) {
            $conditions[] = "(e.name LIKE :search_term OR c.name LIKE :search_term2)";
            $params['search_term'] = "%{$searchTerm}%";
            $params['search_term2'] = "%{$searchTerm}%";
        }
        
        // Add filter conditions
        if (!empty($filters['class_id'])) {
            $conditions[] = "e.class_id = :class_id";
            $params['class_id'] = $filters['class_id'];
        }
        
        if (!empty($filters['term'])) {
            $conditions[] = "e.term = :term";
            $params['term'] = $filters['term'];
        }
        
        if (!empty($filters['academic_year_id'])) {
            $conditions[] = "e.academic_year_id = :academic_year_id";
            $params['academic_year_id'] = $filters['academic_year_id'];
        }
        
        // Apply conditions
        if (!empty($conditions)) {
            $whereClause = " WHERE " . implode(" AND ", $conditions);
            $sql .= $whereClause;
            $countSql .= $whereClause;
        }
        
        // Add ordering and pagination
        $sql .= " ORDER BY e.date DESC LIMIT :limit OFFSET :offset";
        
        // Add pagination parameters
        $params['limit'] = (int)$perPage;
        $params['offset'] = (int)$offset;
        
        // Execute queries
        $data = $this->db->fetchAll($sql, $params);
        $total = $this->db->fetchOne($countSql, array_diff_key($params, ['limit' => '', 'offset' => '']))['total'] ?? 0;
        
        $totalPages = ceil($total / $perPage);
        
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages
        ];
    }
    public function getDistinctTerms()
    {
        $sql = "SELECT DISTINCT term FROM {$this->table} WHERE term IS NOT NULL AND term != '' ORDER BY term";
        $results = $this->db->fetchAll($sql);
        return array_column($results, 'term');
    }

    public function getBySubjects($subjectIds, $limit = 5)
    {
        if (empty($subjectIds)) {
            return [];
        }
        
        // Create placeholders for IN clause
        $placeholders = implode(',', array_fill(0, count($subjectIds), '?'));
        
        $sql = "SELECT DISTINCT e.*, c.name as class_name, ay.name as academic_year_name
                FROM {$this->table} e 
                LEFT JOIN classes c ON e.class_id = c.id
                LEFT JOIN academic_years ay ON e.academic_year_id = ay.id
                JOIN exam_results er ON e.id = er.exam_id
                WHERE er.subject_id IN ($placeholders)
                ORDER BY e.date DESC
                LIMIT ?";
                
        // Merge subject IDs with limit for parameters
        $params = array_merge($subjectIds, [$limit]);
        
        return $this->db->fetchAll($sql, $params);
    }
}