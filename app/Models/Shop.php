<?php

namespace App\Models;

use Carbon\Carbon;
use Illuminate\Database\Eloquent\Model;

class Shop extends Model
{
    protected $table = "shops";

    public $timestamps = true;
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'domain_id',
        'template_id',
    ];
    protected $appends = ['mobilenumber'];

    public function getMobilenumberAttribute()
    {
        $store_mobile_number = ShopOption::where('key', 'store_mobile_number')->where('shop_id', $this->id)->first();

        return $store_mobile_number ? $store_mobile_number->value : '';
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function CreatedUser()
    {
        return $this->hasMany(User::class, 'created_by', 'id');
    }

    public function theme()
    {
        return $this->belongsTo(Template::class, 'template_id', 'id');
    }

    public function SubscriptionWithPlan()
    {
        return $this->belongsTo(Subscription::class, 'subscription_id')->with('plan');
    }

    public function Shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function shop_option_mode()
    {
        return $this->hasOne(ShopOption::class, 'shop_id')->where('key', 'shop_mode');
    }

    public function Products()
    {
        return $this->hasMany(Product::class, 'shop_id');
    }

    public function domain()
    {
        return $this->hasOne(Domain::class, 'shop_id');
    }

    public function domains()
    {
        return $this->hasMany(Domain::class, 'shop_id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'shop_id');
    }

    public function orders_complete()
    {
        return $this->hasMany(Order::class, 'shop_id')->where('status', 'completed');
    }

    public function orders_processing()
    {
        return $this->hasMany(Order::class, 'shop_id')->where('status', '!=', 'completed')->where('status', '!=', 'canceled');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'shop_id')->orderBy('id', 'DESC')->with('plan');
    }

    public function customers()
    {
        return $this->hasMany(User::class, 'created_by', 'id');
    }

    public function ShopUsers()
    {
        return $this->hasMany(ShopUser::class, 'shop_id');
    }

    public function plan_discount()
    {
        $shop = Shop::where('will_expire', '>', Carbon::today())->find($this->id);
        $discount = 0;
        if (isset($shop)) {
            $subscription = $shop->SubscriptionWithPlan()
                ->where('will_expire', '>', Carbon::today())
                ->where('id', $shop->subscription_id)
                ->first();

            if (isset($subscription)) {
                $remaining_days = Carbon::now()->subDays(1)->diffInDays($shop->will_expire, false);
                $amount = $subscription->amount + $subscription->discount - $subscription->tax;
                $discount = round($remaining_days * ($amount / $subscription->plan->days), 2);
            }
        }

        return $discount;
    }

    public function Settlements()
    {
        return $this->hasMany(Settlement::class, 'shop_id');
    }
}
