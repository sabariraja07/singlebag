<?php

namespace App\Helper\Subscription;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Session;

class Mollie
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
      return url('/merchant/payment/mollie');
    } else {
      return url('seller/payment_with_mollie');
    }
  }

  public static function make_payment($array)
  {




    $phone = $array['phone'];
    $email = $array['email'];
    $total_amount = str_replace(',', '', number_format($array['amount'], 2));
    $ref_id = $array['ref_id'];
    $payment_method = $array['payment_method'];
    $name = $array['name'];
    $billName = $array['billName'];


    $info = PaymentMethod::where('status', 1)->with('gateway')->where('slug', $payment_method)->firstorFail();
    $credentials =  $info->meta['credentials'] ?? [];
    $data['api_key'] = $credentials['api_key'];
    $data['currency'] = $credentials['currency'];


    if (Session::has('seller_mollie_credentials')) {
      Session::forget('seller_mollie_credentials');
    }
    Session::put('seller_mollie_credentials', $data);

    try {
      $mollie = new \Mollie\Api\MollieApiClient();
      $mollie->setApiKey($credentials['api_key']);

      $payment = $mollie->payments->create([

        "amount" => [
          "currency" => $credentials['currency'],
          "value" => $total_amount
        ],
        "description" => $billName,
        "redirectUrl" => Mollie::fallback(),

      ]);
      Session::put('seller_pay_id', $payment->id);
      return redirect($payment->getCheckoutUrl());
    } catch (\Exception $e) {

      Session::forget('order_info');
      Session::forget('seller_mollie_credentials');
      return redirect(Mollie::redirect_if_payment_faild());
    }
  }

  public function status(Request $request)
  {
    if (Session::has('seller_pay_id') && Session::has('seller_mollie_credentials')) {
      $info = Session::get('seller_mollie_credentials');


      $mollie = new \Mollie\Api\MollieApiClient();
      $mollie->setApiKey($info['api_key']);
      $pay_id = Session::get('seller_pay_id');
      $payment = $mollie->payments->get($pay_id);

      if ($payment->isPaid()) {

        $data['payment_id'] = $pay_id;
        $data['payment_method'] = "mollie";
        $order_info = Session::get('order_info');
        $data['ref_id'] = $order_info['ref_id'];
        $data['payment_method'] = $order_info['payment_method'];
        $data['amount'] = $order_info['amount'];
        Session::forget('order_info');
        Session::put('payment_info', $data);
        Session::forget('seller_mollie_credentials');
        Session::forget('seller_pay_id');
        return redirect(Mollie::redirect_if_payment_success());
      }

      Session::forget('order_info');
      Session::forget('seller_mollie_credentials');
      Session::forget('seller_pay_id');
      return redirect(Mollie::redirect_if_payment_faild());
    }
    abort(404);
  }
}
