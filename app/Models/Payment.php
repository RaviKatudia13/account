<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'invoice_id',
        'amount',
        'payment_date',
        'payment_mode',
        'account_id',
        'remarks',
        'recorded_by',
        'vendor_id',
        'employee_id',
        'type',
        'internal_transfer',
        'subscription_client_id',
        'subscription_client_payment_id',
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
     * Relationship to the invoice.
     */
    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }

    /**
     * Relationship to the vendor.
     */
    public function vendor()
    {
        return $this->belongsTo(\App\Models\Vendor::class, 'vendor_id');
    }

    /**
     * Relationship to the employee.
     */
    public function employee()
    {
        return $this->belongsTo(\App\Models\Employee::class, 'employee_id');
    }

    /**
     * Relationship to the user who recorded the payment.
     */
    public function recordedByUser()
    {
        return $this->belongsTo(User::class, 'recorded_by');
    }

    /**
     * Relationship to the payment method used.
     */
    public function paymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_mode');
    }

    /**
     * Relationship to the account used.
     */
    public function account()
    {
        return $this->belongsTo(\App\Models\Account::class, 'account_id');
    }

    /**
     * Relationship to the subscription client payment.
     */
    public function subscriptionClientPayment()
    {
        return $this->belongsTo(\App\Models\SubscriptionClientPayment::class, 'subscription_client_payment_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
