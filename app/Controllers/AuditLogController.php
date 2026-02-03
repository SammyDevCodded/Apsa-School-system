<?php
namespace App\Controllers;

use App\Core\Controller;
use App\Models\AuditLog;

class AuditLogController extends Controller
{
    private $auditLogModel;

    public function __construct()
    {
        $this->auditLogModel = new AuditLog();
    }

    public function index()
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasRole('super_admin')) {
            $this->redirect('/dashboard');
        }

        $auditLogs = $this->auditLogModel->getAll();
        
        $this->view('audit_logs/index', [
            'auditLogs' => $auditLogs
        ]);
    }

    public function show($id)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasRole('super_admin')) {
            $this->redirect('/dashboard');
        }

        $auditLog = $this->auditLogModel->getById($id);
        
        if (!$auditLog) {
            $this->flash('error', 'Audit log not found');
            $this->redirect('/audit_logs');
        }
        
        // Decode JSON values for display
        if (!empty($auditLog['old_values'])) {
            $auditLog['old_values'] = json_decode($auditLog['old_values'], true);
        }
        
        if (!empty($auditLog['new_values'])) {
            $auditLog['new_values'] = json_decode($auditLog['new_values'], true);
        }
        
        $this->view('audit_logs/show', [
            'auditLog' => $auditLog
        ]);
    }

    public function viewByUser($userId)
    {
        // Check if user is logged in
        if (!isset($_SESSION['user'])) {
            $this->redirect('/login');
        }
        
        // Only allow super admin users to access this
        if (!$this->hasRole('super_admin')) {
            $this->redirect('/dashboard');
        }

        $auditLogs = $this->auditLogModel->getByUser($userId);
        
        $this->view('audit_logs/view_by_user', [
            'auditLogs' => $auditLogs,
            'userId' => $userId
        ]);
    }
}