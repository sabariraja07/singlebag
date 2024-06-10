<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ProductAttribute extends Model
{
    public $timestamps = false;

    public function Attribute()
    {
    	return $this->belongsTo(Attribute::class, 'attribute_id');
    }

    public function Variation()
    {
    	return $this->belongsTo(Attribute::class, 'variation_id');
    }
}