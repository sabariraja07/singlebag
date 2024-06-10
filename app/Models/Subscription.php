<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscription extends Model
{
    

    public function plan_info()
    {
    	return $this->belongsTo(Plan::class,'plan_id','id');
    }
    public function plan()
    {
        return $this->belongsTo(Plan::class,'plan_id');
    }

    public function category()
    {
        return $this->belongsTo(Category::class,'category_id','id');
    }

    public function PaymentMethod()
    {
        return $this->belongsTo(PaymentMethod::class, 'payment_method', 'slug');
    }

    public function Transaction()
    {
        return $this->belongsTo(Transaction::class,'transaction_id')->with('method');
    }

    public function user()
    {
    	return $this->belongsTo(User::class,'user_id','id');
    }

    public function Shop()
    {
        return $this->belongsTo(Shop::class,'shop_id');
    }

    public function user_with_domain()
    {
       return $this->belongsTo(User::class,'user_id','id')->with(['shop', 'shop.domain']);
    }

    public function latestorder(){
        return $this->hasMany(Subscription::class)->where('will_expire',date('Y-m-d'))->where('status',1);
    }
}
