<?php
namespace App\Models;

use App\Core\Model;

class PortalNotification extends Model
{
    protected $table = 'portal_notifications';
    protected $fillable = [
        'user_type',
        'user_id',
        'sender_id',
        'title',
        'message',
        'attachment_path',
        'is_read',
        'created_at'
    ];

    public function createNotification($data)
    {
        return $this->create($data);
    }
    
    public function getUnreadCount($userType, $userId)
    {
        $sql = "SELECT COUNT(*) as count FROM {$this->table} 
                WHERE user_type = :user_type AND user_id = :user_id 
                AND is_read = 0 AND is_archived = 0 AND deleted_at IS NULL";
        $result = $this->db->fetchOne($sql, ['user_type' => $userType, 'user_id' => $userId]);
        return $result['count'] ?? 0;
    }

    public function getByUser($userType, $userId, $limit = 50, $filter = 'all')
    {
        $sql = "SELECT * FROM {$this->table} WHERE user_type = :user_type AND user_id = :user_id";
        
        switch ($filter) {
            case 'unread':
                $sql .= " AND is_read = 0 AND is_archived = 0 AND deleted_at IS NULL";
                break;
            case 'read':
                $sql .= " AND is_read = 1 AND is_archived = 0 AND deleted_at IS NULL";
                break;
            case 'archived':
                $sql .= " AND is_archived = 1 AND deleted_at IS NULL";
                break;
            default: // all (active)
                $sql .= " AND is_archived = 0 AND deleted_at IS NULL";
                break;
        }

        $sql .= " ORDER BY created_at DESC LIMIT {$limit}";
        return $this->db->fetchAll($sql, ['user_type' => $userType, 'user_id' => $userId]);
    }

    public function markAsRead($id, $userType, $userId)
    {
        $sql = "UPDATE {$this->table} SET is_read = 1 
                WHERE id = :id AND user_type = :user_type AND user_id = :user_id";
        return $this->db->query($sql, ['id' => $id, 'user_type' => $userType, 'user_id' => $userId]);
    }

    public function archive($id, $userType, $userId)
    {
        $sql = "UPDATE {$this->table} SET is_archived = 1 
                WHERE id = :id AND user_type = :user_type AND user_id = :user_id";
        return $this->db->query($sql, ['id' => $id, 'user_type' => $userType, 'user_id' => $userId]);
    }

    public function softDelete($id, $userType, $userId)
    {
        $sql = "UPDATE {$this->table} SET deleted_at = NOW() 
                WHERE id = :id AND user_type = :user_type AND user_id = :user_id";
        return $this->db->query($sql, ['id' => $id, 'user_type' => $userType, 'user_id' => $userId]);
    }

    public function getById($id, $userType, $userId)
    {
        $sql = "SELECT * FROM {$this->table} 
                WHERE id = :id AND user_type = :user_type AND user_id = :user_id";
        return $this->db->fetchOne($sql, ['id' => $id, 'user_type' => $userType, 'user_id' => $userId]);
    }
}
