<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingMethod extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'title',
        'cost',
        'estimated_days',
        'estimated_delivery',
        'is_free',
        'shop_id',
        'status',
    ];

    public function Locations()
    {
        return $this->hasMany(ShippingLocation::class, 'shipping_method_id');
    }
    public function shipping_locations()
    {
        return $this->hasMany(ShippingLocation::class, 'shipping_method_id')->where('status', 1);
    }
}
