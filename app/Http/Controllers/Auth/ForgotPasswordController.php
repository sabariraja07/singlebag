<?php

namespace App\Http\Controllers\Auth;

use App\Models\User;
use App\Models\Shop;
use App\Models\Domain;
use App\Models\Partner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Password;
use Illuminate\Support\Facades\Redirect;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;

class ForgotPasswordController extends Controller
{
    /*
    |--------------------------------------------------------------------------
    | Password Reset Controller
    |--------------------------------------------------------------------------
    |
    | This controller is responsible for handling password reset emails and
    | includes a trait which assists in sending these notifications from
    | your application to your users. Feel free to explore this trait.
    |
    */

    use SendsPasswordResetEmails;

    public function sendResetLinkEmail(Request $request)
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
          
        $this->validate($request, ['email' => 'required|email_address',]);

        $url = request()->getHost();
        $url = str_replace('www.', '', $url);
        $domain = Domain::where('domain', $url)->first();

        if(isset($domain)){
            $user = User::whereHas('Shops', function($q) use($domain) {
                            $q->where('id', $domain->shop_id);
                        })
                        ->where('email', $request->email)
                        ->first();
            
            if($user != null) {
                if ($user->email_verified_at == null) {
                    return Redirect::route('email_not_verified');
                  }
            }

            if($user == null){
                return back()->withErrors(
                    ['email' => trans(Password::INVALID_USER)]
                );
            }
        }

        ##### Partner Reset Password Validation #####
        $partner_get = User::whereHas('Partner')
        ->where('email', $request->email)
        ->first();

        if(isset($partner_get)){
        $partner = Partner::where('user_id', $partner_get->id)->first();

            if ($partner_get->email_verified_at == null) {
                return Redirect::route('email_not_verified');
            }
            elseif($partner->status != 1){
                return Redirect::route('account_not_active');
            }

        }



        try {
        $response = $this->broker()->sendResetLink(
            $request->only('email')
        );
        } 
        catch (\Exception $e) {
            return back()->with('error', 'Error Occured Mail Not Sent.');
        }
        if ($response === Password::RESET_LINK_SENT) {
            return back()->with('status', trans($response));
        }

        return back()->withErrors(
            ['email' => trans($response)]
        );
    }
}
