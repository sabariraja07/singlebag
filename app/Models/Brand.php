<?php

namespace App\Models;

use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;

class Brand extends Model
{
    use FileUploadTrait;

    protected $table = 'brands';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
        'featured',
        'status',
        'user_id',
        'shop_id',
    ];

    protected $casts = [
        // 'meta' => 'json'
    ];

    protected $attributes = [];

    protected $appends = ['image'];

    public function getImageAttribute()
    {
        $attachment = $this->getFile('image');
        return $attachment->url ?? asset('uploads/default.png');
    }

    public function Products()
	{
		if (current_shop_type() == 'reseller') {
            return $this->belongsToMany(Product::class, 'reseller_products', 'brand_id', 'product_id');
        } else {
            return $this->hasMany(Product::class, 'brand_id');
        }
	}

    public function take_products($limit = 10)
	{
		return $this->products()->take($limit);
	}

    public function ResellerProducts()
	{
        return $this->hasMany(ResellerProduct::class, 'brand_id');
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

}