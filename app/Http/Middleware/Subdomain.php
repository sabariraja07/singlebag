<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Shop;
use App\Models\Domain;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Carbon\Carbon;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class Subdomain
{
    use ApiResponser;

    public static $domain;
    public static $full_domain;
    public static $autoload_static_data;
    public static $position;
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        URL::defaults(['subdomain' => request('subdomain')]);
        $request->route()->forgetParameter('subdomain');

        $domain = request()->getHost();
        $full_domain = url('/');
        
        Subdomain::$domain = $domain;
        Subdomain::$full_domain = $full_domain;

        // if ($full_domain == env('APP_URL') || $full_domain == env('APP_URL_WITHOUT_WWW')) {
        //     return $next($request);
        // }

        // if ($domain == env('APP_PROTOCOLESS_URL') || str_replace('www.', '', $domain) == env('APP_PROTOCOLESS_URL')) {
        //     return $next($request);
        // }

        $domain = str_replace('www.', '', $domain);
        Subdomain::$domain = $domain;

        if (!Cache::has(Subdomain::$domain . '.admin')) {

            $data = Domain::where('domain', Subdomain::$domain)->where('status', 1)->first();

            if (empty($data)) {
                if(request()->wantsJson()){
                    return $this->error('Store is temporarily unavailable!. Please contact your admin.', 401);
                }
                return redirect()->route('store-unavailable');
            }

            $shop = Shop::where('status', 'active')->where('id', $data->shop_id)->with('theme')->first();
            $shop_expiry = Shop::where('will_expire', '>=',  Carbon::now()->format('Y-m-d'))->where('id', $data->shop_id)->first();
                     
            if (empty($data) || empty($shop)) {
                if(request()->wantsJson()){
                    return $this->error('Store is temporarily unavailable!. Please contact your admin.', 401);
                }
                return redirect()->route('store-unavailable');
            }

            $find_shop = Shop::where('id', $data->shop_id)->first();
            $plan_status = Subscription::where('id', $find_shop->subscription_id)->first();
            if (isset($plan_status) && !empty($plan_status) && !empty($shop_expiry)) {
                if($plan_status->status != 1){
                    if(request()->wantsJson()){
                        return $this->error('Store is temporarily unavailable!. Please contact your admin.', 401);
                    }
                    return redirect()->route('store-unavailable');
                }
            }

            if(auth()->check()) {
                if(DB::table('shop_users')->where('shop_id',$data->shop_id)->where('user_id',Auth::id())->count() == 0) {
                    if(request()->wantsJson()){
                        return $this->error('Unauthenticated.', 401);
                    }
                    return redirect()->route('store-unavailable');
                }
            }

            $value = Cache::remember(Subdomain::$domain . '.admin', 300, function () use($data, $shop){

                $info['domain_id'] = $data->id;
                $info['user_id'] = $shop->user_id;
                $info['domain_name'] = Subdomain::$domain;
                $info['full_domain'] = Subdomain::$full_domain;
                $info['view_path'] = $shop->theme->src_path;
                $info['asset_path'] = $shop->theme->asset_path;
                $info['shop_type'] = $shop->shop_type;
                $info['shop_id'] = $shop->id;
                $info['plan'] = json_decode($shop->data);
                return $info;
            });
        }
        
        return $next($request);
    }
}
