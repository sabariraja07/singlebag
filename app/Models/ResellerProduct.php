<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResellerProduct extends Model
{
    protected $table = 'reseller_products';

    public $timestamps = true;

    protected $fillable = [
        'title',
        'slug',
        'price',
        'amount',
        'amount_type',
        'featured',
        'status',
        'seo',
        'product_id',
        'brand_id',
        'category_id',
        'group_product_id',
        'shop_id',
    ];

    protected $casts = [
        'seo' => 'json'
        // 'meta' => 'json'
    ];

    protected $attributes = [];

    public function Category()
    {
        return $this->belongsTo(Category::class, 'category_id');
    }

    public function Brand()
    {
        return $this->belongsTo(Brand::class, 'brand_id');
    }

}