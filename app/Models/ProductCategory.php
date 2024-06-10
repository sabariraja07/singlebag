<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductCategory extends Model
{
    public $timestamps = false;

    public function Category()
    {
    	return $this->belongsTo(Category::class, 'category_id');
    }

    public function Product()
    {
    	return $this->belongsTo(Product::class, 'product_id');
    }
}