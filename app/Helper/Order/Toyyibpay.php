<?php

namespace App\Helper\Order;

use App\Models\Order;
use App\Models\Gateway;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class Toyyibpay
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
		return url('/payment/toyyibpay');
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

		$data['user_secretkey'] = $info['user_secretkey'];
		$data['category_code'] = $info['category_code'];

		if ($info['env'] == 'production') {
			$data['env'] = false;
			$test_mode = false;
		} else {
			$data['env'] = true;
			$test_mode = true;
		}

		$phone = $array['phone'];
		$email = $array['email'];
		$amount = $array['amount'];
		$ref_id = $array['ref_id'];
		$payment_method = $array['payment_method'];
		$name = $array['name'];
		$billName = $array['billName'];


		if ($test_mode == false) {
			$url = 'https://toyyibpay.com/';
		} else {
			$url = 'https://dev.toyyibpay.com/';
		}


		$data = array(
			'userSecretKey' => $info['user_secretkey'],
			'categoryCode' => $info['category_code'],
			'billName' => $billName,
			'billDescription' => "Thank you for order",
			'billPriceSetting' => 1,
			'billPayorInfo' => 1,
			'billAmount' => $amount * 100,
			'billReturnUrl' => Toyyibpay::fallback(),
			'billCallbackUrl' => Toyyibpay::fallback(),
			'billExternalReferenceNo' => $ref_id,
			'billTo' => $name,
			'billEmail' => $email,
			'billPhone' => $phone,
			'billSplitPayment' => 0,
			'billSplitPaymentArgs' => '',
			'billPaymentChannel' => '2',
			'billDisplayMerchant' => 1,
			'billContentEmail' => "",
			'billChargeToCustomer' => 2
		);
		$f_url = $url . 'index.php/api/createBill';

		$response = Http::asForm()->post($f_url, $data);
		$billcode = $response[0]['BillCode'];
		$url = $url . $billcode;
		return redirect($url);
	}


	public function status()
	{
		$response = Request()->all();
		$status = $response['status_id'];
		$payment_id = $response['billcode'];


		if ($status == 1) {
			$data['payment_id'] = $payment_id;
			$data['payment_method'] = "toyyibpay";
			$order_info = Session::get('customer_order_info');
			$data['ref_id'] = $order_info['ref_id'];
			$data['payment_method'] = $order_info['payment_method'];
			$data['amount'] = $order_info['amount'];
			$data['billName'] = $order_info['billName'];
			Session::put('customer_payment_info', $data);
			Session::forget('customer_order_info');
			Session::forget('order_info');
			return redirect(Toyyibpay::redirect_if_payment_success());
		} else {
			$order_info = Session::get('customer_order_info');
			Order::destroy($order_info['ref_id']);
			Session::forget('customer_order_info');
			return redirect(Toyyibpay::redirect_if_payment_faild());
		}
	}

	public static  function Toyi($param)
	{
		return \Crypt::decryptString($param);
	}
}
