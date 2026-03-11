<?php
namespace App\Models;

use App\Core\Model;

class RecurringFeePayment extends Model
{
    protected $table = 'recurring_fee_payments';
    protected $fillable = [
        'student_id', 'recurring_fee_id', 'amount_paid',
        'payment_date', 'payment_method', 'reference_no',
        'notes', 'recorded_by'
    ];

    /** Insert a new payment record and return its ID */
    public function create($data)
    {
        $sql = "INSERT INTO recurring_fee_payments
                    (student_id, recurring_fee_id, amount_paid, payment_date,
                     payment_method, reference_no, notes, recorded_by)
                VALUES (?, ?, ?, ?, ?, ?, ?, ?)";
        $this->db->execute($sql, [
            $data['student_id'],
            $data['recurring_fee_id'],
            $data['amount_paid'],
            $data['payment_date'],
            $data['payment_method'] ?? 'cash',
            $data['reference_no']   ?? null,
            $data['notes']          ?? null,
            $data['recorded_by']    ?? null,
        ]);
        return $this->db->getConnection()->lastInsertId();
    }

    /** All payments for a particular student + fee, newest first */
    public function getByStudentAndFee($studentId, $recurringFeeId)
    {
        $sql = "SELECT rfp.*,
                       u.username AS recorded_by_username
                FROM recurring_fee_payments rfp
                LEFT JOIN users u ON rfp.recorded_by = u.id
                WHERE rfp.student_id = ? AND rfp.recurring_fee_id = ?
                ORDER BY rfp.payment_date DESC, rfp.created_at DESC";
        return $this->db->fetchAll($sql, [$studentId, $recurringFeeId]);
    }

    /** All payments for a fee (all students), for the history view */
    public function getByFee($recurringFeeId, $fromDate = null, $toDate = null)
    {
        $sql = "SELECT rfp.*,
                       s.first_name, s.last_name, s.admission_no,
                       c.name AS class_name,
                       u.username AS recorded_by_username
                FROM recurring_fee_payments rfp
                JOIN students s ON rfp.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                LEFT JOIN users u ON rfp.recorded_by = u.id
                WHERE rfp.recurring_fee_id = ?";
        $params = [$recurringFeeId];
        if ($fromDate) { $sql .= " AND rfp.payment_date >= ?"; $params[] = $fromDate; }
        if ($toDate)   { $sql .= " AND rfp.payment_date <= ?"; $params[] = $toDate; }
        $sql .= " ORDER BY rfp.payment_date DESC, rfp.created_at DESC";
        return $this->db->fetchAll($sql, $params);
    }
}
