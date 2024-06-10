<?php

namespace App\Helper\Order;

use Omnipay\Omnipay;
use App\Models\Order;
use App\Models\Gateway;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class Paypal
{

    public static function redirect_if_payment_success()
    {
        return url('/payment/payment-success');
    }

    public static function redirect_if_payment_faild()
    {
        return url('/payment/payment-fail');
    }

    public static function fallback()
    {
        return url('/payment/paypal');
    }

    public static function make_payment($array)
    {

        $shop_id = domain_info('shop_id');
        $shop_type = domain_info('shop_type');

        if ($shop_type == 'reseller') {
            $data = PaymentMethod::where('slug', 'razorpay')->first();
            $info = $data->meta['credentials'] ?? [];
        } else {
            $data = Gateway::where('shop_id', $shop_id)->where('payment_method', $array['payment_method'])->first();
            $info = $data->content;
            $currency =  Cache::get(domain_info('shop_id') . 'currency_info');
            $info['currency'] = $currency->code ?? null;
        }

        $data['currency'] = $info['currency'];
        $data['ClientID'] = $info['ClientID'];
        $data['ClientSecret'] = $info['ClientSecret'];
        if ($info['env'] == 'production') {
            $data['env'] = false;
            $test_mode = false;
        } else {
            $data['env'] = true;
            $test_mode = true;
        }

        if (Session::has('paypal_credentials')) {
            Session::forget('paypal_credentials');
        }
        $credentials = Session::put('paypal_credentials', $data);

        $phone = $array['phone'];
        $email = $array['email'];
        $amount = round($array['amount'], 2);
        $ref_id = $array['ref_id'];
        $payment_method = $array['payment_method'];
        $name = $array['name'];
        $billName = $array['billName'];
        $currency = $info['currency'];


        $gateway = Omnipay::create('PayPal_Rest');
        $gateway->setClientId($info['ClientID']);
        $gateway->setSecret($info['ClientSecret']);
        $gateway->setTestMode($test_mode);

        if ($test_mode == true) {
            $total_amount = $amount * 0.01;
        } else {
            $total_amount = $amount;
        }

        $response = $gateway->purchase(array(
            'amount' => $total_amount,
            'currency' => strtoupper($currency),
            'returnUrl' => Paypal::fallback(),
            'cancelUrl' => Paypal::redirect_if_payment_faild(),
        ))->send();
        if ($response->isRedirect()) {
            $response->redirect(); // this will automatically forward the customer
        } else {
            // not successful
            Order::destroy($ref_id);
            return redirect(Paypal::redirect_if_payment_faild());
        }
    }

    public function status(Request $request)
    {

        if (Session::has('paypal_credentials')) {
            $credentials = Session::get('paypal_credentials');
        } else {
            $shop_id = domain_info('shop_id');
            $order_info = Session::get('customer_order_info');
            $shop_type = domain_info('shop_type');
            $payment_method = $order_info['payment_method'];


            if ($shop_type == 'reseller') {
                $data = PaymentMethod::where('slug', 'razorpay')->first();
                $info = $data->meta['credentials'] ?? [];
            } else {
                $data = Gateway::where('shop_id', $shop_id)->where('payment_method', $payment_method)->first();
                $info = $data->content;
                $currency =  Cache::get(domain_info('shop_id') . 'currency_info');
                $info['currency'] = $currency->code ?? null;
            }

            $credentials['currency'] = $info['currency'];
            $credentials['ClientID'] = $info['ClientID'];
            $credentials['ClientSecret'] = $info['ClientSecret'];
            if ($info['env'] == 'production') {
                $credentials['env'] = false;
                $test_mode = false;
            } else {
                $credentials['env'] = true;
                $test_mode = true;
            }
        }
        $gateway = Omnipay::create('PayPal_Rest');
        $gateway->setClientId($credentials['ClientID']);
        $gateway->setSecret($credentials['ClientSecret']);
        $gateway->setTestMode($credentials['env']);

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
            $order_info = Session::get('customer_order_info');
            $data['ref_id'] = $order_info['ref_id'];
            $data['payment_method'] = $order_info['payment_method'];
            $data['amount'] = $order_info['amount'];
            $data['billName'] = $order_info['billName'];
            Session::put('customer_payment_info', $data);
            Session::forget('customer_order_info');
            Session::forget('paypal_credentials');
            return redirect(Paypal::redirect_if_payment_success());
        } else {
            $order_info = Session::get('customer_order_info');

            Order::destroy($order_info['ref_id']);
            Session::forget('paypal_credentials');
            Session::forget('customer_order_info');
            return redirect(Paypal::redirect_if_payment_faild());
        }
    }
}
