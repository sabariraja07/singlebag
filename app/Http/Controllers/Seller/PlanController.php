<?php

namespace App\Http\Controllers\Seller;

use App\Models\Plan;
use App\Models\Shop;
use App\Models\User;
use App\Models\Option;
use App\Models\Product;
use App\Mail\OrderMail;
use App\Models\Category;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use App\Helper\Subscription\Paypal;
use App\Helper\Subscription\Stripe;
use App\Helper\Subscription\Mollie;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Helper\Subscription\Toyyibpay;
use App\Helper\Subscription\Instamojo;
use App\Helper\Subscription\Paystack;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Session;

class PlanController extends Controller
{
	public function index()
	{
		if (current_shop_type() == 'seller') {
			$posts = Plan::where('status', 1)->where('is_default', 0)->where('status', 1)->where('is_trial', 0)->where('price', '>', 0)
			->where(
				function($query) {
				  return $query
						 ->where('shop_type', 'seller')
						 ->orWhere('shop_type', NULL);
				 })->latest()->get();
		} else 	if (current_shop_type() == 'supplier') {
			$posts = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('price', '>', 0)->where('shop_type', 'supplier')->latest()->get();
		} else if (current_shop_type() == 'reseller') {
			$posts = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('price', '>', 0)->where('shop_type', 'reseller')->latest()->get();
		}  else {
			$posts = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('price', '>', 0)->latest()->get();
		}      
		return view('seller.plan.index', compact('posts'));
	}
	public function make_payment($id)
	{
		$info = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('price', '>', 0)->findorFail($id);
        $product_count = json_decode($info->data);
		$current_plan_product_limit = $product_count->product_limit;
		$total_product_count = Product::where('shop_id', current_shop_id())->where('type', '!=', 'page')->count();
	
		$gateways = PaymentMethod::where('status', 1)->with('gateway')->where('slug', '!=', 'cod')->get();

		$shop_id = current_shop_id();
		$shop = Shop::findorFail($shop_id);
		$discount = $shop->plan_discount();
		$tax = Option::where('key', 'tax')->first();
		$tax = round((($info->price - $discount) * 0.01) * $tax->value, 2);

		$currency = Option::where('key', 'currency')->first();
		$price = $currency->code . ' ' . number_format($info->price + $tax - $discount, 2);
		$main_price = $info->price;
		$amount = $info->price + $tax - $discount;
		
		if($amount <= 0){
			return back()->with('fail', trans("You are not able to downgrade existing plan."));  
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
		return view('seller.plan.payment', compact('info', 'gateways', 'price', 'discount', 'tax', 'main_price'));
	}

	public function renew()
	{
		$shop = Shop::with(['subscription', 'subscription.plan'])->findOrFail(current_shop_id());

		if(isset($shop->subscription->plan)){
			if($shop->subscription->plan->is_trial == 0){
				return redirect('seller/make-payment/' . $shop->subscription->plan->id);
			}
		}


		return redirect()->route('seller.plan.index');
	}

	public function make_charge(Request $request, $id)
	{

		$info = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('price', '>', 0)->findorFail($id);

		$gateway = PaymentMethod::where('status', 1)->where('slug', '!=', 'cod')->where('slug', $request->payment_method)->first();

		$currency = Option::where('key', 'currency')->first();

		$shop_id = current_shop_id();
		$shop = Shop::findorFail($shop_id);
		$discount = $shop->plan_discount();
		$tax = Option::where('key', 'tax')->first();
		$tax = round((($info->price - $discount) * 0.01) * $tax->value, 2);

		$total = str_replace(',', '', number_format($info->price + $tax - $discount, 2));

		$data['ref_id'] = $id;
		$data['payment_method'] = $request->payment_method;
        $data['shop_id'] = $shop_id;
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
			return redirect('/seller/payment-with/razorpay');
		}
	}





	public function success()
	{
		if (Session::has('payment_info')) {
			Log::info(Session::get('payment_info'));
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

				$partner = User::where('id', $dom->created_by)->with('partner')->first();
				if(isset($partner->partner)){
					Log::alert($partner->partner->commission);
					
					if($partner->partner->commission > 0){
						$user->commission = ($data['amount'] - $tax) * ($partner->partner->commission * 0.01);
						$user->save();
					}
				}



				Session::flash('success', trans('Thank you!  You have successfully upgraded your plan.'));
				$domain = request()->getHost();
				Cache::forget($domain. 'current_shop_' . domain_info('shop_id'));

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
				Log::info($e->getMessage());
				DB::rollback();
			}
		}
		return redirect('seller/settings/plan');
	}

	public function fail()
	{
		Session::forget('payment_info');
		Session::flash('fail', trans('Transaction Failed'));
		return redirect('seller/settings/plan');
	}

	public function show($id)
	{
		$shop_id = current_shop_id();
		$info = Subscription::where('shop_id', $shop_id)->with('plan_info', 'PaymentMethod', 'user', 'shop')->findorFail($id);
		$shop = Shop::with(['domain', 'user'])->where('user_id', Auth::id())->findorFail($info->shop->id);

		return view('partner.order.show_new', compact('info', 'shop'));
	}
}
