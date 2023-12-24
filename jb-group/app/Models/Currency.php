<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'description',
        'limit',
        'is_additional',
    ];

    protected static function boot()
    {
        parent::boot();

        static::addGlobalScope('defaultSort', function ($builder) {
            $builder->orderBy('id', 'asc');
        });
    }
}
