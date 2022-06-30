<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BranchCurrency extends Model
{
    use HasFactory;

    protected $fillable = [
        'branch_id',
        'currency_id',
        'balance',
        'is_limited',
    ];

    public function currency()
    {
        return $this->hasOne(Currency::class, 'id', 'currency_id');
    }
}
