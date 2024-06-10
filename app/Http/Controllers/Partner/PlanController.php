<?php

namespace App\Http\Controllers\Partner;

use App\Models\Plan;
use App\Models\Shop;
use App\Models\User;
use App\Models\Option;
use App\Mail\OrderMail;
use App\Models\Product;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use App\Helper\Subscription\Paypal;
use App\Helper\Subscription\Stripe;
use Illuminate\Support\Facades\Log;
use App\Helper\Subscription\Mollie;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Helper\Subscription\Paystack;
use App\Helper\Subscription\Toyyibpay;
use App\Helper\Subscription\Instamojo;
use Illuminate\Support\Facades\Session;

class PlanController extends Controller
{

	public function make_payment(Request $request, $id)
	{
		$info = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('price', '>', 0)->findorFail($id);

		$gateways = PaymentMethod::where('status', 1)->with('gateway')->where('slug', '!=', 'cod')->get();

        $shop = Shop::findorFail($request->shop_id);
		$discount = $shop->plan_discount();
		$tax = Option::where('key', 'tax')->first();
		$tax = round((($info->price - $discount) * 0.01) * $tax->value, 2);

		$currency = Option::where('key', 'currency')->first();
		$price = $currency->code . ' ' . number_format($info->price + $tax - $discount, 2);
		$main_price = $info->price;
		$amount = $info->price + $tax - $discount;

		$product_count = json_decode($info->data);
		$current_plan_product_limit = $product_count->product_limit;
		$total_product_count = Product::where('shop_id', $shop->id)->where('type', '!=', 'page')->count();
		
		if($amount <= 0){
			return back()->with('fail', trans('You are not able to downgrade existing plan.'));  
		}
		if(isset($current_plan_product_limit) && !empty($current_plan_product_limit)){
			if($current_plan_product_limit == -1){
			  }
			elseif($current_plan_product_limit >= $total_product_count){
			}
			else{
			  return back()->with('fail', trans("Your product limit is exceeding the selected plan. Check with other plans"));
			}
		  }

		return view('partner.plan.payment', compact('info', 'gateways', 'price', 'shop', 'discount', 'tax', 'main_price'));
	}

	public function renew()
	{
		return redirect('seller/make-payment/' . Auth::user()->subscription->plan_id);
	}

	public function make_charge(Request $request, $id)
	{
		$info = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('price', '>', 0)->findorFail($id);

		$gateway = PaymentMethod::where('status', 1)->where('slug', '!=', 'cod')->where('slug', $request->payment_method)->first();

		$currency = Option::where('key', 'currency')->first();

		$shop = Shop::findorFail($request->shop_id);
		$discount = $shop->plan_discount();


		$tax = Option::where('key', 'tax')->first();
		$tax = round((($info->price - $discount) * 0.01) * $tax->value, 2);

		$total = str_replace(',', '', number_format($info->price + $tax - $discount, 2));

		$data['ref_id'] = $id;
        $data['shop_id'] = $request->shop_id;
		$data['payment_method'] = $request->payment_method;
		$data['amount'] = $total;
		$data['discount'] = $discount;
		$data['email'] = Auth::user()->email;
		$data['name'] = Auth::user()->fullname;
		$data['phone'] = $request->phone;
		$data['billName'] = $info->name;
		$data['currency'] = strtoupper($currency->code);
		Session::put('order_info', $data);
		if ($gateway->slug == 'paypal') {
			return Paypal::make_payment($data);
		}
		if ($gateway->slug == 'instamojo') {
			return Instamojo::make_payment($data);
		}
		if ($gateway->slug == 'toyyibpay') {
			return Toyyibpay::make_payment($data);
		}
		if ($gateway->slug == 'stripe') {
			$data['stripeToken'] = $request->stripeToken;
			return Stripe::make_payment($data);
		}
		if ($gateway->slug == 'mollie') {

			return Mollie::make_payment($data);
		}
		if ($gateway->slug == 'paystack') {

			return Paystack::make_payment($data);
		}
		if ($gateway->slug == 'razorpay') {
			return redirect('/partner/payment-with/razorpay');
		}
	}
	
	public function success()
	{
		if (Session::has('payment_info')) {
			$data = Session::get('payment_info');
			$plan = Plan::findorFail($data['ref_id']);
			DB::beginTransaction();
			try {


				$exp_days =  $plan->days;
				$expiry_date = \Carbon\Carbon::now()->addDays(($exp_days-1))->format('Y-m-d');

				$max_order = Subscription::max('id');
				$order_prefix = Option::where('key', 'order_prefix')->first();
				$tax = Option::where('key', 'tax')->first();
				$tax = round((($plan->price - $data['discount'] ?? 0) * 0.01) * $tax->value, 2);


				$order_no = $order_prefix->value . $max_order;

                $partner = User::where('id', Auth::id())->with('partner')->first();

				$user = new Subscription;
				$user->order_no = $order_no;
				$user->amount = $data['amount'];
				$user->tax = $tax;
				$user->trx = $data['payment_id'];
				$user->will_expire = $expiry_date;
				$user->user_id = Auth::id();
				$user->plan_id = $plan->id;
				$user->shop_id = $data['shop_id'];
				$user->payment_method = $data['payment_method'];;
                $user->payment_status = 1;
				$user->discount = $data['discount'] ?? 0;

                if($partner->partner->commission > 0){
                    $user->commission = ($data['amount'] - $tax) * ($partner->partner->commission * 0.01);
                }

				// $auto_order = Option::where('key', 'auto_order')->first();
				// if ($auto_order->value == 'yes'  && $user->payment_status == 1) {
				// 	$user->status = 1;
				// }
				$user->status = 1;
				$user->save();

				$dom = Shop::where('id', $user->shop_id)->first();
				$dom->data = $plan->data;
				$dom->subscription_id = $user->id;
				$dom->will_expire = $expiry_date;
				$dom->is_trial = 0;
				$dom->save();



				Session::flash('success', 'Thank you!  You have successfully upgraded your plan.');


				$data['info'] = $user;
				$data['to_admin'] = env('MAIL_TO');
				$data['from_email'] = Auth::user()->email;

				try {
					if (env('QUEUE_MAIL') == 'on') {
						dispatch(new \App\Jobs\SendInvoiceEmail($data));
					} else {
						\Mail::to(env('MAIL_TO'))->send(new OrderMail($data));
					}
				} catch (\Exception $e) {
				}



				DB::commit();
			} catch (\Exception $e) {
                Log::critical($e);
				DB::rollback();
			}
		}
		return redirect('partner/order');
	}

	public function fail()
	{
		Session::forget('payment_info');
		Session::flash('fail', 'Transaction Failed');
		return redirect('partner/order');
	}
}
