<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Roles extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'name'
    ];

    public function accesses()
    {
        return $this->hasManyThrough(
            Access::class,
            RoleAccess::class,
            'role_id',
            'id',
            'id',
            'access_id'
        );
    }
}
