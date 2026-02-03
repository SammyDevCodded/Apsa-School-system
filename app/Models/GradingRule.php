<?php
namespace App\Models;

use App\Core\Model;

class GradingRule extends Model
{
    protected $table = 'grading_rules';
    protected $fillable = [
        'scale_id',
        'min_score',
        'max_score',
        'grade',
        'remark'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    // Method to get grade for specific marks based on grading scale
    public function getGradeForMarks($scaleId, $marks)
    {
        // Get rules sorted by min_score descending to ensure proper matching
        $sql = "SELECT * FROM {$this->table} WHERE scale_id = :scale_id ORDER BY min_score DESC";
        $rules = $this->db->fetchAll($sql, ['scale_id' => $scaleId]);
        
        // Find the rule that matches the marks
        foreach ($rules as $rule) {
            if ($marks >= $rule['min_score'] && $marks <= $rule['max_score']) {
                return $rule['grade'];
            }
        }
        
        // Default grade if no rule matches
        return 'F';
    }
    
    // Method to get grading rules for a scale sorted by min_score descending
    public function getRulesByScaleSorted($scaleId)
    {
        $sql = "SELECT * FROM {$this->table} WHERE scale_id = :scale_id ORDER BY min_score DESC";
        return $this->db->fetchAll($sql, ['scale_id' => $scaleId]);
    }
}