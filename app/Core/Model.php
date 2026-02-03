<?php
namespace App\Core;

class Model
{
    protected $db;
    protected $table;
    protected $primaryKey = 'id';

    public function __construct()
    {
        $this->db = new Database();
    }

    public function all()
    {
        $sql = "SELECT * FROM {$this->table}";
        return $this->db->fetchAll($sql);
    }

    public function find($id)
    {
        $sql = "SELECT * FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->fetchOne($sql, ['id' => $id]);
    }

    public function where($column, $value, $column2 = null, $value2 = null)
    {
        $params = [$column => $value];
        $sql = "SELECT * FROM {$this->table} WHERE {$column} = :{$column}";
        
        if ($column2 && $value2) {
            $sql .= " AND {$column2} = :{$column2}";
            $params[$column2] = $value2;
        }
        
        return $this->db->fetchAll($sql, $params);
    }

    public function create($data)
    {
        $columns = implode(',', array_keys($data));
        $placeholders = ':' . implode(', :', array_keys($data));
        
        $sql = "INSERT INTO {$this->table} ({$columns}) VALUES ({$placeholders})";
        $this->db->execute($sql, $data);
        
        return $this->db->getConnection()->lastInsertId();
    }

    public function update($id, $data)
    {
        // Check if there's actually data to update
        if (empty($data)) {
            return 0; // No data to update
        }
        
        $set = '';
        $params = [];
        
        // Build the SET clause and parameters, excluding the primary key
        foreach ($data as $key => $value) {
            if ($key !== $this->primaryKey) {
                $set .= "{$key} = :{$key}, ";
                $params[$key] = $value;
            }
        }
        
        // If no valid fields to update, return early
        if (empty($set)) {
            return 0;
        }
        
        $set = rtrim($set, ', ');
        
        // Add the primary key for the WHERE clause
        $params[$this->primaryKey] = $id;
        
        $sql = "UPDATE {$this->table} SET {$set} WHERE {$this->primaryKey} = :{$this->primaryKey}";
        $result = $this->db->execute($sql, $params);
        
        return $result;
    }

    public function delete($id)
    {
        $sql = "DELETE FROM {$this->table} WHERE {$this->primaryKey} = :id";
        return $this->db->execute($sql, ['id' => $id]);
    }

    public function count()
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table}";
        $result = $this->db->fetchOne($sql);
        return $result['count'];
    }
    
    // Method to execute raw SQL queries
    public function executeRaw($sql, $params = [])
    {
        return $this->db->execute($sql, $params);
    }
}