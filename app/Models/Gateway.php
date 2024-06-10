<?php

namespace App\Models;

use Faker\Provider\ar_EG\Payment;
use Illuminate\Database\Eloquent\Model;

class Gateway extends Model
{
    protected $casts = [
        'content' => 'json'
    ];

    public function scopeByShop($query)
    {
        return $query->where('shop_id', current_shop_id());
    }

    public function scopeIsActive($query, $status = 1)
    {
        return $query->where('status', $status);
    }

    public function Method()
    {
    	return $this->belongsTo(PaymentMethod::class,'payment_method', 'slug');
    }

    // public function method()
    // {
    // 	return $this->belongsTo(Category::class,'category_id');
    // }
}
