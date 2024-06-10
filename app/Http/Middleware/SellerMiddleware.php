<?php

namespace App\Http\Middleware;

use App\Models\Shop;
use App\Models\ShopUser;
use Carbon\Carbon;
use Illuminate\Http\Request;
use Closure;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;

class SellerMiddleware
{
  /**
   * Handle an incoming request.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  \Closure  $next
   * @return mixed
   */
  public function handle($request, Closure $next)
  {
    if (Auth::check()) {
      // if (Auth::user()->status == 3) {
      //   return redirect(env('APP_URL') . '/merchant/dashboard');
      // }
      // if (Auth::user()->status === 0 || Auth::user()->status == 2) {
      //   return redirect(env('APP_URL') . '/suspended');
      // }
      // if (url('/') == env('APP_URL') && Auth::user()->status == 1) {
      //   Auth::logout();
      //   return redirect()->route('login');
      // }
      $shop_id = current_shop_id();
      if (current_shop_id() == 0) {
        Auth::logout();
        return redirect(env('APP_URL') . '/login');
      }
      $shop = Shop::where('will_expire', '>=',  Carbon::now()->format('Y-m-d'))->where('id', $shop_id)->first();
      if (empty($shop)) {
        return redirect()->route('seller.suspended');
      }
      if (ShopUser::where('shop_id', $shop->id)->where('user_id', auth()->id())->where('status', 1)->count() == 0) {
        Auth::logout();
        return redirect(env('APP_URL') . '/login');
      }
      return $next($request);
    }
    return redirect(env('APP_URL') . '/login');
  }
}
