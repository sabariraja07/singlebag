<?php

namespace App\Http\Controllers\Seller;

use App\Models\Plan;
use App\Models\Shop;
use App\Models\Option;
use App\Models\Domain;
use App\Models\ShopUser;
use App\Models\ShopOption;
use Illuminate\Support\Str;
use App\Rules\IsValidDomain;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Mail\StoreWelcomeMail;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Mail\NewStoreWelcomeMail;

class ShopController extends Controller
{

    public function index()
    {
        $user = Auth::user();

        $shops = Shop::whereHas('ShopUsers', function($q) use($user) {
                       $q->where('user_id', $user->id);
                    })
                    ->latest()
                    ->paginate(20);
        
        return view('seller.shops.index', compact('shops'));
    }

    public function create()
    {
        return view('seller.shops.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shop_name' => 'required|max:20|string',
            'shop_name' => [new IsValidDomain()],
            'city' => 'required|string|max:255',
            'state' => 'required|string|max:255',
            'description' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'email' => 'required|email_address|max:255',
            'mobile_number' => 'required|integer|digits:10',
            'zip_code' => 'required|max:10',
            'language' => 'required',
            'shop_type' => 'required',
        ],
        [
            'mobile_number.integer' => 'Invalid mobile number',
            'mobile_number.digits' => 'Invalid mobile number'
        ]);

        $user = auth()->user();
    
        $info = Plan::where('status', 1)->where('is_trial', 1)->first();
    
        $domain = preg_replace('/[^a-zA-Z0-9\']/', '', $request->shop_name);
        $domain = str_replace("'", '', $domain);
    
        $full_domain = env('APP_PROTOCOL') . Str::lower($domain).'.'.env('APP_PROTOCOLESS_URL');
    
        DB::beginTransaction();
        try {
    
        $shop = new Shop();
        $shop->name = $request->shop_name;
        $shop->email = $request->email;
        $shop->sub_domain = Str::lower($domain);
        $shop->shop_type = $request->shop_type;
        $shop->status = 'active';
        $shop->created_by = $user->id;
        $shop->user_id = $user->id;
        $shop->save();
    
        $shop_mode = new ShopOption();
        $shop_mode->key = 'shop_mode';
        $shop_mode->value = 'offline';
        $shop_mode->shop_id = $shop->id;
        $shop_mode->save();
    
        $shop_name = new ShopOption();
        $shop_name->key = 'shop_name';
        $shop_name->value = $request->shop_name;
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
    
        $shop_mode_duration = new ShopOption();
        $shop_mode_duration->key = 'shop_mode_duration';
        $shop_mode_duration->shop_id = $shop->id;
        $shop_mode_duration->save();
    
        $exp_days =  $info->days;
        $expiry_date = \Carbon\Carbon::now()->addDays(($exp_days-1))->format('Y-m-d');
    
        $max_order = Subscription::max('id');
        $order_prefix = Option::where('key', 'order_prefix')->first();
    
    
        $order_no = $order_prefix->value . $max_order;
    
        $subscription = new Subscription();
        $subscription->order_no = $order_no;
        $subscription->amount = 0;
        $subscription->tax = 0;
        $subscription->trx = Str::random(15) . $max_order;
        $subscription->will_expire = $expiry_date;
        $subscription->user_id = $user->id;
        $subscription->plan_id = $info->id;
        // $subscription->category_id = 2;
        $subscription->payment_method = 'cod';
        $subscription->status = 1;
        $subscription->payment_status = 1;
        $subscription->shop_id = $shop->id;
        $subscription->save();
    
        $dom = new Domain();
        $dom->domain = $domain . '.'.env('APP_PROTOCOLESS_URL');
        $dom->full_domain = $full_domain;
        $dom->status = 1;
        $dom->type = 1;
        $dom->shop_id = $shop->id;
        $dom->save();
    
        $shop->data = $info->data;
        $shop->will_expire = $expiry_date;
        $shop->subscription_id = $subscription->id;
        $shop->will_expire = $expiry_date;
        $shop->is_trial = 1;
        $shop->save();
    
        $role = Role::where('name', 'admin')->where('guard_name' , 'web')->first();
    
        $shop_user = new ShopUser();
        $shop_user->shop_id = $shop->id;
        $shop_user->role_id = $role->id;
        $shop_user->user_id = $user->id;
        $shop_user->status = 1;
        $shop_user->save();

        $shop_description = ShopOption::where('shop_id', $shop->id)->where('key', 'shop_description')->first();
        if (empty($shop_description)) {
            $shop_description = new ShopOption();
            $shop_description->key = 'shop_description';
        }
        $shop_description->value = $request->description ?? "";
        $shop_description->shop_id = $shop->id;
        $shop_description->save();

        $location = ShopOption::where('shop_id', $shop->id)->where('key', 'location')->first();
        if (empty($location)) {
            $location = new ShopOption;
            $location->key = 'location';
        }
        $data['company_name'] = $shop->name;
        $data['address'] = $request->address;
        $data['city'] = $request->city;
        $data['state'] = $request->state;
        $data['zip_code'] = $request->zip_code;
        $data['email'] = '';
        $data['phone'] = '';
        $data['invoice_description'] = '';
    
        $location->value = json_encode($data);
        $location->shop_id = $shop->id;
        $location->save();

        $local = ShopOption::where('shop_id', $shop->id)->where('key', 'local')->first();
        if (empty($local)) {
            $local = new ShopOption;
            $local->key = 'local';
        }
        $local->value = $request->language ?? 'en';
        $local->shop_id = $shop->id;
        $local->save();

        DB::commit();
    
        $shop = $shop->load(['user', 'domain']);
    
        if (env('QUEUE_MAIL') == 'on') {
            dispatch(new \App\Jobs\StoreWelcomeMailJob($shop));
        } else {
            // Mail::to($shop->email)->send(new StoreWelcomeMail($shop));
            Mail::to($shop->email)->send(new NewStoreWelcomeMail($shop));
        }
        } catch (\Exception $e) {
            DB::rollback();
        }
        Session::flash('success', trans('Shop Created Successfully'));
        return back();
    }

    public function show($id)
    {
        $user = Auth::user();
        $info = Shop::where('shop_id', current_shop_id())->findorFail($id);
        return response()->json($info->child_relation);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $user = Auth::user();
        $info = Shop::where('shop_id', current_shop_id())->with('parent_relation')->findorFail($id);

        return view('seller.shops.edit', compact('info', 'delivery_time', 'data'));
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
        $validatedData = $request->validate([
            'title' => 'required|max:50',
            'price' => 'required|max:50',
            'locations' => 'required',

        ]);
        $user = Auth::user();
        $post = Shop::where('shop_id', current_shop_id())->findorFail($id);
        $post->save();

        return response()->json([trans('Method Updated Successfully')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $user = Auth::user();
        if ($request->method == 'delete') {
            foreach ($request->ids as $key => $id) {
                $post = Shop::where('shop_id', current_shop_id())->findorFail($id);
                $post_meta = Shop::where('category_id', $post->$id);
                $post_meta->delete();
                $post->delete();
            }
        }

        return response()->json([trans('Shop Deleted')]);
    }
}
