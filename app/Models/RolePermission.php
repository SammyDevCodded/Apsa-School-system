<?php
namespace App\Models;

use App\Core\Model;

class RolePermission extends Model
{
    protected $table = 'role_permissions';
    protected $fillable = [
        'role_id',
        'permission_id'
    ];

    public function __construct()
    {
        parent::__construct();
    }
}

