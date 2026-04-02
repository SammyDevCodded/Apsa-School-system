<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\RecurringFee;
use App\Models\RecurringFeeEnrollment;
use App\Models\RecurringFeeEntry;
use App\Models\AcademicYear;
use App\Models\ClassModel;
use App\Helpers\AuditHelper;
use App\Models\RecurringFeePayment;

class RecurringFeeController extends Controller
{
    private function requireAuth()
    {
        if (!isset($_SESSION['user'])) {
            if ($this->isAjaxRequest()) {
                $this->jsonResponse(['error' => 'Unauthorized'], 401);
            } else {
                $this->redirect('/login');
            }
            return false;
        }
        return true;
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /finance/recurring-fees  — main page (all 3 tabs)
    // ─────────────────────────────────────────────────────────────────────────
    public function index()
    {
        if (!$this->requireAuth()) return;

        $academicYearModel = new AcademicYear();
        $currentYear       = $academicYearModel->getCurrent();

        $recurringFeeModel = new RecurringFee();
        $fees              = $recurringFeeModel->getAllIncludingInactive($currentYear ? $currentYear['id'] : null);

        $classModel = new ClassModel();
        $classes    = $classModel->getAll();

        require_once ROOT_PATH . '/app/Helpers/TemplateHelper.php';
        $settings       = getSchoolSettings();
        $currencySymbol = $settings['currency_symbol'] ?? '₵';
        $schoolName     = $settings['school_name'] ?? 'School Name';
        $schoolLogo     = !empty($settings['school_logo']) ? $settings['school_logo'] : '/assets/images/logo.png';

        $this->view('recurring_fees/index', [
            'title'          => 'Recurring Fees',
            'fees'           => $fees,
            'classes'        => $classes,
            'currentYear'    => $currentYear,
            'currencySymbol' => $currencySymbol,
            'schoolName'     => $schoolName,
            'schoolLogo'     => $schoolLogo,
        ]);
    }


    // ─────────────────────────────────────────────────────────────────────────
    // POST /finance/recurring-fees  — create a new recurring fee service
    // ─────────────────────────────────────────────────────────────────────────
    public function store()
    {
        if (!$this->requireAuth()) return;

        $data = [
            'name'             => trim($this->post('name', '')),
            'description'      => trim($this->post('description', '')),
            'amount_per_unit'  => (float) $this->post('amount_per_unit', 0),
            'billing_cycle'    => $this->post('billing_cycle', 'daily'),
            'scope'            => $this->post('scope', 'individual'),
            'academic_year_id' => $this->post('academic_year_id') ?: null,
        ];

        if (empty($data['name']) || $data['amount_per_unit'] <= 0) {
            $this->jsonResponse(['error' => 'Name and a positive amount are required.'], 400);
            return;
        }

        $model = new RecurringFee();
        $id    = $model->create($data);

        if ($id) {
            AuditHelper::log(
                $_SESSION['user']['id'], 'create', 'recurring_fees', $id,
                json_encode($data), null
            );
            $this->jsonResponse(['success' => true, 'id' => $id, 'message' => 'Recurring fee created successfully.']);
        } else {
            $this->jsonResponse(['error' => 'Failed to create recurring fee.'], 500);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /finance/recurring-fees/students/search?q=  — AJAX student search
    // ─────────────────────────────────────────────────────────────────────────
    public function searchStudents()
    {
        if (!$this->requireAuth()) return;

        $q = trim($this->get('q', ''));
        if (strlen($q) < 2) {
            $this->jsonResponse([]);
            return;
        }

        $db   = \App\Core\Database::getInstance();
        $like = '%' . $q . '%';
        $sql  = "SELECT s.id, s.first_name, s.last_name, s.admission_no,
                        c.name AS class_name
                 FROM students s
                 LEFT JOIN classes c ON s.class_id = c.id
                 WHERE (
                       CONCAT(s.first_name, ' ', s.last_name) LIKE ?
                       OR s.first_name   LIKE ?
                       OR s.last_name    LIKE ?
                       OR s.admission_no LIKE ?
                   )
                 ORDER BY s.first_name, s.last_name
                 LIMIT 20";


        $students = $db->fetchAll($sql, [$like, $like, $like, $like]);
        $this->jsonResponse($students);
    }

    public function show($id)
    {
        if (!$this->requireAuth()) return;

        $model = new RecurringFee();
        $fee   = $model->find($id);
        if (!$fee) {
            $this->jsonResponse(['error' => 'Recurring fee not found.'], 404);
            return;
        }

        $enrollmentModel = new RecurringFeeEnrollment();
        $enrollments     = $enrollmentModel->getByFee($id);

        $this->jsonResponse([
            'success'     => true,
            'fee'         => $fee,
            'enrollments' => $enrollments,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // PUT /finance/recurring-fees/{id}  — update a fee
    // ─────────────────────────────────────────────────────────────────────────
    public function update($id)
    {
        if (!$this->requireAuth()) return;

        $data = [
            'name'             => trim($this->post('name', '')),
            'description'      => trim($this->post('description', '')),
            'amount_per_unit'  => (float) $this->post('amount_per_unit', 0),
            'billing_cycle'    => $this->post('billing_cycle', 'daily'),
            'scope'            => $this->post('scope', 'individual'),
            'academic_year_id' => $this->post('academic_year_id') ?: null,
        ];

        if (empty($data['name']) || $data['amount_per_unit'] <= 0) {
            $this->jsonResponse(['error' => 'Name and a positive amount are required.'], 400);
            return;
        }

        $model = new RecurringFee();
        $model->update($id, $data);
        $this->jsonResponse(['success' => true, 'message' => 'Recurring fee updated.']);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /finance/recurring-fees/{id}/enroll  — enroll students/class/school
    // ─────────────────────────────────────────────────────────────────────────
    public function enroll($id)
    {
        if (!$this->requireAuth()) return;

        $feeModel = new RecurringFee();
        $fee      = $feeModel->find($id);
        if (!$fee) {
            $this->jsonResponse(['error' => 'Recurring fee not found.'], 404);
            return;
        }

        $enrollType   = $this->post('enroll_type', 'individual'); // individual | class | school
        $billingCycle = $this->post('billing_cycle', 'daily');
        $startDate    = $this->post('start_date', date('Y-m-d'));

        $enrollModel = new RecurringFeeEnrollment();
        $count = 0;

        if ($enrollType === 'school') {
            $count = $enrollModel->enrollAll($id, $billingCycle, $startDate);
        } elseif ($enrollType === 'class') {
            $classId = (int) $this->post('class_id', 0);
            if (!$classId) {
                $this->jsonResponse(['error' => 'Please select a class.'], 400);
                return;
            }
            $count = $enrollModel->enrollClass($id, $classId, $billingCycle, $startDate);
        } else {
            $studentIds = $this->post('student_ids', []);
            if (empty($studentIds)) {
                $this->jsonResponse(['error' => 'Please select at least one student.'], 400);
                return;
            }
            $count = $enrollModel->enrollStudents($id, (array)$studentIds, $billingCycle, $startDate);
        }

        AuditHelper::log(
            $_SESSION['user']['id'], 'enroll', 'recurring_fees', $id,
            json_encode(['enroll_type' => $enrollType, 'count' => $count]), null
        );

        $this->jsonResponse([
            'success' => true,
            'message' => "$count student(s) enrolled successfully.",
            'count'   => $count,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /finance/recurring-fees/{id}/generate  — generate bill entries
    // ─────────────────────────────────────────────────────────────────────────
    public function generateBills($id)
    {
        if (!$this->requireAuth()) return;

        $feeModel = new RecurringFee();
        $fee      = $feeModel->find($id);
        if (!$fee) {
            $this->jsonResponse(['error' => 'Recurring fee not found.'], 404);
            return;
        }

        $fromDate = $this->post('from_date', date('Y-m-01')); // default: first of month
        $toDate   = $this->post('to_date',   date('Y-m-d'));
        $amount   = (float)($this->post('amount') ?: $fee['amount_per_unit']);

        if ($fromDate > $toDate) {
            $this->jsonResponse(['error' => 'From date must be before To date.'], 400);
            return;
        }

        $enrollModel = new RecurringFeeEnrollment();
        $entryModel  = new RecurringFeeEntry();
        $enrollments = $enrollModel->getByFee($id);

        $total = 0;
        foreach ($enrollments as $enrollment) {
            $created = $entryModel->generateEntries(
                $enrollment['id'],
                $enrollment['student_id'],
                $amount,
                $fromDate,
                $toDate
            );
            $total += $created;
        }

        AuditHelper::log(
            $_SESSION['user']['id'], 'generate_bills', 'recurring_fees', $id,
            json_encode(['from' => $fromDate, 'to' => $toDate, 'total' => $total]), null
        );

        $this->jsonResponse([
            'success' => true,
            'message' => "$total new bill entries generated.",
            'total'   => $total,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /finance/recurring-fees/waive  — waive a single entry
    // ─────────────────────────────────────────────────────────────────────────
    public function waiveEntry()
    {
        if (!$this->requireAuth()) return;

        $entryId = (int) $this->post('entry_id', 0);
        $reason  = trim($this->post('reason', ''));

        if (!$entryId || empty($reason)) {
            $this->jsonResponse(['error' => 'Entry ID and reason are required.'], 400);
            return;
        }

        $entryModel = new RecurringFeeEntry();
        $entry      = $entryModel->find($entryId);
        if (!$entry) {
            $this->jsonResponse(['error' => 'Entry not found.'], 404);
            return;
        }

        $result = $entryModel->waive($entryId, $reason, $_SESSION['user']['id']);
        if ($result) {
            $this->jsonResponse(['success' => true, 'message' => 'Entry waived successfully.']);
        } else {
            $this->jsonResponse(['error' => 'Could not waive entry. It may already be waived or paid.'], 400);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /finance/recurring-fees/unwaive  — reverse a waiver
    // ─────────────────────────────────────────────────────────────────────────
    public function unwaiveEntry()
    {
        if (!$this->requireAuth()) return;

        $entryId = (int) $this->post('entry_id', 0);
        if (!$entryId) {
            $this->jsonResponse(['error' => 'Entry ID is required.'], 400);
            return;
        }

        $entryModel = new RecurringFeeEntry();
        $result     = $entryModel->unwaive($entryId);

        if ($result) {
            $this->jsonResponse(['success' => true, 'message' => 'Entry restored to pending.']);
        } else {
            $this->jsonResponse(['error' => 'Could not restore entry. It may not be in waived status.'], 400);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // AJAX — GET /finance/recurring-fees/{id}  (with ?action=entries)
    //  returns bill entries for a fee (with filters)
    // ─────────────────────────────────────────────────────────────────────────
    public function getEntries($id)
    {
        if (!$this->requireAuth()) return;

        $entryModel = new RecurringFeeEntry();
        $filters    = [
            'from_date'  => $this->get('from_date', ''),
            'to_date'    => $this->get('to_date', ''),
            'student_id' => $this->get('student_id', ''),
            'status'     => $this->get('status', ''),
        ];
        $entries = $entryModel->getByFee($id, $filters);
        $summary = $entryModel->getSummary($id, $filters['from_date'] ?: null, $filters['to_date'] ?: null);

        $this->jsonResponse(['success' => true, 'entries' => $entries, 'summary' => $summary]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /finance/recurring-fees/{id}/toggle — activate / deactivate a fee
    // ─────────────────────────────────────────────────────────────────────────
    public function toggle($id)
    {
        if (!$this->requireAuth()) return;

        $model = new RecurringFee();
        $fee   = $model->find($id);
        if (!$fee) {
            $this->jsonResponse(['error' => 'Not found.'], 404);
            return;
        }

        if ($fee['active']) {
            $model->deactivate($id);
            $this->jsonResponse(['success' => true, 'active' => false, 'message' => 'Fee deactivated.']);
        } else {
            $model->activate($id);
            $this->jsonResponse(['success' => true, 'active' => true, 'message' => 'Fee activated.']);
        }
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /finance/recurring-fees/{id}/student-bills?student_id=
    // ─────────────────────────────────────────────────────────────────────────
    public function getStudentBills($feeId)
    {
        if (!$this->requireAuth()) return;

        $studentId = (int)$this->get('student_id', 0);
        if (!$studentId) {
            $this->jsonResponse(['error' => 'student_id is required.'], 422);
            return;
        }

        $entryModel = new RecurringFeeEntry();
        $pending    = $entryModel->getByFee($feeId, [
            'student_id' => $studentId,
            'status'     => 'pending',
        ]);

        $totalPending = array_sum(array_column($pending, 'amount'));

        // Also grab student info
        $db      = \App\Core\Database::getInstance();
        $student = $db->fetchOne(
            "SELECT s.id, s.first_name, s.last_name, s.admission_no, c.name AS class_name
             FROM students s LEFT JOIN classes c ON s.class_id = c.id
             WHERE s.id = ?",
            [$studentId]
        );

        $this->jsonResponse([
            'success'       => true,
            'student'       => $student,
            'pending'       => $pending,
            'total_pending' => $totalPending,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // POST /finance/recurring-fees/{id}/pay
    // ─────────────────────────────────────────────────────────────────────────
    public function recordPayment($feeId)
    {
        if (!$this->requireAuth()) return;

        $studentId    = (int)$this->post('student_id', 0);
        $amountPaid   = (float)$this->post('amount_paid', 0);
        $paymentDate  = $this->post('payment_date', date('Y-m-d'));
        $method       = $this->post('payment_method', 'cash');
        $referenceNo  = $this->post('reference_no', '');
        $notes        = $this->post('notes', '');

        if (!$studentId || $amountPaid <= 0) {
            $this->jsonResponse(['error' => 'Student and a valid amount are required.'], 422);
            return;
        }

        // 1. Save the payment record
        $payModel = new RecurringFeePayment();
        $payId    = $payModel->create([
            'student_id'       => $studentId,
            'recurring_fee_id' => $feeId,
            'amount_paid'      => $amountPaid,
            'payment_date'     => $paymentDate,
            'payment_method'   => $method,
            'reference_no'     => $referenceNo ?: null,
            'notes'            => $notes ?: null,
            'recorded_by'      => $_SESSION['user']['id'],
        ]);

        // 2. Mark pending entries as paid oldest-first up to the paid amount
        $entryModel = new RecurringFeeEntry();
        $pending    = $entryModel->getByFee($feeId, [
            'student_id' => $studentId,
            'status'     => 'pending',
        ]);
        // Sort oldest first
        usort($pending, fn($a, $b) => strcmp($a['service_date'], $b['service_date']));

        $db        = \App\Core\Database::getInstance();
        $remaining = $amountPaid;
        $marked    = 0;
        foreach ($pending as $entry) {
            if ($remaining <= 0) break;
            $db->execute(
                "UPDATE recurring_fee_entries SET status = 'paid' WHERE id = ? AND status = 'pending'",
                [$entry['id']]
            );
            $remaining -= (float)$entry['amount'];
            $marked++;
        }

        $this->jsonResponse([
            'success'      => true,
            'message'      => 'Payment recorded. ' . $marked . ' bill entr' . ($marked === 1 ? 'y' : 'ies') . ' marked as paid.',
            'payment_id'   => $payId,
            'entries_paid' => $marked,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /finance/recurring-fees/{id}/payments  — payment history for a fee
    // ─────────────────────────────────────────────────────────────────────────
    public function getPaymentHistory($feeId)
    {
        if (!$this->requireAuth()) return;

        $payModel = new RecurringFeePayment();
        $payments = $payModel->getByFee(
            $feeId,
            $this->get('from_date') ?: null,
            $this->get('to_date')   ?: null
        );

        $totalPaid = array_sum(array_column($payments, 'amount_paid'));

        $this->jsonResponse([
            'success'    => true,
            'payments'   => $payments,
            'total_paid' => $totalPaid,
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /finance/recurring-fees/{id}/enrolled-students-pay
    // Returns enrolled students with their pending balance for the Pay tab list
    // ─────────────────────────────────────────────────────────────────────────
    public function getEnrolledStudentsForPay($feeId)
    {
        if (!$this->requireAuth()) return;

        $db  = \App\Core\Database::getInstance();
        $sql = "SELECT s.id, s.first_name, s.last_name, s.admission_no,
                       c.name AS class_name,
                       COALESCE(SUM(CASE WHEN rfe.status = 'pending' THEN rfe.amount ELSE 0 END), 0) AS pending_total,
                       COUNT(CASE WHEN rfe.status = 'pending' THEN 1 END) AS pending_count
                FROM recurring_fee_enrollments rfen
                JOIN students s ON rfen.student_id = s.id
                LEFT JOIN classes c ON s.class_id = c.id
                LEFT JOIN recurring_fee_entries rfe ON rfe.student_id = s.id
                    AND rfe.enrollment_id = rfen.id
                WHERE rfen.recurring_fee_id = ? AND rfen.active = 1
                GROUP BY s.id, s.first_name, s.last_name, s.admission_no, c.name
                ORDER BY pending_total DESC, s.last_name, s.first_name";

        $students = $db->fetchAll($sql, [$feeId]);
        $this->jsonResponse(['success' => true, 'students' => $students]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /finance/recurring-fees/student-fees?student_id=
    // Returns recurring fees that a student has pending bills for
    // ─────────────────────────────────────────────────────────────────────────
    public function getStudentRecurringFees()
    {
        if (!$this->requireAuth()) return;

        $studentId = (int)$this->get('student_id', 0);
        if (!$studentId) {
            $this->jsonResponse(['error' => 'student_id is required.'], 422);
            return;
        }

        $db  = \App\Core\Database::getInstance();
        $sql = "SELECT rf.id, rf.name, rf.billing_cycle, rf.amount_per_unit,
                       COALESCE(SUM(CASE WHEN rfe.status = 'pending' THEN rfe.amount ELSE 0 END), 0) AS pending_total,
                       COUNT(CASE WHEN rfe.status = 'pending' THEN 1 END) AS pending_count
                FROM recurring_fee_enrollments rfen
                JOIN recurring_fees rf ON rfen.recurring_fee_id = rf.id
                LEFT JOIN recurring_fee_entries rfe ON rfe.student_id = ? AND rfe.enrollment_id = rfen.id
                WHERE rfen.student_id = ? AND rfen.active = 1 AND rf.active = 1
                GROUP BY rf.id, rf.name, rf.billing_cycle, rf.amount_per_unit
                HAVING pending_total > 0
                ORDER BY rf.name";

        $fees = $db->fetchAll($sql, [$studentId, $studentId]);
        $this->jsonResponse(['success' => true, 'fees' => $fees]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /finance/recurring-fees/{id}/ledger?student_id=
    // Full chronological ledger: bills + waivers + payments with running balance
    // ─────────────────────────────────────────────────────────────────────────
    public function getLedger($feeId)
    {
        if (!$this->requireAuth()) return;

        $studentId = (int)$this->get('student_id', 0);
        if (!$studentId) {
            $this->jsonResponse(['error' => 'student_id is required.'], 422);
            return;
        }

        $db = \App\Core\Database::getInstance();

        // ── Student info ──────────────────────────────────────────────────────
        $student = $db->fetchOne(
            "SELECT s.first_name, s.last_name, s.admission_no, c.name AS class_name
             FROM students s LEFT JOIN classes c ON s.class_id = c.id WHERE s.id = ?",
            [$studentId]
        );

        // ── Fee info ──────────────────────────────────────────────────────────
        $fee = $db->fetchOne("SELECT * FROM recurring_fees WHERE id = ?", [$feeId]);

        // ── All bill entries for this student + fee ───────────────────────────
        $entries = $db->fetchAll(
            "SELECT rfe.id, rfe.service_date AS date, rfe.amount, rfe.status,
                    rfe.waive_reason,
                    u.username AS waived_by
             FROM recurring_fee_entries rfe
             LEFT JOIN recurring_fee_enrollments rfen ON rfe.enrollment_id = rfen.id
             LEFT JOIN users u ON rfe.waived_by = u.id
             WHERE rfe.student_id = ? AND rfen.recurring_fee_id = ?
             ORDER BY rfe.service_date ASC, rfe.id ASC",
            [$studentId, $feeId]
        );

        // ── All payments for this student + fee ───────────────────────────────
        $payments = $db->fetchAll(
            "SELECT rfp.id, rfp.payment_date AS date, rfp.amount_paid AS amount,
                    rfp.payment_method, rfp.reference_no, rfp.notes,
                    u.username AS recorded_by
             FROM recurring_fee_payments rfp
             LEFT JOIN users u ON rfp.recorded_by = u.id
             WHERE rfp.student_id = ? AND rfp.recurring_fee_id = ?
             ORDER BY rfp.payment_date ASC, rfp.created_at ASC",
            [$studentId, $feeId]
        );

        // ── Build chronological ledger rows ───────────────────────────────────
        $rows = [];

        foreach ($entries as $e) {
            $rows[] = [
                'date'    => $e['date'],
                'sort'    => $e['date'] . '_A_' . str_pad($e['id'], 10, '0', STR_PAD_LEFT),
                'type'    => $e['status'] === 'waived' ? 'waiver' : 'bill',
                'detail'  => $e['status'] === 'waived'
                                ? 'Waived' . ($e['waive_reason'] ? ': ' . $e['waive_reason'] : '')
                                : 'Daily bill',
                'billed'  => $e['status'] !== 'waived' ? (float)$e['amount'] : 0,
                'waived'  => $e['status'] === 'waived'  ? (float)$e['amount'] : 0,
                'paid_in' => 0,
                'status'  => $e['status'],
                'ref'     => '',
            ];
        }

        foreach ($payments as $p) {
            $methodMap = [
                'cash' => 'Cash', 'bank_transfer' => 'Bank Transfer',
                'cheque' => 'Cheque', 'mobile_money' => 'Mobile Money',
            ];
            $method = $methodMap[$p['payment_method']] ?? ucfirst($p['payment_method']);
            $rows[] = [
                'date'    => $p['date'],
                'sort'    => $p['date'] . '_B_' . str_pad($p['id'], 10, '0', STR_PAD_LEFT),
                'type'    => 'payment',
                'detail'  => 'Payment — ' . $method
                             . ($p['reference_no'] ? ' [' . $p['reference_no'] . ']' : '')
                             . ($p['recorded_by']  ? ' (by ' . $p['recorded_by'] . ')' : ''),
                'billed'  => 0,
                'waived'  => 0,
                'paid_in' => (float)$p['amount'],
                'status'  => 'payment',
                'ref'     => $p['reference_no'] ?? '',
            ];
        }

        // Sort chronologically; within the same date bills before payments
        usort($rows, fn($a, $b) => strcmp($a['sort'], $b['sort']));

        // ── Add running balance ───────────────────────────────────────────────
        $balance = 0.0;
        foreach ($rows as &$row) {
            $balance += $row['billed'];
            $balance -= $row['waived'];
            $balance -= $row['paid_in'];
            $row['balance'] = round($balance, 2);
        }
        unset($row);

        // ── Summary totals ────────────────────────────────────────────────────
        $totalBilled  = array_sum(array_column($entries, 'amount'));
        $totalWaived  = array_sum(array_filter(array_map(
            fn($e) => $e['status'] === 'waived' ? (float)$e['amount'] : null, $entries
        )));
        $totalPaid    = array_sum(array_column($payments, 'amount'));
        $pendingCount = count(array_filter($entries, fn($e) => $e['status'] === 'pending'));
        $paidCount    = count(array_filter($entries, fn($e) => $e['status'] === 'paid'));
        $waivedCount  = count(array_filter($entries, fn($e) => $e['status'] === 'waived'));

        $this->jsonResponse([
            'success'       => true,
            'student'       => $student,
            'fee'           => $fee,
            'rows'          => $rows,
            'summary'       => [
                'total_billed'  => round($totalBilled, 2),
                'total_waived'  => round($totalWaived, 2),
                'total_paid'    => round($totalPaid, 2),
                'outstanding'   => round($totalBilled - $totalWaived - $totalPaid, 2),
                'pending_days'  => $pendingCount,
                'paid_days'     => $paidCount,
                'waived_days'   => $waivedCount,
                'total_days'    => count($entries),
            ],
        ]);
    }

    // ─────────────────────────────────────────────────────────────────────────
    // GET /finance/recurring-fees/reports/data
    // Fetch JSON data for reports (Payments, Waived, Advances)
    // ─────────────────────────────────────────────────────────────────────────
    public function getReportsData()
    {
        if (!$this->requireAuth()) return;

        $type = $this->get('type', 'payments');
        $feeId = (int)$this->get('fee_id', 0);
        $studentId = (int)$this->get('student_id', 0);
        $fromDate = $this->get('from_date', '');
        $toDate = $this->get('to_date', '');

        $db = \App\Core\Database::getInstance();
        $params = [];
        $where = "1=1";

        if ($feeId) {
            $where .= " AND fee.id = ?";
            $params[] = $feeId;
        }
        if ($studentId) {
            $where .= " AND s.id = ?";
            $params[] = $studentId;
        }

        if ($type === 'payments') {
            if ($fromDate) { $where .= " AND rfp.payment_date >= ?"; $params[] = $fromDate; }
            if ($toDate) { $where .= " AND rfp.payment_date <= ?"; $params[] = $toDate; }

            $sql = "SELECT rfp.id, rfp.payment_date, rfp.amount_paid, rfp.payment_method, rfp.reference_no,
                           s.first_name, s.last_name, s.admission_no, c.name AS class_name,
                           fee.name AS fee_name
                    FROM recurring_fee_payments rfp
                    JOIN students s ON rfp.student_id = s.id
                    LEFT JOIN classes c ON s.class_id = c.id
                    JOIN recurring_fees fee ON rfp.recurring_fee_id = fee.id
                    WHERE $where
                    ORDER BY rfp.payment_date DESC, rfp.id DESC";
            
            $data = $db->fetchAll($sql, $params);
            $this->jsonResponse(['success' => true, 'data' => $data]);
            return;
        }

        if ($type === 'waived') {
            if ($fromDate) { $where .= " AND rfe.service_date >= ?"; $params[] = $fromDate; }
            if ($toDate) { $where .= " AND rfe.service_date <= ?"; $params[] = $toDate; }

            $sql = "SELECT rfe.id, rfe.service_date, rfe.amount, rfe.waive_reason,
                           s.first_name, s.last_name, s.admission_no, c.name AS class_name,
                           fee.name AS fee_name
                    FROM recurring_fee_entries rfe
                    JOIN recurring_fee_enrollments rfen ON rfe.enrollment_id = rfen.id
                    JOIN recurring_fees fee ON rfen.recurring_fee_id = fee.id
                    JOIN students s ON rfe.student_id = s.id
                    LEFT JOIN classes c ON s.class_id = c.id
                    WHERE rfe.status = 'waived' AND $where
                    ORDER BY rfe.service_date DESC, rfe.id DESC";

            $data = $db->fetchAll($sql, $params);
            $this->jsonResponse(['success' => true, 'data' => $data]);
            return;
        }

        if ($type === 'advances') {
            // For advances, date filters normally don't make sense since it's a current balance snapshot.
            $sql = "SELECT s.id AS student_id, s.first_name, s.last_name, s.admission_no, c.name AS class_name,
                           fee.id AS fee_id, fee.name AS fee_name,
                           (SELECT COALESCE(SUM(amount), 0) FROM recurring_fee_entries WHERE student_id = s.id AND enrollment_id IN (SELECT id FROM recurring_fee_enrollments WHERE recurring_fee_id = fee.id) AND status != 'waived') AS total_billed,
                           (SELECT COALESCE(SUM(amount_paid), 0) FROM recurring_fee_payments WHERE student_id = s.id AND recurring_fee_id = fee.id) AS total_paid
                    FROM students s
                    JOIN recurring_fee_enrollments rfen ON s.id = rfen.student_id
                    JOIN recurring_fees fee ON rfen.recurring_fee_id = fee.id
                    LEFT JOIN classes c ON s.class_id = c.id
                    WHERE $where
                    GROUP BY s.id, fee.id";

            $rows = $db->fetchAll($sql, $params);
            $advances = [];
            foreach ($rows as $row) {
                $billed = (float)$row['total_billed'];
                $paid = (float)$row['total_paid'];
                if ($paid > $billed) {
                    $row['advance_amount'] = $paid - $billed;
                    $advances[] = $row;
                }
            }
            
            usort($advances, fn($a, $b) => $b['advance_amount'] <=> $a['advance_amount']);

            $this->jsonResponse(['success' => true, 'data' => $advances]);
            return;
        }

        if ($type === 'all') {
            // Grand Ledger: combines billed, paid, waived based on date ranges
            $dateFilterEntries = "";
            $dateFilterPayments = "";
            $dateFilterParamsEntries = [];
            $dateFilterParamsPayments = [];

            if ($fromDate) {
                $dateFilterEntries .= " AND service_date >= ?";
                $dateFilterPayments .= " AND payment_date >= ?";
                $dateFilterParamsEntries[] = $fromDate;
                $dateFilterParamsPayments[] = $fromDate;
            }
            if ($toDate) {
                $dateFilterEntries .= " AND service_date <= ?";
                $dateFilterPayments .= " AND payment_date <= ?";
                $dateFilterParamsEntries[] = $toDate;
                $dateFilterParamsPayments[] = $toDate;
            }

            // We need to inject these parameters into the main query
            // However, prepared statements with variable numbers of parameters inside subqueries in MySQL 
            // can be tricky if we don't bind them in the exact order.
            // Let's use string concatenation for dates since they are just date strings (after simple validation)
            // It's safer to use parameterized queries, so we will build the query carefully.

            $sql = "SELECT s.id AS student_id, s.first_name, s.last_name, s.admission_no, c.name AS class_name,
                           fee.id AS fee_id, fee.name AS fee_name,
                           (SELECT COALESCE(SUM(amount), 0) FROM recurring_fee_entries WHERE student_id = s.id AND enrollment_id IN (SELECT id FROM recurring_fee_enrollments WHERE recurring_fee_id = fee.id) AND status != 'waived' $dateFilterEntries) AS total_billed,
                           (SELECT COALESCE(SUM(amount), 0) FROM recurring_fee_entries WHERE student_id = s.id AND enrollment_id IN (SELECT id FROM recurring_fee_enrollments WHERE recurring_fee_id = fee.id) AND status = 'waived' $dateFilterEntries) AS total_waived,
                           (SELECT COALESCE(SUM(amount_paid), 0) FROM recurring_fee_payments WHERE student_id = s.id AND recurring_fee_id = fee.id $dateFilterPayments) AS total_paid
                    FROM students s
                    JOIN recurring_fee_enrollments rfen ON s.id = rfen.student_id
                    JOIN recurring_fees fee ON rfen.recurring_fee_id = fee.id
                    LEFT JOIN classes c ON s.class_id = c.id
                    WHERE $where
                    GROUP BY s.id, fee.id
                    ORDER BY c.name, s.first_name";

            // The parameters order must be: dateFilterEntries (billed), dateFilterEntries (waived), dateFilterPayments, and then $where
            $finalParams = array_merge($dateFilterParamsEntries, $dateFilterParamsEntries, $dateFilterParamsPayments, $params);

            $rows = $db->fetchAll($sql, $finalParams);
            
            $ledger = [];
            foreach ($rows as $row) {
                $billed = (float)$row['total_billed'];
                $paid = (float)$row['total_paid'];
                $waived = (float)$row['total_waived'];
                $expected = $billed;
                
                $outstanding = max(0, $expected - $paid);
                $advance = max(0, $paid - $expected);

                // Only include rows where there is some activity (billed > 0 OR paid > 0 OR waived > 0)
                if ($billed > 0 || $paid > 0 || $waived > 0) {
                    $row['outstanding_amount'] = $outstanding;
                    $row['advance_amount'] = $advance;
                    $ledger[] = $row;
                }
            }

            $this->jsonResponse(['success' => true, 'data' => $ledger]);
            return;
        }

        $this->jsonResponse(['error' => 'Invalid report type'], 400);
    }
}

