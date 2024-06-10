<?php

namespace App\Http\Controllers\Seller;

use App\Models\Gateway;
use App\Models\ShopOption;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;

class GatewayController extends Controller
{


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $gateway = $this->install($request->payment_method);
        return redirect()->route('seller.payment.show', $gateway->slug);
    }

    public function install($payment_method)
    {
        $gateway = PaymentMethod::with('gateway')->where('slug', $payment_method)->firstOrFail();
        if (empty($gateway->gateway)) {
            if ($gateway->slug == 'cod') {
                $data['title'] = 'Cash On Delivery (COD)';
                $data['additional_details'] = '';
            } elseif ($gateway->slug == 'instamojo') {
                $data['title'] = 'instamojo';
                $data['env'] = 'production';
                $data['purpose'] = '';
                $data['private_api_key'] = '';
                $data['private_auth_token'] = '';
            } elseif ($gateway->slug == 'razorpay') {
                $data['title'] = 'razorpay';
                $data['description'] = '';
                $data['currency'] = '';
                $data['key_id'] = '';
                $data['key_secret'] = '';
            } elseif ($gateway->slug == 'paypal') {
                $data['title'] = 'paypal';
                $data['description'] = '';
                $data['currency'] = '';
                $data['ClientID'] = '';
                $data['ClientSecret'] = '';
                $data['env'] = 'production';
            } elseif ($gateway->slug == 'stripe') {
                $data['title'] = 'stripe';
                $data['description'] = '';
                $data['currency'] = '';
                $data['stripe_key'] = '';
                $data['stripe_secret'] = '';
                $data['env'] = 'production';
            } elseif ($gateway->slug == 'toyyibpay') {
                $data['title'] = 'toyyibpay';
                $data['description'] = '';
                $data['currency'] = '';
                $data['user_secretkey'] = '';
                $data['category_code'] = '';
                $data['env'] = 'production';
            } elseif ($gateway->slug == 'mollie') {
                $data['title'] = 'mollie';
                $data['description'] = '';
                $data['currency'] = '';
                $data['api_key'] = '';
            } elseif ($gateway->slug == 'paystack') {
                $data['title'] = 'paystack';
                $data['description'] = '';
                $data['currency'] = '';
                $data['public_key'] = '';
                $data['secret_key'] = '';
            } else {
                return back();
            }
            $install = new Gateway();
            $install->shop_id = current_shop_id();
            $install->payment_method = $payment_method;
            $install->status = 0;
            $install->content = $data;
            $install->save();
        }

        return $gateway;
    }



    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $info = PaymentMethod::with('gateway')->where('slug', $slug)->first();
        $data = $info->gateway->content ?? null;
        if(!isset($data)) {
            $this->install($slug);
            $info = PaymentMethod::with('gateway')->where('slug', $slug)->first();
            $data = $info->gateway->content ?? null;
        }
        $currency = ShopOption::where('key', 'currency')->where('shop_id', current_shop_id())->first();
        $currency = get_currency_info($currency->value ?? '');
        return view('seller.settings.payment.' . $slug, compact('info', 'data', 'currency'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $info = Gateway::where('shop_id', current_shop_id())->with('method')->where('payment_method', $id)->firstOrFail();
        $currency = ShopOption::where('key', 'currency')->where('shop_id', current_shop_id())->first();
        $symbol = isset($currency) ? $currency->value : "";
        if ($info->method->slug == 'cod') {
            $data['title'] = $request->name;
            $data['additional_details'] = $request->additional_details;
        }

        if ($info->method->slug == 'instamojo') {
            $data['title'] = $request->name;
            $data['env'] = $request->env ?? 'production';
            $data['private_api_key'] = $request->private_api_key;
            $data['private_auth_token'] = $request->private_auth_token;
            $data['purpose'] = $request->purpose;
        }

        if ($info->method->slug == 'razorpay') {
            $data['title'] = $request->name;
            $data['key_id'] = $request->key_id;
            $data['key_secret'] = $request->key_secret;
            $data['description'] = $request->description;
            $data['currency'] = $symbol;
        }

        if ($info->method->slug == 'paypal') {
            $data['title'] = $request->name;
            $data['currency'] = $symbol;
            $data['ClientID'] = $request->ClientID;
            $data['ClientSecret'] = $request->ClientSecret;
            $data['env'] = $request->env ?? 'production';
        }

        if ($info->method->slug == 'stripe') {
            $data['title'] = $request->name;
            $data['description'] = $request->description;
            $data['currency'] = $symbol;
            $data['stripe_key'] = $request->stripe_key;
            $data['stripe_secret'] = $request->stripe_secret;
            $data['env'] = $request->env ?? 'production';
        }
        if ($info->method->slug == 'toyyibpay') {
            $data['title'] = $request->name;
            $data['description'] = $request->description;
            $data['currency'] = $symbol;
            $data['user_secretkey'] = $request->user_secretkey;
            $data['category_code'] = $request->category_code;
            $data['env'] = $request->env ?? 'production';
        }

        if ($info->method->slug == 'mollie') {
            $data['title'] = $request->name;
            $data['description'] = $request->description;
            $data['currency'] = $symbol;
            $data['api_key'] = $request->api_key;
        } elseif ($info->method->slug == 'paystack') {
            $data['title'] = $request->name;
            $data['description'] = $request->description;
            $data['currency'] = strtoupper($symbol);
            $data['public_key'] = $request->public_key;
            $data['secret_key'] = $request->secret_key;
        }

        $info->content = $data;

        $info->status = $request->status ?? 0;
        $info->save();

        return response()->json([trans('Information Updated')]);
    }
}
