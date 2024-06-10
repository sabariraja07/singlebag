<?php

namespace App\Http\Controllers\Partner;

use Image;
use Carbon\Carbon;
use App\Models\User;
use App\Models\Partner;
// use App\Rules\EmailSpam;
use Illuminate\Http\Request;
use App\Mail\PartnerOtpMail;
use App\Mail\PartnerRegisterMail;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Providers\RouteServiceProvider;
use Illuminate\Support\Facades\Redirect;
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
      return redirect()->intended('/partner/dashboard');
    } else {
      return view("partner.auth.login");
    }
  }

  public function authenticate(Request $request)
  {
    $remember = $request->remember ?? 0;

    if (env('NOCAPTCHA_SITEKEY') != null) {
      $messages = [
        'g-recaptcha-response.required' => 'You must check the reCAPTCHA.',
        'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
      ];

      $request->validate([
        'g-recaptcha-response' => 'required|captcha'
      ], $messages);
    }
    $url = $_SERVER['REQUEST_URI'];
    $request_uri = explode('/', $url)[1];

    if (Auth::attempt(['email' =>  $request->input('email'), 'password' => $request->input('password')], $remember)) {
      $partner = Auth::user()->partner;
      $user = Auth::user();
      if ($request_uri == "partner") {
        if (empty($partner)) {
          Auth::logout();
          return back()->withInput()->withError(trans("These credentials do not match our records"));
        }
      }
      if ($user->email_verified_at == null) {
        return Redirect::route('partner.resend_partner_otp',$user->id);
        return redirect()->intended('/partner/otp');
      }
      if (!isset($partner) || $partner->status == 0) {
        return redirect()->intended('/partner/dashboard');
      }

      if (!isset($partner) || $partner->status == 2) {
        Auth::logout();
        return back()->withInput()->withError(trans("Your account is not active."));
      }
      if (!isset($partner) || $partner->status == 3) {
        Auth::logout();
        return back()->withInput()->withError(trans("Your account is Banned."));
      }
      return redirect()->intended('/partner/dashboard');
    } else {
      return back()->withInput()->withError(trans("You have entered an invalid email or password."));
    }
  }

  public function register_form()
  {
    return view("partner.auth.register");
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

    $request->validate([
      'email' => 'bail|required|unique:users|email_address|max:255',
      'first_name' => 'required|string|max:255',
      'last_name' => 'required|string|max:255',
      'mobile_number' => 'required|min:10|unique:partners|numeric',
      'password' => 'required|min:8|confirmed|string|without_spaces',
      // 'email' => [
      //     new EmailSpam()
      // ],
    ]);

    DB::beginTransaction();
    try {
      $user = new User();
      $user->first_name = $request->first_name;
      $user->last_name = $request->last_name;
      $user->email = $request->email;
      $user->password = Hash::make($request->password);
      $user->status = 0;
      $user->save();

      $partner = new Partner();
      $partner->status = 0;
      $partner->commission = 0;
      $partner->user_id = $user->id;
      $partner->mobile_number = $request->mobile_number;
      $partner->save();



      DB::commit();
      Auth::loginUsingId($user->id);
      $dta['redirect'] = true;
      $dta['domain'] = route('partner.otp');
    } catch (\Exception $e) {
      DB::rollback();
    }

    if (!empty($request->email)) {
      $user = User::where('id', $user->id)->first();
      $userInfo['otp'] = substr(str_shuffle("0123456789"), 0, 4);

      $data = [
        'name' => $user->first_name,
        'email' => $user->email,
        'otp' => $userInfo['otp']
      ];

      DB::table('password_resets')->insert([
        'email' => $user->email,
        'token' => $userInfo['otp'],
        'created_at' => Carbon::now()
      ]);

      Mail::to($user->email)->send(new PartnerOtpMail($data));
    }

    return response()->json($dta);
  }

  public function logout(Request $request)
  {
    $this->guard()->logout();

    $request->session()->flush();

    $request->session()->regenerate();

    Auth::logout();

    return Redirect::route('/partner/login');
  }

  public function Forgot()
  {
    return view("admin.auth.forgot-password");
  }
  public function otp()
  {
    return view("partner.auth.otp");
  }
  public function resend_error()
  {
    return view("partner.auth.error");
  }
  public function otp_verify(Request $request)
  {

    $otp_verification = $request->otp_verify;
    $user = Auth::user();
    if (!empty($otp_verification)) {
      $user_email_verify = User::where('id', $user->id)->first();
      $otpdata = DB::table('password_resets')
        ->where('email', $user->email)->first();

      if ($otp_verification ==  $otpdata->token) {
        $user_email_verify->email_verified_at = Carbon::now();
        $user_email_verify->save();

        DB::table('password_resets')->where('email', $user->email)->delete();
        DB::commit();
        Auth::loginUsingId($user->id);
        $dta['redirect'] = true;
        $dta['domain'] = route('partner.dashboard');
        $data = [
          'name' => $user->first_name,
          'email' => $user->email
        ];


        Mail::to($user->email)->send(new PartnerRegisterMail($data));
      } else {
        $dta['redirect'] = false;
      }
    }

    return response()->json($dta);
  }
  public function resend_partner_otp(Request $request, $id)
  {
    DB::beginTransaction();
    try {

      $user = User::where('id', $id)->first();
      $userInfo['otp'] = substr(str_shuffle("0123456789"), 0, 4);

      $data = [
        'name' => $user->first_name,
        'email' => $user->email,
        'otp' => $userInfo['otp']
      ];

      $otp_check = DB::table('password_resets')->where('email', $user->email)->first();
      if (!empty($otp_check)) {
        DB::table('password_resets')->where('email', $user->email)->delete();
        DB::commit();
      }

      DB::table('password_resets')->insert([
        'email' => $user->email,
        'token' => $userInfo['otp'],
        'created_at' => Carbon::now()
      ]);

      Mail::to($user->email)->send(new PartnerOtpMail($data));

      DB::commit();
      //   Auth::loginUsingId($id);
      $dta['redirect'] = true;
      return redirect()->intended('/partner/otp');
    } catch (\Exception $e) {
      DB::rollback();
      // return response()->view('errors.' . '500', [], 500);
      return redirect()->intended('/partner/otp_page');
    }

    // return response()->json($data);
  }
}
