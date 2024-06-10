<?php

namespace App\Http\Controllers\Customer;

use App\Mail\Sendotp;
use App\Models\Customer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Password;
use Illuminate\Foundation\Auth\SendsPasswordResetEmails;


class ForgotPasswordController extends Controller
{
    use SendsPasswordResetEmails;

    /**
     * Only guests for "admin" guard are allowed except
     * for logout.
     * 
     * @return void
     */
    public function __construct()
    {
        $this->middleware('guest');
    }

    /**
     * Show the reset email form.
     * 
     * @return \Illuminate\Http\Response
     */
    public function showLinkRequestForm()
    {
        // return view('auth.passwords.email',[
        // 	'title' => 'Admin Password Reset',
        // 	'passwordEmailRoute' => 'admin.password.email'
        // ]);

        return view('auth.customer.passwords.email');
    }

    /**
     * password broker for admin guard.
     * 
     * @return \Illuminate\Contracts\Auth\PasswordBroker
     */
    public function broker()
    {
        return Password::broker('customers');
    }

    /**
     * Get the guard to be used during authentication
     * after password reset.
     * 
     * @return \Illuminate\Contracts\Auth\StatefulGuard
     */
    public function guard()
    {
        return Auth::guard('customers');
    }

    public function sendResetOtp(Request $request)
    {
        Session::forget('customer_info');
        $shop_id = domain_info('shop_id');
        $customer = Customer::where([['email', $request->email], ['shop_id', $shop_id]])->first();
        if (empty($customer)) {
            return redirect()->back()->with(
                'error',
                'Email ID doesn\'t belongs to this account.'
            );
        }

        $userInfo['id'] = $customer->id;
        $userInfo['otp'] = rand(2000, 1000000);

        Session::put('customer_info', $userInfo);
        $data = [
            'name' => $customer->fullname,
            'email' => $customer->email,
            'otp' => $userInfo['otp']
        ];
        

        try {
            Mail::to($customer->email)->send(new Sendotp($data));
        } catch (\Exception $e) {
            return redirect()->back()->with(
                'error',
                'Error Occured Mail Not Sent.'
            );
        }

        return redirect('/user/password/otp')->with('success', 'We sent an otp code on your mail');
    }
}
