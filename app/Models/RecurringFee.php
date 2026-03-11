<?php
namespace App\Models;

use App\Core\Model;

class RecurringFee extends Model
{
    protected $table = 'recurring_fees';
    protected $fillable = [
        'name', 'description', 'amount_per_unit', 'billing_cycle',
        'scope', 'academic_year_id', 'active'
    ];

    /** Get all active recurring fees, optionally filtered by academic year */
    public function getAll($academicYearId = null)
    {
        $sql = "SELECT rf.*, ay.name AS academic_year_name
                FROM recurring_fees rf
                LEFT JOIN academic_years ay ON rf.academic_year_id = ay.id
                WHERE rf.active = 1";
        $params = [];
        if ($academicYearId) {
            $sql .= " AND rf.academic_year_id = ?";
            $params[] = $academicYearId;
        }
        $sql .= " ORDER BY rf.created_at DESC";
        return $this->db->fetchAll($sql, $params);
    }

    /** Get all fees including inactive */
    public function getAllIncludingInactive($academicYearId = null)
    {
        $sql = "SELECT rf.*, ay.name AS academic_year_name
                FROM recurring_fees rf
                LEFT JOIN academic_years ay ON rf.academic_year_id = ay.id";
        $params = [];
        if ($academicYearId) {
            $sql .= " WHERE (rf.academic_year_id = ? OR rf.academic_year_id IS NULL)";
            $params[] = $academicYearId;
        }
        $sql .= " ORDER BY rf.created_at DESC";
        return $this->db->fetchAll($sql, $params);
    }


    public function find($id)
    {
        $sql = "SELECT rf.*, ay.name AS academic_year_name
                FROM recurring_fees rf
                LEFT JOIN academic_years ay ON rf.academic_year_id = ay.id
                WHERE rf.id = ?";
        return $this->db->fetchOne($sql, [$id]);
    }

    public function create($data)
    {
        $sql = "INSERT INTO recurring_fees
                    (name, description, amount_per_unit, billing_cycle, scope, academic_year_id, active)
                VALUES (?, ?, ?, ?, ?, ?, 1)";
        $this->db->execute($sql, [
            $data['name'],
            $data['description'] ?? null,
            $data['amount_per_unit'],
            $data['billing_cycle'],
            $data['scope'],
            $data['academic_year_id'] ?? null,
        ]);
        return $this->db->getConnection()->lastInsertId();
    }


    public function update($id, $data)
    {
        $sql = "UPDATE recurring_fees SET
                    name = ?, description = ?, amount_per_unit = ?,
                    billing_cycle = ?, scope = ?, academic_year_id = ?
                WHERE id = ?";
        return $this->db->execute($sql, [
            $data['name'],
            $data['description'] ?? null,
            $data['amount_per_unit'],
            $data['billing_cycle'],
            $data['scope'],
            $data['academic_year_id'] ?? null,
            $id,
        ]);
    }

    public function deactivate($id)
    {
        return $this->db->execute("UPDATE recurring_fees SET active = 0 WHERE id = ?", [$id]);
    }

    public function activate($id)
    {
        return $this->db->execute("UPDATE recurring_fees SET active = 1 WHERE id = ?", [$id]);
    }
}
