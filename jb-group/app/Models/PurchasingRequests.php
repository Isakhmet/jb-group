<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

class PurchasingRequests extends Model
{
    use HasFactory;

    protected $fillable = ['branch_id', 'list', 'date', 'user_id'];

    public function branches()
    {
        return $this->hasOne(Branch::class, 'id', 'branch_id');
    }

    public function getDateAttribute($date)
    {
        return Carbon::createFromFormat('Y-m-d H:i:s', $date)->format('Y-m-d');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'id', 'user_id');
    }

    public function products()
    {
        return $this->hasMany(PurchasingProduct::class, 'purchasing_requests_id', 'id');
    }

    public function status()
    {
        return Status::find($this->status_id)->description;
    }
}
