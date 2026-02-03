<?php
namespace App\Models;

use App\Core\Model;

class User extends Model
{
    protected $table = 'users';
    protected $fillable = [
        'role_id',
        'username',
        'password_hash',
        'first_name',
        'last_name',
        'email',
        'phone',
        'status',
        'last_login'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function findByUsername($username)
    {
        return $this->where('username', $username);
    }

    public function authenticate($username, $password)
    {
        $user = $this->findByUsername($username);
        
        if ($user && password_verify($password, $user[0]['password_hash'])) {
            return $user[0];
        }
        
        return false;
    }

    public function createWithPassword($data)
    {
        // Hash the password
        $data['password_hash'] = password_hash($data['password'], PASSWORD_DEFAULT);
        
        // Remove plain text password
        unset($data['password']);
        
        // Set default status
        $data['status'] = $data['status'] ?? 'active';
        
        return $this->create($data);
    }
    
    public function updatePassword($id, $password)
    {
        $data = [
            'password_hash' => password_hash($password, PASSWORD_DEFAULT)
        ];
        
        return $this->update($id, $data);
    }
}