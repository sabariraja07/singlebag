<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ShippingLocation extends Model
{
    public $timestamps = true;
    protected $fillable = [
        'shipping_method_id',
        'city_id',
        'state_id',
        'country_id',
        'country_code',
        'city',
        'state',
        'country',
        'zip_code',
        'rate',
        'estimated_days',
        'estimate_info',
        'status',
    ];

    public function Method()
    {
        return $this->belongsTo(ShippingMethod::class, 'shipping_method_id');
    }

    public function City()
    {
        return $this->belongsTo(City::class, 'city_id');
    }
}
