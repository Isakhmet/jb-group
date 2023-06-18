<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Branch extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'phone',
        'address',
        'slug'
    ];

    public function currencies()
    {
        return $this->hasManyThrough(
            Currency::class,
            BranchCurrency::class,
            'branch_id',
            'id',
            'id',
            'currency_id',
        );
    }

    public function balances()
    {
        return $this->hasMany(BranchCurrency::class, 'branch_id', 'id');
    }

    public function branchCurrencies($isAdditional)
    {
        return BranchCurrency::whereHas('currency', function ($q) use ($isAdditional) {
            $q->where('is_additional', $isAdditional);
        })->where('branch_id', $this->id)->get();
    }
}
