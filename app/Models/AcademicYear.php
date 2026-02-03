<?php
namespace App\Models;

use App\Core\Model;

class AcademicYear extends Model
{
    protected $table = 'academic_years';
    protected $fillable = [
        'name',
        'start_date',
        'end_date',
        'is_current',
        'status',
        'term'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAll()
    {
        return $this->all();
    }

    public function getCurrent()
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_current = 1 AND status = 'active' LIMIT 1";
        return $this->db->fetchOne($sql);
    }

    public function getCurrentWithTerm()
    {
        $sql = "SELECT * FROM {$this->table} WHERE is_current = 1 AND status = 'active' LIMIT 1";
        $result = $this->db->fetchOne($sql);
        
        // If no term is set, default to "1st Term"
        if ($result && empty($result['term'])) {
            $result['term'] = '1st Term';
        }
        
        return $result;
    }

    public function setActive($id)
    {
        // Set all academic years as not current
        $sql1 = "UPDATE {$this->table} SET is_current = 0 WHERE 1=1";
        $this->db->execute($sql1);
        
        // Set the selected academic year as current
        $sql2 = "UPDATE {$this->table} SET is_current = 1 WHERE id = ?";
        return $this->db->execute($sql2, [$id]);
    }
    
    // Method to update the term for an academic year
    public function updateTerm($id, $term)
    {
        $sql = "UPDATE {$this->table} SET term = ? WHERE id = ?";
        return $this->db->execute($sql, [$term, $id]);
    }
    
    // Method to get current academic year with term, ensuring a term is always returned
    public function getCurrentAcademicYearWithTerm()
    {
        $current = $this->getCurrent();
        
        // If no current academic year, return null
        if (!$current) {
            return null;
        }
        
        // If no term is set, default to "1st Term"
        if (empty($current['term'])) {
            $current['term'] = '1st Term';
        }
        
        return $current;
    }
}