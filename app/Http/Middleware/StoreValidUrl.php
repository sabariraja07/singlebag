<?php

namespace App\Http\Middleware;

use Closure;
use App\Models\Domain;
use App\Models\Shop;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\URL;
use Illuminate\Support\Facades\Route;

class StoreValidUrl
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @return mixed
     */
    public function handle(Request $request, Closure $next)
    {
        $host = request()->getHost();
        $host = str_replace('www.', '', $host);
        if (isset($host) && $host != 'app.'  . env('APP_PROTOCOLESS_URL') && $host != env('APP_PROTOCOLESS_URL') ) {
            $store_domain = Domain::where('domain', $host)->where('status', 1)->first();
            if (!isset($store_domain))
                return redirect()->route('store-unavailable');
            $shop_not_active = Shop::where('id', $store_domain->shop_id)->where('status','<>','active')->first();
            if (isset($shop_not_active))
                return redirect()->route('store-unavailable');
        }

        return $next($request);
    }
}
