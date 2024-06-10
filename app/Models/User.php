<?php

namespace App\Models;

use Illuminate\Support\Facades\Auth;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Spatie\Permission\Traits\HasRoles;
use Laravel\Sanctum\HasApiTokens;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;

class User extends Authenticatable
{
    use Notifiable, HasRoles, HasApiTokens;

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


    public static function getpermissionGroups()
    {
        $permission_groups = DB::table('permissions')
            ->select('group_name as name')
            ->groupBy('group_name')
            ->get();
        return $permission_groups;
    }

    public static function getPermissionGroup()
    {
        return $permission_groups = DB::table('permissions')->select('group_name')->groupBy('group_name')->get();
    }

    public static function getpermissionsByGroupName($group_name)
    {
        $permissions = DB::table('permissions')
            ->select('name', 'id')
            ->where('group_name', $group_name)
            ->get();
        return $permissions;
    }

    public static function roleHasPermissions($role, $permissions)
    {
        $hasPermission = true;
        foreach ($permissions as $permission) {
            if (!$role->hasPermissionTo($permission->name)) {
                $hasPermission = false;
                return $hasPermission;
            }
        }
        return $hasPermission;
    }

    public function usermeta()
    {
        return $this->hasOne(UserMeta::class)->where('type', 'content');
    }

    public function Shops()
    {
        return $this->hasMany(Shop::class, 'user_id');
    }

    public function Shop()
    {
        return $this->hasOne(Shop::class, 'user_id');
    }

    public function Partner()
    {
        return $this->hasOne(Partner::class, 'user_id');
    }

    public function isPartner()
    {
        if (!Cache::has(Auth::id() . '.is_partner')) {
            Cache::remember(Auth::id() . '.is_partner', 300, function () {
                $partner = $this->Partner()->where('status', 1)->first();
                return $partner ? true : false;
            });
        }
        return Cache::get(Auth::id() . '.is_partner');
    }

    public function isInActivePartner()
    {
        if (!Cache::has(Auth::id() . '.is_in_active_partner')) {
            Cache::remember(Auth::id() . '.is_in_active_partner', 300, function () {
                $partner = $this->Partner()->where('status', 0)->first();
                return $partner ? true : false;
            });
        }
        return Cache::get(Auth::id() . '.is_in_active_partner');
    }


    public function current_shop_ids()
    {
        if (!Cache::has('seller.' . Auth::id() . '.current_shop_id')) {
            Cache::remember('seller.' . Auth::id() . '.current_shop_id', 300, function () {
                $shop = Shop::where('user_id', Auth::id())->first();
                return $shop->id ?? 0;
            });
        }

        return Cache::get('seller.' . Auth::id() . '.current_shop_id');
    }

    public function current_shop_url()
    {
        if (!Cache::has('seller.' . Auth::id() . '.current_shop_url')) {
            Cache::remember('seller.' . Auth::id() . '.current_shop_url', 300, function () {
                $shop = Shop::where('user_id', Auth::id())->where('id', current_shop_id())->first();
                return $shop ? env('APP_PROTOCOL') . Str::lower($shop->sub_domain)  . '.' . env('APP_PROTOCOLESS_URL') : "";
            });
        }

        return Cache::get('seller.' . Auth::id() . '.current_shop_url');
    }

    public function current_shop()
    {
        return $this->hasOne(Shop::class, 'user_id')->where('user_id', Auth::id());
    }

    public function isShopAdmin()
    {
        return Shop::where('user_id', Auth::id())->first() ? true : false;
    }

    public function user_domain()
    {
        return $this->belongsTo(Domain::class, 'domain_id', 'id');
    }

    public function orders()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id');
    }

    public function orders_complete()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id')->where('status', 'completed');
    }

    public function orders_processing()
    {
        return $this->hasMany(Order::class, 'customer_id', 'id')->where('status', '!=', 'completed')->where('status', '!=', 'canceled');
    }

    public function subscription()
    {
        return $this->hasOne(Subscription::class, 'user_id', 'id')->orderBy('id', 'DESC')->where('status', 1)->with('plan_info');
    }

    public function ShopWithPlan()
    {
        return Shop::with(['subscription', 'subscription.id'])->find(current_shop_id());
    }

    public function customers()
    {
        return $this->hasMany(User::class, 'created_by', 'id');
    }

    public function shop_user()
    {
        return $this->hasOne(ShopUser::class, 'user_id');
    }

    public function shop_users()
    {
        return $this->hasMany(ShopUser::class, 'user_id');
    }

    public function scopeWithShopRole($query, $shop_id)
    {
        return DB::table('roles')
            ->join('shop_users', 'shop_users.role_id', '=', 'roles.id')
            ->where('shop_users.user_id', $this->id)
            ->where('shop_users.shop_id', $shop_id)
            ->select('roles.name')
            ->take(1);
    }

    public function my_shop_role()
    {
        return "Admin";
    }
}
