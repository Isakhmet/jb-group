<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class ScheduleEmployee extends Model
{
    use SoftDeletes;
    use HasFactory;

    protected $fillable = ['employee_id', 'branch_id', 'number_cash_desk','date'];

    public function employee()
    {
        return $this->belongsTo(Employee::class, 'employee_id', 'id');
    }

    public function branch()
    {
        return $this->belongsTo(Branch::class,'branch_id', 'id');
    }
}
