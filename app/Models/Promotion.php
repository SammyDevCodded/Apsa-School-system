<?php
namespace App\Models;

use App\Core\Model;

class Promotion extends Model
{
    protected $table = 'student_promotions';
    protected $fillable = [
        'student_id',
        'from_class_id',
        'to_class_id',
        'academic_year_id',
        'promotion_date',
        'promoted_by',
        'remarks'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Promote students from one class to another
     * @param array $studentIds
     * @param int $fromClassId
     * @param int $toClassId
     * @param int $academicYearId
     * @param int $promotedBy
     * @param string $promotionDate
     * @param string $remarks
     * @return bool
     */
    public function promoteStudents($studentIds, $fromClassId, $toClassId, $academicYearId, $promotedBy, $promotionDate, $remarks = '')
    {
        try {
            $studentModel = new Student();
            
            // First, record all promotions
            $promotionsToCreate = [];
            foreach ($studentIds as $studentId) {
                $promotionsToCreate[] = [
                    'student_id' => $studentId,
                    'from_class_id' => $fromClassId,
                    'to_class_id' => $toClassId,
                    'academic_year_id' => $academicYearId,
                    'promotion_date' => $promotionDate,
                    'promoted_by' => $promotedBy,
                    'remarks' => $remarks
                ];
            }
            
            // Insert all promotion records at once for better performance
            $placeholders = str_repeat('(?, ?, ?, ?, ?, ?, ?), ', count($promotionsToCreate));
            $placeholders = rtrim($placeholders, ', ');
            
            $sql = "INSERT INTO {$this->table} (student_id, from_class_id, to_class_id, academic_year_id, promotion_date, promoted_by, remarks) VALUES {$placeholders}";
            
            $values = [];
            foreach ($promotionsToCreate as $promotion) {
                $values[] = $promotion['student_id'];
                $values[] = $promotion['from_class_id'];
                $values[] = $promotion['to_class_id'];
                $values[] = $promotion['academic_year_id'];
                $values[] = $promotion['promotion_date'];
                $values[] = $promotion['promoted_by'];
                $values[] = $promotion['remarks'];
            }
            
            $stmt = $this->db->getConnection()->prepare($sql);
            $stmt->execute($values);
            
            // Then update all students' classes
            $studentIdsStr = implode(',', array_map('intval', $studentIds));
            $updateSql = "UPDATE students SET class_id = ? WHERE id IN ({$studentIdsStr})";
            $this->db->execute($updateSql, [$toClassId]);
            
            return true;
            
        } catch (\Exception $e) {
            error_log("Promotion failed: " . $e->getMessage());
            return false;
        }
    }

    /**
     * Get promotion history for a student
     * @param int $studentId
     * @return array
     */
    public function getStudentPromotionHistory($studentId)
    {
        $sql = "SELECT sp.*, 
                       fc.name as from_class_name,
                       tc.name as to_class_name,
                       ay.name as academic_year_name,
                       u.username as promoted_by_username
                FROM {$this->table} sp
                LEFT JOIN classes fc ON sp.from_class_id = fc.id
                LEFT JOIN classes tc ON sp.to_class_id = tc.id
                LEFT JOIN academic_years ay ON sp.academic_year_id = ay.id
                LEFT JOIN users u ON sp.promoted_by = u.id
                WHERE sp.student_id = :student_id
                ORDER BY sp.promotion_date DESC, sp.created_at DESC";
        
        return $this->db->fetchAll($sql, ['student_id' => $studentId]);
    }

    /**
     * Get all promotions for a specific academic year
     * @param int $academicYearId
     * @return array
     */
    public function getPromotionsByAcademicYear($academicYearId)
    {
        $sql = "SELECT sp.*, 
                       s.first_name, s.last_name, s.admission_no,
                       fc.name as from_class_name,
                       tc.name as to_class_name,
                       ay.name as academic_year_name,
                       u.username as promoted_by_username
                FROM {$this->table} sp
                LEFT JOIN students s ON sp.student_id = s.id
                LEFT JOIN classes fc ON sp.from_class_id = fc.id
                LEFT JOIN classes tc ON sp.to_class_id = tc.id
                LEFT JOIN academic_years ay ON sp.academic_year_id = ay.id
                LEFT JOIN users u ON sp.promoted_by = u.id
                WHERE sp.academic_year_id = :academic_year_id
                ORDER BY sp.promotion_date DESC, sp.created_at DESC";
        
        return $this->db->fetchAll($sql, ['academic_year_id' => $academicYearId]);
    }

    /**
     * Get promotions by date range
     * @param string $startDate
     * @param string $endDate
     * @return array
     */
    public function getPromotionsByDateRange($startDate, $endDate)
    {
        $sql = "SELECT sp.*, 
                       s.first_name, s.last_name, s.admission_no,
                       fc.name as from_class_name,
                       tc.name as to_class_name,
                       ay.name as academic_year_name,
                       u.username as promoted_by_username
                FROM {$this->table} sp
                LEFT JOIN students s ON sp.student_id = s.id
                LEFT JOIN classes fc ON sp.from_class_id = fc.id
                LEFT JOIN classes tc ON sp.to_class_id = tc.id
                LEFT JOIN academic_years ay ON sp.academic_year_id = ay.id
                LEFT JOIN users u ON sp.promoted_by = u.id
                WHERE sp.promotion_date BETWEEN :start_date AND :end_date
                ORDER BY sp.promotion_date DESC, sp.created_at DESC";
        
        return $this->db->fetchAll($sql, [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }

    /**
     * Get promotion statistics
     * @param int|null $academicYearId
     * @return array
     */
    public function getPromotionStatistics($academicYearId = null)
    {
        $sql = "SELECT 
                    COUNT(*) as total_promotions,
                    COUNT(DISTINCT student_id) as unique_students_promoted,
                    COUNT(DISTINCT from_class_id) as classes_promoted_from,
                    COUNT(DISTINCT to_class_id) as classes_promoted_to";
        
        $params = [];
        
        if ($academicYearId) {
            $sql .= " FROM {$this->table} WHERE academic_year_id = :academic_year_id";
            $params['academic_year_id'] = $academicYearId;
        } else {
            $sql .= " FROM {$this->table}";
        }
        
        $stats = $this->db->fetchOne($sql, $params);
        
        // Get promotions by class pairs
        $classSql = "SELECT 
                        fc.name as from_class,
                        tc.name as to_class,
                        COUNT(*) as promotion_count";
        
        if ($academicYearId) {
            $classSql .= " FROM {$this->table} sp
                          LEFT JOIN classes fc ON sp.from_class_id = fc.id
                          LEFT JOIN classes tc ON sp.to_class_id = tc.id
                          WHERE sp.academic_year_id = :academic_year_id
                          GROUP BY sp.from_class_id, sp.to_class_id
                          ORDER BY promotion_count DESC";
        } else {
            $classSql .= " FROM {$this->table} sp
                          LEFT JOIN classes fc ON sp.from_class_id = fc.id
                          LEFT JOIN classes tc ON sp.to_class_id = tc.id
                          GROUP BY sp.from_class_id, sp.to_class_id
                          ORDER BY promotion_count DESC";
        }
        
        $stats['promotions_by_class'] = $this->db->fetchAll($classSql, $params);
        
        return $stats;
    }

    /**
     * Check if student can be promoted (not already promoted this academic year)
     * @param int $studentId
     * @param int $academicYearId
     * @return bool
     */
    public function canStudentBePromoted($studentId, $academicYearId)
    {
        $sql = "SELECT COUNT(*) as count 
                FROM {$this->table} 
                WHERE student_id = :student_id 
                AND academic_year_id = :academic_year_id";
        
        $result = $this->db->fetchOne($sql, [
            'student_id' => $studentId,
            'academic_year_id' => $academicYearId
        ]);
        
        return $result['count'] == 0;
    }

    /**
     * Get recent promotions (last 30 days)
     * @return array
     */
    public function getRecentPromotions($limit = 10)
    {
        $sql = "SELECT sp.*, 
                       s.first_name, s.last_name, s.admission_no,
                       fc.name as from_class_name,
                       tc.name as to_class_name,
                       ay.name as academic_year_name,
                       u.username as promoted_by_username
                FROM {$this->table} sp
                LEFT JOIN students s ON sp.student_id = s.id
                LEFT JOIN classes fc ON sp.from_class_id = fc.id
                LEFT JOIN classes tc ON sp.to_class_id = tc.id
                LEFT JOIN academic_years ay ON sp.academic_year_id = ay.id
                LEFT JOIN users u ON sp.promoted_by = u.id
                WHERE sp.promotion_date >= DATE_SUB(NOW(), INTERVAL 30 DAY)
                ORDER BY sp.promotion_date DESC, sp.created_at DESC
                LIMIT :limit";
        
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }
    
    /**
     * Get promotion statistics for a specific class
     * @param int $classId
     * @param int|null $academicYearId
     * @return array
     */
    public function getClassPromotionStats($classId, $academicYearId = null)
    {
        $stats = [
            'students_promoted_out' => 0,
            'students_promoted_in' => 0,
            'net_movement' => 0,
            'promotion_history' => [],
            'by_term' => []
        ];
        
        // Get students promoted FROM this class
        $outSql = "SELECT sp.*, 
                          s.first_name, s.last_name, s.admission_no,
                          tc.name as to_class_name,
                          ay.name as academic_year_name,
                          ay.term as academic_term,
                          u.username as promoted_by_username
                   FROM {$this->table} sp
                   LEFT JOIN students s ON sp.student_id = s.id
                   LEFT JOIN classes tc ON sp.to_class_id = tc.id
                   LEFT JOIN academic_years ay ON sp.academic_year_id = ay.id
                   LEFT JOIN users u ON sp.promoted_by = u.id
                   WHERE sp.from_class_id = :class_id";
        
        $outParams = ['class_id' => $classId];
        
        if ($academicYearId) {
            $outSql .= " AND sp.academic_year_id = :academic_year_id";
            $outParams['academic_year_id'] = $academicYearId;
        }
        
        $outSql .= " ORDER BY sp.promotion_date DESC, sp.created_at DESC";
        
        $promotedOut = $this->db->fetchAll($outSql, $outParams);
        $stats['students_promoted_out'] = count($promotedOut);
        
        // Get students promoted TO this class
        $inSql = "SELECT sp.*, 
                         s.first_name, s.last_name, s.admission_no,
                         fc.name as from_class_name,
                         ay.name as academic_year_name,
                         ay.term as academic_term,
                         u.username as promoted_by_username
                  FROM {$this->table} sp
                  LEFT JOIN students s ON sp.student_id = s.id
                  LEFT JOIN classes fc ON sp.from_class_id = fc.id
                  LEFT JOIN academic_years ay ON sp.academic_year_id = ay.id
                  LEFT JOIN users u ON sp.promoted_by = u.id
                  WHERE sp.to_class_id = :class_id";
        
        $inParams = ['class_id' => $classId];
        
        if ($academicYearId) {
            $inSql .= " AND sp.academic_year_id = :academic_year_id";
            $inParams['academic_year_id'] = $academicYearId;
        }
        
        $inSql .= " ORDER BY sp.promotion_date DESC, sp.created_at DESC";
        
        $promotedIn = $this->db->fetchAll($inSql, $inParams);
        $stats['students_promoted_in'] = count($promotedIn);
        
        // Calculate net movement
        $stats['net_movement'] = $stats['students_promoted_in'] - $stats['students_promoted_out'];
        
        // Combine and sort promotion history
        $stats['promotion_history'] = array_merge($promotedOut, $promotedIn);
        usort($stats['promotion_history'], function($a, $b) {
            return strcmp($b['promotion_date'], $a['promotion_date']) ?: strcmp($b['created_at'], $a['created_at']);
        });
        
        // Group by term if academic year is specified
        if ($academicYearId) {
            $termStats = [];
            foreach ($stats['promotion_history'] as $record) {
                $term = $record['academic_term'] ?? 'Unknown';
                if (!isset($termStats[$term])) {
                    $termStats[$term] = [
                        'promoted_out' => 0,
                        'promoted_in' => 0,
                        'net_movement' => 0,
                        'records' => []
                    ];
                }
                
                $termStats[$term]['records'][] = $record;
                if ($record['from_class_id'] == $classId) {
                    $termStats[$term]['promoted_out']++;
                } else {
                    $termStats[$term]['promoted_in']++;
                }
                
                $termStats[$term]['net_movement'] = $termStats[$term]['promoted_in'] - $termStats[$term]['promoted_out'];
            }
            $stats['by_term'] = $termStats;
        }
        
        return $stats;
    }
}