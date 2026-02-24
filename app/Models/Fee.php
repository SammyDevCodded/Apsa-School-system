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
        
        $classModel = new ClassModel();
        $feeAssignmentModel = new FeeAssignment();
        $studentModel = new Student();
        
        $classTotalCache = [];

        foreach ($fees as &$fee) {
            $assignedClasses = [];

            // 1. Get original classes defined at fee creation
            $originalClassIds = $this->getOriginalClasses($fee['id']);
            if (!empty($originalClassIds)) {
                foreach ($originalClassIds as $cId) {
                    $assignedClasses[(int)$cId] = true;
                }
            }
            
            // 2. Get dynamically assigned classes based on student assignments
            $dynamicClasses = $feeAssignmentModel->getClassesByFeeId($fee['id']);
            foreach ($dynamicClasses as $dynClass) {
                $assignedClasses[(int)$dynClass['id']] = true;
            }

            // Build the display string
            $classDisplayTexts = [];
            foreach (array_keys($assignedClasses) as $classId) {
                $class = $classModel->find($classId);
                if ($class) {
                    if (!isset($classTotalCache[$classId])) {
                        $classTotalCache[$classId] = $studentModel->getCountByClassId($classId);
                    }
                    
                    $totalStudents = $classTotalCache[$classId];
                    $assignedToFee = $feeAssignmentModel->getCountByFeeAndClass($fee['id'], $classId);
                    
                    if ($totalStudents == 0 || $assignedToFee == $totalStudents) {
                        $classDisplayTexts[] = $class['name'];
                    } else {
                        $classDisplayTexts[] = sprintf('%s (%d of %d)', $class['name'], $assignedToFee, $totalStudents);
                    }
                }
            }

            if (!empty($classDisplayTexts)) {
                $fee['display_classes'] = implode(', ', $classDisplayTexts);
            } else {
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
     * Get all fees assigned to a specific class (either primarily or via original_classes)
     */
    public function getFeesByClassId($classId)
    {
        $allFees = $this->all();
        $classFees = [];
        
        foreach ($allFees as $fee) {
            $isForClass = false;
            // Check primary class_id
            if ($fee['class_id'] == $classId) {
                $isForClass = true;
            } 
            // Check original_classes JSON array
            else if (!empty($fee['original_classes'])) {
                $originalClasses = json_decode($fee['original_classes'], true) ?: [];
                if (in_array((string)$classId, $originalClasses) || in_array((int)$classId, $originalClasses)) {
                    $isForClass = true;
                }
            }
            
            if ($isForClass) {
                // Ensure the fee is active for the current academic year/term (if applicable)
                $classFees[] = $fee;
            }
        }
        
        return $classFees;
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
        
        $classModel = new ClassModel();
        $feeAssignmentModel = new FeeAssignment();
        $studentModel = new Student();
        
        // Cache to prevent repetitive global count queries for the same class
        $classTotalCache = [];

        foreach ($fees as &$fee) {
            // Get overall student count for this fee
            $fee['student_count'] = $feeAssignmentModel->getCountByFeeId($fee['id']);
            
            $assignedClasses = [];

            // 1. Get original classes defined at fee creation
            $originalClassIds = $this->getOriginalClasses($fee['id']);
            if (!empty($originalClassIds)) {
                foreach ($originalClassIds as $cId) {
                    $assignedClasses[(int)$cId] = true;
                }
            }
            
            // 2. Get dynamically assigned classes based on student assignments
            $dynamicClasses = $feeAssignmentModel->getClassesByFeeId($fee['id']);
            foreach ($dynamicClasses as $dynClass) {
                $assignedClasses[(int)$dynClass['id']] = true;
            }

            // Build the display string
            $classDisplayTexts = [];
            foreach (array_keys($assignedClasses) as $classId) {
                $class = $classModel->find($classId);
                if ($class) {
                    // Check if we already cached the total students in this class
                    if (!isset($classTotalCache[$classId])) {
                        $classTotalCache[$classId] = $studentModel->getCountByClassId($classId);
                    }
                    
                    $totalStudents = $classTotalCache[$classId];
                    $assignedToFee = $feeAssignmentModel->getCountByFeeAndClass($fee['id'], $classId);
                    
                    if ($totalStudents == 0 || $assignedToFee == $totalStudents) {
                        // Class is empty, OR everyone is assigned -> Just show "Class Name"
                        $classDisplayTexts[] = $class['name'];
                    } else {
                        // Partial assignment -> Show "Class Name (X of Y)"
                        $classDisplayTexts[] = sprintf('%s (%d of %d)', $class['name'], $assignedToFee, $totalStudents);
                    }
                }
            }

            if (!empty($classDisplayTexts)) {
                $fee['display_classes'] = implode(', ', $classDisplayTexts);
            } else {
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