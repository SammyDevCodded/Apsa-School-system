<?php
namespace App\Models;

use App\Core\Model;

class AuditLog extends Model
{
    protected $table = 'audit_logs';
    protected $fillable = [
        'user_id',
        'action',
        'table_name',
        'record_id',
        'old_values',
        'new_values',
        'ip_address',
        'user_agent',
        'academic_year_id',
        'term'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        $sql = "
            SELECT al.*, u.username, u.first_name, u.last_name, ay.name as academic_year_name
            FROM {$this->table} al
            LEFT JOIN users u ON al.user_id = u.id
            LEFT JOIN academic_years ay ON al.academic_year_id = ay.id
            ORDER BY al.created_at DESC
        ";
        return $this->db->fetchAll($sql);
    }

    public function getById($id)
    {
        $sql = "
            SELECT al.*, u.username, u.first_name, u.last_name, ay.name as academic_year_name
            FROM {$this->table} al
            LEFT JOIN users u ON al.user_id = u.id
            LEFT JOIN academic_years ay ON al.academic_year_id = ay.id
            WHERE al.id = :id
        ";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    public function getByUser($userId)
    {
        $sql = "
            SELECT al.*, u.username, u.first_name, u.last_name, ay.name as academic_year_name
            FROM {$this->table} al
            LEFT JOIN users u ON al.user_id = u.id
            LEFT JOIN academic_years ay ON al.academic_year_id = ay.id
            WHERE al.user_id = :user_id
            ORDER BY al.created_at DESC
        ";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }
    
    public function getAllWithFilters($filters = [], $page = 1, $perPage = 10)
    {
        // Calculate offset
        $offset = ($page - 1) * $perPage;
        
        $sql = "SELECT al.*, u.username, u.first_name, u.last_name, ay.name as academic_year_name
                FROM {$this->table} al
                LEFT JOIN users u ON al.user_id = u.id
                LEFT JOIN academic_years ay ON al.academic_year_id = ay.id";
        
        $countSql = "SELECT COUNT(*) as count 
                     FROM {$this->table} al
                     LEFT JOIN users u ON al.user_id = u.id
                     LEFT JOIN academic_years ay ON al.academic_year_id = ay.id";
        
        $whereConditions = [];
        $countWhereConditions = [];
        $params = [];
        $countParams = [];
        
        // Add filter conditions
        if (!empty($filters['table_name'])) {
            $whereConditions[] = "al.table_name = :table_name";
            $countWhereConditions[] = "al.table_name = :table_name_count";
            $params['table_name'] = $filters['table_name'];
            $countParams['table_name_count'] = $filters['table_name'];
        }
        
        if (!empty($filters['academic_year_id'])) {
            $whereConditions[] = "al.academic_year_id = :academic_year_id";
            $countWhereConditions[] = "al.academic_year_id = :academic_year_id_count";
            $params['academic_year_id'] = $filters['academic_year_id'];
            $countParams['academic_year_id_count'] = $filters['academic_year_id'];
        }
        
        if (!empty($filters['term'])) {
            $whereConditions[] = "al.term = :term";
            $countWhereConditions[] = "al.term = :term_count";
            $params['term'] = $filters['term'];
            $countParams['term_count'] = $filters['term'];
        }
        
        if (!empty($filters['user_id'])) {
            $whereConditions[] = "al.user_id = :user_id";
            $countWhereConditions[] = "al.user_id = :user_id_count";
            $params['user_id'] = $filters['user_id'];
            $countParams['user_id_count'] = $filters['user_id'];
        }
        
        // Add date range filter conditions
        if (!empty($filters['date_from']) && !empty($filters['date_to'])) {
            $whereConditions[] = "DATE(al.created_at) BETWEEN :date_from AND :date_to";
            $countWhereConditions[] = "DATE(al.created_at) BETWEEN :date_from_count AND :date_to_count";
            $params['date_from'] = $filters['date_from'];
            $params['date_to'] = $filters['date_to'];
            $countParams['date_from_count'] = $filters['date_from'];
            $countParams['date_to_count'] = $filters['date_to'];
        } elseif (!empty($filters['date_from'])) {
            $whereConditions[] = "DATE(al.created_at) >= :date_from";
            $countWhereConditions[] = "DATE(al.created_at) >= :date_from_count";
            $params['date_from'] = $filters['date_from'];
            $countParams['date_from_count'] = $filters['date_from'];
        } elseif (!empty($filters['date_to'])) {
            $whereConditions[] = "DATE(al.created_at) <= :date_to";
            $countWhereConditions[] = "DATE(al.created_at) <= :date_to_count";
            $params['date_to'] = $filters['date_to'];
            $countParams['date_to_count'] = $filters['date_to'];
        }
        
        // Combine all conditions
        if (!empty($whereConditions)) {
            $whereClause = " WHERE " . implode(' AND ', $whereConditions);
            $countWhereClause = " WHERE " . implode(' AND ', $countWhereConditions);
            $sql .= $whereClause;
            $countSql .= $countWhereClause;
        }
        
        $sql .= " ORDER BY al.created_at DESC";
        
        // Add pagination
        $sql .= " LIMIT :limit OFFSET :offset";
        $params['limit'] = $perPage;
        $params['offset'] = $offset;
        
        // Get total count
        $countResult = $this->db->fetchOne($countSql, $countParams);
        $totalCount = $countResult ? (int)$countResult['count'] : 0;
        
        // Get results
        $results = $this->db->fetchAll($sql, $params);
        
        // Calculate pagination details
        $totalPages = ceil($totalCount / $perPage);
        
        return [
            'data' => $results,
            'total' => $totalCount,
            'current_page' => $page,
            'per_page' => $perPage,
            'total_pages' => $totalPages
        ];
    }
    
    public function getDistinctModules()
    {
        $sql = "SELECT DISTINCT table_name FROM {$this->table} WHERE table_name IS NOT NULL ORDER BY table_name";
        $results = $this->db->fetchAll($sql);
        return array_column($results, 'table_name');
    }
    
    public function getDistinctTerms()
    {
        $sql = "SELECT DISTINCT term FROM {$this->table} WHERE term IS NOT NULL ORDER BY term";
        $results = $this->db->fetchAll($sql);
        return array_column($results, 'term');
    }

    public function log($userId, $action, $tableName, $recordId = null, $oldValues = null, $newValues = null, $academicYearId = null, $term = null)
    {
        // Get IP address
        $ipAddress = $_SERVER['REMOTE_ADDR'] ?? '';
        
        // Get user agent
        $userAgent = $_SERVER['HTTP_USER_AGENT'] ?? '';
        
        $data = [
            'user_id' => $userId,
            'action' => $action,
            'table_name' => $tableName,
            'record_id' => $recordId,
            'old_values' => $oldValues ? json_encode($oldValues) : null,
            'new_values' => $newValues ? json_encode($newValues) : null,
            'ip_address' => $ipAddress,
            'user_agent' => $userAgent,
            'academic_year_id' => $academicYearId,
            'term' => $term
        ];
        
        return $this->create($data);
    }
}