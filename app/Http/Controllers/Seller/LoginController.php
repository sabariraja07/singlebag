<?php

namespace App\Http\Controllers\Seller;

use Image;
use App\Models\User;
use App\Models\Partner;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Support\Facades\Validator;
use App\Providers\RouteServiceProvider;
use Illuminate\Foundation\Auth\AuthenticatesUsers;

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
    protected $redirectTo = RouteServiceProvider::HOME;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest')->except('logout');
    }

    public function login()
    {
        if (Auth::viaRemember()) {
            return redirect()->intended('/seller/dashboard');
        } else {
            return view("seller.auth.login");
        }
    }

    public function authenticate(Request $request)
    {
        $remember = $request->has('remember_me');
        if (Auth::attempt(['email' =>  $request->input('email'), 'password' => $request->input('password')], $remember)) {
            return redirect()->intended('/seller/dashboard');
        } else {
            return view("seller.auth.login", ["error" => trans("You have entered an invalid username or password.")]);
        }
    }

    public function register_form()
    {
        return view("seller.auth.register");
    }

    public function register(Request $request)
    { 
  
      if (env('NOCAPTCHA_SITEKEY') != null) {
        $messages = [
          'g-recaptcha-response.required' => 'You must check the reCAPTCHA.',
          'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
        ];
  
        $request->validate([
          'g-recaptcha-response' => 'required|captcha'
        ], $messages);
      }  
  
      $validatedData = $request->validate([
        'first_name' => 'required|string|max:255',
        'last_name' => 'required|string|max:255',
        'email' => 'required|unique:users|email_address|max:255',
        'password' => 'required|min:8|confirmed|string|without_spaces',
      ]);
  
      DB::beginTransaction();
      try {
        $user = new User();
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        $user->password = Hash::make($request->password);
        $user->status = 1;
        $user->save();
  
        $partner = new Partner();
        $partner->status = 1;
        $partner->user_id = $user->id;
        $partner->save();
  
  
        DB::commit();
        Auth::loginUsingId($user->id);
        $dta['redirect'] = true;
        $dta['domain'] = route('partner.dashboard');
      } catch (\Exception $e) {
        DB::rollback();
      }
  
      return response()->json($dta);
    }

    public function logout(Request $request)
    {
        $this->guard()->logout();

        $request->session()->flush();

        $request->session()->regenerate();

        Auth::logout();

        return Redirect::route('/seller/login');
    }

    public function Forgot()
    {
        return view("admin.auth.forgot-password");
    }
}