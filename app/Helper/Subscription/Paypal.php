<?php

namespace App\Helper\Subscription;

use Omnipay\Omnipay;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Session;

class Paypal
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

    public static function fallback()
    {
        if (url('/') == env('APP_URL')) {
            return url('/merchant/paypal');
        } else {
            return route('seller.paypal.fallback');
        }
    }

    public static function make_payment($array)
    {

        $phone = $array['phone'];
        $email = $array['email'];
        $amount = round($array['amount'], 2);
        $ref_id = $array['ref_id'];
        $payment_method = $array['payment_method'];
        $name = $array['name'];
        $billName = $array['billName'];
        $currency = $array['currency'];

        $info = PaymentMethod::where('status', 1)->with('gateway')->where('slug', $payment_method)->firstorFail();
        $credentials =  $info->meta['credentials'] ?? [];

        $paypal_credentials['client_id'] = $credentials['client_id'];
        $paypal_credentials['client_secret'] = $credentials['client_secret'];
        $paypal_credentials['currency'] = $credentials['currency'];
        Session::put('paypal_credentials_for_admin', $paypal_credentials);


        $gateway = Omnipay::create('PayPal_Rest');
        $gateway->setClientId($credentials['client_id']);
        $gateway->setSecret($credentials['client_secret']);
        $gateway->setTestMode(env('APP_DEBUG'));

        if (env('APP_DEBUG') == true) {
            $total_amount = $amount * 0.01;
        } else {
            $total_amount = $amount;
        }

        $response = $gateway->purchase(array(
            'amount' => $total_amount,
            'currency' => strtoupper($paypal_credentials['currency']),
            'returnUrl' => Paypal::fallback(),
            'cancelUrl' => Paypal::redirect_if_payment_faild(),
        ))->send();
        if ($response->isRedirect()) {
            $response->redirect(); // this will automatically forward the customer
        } else {
            // not successful
            echo $response->getMessage();
        }
    }

    public function status(Request $request)
    {
        if (Session::has('paypal_credentials_for_admin')) {
            $credentials = Session::get('paypal_credentials_for_admin');


            $gateway = Omnipay::create('PayPal_Rest');
            $gateway->setClientId($credentials['client_id']);
            $gateway->setSecret($credentials['client_secret']);
            $gateway->setTestMode(env('APP_DEBUG'));

            $request = $request->all();

            $transaction = $gateway->completePurchase(array(
                'payer_id'             => $request['PayerID'],
                'transactionReference' => $request['paymentId'],
            ));
            $response = $transaction->send();
            if ($response->isSuccessful()) {
                $arr_body = $response->getData();
                $data['payment_id'] = $arr_body['id'];
                $data['payment_method'] = "paypal";
                $order_info = Session::get('order_info');
                $data['ref_id'] = $order_info['ref_id'];
                $data['payment_method'] = $order_info['payment_method'];
                $data['amount'] = $order_info['amount'];
                Session::forget('order_info');
                Session::put('payment_info', $data);
                Session::forget('paypal_credentials_for_admin');
                return redirect(Paypal::redirect_if_payment_success());
            }
        }
        Session::forget('paypal_credentials_for_admin');
        return redirect(Paypal::redirect_if_payment_faild());
    }
}
