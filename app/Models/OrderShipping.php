<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderShipping extends Model
{
     public $timestamps = false;

     protected $fillable = [
          'location_id',
          'shipping_id',
          'order_id',
          'amount',
      ];

     public function City()
     {
     	return $this->belongsTo(City::class, 'location_id');
     }

     public function shipping_method()
     {
     	return $this->belongsTo(ShippingMethod::class, 'shipping_id');
     }

     public function Method()
     {
          return $this->belongsTo(ShippingMethod::class, 'shipping_id');
     }
}
