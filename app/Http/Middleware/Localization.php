<?php

namespace App\Http\Middleware;

use Closure;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
class Localization
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
        //  if (Session::has('locale')==true) {

        //     \App::setlocale(\Session::get('locale'));
        // }
        // else{
        //     Session::put('locale',env('DEFAULT_LANG'));
        //     \App::setlocale(\Session::get('locale'));

        // }
        if (Session::has('locale')) {
            app()->setLocale(Session::get('locale'));
        } else if($request->route()->getPrefix() == 'seller' && domain_info('shop_id')) {
            set_language();
        }
        return $next($request);
    }
}
