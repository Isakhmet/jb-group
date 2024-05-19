<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Employee extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = [
        'name',
        'iin',
        'phone',
        'address',
        'addition_phone',
        'branch_id',
        'position'
    ];

    public function branch()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('defaultSort', function ($builder) {
            $builder->orderBy('id', 'asc');
        });
    }
}
