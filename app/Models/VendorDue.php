<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class VendorDue extends Model
{
    protected $table = 'vendor_due';

    protected $fillable = [
        'vendor_id', 'date', 'total_amount', 'paid_amount', 'remaining_amount', 'description', 'user_id'
    ];

    public function vendor()
    {
        return $this->belongsTo(Vendor::class);
    }
} 