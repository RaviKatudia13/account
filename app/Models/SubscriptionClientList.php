<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class SubscriptionClientList extends Model
{
    use HasFactory;

    protected $table = 'subscriptions_client_list';

    protected $fillable = [
        'name',
        'company_name',
        'mobile',
        'email',
        'address',
        'gst_registered',
        'gstin',
        'payment_mode',
        'category_id'
    ];

    protected static function booted()
    {
        static::creating(function ($model) {
            if (auth()->check() && empty($model->user_id)) {
                $model->user_id = auth()->id();
            }
        });
    }

    // public function category()
    // {
    //     return $this->belongsTo(SubscriptionClientCategory::class, 'category_id');
    // }
    public function category()
    {
        return $this->belongsTo(Category::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }
} 
