<?php

namespace App\Gateways;

use Omnipay\Omnipay;
use App\Models\Order;
use Mockery\Exception;
use Omnipay\Stripe\Gateway;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Modules\Booking\Models\Payment;
use PHPUnit\Framework\Error\Warning;
use Illuminate\Validation\Validator;
use Modules\Booking\Events\BookingCreatedEvent;
use Omnipay\Common\Exception\InvalidCreditCardException;

use App\Helpers\Assets;

class StripeGateway extends BaseGateway
{
    protected $id = 'stripe';

    public $name = 'Stripe Checkout';

    protected $gateway;

    public function process(Request $request, $booking, $service = null)
    {
        if (in_array($booking->status, [
            $booking::PAID,
            $booking::COMPLETED,
            $booking::CANCELLED
        ])) {

            throw new Exception(__("Booking status does need to be paid"));
        }
        if (!$booking->total) {
            throw new Exception(__("Booking total is zero. Can not process payment gateway!"));
        }
        $rules = [
            'card_name'    => ['required'],
            'token'  => ['required'],
        ];
        $messages = [
            'card_name.required'    => __('Card Name is required field'),
            'token.required'  => __('Card invalid!'),
        ];
        $validator = Validator::make($request->all(), $rules, $messages);
        if ($validator->fails()) {
            return response()->json(['errors'   => $validator->errors()], 200)->send();
        }
        $this->getGateway();
        $payment = new Payment();
        $payment->booking_id = $booking->id;
        $payment->payment_gateway = $this->id;
        $data = $this->handlePurchaseData([
            'amount'        => (float)$booking->total,
            'transactionId' => $booking->code . '.' . time()
        ], $booking, $request);
        try {
            $response = $this->gateway->purchase($data)->send();
            if ($response->isSuccessful()) {
                $payment->status = 'completed';
                $payment->logs = \GuzzleHttp\json_encode($response->getData());
                $payment->save();
                $booking->payment_id = $payment->id;
                $booking->status = $booking::PAID;
                $booking->save();
                try {
                    $booking->sendNewBookingEmails();
                    event(new BookingCreatedEvent($booking));
                } catch (\Swift_TransportException $e) {
                    Log::warning($e->getMessage());
                }
                response()->json([
                    'url' => $booking->getDetailUrl()
                ])->send();
            } else {
                $payment->status = 'fail';
                $payment->logs = \GuzzleHttp\json_encode($response->getData());
                $payment->save();
                throw new Exception($response->getMessage());
            }
        } catch (Exception | InvalidCreditCardException $e) {
            $payment->status = 'fail';
            $payment->save();
            throw new Exception('Stripe Gateway: ' . $e->getMessage());
        }
    }

    public function getGateway()
    {
        $this->gateway = Omnipay::create('Stripe');
        $this->gateway->setApiKey($this->getOption('stripe_secret_key'));
        if ($this->getOption('stripe_enable_sandbox')) {
            $this->gateway->setApiKey($this->getOption('stripe_test_secret_key'));
        }
    }

    public function handlePurchaseData($data, $booking, $request)
    {
        $data['currency'] = setting_item('currency_main');
        $data['token'] = $request->input("token");
        $data['description'] = setting_item("site_title") . " - #" . $booking->id;
        return $data;
    }

    public function getDisplayHtml()
    {

        $script_inline = "
        var bookingCore_gateways_stripe = {
                stripe_publishable_key:'{$this->getOption('stripe_publishable_key')}',
                stripe_test_publishable_key:'{$this->getOption('stripe_test_publishable_key')}',
                stripe_enable_sandbox:'{$this->getOption('stripe_enable_sandbox')}',
            };";
        Assets::registerJs("https://js.stripe.com/v3/", true);
        Assets::registerJs($script_inline, true, 10, false, true);
        Assets::registerJs(asset('module/booking/gateways/stripe.js'), true);
        $data = [
            'html' => $this->getOption('html', ''),
        ];
        return view("Booking::frontend.gateways.stripe", $data);
    }
}
