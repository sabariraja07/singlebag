<?php

namespace App\Http\Middleware;

use Closure;
use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Cache;
use App\Models\Domain as DomainModel;

class Domain
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

        $domain = request()->getHost();

        $full_domain = url('/');

        URL::defaults(['domain' => request('domain') ?? $domain]);
        $request->route()->forgetParameter('domain');

        Domain::$domain = $domain;
        Domain::$full_domain = $full_domain;

        if ($full_domain == env('APP_URL') || $full_domain == env('APP_URL_WITHOUT_WWW')) {
            return $next($request);
        }

        if ($domain == env('APP_PROTOCOLESS_URL') || str_replace('www.', '', $domain) == env('APP_PROTOCOLESS_URL')) {
            return $next($request);
        }

        $domain = str_replace('www.', '', $domain);
        Domain::$domain = $domain;

        if (Cache::has(Domain::$domain . '.admin')) {
            Cache::forget(Domain::$domain . '.admin');
        }

        if (!Cache::has(Domain::$domain)) {

            $data = DomainModel::where('domain', Domain::$domain)->where('status', 1)->first();

            if (empty($data)) {
                if (request()->wantsJson()) {
                    return $this->error('Store is temporarily unavailable!. Please contact your admin.', 401);
                }
                return redirect()->route('store-unavailable');
            }

            //$shop = Shop::where('status', 'active')->whereDate('will_expire', '>',  Carbon::now()->format('Y-m-d'))->where('id', $data->shop_id)->with('theme')->first();
            $shop = Shop::where('status', 'active')->whereDate('will_expire', '>=', Carbon::now()->format('Y-m-d'))->where('id', $data->shop_id)->with('theme', 'shop_option_mode')->first();
            $shop_expiry = Shop::where('will_expire', '>=',  Carbon::now()->format('Y-m-d'))->where('id', $data->shop_id)->first();
            $supplier_shop = Shop::where('status', 'active')->where('id', $data->shop_id)->where('shop_type','supplier')->first();

            if (!empty($supplier_shop)) {
              
                return redirect()->route('store-unavailable');
            }

            if (empty($data) || empty($shop)) {
                if (request()->wantsJson()) {
                    return $this->error('Store is temporarily unavailable!. Please contact your admin.', 401);
                }
                return redirect()->route('store-unavailable');
            }

            $shop_option_mode = $shop->shop_option_mode ? $shop->shop_option_mode->value : 'online';

            if ($shop->shop_type != 'supplier') {
                if ($shop_option_mode == 'offline') {
                    if (request()->wantsJson()) {
                        return $this->error('Store is temporarily unavailable!. Please contact your admin.', 401);
                    }
                    return redirect()->route('store-maintenance');
                }
            }

            $find_shop = Shop::where('id', $data->shop_id)->first();
            $plan_status = Subscription::where('id', $find_shop->subscription_id)->first();
            if (isset($plan_status) && !empty($plan_status) && !empty($shop_expiry)) {
                if ($plan_status->status != 1) {
                    return redirect()->route('store-unavailable');
                }
            }

            $value = Cache::remember(Domain::$domain, 300, function () use ($data, $shop, $shop_option_mode) {
                // session('shop_id', $shop->id);

                $info['domain_id'] = $data->id;
                $info['user_id'] = $shop->user_id;
                $info['domain_name'] = Domain::$domain;
                $info['full_domain'] = Domain::$full_domain;
                $info['view_path'] = $shop->theme->src_path;
                $info['asset_path'] = $shop->theme->asset_path;
                $info['shop_type'] = $shop->shop_type;
                $info['shop_id'] = $shop->id;
                $info['plan'] = json_decode($shop->data);
                $info['store_is_online'] = $shop_option_mode;
                return $info;
            });
        }

        // if (domain_info('store_is_online', 'online') == 'offline') {
        //     return redirect()->route('store-maintenance');
        // }


        return $next($request);
    }
}
