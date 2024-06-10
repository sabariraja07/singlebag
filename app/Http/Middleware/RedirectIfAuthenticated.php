<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use App\Providers\RouteServiceProvider;
use Closure;
use App\Models\Domain;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Redirect;

class RedirectIfAuthenticated
{
    /**
     * Handle an incoming request.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \Closure  $next
     * @param  string|null  ...$guards
     * @return mixed
     */
    public function handle(Request $request, Closure $next, ...$guards)
    {
        $guards = empty($guards) ? [null] : $guards;

        foreach ($guards as $guard) {

            if (Auth::guard($guard)->check() && $guard == "customer") {
                $url =  Auth::guard($guard)->user()->shop_url();
                return redirect($url . '/user/dashboard');
            } else if (Auth::check() && Auth::user()->hasRole('superadmin')) {
                return redirect(env('APP_URL') . '/admin/dashboard');
            } else if (Auth::check() && current_shop_id() != 0) {
                $user = Auth::user();
                if ($user->email_verified_at == null) {
                    return Redirect::route('resend_store_otp',auth()->id());
                    return redirect()->intended('/otp');
                }
                $domain = Domain::where('domain', request()->getHost())->where('status', 1)->first();
                $shop = isset($domain) ? Shop::whereHas('ShopUsers', function($q) use($user) {
                    $q->where('user_id', $user->id);
                 })->where('id', $domain->shop_id)->first() : null;
                
                if($shop) {
                    $url = env('APP_PROTOCOL') . Str::lower($shop->sub_domain)  . '.' . env('APP_PROTOCOLESS_URL');
                    return redirect($url . '/seller/dashboard');
                }

                return redirect()->route('store-unavailable');
            } else if (Auth::check() && Auth::user()->isPartner()) {
                return redirect(env('APP_URL') . '/partner/dashboard');
            }
        }

        return $next($request);
    }
}
