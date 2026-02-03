<?php
namespace App\Models;

use App\Core\Model;

class Timetable extends Model
{
    protected $table = 'timetables';
    protected $fillable = [
        'class_id',
        'subject_id',
        'staff_id',
        'day_of_week',
        'start_time',
        'end_time',
        'room',
        'academic_year_id'
    ];

    public function __construct()
    {
        parent::__construct();;
    }

    public function getAll()
    {
        $sql = "
            SELECT t.*, c.name as class_name, s.name as subject_name, st.first_name as staff_first_name, st.last_name as staff_last_name
            FROM {$this->table} t
            LEFT JOIN classes c ON t.class_id = c.id
            LEFT JOIN subjects s ON t.subject_id = s.id
            LEFT JOIN staff st ON t.staff_id = st.id
            ORDER BY t.day_of_week, t.start_time
        ";
        return $this->db->fetchAll($sql);
    }

    public function getByClass($classId)
    {
        $sql = "
            SELECT t.*, c.name as class_name, s.name as subject_name, st.first_name as staff_first_name, st.last_name as staff_last_name
            FROM {$this->table} t
            LEFT JOIN classes c ON t.class_id = c.id
            LEFT JOIN subjects s ON t.subject_id = s.id
            LEFT JOIN staff st ON t.staff_id = st.id
            WHERE t.class_id = :class_id
            ORDER BY t.day_of_week, t.start_time
        ";
        return $this->db->fetchAll($sql, ['class_id' => $classId]);
    }

    public function getByDay($day)
    {
        $sql = "
            SELECT t.*, c.name as class_name, s.name as subject_name, st.first_name as staff_first_name, st.last_name as staff_last_name
            FROM {$this->table} t
            LEFT JOIN classes c ON t.class_id = c.id
            LEFT JOIN subjects s ON t.subject_id = s.id
            LEFT JOIN staff st ON t.staff_id = st.id
            WHERE t.day_of_week = :day
            ORDER BY t.start_time
        ";
        return $this->db->fetchAll($sql, ['day' => $day]);
    }
    
    public function getFiltered($classId = null, $staffId = null, $subjectId = null)
    {
        $sql = "
            SELECT t.*, c.name as class_name, s.name as subject_name, st.first_name as staff_first_name, st.last_name as staff_last_name
            FROM {$this->table} t
            LEFT JOIN classes c ON t.class_id = c.id
            LEFT JOIN subjects s ON t.subject_id = s.id
            LEFT JOIN staff st ON t.staff_id = st.id
        ";
        
        $conditions = [];
        $params = [];
        
        if ($classId) {
            $conditions[] = "t.class_id = :class_id";
            $params['class_id'] = $classId;
        }
        
        if ($staffId) {
            $conditions[] = "t.staff_id = :staff_id";
            $params['staff_id'] = $staffId;
        }
        
        if ($subjectId) {
            $conditions[] = "t.subject_id = :subject_id";
            $params['subject_id'] = $subjectId;
        }
        
        if (!empty($conditions)) {
            $sql .= " WHERE " . implode(' AND ', $conditions);
        }
        
        $sql .= " ORDER BY t.day_of_week, t.start_time";
        
        return $this->db->fetchAll($sql, $params);
    }
}

