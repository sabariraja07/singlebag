<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Foundation\Auth\ThrottlesLogins;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;



class LoginController extends Controller
{
  public function __construct()
  {
    if (env('MULTILEVEL_CUSTOMER_REGISTER') != true || url('/') == env('APP_URL')) {
      abort(404);
    }
  }
  use ThrottlesLogins;

  /**
   * Max login attempts allowed.
   */
  public $maxAttempts = 5;

  /**
   * Number of minutes to lock the login.
   */
  public $decayMinutes = 3;

  /**
   * Login the admin.
   * 
   * @param \Illuminate\Http\Request $request
   * @return \Illuminate\Http\RedirectResponse
   */
  public function login(Request $request)
  {
    $validated = $request->validate([
      'email' => 'required|max:100|email_address',
      'password' => 'required|without_spaces',
    ]);
    //check if the user has too many login attempts.
    if ($this->hasTooManyLoginAttempts($request)) {
      //Fire the lockout event.
      $this->fireLockoutEvent($request);

      //redirect the user back after lockout.
      return $this->sendLockoutResponse($request);
    }

    if (Auth::check()) {
      Auth::logout();
    }
    $find_customer = Customer::where('email', $request->email)->where('shop_id', domain_info('shop_id'))->first();
    // $customer = Customer::where('email', $request->email)->where('status', 1)->where('shop_id', domain_info('shop_id'))->first();

    if (!empty($find_customer)) {
      if (Auth::guard('customer')->attempt([
        'email' => $request->email, 'password' => $request->password,
        'shop_id' => domain_info('shop_id'), 'status' => 1
      ], $request->filled('remember'))) {
        //Authentication passed...
        return redirect()
          ->intended(url('/user/dashboard'))
          ->with('status', 'You are Logged in as Admin!');
      } else {
        // Session::flash('message', 'Your account is inactive. Please contact store admin.');
        Session::flash('message', 'Invalid email id or password.');
        $this->incrementLoginAttempts($request);
        return $this->loginFailed();
      }
    } else {
      Session::flash('message', 'Invalid email id or password.');
      //keep track of login attempts from the user.
      $this->incrementLoginAttempts($request);

      //Authentication failed...
      return $this->loginFailed();
    }
  }

  /**
   * Username used in ThrottlesLogins trait
   * 
   * @return string
   */
  public function username()
  {
    return 'email';
  }

  /**
   * Logout the admin.
   * 
   * @return \Illuminate\Http\RedirectResponse
   */
  public function logout()
  {
    Auth::guard('customer')->logout();
    return redirect('/');
  }

  /**
   * Validate the form data.
   * 
   * @param \Illuminate\Http\Request $request
   * @return 
   */
  private function validator(Request $request)
  {
    //validate the form...
  }

  /**
   * Redirect back after a failed login.
   * 
   * @return \Illuminate\Http\RedirectResponse
   */
  private function loginFailed()
  {

    return redirect()
      ->back()
      ->withInput()
      ->with('error', 'Login failed, please try again!');
  }
}
