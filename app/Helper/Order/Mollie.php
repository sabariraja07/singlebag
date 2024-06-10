<?php

namespace App\Helper\Order;

use App\Models\Order;
use App\Models\Gateway;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class Mollie
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
        return url('/payment/mollie');
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
        $data['api_key'] = $info['api_key'];


        if (Session::has('mollie_credentials')) {
            Session::forget('mollie_credentials');
        }
        $credentials = Session::put('mollie_credentials', $data);

        $phone = $array['phone'];
        $email = $array['email'];
        $total_amount = str_replace(',', '', number_format($array['amount'], 2));
        $ref_id = $array['ref_id'];
        $payment_method = $array['payment_method'];
        $name = $array['name'];
        $billName = $array['billName'];
        $currency = $info['currency'];

        try {
            $mollie = new \Mollie\Api\MollieApiClient();
            $mollie->setApiKey($info['api_key']);

            $payment = $mollie->payments->create([

                "amount" => [
                    "currency" => $currency,
                    "value" => $total_amount
                ],
                "description" => $billName,
                "redirectUrl" => Mollie::fallback(),

            ]);
            Session::put('pay_id', $payment->id);
            return redirect($payment->getCheckoutUrl());
        } catch (\Exception $e) {
            Order::destroy($ref_id);
            Session::forget('customer_order_info');
            Session::forget('mollie_credentials');
            return redirect(Mollie::redirect_if_payment_faild());
        }
    }

    public function status(Request $request)
    {

        if (Session::has('pay_id') && Session::has('mollie_credentials')) {
            $info = Session::get('mollie_credentials');


            $mollie = new \Mollie\Api\MollieApiClient();
            $mollie->setApiKey($info['api_key']);
            $pay_id = Session::get('pay_id');
            $payment = $mollie->payments->get($pay_id);

            if ($payment->isPaid()) {

                $data['payment_id'] = $pay_id;
                $data['payment_method'] = "mollie";
                $order_info = Session::get('customer_order_info');
                $data['ref_id'] = $order_info['ref_id'];
                $data['payment_method'] = $order_info['payment_method'];
                $data['amount'] = $order_info['amount'];
                $data['billName'] = $order_info['billName'];
                Session::put('customer_payment_info', $data);
                Session::forget('customer_order_info');

                Session::forget('mollie_credentials');
                Session::forget('pay_id');
                return redirect(Mollie::redirect_if_payment_success());
            }
            $order_info = Session::get('customer_order_info');

            Order::destroy($order_info['ref_id']);
            Session::forget('customer_order_info');
            Session::forget('mollie_credentials');
            Session::forget('pay_id');
            return redirect(Mollie::redirect_if_payment_faild());
        }
        abort(401);
    }
}
