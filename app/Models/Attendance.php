<?php
namespace App\Models;

use App\Core\Model;

class Attendance extends Model
{
    protected $table = 'attendance';
    protected $fillable = [
        'student_id',
        'date',
        'status',
        'remarks'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllWithStudent()
    {
        $sql = "SELECT a.*, s.first_name, s.last_name, s.admission_no 
                FROM {$this->table} a 
                LEFT JOIN students s ON a.student_id = s.id
                ORDER BY a.date DESC";
        return $this->db->fetchAll($sql);
    }

    public function getByStudentId($studentId)
    {
        $sql = "SELECT a.*, s.first_name, s.last_name, s.admission_no 
                FROM {$this->table} a 
                LEFT JOIN students s ON a.student_id = s.id
                WHERE a.student_id = :student_id
                ORDER BY a.date DESC";
        return $this->db->fetchAll($sql, ['student_id' => $studentId]);
    }

    public function getByDate($date)
    {
        $sql = "SELECT a.*, s.first_name, s.last_name, s.admission_no, c.name as class_name
                FROM {$this->table} a 
                LEFT JOIN students s ON a.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE a.date = :date
                ORDER BY c.name, s.last_name, s.first_name";
        return $this->db->fetchAll($sql, ['date' => $date]);
    }

    public function getAttendanceSummary($startDate, $endDate)
    {
        $sql = "SELECT 
                    s.id as student_id,
                    s.first_name,
                    s.last_name,
                    s.admission_no,
                    c.name as class_name,
                    COUNT(CASE WHEN a.status = 'present' THEN 1 END) as present,
                    COUNT(CASE WHEN a.status = 'absent' THEN 1 END) as absent,
                    COUNT(CASE WHEN a.status = 'late' THEN 1 END) as late,
                    COUNT(a.id) as total
                FROM students s
                LEFT JOIN attendance a ON s.id = a.student_id 
                    AND a.date BETWEEN :start_date AND :end_date
                LEFT JOIN classes c ON s.class_id = c.id
                GROUP BY s.id, s.first_name, s.last_name, s.admission_no, c.name
                ORDER BY c.name, s.last_name, s.first_name";
        return $this->db->fetchAll($sql, [
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }
    
    public function getAttendanceSummaryWithFilters($startDate, $endDate, $searchTerm = '', $classId = '')
    {
        $sql = "SELECT 
                    s.id as student_id,
                    s.first_name,
                    s.last_name,
                    s.admission_no,
                    c.name as class_name,
                    COUNT(CASE WHEN a.status = 'present' THEN 1 END) as present,
                    COUNT(CASE WHEN a.status = 'absent' THEN 1 END) as absent,
                    COUNT(CASE WHEN a.status = 'late' THEN 1 END) as late,
                    COUNT(a.id) as total
                FROM students s
                LEFT JOIN attendance a ON s.id = a.student_id 
                    AND a.date BETWEEN :start_date AND :end_date
                LEFT JOIN classes c ON s.class_id = c.id";
        
        $params = [
            'start_date' => $startDate,
            'end_date' => $endDate
        ];
        
        $whereConditions = [];
        
        // Add search term conditions
        if (!empty($searchTerm)) {
            $whereConditions[] = "(s.first_name LIKE :search_term_first OR 
                                  s.last_name LIKE :search_term_last OR 
                                  CONCAT(s.first_name, ' ', s.last_name) LIKE :search_term_concat OR
                                  s.admission_no LIKE :search_term_admission)";
            $params['search_term_first'] = '%' . $searchTerm . '%';
            $params['search_term_last'] = '%' . $searchTerm . '%';
            $params['search_term_concat'] = '%' . $searchTerm . '%';
            $params['search_term_admission'] = '%' . $searchTerm . '%';
        }
        
        // Add class filter
        if (!empty($classId)) {
            $whereConditions[] = "s.class_id = :class_id";
            $params['class_id'] = $classId;
        }
        
        // Combine all conditions
        if (!empty($whereConditions)) {
            $sql .= " WHERE " . implode(' AND ', $whereConditions);
        }
        
        $sql .= " GROUP BY s.id, s.first_name, s.last_name, s.admission_no, c.name
                  ORDER BY c.name, s.last_name, s.first_name";
                  
        return $this->db->fetchAll($sql, $params);
    }
    
    public function getDetailedAttendanceByStudent($studentId, $startDate, $endDate)
    {
        $sql = "SELECT 
                    a.date,
                    a.status,
                    a.remarks,
                    s.first_name,
                    s.last_name,
                    s.admission_no,
                    c.name as class_name
                FROM attendance a
                LEFT JOIN students s ON a.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE a.student_id = :student_id 
                AND a.date BETWEEN :start_date AND :end_date
                ORDER BY a.date ASC";
                
        return $this->db->fetchAll($sql, [
            'student_id' => $studentId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }
    
    public function getAttendanceStatsByStudent($studentId, $startDate, $endDate)
    {
        $sql = "SELECT 
                    COUNT(CASE WHEN status = 'present' THEN 1 END) as present,
                    COUNT(CASE WHEN status = 'absent' THEN 1 END) as absent,
                    COUNT(CASE WHEN status = 'late' THEN 1 END) as late,
                    COUNT(*) as total
                FROM attendance
                WHERE student_id = :student_id 
                AND date BETWEEN :start_date AND :end_date";
                
        return $this->db->fetchOne($sql, [
            'student_id' => $studentId,
            'start_date' => $startDate,
            'end_date' => $endDate
        ]);
    }
    
    /**
     * Get all distinct attendance dates
     */
    public function getAllAttendanceDates()
    {
        $sql = "SELECT DISTINCT date FROM {$this->table} ORDER BY date DESC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get recent attendance records (last 5)
     */
    public function getRecentAttendance($limit = 5)
    {
        $sql = "SELECT a.*, s.first_name, s.last_name, s.admission_no, c.name as class_name
                FROM {$this->table} a 
                LEFT JOIN students s ON a.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                ORDER BY a.date DESC, a.created_at DESC
                LIMIT :limit";
        return $this->db->fetchAll($sql, ['limit' => $limit]);
    }
    
    /**
     * Get distinct months with attendance records
     */
    public function getMonthsWithAttendance()
    {
        $sql = "SELECT DISTINCT DATE_FORMAT(date, '%Y-%m') as month, 
                       DATE_FORMAT(date, '%M %Y') as month_name
                FROM {$this->table} 
                ORDER BY month DESC";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get attendance dates for a specific month
     */
    public function getAttendanceDatesForMonth($yearMonth)
    {
        $sql = "SELECT DISTINCT date FROM {$this->table} 
                WHERE DATE_FORMAT(date, '%Y-%m') = :year_month
                ORDER BY date DESC";
        return $this->db->fetchAll($sql, ['year_month' => $yearMonth]);
    }
    
    /**
     * Get recent attendance records for analytics dashboard
     */
    public function getRecentAttendanceForAnalytics($days = 30)
    {
        $sql = "SELECT a.*, s.first_name, s.last_name, s.admission_no, c.name as class_name
                FROM {$this->table} a 
                LEFT JOIN students s ON a.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE a.date >= DATE_SUB(CURDATE(), INTERVAL :days DAY)
                ORDER BY a.date DESC";
        return $this->db->fetchAll($sql, ['days' => $days]);
    }
}