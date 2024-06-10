<?php

namespace App\Helper\Order;

use App\Models\Order;
use App\Models\Gateway;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class Paystack
{

    public static function redirect_if_payment_success()
    {
        return url('/payment/payment-success');
    }

    public static function redirect_if_payment_faild()
    {
        return url('/payment/payment-fail');
    }

    public function view()
    {
        if (Session::has('paystack_payment') && Session::get('customer_order_info')) {
            $array = Session::get('customer_order_info');
            $amount = $array['amount'];
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

            $credentials['public_key'] = $info['public_key'];
            $credentials['secret_key'] = $info['secret_key'];
            $credentials['currency'] = $info['currency'];
            $credentials['amount'] = $amount;
            $credentials['email'] = $array['email'];

            if (Session::has('paystack_credentials')) {
                Session::forget('paystack_credentials');
            }
            Session::put('paystack_credentials', $credentials);


            return view(base_view() . '::payment.paystack', compact('credentials'));
        }
        abort(404);
    }

    public function status(Request $request)
    {
        Session::forget('customer_payment_info');

        if (Session::has('paystack_payment') && Session::has('customer_order_info') && Session::has('paystack_credentials')) {
            $info = Session::get('paystack_credentials');

            $order_info = Session::get('customer_order_info');
            $phone = $order_info['phone'];
            $email = $order_info['email'];
            $amount = $order_info['amount'];

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

                    $pay_data['ref_id'] = $order_info['ref_id'];
                    $pay_data['payment_method'] = $order_info['payment_method'];
                    $pay_data['amount'] = $order_info['amount'];
                    $pay_data['billName'] = $order_info['billName'];
                    Session::put('customer_payment_info', $pay_data);
                    Session::forget('customer_order_info');
                    Session::forget('order_info');
                    Session::forget('paystack_payment');
                    Session::forget('paystack_credentials');

                    return redirect(Paystack::redirect_if_payment_success());
                }
                $order_info = Session::get('customer_order_info');

                Order::destroy($order_info['ref_id']);
                Session::forget('customer_order_info');
                Session::forget('order_info');
                Session::forget('paystack_payment');
                Session::forget('paystack_credentials');
                return redirect(Paystack::redirect_if_payment_faild());
            }

            abort(404);
        }
    }
}
