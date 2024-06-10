<?php

namespace App\Helper\Subscription;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Session;

class Paystack
{

    protected static $payment_id;

    public static function redirect_if_payment_success()
    {
        if (url('/') == env('APP_URL')) {
            return url('/merchant/payment-success');
        } else {
            return route('seller.payment.success');
        }
    }

    public static function redirect_if_payment_faild()
    {
        if (url('/') == env('APP_URL')) {
            return url('/merchant/payment-fail');
        } else {
            return route('seller.payment.fail');
        }
    }



    public static function make_payment($array)
    {

        $phone = $array['phone'];
        $email = $array['email'];
        $amount = $array['amount'];
        $ref_id = $array['ref_id'];
        $payment_method = $array['payment_method'];
        $name = $array['name'];
        $billName = $array['billName'];
        $currency = $array['currency'];

        $info = PaymentMethod::where('status', 1)->with('gateway')->where('slug', $payment_method)->firstorFail();
        $credentials =  $info->meta['credentials'] ?? [];

        $paystack_credentials['public_key'] = $credentials['public_key'];
        $paystack_credentials['secret_key'] = $credentials['secret_key'];
        $paystack_credentials['currency'] = $credentials['currency'];
        $paystack_credentials['amount'] = $amount;
        Session::put('paystack_credentials_for_admin', $paystack_credentials);

        // Let's checkout payment page is it working
        return view('subscription.paystack', compact('paystack_credentials'));
    }


    public function status(Request $request)
    {
        if (Session::has('paystack_credentials_for_admin')) {
            $info = Session::get('paystack_credentials_for_admin');
            $curl = curl_init();
            curl_setopt_array($curl, array(
                CURLOPT_URL => "https://api.paystack.co/transaction/verify/" . $request->ref_id,
                CURLOPT_RETURNTRANSFER => true,
                CURLOPT_ENCODING => "",
                CURLOPT_MAXREDIRS => 10,
                CURLOPT_TIMEOUT => 30,
                CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
                CURLOPT_CUSTOMREQUEST => "GET",
                CURLOPT_HTTPHEADER => array(
                    "Authorization: Bearer " . $info['secret_key'] . "",
                    "Cache-Control: no-cache",
                ),
            ));

            $response = curl_exec($curl);

            $err = curl_error($curl);
            curl_close($curl);

            if ($err) {
                Session::forget('paystack_credentials');
                return redirect(Paystack::redirect_if_payment_faild());
            } else {
                $data = json_decode($response);

                if ($data->status == true && $data->data->status == 'success') {
                    $payment_id = $data->data->reference;
                    $amount = $data->data->amount * 0.01;
                    if ($amount != $info['amount']) {
                        return abort(404);
                    }




                    $pay_data['payment_id'] = $data->data->reference;
                    $pay_data['payment_method'] = "paystack";
                    $order_info = Session::get('order_info');
                    $pay_data['ref_id'] = $order_info['ref_id'];
                    $pay_data['payment_method'] = $order_info['payment_method'];
                    $pay_data['amount'] = $order_info['amount'];
                    Session::forget('order_info');
                    Session::forget('paystack_credentials_for_admin');
                    Session::put('payment_info', $pay_data);

                    return redirect(Paystack::redirect_if_payment_success());
                }
            }
        }
    }
}
