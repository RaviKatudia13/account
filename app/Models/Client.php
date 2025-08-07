<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Client extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'company_name',
        'mobile',
        'email',
        'address',
        'gst_registered',
        'gstin',
        'category_id',
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
        return $this->belongsTo(Category::class);
    }

    public function invoices()
    {
        return $this->hasMany(Invoice::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function dueAmount()
    {
        return $this->invoices
            ->where('status', '!=', 'Paid')
            ->sum(function ($invoice) {
                $paid = $invoice->payments->sum('amount');
                return $invoice->total - $paid;
            });
    }
}
