<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use App\Http\Controllers\Controller;

class PaymentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        if (!Auth()->user()->can('payment_gateway.config')) {
            abort(401);
        }
        $posts = PaymentMethod::withCount('gateways')->get();
        return view('admin.payment_gateway.index', compact('posts'));
    }



    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if (!Auth()->user()->can('payment_gateway.config')) {
            abort(401);
        }
        $info = PaymentMethod::where('slug', $slug)->first();
        $credentials = $info->meta['credentials'];

        return view('admin.payment_gateway.edit', compact('info', 'credentials'));
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
        $request->validate([
            'title' => 'required|max:20',
            'description' => 'required|max:200',
            'file' => 'image',
            'gateway' => 'required|unique:payment_methods,slug,' . $id,
        ]);

        $method = PaymentMethod::find($id);
        $method->name = $request->title;
        $method->slug = $request->gateway;
        $method->description = $request->description ?? 0;
        $method->status = $request->status ?? 0;
        $method->user_id = auth()->id();

        if ($request->gateway != 'cod') {

            if ($request->gateway == 'instamojo') {
                $data['x_api_Key'] = $request->x_api_Key;
                $data['x_api_token'] = $request->x_api_token;
            }
            if ($request->gateway == 'razorpay') {
                $data['key_id'] = $request->key_id;
                $data['key_secret'] = $request->key_secret;
                $data['currency'] = $request->currency;
            }
            if ($request->gateway == 'paypal') {
                $data['client_id'] = $request->client_id;
                $data['client_secret'] = $request->client_secret;
                $data['currency'] = $request->currency;
            }

            if ($request->gateway == 'stripe') {
                $data['publishable_key'] = $request->publishable_key;
                $data['secret_key'] = $request->secret_key;
                $data['currency'] = $request->currency;
            }

            if ($request->gateway == 'toyyibpay') {
                $data['userSecretKey'] = $request->userSecretKey;
                $data['categoryCode'] = $request->categoryCode;
            }

            if ($request->gateway == 'mollie') {
                $data['api_key'] = $request->api_key;
                $data['currency'] = $request->currency;
            }
            if ($request->gateway == 'paystack') {
                $data['public_key'] = $request->public_key;
                $data['secret_key'] = $request->secret_key;
                $data['currency'] = $request->currency;
            }
            $meta = $method->meta;
            $meta['credentials'] = $data;
            $method->meta = $meta;
        }
        $method->save();

        if ($request->file) {
            $method->deleteFile('image');
            $method->uploadFile($request->file, 'admin/payment_method/' . $method->id . '/image', 'image');
        }

        return response()->json(['Payment Gateway Info Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
