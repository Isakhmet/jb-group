<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PurchasingProduct extends Model
{
    use HasFactory;

    protected $fillable = [
        'purchasing_requests_id',
        'product_id',
        'count',
    ];

    public function product()
    {
        return $this->hasOne(Product::class, 'id', 'product_id');
    }

    public function purchasingRequest()
    {
        return $this->belongsTo(PurchasingRequests::class, 'purchasing_requests_id', 'id');
    }
}
