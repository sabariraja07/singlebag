<?php

namespace App\Helper\Order;

use Omnipay\Omnipay;
use App\Models\Order;
use App\Models\Gateway;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class Stripe
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
    if (Session::has('stripe_payment') && Session::get('customer_order_info')) {
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

      $credentials['stripe_key'] = $info['stripe_key'];
      $credentials['stripe_secret'] = $info['stripe_secret'];
      $credentials['currency'] = $info['currency'];

      if (Session::has('stripe_credentials')) {
        Session::forget('stripe_credentials');
      }
      Session::put('stripe_credentials', $credentials);
      $publishable_key = $info['stripe_key'];
      $description = $info['description'];


      return view(base_view() . '::payment.stripe', compact('amount', 'publishable_key', 'description'));
    }
    abort(404);
  }

  public function status(Request $request)
  {

    if (Session::has('stripe_payment') && Session::has('customer_order_info') && Session::has('stripe_credentials')) {
      $stripe_credentials = Session::get('stripe_credentials');

      $order_info = Session::get('customer_order_info');
      $phone = $order_info['phone'];
      $email = $order_info['email'];
      $amount = $order_info['amount'];

      $currency = $stripe_credentials['currency'];
      $stripe_secret = $stripe_credentials['stripe_secret'];

      if ($request->stripeToken) {

        $gateway = Omnipay::create('Stripe');
        $gateway->setApiKey($stripe_secret);

        $response = $gateway->purchase([
          'amount' => $amount,
          'currency' => strtoupper($currency),
          "email" => $email,
          'token' => $request->stripeToken,
        ])->send();

        if ($response->isSuccessful()) {
          // payment was successful: insert transaction data into the database
          $arr_payment_data = $response->getData();
          $data['payment_id'] = $arr_payment_data['id'];
          $data['payment_method'] = "stripe";

          $data['ref_id'] = $order_info['ref_id'];
          $data['payment_method'] = $order_info['payment_method'];
          $data['amount'] = $order_info['amount'];
          $data['billName'] = $order_info['billName'];
          Session::put('customer_payment_info', $data);
          Session::forget('customer_order_info');
          Session::forget('order_info');
          Session::forget('stripe_payment');
          Session::forget('stripe_credentials');
          return redirect(Stripe::redirect_if_payment_success());
        } else {

          $order_info = Session::get('customer_order_info');

          Order::destroy($order_info['ref_id']);
          Session::forget('customer_order_info');
          Session::forget('order_info');
          Session::forget('stripe_payment');
          Session::forget('stripe_credentials');
          return redirect(Stripe::redirect_if_payment_faild());
        }
      }
    }

    abort(401);
  }
}
