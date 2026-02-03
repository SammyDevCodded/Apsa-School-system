<?php
namespace App\Models;

use App\Core\Model;

class Role extends Model
{
    protected $table = 'roles';
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

