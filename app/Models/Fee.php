<?php
namespace App\Models;

use App\Core\Model;

class Fee extends Model
{
    protected $table = 'fees';
    protected $fillable = [
        'name',
        'amount',
        'type',
        'class_id',
        'original_classes',
        'description',
        'academic_year_id',
        'term'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllWithClass()
    {
        $sql = "SELECT f.*, c.name as class_name 
                FROM {$this->table} f 
                LEFT JOIN classes c ON f.class_id = c.id";
        return $this->db->fetchAll($sql);
    }
    
    /**
     * Get all fees with their originally selected class names
     */
    public function getAllWithOriginalClasses()
    {
        $sql = "SELECT f.*, c.name as class_name 
                FROM {$this->table} f 
                LEFT JOIN classes c ON f.class_id = c.id";
        $fees = $this->db->fetchAll($sql);
        
        // For each fee, get the original class names
        $classModel = new ClassModel();
        foreach ($fees as &$fee) {
            $originalClassIds = $this->getOriginalClasses($fee['id']);
            if (!empty($originalClassIds)) {
                $classNames = [];
                foreach ($originalClassIds as $classId) {
                    $class = $classModel->find($classId);
                    if ($class) {
                        $classNames[] = $class['name'];
                    }
                }
                // If we have original classes, use them instead of the old class_name
                if (!empty($classNames)) {
                    $fee['display_classes'] = implode(', ', $classNames);
                } else {
                    $fee['display_classes'] = 'No classes assigned';
                }
            } else {
                // Fallback to the old class_name field
                $fee['display_classes'] = $fee['class_name'] ?? 'All Classes';
            }
        }
        
        return $fees;
    }
    
    public function findByType($type)
    {
        return $this->where('type', $type);
    }
    
    /**
     * Override the create method to handle multiple class assignments
     */
    public function create($data)
    {
        // For now, we'll keep the existing structure but allow for future expansion
        // In a more complex implementation, we might store multiple class IDs
        return parent::create($data);
    }
    
    /**
     * Get original classes for a fee as an array
     */
    public function getOriginalClasses($feeId)
    {
        $fee = $this->find($feeId);
        if (!$fee || empty($fee['original_classes'])) {
            return [];
        }
        
        // Decode JSON array of class IDs
        return json_decode($fee['original_classes'], true) ?: [];
    }
    
    /**
     * Set original classes for a fee
     */
    public function setOriginalClasses($feeId, $classIds)
    {
        $data = [
            'original_classes' => json_encode(array_values($classIds))
        ];
        
        return $this->update($feeId, $data);
    }
    
    /**
     * Get count of students assigned to a fee
     */
    public function getAssignedStudentCount($feeId)
    {
        $sql = "SELECT COUNT(*) as count
                FROM fee_assignments
                WHERE fee_id = :fee_id";
        $result = $this->db->fetchOne($sql, ['fee_id' => $feeId]);
        return $result['count'] ?? 0;
    }
    
    /**
     * Get all fees with their originally selected class names and student counts
     */
    public function getAllWithOriginalClassesAndStudentCount()
    {
        $sql = "SELECT f.*, c.name as class_name 
                FROM {$this->table} f 
                LEFT JOIN classes c ON f.class_id = c.id";
        $fees = $this->db->fetchAll($sql);
        
        // For each fee, get the original class names and student count
        $classModel = new ClassModel();
        $feeAssignmentModel = new FeeAssignment();
        foreach ($fees as &$fee) {
            // Get student count for this fee
            $fee['student_count'] = $feeAssignmentModel->getCountByFeeId($fee['id']);
            
            $originalClassIds = $this->getOriginalClasses($fee['id']);
            if (!empty($originalClassIds)) {
                $classNames = [];
                foreach ($originalClassIds as $classId) {
                    $class = $classModel->find($classId);
                    if ($class) {
                        $classNames[] = $class['name'];
                    }
                }
                // If we have original classes, use them instead of the old class_name
                if (!empty($classNames)) {
                    $fee['display_classes'] = implode(', ', $classNames);
                } else {
                    $fee['display_classes'] = 'No classes assigned';
                }
            } else {
                // Fallback to the old class_name field
                $fee['display_classes'] = $fee['class_name'] ?? 'All Classes';
            }
        }
        
        return $fees;
    }
    
    /**
     * Get fees assigned to a specific student
     */
    public function getByStudentId($studentId)
    {
        $sql = "SELECT f.*, fa.assigned_date
                FROM {$this->table} f
                INNER JOIN fee_assignments fa ON f.id = fa.fee_id
                WHERE fa.student_id = :student_id
                ORDER BY fa.assigned_date DESC";
        return $this->db->fetchAll($sql, ['student_id' => $studentId]);
    }
}