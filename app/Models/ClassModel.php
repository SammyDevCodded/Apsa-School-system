<?php
namespace App\Models;

use App\Core\Model;

class ClassModel extends Model
{
    protected $table = 'classes';
    protected $fillable = [
        'name',
        'level',
        'capacity'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        return $this->all();
    }

    public function getAllWithStudentCount()
    {
        $sql = "SELECT c.*, COUNT(s.id) as student_count 
                FROM classes c 
                LEFT JOIN students s ON c.id = s.class_id 
                GROUP BY c.id 
                ORDER BY c.name";
        return $this->db->fetchAll($sql);
    }

    public function getByIdWithStudentCount($id)
    {
        $sql = "SELECT c.*, COUNT(s.id) as student_count 
                FROM classes c 
                LEFT JOIN students s ON c.id = s.class_id 
                WHERE c.id = ? 
                GROUP BY c.id";
        $stmt = $this->db->getConnection()->prepare($sql);
        $stmt->execute([$id]);
        return $stmt->fetch(\PDO::FETCH_ASSOC);
    }

    // Method to get class by ID with student count for editing
    public function findWithStudentCount($id)
    {
        $sql = "SELECT c.*, COUNT(s.id) as student_count 
                FROM classes c 
                LEFT JOIN students s ON c.id = s.class_id 
                WHERE c.id = :id
                GROUP BY c.id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }
    
    public function getTotalCount()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $result = $this->db->fetchOne($sql);
        return $result ? (int)$result['count'] : 0;
    }
    
    public function getAllWithStudentCountPaginated($search = '', $page = 1, $perPage = 10)
    {
        $offset = ($page - 1) * $perPage;
        
        // Build search condition
        $searchCondition = '';
        $countParams = [];
        $dataParams = [];
        
        if (!empty($search)) {
            $searchCondition = "WHERE c.name LIKE ? OR c.level LIKE ?";
            $countParams = ["%{$search}%", "%{$search}%"];
            $dataParams = ["%{$search}%", "%{$search}%"];
        }
        
        // Get total count
        $countSql = "SELECT COUNT(DISTINCT c.id) as total 
                     FROM classes c 
                     LEFT JOIN students s ON c.id = s.class_id 
                     {$searchCondition}";
        $totalCount = $this->db->fetchOne($countSql, $countParams);
        $totalRecords = $totalCount ? (int)$totalCount['total'] : 0;
        
        // Get paginated data
        $sql = "SELECT c.*, COUNT(s.id) as student_count 
                FROM classes c 
                LEFT JOIN students s ON c.id = s.class_id 
                {$searchCondition}
                GROUP BY c.id 
                ORDER BY c.name 
                LIMIT ? OFFSET ?";
        
        $dataParams[] = $perPage;
        $dataParams[] = $offset;
        
        $data = $this->db->fetchAll($sql, $dataParams);
        
        // Calculate pagination info
        $totalPages = ceil($totalRecords / $perPage);
        
        return [
            'data' => $data,
            'pagination' => [
                'current_page' => $page,
                'per_page' => $perPage,
                'total_records' => $totalRecords,
                'total_pages' => $totalPages,
                'has_previous' => $page > 1,
                'has_next' => $page < $totalPages,
                'previous_page' => $page > 1 ? $page - 1 : null,
                'next_page' => $page < $totalPages ? $page + 1 : null
            ]
        ];
    }
}