<?php
namespace App\Models;

use App\Core\Model;

class RecurringFeeEnrollment extends Model
{
    protected $table = 'recurring_fee_enrollments';
    protected $fillable = [
        'recurring_fee_id', 'student_id', 'class_id',
        'billing_cycle', 'start_date', 'active'
    ];

    /**
     * Enroll students individually into a recurring fee.
     * $studentIds is an array of student IDs.
     */
    public function enrollStudents($recurringFeeId, array $studentIds, $billingCycle, $startDate)
    {
        $inserted = 0;
        foreach ($studentIds as $studentId) {
            // Avoid duplicate enrollments
            $existing = $this->db->fetchOne(
                "SELECT id FROM recurring_fee_enrollments
                 WHERE recurring_fee_id = ? AND student_id = ? AND active = 1",
                [$recurringFeeId, $studentId]
            );
            if (!$existing) {
                $this->db->execute(
                    "INSERT INTO recurring_fee_enrollments
                        (recurring_fee_id, student_id, class_id, billing_cycle, start_date, active)
                     VALUES (?, ?, NULL, ?, ?, 1)",
                    [$recurringFeeId, $studentId, $billingCycle, $startDate]
                );
                $inserted++;
            }
        }
        return $inserted;
    }

    /**
     * Enroll an entire class into a recurring fee.
     */
    public function enrollClass($recurringFeeId, $classId, $billingCycle, $startDate)
    {
        // Enroll individual students of the class
        $students = $this->db->fetchAll(
            "SELECT id FROM students WHERE class_id = ? AND status = 'active'",
            [$classId]
        );
        $studentIds = array_column($students, 'id');
        return $this->enrollStudents($recurringFeeId, $studentIds, $billingCycle, $startDate);
    }

    /**
     * Enroll ALL active students in the school.
     */
    public function enrollAll($recurringFeeId, $billingCycle, $startDate)
    {
        $students = $this->db->fetchAll(
            "SELECT id FROM students WHERE status = 'active'",
            []
        );
        $studentIds = array_column($students, 'id');
        return $this->enrollStudents($recurringFeeId, $studentIds, $billingCycle, $startDate);
    }

    /** Get all active enrollments for a recurring fee, joined with student names */
    public function getByFee($recurringFeeId)
    {
        $sql = "SELECT rfe.*,
                       s.first_name, s.last_name, s.admission_no,
                       c.name AS class_name
                FROM recurring_fee_enrollments rfe
                LEFT JOIN students s ON rfe.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE rfe.recurring_fee_id = ? AND rfe.active = 1
                ORDER BY s.last_name, s.first_name";
        return $this->db->fetchAll($sql, [$recurringFeeId]);
    }

    /** Unenroll (soft-delete) a specific enrollment */
    public function unenroll($enrollmentId)
    {
        return $this->db->execute(
            "UPDATE recurring_fee_enrollments SET active = 0 WHERE id = ?",
            [$enrollmentId]
        );
    }

    public function find($id)
    {
        return $this->db->fetchOne(
            "SELECT * FROM recurring_fee_enrollments WHERE id = ?",
            [$id]
        );
    }
}
