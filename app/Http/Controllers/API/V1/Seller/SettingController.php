<?php

namespace App\Http\Controllers\API\V1\Seller;

use DateTimeZone;
use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Shop;
use App\Models\ShopOption;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Models\PaymentMethod;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Storage;

class SettingController extends Controller
{
    use ApiResponser;
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user = Auth::user();
        if ($request->type == 'general') {

            $request->validate([
                'shop_name' => 'required|min:4|max:15',
                'store_email' => 'required|max:50|email_address',
                'store_mobile_number' => 'required|min:10|max:10',
                'order_prefix' => 'required|max:20',
                'currency' => 'required|max:10',
                'lanugage' => 'required',
                'local' => 'required',
                // 'account_no' => 'digits_between:6,20',
                // 'store_email' => [
                //     new EmailSpam()
                // ],
            ]);

            if (current_shop_type() != 'supplier') {
                $request->validate([
                    'shop_description' => 'required|max:250'
                ]);
            }

            $shop_name = ShopOption::where('shop_id', current_shop_id())->where('key', 'shop_name')->first();
            if (empty($shop_name)) {
                $shop_name = new ShopOption();
                $shop_name->key = 'shop_name';
            }
            $shop_validation = preg_replace('/[^a-zA-Z0-9\']/', '', $request->shop_name);
            $shop_validation = str_replace("'", '', $shop_validation);
            $shop_name->value = Str::lower($shop_validation);
            $shop_name->shop_id = current_shop_id();
            $shop_name->save();

            if (!empty($request->shop_name)) {
                $admin_shop = Shop::where('id', current_shop_id())->first();
                $admin_shop->name = Str::lower($shop_validation);
                $admin_shop->save();
            }

            $shop_description = ShopOption::where('shop_id', current_shop_id())->where('key', 'shop_description')->first();
            if (empty($shop_description)) {
                $shop_description = new ShopOption;
                $shop_description->key = 'shop_description';
            }
            $shop_description->value = $request->shop_description ?? '';
            $shop_description->shop_id = current_shop_id();
            $shop_description->save();


            $store_email = ShopOption::where('shop_id', current_shop_id())->where('key', 'store_email')->first();
            if (empty($store_email)) {
                $store_email = new ShopOption;
                $store_email->key = 'store_email';
            }
            $store_email->value = $request->store_email;
            $store_email->shop_id = current_shop_id();
            $store_email->save();

            $store_mobile_number = ShopOption::where('shop_id', current_shop_id())->where('key', 'store_mobile_number')->first();
            if (empty($store_mobile_number)) {
                $store_mobile_number = new ShopOption();
                $store_mobile_number->key = 'store_mobile_number';
            }
            $store_mobile_number->value = $request->store_mobile_number;
            $store_mobile_number->shop_id = current_shop_id();
            $store_mobile_number->save();

            $order_prefix = ShopOption::where('shop_id', current_shop_id())->where('key', 'order_prefix')->first();
            if (empty($order_prefix)) {
                $order_prefix = new ShopOption;
                $order_prefix->key = 'order_prefix';
            }
            $order_prefix->value = $request->order_prefix;
            $order_prefix->shop_id = current_shop_id();
            $order_prefix->save();

            $local = ShopOption::where('shop_id', current_shop_id())->where('key', 'local')->first();
            if (empty($local)) {
                $local = new ShopOption;
                $local->key = 'local';
            }
            $local->value = $request->local;
            $local->shop_id = current_shop_id();
            $local->save();

            $order_receive_method = ShopOption::where('shop_id', current_shop_id())->where('key', 'order_receive_method')->first();
            if (empty($order_receive_method)) {
                $order_receive_method = new ShopOption;
                $order_receive_method->key = 'order_receive_method';
            }
            $order_receive_method->value = $request->order_receive_method;
            $order_receive_method->shop_id = current_shop_id();
            $order_receive_method->save();



            $currency = ShopOption::where('shop_id', current_shop_id())->where('key', 'currency')->first();
            if (empty($currency)) {
                $currency = new ShopOption;
                $currency->key = 'currency';
            }

            $currency->value = $request->currency;
            $currency->shop_id = current_shop_id();
            $currency->save();
            Cache::forget(current_shop_id() . 'currency_info');

            $bank_details = ShopOption::where('shop_id', current_shop_id())->where('key', 'bank_details')->first();
            if (empty($bank_details)) {
                $bank_details = new ShopOption;
                $bank_details->key = 'bank_details';
            }
            $accountInfo['account_holder_name'] = $request->account_holder_name;
            $accountInfo['ifsc_code'] = $request->ifsc_code;
            $accountInfo['account_no'] = $request->account_no;

            $bank_details->value = json_encode($accountInfo);
            $bank_details->shop_id = current_shop_id();
            $bank_details->save();

            $langs = $request->lanugage ?? ['en'];
            // foreach ($request->lanugage as $key => $value) {
            //     $str = explode(',', $value);
            //     $langs[$str[0]] = $str[1];
            // }
            $languages = ShopOption::where('shop_id', current_shop_id())->where('key', 'languages')->first();
            if (empty($languages)) {
                $languages = new ShopOption;
                $languages->key = 'languages';
                $languages->shop_id = current_shop_id();
            }
            $languages->value = json_encode($langs);
            $languages->save();

            Cache::forget('get_shop_languages_' . current_shop_id());

            $tax = ShopOption::where('shop_id', current_shop_id())->where('key', 'tax')->first();
            if (empty($tax)) {
                $tax = new ShopOption;
                $tax->key = 'tax';
                $tax->shop_id = current_shop_id();
            }
            $tax->value = $request->tax;
            $tax->save();
            Cache::forget('tax' . current_shop_id());


            $gst = ShopOption::where('shop_id', current_shop_id())->where('key', 'gst')->first();
            if (empty($gst)) {
                $gst = new ShopOption;
                $gst->key = 'gst';
                $gst->shop_id = current_shop_id();
            }
            $gst->value = $request->gst;
            $gst->save();

            $pan = ShopOption::where('shop_id', current_shop_id())->where('key', 'pan')->first();
            if (empty($pan)) {
                $pan = new ShopOption;
                $pan->key = 'pan';
                $pan->shop_id = current_shop_id();
            }
            $pan->value = $request->pan;
            $pan->save();

            $aadhar = ShopOption::where('shop_id', current_shop_id())->where('key', 'aadhar')->first();
            if (empty($aadhar)) {
                $aadhar = new ShopOption;
                $aadhar->key = 'aadhar';
                $aadhar->shop_id = current_shop_id();
            }
            $aadhar->value = $request->aadhar;
            $aadhar->save();

            Cache::forget('gst' . current_shop_id());

            return $this->success(null, trans('Updated success'));
        }

        if ($request->type == 'location') {
            if (current_shop_type() != 'reseller') {
                $request->validate([
                    'company_name' => 'required|max:20',
                    'address' => 'required|max:250',
                    'city' => 'required|max:20',
                    'state' => 'required|max:20',
                    'zip_code' => 'required|max:20',
                    'email' => 'required|max:30',
                    'phone' => 'required|max:15',
                ]);
            } else {
                $request->validate([
                    'company_name' => 'required|max:20',
                    'city' => 'required|max:20',
                    'state' => 'required|max:20',
                ]);
            }

            $location = ShopOption::where('shop_id', current_shop_id())->where('key', 'location')->first();
            if (empty($location)) {
                $location = new ShopOption;
                $location->key = 'location';
            }
            $data['company_name'] = $request->company_name;
            $data['address'] = $request->address;
            $data['city'] = $request->city;
            $data['state'] = $request->state;
            $data['zip_code'] = $request->zip_code;
            $data['email'] = $request->email;
            $data['phone'] = $request->phone;
            $data['invoice_description'] = $request->invoice_description;

            $location->value = json_encode($data);
            $location->shop_id = current_shop_id();
            $location->save();

            $langlist = \App\Models\Option::where('key', 'languages')->first();
            $langlist = json_decode($langlist->value ?? '');

            $languages = ShopOption::where('shop_id', current_shop_id())->where('key', 'languages')->first();
            $active_languages = json_decode($languages->value ?? '');
            $my_languages = [];
            foreach ($active_languages ?? [] as $key => $value) {
                array_push($my_languages, $value);
            }

            $def_languages = base_path('resources/lang/langlist.json');
            $def_languages = json_decode(file_get_contents($def_languages), true);

            $shop_name = ShopOption::where('key', 'shop_name')->where('shop_id', current_shop_id())->first();
            $shop_description = ShopOption::where('key', 'shop_description')->where('shop_id', current_shop_id())->first();
            $store_email = ShopOption::where('key', 'store_email')->where('shop_id', current_shop_id())->first();
            $store_mobile_number = ShopOption::where('key', 'store_mobile_number')->where('shop_id', current_shop_id())->first();
            $order_prefix = ShopOption::where('key', 'order_prefix')->where('shop_id', current_shop_id())->first();
            $currency = ShopOption::where('key', 'currency')->where('shop_id', current_shop_id())->first();
            $bank_details = ShopOption::where('key', 'bank_details')->where('shop_id', current_shop_id())->first();
            $location = ShopOption::where('key', 'location')->where('shop_id', current_shop_id())->first();
            $theme_color = ShopOption::where('key', 'theme_color')->where('shop_id', current_shop_id())->first();
            $currency = get_currency_info($currency->value ?? '');
            $bank_details = json_decode($bank_details->value ?? '');
            $location = json_decode($location->value ?? '');
            $tax = ShopOption::where('shop_id', current_shop_id())->where('key', 'tax')->first();
            $gst = ShopOption::where('shop_id', current_shop_id())->where('key', 'gst')->first();
            $pan = ShopOption::where('shop_id', current_shop_id())->where('key', 'pan')->first();
            $local = ShopOption::where('shop_id', current_shop_id())->where('key', 'local')->first();
            $socials = ShopOption::where('shop_id', current_shop_id())->where('key', 'socials')->first();
            $local = $local->value ?? '';
            $socials = json_decode($socials->value ?? '');
            if (Storage::has(current_shop_id() . '/pwa/manifest.json')) {
                $pwa = json_decode(Storage::get(current_shop_id() . '/pwa/manifest.json'));
            } else {
                $pwa = [];
            }
            $order_receive_method = ShopOption::where('shop_id', current_shop_id())->where('key', 'order_receive_method')->first();
            $order_receive_method = $order_receive_method->value ?? 'email';


            if (Storage::has(current_shop_id() . '/js/additional.js')) {
                $js = Storage::get(current_shop_id() . '/js/additional.js');
            } else {
                $js = '';
            }

            if (Storage::has(current_shop_id() . '/css/additional.css')) {
                $css = Storage::get(current_shop_id() . '/css/additional.css');
            } else {
                $css = '';
            }
            $page_type = "location";
            $plan_info = Shop::with('subscription.plan')->where('id', current_shop_id())->first();
            $currency = ShopOption::where('key', 'currency')->where('shop_id', current_shop_id())->first();
            $currency = get_currency_info($currency->value ?? '');
            $plan_data = json_decode($plan_info->data);
            $plan = $plan_info->subscription->plan->name ?? '';
            $product_limit = $plan_data->product_limit;
            $customer_limit = $plan_data->customer_limit;
            $storage = $plan_data->storage;
            $amount = $plan_info->subscription->plan->price ?? '';
            $duration = $plan_info->subscription->plan->days ?? '';
            $will_expired = $plan_info->will_expire;

            $date = Carbon::parse($will_expired);
            $now = Carbon::now();

            $remaining_days = $date->diffInDays($now);

            $data['plan_name'] = $plan;
            $data['customer_limit'] = $customer_limit;
            $data['product_limit'] = $product_limit;
            $data['storage'] = $storage;
            $data['amount'] = $amount;
            $data['duration'] = $duration;


            if ($will_expired == null) {
                $expire = "Expired";
            } else {
                $expire = Carbon::parse($will_expired)->format('F d, Y');
            }
            $data['will_expired'] = $expire;

            return $this->success(null, trans('Location Updated !!'));
        }

        if ($request->type == 'pwa_settings') {
            $request->validate([
                'pwa_app_title' => 'required|max:20',
                'pwa_app_name' => 'required|max:15',
                'app_lang' => 'required|max:15',
                'pwa_app_background_color' => 'required|max:15',
                'pwa_app_theme_color' => 'required|max:15',
                'app_icon_128x128' => 'max:300|mimes:png',
                'app_icon_144x144' => 'max:300|mimes:png',
                'app_icon_152x152' => 'max:300|mimes:png',
                'app_icon_192x192' => 'max:300|mimes:png',
                'app_icon_512x512' => 'max:500|mimes:png',
                'app_icon_256x256' => 'max:400|mimes:png',
            ]);

            $files = [
                '128x128',
                '144x144',
                '152x152',
                '192x192',
                '512x512',
                '256x256',
            ];

            foreach ($files as $file) {
                if ($request->has('app_icon_' . $file)) {
                    Storage::putFileAs(current_shop_id() . '/icons', $request->file('app_icon_' . $file), $file . '.png');
                }
            }

            $mainfest = '{
            "name": "' . $request->pwa_app_title . '",
            "short_name": "' . $request->pwa_app_name . '",
            "lang": "' . $request->app_lang . '",
            "start_url": "/pwa",
            "display": "standalone",
            "background_color": "' . $request->pwa_app_background_color . '",
            "theme_color": "' . $request->pwa_app_theme_color . '",
            "pwa_status": "' . $request->pwa_app_status . '",
            "icons": [
                {
                "src": "' . Storage::url(current_shop_id() . '/icons/' . '/192x192.png') . '",
                "sizes": "128x128",
                "type": "image/png"
                },
                {
                "src": "' . Storage::url(current_shop_id() . '/icons/' . '/144x144.png') . '",
                "sizes": "144x144",
                "type": "image/png"
                },
                {
                "src": "' . Storage::url(current_shop_id() . '/icons/' . '/152x152.png') . '",
                "sizes": "152x152",
                "type": "image/png"
                },
                {
                "src": "' . Storage::url(current_shop_id() . '/icons/' . '/192x192.png') . '",
                "sizes": "192x192",
                "type": "image/png"
                },
                {
                "src": "' . Storage::url(current_shop_id() . '/icons/' . '/256x256.png') . '",
                "sizes": "256x256",
                "type": "image/png"
                },
                {
                "src": "' . Storage::url(current_shop_id() . '/icons/' . '/512x512.png') . '",
                "sizes": "512x512",
                "type": "image/png"
                }
            ]
            
            }';

            Storage::put(current_shop_id() . '/pwa/manifest.json', $mainfest);
            $langlist = \App\Models\Option::where('key', 'languages')->first();
            $langlist = json_decode($langlist->value ?? '');

            $languages = ShopOption::where('shop_id', current_shop_id())->where('key', 'languages')->first();
            $active_languages = json_decode($languages->value ?? '');
            $my_languages = [];
            foreach ($active_languages ?? [] as $key => $value) {
                array_push($my_languages, $value);
            }

            $def_languages = base_path('resources/lang/langlist.json');
            $def_languages = json_decode(file_get_contents($def_languages), true);

            $shop_name = ShopOption::where('key', 'shop_name')->where('shop_id', current_shop_id())->first();
            $shop_description = ShopOption::where('key', 'shop_description')->where('shop_id', current_shop_id())->first();
            $store_email = ShopOption::where('key', 'store_email')->where('shop_id', current_shop_id())->first();
            $store_mobile_number = ShopOption::where('key', 'store_mobile_number')->where('shop_id', current_shop_id())->first();
            $order_prefix = ShopOption::where('key', 'order_prefix')->where('shop_id', current_shop_id())->first();
            $currency = ShopOption::where('key', 'currency')->where('shop_id', current_shop_id())->first();
            $bank_details = ShopOption::where('key', 'bank_details')->where('shop_id', current_shop_id())->first();
            $location = ShopOption::where('key', 'location')->where('shop_id', current_shop_id())->first();
            $theme_color = ShopOption::where('key', 'theme_color')->where('shop_id', current_shop_id())->first();
            $currency = get_currency_info($currency->value ?? '');
            $bank_details = json_decode($bank_details->value ?? '');
            $location = json_decode($location->value ?? '');
            $tax = ShopOption::where('shop_id', current_shop_id())->where('key', 'tax')->first();
            $gst = ShopOption::where('shop_id', current_shop_id())->where('key', 'gst')->first();
            $pan = ShopOption::where('shop_id', current_shop_id())->where('key', 'pan')->first();
            $local = ShopOption::where('shop_id', current_shop_id())->where('key', 'local')->first();
            $socials = ShopOption::where('shop_id', current_shop_id())->where('key', 'socials')->first();
            $local = $local->value ?? '';
            $socials = json_decode($socials->value ?? '');
            if (Storage::has(current_shop_id() . '/pwa/manifest.json')) {
                $pwa = json_decode(Storage::get(current_shop_id() . '/pwa/manifest.json'));
            } else {
                $pwa = [];
            }
            $order_receive_method = ShopOption::where('shop_id', current_shop_id())->where('key', 'order_receive_method')->first();
            $order_receive_method = $order_receive_method->value ?? 'email';


            if (Storage::has(current_shop_id() . '/js/additional.js')) {
                $js = Storage::get(current_shop_id() . '/js/additional.js');
            } else {
                $js = '';
            }

            if (Storage::has(current_shop_id() . '/css/additional.css')) {
                $css = Storage::get(current_shop_id() . '/css/additional.css');
            } else {
                $css = '';
            }
            $page_type = "pwa";
            $plan_info = Shop::with('subscription.plan')->where('id', current_shop_id())->first();
            $currency = ShopOption::where('key', 'currency')->where('shop_id', current_shop_id())->first();
            $currency = get_currency_info($currency->value ?? '');
            $plan_data = json_decode($plan_info->data);
            $plan = $plan_info->subscription->plan->name ?? '';
            $product_limit = $plan_data->product_limit;
            $customer_limit = $plan_data->customer_limit;
            $storage = $plan_data->storage;
            $amount = $plan_info->subscription->plan->price ?? '';
            $duration = $plan_info->subscription->plan->days ?? '';
            $will_expired = $plan_info->will_expire;

            $date = Carbon::parse($will_expired);
            $now = Carbon::now();

            $remaining_days = $date->diffInDays($now);

            $data['plan_name'] = $plan;
            $data['customer_limit'] = $customer_limit;
            $data['product_limit'] = $product_limit;
            $data['storage'] = $storage;
            $data['amount'] = $amount;
            $data['duration'] = $duration;


            if ($will_expired == null) {
                $expire = "Expired";
            } else {
                $expire = Carbon::parse($will_expired)->format('F d, Y');
            }
            $data['will_expired'] = $expire;

            return $this->success(compact('data', 'remaining_days', 'currency', 'bank_details', 'def_languages', 'shop_name', 'order_receive_method', 'shop_description', 'store_email', 'store_mobile_number', 'order_prefix', 'currency', 'location', 'theme_color', 'langlist', 'my_languages', 'tax', 'gst', 'pan', 'local', 'socials', 'pwa', 'js', 'css', 'page_type'));
        }
        if ($request->type == 'theme_settings') {
            $request->validate([
                // 'theme_color' => 'required|max:50',
                'logo' => 'max:1000|mimes:png|dimensions:min_width=100,min_height=100',
                'favicon' => 'max:100|mimes:ico',
            ]);

            $theme_color = ShopOption::where('shop_id', current_shop_id())->where('key', 'theme_color')->first();
            if (empty($theme_color)) {
                $theme_color = new ShopOption;
                $theme_color->key = 'theme_color';
            }

            if ($request->has('logo')) {
                Storage::putFileAs(current_shop_id() . '/icons', $request->file('logo'), 'logo.png');
            }

            if ($request->has('favicon')) {
                Storage::putFileAs(current_shop_id() . '/icons', $request->file('favicon'), 'favicon-16x16.ico');
            }


            $theme_color->value = $request->theme_color;
            $theme_color->shop_id = current_shop_id();
            $theme_color->save();


            $social = ShopOption::where('shop_id', current_shop_id())->where('key', 'socials')->first();
            if (empty($social)) {
                $social = new ShopOption;
                $social->key = 'socials';
            }

            $links = [];
            foreach ($request->icon ?? [] as $key => $value) {
                $data['icon'] = $value;
                $data['url'] = $request->url[$key];
                array_push($links, $data);
            }

            $social->value = json_encode($links);
            $social->shop_id = current_shop_id();
            $social->save();


            // Session::flash('success', trans('Theme Settings Updated !!'));
            // return back();

            ##### Current Update File #####
            $langlist = \App\Models\Option::where('key', 'languages')->first();
            $langlist = json_decode($langlist->value ?? '');

            $languages = ShopOption::where('shop_id', current_shop_id())->where('key', 'languages')->first();
            $active_languages = json_decode($languages->value ?? '');
            $my_languages = [];
            foreach ($active_languages ?? [] as $key => $value) {
                array_push($my_languages, $value);
            }

            $def_languages = base_path('resources/lang/langlist.json');
            $def_languages = json_decode(file_get_contents($def_languages), true);

            $shop_name = ShopOption::where('key', 'shop_name')->where('shop_id', current_shop_id())->first();
            $shop_description = ShopOption::where('key', 'shop_description')->where('shop_id', current_shop_id())->first();
            $store_email = ShopOption::where('key', 'store_email')->where('shop_id', current_shop_id())->first();
            $store_mobile_number = ShopOption::where('key', 'store_mobile_number')->where('shop_id', current_shop_id())->first();
            $order_prefix = ShopOption::where('key', 'order_prefix')->where('shop_id', current_shop_id())->first();
            $currency = ShopOption::where('key', 'currency')->where('shop_id', current_shop_id())->first();
            $bank_details = ShopOption::where('key', 'bank_details')->where('shop_id', current_shop_id())->first();
            $location = ShopOption::where('key', 'location')->where('shop_id', current_shop_id())->first();
            $theme_color = ShopOption::where('key', 'theme_color')->where('shop_id', current_shop_id())->first();
            $currency = get_currency_info($currency->value ?? '');
            $bank_details = json_decode($bank_details->value ?? '');
            $location = json_decode($location->value ?? '');
            $tax = ShopOption::where('shop_id', current_shop_id())->where('key', 'tax')->first();
            $gst = ShopOption::where('shop_id', current_shop_id())->where('key', 'gst')->first();
            $pan = ShopOption::where('shop_id', current_shop_id())->where('key', 'pan')->first();
            $local = ShopOption::where('shop_id', current_shop_id())->where('key', 'local')->first();
            $socials = ShopOption::where('shop_id', current_shop_id())->where('key', 'socials')->first();
            $local = $local->value ?? '';
            $socials = json_decode($socials->value ?? '');
            if (Storage::has(current_shop_id() . '/pwa/manifest.json')) {
                $pwa = json_decode(Storage::get(current_shop_id() . '/pwa/manifest.json'));
            } else {
                $pwa = [];
            }
            $order_receive_method = ShopOption::where('shop_id', current_shop_id())->where('key', 'order_receive_method')->first();
            $order_receive_method = $order_receive_method->value ?? 'email';


            if (Storage::has(current_shop_id() . '/js/additional.js')) {
                $js = Storage::get(current_shop_id() . '/js/additional.js');
            } else {
                $js = '';
            }

            if (Storage::has(current_shop_id() . '/css/additional.css')) {
                $css = Storage::get(current_shop_id() . '/css/additional.css');
            } else {
                $css = '';
            }
            $page_type = "logo";
            $plan_info = Shop::with('subscription.plan')->where('id', current_shop_id())->first();
            $currency = ShopOption::where('key', 'currency')->where('shop_id', current_shop_id())->first();
            $currency = get_currency_info($currency->value ?? '');
            $plan_data = json_decode($plan_info->data);
            $plan = $plan_info->subscription->plan->name ?? '';
            $product_limit = $plan_data->product_limit;
            $customer_limit = $plan_data->customer_limit;
            $storage = $plan_data->storage;
            $amount = $plan_info->subscription->plan->price ?? '';
            $duration = $plan_info->subscription->plan->days ?? '';
            $will_expired = $plan_info->will_expire;

            $date = Carbon::parse($will_expired);
            $now = Carbon::now();

            $remaining_days = $date->diffInDays($now);

            $data['plan_name'] = $plan;
            $data['customer_limit'] = $customer_limit;
            $data['product_limit'] = $product_limit;
            $data['storage'] = $storage;
            $data['amount'] = $amount;
            $data['duration'] = $duration;


            if ($will_expired == null) {
                $expire = "Expired";
            } else {
                $expire = Carbon::parse($will_expired)->format('F d, Y');
            }
            $data['will_expired'] = $expire;

            $this->success(
                compact('data', 'remaining_days', 'currency', 'bank_details', 'def_languages', 'shop_name', 'order_receive_method', 'shop_description', 'store_email', 'store_mobile_number', 'order_prefix', 'currency', 'location', 'theme_color', 'langlist', 'my_languages', 'tax', 'gst', 'pan', 'local', 'socials', 'pwa', 'js', 'css', 'page_type'),
                trans('Theme Settings Updated !!')
            );

            ##### Current Update End #####
        }

        if ($request->type == 'css') {
            if (user_plan_access('custom_css') == true) {
                Storage::put(current_shop_id() . '/css/additional.css', $request->css);
                return $this->success(null, trans('Updated success'));
            }
        }

        if ($request->type == 'js') {
            if (user_plan_access('custom_js') == true) {
                Storage::put(current_shop_id() . '/js/additional.css', $request->js);
                return $this->success(null, trans('Updated success'));
            }
        }

        abort(404);
    }

    /**
     * Display the specified resource.
     *
     * @param  string  $slug
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        $user = Auth::user();
        if ($slug == 'shop') {

            $langlist = \App\Models\Option::where('key', 'languages')->first();
            $langlist = json_decode($langlist->value ?? '');

            $languages = ShopOption::where('shop_id', current_shop_id())->where('key', 'languages')->first();
            $active_languages = json_decode($languages->value ?? '');
            $my_languages = [];
            foreach ($active_languages ?? [] as $key => $value) {
                array_push($my_languages, $value);
            }

            $def_languages = base_path('resources/lang/langlist.json');
            $def_languages = json_decode(file_get_contents($def_languages), true);

            $shop_name = ShopOption::where('key', 'shop_name')->where('shop_id', current_shop_id())->first();
            $shop_description = ShopOption::where('key', 'shop_description')->where('shop_id', current_shop_id())->first();
            $store_email = ShopOption::where('key', 'store_email')->where('shop_id', current_shop_id())->first();
            $store_mobile_number = ShopOption::where('key', 'store_mobile_number')->where('shop_id', current_shop_id())->first();
            $order_prefix = ShopOption::where('key', 'order_prefix')->where('shop_id', current_shop_id())->first();
            $currency = ShopOption::where('key', 'currency')->where('shop_id', current_shop_id())->first();
            $bank_details = ShopOption::where('key', 'bank_details')->where('shop_id', current_shop_id())->first();
            $location = ShopOption::where('key', 'location')->where('shop_id', current_shop_id())->first();
            $theme_color = ShopOption::where('key', 'theme_color')->where('shop_id', current_shop_id())->first();
            $currency = get_currency_info($currency->value ?? '');
            $bank_details = json_decode($bank_details->value ?? '');
            $location = json_decode($location->value ?? '');
            $tax = ShopOption::where('shop_id', current_shop_id())->where('key', 'tax')->first();
            $gst = ShopOption::where('shop_id', current_shop_id())->where('key', 'gst')->first();
            $pan = ShopOption::where('shop_id', current_shop_id())->where('key', 'pan')->first();
            $aadhar = ShopOption::where('shop_id', current_shop_id())->where('key', 'aadhar')->first();
            $local = ShopOption::where('shop_id', current_shop_id())->where('key', 'local')->first();
            $socials = ShopOption::where('shop_id', current_shop_id())->where('key', 'socials')->first();
            $local = $local->value ?? '';
            $socials = json_decode($socials->value ?? '');
            if (Storage::has(current_shop_id() . '/pwa/manifest.json')) {
                $pwa = json_decode(Storage::get(current_shop_id() . '/pwa/manifest.json'));
            } else {
                $pwa = [];
            }
            $order_receive_method = ShopOption::where('shop_id', current_shop_id())->where('key', 'order_receive_method')->first();
            $order_receive_method = $order_receive_method->value ?? 'email';


            if (Storage::has(current_shop_id() . '/js/additional.js')) {
                $js = Storage::get(current_shop_id() . '/js/additional.js');
            } else {
                $js = '';
            }

            if (Storage::has(current_shop_id() . '/css/additional.css')) {
                $css = Storage::get(current_shop_id() . '/css/additional.css');
            } else {
                $css = '';
            }

            $page_type = "general";
            $plan_info = Shop::with('subscription.plan')->where('id', current_shop_id())->first();
            $currency = ShopOption::where('key', 'currency')->where('shop_id', current_shop_id())->first();
            $currency = get_currency_info($currency->value ?? '');
            $plan_data = json_decode($plan_info->data);
            $plan_name = $plan_info->subscription->plan->name ?? '';
            $product_limit = $plan_data->product_limit;
            $customer_limit = $plan_data->customer_limit;
            $storage = $plan_data->storage;
            $amount = $plan_info->subscription->plan->price ?? '';
            $duration = $plan_info->subscription->plan->days ?? '';
            $will_expired = $plan_info->will_expire;

            $date = Carbon::parse($will_expired);
            $now = Carbon::now();

            $remaining_days = $date->diffInDays($now);

            $plan['plan_name'] = $plan_name;
            $plan['customer_limit'] = $customer_limit;
            $plan['product_limit'] = $product_limit;
            $plan['storage'] = $storage;
            $plan['amount'] = $amount;
            $plan['duration'] = $duration;


            if ($will_expired == null) {
                $expire = "Expired";
            } else {
                $expire = Carbon::parse($will_expired)->format('F d, Y');
            }
            $plan['will_expired'] = $expire;

            return $this->success(compact('plan', 'remaining_days', 'currency', 'bank_details', 'def_languages', 'shop_name', 'order_receive_method', 'shop_description', 'store_email', 'store_mobile_number', 'order_prefix', 'currency', 'location', 'theme_color', 'langlist', 'my_languages', 'tax', 'gst', 'pan', 'aadhar', 'local', 'socials', 'pwa', 'js', 'css', 'page_type'));
        }

        //dashboad setup other tab
        if ($slug == 'logo') {

            $langlist = \App\Models\Option::where('key', 'languages')->first();
            $langlist = json_decode($langlist->value ?? '');

            $languages = ShopOption::where('shop_id', current_shop_id())->where('key', 'languages')->first();
            $active_languages = json_decode($languages->value ?? '');
            $my_languages = [];
            foreach ($active_languages ?? [] as $key => $value) {
                array_push($my_languages, $value);
            }

            $def_languages = base_path('resources/lang/langlist.json');
            $def_languages = json_decode(file_get_contents($def_languages), true);

            $shop_name = ShopOption::where('key', 'shop_name')->where('shop_id', current_shop_id())->first();
            $shop_description = ShopOption::where('key', 'shop_description')->where('shop_id', current_shop_id())->first();
            $store_email = ShopOption::where('key', 'store_email')->where('shop_id', current_shop_id())->first();
            $order_prefix = ShopOption::where('key', 'order_prefix')->where('shop_id', current_shop_id())->first();
            $currency = ShopOption::where('key', 'currency')->where('shop_id', current_shop_id())->first();
            $location = ShopOption::where('key', 'location')->where('shop_id', current_shop_id())->first();
            $theme_color = ShopOption::where('key', 'theme_color')->where('shop_id', current_shop_id())->first();
            $currency = get_currency_info($currency->value ?? '');
            $location = json_decode($location->value ?? '');
            $tax = ShopOption::where('shop_id', current_shop_id())->where('key', 'tax')->first();
            $gst = ShopOption::where('shop_id', current_shop_id())->where('key', 'gst')->first();
            $local = ShopOption::where('shop_id', current_shop_id())->where('key', 'local')->first();
            $socials = ShopOption::where('shop_id', current_shop_id())->where('key', 'socials')->first();
            $local = $local->value ?? '';
            $socials = json_decode($socials->value ?? '');
            if (Storage::has(current_shop_id() . '/pwa/manifest.json')) {
                $pwa = Storage::get(current_shop_id() . '/pwa/manifest.json');
                $pwa = json_decode($pwa);
            } else {
                $pwa = [];
            }

            $order_receive_method = ShopOption::where('shop_id', current_shop_id())->where('key', 'order_receive_method')->first();
            $order_receive_method = $order_receive_method->value ?? 'email';


            if (Storage::has(current_shop_id() . '/js/additional.js')) {
                $js = Storage::get(current_shop_id() . '/js/additional.js');
            } else {
                $js = '';
            }

            if (Storage::has(current_shop_id() . '/css/additional.css')) {
                $css = Storage::get(current_shop_id() . '/css/additional.css');
            } else {
                $css = '';
            }
            $page_type = "logo";
            $plan_info = Shop::with('subscription.plan')->where('id', current_shop_id())->first();
            $currency = ShopOption::where('key', 'currency')->where('shop_id', current_shop_id())->first();
            $currency = get_currency_info($currency->value ?? '');
            $plan_data = json_decode($plan_info->data);
            $plan = $plan_info->subscription->plan->name ?? '';
            $product_limit = $plan_data->product_limit;
            $customer_limit = $plan_data->customer_limit;
            $storage = $plan_data->storage;
            $amount = $plan_info->subscription->plan->price ?? '';
            $duration = $plan_info->subscription->plan->days ?? '';
            $will_expired = $plan_info->will_expire;

            $date = Carbon::parse($will_expired);
            $now = Carbon::now();

            $remaining_days = $date->diffInDays($now);

            $data['plan_name'] = $plan;
            $data['customer_limit'] = $customer_limit;
            $data['product_limit'] = $product_limit;
            $data['storage'] = $storage;
            $data['amount'] = $amount;
            $data['duration'] = $duration;


            if ($will_expired == null) {
                $expire = "Expired";
            } else {
                $expire = Carbon::parse($will_expired)->format('F d, Y');
            }
            $data['will_expired'] = $expire;

            return $this->success(compact('def_languages', 'shop_name', 'order_receive_method', 'shop_description', 'store_email', 'order_prefix', 'currency', 'location', 'theme_color', 'langlist', 'my_languages', 'tax', 'gst', 'local', 'socials', 'pwa', 'js', 'css', 'page_type', 'data', 'remaining_days'));
        }

        if ($slug == 'payment') {
            $posts = PaymentMethod::activeGateways()->orderBy('name', 'ASC')->get();
            return $this->success($posts);
        }
        if ($slug == 'plan') {
            if (current_shop_type() == 'seller') {
                $posts = Plan::where('status', 1)->where('is_default', 0)->where('status', 1)->where('featured', 1)->where('is_trial', 0)->where('price', '>', 0)
                    ->where(
                        function ($query) {
                            return $query
                                ->where('shop_type', 'seller')
                                ->orWhere('shop_type', NULL);
                        }
                    )->latest()->get();
            } else 	if (current_shop_type() == 'supplier') {
                $posts = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('featured', 1)->where('price', '>', 0)->where('shop_type', 'supplier')->latest()->get();
            } else if (current_shop_type() == 'reseller') {
                $posts = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('featured', 1)->where('price', '>', 0)->where('shop_type', 'reseller')->latest()->get();
            }

            return $this->success($posts);
        }
        if ($slug == 'subscriptions') {
            $subscriptions = Subscription::with('plan', 'PaymentMethod')->where('shop_id', current_shop_id())->latest()->get();
            return $this->success($subscriptions);
        }
        abort(404);
    }

    public function timezones()
    {
        return response()->json(DateTimeZone::listIdentifiers());
    }
}
