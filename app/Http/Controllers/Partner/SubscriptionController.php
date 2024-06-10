<?php

namespace App\Http\Controllers\Partner;

use App\Models\User;
use App\Models\Plan;
use App\Models\Shop;
use App\Models\Domain;
use App\Models\Option;
use App\Models\ShopUser;
use App\Models\ShopOption;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Rules\IsValidDomain;
use App\Models\Subscription;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use App\Helper\Subscription\Stripe;
use App\Helper\Subscription\Mollie;
use App\Helper\Subscription\Paypal;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use App\Helper\Subscription\Paystack;
use App\Helper\Subscription\Toyyibpay;
use App\Helper\Subscription\Instamojo;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Redirect;

class SubscriptionController extends Controller
{

    public function plans(Request $request)
    {
        if (!Auth()->user()->isPartner()) {
            return abort(401);
        }
        $posts = Plan::whereHas('subscriptions', function ($q) {
            return $q->where('user_id', Auth::id())
                ->whereNull('shop_id');
        })->withCount(['subscriptions' => function ($q) {
            return $q->where('user_id', Auth::id())
                ->whereNull('shop_id');
        }])->latest()->paginate(40);

        return view('partner.subscription.licences', compact('posts', 'request'));
    }

    public function index(Request $request)
    {
        if (!Auth()->user()->isPartner()) {
            return abort(401);
        }

        $type = $request->status ?? 'all';
        if ($request->status == 'cancelled') {
            $type = 0;
        }

        if (!empty($request->src) && $request->term == 'email') {
            $this->user_email = $request->src;
            if ($type === 'all') {
                $posts = Subscription::whereHas('user', function ($q) {
                    return $q->where('email', $this->user_email);
                });
            } else {
                $posts = Subscription::whereHas('user', function ($q) {
                    return $q->where('email', $this->user_email);
                })->where('status', $type);
            }
        } elseif (!empty($request->src)) {
            if ($type === 'all') {
                $posts = Subscription::where($request->term, $request->src);
            } else {
                $posts = Subscription::where($request->term, $request->src)->where('status', $type);
            }
        } else {
            if ($type === 'all') {
                $posts = new Subscription();
            } else {
                $posts = Subscription::where('status', $type);
            }
        }

        $posts = $posts->with('user', 'plan_info', 'PaymentMethod', 'shop')->whereHas('shop', function ($q) {
            $q->where('created_by', Auth::id());
        })->latest()->paginate(40);

        return view('partner.order.index', compact('type', 'posts', 'request'));
    }

    public function create($id)
    {
        $plan = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('price', '>', 0)->findorFail($id);
        $gateways = PaymentMethod::where('status', 1)->with('gateway')->where('slug', '!=', 'cod')->get();

        return view('partner.subscription.create', compact('plan', 'gateways'));
    }

    public function plan_discount_calculation(Request $request)
    {
        $plan = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('price', '>', 0)->find($request->plan_id);
    }

    public function getCommission($amount)
    {
        $partner_commissions = Option::where('key', 'partner_commissions')->first();
        $commission_amount = 0;
        $commissions = json_decode($partner_commissions->value ?? null, true);

        if (isset($commissions)) {
            foreach ($commissions as $commission) {
                if (($commission['from'] <= $amount) && ($commission['to'] >= $amount)) {
                    $commission_amount = $commission['commission'];
                    break;
                }
            }
        }

        $default_commission = Option::where('key', 'default_commission')->first();

        if ($commission_amount == 0) {
            $commission_amount = $default_commission->value ?? 0;
        }

        return (int) $commission_amount;
    }

    public function create_license()
    {
        $plans = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('price', '>', 0)->get();
        $gateways = PaymentMethod::where('status', 1)->with('gateway')->where('slug', '!=', 'cod')->get();
        $currency = Option::where('key', 'currency')->first();

        return view('partner.subscription.licence', compact('plans', 'gateways', 'currency'));
    }

    public function get_license_price(Request $request)
    {
        $plan = Plan::find($request->plan_id);
        $amount = $plan->price * $request->quantity ?? 0;
        $commission = $this->getCommission($amount);
        $currency = Option::where('key', 'currency')->first();
        $currency = get_currency_info($currency->value ?? "");

        $discount = round($amount * ($commission * 0.01), 2);

        $tax = Option::where('key', 'tax')->first();
        $tax = round((($amount - $discount) * 0.01) * $tax->value, 2);

        $total = str_replace(',', '', number_format($amount + $tax - $discount, 2));

        return response(['amount' => $amount, 'commission' => $commission, 'discount' => $discount, 'tax' => $tax, 'total' => $total, 'currency' => $currency]);
    }

    public function make_charge(Request $request)
    {
        $info = Plan::find($request->plan_id);
        $amount = $info->price * $request->quantity ?? 0;
        $commission = $this->getCommission($amount);

        $gateway = PaymentMethod::where('status', 1)->where('slug', '!=', 'cod')->where('slug', $request->payment_method)->first();

        $currency = Option::where('key', 'currency')->first();

        $discount = round($amount * ($commission * 0.01), 2);


        $tax = Option::where('key', 'tax')->first();
        $tax = round((($amount - $discount) * 0.01) * $tax->value, 2);

        $total = str_replace(',', '', number_format($amount + $tax - $discount, 2));

        if ($total >= 500000.00) {
            return Redirect::back()->withErrors(['The Recommended  License payable amount should be less than Rs.5 laks']);
        }
        $data['ref_id'] = $info->id;
        $data['quantity'] = $request->quantity;
        $data['payment_method'] = $request->payment_method;
        $data['amount'] = $total;
        $data['discount'] = $discount;
        $data['email'] = Auth::user()->email;
        $data['name'] = Auth::user()->fullname;
        $data['phone'] = $request->phone;
        $data['billName'] = $info->name;
        $data['currency'] = strtoupper($currency->code);
        $data['redirect_success_url'] = '/partner/licence/payment-success';

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

    public function payment_success()
    {
        if (Session::has('payment_info')) {
            $data = Session::get('payment_info');
            $plan = Plan::findorFail($data['ref_id']);
            DB::beginTransaction();
            try {
                $quantity = $data['quantity'];
                $amount = $plan->price;
                $commission = $this->getCommission($amount);


                $exp_days =  $plan->days;
                $expiry_date = \Carbon\Carbon::now()->addDays(($exp_days - 1))->format('Y-m-d');

                $partner = User::where('id', Auth::id())->with('partner')->first();

                for ($i = 0; $i < $quantity; $i++) {

                    $discount = round($amount * ($commission * 0.01), 2);

                    $max_order = Subscription::max('id');
                    $order_prefix = Option::where('key', 'order_prefix')->first();
                    $tax = Option::where('key', 'tax')->first();
                    $tax = round((($plan->price - $discount ?? 0) * 0.01) * $tax->value, 2);

                    $order_no = $order_prefix->value . $max_order;

                    $user = new Subscription;
                    $user->order_no = $order_no;
                    $user->amount = $amount + $tax - $discount;
                    $user->tax = $tax;
                    $user->trx = $data['payment_id'];
                    $user->will_expire = $expiry_date;
                    $user->user_id = Auth::id();
                    $user->plan_id = $plan->id;
                    $user->shop_id = null;
                    $user->payment_method = $data['payment_method'];;
                    $user->payment_status = 1;
                    $user->discount = $discount ?? 0;
                    $user->commission = 0;
                    $user->status = 1;
                    $user->save();
                }

                Session::flash('success', 'Thank you!  You have successfully upgraded your plan.');

                DB::commit();
            } catch (\Exception $e) {
                DB::rollback();
            }
        }
        return redirect()->route('partner.license.plans');
    }

    public function create_shop($id)
    {
        $plan = Plan::findOrFail($id);
        return view('partner.subscription.create_store', compact('plan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store_shop(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users|email_address|max:255',
            'password' => 'required|without_spaces',
            'plan' => 'required',
            'shop_name' => 'required',
            'shop_name' => [new IsValidDomain()],
            // 'domain_name' => 'required|max:100|unique:domains,domain',
            // 'full_domain' => 'required|max:100|unique:domains,full_domain',
        ]);


        $info = Plan::find($request->plan);

        $domain = preg_replace('/[^a-zA-Z0-9\']/', '', $request->shop_name);
        $domain = str_replace("'", '', $domain);

        $full_domain = env('APP_PROTOCOL') . Str::lower($domain) . '.' . env('APP_PROTOCOLESS_URL');



        DB::beginTransaction();
        try {

            $subscription = Subscription::where('user_id', Auth::id())->where('plan_id', $info->id)->whereNull('shop_id')->first();

            if (!$subscription) {
                return response()->json(['message' => 'Licence not found.'], 400);
            }

            $user = new User();
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name;
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->status = 1;
            $user->save();

            $shop = new Shop();
            $shop->name = $request->shop_name;
            $shop->sub_domain = Str::lower($domain);
            $shop->email = $request->email;
            $shop->shop_type = 'seller';
            $shop->status = 'active';
            $shop->created_by = Auth::id();
            $shop->user_id = $user->id;
            $shop->data = $info->data;
            $shop->subscription_id = $subscription->id;
            $shop->will_expire = $subscription->will_expire;
            $shop->is_trial = 0;
            $shop->save();

            $shop_id = $shop->id;

            $shop_name = new ShopOption();
            $shop_name->key = 'shop_name';
            $shop_name->value = Str::lower($request->shop_name);
            $shop_name->shop_id = $shop->id;
            $shop_name->save();

            $currency = new ShopOption();
            $currency->key = 'currency';
            $currency->value = env('DEFAULT_STORE_CURRENCY', 'INR');
            $currency->shop_id = $shop->id;
            $currency->save();

            $shop_mode = new ShopOption();
            $shop_mode->key = 'shop_mode';
            $shop_mode->value = 'offline';
            $shop_mode->shop_id = $shop->id;
            $shop_mode->save();

            $shop_mobile_number = new ShopOption();
            $shop_mobile_number->key = 'store_mobile_number';
            $shop_mobile_number->value = $request->mobile_number;
            $shop_mobile_number->shop_id = $shop->id;
            $shop_mobile_number->save();


            $subscription->shop_id = $shop->id;
            $subscription->save();


            $dom = new Domain();
            $dom->domain = $domain . '.' . env('APP_PROTOCOLESS_URL');
            $dom->full_domain = $full_domain;
            $dom->status = 1;
            $dom->type = 1;
            $dom->shop_id = $shop->id;
            $dom->save();



            $role = Role::where('name', 'admin')->where('guard_name', 'web')->first();

            $shop_user = new ShopUser();
            $shop_user->shop_id = $shop->id;
            $shop_user->role_id = $role->id;
            $shop_user->user_id = $user->id;
            $shop_user->status = 1;
            $shop_user->save();


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return response()->json(['redirect' => route('partner.shop.show', $shop->id), 'message' => 'Store Created Successfully']);
    }
}
