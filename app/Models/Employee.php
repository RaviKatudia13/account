<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Employee extends Model
{
    use HasFactory;

    protected $fillable = [
        'name', 'number', 'address', 'designation', 'join_date', 'employee_category_id'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check() && empty($model->user_id)) {
                $model->user_id = auth()->id();
            }
        });
    }

    public function employeeCategory()
    {
        return $this->belongsTo(EmployeeCategory::class);
    }

    public function dues()
    {
        return $this->hasMany(\App\Models\EmployeeDue::class, 'employee_id');
    }

    public function getTotalRemainingAmountAttribute()
    {
        return $this->dues()->sum('remaining_amount');
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'employee_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 