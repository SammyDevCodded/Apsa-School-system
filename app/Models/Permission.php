<?php
namespace App\Models;

use App\Core\Model;

class Permission extends Model
{
    protected $table = 'permissions';
    protected $fillable = [
        'name',
        'description'
    ];

    public function __construct()
    {
        parent::__construct();
    }

    public function findByName($name)
    {
        return $this->where('name', $name);
    }
}

