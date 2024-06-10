<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Domain extends Model
{
    protected $table="domains";

    public function Shop()
    {
    	return $this->belongsTo(Shop::class, 'shop_id');
    }
    public function user()
    {
    	return $this->belongsTo(User::class,'shop_id','id');
    }
   
}
