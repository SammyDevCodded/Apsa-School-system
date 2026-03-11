<?php
namespace App\Models;

use App\Core\Model;

class RecurringFeeEntry extends Model
{
    protected $table = 'recurring_fee_entries';
    protected $fillable = [
        'enrollment_id', 'student_id', 'service_date',
        'amount', 'status', 'waive_reason', 'waived_by', 'waived_at'
    ];

    /**
     * Generate daily entries for an enrollment over a date range.
     * For weekly/monthly billing cycles, entries are still daily so corrections
     * can be applied per day; payment is handled on the aggregate.
     */
    public function generateEntries($enrollmentId, $studentId, $amount, $fromDate, $toDate)
    {
        $current = new \DateTime($fromDate);
        $end     = new \DateTime($toDate);
        $created = 0;

        while ($current <= $end) {
            $dateStr = $current->format('Y-m-d');
            // Skip weekends (Saturday=6, Sunday=0) — optional business rule
            // $dow = (int)$current->format('w');
            // if ($dow === 0 || $dow === 6) { $current->modify('+1 day'); continue; }

            // Check for duplicate
            $exists = $this->db->fetchOne(
                "SELECT id FROM recurring_fee_entries
                 WHERE enrollment_id = ? AND service_date = ?",
                [$enrollmentId, $dateStr]
            );

            if (!$exists) {
                $this->db->execute(
                    "INSERT INTO recurring_fee_entries
                         (enrollment_id, student_id, service_date, amount, status)
                     VALUES (?, ?, ?, ?, 'pending')",
                    [$enrollmentId, $studentId, $dateStr, $amount]
                );
                $created++;
            }

            $current->modify('+1 day');
        }
        return $created;
    }

    /** Get entries for a specific recurring fee, with optional filters */
    public function getByFee($recurringFeeId, $filters = [])
    {
        $sql = "SELECT rfe.*,
                       s.first_name, s.last_name, s.admission_no,
                       c.name AS class_name,
                       u.username AS waived_by_username
                FROM recurring_fee_entries rfe
                JOIN recurring_fee_enrollments rfen ON rfe.enrollment_id = rfen.id
                JOIN students s ON rfe.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                LEFT JOIN users u ON rfe.waived_by = u.id
                WHERE rfen.recurring_fee_id = ?";
        $params = [$recurringFeeId];

        if (!empty($filters['from_date'])) {
            $sql .= " AND rfe.service_date >= ?";
            $params[] = $filters['from_date'];
        }
        if (!empty($filters['to_date'])) {
            $sql .= " AND rfe.service_date <= ?";
            $params[] = $filters['to_date'];
        }
        if (!empty($filters['student_id'])) {
            $sql .= " AND rfe.student_id = ?";
            $params[] = $filters['student_id'];
        }
        if (!empty($filters['status'])) {
            $sql .= " AND rfe.status = ?";
            $params[] = $filters['status'];
        }

        $sql .= " ORDER BY rfe.service_date DESC, s.last_name, s.first_name";
        return $this->db->fetchAll($sql, $params);
    }

    /** Get entries for a specific student and fee */
    public function getByStudentAndFee($studentId, $recurringFeeId, $filters = [])
    {
        $filters['student_id'] = $studentId;
        return $this->getByFee($recurringFeeId, $filters);
    }

    /** Summary totals grouped by student for a recurring fee and date range */
    public function getSummary($recurringFeeId, $fromDate = null, $toDate = null)
    {
        $sql = "SELECT s.id AS student_id, s.first_name, s.last_name, s.admission_no,
                       c.name AS class_name,
                       COUNT(rfe.id)                                            AS total_days,
                       SUM(CASE WHEN rfe.status = 'pending' THEN rfe.amount ELSE 0 END) AS pending_amount,
                       SUM(CASE WHEN rfe.status = 'paid'    THEN rfe.amount ELSE 0 END) AS paid_amount,
                       SUM(CASE WHEN rfe.status = 'waived'  THEN rfe.amount ELSE 0 END) AS waived_amount,
                       COUNT(CASE WHEN rfe.status = 'waived' THEN 1 END)        AS waived_days
                FROM recurring_fee_entries rfe
                JOIN recurring_fee_enrollments rfen ON rfe.enrollment_id = rfen.id
                JOIN students s ON rfe.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                WHERE rfen.recurring_fee_id = ?";
        $params = [$recurringFeeId];

        if ($fromDate) { $sql .= " AND rfe.service_date >= ?"; $params[] = $fromDate; }
        if ($toDate)   { $sql .= " AND rfe.service_date <= ?"; $params[] = $toDate; }

        $sql .= " GROUP BY s.id, s.first_name, s.last_name, s.admission_no, c.name
                  ORDER BY s.last_name, s.first_name";
        return $this->db->fetchAll($sql, $params);
    }

    /** Waive a single entry */
    public function waive($entryId, $reason, $userId)
    {
        return $this->db->execute(
            "UPDATE recurring_fee_entries
             SET status = 'waived', waive_reason = ?, waived_by = ?, waived_at = NOW()
             WHERE id = ? AND status = 'pending'",
            [$reason, $userId, $entryId]
        );
    }

    /** Reverse a waiver back to pending */
    public function unwaive($entryId)
    {
        return $this->db->execute(
            "UPDATE recurring_fee_entries
             SET status = 'pending', waive_reason = NULL, waived_by = NULL, waived_at = NULL
             WHERE id = ? AND status = 'waived'",
            [$entryId]
        );
    }

    public function find($id)
    {
        return $this->db->fetchOne(
            "SELECT * FROM recurring_fee_entries WHERE id = ?",
            [$id]
        );
    }
}
