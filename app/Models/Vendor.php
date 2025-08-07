<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Vendor extends Model
{
    use HasFactory;

    protected $fillable = ['name', 'address', 'phone', 'vendor_category_id'];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check() && empty($model->user_id)) {
                $model->user_id = auth()->id();
            }
        });
    }

    public function vendorCategory()
    {
        return $this->belongsTo(VendorCategory::class);
    }

    public function dues()
    {
        return $this->hasMany(\App\Models\VendorDue::class, 'vendor_id');
    }

    public function getTotalRemainingAmountAttribute()
    {
        return $this->dues()->sum('remaining_amount');
    }

    public function payments()
    {
        return $this->hasMany(\App\Models\Payment::class, 'vendor_id');
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 