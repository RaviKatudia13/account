<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionClientPayment extends Model
{
    use HasFactory;

    protected $table = 'subscription_client_payments';

    protected $fillable = [
        'client_id',
        'gst_type',
        'invoice_number',
        'gstin',
        'start_date',
        'end_date',
        'subtotal',
        'gst_amount',
        'total',
        'description',
    ];

    protected $casts = [
        'items' => 'array',
        'invoice_date' => 'date',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check() && empty($model->user_id)) {
                $model->user_id = auth()->id();
            }
        });
    }

    public function client()
    {
        return $this->belongsTo(SubscriptionClientList::class, 'client_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 