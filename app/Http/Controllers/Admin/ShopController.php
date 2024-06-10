<?php

namespace App\Http\Controllers\Admin;

use App\Models\User;
use App\Models\Shop;
use App\Models\Plan;
use App\Models\Domain;
use App\Models\Option;
use App\Models\Customer;
use App\Models\Subscription;
use App\Models\ShopOption;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Models\ShopUser;
use App\Rules\IsValidDomain;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Str;
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
        if (!Auth()->user()->can('shop.list')) {
            return abort(401);
        }
        $type = $request->type ?? 'all';

        $posts = Shop::with('domain', 'subscription', 'user');

        if ($type != "all") {
            if ($type == "expired") {
                $posts = $posts->whereDate('will_expire', '<', Carbon::now());
            } else {
                $posts = $posts->where('status', $type);
            }
        }
        //return $request;
        if (!empty($request->src) && $request->term == "domain") {
            $this->request = $request->src;
            $posts = $posts->whereHas('domain', function ($q) {
                return $q->where('domain', $this->request);
            });
        } elseif (!empty($request->src) &&  $request->term == "shop") {
            $this->request = $request->src;
            $posts = $posts->where('name', $this->request)->orWhere('sub_domain', $this->request)->orWhere('email', $this->request);
        } elseif (!empty($request->src) && $request->term == "customer") {
            $this->request = $request->src;
            $posts = $posts->whereHas('user', function ($q) {
                return $q->where('first_name', $this->request)
                    ->orWhere('last_name', $this->request)
                    ->orWhere('email', $this->request);
            });
        }

        $posts = $posts->latest()->paginate(40);

        $all = Shop::count();
        $actives = Shop::where('status', 'active')->count();
        $suspened = Shop::where('status', 'suspended')->count();
        $trash = Shop::where('status', 'trash')->count();
        $requested = Shop::where('status', 'requested')->count();
        $pendings = Shop::where('status', 'pending')->count();
        $expired = Shop::whereDate('will_expire', '<', Carbon::now())->count();
        return view('admin.shop.index', compact('posts', 'request', 'type', 'all', 'actives', 'expired', 'suspened', 'trash', 'requested', 'pendings'));
    }

    public function seller(Request $request)
    {
        if (!Auth()->user()->can('shop.list')) {
            return abort(401);
        }
        $type = $request->type ?? 'all';

        $posts = Shop::with('domain', 'subscription', 'user');

        if ($type != "all") {
            if ($type == "expired") {
                $posts = $posts->whereDate('will_expire', '<', Carbon::now());
            } else {
                $posts = $posts->where('status', $type);
            }
        }
        //return $request;
        if (!empty($request->src) && $request->term == "domain") {
            $this->request = $request->src;
            $posts = $posts->whereHas('domain', function ($q) {
                return $q->where('domain', $this->request);
            });
        } elseif (!empty($request->src) &&  $request->term == "shop") {
            $this->request = $request->src;
            $posts = $posts->where('name', $this->request)->orWhere('sub_domain', $this->request)->orWhere('email', $this->request);
        } elseif (!empty($request->src) && $request->term == "customer") {
            $this->request = $request->src;
            $posts = $posts->whereHas('user', function ($q) {
                return $q->where('first_name', $this->request)
                    ->orWhere('last_name', $this->request)
                    ->orWhere('email', $this->request);
            });
        }

        $posts = $posts->where('shop_type','seller')->latest()->paginate(40);

        $all = Shop::where('shop_type','seller')->count();
        $actives = Shop::where('shop_type','seller')->where('status', 'active')->count();
        $suspened = Shop::where('shop_type','seller')->where('status', 'suspended')->count();
        $trash = Shop::where('shop_type','seller')->where('status', 'trash')->count();
        $requested = Shop::where('shop_type','seller')->where('status', 'requested')->count();
        $pendings = Shop::where('shop_type','seller')->where('status', 'pending')->count();
        $expired = Shop::where('shop_type','seller')->whereDate('will_expire', '<', Carbon::now())->count();
        return view('admin.shop.index', compact('posts', 'request', 'type', 'all', 'actives', 'expired', 'suspened', 'trash', 'requested', 'pendings'));
    }

    public function supplier(Request $request)
    {
        if (!Auth()->user()->can('shop.list')) {
            return abort(401);
        }
        $type = $request->type ?? 'all';

        $posts = Shop::with('domain', 'subscription', 'user');

        if ($type != "all") {
            if ($type == "expired") {
                $posts = $posts->whereDate('will_expire', '<', Carbon::now());
            } else {
                $posts = $posts->where('status', $type);
            }
        }
        //return $request;
        if (!empty($request->src) && $request->term == "domain") {
            $this->request = $request->src;
            $posts = $posts->whereHas('domain', function ($q) {
                return $q->where('domain', $this->request);
            });
        } elseif (!empty($request->src) &&  $request->term == "shop") {
            $this->request = $request->src;
            $posts = $posts->where('name', $this->request)->orWhere('sub_domain', $this->request)->orWhere('email', $this->request);
        } elseif (!empty($request->src) && $request->term == "customer") {
            $this->request = $request->src;
            $posts = $posts->whereHas('user', function ($q) {
                return $q->where('first_name', $this->request)
                    ->orWhere('last_name', $this->request)
                    ->orWhere('email', $this->request);
            });
        }

        $posts = $posts->where('shop_type','supplier')->latest()->paginate(40);

        $all = Shop::where('shop_type','supplier')->count();
        $actives = Shop::where('shop_type','supplier')->where('status', 'active')->count();
        $suspened = Shop::where('shop_type','supplier')->where('status', 'suspended')->count();
        $trash = Shop::where('shop_type','supplier')->where('status', 'trash')->count();
        $requested = Shop::where('shop_type','supplier')->where('status', 'requested')->count();
        $pendings = Shop::where('shop_type','supplier')->where('status', 'pending')->count();
        $expired = Shop::where('shop_type','supplier')->whereDate('will_expire', '<', Carbon::now())->count();
        return view('admin.shop.index', compact('posts', 'request', 'type', 'all', 'actives', 'expired', 'suspened', 'trash', 'requested', 'pendings'));
    }

    public function reseller(Request $request)
    {
        if (!Auth()->user()->can('shop.list')) {
            return abort(401);
        }
        $type = $request->type ?? 'all';

        $posts = Shop::with('domain', 'subscription', 'user');

        if ($type != "all") {
            if ($type == "expired") {
                $posts = $posts->whereDate('will_expire', '<', Carbon::now());
            } else {
                $posts = $posts->where('status', $type);
            }
        }
        //return $request;
        if (!empty($request->src) && $request->term == "domain") {
            $this->request = $request->src;
            $posts = $posts->whereHas('domain', function ($q) {
                return $q->where('domain', $this->request);
            });
        } elseif (!empty($request->src) &&  $request->term == "shop") {
            $this->request = $request->src;
            $posts = $posts->where('name', $this->request)->orWhere('sub_domain', $this->request)->orWhere('email', $this->request);
        } elseif (!empty($request->src) && $request->term == "customer") {
            $this->request = $request->src;
            $posts = $posts->whereHas('user', function ($q) {
                return $q->where('first_name', $this->request)
                    ->orWhere('last_name', $this->request)
                    ->orWhere('email', $this->request);
            });
        }

        $posts = $posts->where('shop_type','reseller')->latest()->paginate(40);

        $all = Shop::where('shop_type','reseller')->count();
        $actives = Shop::where('shop_type','reseller')->where('status', 'active')->count();
        $suspened = Shop::where('shop_type','reseller')->where('status', 'suspended')->count();
        $trash = Shop::where('shop_type','reseller')->where('status', 'trash')->count();
        $requested = Shop::where('shop_type','reseller')->where('status', 'requested')->count();
        $pendings = Shop::where('shop_type','reseller')->where('status', 'pending')->count();
        $expired = Shop::where('shop_type','reseller')->whereDate('will_expire', '<', Carbon::now())->count();
        return view('admin.shop.index', compact('posts', 'request', 'type', 'all', 'actives', 'expired', 'suspened', 'trash', 'requested', 'pendings'));
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth()->user()->can('shop.create')) {
            return abort(401);
        }

        return view('admin.shop.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $validatedData = $request->validate(
            [
                'first_name' => 'required',
                'last_name' => 'required',
                'email' => 'required|unique:users|email_address|max:255',
                'mobile_number' => 'required|integer|digits:10',
                'password' => 'required|without_spaces',
                'plan' => 'required',
                'shop_name' => 'required',
                'shop_name' => [new IsValidDomain()],
                // 'domain_name' => 'required|max:100|unique:domains,domain',
                // 'full_domain' => 'required|max:100|unique:domains,full_domain',
            ],
            [
                'mobile_number.integer' => 'Invalid mobile number',
                'mobile_number.digits' => 'Invalid mobile number'
            ]
        );


        $info = Plan::find($request->plan);

        $domain = preg_replace('/[^a-zA-Z0-9\']/', '', $request->shop_name);
        $domain = str_replace("'", '', $domain);

        $full_domain = env('APP_PROTOCOL') . Str::lower($domain) . '.' . env('APP_PROTOCOLESS_URL');



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
            $shop->name = Str::lower($request->shop_name);
            $shop->sub_domain = Str::lower($domain);
            $shop->email = $request->email;
            $shop->shop_type = 'seller';
            $shop->status = 'active';
            $shop->created_by = $user->id;
            $shop->user_id = $user->id;
            $shop->save();

            $shop_id = $shop->id;

            $shop_mode = new ShopOption();
            $shop_mode->key = 'shop_mode';
            $shop_mode->value = 'offline';
            $shop_mode->shop_id = $shop->id;
            $shop_mode->save();

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

            $shop_mobile_number = new ShopOption();
            $shop_mobile_number->key = 'store_mobile_number';
            $shop_mobile_number->value = $request->mobile_number;
            $shop_mobile_number->shop_id = $shop->id;
            $shop_mobile_number->save();

            $exp_days =  $info->days;
            $expiry_date = \Carbon\Carbon::now()->addDays(($exp_days - 1))->format('Y-m-d');

            $max_order = Subscription::max('id');
            $order_prefix = Option::where('key', 'order_prefix')->first();


            $order_no = $order_prefix->value . $max_order;

            $tax = Option::where('key', 'tax')->first();
            $tax = ($info->price * 0.01) * $tax->value;
            $final_amount =  $info->price + $tax;


            $subscription = new Subscription();
            $subscription->order_no = $order_no;
            $subscription->amount = $final_amount;
            $subscription->tax = $tax;
            $subscription->trx = $request->transaction_id;
            $subscription->will_expire = $expiry_date;
            $subscription->user_id = $user->id;
            $subscription->plan_id = $info->id;
            // $subscription->category_id = $request->transaction_method;
            $subscription->payment_method = $request->payment_method;
            $subscription->status = 1;
            $subscription->payment_status = 1;
            $subscription->shop_id = $shop->id;
            $subscription->save();



            $dom = new Domain();
            $dom->domain = Str::lower($domain) . '.' . env('APP_PROTOCOLESS_URL');
            $dom->full_domain = Str::lower($full_domain);
            $dom->status = 1;
            $dom->type = 1;
            $dom->shop_id = $shop->id;
            $dom->save();

            $shop->data = $info->data;
            $shop->will_expire = $expiry_date;
            $shop->subscription_id = $subscription->id;
            $shop->will_expire = $expiry_date;
            $shop->data = $info->data;
            $shop->is_trial = 1;
            $shop->save();

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
        if (!Auth()->user()->can('shop.view')) {
            return abort(401);
        }

        $info = Shop::withCount('term', 'orders', 'customers')->with('domain', 'subscription', 'user')->findorFail($id);
        $histories = Subscription::with('plan', 'PaymentMethod')->where('shop_id', $info->id)->latest()->paginate(40);

        $customers = Customer::withCount('orders')->where('shop_id',  $info->id)->latest()->paginate(40);
        $posts = \App\Models\Product::where('shop_id', $info->id)->latest()->paginate(40);

        $bank_details = ShopOption::where('key', 'bank_details')->where('shop_id', $info->id)->first();
        $bank_details = json_decode($bank_details->value ?? '');

        $location = ShopOption::where('shop_id', $info->id)->where('key', 'location')->first();
        $location = json_decode($location->value ?? '');
        
        $get_shop = Shop::findorFail($id);

        return view('admin.shop.show', compact('info', 'histories', 'customers', 'posts','bank_details','location','get_shop'));
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

        return view('admin.shop.planinfo', compact('info', 'planinfo', 'domain'));
    }

    public function updateplaninfo(Request $request, $id)
    {
        $plan_data['product_limit'] = $request->product_limit ?? "";
        $plan_data['customer_limit'] = $request->customer_limit ?? "";
        $plan_data['agent_limit'] = $request->agent_limit ?? "";
        $plan_data['storage'] = $request->storage ?? "";
        $plan_data['location_limit'] = $request->location_limit  ?? "";
        $plan_data['category_limit'] = $request->category_limit  ?? "";
        $plan_data['brand_limit'] = $request->brand_limit ?? "";
        $plan_data['variation_limit'] = $request->variation_limit  ?? "";
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
        $plan_data['google_analytics'] = $request->google_analytics;

        $shop = Shop::with('domain')->findorFail($id);
        $shop->data = json_encode($plan_data);
        $shop->save();

        Cache::forget($shop->domain->domain);

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
        if (!Auth()->user()->can('shop.edit')) {
            return abort(401);
        }

        $info = Shop::findorFail($id);
        return view('admin.shop.edit', compact('info'));
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

        // $validator = Validator::make($request->all(), [
        //     'name' => 'required|min:4|max:15',
        //     'change_subdomain' => 'sometimes|required|boolean',
        //     'sub_domain' => 'required_if:change_subdomain|max:50|unique:shops,sub_domain,' . $id
        //   ]);

        $shop = Shop::findorFail($id);
        $shop_name_validation = preg_replace('/[^a-zA-Z0-9\']/', '', $request->name);
        $shop_name_validation = str_replace("'", '', $shop_name_validation);
        $shop_name = Str::lower($shop_name_validation);
        $shop_name_find = Shop::where('name', $shop_name)->first();
        $current_shop_name = Shop::where('id', $id)->first();

        if ($request->get('change_subdomain', 0) != 1) {

            if ($current_shop_name->name == $request->name) {
                $shop->status = $request->status;
                $shop->save();
                return response()->json(['Status Updated Successfully']);
            } else {
                if (empty($shop_name_find)) {
                    if (!empty($request->name)) {
                        $shop_option = ShopOption::where('shop_id', $id)->where('key', 'shop_name')->first();
                        if (!empty($shop_option)) {
                            $shop_option->value = Str::lower($shop_name_validation);
                            $shop_option->save();
                        }
                        if (empty($shop_option)) {
                            $shop_name = new ShopOption;
                            $shop_name->key = 'shop_name';
                            $shop_name->value = Str::lower($shop_name_validation);
                            $shop_name->shop_id = $id;
                            $shop_name->save();
                        }
                    }
                    $shop->name = Str::lower($shop_name_validation);
                    $shop->status = $request->status ?? 'active';
                    $shop->save();

                    return response()->json(['User Updated Successfully']);
                } else {
                    return response()->json(['errors' => ['Shop Name Already Exist']], 400);
                }
            }
        }



        if ($request->get('change_subdomain', 0) == 1) {


            $domain = preg_replace('/[^a-zA-Z0-9\']/', '', $request->sub_domain);
            $domain = str_replace("'", '', $domain);
            $full_domain = env('APP_PROTOCOL') . Str::lower($domain) . '.' . env('APP_PROTOCOLESS_URL');
            $req_domain = Str::lower($domain);

            $shop_domain_find = Shop::where('sub_domain', $req_domain)->first();
            $current_domain_name = Shop::where('id', $id)->first();

            if ($current_domain_name->sub_domain == $req_domain) {
                return response()->json(['User Updated Successfully']);
            } else {
                if (empty($shop_domain_find)) {
                    $shop = Shop::where('id', $id)->first();
                    $dom = Domain::where('type', 1)->where('shop_id', $id)->first();
                    $dom->domain =  Str::lower($domain) . '.' . env('APP_PROTOCOLESS_URL');
                    $dom->full_domain = $full_domain;
                    $dom->save();
                    $shop->sub_domain = Str::lower($domain);
                    $shop->save();
                    return response()->json(['User Updated Successfully']);
                } else {
                    return response()->json(['errors' => ['Store Domain Already Exist']], 400);
                }
            }
        }
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
                            $user->status = 'trash';
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
