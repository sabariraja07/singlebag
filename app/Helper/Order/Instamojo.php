<?php

namespace App\Helper\Order;

use App\Models\Order;
use App\Models\Gateway;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class Instamojo
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
        return url('/payment/instamojo');
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

        $data['private_api_key'] = $info['private_api_key'];
        $data['private_auth_token'] = $info['private_auth_token'];

        if ($info['env'] == 'production') {
            $data['env'] = false;
            $test_mode = false;
        } else {
            $data['env'] = true;
            $test_mode = true;
        }


        if ($test_mode == true) {
            $url = 'https://test.instamojo.com/api/1.1/payment-requests/';
        } else {
            $url = 'https://www.instamojo.com/api/1.1/payment-requests/';
        }


        $phone = $array['phone'];
        $email = $array['email'];
        $amount = round($array['amount'], 2);
        $ref_id = $array['ref_id'];
        $payment_method = $array['payment_method'];
        $name = $array['name'];
        $billName = $array['billName'];


        $params = [
            'purpose' => $billName,
            'amount' => $amount,
            'phone' => $phone,
            'buyer_name' => $name,
            'redirect_url' => Instamojo::fallback(),
            'send_email' => true,
            'send_sms' => true,
            'email' => $email,
            'allow_repeated_payments' => false
        ];
        $response = Http::asForm()->withHeaders([
            'X-Api-Key' => $info['private_api_key'],
            'X-Auth-Token' => $info['private_auth_token']
        ])->post($url, $params);

        if (isset($response['payment_request'])) {
            $url = $response['payment_request']['longurl'];
            return redirect($url);
        } else {
            $order_info = Session::get('customer_order_info');
            Order::destroy($order_info['ref_id']);
            Session::forget('customer_order_info');
            return redirect(Instamojo::redirect_if_payment_faild());
        }
    }


    public function status()
    {
        $response = Request()->all();
        $payment_id = $response['payment_id'];

        if ($response['payment_status'] == 'Credit') {
            $data['payment_id'] = $payment_id;
            $data['payment_method'] = "instamojo";
            $order_info = Session::get('customer_order_info');
            $data['ref_id'] = $order_info['ref_id'];
            $data['payment_method'] = $order_info['payment_method'];
            $data['amount'] = $order_info['amount'];
            $data['billName'] = $order_info['billName'];
            Session::put('customer_payment_info', $data);
            Session::forget('customer_order_info');

            Session::forget('order_info');
            return redirect(Instamojo::redirect_if_payment_success());
        } else {
            $order_info = Session::get('customer_order_info');
            Order::destroy($order_info['ref_id']);
            Session::forget('customer_order_info');
            return redirect(Instamojo::redirect_if_payment_faild());
        }
    }
}
