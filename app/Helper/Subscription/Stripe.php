<?php

namespace App\Helper\Subscription;

use Omnipay\Omnipay;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Session;

class Stripe
{
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
        $name = $array['name'];
        $billName = $array['billName'];
        $stripeToken = $array['stripeToken'];
        $currency = $array['currency'];
        $payment_method = $array['payment_method'];

        $info = PaymentMethod::where('status', 1)->with('gateway')->where('slug', $payment_method)->firstorFail();
        $credentials =  $info->meta['credentials'] ?? [];

        if ($stripeToken) {

            $gateway = Omnipay::create('Stripe');
            $gateway->setApiKey($credentials['secret_key']);

            $token = $stripeToken;

            $response = $gateway->purchase([
                'amount' => $amount,
                'currency' => strtoupper($credentials['currency']),
                "email" => $email,
                'token' => $token,
            ])->send();

            if ($response->isSuccessful()) {
                // payment was successful: insert transaction data into the database
                $arr_payment_data = $response->getData();
                $data['payment_id'] = $arr_payment_data['id'];
                $data['payment_method'] = "stripe";
                $order_info = Session::get('order_info');
                $data['ref_id'] = $order_info['ref_id'];
                $data['payment_method'] = $order_info['payment_method'];
                $data['amount'] = $order_info['amount'];
                Session::forget('order_info');
                Session::put('payment_info', $data);
                return redirect(Stripe::redirect_if_payment_success());
            } else {
                return redirect(Stripe::redirect_if_payment_faild());
            }
        }
    }
}
