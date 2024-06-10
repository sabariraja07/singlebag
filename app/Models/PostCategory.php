<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostCategory extends Model
{
    public $timestamps = false;

    public function category()
    {
    	return $this->belongsTo(Category::class)->where('type','category');
    }
}
