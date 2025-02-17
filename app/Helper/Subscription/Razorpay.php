<?php

namespace App\Helper\Subscription;

use Razorpay\Api\Api;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class Razorpay
{

    protected static $payment_id;

    public static function redirect_if_payment_success($url = '')
    {
        if ($url != '') {
            return url($url);
        } else if (Auth::user()->isPartner()) {
            return route('partner.payment.success');
        } else if (url('/') == env('APP_URL')) {
            return url('/merchant/payment-success');
        } else {
            return route('seller.payment.success');
        }
    }

    public static function redirect_if_payment_faild()
    {
        if (Auth::user()->isPartner()) {
            return route('partner.payment.fail');
        } else if (url('/') == env('APP_URL')) {
            return url('/merchant/payment-fail');
        } else {
            return route('seller.payment.fail');
        }
    }

    public function razorpay_view()
    {

        $data = Session::get('order_info');
        $Info = Razorpay::make_payment($data);
        return view('subscription.razorpay', compact('Info'));
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

        $info = PaymentMethod::where('status', 1)->where('slug', $payment_method)->firstorFail();
        $credentials =  $info->meta['credentials'] ?? [];

        $razorpay_credentials['key_id'] = $credentials['key_id'];
        $razorpay_credentials['key_secret'] = $credentials['key_secret'];
        $razorpay_credentials['currency'] = $credentials['currency'];
        Session::put('razorpay_credentials_for_admin', $razorpay_credentials);

        $api = new Api($razorpay_credentials['key_id'], $razorpay_credentials['key_secret']);
        $referance_id = $ref_id;
        $order = $api->order->create(
            array(
                'receipt' => $referance_id,
                'amount' => $amount * 100,
                'currency' => $razorpay_credentials['currency']
            )
        );

        // Return response on payment page
        $response = [
            'orderId' => $order['id'],
            'razorpayId' =>  $razorpay_credentials['key_id'],
            'amount' => $amount * 100,
            'name' => $name,
            'currency' => $razorpay_credentials['currency'],
            'email' => $email,
            'contactNumber' => $phone,
            'address' => "",
            'description' => $billName,
        ];

        // Let's checkout payment page is it working
        return $response;
    }


    public function status(Request $request)
    {
        if (Session::has('razorpay_credentials_for_admin')) {
            // Now verify the signature is correct . We create the private function for verify the signature
            $signatureStatus = Razorpay::SignatureVerify(
                $request->all()['rzp_signature'],
                $request->all()['rzp_paymentid'],
                $request->all()['rzp_orderid']
            );

            // If Signature status is true We will save the payment response in our database
            // In this tutorial we send the response to Success page if payment successfully made
            if ($signatureStatus == true) {

                //for success
                $data['payment_id'] = Razorpay::$payment_id;
                $data['payment_method'] = "razorpay";

                $order_info = Session::get('order_info');
                $data['ref_id'] = $order_info['ref_id'];
                $data['payment_method'] = $order_info['payment_method'];
                $data['amount'] = $order_info['amount'];
                $data['discount'] = $order_info['discount'];
                $data['shop_id'] = $order_info['shop_id'] ?? null;
                $data['quantity'] = $order_info['quantity'] ?? 1;
                Session::forget('order_info');
                Session::put('payment_info', $data);
                Session::forget('razorpay_credentials_for_admin');
                return redirect(Razorpay::redirect_if_payment_success($order_info['redirect_success_url'] ?? ""));
            }
        } else {
            Session::forget('razorpay_credentials_for_admin');
            return redirect(Razorpay::redirect_if_payment_faild());
        }
    }

    // In this function we return boolean if signature is correct
    private static function SignatureVerify($_signature, $_paymentId, $_orderId)
    {
        $razorpay_credentials = Session::get('razorpay_credentials_for_admin');
        try {
            // Create an object of razorpay class
            $api = new Api($razorpay_credentials['key_id'], $razorpay_credentials['key_secret']);
            $attributes  = array('razorpay_signature'  => $_signature,  'razorpay_payment_id'  => $_paymentId,  'razorpay_order_id' => $_orderId);
            $order  = $api->utility->verifyPaymentSignature($attributes);
            Razorpay::$payment_id = $_paymentId;
            return true;
        } catch (\Exception $e) {
            // If Signature is not correct its give a excetption so we use try catch
            return false;
        }
    }
}
