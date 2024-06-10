<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Coupon extends Model
{
    protected $table = 'coupons';

    public $timestamps = true;

    protected $fillable = [
        'title',
        'description',
        'code',
        'expiry_date',
        'amount',
        'discount_type',
        'meta',
        'status',
        'user_id',
        'shop_id',
    ];

    protected $casts = [
        'meta' => 'json'
    ];

    protected $attributes = [];

    public function Shop()
	{
		return $this->belongsTo(Shop::class, 'shop_id');
	}

    public function User()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

    public function scopeByShop($query)
    {
        return $query->where('shop_id', current_shop_id());
    }

    public function scopeIsActive($query, $status = 1)
    {
        return $query->where('status', $status);
    }

}