<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class RoleAccess extends Model
{
    use HasFactory;

    protected $fillable = [
        'access_id',
        'role_id'
    ];

    public function accesses()
    {
        return $this->belongsTo(Access::class, 'access_id', 'id');
    }

    public function roles()
    {
        return $this->belongsTo(Roles::class, 'role_id', 'id');
    }
}
