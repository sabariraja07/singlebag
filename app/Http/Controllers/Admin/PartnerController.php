<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\User;
use App\Models\Subscription;
use App\Models\Domain;
use App\Models\Plan;
use App\Models\Option;
use Hash;
use App\Models\Partner;
use App\Models\Settlement;
use App\Models\Shop;
use Illuminate\Support\Facades\Cache;
use App\Mail\PartnerConfirmationMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;
use App\Mail\PartnerOtpMail;
use App\Models\Currency;

class PartnerController extends Controller
{
    protected $request;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth()->user()->can('partner.list')) {
            return abort(401);
        }
        $type = $request->type ?? 'all';
        if ($type == "trash") {
            $type = 0;
        }

        $posts = User::query();

        if ($type != 'all') {
            $posts = $posts->where('status', $type);
        }

        if ($request->has('src') && $request->has('term')) {
            $this->request = $request->src;
            if ($request->term == 'first_name') {
                $posts = $posts->where('first_name', 'like', '%' . $request->src . '%');
            } else if ($request->term == 'last_name') {
                $posts = $posts->where('last_name', 'like', '%' . $request->src . '%');
            } else {
                $posts = $posts->where($request->term, $request->src);
            }
        }

        $posts = $posts->whereHas('Partner')->with(['partner', 'shop', 'shop.domain'])->latest()->paginate(40);


        $all = User::whereHas('Partner')->count();
        $actives = User::whereHas('Partner')->where('status', 1)->count();
        $inactives = User::whereHas('Partner')->where('status', 2)->count();
        $pendings = User::whereHas('Partner')->where('status', 0)->count();
        $banneds = User::whereHas('Partner')->where('status', 3)->count();
        // $suspened = User::whereHas('Partner')->where('status', 2)->count();
        // $trash = User::whereHas('Partner')->where('status', 0)->count();
        // $requested = User::whereHas('Partner')->where('status', 4)->count();
        // $pendings = User::whereHas('Partner')->where('status', 3)->count();
        return view('admin.partner.index', compact('posts', 'request', 'type', 'all', 'actives', 'inactives', 'pendings', 'banneds'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth()->user()->can('customer.create')) {
            return abort(401);
        }

        return view('admin.customer.create');
    }

    public function settlements(Request $request)
    {
        $settlements = Settlement::whereHas('user')->get();
        return view('admin.settlement.index', compact('settlements', 'request'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required|unique:users|email_address|max:255',
            'first_name' => 'required',
            'password' => 'required|without_spaces',
            'plan' => 'required',
            'domain_name' => 'required|max:100|unique:domains,domain',
            'full_domain' => 'required|max:100|unique:domains,full_domain',
        ]);


        $info = Plan::find($request->plan);



        DB::beginTransaction();
        try {
            $user = new User;
            $user->first_name = $request->first_name;
            $user->last_name = $request->last_name ?? "";
            $user->email = $request->email;
            $user->password = Hash::make($request->password);
            $user->role_id = 3;
            $user->status = 1;
            $user->save();

            $exp_days =  $info->days;
            $expiry_date = \Carbon\Carbon::now()->addDays(($exp_days - 1))->format('Y-m-d');

            $max_order = Subscription::max('id');
            $order_prefix = Option::where('key', 'order_prefix')->first();


            $order_no = $order_prefix->value . $max_order;

            $tax = Option::where('key', 'tax')->first();
            $tax = ($info->price * 0.01) * $tax->value;

            $subscription = new Subscription;
            $subscription->order_no = $order_no;
            $subscription->amount = $info->amount;
            $subscription->tax = $tax;
            $subscription->trx = $request->transaction_id;
            $subscription->will_expire = $expiry_date;
            $subscription->user_id = $user->id;
            $subscription->plan_id = $info->id;
            // $subscription->category_id = $request->transaction_method;
            $subscription->payment_method = $request->payment_method;
            $subscription->status = 1;
            $subscription->payment_status = 1;
            $subscription->save();

            // $shop = new Shop();
            // $shop->name = $request->domain;
            // $shop->sub_domain = $domain;
            // $shop->shop_type = 'seller';
            // $shop->status = 'active';
            // $shop->owner_id = $user->id;
            // $shop->user_id = $user->id;
            // $shop->save();



            $dom = new Domain;
            $dom->domain = $request->domain_name;
            $dom->full_domain = $request->full_domain;
            $dom->status = 1;
            $dom->user_id = $user->id;
            $dom->is_trial = $info->is_trial;
            $dom->type = 1;
            $dom->data = $info->data;
            $dom->will_expire = $expiry_date;
            $dom->subscription_id = $subscription->id;
            $dom->save();


            $user = User::find($user->id);
            $user->domain_id = $dom->id;
            $user->save();

            $dom->orderlog()->create(['subscription_id' => $subscription->id, 'domain_id' => $dom->id]);


            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        return response()->json(['Customer Created Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth()->user()->can('partner.view')) {
            return abort(401);
        }

        $info = User::with('partner')->where('id', $id)->first();
        $histories = Subscription::with('plan_info', 'PaymentMethod')->where('user_id', $id)->latest()->paginate(40);

        $shops = Shop::where('created_by', $info->id)->latest()->paginate(40);
        return view('admin.partner.show', compact('info', 'histories', 'shops'));
    }

    public function planview($id)
    {
        if (!Auth()->user()->can('customer.edit')) {
            return abort(401);
        }

        $info = User::withCount('term', 'orders', 'customers')->where('role_id', 3)->findorFail($id);

        $domain = Domain::where('user_id', $id)->first();
        $planinfo = json_decode($domain->data);
        abort_if(empty($planinfo), 404);

        return view('admin.customer.planinfo', compact('info', 'planinfo', 'domain'));
    }

    public function updateplaninfo(Request $request, $id)
    {
        $plan_data['product_limit'] = $request->product_limit;
        $plan_data['customer_limit'] = $request->customer_limit;
        $plan_data['storage'] = $request->storage;
        $plan_data['custom_domain'] = $request->custom_domain;
        $plan_data['inventory'] = $request->inventory;
        $plan_data['pos'] = $request->pos;
        $plan_data['customer_panel'] = $request->customer_panel;
        $plan_data['pwa'] = $request->pwa;
        $plan_data['whatsapp'] = $request->whatsapp;
        $plan_data['live_support'] = $request->live_support;
        $plan_data['qr_code'] = $request->qr_code;
        $plan_data['facebook_pixel'] = $request->facebook_pixel;
        $plan_data['custom_css'] = $request->custom_css;
        $plan_data['custom_js'] = $request->custom_js;
        $plan_data['gtm'] = $request->gtm;
        $plan_data['location_limit'] = $request->location_limit;
        $plan_data['category_limit'] = $request->category_limit;
        $plan_data['brand_limit'] = $request->brand_limit;
        $plan_data['variation_limit'] = $request->variation_limit;
        $plan_data['google_analytics'] = $request->google_analytics;

        $domain = Domain::findorFail($id);
        $domain->data = json_encode($plan_data);
        $domain->save();

        return response()->json('Info Updated Successfully');
    }
    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth()->user()->can('partner.edit')) {
            return abort(401);
        }

        $info = User::with('partner')->findorFail($id);
        return view('admin.partner.edit', compact('info'));
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
            'first_name' => 'required',
            'email' => 'required|max:100|email_address|unique:users,email,' . $id,
            'mobile_number' => 'required|min:10',
            // 'threshold_value' => 'required',
        ]);

        $user = User::findorFail($id);
        $user->first_name = $request->first_name;
        $user->last_name = $request->last_name;
        $user->email = $request->email;
        if ($request->password) {
            $user->password = Hash::make($request->password);
        }
        $user->status = $request->partner_status;
        $user->save();

        $partner = Partner::where('user_id', $user->id)->first();

        if (!isset($partner)) {
            $partner = new Partner();
            $partner->user_id = $user->id;
        }
        $partner->mobile_number = $request->mobile_number;
        $partner->commission = $request->commission;
        $partner->settlement_amount = $request->threshold_value;
        $partner->bank_details = $request->bank_details;
        // $partner->status = $request->partner_status;

        if ($request->partner_status != 1 && $user->email_verified_at == null) {
            $partner->status = 0;
        } elseif ($user->email_verified_at != null) {
            $partner->status = $request->partner_status;
        }

        if ($request->partner_status == 1 && $user->email_verified_at != null) {
            $partner->status = 1;
        }
        // else{
        //     $partner->status = 0; 
        // }
        $partner->save();


        if ($request->partner_status == 1) {

            if ($request->partner_status == 1 && $user->email_verified_at != null) {
                $data = [
                    'name' => $user->first_name,
                    'email' => $user->email
                ];


                Mail::to($user->email)->send(new PartnerConfirmationMail($data));
            } else {

                return response()->json(['Partner Account OTP Not Confirmed']);
            }
        }
        if ($request->partner_status != 1 && $user->email_verified_at == null) {
            return response()->json(['Partner Account OTP Not Confirmed']);
        }


        Cache::forget($user->id . '.is_in_active_partner');
        Cache::forget($user->id . '.is_partner');

        return response()->json(['User Updated Successfully']);
    }

    public function save_commission(Request $request)
    {
        $commissions = $request->commissions ?? [];
        $is_valid = true;
        $lastvalue  = 0;
        foreach ($commissions as $commission) {
            if (($commission['from'] >= $commission['to']) || ($lastvalue != $commission['from'])) {
                $is_valid = false;
                break;
            }
            $lastvalue  = $commission['to'] + 1;
        }

        if (!$is_valid) {
            return response()->json(['errors' => ['Invalid commission format.']], 400);
        }

        $partner_commissions = Option::where('key', 'partner_commissions')->first();
        if (empty($partner_commissions)) {
            $partner_commissions = new Option();
            $partner_commissions->key = "partner_commissions";
        }
        $partner_commissions->value = json_encode($commissions);
        $partner_commissions->save();

        $default_commission = Option::where('key', 'default_commission')->first();
        if (empty($default_commission)) {
            $default_commission = new Option();
            $default_commission->key = "default_commission";
        }
        $default_commission->value = $request->default_commission ?? 0;
        $default_commission->save();

        return response()->json(['Success']);
    }

    public function view_commission(Request $request)
    {
        $partner_commissions = Option::where('key', 'partner_commissions')->first();

        $commissions = json_decode($partner_commissions->value ?? "[]", true);

        $default_commission = Option::where('key', 'default_commission')->first();
        $default_commission = $default_commission->value ?? 0;
        $currency = Option::where('key', 'currency')->first();
        $currency = Currency::getDefault();

        return view('admin.partner.commissions', compact('commissions', 'default_commission', 'currency'));
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!Auth()->user()->can('customer.delete')) {
            return abort(401);
        }

        if ($request->type == "term_delete") {
            foreach ($request->ids ?? [] as $key => $id) {
                \App\Models\Product::destroy($id);
            }
        } elseif ($request->type == "user_delete") {
            foreach ($request->ids ?? [] as $key => $id) {
                \App\Models\Customer::destroy($id);
            }
        } else {
            if (!empty($request->method)) {
                if ($request->method == "delete") {
                    foreach ($request->ids ?? [] as $key => $id) {
                        \File::deleteDirectory('uploads/' . $id);
                        $user = User::destroy($id);
                    }
                } else {
                    foreach ($request->ids ?? [] as $key => $id) {
                        $user = User::find($id);
                        if ($request->method == "trash") {
                            $user->status = 0;
                        } else {
                            $user->status = $request->method;
                        }
                        $user->save();
                    }
                }
            }
        }

        return response()->json(['Success']);
    }
    public function mobile_otp(Request $request, $id)
    {

        $info = User::with('partner')->findorFail($id);
        return view('admin.partner.partner_mobile', compact('info'));
    }

    public function update_mobile(Request $request, $id)
    {

        DB::beginTransaction();
        try {

            $user = User::where('id', $id)->first();
            $partner = Partner::where('user_id', $id)->first();
            $partner->mobile_number = $request->mobile_number;
            $partner->save();

            $otp_check = DB::table('password_resets')->where('email', $user->email)->first();
            if (!empty($otp_check)) {
                DB::table('password_resets')->where('email', $user->email)->delete();
                DB::commit();
            }

            DB::commit();
            //   Auth::loginUsingId($id);
            $dta['redirect'] = true;
            $dta['domain'] = route('admin.confirm.otp', $id);
        } catch (\Exception $e) {
            DB::rollback();
        }

        if (!empty($request->email)) {
            $user = User::where('id', $id)->first();
            $userInfo['otp'] = substr(str_shuffle("0123456789"), 0, 4);

            $data = [
                'name' => $user->first_name,
                'email' => $user->email,
                'otp' => $userInfo['otp']
            ];

            $otp_check = DB::table('password_resets')->where('email', $user->email)->first();
            if (!empty($otp_check)) {
                DB::table('password_resets')->where('email', $user->email)->delete();
                DB::commit();
            }

            DB::table('password_resets')->insert([
                'email' => $user->email,
                'token' => $userInfo['otp'],
                'created_at' => Carbon::now()
            ]);

            Mail::to($user->email)->send(new PartnerOtpMail($data));
        }

        return response()->json($dta);
    }
    public function partner_otp(Request $request, $id)
    {

        $info = User::with('partner')->findorFail($id);
        return view("partner.auth.partner_otp", compact('info'));
    }
    public function partner_otpverify(Request $request, $id)
    {
        $otp_verification = $request->otp_verify;
        $user = User::where('id', $id)->first();
        if (!empty($otp_verification)) {
            $user_email_verify = User::where('id', $user->id)->first();
            $otpdata = DB::table('password_resets')
                ->where('email', $user->email)->first();

            if ($otp_verification ==  $otpdata->token) {
                $user_email_verify->email_verified_at = Carbon::now();
                $user_email_verify->save();

                DB::table('password_resets')->where('email', $user->email)->delete();
                DB::commit();
                $dta['redirect'] = true;
                $dta['domain'] = route('admin.partner.index');
            } else {
                $dta['redirect'] = false;
            }
        }

        return response()->json($dta);
    }
}
