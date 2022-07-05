<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class UserBranch extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'user_id'
    ];

    public function users()
    {
        return $this->hasOne(User::class, 'id', 'user_id');
    }

    public function branches()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }
}
