<?php

namespace App\Models;

use App\Models\Attribute;
use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;

class Category extends Model
{
	use FileUploadTrait;

	protected $appends = ['image'];

	public function getImageAttribute()
	{
		$attachment = $this->getFile('image');
		return $attachment->url ?? asset('uploads/default.png');
	}

	public function Parent()
	{
		return $this->belongsTo(Category::class, 'p_id');
	}

	public function Children()
	{
		return $this->hasMany(Category::class, 'p_id');
	}

	public function Childrens()
	{
		return $this->children()->with('childrens');
	}

	public function categories()
	{
		return $this->hasMany(Category::class, 'p_id', 'id');
	}

	public function childrenCategories()
	{
		return $this->hasMany(Category::class, 'p_id', 'id')->with('categories')->where('status', 1);
	}

	public function Products()
	{
		if (current_shop_type() == 'reseller') {
			return $this->belongsToMany(Product::class, 'reseller_products', 'category_id', 'product_id');
		} else {
			return $this->belongsToMany(Product::class, 'product_categories', 'category_id', 'product_id');
		}
	}

	public function Shop()
	{
		return $this->belongsTo(Shop::class, 'shop_id');
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
