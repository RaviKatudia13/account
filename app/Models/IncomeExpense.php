<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class IncomeExpense extends Model
{
    use HasFactory;

    protected $fillable = [
        'type', 'emp_vendor_id', 'emp_vendor_type', 'date', 'amount', 'inc_exp_category_id'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check() && empty($model->user_id)) {
                $model->user_id = auth()->id();
            }
        });
    }

    public function category()
    {
        return $this->belongsTo(IncExpCategory::class, 'inc_exp_category_id');
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'expense_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 