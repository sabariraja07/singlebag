<?php

namespace App\Http\Controllers\Auth;

use App\Http\Controllers\Controller;
use App\Models\Domain;
use App\Models\Shop;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;
use Illuminate\Support\Facades\Auth;
use App\Models\User;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Log;
use Illuminate\Http\Request;
use App\Rules\StoreUser;

class LoginController extends Controller
{
  /*
    |--------------------------------------------------------------------------
    | Login Controller
    |--------------------------------------------------------------------------
    |
    | This controller handles authenticating users for the application and
    | redirecting them to your home screen. The controller uses a trait
    | to conveniently provide its functionality to your applications.
    |
    */

  use AuthenticatesUsers;

  /**
   * Where to redirect users after login.
   *
   * @var string
   */
  //protected $redirectTo = RouteServiceProvider::HOME;

  /**
   * Create a new controller instance.
   *
   * @return void
   */
  public function __construct()
  {
    $this->middleware('guest')->except('logout');
  }

  protected function validateLogin(Request $request)
  {

    $rules = [
      'email' => 'required|email_address|max:255',
      'password' => 'required',
    ];
    if (env('NOCAPTCHA_SITEKEY') != null) {
      $messages = [
        'g-recaptcha-response.required' => 'You must check the reCAPTCHA.',
        'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
      ];

      $rules['g-recaptcha-response'] = 'required|captcha';
    }
    $domain = Domain::where('domain', request()->getHost())
        ->where('status', 1)
        ->first();
    if(isset($domain)){
      $rules['email'] = ['required','email', new StoreUser()];
    }
    $url = $_SERVER['REQUEST_URI'];
    $request_uri = explode('/', $url)[1];
    if($request_uri == "login"){
      $rules['email'] = ['required','email', new StoreUser()];
    }
    $this->validate($request, $rules, $messages ?? []);
  }

  public function redirectTo()
  {
    if (Auth::user()->hasRole('superadmin')) {
      // if (url('/') != env('APP_URL')) {
      //   Auth::logout();
      //   $this->redirectTo = env('APP_URL') . '/login';
      //   return $this->redirectTo;
      // } else {
        $this->redirectTo = env('APP_URL') . '/admin/dashboard';
        return $this->redirectTo;
      // }
    // } elseif (Auth::user()->role_id == 2) {
    //   $url = Auth::user()->user_domain->full_domain;

    //   if (str_replace('www.', '', url('/')) != $url) {
    //     Auth::logout();
    //     return $this->redirectTo = $url . '/user/login';
    //   } else {
    //     return  $this->redirectTo = $url . '/user/dashboard';
    //   }
    } elseif (current_shop_id() != 0) {
      Log::error( Auth::user()->current_shop_url());
      $this->redirectTo = Auth::user()->current_shop_url() . '/seller/dashboard';
      return $this->redirectTo;
      // $url = Auth::user()->user_domain->full_domain;
      // if (Auth::user()->status == 3) {
      //   $this->redirectTo = env('APP_URL') . '/merchant/dashboard';
      //   return $this->redirectTo;
      // } elseif (Auth::user()->status === 0 || Auth::user()->status == 2) {
      //   $this->redirectTo = env('APP_URL') . '/suspended';
      //   return $this->redirectTo;
      // } elseif (url('/') != $url && Auth::user()->status != 3) {
      //   Auth::logout();
      //   return  $this->redirectTo = $url . '/login';
      // } else {
      //   if (url('/') != $url) {s
      //     Auth::logout();
      //     return  $this->redirectTo = $url . '/login';
      //   }
      //   return $this->redirectTo = $url . '/seller/dashboard';
      // }
    } elseif (Auth::user()->isPartner()) {
      $this->redirectTo = env('APP_URL') . '/partner/dashboard';
      return $this->redirectTo;
    }
    $this->middleware('guest')->except('logout');
  }
}
