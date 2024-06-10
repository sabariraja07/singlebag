<?php

namespace App\Models;

use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Illuminate\Support\Facades\Auth;

class Customer extends Authenticatable
{
    use Notifiable, HasApiTokens;

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'first_name',
        'last_name',
        'email',
        'password',
        'mobile',
        'status'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'password',
        'remember_token',
    ];

    /**
     * The attributes that should be cast to native types.
     *
     * @var array
     */
    protected $casts = [
        'email_verified_at' => 'datetime',
    ];

    protected $appends = ['fullname'];

    public function getFullNameAttribute()
    {
        return ucfirst(strtolower($this->first_name)) . ' ' . ucfirst(strtolower($this->last_name));
    }

    public function agent_orders() //agent_order
    {
        return $this->hasMany(Order::class, 'agent_id', 'id');
    }

    public function agent_orders_processing() //agent_order_processing
    {
        return $this->hasMany(Order::class, 'agent_id', 'id')->where('status', '!=', 'pending')->where('status', '!=', 'processing')->where('status', '!=', 'delivered')->where('status', '!=', 'completed')->where('status', '!=', 'canceled')->where('status', '!=', 'ready-for-pickup')->where('status', '!=', 'archived');
    }

    public function agent_orders_complete()   //agent_order_complete
    {
        return $this->hasMany(Order::class, 'agent_id', 'id')->where('status', 'completed');
    }

    public function agent_orders_ready_pickup()   //agent_order_pickup
    {
        return $this->hasMany(Order::class, 'agent_id', 'id')->where('status', 'ready-for-pickup');
    }



    public function user_domain()
    {
        return $this->Shop();
    }

    public function Shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }

    public function shop_url()
    {
        if (!Cache::has('customer.' . Auth::guard('customers')->id() . '.shop_url')) {
            Cache::remember('customer.' . Auth::guard('customers')->id() . '.shop_url', 300, function () {
                $shop = Shop::where('id', $this->shop_id)->first();
                return $shop ? env('APP_PROTOCOL') . Str::lower($shop->sub_domain)  . '.' . env('APP_PROTOCOLESS_URL') : "";
            });
        }

        return Cache::get('customer.' . Auth::guard('customers')->id() . '.shop_url');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id')->where('shop_id', current_shop_id());
    }

    public function orders_complete()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id')->where('status', 'completed')->where('shop_id', current_shop_id());
    }

    public function orders_processing()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id')->where('status', '!=', 'completed')->where('status', '!=', 'canceled')->where('shop_id', current_shop_id());
    }

    public function preview()
    {
        return $this->hasOne(DeliveryAgent::class, 'customer_id');
    }

    public function delivery_agent()
    {
        return $this->hasOne(DeliveryAgent::class, 'customer_id')->where('status', 1);
    }

    public function Rider()
    {
        return $this->hasOne(DeliveryAgent::class, 'customer_id');
    }

    public function delivery_orders()
    {
        return $this->hasMany(Order::class, 'agent_id');
    }

    public function search_agent_avathar()
    {
        return $this->hasOne(DeliveryAgent::class, 'customer_id', 'id');
    }

    public function agent_preview()
    {
        return $this->hasOne(DeliveryAgent::class, 'customer_id')->where('status', '!=', 5);
    }
}
