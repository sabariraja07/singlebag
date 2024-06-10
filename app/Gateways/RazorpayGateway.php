<?php

namespace App\Gateways;

use Razorpay\Api\Api;
use App\Models\Order;
use App\Models\Gateway;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;

class RazorpayGateway extends BaseGateway
{
    protected $gateway = 'gazorpay';
    protected $payment_id;

    public function process(Request $request, $order)
    {
    }

    public function view()
    {
        if (Session::has('razorpay_payment') && Session::get('customer_order_info')) {
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

            if (Session::has('razorpay_credentials')) {
                Session::forget('razorpay_credentials');
            }
            Session::put('razorpay_credentials', $info);

            $Info = self::make_payment();

            return view(base_view() . '::payment.razorpay', compact('amount', 'Info'));
        }
        abort(404);
    }

    public static function make_payment()
    {
        $array = Session::get('customer_order_info');
        $amount = $array['amount'];

        $phone = $array['phone'];
        $email = $array['email'];
        $amount = $array['amount'];
        $ref_id = $array['ref_id'];
        $payment_method = $array['payment_method'];
        $name = $array['name'];
        $billName = $array['billName'];

        $razorpay_credentials = Session::get('razorpay_credentials');

        $api = new Api($razorpay_credentials['key_id'], $razorpay_credentials['key_secret']);
        $referance_id = $ref_id;

        $order = $api->order->create(
            array(
                'receipt' => $referance_id,
                'amount' => $amount * 100,
                'currency' => $razorpay_credentials['currency'],
            )
        );

        // Return response on payment page
        $response = [
            'orderId' => $order['id'],
            'razorpayId' => $razorpay_credentials['key_id'],
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


        if (Session::has('razorpay_payment') && Session::has('customer_order_info') && Session::has('razorpay_credentials')) {


            // Now verify the signature is correct . We create the private function for verify the signature
            $signatureStatus = self::SignatureVerify(
                $request->all()['rzp_signature'],
                $request->all()['rzp_paymentid'],
                $request->all()['rzp_orderid']
            );

            // If Signature status is true We will save the payment response in our database
            // In this tutorial we send the response to Success page if payment successfully made
            if ($signatureStatus == true) {
                $order_info = Session::get('customer_order_info');

                //for success
                $data['payment_id'] = self::$payment_id;
                $data['payment_method'] = "razorpay";

                $data['ref_id'] = $order_info['ref_id'];
                $data['payment_method'] = $order_info['payment_method'];
                $data['amount'] = $order_info['amount'];
                $data['billName'] = $order_info['billName'];
                Session::put('customer_payment_info', $data);
                Session::forget('customer_order_info');
                Session::forget('order_info');
                Session::forget('razorpay_payment');
                Session::forget('razorpay_credentials');
                return redirect(self::redirect_if_payment_success());
            } else {
                $order_info = Session::get('customer_order_info');

                Order::destroy($order_info['ref_id']);
                Session::forget('customer_order_info');
                Session::forget('order_info');
                Session::forget('razorpay_payment');
                Session::forget('razorpay_credentials');
                return redirect(self::redirect_if_payment_faild());
            }
        }
    }

    // In this function we return boolean if signature is correct
    private static function SignatureVerify($_signature, $_paymentId, $_orderId)
    {
        try {
            $razorpay_credentials = Session::get('razorpay_credentials');
            // Create an object of razorpay class
            $api = new Api($razorpay_credentials['key_id'], $razorpay_credentials['key_secret']);
            $attributes  = array('razorpay_signature'  => $_signature,  'razorpay_payment_id'  => $_paymentId,  'razorpay_order_id' => $_orderId);
            $order  = $api->utility->verifyPaymentSignature($attributes);
            self::$payment_id = $_paymentId;
            return true;
        } catch (\Exception $e) {
            // If Signature is not correct its give a excetption so we use try catch
            return false;
        }
    }
}
