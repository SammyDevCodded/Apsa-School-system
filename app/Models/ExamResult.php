<?php
namespace App\Models;

use App\Core\Model;
use App\Models\GradingRule;

class ExamResult extends Model
{
    protected $table = 'exam_results';
    protected $fillable = [
        'exam_id',
        'student_id',
        'subject_id',
        'marks',
        'grade',
        'remark',
        'exam_marks',
        'classwork_marks',
        'final_marks'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllWithDetails()
    {
        $sql = "SELECT er.*, e.name as exam_name, e.date as exam_date, e.term as exam_term, 
                       s.first_name, s.last_name, s.admission_no, sub.name as subject_name,
                       c.name as class_name, c.level as class_level, ay.name as academic_year_name,
                       sc.name as student_class_name, sc.level as student_class_level
                FROM {$this->table} er
                LEFT JOIN exams e ON er.exam_id = e.id
                LEFT JOIN students s ON er.student_id = s.id
                LEFT JOIN subjects sub ON er.subject_id = sub.id
                LEFT JOIN classes c ON e.class_id = c.id
                LEFT JOIN classes sc ON s.class_id = sc.id
                LEFT JOIN academic_years ay ON e.academic_year_id = ay.id
                ORDER BY e.date DESC, s.last_name, s.first_name";
        return $this->db->fetchAll($sql);
    }

    public function getByExamId($examId)
    {
        $sql = "SELECT er.*, s.first_name, s.last_name, s.admission_no, sub.name as subject_name, c.name as class_name,
                       sc.name as student_class_name, sc.level as student_class_level
                FROM {$this->table} er
                LEFT JOIN students s ON er.student_id = s.id
                LEFT JOIN subjects sub ON er.subject_id = sub.id
                LEFT JOIN exams e ON er.exam_id = e.id
                LEFT JOIN classes c ON e.class_id = c.id
                LEFT JOIN classes sc ON s.class_id = sc.id
                WHERE er.exam_id = :exam_id
                ORDER BY s.last_name, s.first_name";
        return $this->db->fetchAll($sql, ['exam_id' => $examId]);
    }

    public function getByStudentId($studentId)
    {
        $sql = "SELECT er.*, e.name as exam_name, e.date as exam_date, sub.name as subject_name,
                       c.name as class_name, c.level as class_level
                FROM {$this->table} er
                LEFT JOIN exams e ON er.exam_id = e.id
                LEFT JOIN subjects sub ON er.subject_id = sub.id
                LEFT JOIN classes c ON e.class_id = c.id
                WHERE er.student_id = :student_id
                ORDER BY e.date DESC";
        return $this->db->fetchAll($sql, ['student_id' => $studentId]);
    }

    public function calculateGrade($marks)
    {
        if ($marks >= 90) return 'A+';
        if ($marks >= 80) return 'A';
        if ($marks >= 70) return 'B';
        if ($marks >= 60) return 'C';
        if ($marks >= 50) return 'D';
        return 'F';
    }
    
    public function calculateGradeWithScale($marks, $gradingScaleId)
    {
        // Get grading rules for the selected scale
        $gradingRuleModel = new GradingRule();
        $gradingRules = $gradingRuleModel->where('scale_id', $gradingScaleId);
        
        // Find the matching rule
        foreach ($gradingRules as $rule) {
            if ($marks >= $rule['min_score'] && $marks <= $rule['max_score']) {
                return [
                    'grade' => $rule['grade'],
                    'remark' => $rule['remark'] ?? ''
                ];
            }
        }
        
        // Default if no rule matches
        return [
            'grade' => 'F',
            'remark' => 'No matching grade rule found'
        ];
    }
    
    // Method to get distinct grades
    public function getDistinctGrades()
    {
        $sql = "SELECT DISTINCT grade FROM {$this->table} WHERE grade IS NOT NULL ORDER BY grade";
        $grades = $this->db->fetchAll($sql);
        return array_column($grades, 'grade');
    }
    
    // Method to check if a result already exists for a specific exam, student, and subject combination
    public function findByExamStudentSubject($examId, $studentId, $subjectId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE exam_id = :exam_id AND student_id = :student_id AND subject_id = :subject_id";
        return $this->db->fetchOne($sql, [
            'exam_id' => $examId,
            'student_id' => $studentId,
            'subject_id' => $subjectId
        ]);
    }
    
    // Method to search and filter exam results with pagination
    public function searchWithDetailsPaginated($searchTerm = '', $filters = [], $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        // Base query
        $sql = "SELECT er.*, e.name as exam_name, e.date as exam_date, e.term as exam_term, 
                       s.first_name, s.last_name, s.admission_no, sub.name as subject_name,
                       c.name as class_name, c.level as class_level, ay.name as academic_year_name,
                       sc.name as student_class_name, sc.level as student_class_level
                FROM {$this->table} er
                LEFT JOIN exams e ON er.exam_id = e.id
                LEFT JOIN students s ON er.student_id = s.id
                LEFT JOIN subjects sub ON er.subject_id = sub.id
                LEFT JOIN classes c ON e.class_id = c.id
                LEFT JOIN classes sc ON s.class_id = sc.id
                LEFT JOIN academic_years ay ON e.academic_year_id = ay.id";
        
        // Add search and filter conditions
        $conditions = [];
        $params = [];
        
        if (!empty($searchTerm)) {
            $conditions[] = "(s.first_name LIKE :search_term1 OR s.last_name LIKE :search_term2 OR s.admission_no LIKE :search_term3 OR e.name LIKE :search_term4)";
            $params['search_term1'] = "%{$searchTerm}%";
            $params['search_term2'] = "%{$searchTerm}%";
            $params['search_term3'] = "%{$searchTerm}%";
            $params['search_term4'] = "%{$searchTerm}%";
        }
        
        if (!empty($filters['exam_name'])) {
            $conditions[] = "e.name LIKE :exam_name";
            $params['exam_name'] = "%{$filters['exam_name']}%";
        }
        
        if (!empty($filters['student_name'])) {
            $conditions[] = "(s.first_name LIKE :student_name1 OR s.last_name LIKE :student_name2)";
            $params['student_name1'] = "%{$filters['student_name']}%";
            $params['student_name2'] = "%{$filters['student_name']}%";
        }
        
        if (!empty($filters['class_id'])) {
            // Filter by the student's actual class, not the exam's class
            $conditions[] = "s.class_id = :class_id";
            $params['class_id'] = $filters['class_id'];
        }
        
        if (!empty($filters['subject_id'])) {
            $conditions[] = "er.subject_id = :subject_id";
            $params['subject_id'] = $filters['subject_id'];
        }
        
        if (!empty($filters['grade'])) {
            $conditions[] = "er.grade = :grade";
            $params['grade'] = $filters['grade'];
        }
        
        // Add date range filter conditions
        if (!empty($filters['date_mode']) && $filters['date_mode'] === 'enabled') {
            if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
                $conditions[] = "DATE(e.date) BETWEEN :date_from AND :date_to";
                $params['date_from'] = $filters['date_from'];
                $params['date_to'] = $filters['date_to'];
            } elseif (!empty($filters['date_from'])) {
                $conditions[] = "DATE(e.date) >= :date_from";
                $params['date_from'] = $filters['date_from'];
            } elseif (!empty($filters['date_to'])) {
                $conditions[] = "DATE(e.date) <= :date_to";
                $params['date_to'] = $filters['date_to'];
            }
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        // Add ordering and pagination
        // Fix the parameter binding issue by using direct values for LIMIT and OFFSET
        $sql .= " ORDER BY e.date DESC, s.last_name, s.first_name LIMIT {$perPage} OFFSET {$offset}";
        
        $data = $this->db->fetchAll($sql, $params);
        
        // Get total count for pagination
        $countSql = "SELECT COUNT(*) as total FROM {$this->table} er
                     LEFT JOIN exams e ON er.exam_id = e.id
                     LEFT JOIN students s ON er.student_id = s.id
                     LEFT JOIN subjects sub ON er.subject_id = sub.id
                     LEFT JOIN classes c ON e.class_id = c.id
                     LEFT JOIN classes sc ON s.class_id = sc.id
                     LEFT JOIN academic_years ay ON e.academic_year_id = ay.id";
        
        // Apply the same filtering logic for the count query
        if (!empty($conditions)) {
            $countSql .= " WHERE " . implode(" AND ", $conditions);
        }
        
        
        // Use the same parameters for count query
        $total = $this->db->fetchOne($countSql, $params)['total'] ?? 0;
        $totalPages = ceil($total / $perPage);
        
        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages
        ];
    }
    
    // New method to get exam results by exam and class
    public function getByExamAndClass($examId, $classId) {
        $sql = "SELECT DISTINCT er.id, er.exam_id, er.student_id, er.subject_id, er.marks, er.grade, 
                       er.exam_marks, er.classwork_marks, er.final_marks,
                       s.first_name, s.last_name, s.admission_no, s.dob as date_of_birth, 
                       sub.name as subject_name,
                       COALESCE(er.marks, 0) as total_score,
                       (SELECT gr.remark FROM grading_rules gr WHERE er.grade = gr.grade LIMIT 1) as remark,
                       sc.name as student_class_name, sc.level as student_class_level
                FROM {$this->table} er
                LEFT JOIN students s ON er.student_id = s.id
                LEFT JOIN subjects sub ON er.subject_id = sub.id
                LEFT JOIN exams e ON er.exam_id = e.id
                LEFT JOIN classes sc ON s.class_id = sc.id
                WHERE er.exam_id = :exam_id AND s.class_id = :class_id
                ORDER BY s.last_name, s.first_name, sub.name";
        return $this->db->fetchAll($sql, [
            'exam_id' => $examId,
            'class_id' => $classId
        ]);
    }
    
    // New method to get exam results by exam, class, and subject for preventing double entry
    public function getByExamClassAndSubject($examId, $classId, $subjectId) {
        $sql = "SELECT er.*, er.exam_marks, er.classwork_marks, er.final_marks,
                       s.first_name, s.last_name, s.admission_no,
                       sc.name as student_class_name, sc.level as student_class_level
                FROM {$this->table} er
                LEFT JOIN students s ON er.student_id = s.id
                LEFT JOIN exams e ON er.exam_id = e.id
                LEFT JOIN classes sc ON s.class_id = sc.id
                WHERE er.exam_id = :exam_id AND s.class_id = :class_id AND er.subject_id = :subject_id
                ORDER BY s.last_name, s.first_name";
        return $this->db->fetchAll($sql, [
            'exam_id' => $examId,
            'class_id' => $classId,
            'subject_id' => $subjectId
        ]);
    }
    
    // New method to get exam results by exam and subject
    public function getByExamAndSubject($examId, $subjectId)
    {
        $sql = "SELECT er.*, s.first_name, s.last_name, s.admission_no, sub.name as subject_name, c.name as class_name,
                       sc.name as student_class_name, sc.level as student_class_level
                FROM {$this->table} er
                LEFT JOIN students s ON er.student_id = s.id
                LEFT JOIN subjects sub ON er.subject_id = sub.id
                LEFT JOIN exams e ON er.exam_id = e.id
                LEFT JOIN classes c ON e.class_id = c.id
                LEFT JOIN classes sc ON s.class_id = sc.id
                WHERE er.exam_id = :exam_id AND er.subject_id = :subject_id
                ORDER BY s.last_name, s.first_name";
        return $this->db->fetchAll($sql, [
            'exam_id' => $examId,
            'subject_id' => $subjectId
        ]);
    }
    
    // Advanced Analytics Methods

    /**
     * Get ranked results based on dynamic filters.
     * Supports filtering by academic_year, term, class, subject, exam.
     */
    public function getRankedResults($filters = [], $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $params = [];
        $whereClause = ['1=1'];

        // Base Joins
        $joins = "
            LEFT JOIN exams e ON er.exam_id = e.id
            LEFT JOIN students s ON er.student_id = s.id
            LEFT JOIN subjects sub ON er.subject_id = sub.id
            LEFT JOIN classes c ON e.class_id = c.id
            LEFT JOIN classes sc ON s.class_id = sc.id
            LEFT JOIN academic_years ay ON e.academic_year_id = ay.id
        ";

        // Filter Logic
        if (!empty($filters['academic_year_id'])) {
            $whereClause[] = "e.academic_year_id = :academic_year_id";
            $params['academic_year_id'] = $filters['academic_year_id'];
        }

        if (!empty($filters['term'])) {
            $whereClause[] = "e.term = :term";
            $params['term'] = $filters['term'];
        }

        if (!empty($filters['class_id'])) {
            $whereClause[] = "e.class_id = :class_id";
            $params['class_id'] = $filters['class_id'];
        }

        if (!empty($filters['subject_id'])) {
            $whereClause[] = "er.subject_id = :subject_id";
            $params['subject_id'] = $filters['subject_id'];
        }

        if (!empty($filters['exam_id'])) {
            if (is_array($filters['exam_id'])) {
                $ids = array_map('intval', $filters['exam_id']);
                $idsStr = implode(',', $ids);
                $whereClause[] = "er.exam_id IN ($idsStr)";
            } elseif (strpos($filters['exam_id'], ',') !== false) {
                $ids = array_map('intval', explode(',', $filters['exam_id']));
                $idsStr = implode(',', $ids);
                $whereClause[] = "er.exam_id IN ($idsStr)";
            } else {
                $whereClause[] = "er.exam_id = :exam_id";
                $params['exam_id'] = $filters['exam_id'];
            }
        }

        $whereSql = implode(' AND ', $whereClause);

        // Data Query
        $sql = "SELECT 
                    s.id as student_id,
                    s.first_name, 
                    s.last_name, 
                    s.admission_no,
                    sc.name as class_name,
                    COUNT(DISTINCT er.subject_id) as subjects_count,
                    SUM(er.marks) as total_score,
                    AVG(er.marks) as average_score,
                    GROUP_CONCAT(DISTINCT CONCAT(sub.name, ': ', ROUND(er.marks, 0)) ORDER BY sub.name SEPARATOR ', ') as subjects_taken
                FROM {$this->table} er
                {$joins}
                WHERE {$whereSql}
                GROUP BY s.id, s.first_name, s.last_name, s.admission_no, sc.name
                ORDER BY total_score DESC";
        
        if ($perPage > 0) {
            $sql .= " LIMIT $perPage OFFSET $offset";
        }

        $data = $this->db->fetchAll($sql, $params);

        // Count Query
        // For accurate count with GROUP BY, we need to wrap in a subquery or Count Distinct
        // COUNT(DISTINCT s.id) should work since we group by student
        $countSql = "SELECT COUNT(DISTINCT s.id) as total
                     FROM {$this->table} er
                     {$joins}
                     WHERE {$whereSql}";
        
        $total = $this->db->fetchOne($countSql, $params)['total'] ?? 0;
        $totalPages = ($perPage > 0) ? ceil($total / $perPage) : 1;

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages
        ];
    }

    /**
     * Get aggregated data for trend analysis / comparisons.
     * groups by the specified dimension (e.g., 'term', 'exam', 'academic_year')
     */
    public function getTrendData($filters = [], $dimension = 'exam', $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        $params = [];
        $whereClause = ['1=1'];

        // Similar Joins
        $joins = "
            LEFT JOIN exams e ON er.exam_id = e.id
            LEFT JOIN classes c ON e.class_id = c.id
            LEFT JOIN students s ON er.student_id = s.id
            LEFT JOIN classes sc ON s.class_id = sc.id
            LEFT JOIN subjects sub ON er.subject_id = sub.id
            LEFT JOIN academic_years ay ON e.academic_year_id = ay.id
        ";

        // Apply Filters
        if (!empty($filters['academic_year_id'])) {
            $whereClause[] = "e.academic_year_id = :academic_year_id";
            $params['academic_year_id'] = $filters['academic_year_id'];
        }
        if (!empty($filters['class_id'])) {
            $whereClause[] = "e.class_id = :class_id";
            $params['class_id'] = $filters['class_id'];
        }
        if (!empty($filters['subject_id'])) {
            $whereClause[] = "er.subject_id = :subject_id";
            $params['subject_id'] = $filters['subject_id'];
        }

        $whereSql = implode(' AND ', $whereClause);

        // Define Grouping
        $groupBy = '';
        $selectLabel = '';
        $countDimension = '';
        
        switch ($dimension) {
            case 'term':
                $groupBy = "e.term, e.academic_year_id, ay.name";
                $selectLabel = "CONCAT(COALESCE(ay.name, 'Unknown'), ' - ', COALESCE(e.term, 'Unknown')) as label";
                // For count, we need to count unique terms (year + term)
                // This is surprisingly complex in SQL standard without a subquery, but CONCAT is okay
                $countDimension = "CONCAT(e.academic_year_id, '-', e.term)"; 
                break;
            case 'academic_year':
                $groupBy = "e.academic_year_id, ay.name";
                $selectLabel = "COALESCE(ay.name, 'Unknown') as label";
                $countDimension = "e.academic_year_id";
                break;
            case 'exam':
            default:
                // Group by Name, Term, Year to aggregate multiple classes for the same exam
                $groupBy = "LOWER(TRIM(e.name)), LOWER(TRIM(e.term)), ay.name"; 
                $selectLabel = "COALESCE(TRIM(e.name), 'Unknown Exam') as label, GROUP_CONCAT(DISTINCT e.id) as exam_ids, COALESCE(ay.name, 'Unknown Year') as academic_year, COALESCE(e.term, 'Unknown Term') as term, GROUP_CONCAT(DISTINCT COALESCE(sc.name, c.name) ORDER BY COALESCE(sc.name, c.name) SEPARATOR ', ') as class_names";
                // For count, we need a unique identifier for this grouping. 
                // Since we group by name+term+year, it's tricky to count distinct efficiently without a subquery.
                // Let's use a subquery for the count.
                break;
        }

        $sql = "SELECT 
                    {$selectLabel},
                    COALESCE(AVG(er.marks), 0) as average_score,
                    COALESCE(MAX(er.marks), 0) as max_score,
                    COALESCE(MIN(er.marks), 0) as min_score,
                    COUNT(DISTINCT er.student_id) as student_count
                FROM {$this->table} er
                {$joins}
                WHERE {$whereSql}
                GROUP BY {$groupBy}
                ORDER BY MIN(e.date) ASC"; // Chronological order usually makes sense for trends

        if ($perPage > 0) {
            $sql .= " LIMIT $perPage OFFSET $offset";
        }

        $data = $this->db->fetchAll($sql, $params);

        // Count Query
        $countSql = "SELECT COUNT(*) as total FROM (
                        SELECT 1
                        FROM {$this->table} er
                        {$joins}
                        WHERE {$whereSql}
                        GROUP BY {$groupBy}
                    ) as count_table";

        $total = $this->db->fetchOne($countSql, $params)['total'] ?? 0;
        $totalPages = ($perPage > 0) ? ceil($total / $perPage) : 1;

        return [
            'data' => $data,
            'total' => $total,
            'page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages
        ];
    }
    
    /**
     * Get statistics for analytics dashboard
     */
    public function getStatistics()
    {
        $sql = "SELECT 
                    COUNT(DISTINCT exam_id) as total_exams,
                    COUNT(DISTINCT student_id) as total_students,
                    COUNT(DISTINCT subject_id) as total_subjects,
                    AVG(marks) as average_marks,
                    MAX(marks) as highest_marks,
                    MIN(marks) as lowest_marks
                FROM {$this->table}";
        return $this->db->fetchOne($sql);
    }

    /**
     * Get available filter options based on current selection.
     * Used for dynamic dropdowns in analytics.
     */
    public function getAvailableFilters($currentFilters = [])
    {
        $baseJoins = "
            JOIN exams e ON er.exam_id = e.id
            JOIN students s ON er.student_id = s.id
            JOIN subjects sub ON er.subject_id = sub.id
            JOIN classes c ON e.class_id = c.id
            JOIN academic_years ay ON e.academic_year_id = ay.id
        ";

        $buildWhere = function($excludeKey) use ($currentFilters) {
            $conditions = ['1=1'];
            $params = [];
            
            $keys = ['academic_year_id', 'term', 'class_id', 'subject_id', 'exam_id'];
            foreach ($keys as $key) {
                if ($key === $excludeKey) continue; // Don't filter a field by itself (usually) 
                // actually we DO want to filter a field by others, but not by itself to restrict the list if we want to change it?
                // Logic: If I selected Class A, I want the Class dropdown to still show Class B if I want to switch?
                // Usually YES. The filters typically constrain the *other* dropdowns.
                // But if I selected Class A and Subject Math, and I query for "Classes", I should see classes that have Subject Math.
                
                if (!empty($currentFilters[$key])) {
                     if ($key === 'term' || $key === 'academic_year_id') {
                         $conditions[] = "e.{$key} = :{$key}";
                     } elseif ($key === 'class_id') {
                         $conditions[] = "e.class_id = :class_id";
                     } elseif ($key === 'subject_id') {
                         $conditions[] = "er.subject_id = :subject_id";
                     } elseif ($key === 'exam_id') {
                         $conditions[] = "er.exam_id = :exam_id";
                     }
                     $params[$key] = $currentFilters[$key];
                }
            }
            return [$conditions, $params];
        };

        $results = [];

        // 1. Get Classes (filtered by others)
        list($conds, $params) = $buildWhere('class_id');
        $sql = "SELECT DISTINCT c.id, c.name, c.level 
                FROM {$this->table} er {$baseJoins} 
                WHERE " . implode(' AND ', $conds) . " ORDER BY c.level, c.name";
        $results['classes'] = $this->db->fetchAll($sql, $params);

        // 2. Get Subjects (filtered by others)
        list($conds, $params) = $buildWhere('subject_id');
        $sql = "SELECT DISTINCT sub.id, sub.name 
                FROM {$this->table} er {$baseJoins} 
                WHERE " . implode(' AND ', $conds) . " ORDER BY sub.name";
        $results['subjects'] = $this->db->fetchAll($sql, $params);

        // 3. Get Exams (filtered by others)
        list($conds, $params) = $buildWhere('exam_id');
        $sql = "SELECT DISTINCT e.id, e.name, e.date, e.description
                FROM {$this->table} er {$baseJoins} 
                WHERE " . implode(' AND ', $conds) . " ORDER BY e.date DESC";
        $results['exams'] = $this->db->fetchAll($sql, $params);

        // 4. Get Terms (filtered by others)
        list($conds, $params) = $buildWhere('term');
        $sql = "SELECT DISTINCT e.term 
                FROM {$this->table} er {$baseJoins} 
                WHERE " . implode(' AND ', $conds) . " ORDER BY e.term";
        $resultsTerms = $this->db->fetchAll($sql, $params);
        $results['terms'] = array_column($resultsTerms, 'term');

        // 5. Get Academic Years (filtered by others)
        list($conds, $params) = $buildWhere('academic_year_id');
        $sql = "SELECT DISTINCT ay.id, ay.name 
                FROM {$this->table} er {$baseJoins} 
                WHERE " . implode(' AND ', $conds) . " ORDER BY ay.start_date DESC";
        $results['academic_years'] = $this->db->fetchAll($sql, $params);

        return $results;
    }

    // Method to get detailed results for a student for specific exam IDs (e.g. for report card)
    public function getStudentExamResults($studentId, $examIds)
    {
        // ... existing code ... (keeping the previous method intact if needed or just appending)
        // Wait, replace_file_content replaces the chunk. I need to be careful.
        // The previous tool call showed lines 1-628, and the file ends at line 628 with '}'.
        // So I will insert before the closing brace or replace the end of the file correctly.
        
        // Handle array or comma-separated string
        if (is_string($examIds)) {
            $examIds = explode(',', $examIds);
        }
        
        $ids = implode(',', array_map('intval', $examIds));
        
        $sql = "SELECT er.*, sub.name as subject_name,
                       e.name as exam_name, e.term as exam_term, e.academic_year_id,
                       ay.name as academic_year_name, c.name as class_name
                FROM {$this->table} er
                LEFT JOIN subjects sub ON er.subject_id = sub.id
                LEFT JOIN exams e ON er.exam_id = e.id
                LEFT JOIN academic_years ay ON e.academic_year_id = ay.id
                LEFT JOIN classes c ON e.class_id = c.id
                WHERE er.student_id = :student_id
                AND er.exam_id IN ($ids)
                ORDER BY sub.name"; 
                
        return $this->db->fetchAll($sql, ['student_id' => $studentId]);
    }

    // New method for Staff Portal: Get detailed results for specific subjects
    public function getDetailedResultsBySubjects($subjectIds)
    {
        if (empty($subjectIds)) {
            return [];
        }

        // Create placeholders
        $placeholders = implode(',', array_fill(0, count($subjectIds), '?'));

        $sql = "SELECT er.*, 
                       s.first_name, s.last_name, s.admission_no,
                       e.name as exam_name, e.term, e.date as exam_date,
                       ay.name as academic_year_name,
                       sub.name as subject_name, sub.code as subject_code,
                       c.name as class_name
                FROM {$this->table} er
                JOIN exams e ON er.exam_id = e.id
                JOIN students s ON er.student_id = s.id
                JOIN subjects sub ON er.subject_id = sub.id
                LEFT JOIN academic_years ay ON e.academic_year_id = ay.id
                LEFT JOIN classes c ON e.class_id = c.id
                WHERE er.subject_id IN ($placeholders)
                ORDER BY ay.start_date DESC, e.term DESC, sub.name, s.last_name";

        return $this->db->fetchAll($sql, $subjectIds);
    }
}