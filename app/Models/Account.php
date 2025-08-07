<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Account extends Model
{
    use HasFactory;

    protected $fillable = [
        'name',
        'type',
        'payment_mode_id',
        'account_number',
        'ifsc_code',
        'bank_name',
        'branch',
        'upi_id',
        'holder_name',
        'description',
        'is_active',
    ];

    protected $casts = [
        'is_active' => 'boolean',
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check() && empty($model->user_id)) {
                $model->user_id = auth()->id();
            }
        });
    }

    /**
     * Relationship to payment method
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_mode_id');
    }

    /**
     * Get the display name for the account
     */
    public function getDisplayNameAttribute()
    {
        if ($this->type === 'bank') {
            return $this->name . ' - ' . $this->bank_name . ' (' . $this->account_number . ')';
        } elseif ($this->type === 'upi') {
            return $this->name . ' - ' . $this->upi_id;
        } else {
            return $this->name;
        }
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
