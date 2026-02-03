<?php
namespace App\Models;

use App\Core\Model;

class Notification extends Model
{
    protected $table = 'notifications';
    protected $fillable = [
        'user_id',
        'message',
        'type',
        'is_read',
        'related_id',
        'related_type'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function getAllForUser($userId)
    {
        $sql = "
            SELECT * FROM {$this->table} 
            WHERE user_id = :user_id 
            ORDER BY created_at DESC
        ";
        return $this->db->fetchAll($sql, ['user_id' => $userId]);
    }

    public function getUnreadCount($userId)
    {
        $sql = "
            SELECT COUNT(*) as count FROM {$this->table} 
            WHERE user_id = :user_id AND is_read = 0
        ";
        $result = $this->db->fetchOne($sql, ['user_id' => $userId]);
        return $result['count'];
    }

    public function markAsRead($id)
    {
        $data = ['is_read' => 1];
        return $this->update($id, $data);
    }

    public function markAllAsRead($userId)
    {
        $sql = "
            UPDATE {$this->table} 
            SET is_read = 1 
            WHERE user_id = :user_id AND is_read = 0
        ";
        return $this->db->execute($sql, ['user_id' => $userId]);
    }
}

