<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    use SoftDeletes;
    use HasApiTokens, HasFactory, Notifiable;

    /**
     * The attributes that are mass assignable.
     *
     * @var array<int, string>
     */
    protected $fillable = [
        'name',
        'password',
    ];

    public function roles()
    {
        return $this->hasOneThrough(
            'App\Models\Roles',
            'App\Models\UserRole',
            'user_id',
            'id',
            'id',
            'role_id'
        );
    }

    public function branches()
    {
        return $this->hasManyThrough(
            Branch::class,
            UserBranch::class,
            'user_id',
            'id',
            'id',
            'branch_id'
        );
    }
}
