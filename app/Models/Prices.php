<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Prices extends Model
{

    public $timestamps = false;

    protected $appends = ['selling_price', 'offer_discount'];

    public function getSellingPriceAttribute()
    {
        if (current_shop_type() == 'reseller') {
            $product = ResellerProduct::where('shop_id', current_shop_id())->where('product_id', $this->product_id)->first();
            return $product->price ?? $this->price;
        }

        return $this->price;
    }

    public function getOfferDiscountAttribute()
    {
        if (current_shop_type() != 'reseller') {
            if ($this->price < $this->regular_price) {
                return round((($this->regular_price - $this->price) / $this->regular_price) * 100, 0);
            }
        }

        return 0;
    }

    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }
}
