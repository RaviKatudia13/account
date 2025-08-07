<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmployeeDue extends Model
{
    protected $table = 'employee_due';

    protected $fillable = [
        'employee_id', 'date', 'total_amount', 'paid_amount', 'remaining_amount', 'description', 'user_id'
    ];

    public function employee()
    {
        return $this->belongsTo(Employee::class);
    }
} 