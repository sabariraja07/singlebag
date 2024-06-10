<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Review extends Model
{

	public static function boot()
	{
		parent::boot();

		static::saved(function ($model) {
			$rating = $model->where('product_id', $model->product_id)->where('status', 1)->avg('rating');
			Product::where('id', $model->product_id)->update(['avg_rating' => $rating]);
		});
	}

	public function getHumanDate()
	{
		return $this->created_at->diffForHumans();
	}

	public function Customer()
	{
		return $this->belongsTo(Customer::class, 'customer_id');
	}

	public function Product()
	{
		return $this->belongsTo(Product::class, 'product_id');
	}

	public function scopeWithProduct($query)
	{
		if (current_shop_type() == 'reseller') {
			return $query->with(['product.ResellerProduct' => function ($q) {
				$q->where('shop_id', current_shop_id());
			}])->whereHas('product.ResellerProduct', function ($q) {
				$q->where('shop_id', current_shop_id());
			});
		} else {
			return $query->with(['product' => function ($q) {
				$q->where('shop_id', current_shop_id());
			}])->whereHas('product', function ($q) {
				$q->where('shop_id', current_shop_id());
			});
		}
	}

	public function scopeHasCustomer($query)
	{
		if (current_shop_type() == 'seller') {
			return $query->whereHas('customer', function ($q) {
				$q->where('shop_id', current_shop_id());
			});
		}
	}
}
