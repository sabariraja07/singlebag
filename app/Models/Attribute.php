<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Attribute extends Model
{
    protected $table = 'attributes';

    public $timestamps = true;

    protected $fillable = [
        'id',
        'name',
        'slug',
        'featured',
        'parent_id',
        'status',
        'user_id',
        'shop_id',
    ];

    protected $casts = [
        // 'meta' => 'json'
    ];

    protected $attributes = [];

    public function Parent()
    {
        return $this->belongsTo(Attribute::class, 'parent_id');
    }

    public function Children()
    {
        return $this->hasMany(Attribute::class, 'parent_id');
    }

    public function Options()
    {
        return $this->hasMany(Attribute::class, 'parent_id')->where('status', 1);
    }

    public function ProductOptions()
    {
        return $this->hasMany(ProductAttribute::class, 'variation_id');
    }

    public function Variations()
    {
        return $this->belongsToMany(Product::class, 'product_attributes', 'product_id', 'variation_id');
    }

    public function Products()
    {
        return $this->belongsToMany(Product::class, 'product_attributes', 'attribute_id', 'product_id')->where('status', 1);
    }

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

    public function featured_with_product_count()
    {
        return $this->hasMany(Attribute::class, 'parent_id')->where('status', 1)->where('featured', 1)->withCount('products');
    }

    public function attribute_with_product_count()
    {
        return $this->hasMany(ProductAttribute::class, 'attribute_id', 'id');
    }
}
