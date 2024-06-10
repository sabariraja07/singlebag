<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Auth;

class Stock extends Model
{
   public $timestamps = true;
   protected $guarded = [];

   public function Product()
   {
      return $this->belongsTo(Product::class, 'product_id');
   }

   public function Variant()
   {
      return $this->belongsTo(ProductVariant::class, 'variant_id');
   }
}
