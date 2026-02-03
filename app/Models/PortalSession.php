<?php
namespace App\Models;

use App\Core\Model;

class PortalSession extends Model
{
    protected $table = 'portal_sessions';
    protected $fillable = [
        'user_type',
        'user_id',
        'session_token',
        'ip_address',
        'user_agent',
        'login_time',
        'last_activity',
        'is_active'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function createSession($userType, $userId, $token)
    {
        $data = [
            'user_type' => $userType,
            'user_id' => $userId,
            'session_token' => $token,
            'ip_address' => $_SERVER['REMOTE_ADDR'] ?? null,
            'user_agent' => $_SERVER['HTTP_USER_AGENT'] ?? null,
            'login_time' => date('Y-m-d H:i:s'),
            'last_activity' => date('Y-m-d H:i:s'),
            'is_active' => 1
        ];
        return $this->create($data); // Model create returns ID
    }

    public function terminate($token)
    {
        $sql = "UPDATE {$this->table} SET is_active = 0 WHERE session_token = :token";
        return $this->db->query($sql, ['token' => $token]);
    }
    
    public function terminateById($id)
    {
        $sql = "UPDATE {$this->table} SET is_active = 0 WHERE id = :id";
        return $this->db->query($sql, ['id' => $id]);
    }

    public function heartbeat($token)
    {
        $sql = "UPDATE {$this->table} SET last_activity = NOW() WHERE session_token = :token";
        $this->db->query($sql, ['token' => $token]);
    }

    public function checkStatus($token)
    {
        $sql = "SELECT is_active FROM {$this->table} WHERE session_token = :token LIMIT 1";
        $result = $this->db->fetchOne($sql, ['token' => $token]);
        return $result ? (bool)$result['is_active'] : false;
    }
    
    public function getActiveSessions()
    {
        // Join with appropriate tables based on type would be complex in one query if types differ greatly.
        // For now, let's just get the session data, possibly join blindly or handle in controller.
        // Or cleaner: Fetch all active sessions, then enrich details.
        
        $sql = "
            SELECT ps.*, 
            CASE 
                WHEN ps.user_type = 'student' THEN CONCAT(s.first_name, ' ', s.last_name)
                WHEN ps.user_type = 'parent' THEN CONCAT(s.guardian_name, ' (Parent of ', s.first_name, ')')
                WHEN ps.user_type = 'staff' THEN u.username
            END as user_name,
            CASE 
                WHEN ps.user_type = 'student' THEN s.admission_no
                WHEN ps.user_type = 'parent' THEN s.admission_no
                WHEN ps.user_type = 'staff' THEN u.username
            END as identity
            FROM {$this->table} ps
            LEFT JOIN students s ON (ps.user_type IN ('student', 'parent') AND ps.user_id = s.id)
            LEFT JOIN users u ON (ps.user_type = 'staff' AND ps.user_id = u.id)
            WHERE ps.is_active = 1
            ORDER BY ps.last_activity DESC
        ";
        return $this->db->fetchAll($sql);
    }
}
