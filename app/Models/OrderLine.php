<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderLine extends Model
{
     public $timestamps = false;

     protected $casts = [
          'info' => 'object'
     ];

     public function order()
     {
          return $this->belongsTo(Order::class, 'order_id');
     }

     public function Product()
     {
          return $this->belongsTo(Product::class,  'product_id');
     }

     public function attribute()
     {
          return $this->hasOne(Attribute::class, 'id', 'attribute_id')->with('attribute', 'variation');
     }

     public function file()
     {
          return $this->hasMany(File::class, 'product_id', 'product_id');
     }

     public function stock()
     {
          return $this->hasOne(Stock::class, 'product_id', 'product_id')->where('stock_manage', 1);
     }

     public function order_stock()
     {
          return $this->hasOne(OrderMeta::class, 'order_id', 'order_id')->where('key', 'stock');
     }
}
