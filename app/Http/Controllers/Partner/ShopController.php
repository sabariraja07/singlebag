<?php

namespace App\Http\Controllers\Partner;

use App\Models\User;
use App\Models\Shop;
use App\Models\Plan;
use App\Models\Domain;
use App\Models\Option;
use App\Models\Customer;
use App\Models\Subscription;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ShopUser;
use App\Models\ShopOption;
use App\Rules\IsValidDomain;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
use App\Mail\Contactmail;
use App\Models\Settlement;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Validator;

class ShopController extends Controller
{
    protected $request;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $type = $request->type ?? 'all';
        //return $request;
        if (!empty($request->src) && $request->term == "domain") {
            $this->request = $request->src;
            if ($type === 'all') {

                $posts = Shop::whereHas('domain', function ($q) {
                    return $q->where('domain', $this->request);
                })->with('domain', 'subscription', 'user')->where('created_by', Auth::id())->latest()->paginate(40);
            } else {
                $posts = Shop::where('status', $type)->whereHas('domain', function ($q) {
                    return $q->where('domain', $request->src);
                })->with('domain', 'subscription', 'user')->where('created_by', Auth::id())->where('status', $type)->latest()->paginate(40);
            }
        } elseif (!empty($request->src) && !empty($request->term)) {
            if ($type === 'all') {
                $posts = Shop::with('domain', 'subscription', 'user')->where('created_by', Auth::id())->where($request->term, $request->src)->latest()->paginate(40);
            } else {
                $posts = Shop::where('status', $type)->with('domain', 'subscription', 'user')->where('created_by', Auth::id())->where($request->term, $request->src)->latest()->paginate(40);
            }
        } else {
            if ($type === 'all') {
                $posts = Shop::with('domain', 'subscription')->where('created_by', Auth::id())->latest()->paginate(40);
            } else {
                $posts = Shop::where('status', $type)->with('domain', 'subscription', 'user')->where('created_by', Auth::id())->latest()->paginate(40);
            }
        }

        $all = Shop::where('created_by' , Auth::id())->count();
        $actives = Shop::where('created_by' , Auth::id())->where('status', 'active')->count();
        $suspened = Shop::where('created_by' , Auth::id())->where('status', 'suspended')->count();
        $trash = Shop::where('created_by' , Auth::id())->where('status', 'trash')->count();
        $requested = Shop::where('created_by' , Auth::id())->where('status', 'requested')->count();
        $pendings = Shop::where('created_by' , Auth::id())->where('status', 'pending')->count();
        return view('partner.shop.index', compact('posts', 'request', 'type', 'all', 'actives', 'suspened', 'trash', 'requested', 'pendings'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('partner.shop.create');
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

        $full_domain = env('APP_PROTOCOL') . Str::lower($domain).'.'.env('APP_PROTOCOLESS_URL');



        DB::beginTransaction();
        try {
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
            $shop->save();

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

            $exp_days =  $info->days;
            $expiry_date = \Carbon\Carbon::now()->addDays(($exp_days-1))->format('Y-m-d');

            $max_order = Subscription::max('id');
            $order_prefix = Option::where('key', 'order_prefix')->first();


            $order_no = $order_prefix->value . $max_order;

            $tax = Option::where('key', 'tax')->first();
            $tax = ($info->price * 0.01) * $tax->value;

            if($info->is_trial){
                $subscription = new Subscription();
                $subscription->order_no = $order_no;
                $subscription->amount = $info->amount;
                $subscription->tax = $tax;
                $subscription->trx = $request->transaction_id;
                $subscription->will_expire = $expiry_date;
                $subscription->user_id =Auth::id();
                $subscription->plan_id = $info->id;
                if($info->is_trial == 1 ){
                    // $subscription->category_id = 2;
                    $subscription->payment_method = 'cod';
                } else {
                    // $subscription->category_id = $request->transaction_method;
                    $subscription->payment_method = $request->payment_method;
                }
                $subscription->status = 1;
                $subscription->payment_status = 1;
                $subscription->shop_id = $shop->id;
                $subscription->save();

                $shop->data = $info->data;
                $shop->will_expire = $expiry_date;
                $shop->subscription_id = $subscription->id;
                $shop->will_expire = $expiry_date;
                $shop->data = $info->data;
                $shop->is_trial = 1;
                $shop->save();
            }



            $dom = new Domain();
            $dom->domain = $domain . '.'.env('APP_PROTOCOLESS_URL');
            $dom->full_domain = $full_domain;
            $dom->status = 1;
            $dom->type = 1;
            $dom->shop_id = $shop->id;
            $dom->save();
      
            

            $role = Role::where('name', 'admin')->where('guard_name' , 'web')->first();

            $shop_user = new ShopUser();
            $shop_user->shop_id = $shop->id;
            $shop_user->role_id = $role->id;
            $shop_user->user_id = $user->id;
            $shop_user->status = 1;
            $shop_user->save();


            DB::commit();
            if(!$info->is_trial){
                return response()->json(['redirect' => route('partner.make_payment', [$info->id, 'shop_id=' . $shop->id]), 'message' => 'Shop Created Successfully']);
            }

        } catch (\Exception $e) {
            DB::rollback();
        }




        return response()->json(['redirect' => route('partner.shop.index'),'message' => 'Shop Created Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth()->user()->isPartner()) {
            return abort(401);
        }

        $info = Shop::withCount('term', 'orders', 'customers')->where('created_by', Auth::id())->with('domain', 'subscription', 'user')->findorFail($id);
        $histories = Subscription::with('plan', 'PaymentMethod')->where('shop_id', $info->id)->latest()->paginate(40);

        $customers = Customer::withCount('orders')->where('shop_id', $info->id)->latest()->paginate(40);
        $posts = \App\Models\Product::where('shop_id', $info->id)->latest()->paginate(40);
        return view('partner.shop.show', compact('info', 'histories', 'customers', 'posts'));
    }

    public function planview($id)
    {
        if (!Auth()->user()->can('shop.edit')) {
            return abort(401);
        }

        $info = Shop::withCount('term', 'orders', 'customers')->findorFail($id);

        $domain = Domain::where('shop_id', $info->id)->first();
        $planinfo = json_decode($info->data);
        abort_if(empty($planinfo), 404);

        return view('partner.shop.planinfo', compact('info', 'planinfo', 'domain'));
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

        $shop = Shop::findorFail($id);
        $shop->data = json_encode($plan_data);
        $shop->save();

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
        if (!Auth()->user()->isPartner()) {
            return abort(401);
        }

        $info = Shop::where('created_by', Auth::id())->findorFail($id);
        return view('partner.shop.edit', compact('info'));
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
            'name' => 'required|max:50',
            // 'email' => 'required|max:100|email_address|unique:users,email,' . $id,
        ]);

        $shop = Shop::findorFail($id);
        $shop->name = $request->name;
        $shop->status = $request->status ?? 'active';
        $shop->save();

        return response()->json(['User Updated Successfully']);
    }

    public function sendmail(Request $request)
    {
        $validatedData = $request->validate([
            'email' => 'required',
            'subject' => 'required',
            'content' => 'required',
        ]);
        $data['content']=$request->content;
        $data['subject']=$request->subject;
        $data['to_subscriber']=$request->email;
        $data['mail_from']=env('MAIL_TO');
        if(env('QUEUE_MAIL') == 'on'){
           dispatch(new \App\Jobs\SendInvoiceEmail($data));
       }
       else{
           Mail::to($request->email)->send(new Contactmail($data));
       }

       return response()->json(['Mail Sent Successfully']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!Auth()->user()->can('shop.delete')) {
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
                        $user = Shop::destroy($id);
                    }
                } else {
                    foreach ($request->ids ?? [] as $key => $id) {
                        $user = Shop::find($id);
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
}
