<?php

namespace App\Http\Controllers;

use Carbon\Carbon;
use App\Models\Plan;
use App\Models\Option;
use App\Models\Domain;
use App\Mail\OrderMail;
use App\Models\Category;
// use App\Rules\EmailSpam;
use App\Models\User;
use App\Models\Shop;
use App\Models\ShopOption;
use App\Models\Subscription;
use App\Models\Partner;
use App\Mail\AdminContactMail;
use App\Mail\StoreOtpMail;
use App\Mail\StoreTestMail;
use App\Helper\Subscription\Paypal;
use App\Helper\Subscription\Stripe;
use App\Helper\Subscription\Mollie;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use App\Helper\Subscription\Paystack;
use App\Helper\Subscription\Toyyibpay;
use App\Helper\Subscription\Instamojo;
use Illuminate\Support\Facades\Session;
use App\Models\ShopUser;
use Artesaos\SEOTools\Facades\JsonLdMulti;
use Artesaos\SEOTools\Facades\SEOTools;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\OpenGraph;
use Artesaos\SEOTools\Facades\JsonLd;
use App\Models\SubscriptionMeta;
use App\Models\Template;
use App\Rules\IsValidDomain;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Spatie\Permission\Models\Role;
use App\Mail\NewStoreWelcomeMail;
use App\Models\PaymentMethod;
use App\Models\Product;

class FrontendController extends Controller
{
  public function welcome(Request $request)
  {

    // $url = $request->getHost();
    // $url = str_replace('www.', '', $url);
    // if ($url == env('APP_PROTOCOLESS_URL') || $url == 'localhost') {


      $seo = Option::where('key', 'seo')->first();
      $seo = json_decode($seo->value);

      JsonLdMulti::setTitle($seo->title ?? env('APP_NAME'));
      JsonLdMulti::setDescription($seo->description ?? null);
      JsonLdMulti::addImage(admin_logo_url());

      SEOMeta::setTitle($seo->title ?? env('APP_NAME'));
      SEOMeta::setDescription($seo->description ?? null);
      SEOMeta::addKeyword($seo->tags ?? null);

      SEOTools::setTitle($seo->title ?? env('APP_NAME'));
      SEOTools::setDescription($seo->description ?? null);
      SEOTools::setCanonical($seo->canonical ?? url('/'));
      SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
      SEOTools::opengraph()->addProperty('image', admin_logo_url());
      SEOTools::twitter()->setTitle($seo->title ?? env('APP_NAME'));
      SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
      SEOTools::jsonLd()->addImage(admin_logo_url());


      $latest_gallery = Category::where('type', 'gallery')->with('preview')->where('is_admin', 1)->latest()->take(15)->get();
      $store_features = Category::where('type', 'store_features')->with('preview', 'excerpt')->where('is_admin', 1)->latest()->take(10)->get();
      $features = Category::where('type', 'features')->with('preview', 'excerpt')->where('is_admin', 1)->latest()->take(6)->get();

      $testimonials = Category::where('type', 'testimonial')->with(['excerpt', 'preview'])->where('is_admin', 1)->latest()->get();

      $brands = Category::where('type', 'brand')->with('preview')->where('is_admin', 1)->latest()->get();

      $plans = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->latest()->take(3)->get();
      $trail_plans = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 1)->latest()->take(1)->get();
      $header = Option::where('key', 'header')->first();
      $header = json_decode($header->value ?? '');

      $about_1 = Option::where('key', 'about_1')->first();
      $about_1 = json_decode($about_1->value ?? '');

      $about_2 = Option::where('key', 'about_2')->first();
      $about_2 = json_decode($about_2->value ?? '');

      $about_3 = Option::where('key', 'about_3')->first();
      $about_3 = json_decode($about_3->value ?? '');

      $about_4 = Option::where('key', 'about_4')->first();
      $about_4 = json_decode($about_4->value ?? '');

      $ecom_features = Option::where('key', 'ecom_features')->first();
      $ecom_features = json_decode($ecom_features->value ?? '');

      $market_features = Option::where('key', 'market_features')->first();
      $market_features = json_decode($market_features->value ?? '');
      

      $counter_area = Option::where('key', 'counter_area')->first();
      $counter_area = json_decode($counter_area->value ?? '');

      $templates = Template::withCount('installed')->latest()->limit(6)->get();

      return view('welcome', compact('latest_gallery', 'plans', 'trail_plans', 'store_features', 'features', 'header', 'about_1', 'about_3', 'about_2','about_4', 'testimonials', 'brands', 'ecom_features','market_features', 'counter_area', 'templates'));
    // }
    // return redirect('/check');
  }

  public function check(Request $request)
  {
    $url = $request->getHost();
    $url = str_replace('www.', '', $url);
    if ($url == env('APP_PROTOCOLESS_URL') || $url == 'localhost') {
      return redirect(env('APP_URL'));
    }

    \Helper::domain($url, url('/'));

    return redirect(url('/'));
  }

  public function verify_store_view(Request $request)
  {
    return view('auth.verify-store');
  }

  public function verify_store_name(Request $request)
  {

    if (env('NOCAPTCHA_SITEKEY') != null) {
      $messages = [
        'g-recaptcha-response.required' => 'You must check the reCAPTCHA.',
        'g-recaptcha-response.captcha' => 'Captcha error! try again later or contact site admin.',
      ];

      $request->validate([
        'g-recaptcha-response' => 'required|captcha'
      ], $messages);
    }  

    $domain = preg_replace('/[^a-zA-Z0-9\']/', '', $request->shop_name ?? "");
    $domain = str_replace("'", '', $domain);

    $shop = Shop::where('name', $domain)->first();

    if(isset($shop)){
      $domain = env('APP_PROTOCOL') . Str::lower($shop->sub_domain).'.'.env('APP_PROTOCOLESS_URL') . '/login';
      return response()->json(['message' => trans('Shop name is available.'), 'domain' => $domain], 200);
    }
    else {
      return response()->json(['message' => trans('Shop name is not available.')], 422);
    }
  }

  public function partner()
  {
    $testimonials = Category::where('type', 'partner_testimonial')->with(['excerpt', 'preview'])->where('is_admin', 1)->latest()->get();
    return view('partner', compact('testimonials'));
  }

  public function partner_faq()
  {
    return view('partner.faq.page');
  }
  public function page($slug)
  {
    $info = Product::where('slug', $slug)->with('content', 'excerpt')->where('is_admin', 1)->first();
    if (empty($info)) {
      abort(404);
    }
    JsonLdMulti::setTitle($info->title);
    JsonLdMulti::setDescription($info->excerpt->value ?? null);
    JsonLdMulti::addImage(admin_logo_url());

    SEOMeta::setTitle($info->title);
    SEOMeta::setDescription($info->excerpt->value ?? null);


    SEOTools::setTitle($info->title);
    SEOTools::setDescription($info->excerpt->value ?? null);


    SEOTools::opengraph()->addProperty('image', admin_logo_url());
    SEOTools::twitter()->setTitle($info->title);

    SEOTools::jsonLd()->addImage(admin_logo_url());
    return view('page', compact('info'));
  }

  public function about()
  {
    JsonLdMulti::setTitle("About - Singlebag");
    JsonLdMulti::setDescription("About - Singlebag" ?? null);
    JsonLdMulti::addImage(admin_logo_url());

    SEOMeta::setTitle("About - Singlebag");
    SEOMeta::setDescription("About - Singlebag" ?? null);


    SEOTools::setTitle("About - Singlebag");
    SEOTools::setDescription("About - Singlebag" ?? null);


    SEOTools::opengraph()->addProperty('image', admin_logo_url());
    SEOTools::twitter()->setTitle("About - Singlebag");

    SEOTools::jsonLd()->addImage(admin_logo_url());
    $company_info = Option::where('key', 'company_info')->first();
    $info = json_decode($company_info->value);
    return view('about', compact('info'));
  }

  public function custom_page(Request $request)
  {
    $title = $request->title ?? "Store Not Available";
    JsonLdMulti::setTitle($title  . " - Singlebag");
    JsonLdMulti::setDescription($title  . " - Singlebag" ?? null);
    JsonLdMulti::addImage(admin_logo_url());

    SEOMeta::setTitle($title  . " - Singlebag");
    SEOMeta::setDescription($title  . " - Singlebag" ?? null);


    SEOTools::setTitle($title  . " - Singlebag");
    SEOTools::setDescription($title  . " - Singlebag" ?? null);


    SEOTools::opengraph()->addProperty('image', admin_logo_url());
    SEOTools::twitter()->setTitle($title  . " - Singlebag");

    SEOTools::jsonLd()->addImage(admin_logo_url());
    $company_info = Option::where('key', 'company_info')->first();
    $info = json_decode($company_info->value);
    return view('store-unavailable', compact('info'));
  }


  public function service()
  {
    $seo = Option::where('key', 'seo')->first();
    $seo = json_decode($seo->value);
    JsonLdMulti::setTitle('Our Service');
    JsonLdMulti::setDescription($seo->description ?? null);
    JsonLdMulti::addImage(admin_logo_url());

    SEOMeta::setTitle('Our Service');
    SEOMeta::setDescription($seo->description ?? null);


    SEOTools::setTitle('Our Service');
    SEOTools::setDescription($seo->description ?? null);


    SEOTools::opengraph()->addProperty('image', admin_logo_url());
    SEOTools::twitter()->setTitle('Our Service');

    SEOTools::jsonLd()->addImage(admin_logo_url());
    $features = Category::where('type', 'features')->with('preview', 'excerpt')->where('is_admin', 1)->latest()->get();
    return view('service', compact('features'));
  }

  public function pricing()
  {
    $seo = Option::where('key', 'seo')->first();
    $seo = json_decode($seo->value);
    JsonLdMulti::setTitle('Pricing');
    JsonLdMulti::setDescription($seo->description ?? null);
    JsonLdMulti::addImage(admin_logo_url());

    SEOMeta::setTitle('Pricing');
    SEOMeta::setDescription($seo->description ?? null);


    SEOTools::setTitle('Pricing');
    SEOTools::setDescription($seo->description ?? null);


    SEOTools::opengraph()->addProperty('image', admin_logo_url());
    SEOTools::twitter()->setTitle('Pricing');

    SEOTools::jsonLd()->addImage(admin_logo_url());

    $plans = Plan::where('status', 1)->where('is_default', 0)->get();

    return view('pricing', compact('plans'));
  }



  public function contact()
  {
    $seo = Option::where('key', 'seo')->first();
    $seo = json_decode($seo->value);

    JsonLdMulti::setTitle('Contact Us');
    JsonLdMulti::setDescription($seo->description ?? null);
    JsonLdMulti::addImage(admin_logo_url());

    SEOMeta::setTitle('Contact Us');
    SEOMeta::setDescription($seo->description ?? null);
    SEOMeta::addKeyword($seo->tags ?? null);

    SEOTools::setTitle('Contact Us');
    SEOTools::setDescription($seo->description ?? null);
    SEOTools::setCanonical($seo->canonical ?? url('/'));
    SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
    SEOTools::opengraph()->addProperty('image', admin_logo_url());
    SEOTools::twitter()->setTitle('Contact Us');
    SEOTools::twitter()->setSite('Contact Us');
    SEOTools::jsonLd()->addImage(admin_logo_url());
    return view('contact');
  }

  public function send_mail(Request $request)
  {
    if (env('NOCAPTCHA_SITEKEY') != null) {
      $messages = [
        'g-recaptcha-response.required' => trans('You must check the reCAPTCHA.'),
        'g-recaptcha-response.captcha' => trans('Captcha error! try again later or contact site admin.'),
      ];

      $request->validate([
        'g-recaptcha-response' => 'required|captcha'
      ], $messages);
    }
    $validatedData = $request->validate([
      'name' => 'required|string|max:100',
      'email' => 'required|email_address|max:100',
      'message' => 'required|max:300',
    ]);
    $data = [
      'name' => $request->name,
      'email' => $request->email,
      'message' => $request->message
    ];
    Mail::to(env('MAIL_TO'))->send(new AdminContactMail($data));

    return response()->json(trans('Your message submitted successfully !!'));
  }


  public function signup()
  {
    $def_languages = base_path('resources/lang/langlist.json');
    $def_languages = json_decode(file_get_contents($def_languages), true);
    $shop_categories = Category::where('status', '1')->where('type', 'shop_category')->get();
    return view('auth.register',compact('def_languages', 'shop_categories'));
  }

  public function otp()
  {
    return view("auth.verify-otp");
  }

  public function resend_error()
  {
    return view("auth.error");
  }

  public function email_not_verified()
  {
    return view("auth.email_not_verified");
  }

  public function account_not_active()
  {
    return view("auth.account_not_active");
  }

    public function otp_verify(Request $request)
  {

    $otp_verification = $request->otp_verify;
    $user = Auth::user();
    if (!empty($otp_verification)) {
      $user_email_verify = User::where('id', $user->id)->first();
      $otpdata = DB::table('password_resets')
        ->where('email', $user->email)->first();

      if ($otp_verification ==  $otpdata->token) {
        $user_email_verify->email_verified_at = Carbon::now();
        $user_email_verify->save();
        $shop = Shop::where('user_id', $user->id)->first();
        $dom = Domain::where('shop_id', $shop->id)->first();
        DB::table('password_resets')->where('email', $user->email)->delete();
        DB::commit();
        $dta['msg'] = trans('Email Verified Successfully');
        if ($dom->status == 1) {
          $url= $dom->full_domain . '/login';
          // $dta['redirect'] = true;
        }
        return redirect($url);
      } else {
        // $dta['msg'] = trans('OTP Mismatch');
        Session::flash('error', 'OTP Mismatch');
        return back();
      }
    }

    return response()->json($dta);
  }
  public function resend_store_otp(Request $request, $id)
  {
    DB::beginTransaction();
    try {

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

      dispatch(new \App\Jobs\NewStoreOtpMailJob($user, $data));

      DB::commit();
        Auth::loginUsingId($id);
      $dta['redirect'] = true;
      return redirect()->intended('/otp');
    } catch (\Exception $e) {
      DB::rollback();
      // return response()->view('errors.' . '500', [], 500);
      return redirect()->intended('/otp_error_page');
    }

    // return response()->json($data);
  }
  
  public function store_test_mail()
  {
    try {
      Mail::to(env('MAIL_TO'))->send(new StoreTestMail('test'));
    } catch (\Exception $e) {
      return response()->json('Error Occured Mail Not Sent');
      // Session::flash('error', 'Error Occured Mail Not Sent');
      // return back();
    }
    // Session::flash('success', 'Mail Sent Successfully');
    return response()->json('Mail Sent Successfully');
    // return back();
  }

  public function translate(Request $request)
  {
    Session::put('locale', $request->local);
    app()->setlocale($request->local);
    return redirect('/');
  }

  public function shopdescription(Request $request) {

    $validatedData = $request->validate([
      'shop_description' => 'required',
    ]);

    $shop_description = ShopOption::where('shop_id', $request->shop_id)->where('key', 'shop_description')->first();
    if (empty($shop_description)) {
        $shop_description = new ShopOption;
        $shop_description->key = 'shop_description';
    }
    $shop_description->value = $request->shop_description;
    $shop_description->shop_id = $request->shop_id;
    $shop_description->save();
    return response()->json(['msg'=>'Successfully updated store description.']); 

  }

  public function shopcategory(Request $request) {

    $validatedData = $request->validate([
      'shop_category_id' => 'required',
    ],
    [
      'shop_category_id.required' => 'Shop Category is required'
    ]);

    $shop_category = ShopOption::where('shop_id', $request->shop_id)->where('key', 'shop_category')->first();
    if (empty($shop_category)) {
        $shop_category = new ShopOption;
        $shop_category->key = 'shop_category';
    }
    $shop_category->value = $request->shop_category_id;
    $shop_category->shop_id = $request->shop_id;
    $shop_category->save();
    return response()->json(['msg'=>'Successfully updated store category.']); 

  }

  public function shopaddress(Request $request) {
    $validatedData = $request->validate([
      'address' => 'required',
      'city' => 'required',
      'state' => 'required',
      'zip_code' => 'required',
    ]);
    $location = ShopOption::where('shop_id', $request->shop_id)->where('key', 'location')->first();
    if (empty($location)) {
        $location = new ShopOption;
        $location->key = 'location';
    }
    $data['company_name'] = '';
    $data['address'] = $request->address;
    $data['city'] = $request->city;
    $data['state'] = $request->state;
    $data['zip_code'] = $request->zip_code;
    $data['email'] = '';
    $data['phone'] = '';
    $data['invoice_description'] = '';

    $location->value = json_encode($data);
    $location->shop_id = $request->shop_id;
    $location->save();
    return response()->json(['msg'=>'Successfully updated store location.']); 

  }

  public function shopbankdetails(Request $request) {
    $request->validate([
      'account_holder_name' => 'required',
      'ifsc_code' => 'required',
      'account_no' => 'required',
    ]);
    $bank_details = ShopOption::where('shop_id',$request->shop_id)->where('key', 'bank_details')->first();
    if (empty($bank_details)) {
        $bank_details = new ShopOption;
        $bank_details->key = 'bank_details';
    }
    $accountInfo['account_holder_name'] = $request->account_holder_name;
    $accountInfo['ifsc_code'] = $request->ifsc_code;
    $accountInfo['account_no'] = $request->account_no;

    $bank_details->value = json_encode($accountInfo);
    $bank_details->shop_id = $request->shop_id;
    $bank_details->save(); 
    return response()->json(['msg'=>'Successfully updated store bank details.']);

  }

  public function shoplanguage(Request $request) {
    $validatedData = $request->validate([
      'local' => 'required',
    ]);
    $local = ShopOption::where('shop_id', $request->shop_id)->where('key', 'local')->first();
    if (empty($local)) {
        $local = new ShopOption;
        $local->key = 'local';
    }
    if($request->local != ''){
      $local->value = $request->local;
    }
    else {
      $local->value = 'en';
    }
    $local->shop_id = $request->shop_id;
    $local->save();
  
    // $dom = Domain::where('shop_id', $request->shop_id)->first();

    // if ($dom->status == 1) {
    //   $dta['domain'] = $dom->full_domain . '/login';
    //   $dta['redirect'] = true;
    // } else {
    //   Auth::loginUsingId($user->id);
    //   $dta['redirect'] = true;
    //   $dta['domain'] = route('merchant.dashboard');
    // }
    $shop = Shop::where('id', $request->shop_id)->first();
    Auth::loginUsingId($shop->user_id);
    $dta['redirect'] = true;
    $dta['domain'] = route('otp');

      $user = User::where('id', $shop->user_id)->first();
      $userInfo['otp'] = substr(str_shuffle("0123456789"), 0, 4);

      $data = [
        'name' => $user->first_name,
        'email' => $user->email,
        'otp' => $userInfo['otp']
      ];

      DB::table('password_resets')->insert([
        'email' => $user->email,
        'token' => $userInfo['otp'],
        'created_at' => Carbon::now()
      ]);

      Mail::to($user->email)->send(new StoreOtpMail($data));

    $dta['msg'] = trans('Successfully Registered');

    return response()->json($dta);

}

  public function register(Request $request)
  {
    $iPhone  = stripos($_SERVER['HTTP_USER_AGENT'],"iPhone");
    $iPad    = stripos($_SERVER['HTTP_USER_AGENT'],"iPad");
    if (env('NOCAPTCHA_SITEKEY') != null && !$iPhone && !$iPad ) {
      $messages = [
        'g-recaptcha-response.required' => trans('You must check the reCAPTCHA.'),
        'g-recaptcha-response.captcha' => trans('Captcha error! try again later or contact site admin.'),
      ];

      $request->validate([
        'g-recaptcha-response' => 'required|captcha'
      ], $messages);
    }

    $request->validate([
      'email' => 'bail|required|unique:users|email_address|max:255',
      'first_name' => 'regex:/^(?!\s)[a-zA-Z_\s-]*$/|required|string|max:255',
      'last_name' => 'regex:/^(?!\s)[a-zA-Z_\s-]*$/|required|string|max:255',
      'mobile_number' => 'required|integer|digits:10',
      'password' => 'required|min:8|confirmed|string|without_spaces',
      'shop_name' => [new IsValidDomain()],
      'shop_name' => 'required|min:4|max:15|string|without_spaces|regex:/^[A-Za-z0-9 ]+$/',
      'shop_type' => 'required',
      'agree' => 'required',
      // 'email' => [
      //     new EmailSpam()
      // ],
    ],
    [
        'shop_name.without_spaces' => 'Space is not allowed in shop name field',
        'first_name.regex' => 'First name field cannot contain spaces',
        'last_name.regex' => 'Last name field cannot contain spaces',
        'mobile_number.integer' => 'Invalid phone number',
        'mobile_number.digits' => 'Invalid phone number'
    ]);
    if($request->shop_type == 'reseller'){
      $info = Plan::where('status', 1)->where('is_trial', 1)->where('shop_type', 'Reseller')->first();
    } else if($request->shop_type == 'supplier'){
      $info = Plan::where('status', 1)->where('is_trial', 1)->where('shop_type', 'Supplier')->first();
    } else {
      $info = Plan::where('status', 1)->where('is_trial', 1)->where('shop_type', 'Seller')->first();
    }
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
      $shop->email = $request->email;
      $shop->sub_domain = Str::lower($domain);
      $shop->shop_type = $request->shop_type;
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


      DB::commit();

      $shop = $shop->load(['user', 'domain']);

      if (env('QUEUE_MAIL') == 'on') {
          dispatch(new \App\Jobs\StoreWelcomeMailJob($shop));
      } else {
          // Mail::to($shop->email)->send(new StoreWelcomeMail($shop));
          Mail::to($shop->email)->send(new NewStoreWelcomeMail($shop));
      }
    } catch (\Exception $e) {
      Log::debug($e->getMessage());
      DB::rollback();
    }

    // if ($dom->status == 1) {
    //   $dta['domain'] = $dom->full_domain . '/login';
    //   $dta['redirect'] = true;
    // } else {
    //   Auth::loginUsingId($user->id);
    //   $dta['redirect'] = true;
    //   $dta['domain'] = route('merchant.dashboard');
    // }
    
    $dta['msg'] = trans('Successfully Registered');
    $dta['shop_id'] = $shop_id;

    return response()->json($dta);
  }

  public function check_store_availability(Request $request)
  {
    $domain = preg_replace('/[^a-zA-Z0-9\']/', '', $request->store_name ?? "");
    $domain = str_replace("'", '', $domain);
    $message = "";
    if(strlen($domain) > 15 || strlen($domain) < 4){
        $message = trans('Invalid shop name (Only allow between 4 to 15 alphanumeric characters).');
        return response()->json(['message' => $message], 422);
    }

    $shop = Shop::where('name', $domain)->first();
    if(!isset($shop)){
      return response()->json(['message' => trans('Shop name is available.'), 'domain' => $domain], 200);
    }else{
      $message = 'Shop name is not available.';
    }

    
    return response()->json(['message' => $message], 422);
  }


  public function dashboard()
  {
    return view('seller.dashboard');
  }

  public function settings()
  {
    $current_id = Auth::id();
    $info = Partner::where('user_id', $current_id)->first();
    return view('settings',compact('info'));
  }



  public function make_payment($id)
  {
    if (Session::has('success')) {
      Session::flash('success', trans('Thank You For Subscribe After Review The Order You Will Get A Notification Mail From Admin'));
      return redirect('merchant/plan');
    }
    $info = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('price', '>', 0)->findorFail($id);

    $gateways = PaymentMethod::where('status', 1)->get()->with('gateway')->where('slug', '!=', 'cod')->get();

    $tax = Option::where('key', 'tax')->first();
    $tax = ($info->price * 0.01) * $tax->value;

    $currency = Option::where('key', 'currency')->first();
    $price = $currency->code . ' ' . number_format($info->price + $tax, 2);
    $main_price = $info->price;
    return view('seller.plan.payment', compact('info', 'gateways', 'price', 'tax', 'main_price'));
  }

  public function make_charge(Request $request, $id)
  {

    $info = Plan::where('status', 1)->where('is_default', 0)->where('is_trial', 0)->where('price', '>', 0)->findorFail($id);

    $gateway = PaymentMethod::where('status', 1)->where('slug', '!=', 'cod')->where('slug', $request->payment_method)->first();

    $currency = Option::where('key', 'currency')->first();


    $tax = Option::where('key', 'tax')->first();
    $tax = ($info->price * 0.01) * $tax->value;

    $total = str_replace(',', '', number_format($info->price + $tax, 2));

    $data['ref_id'] = $id;
    $data['payment_method'] = $request->payment_method;
    $data['amount'] = $total;
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
      return redirect('/merchant/payment-with/razorpay');
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
        $expiry_date = \Carbon\Carbon::now()->addDays($exp_days)->format('Y-m-d');

        $max_order = Subscription::max('id');
        $order_prefix = Option::where('key', 'order_prefix')->first();


        $order_no = $order_prefix->value . $max_order;
        $tax = Option::where('key', 'tax')->first();
        $tax = ($plan->price * 0.01) * $tax->value;

        $user = new Subscription;
        $user->order_no = $order_no;
        $user->amount = $data['amount'];
        $user->tax = $tax;
        $user->trx = $data['payment_id'];
        $user->will_expire = $expiry_date;
        $user->user_id = Auth::id();
        $user->plan_id = $plan->id;
        $user->payment_method = $data['payment_method'];;


        if (isset($data['payment_status'])) {
          $transaction->payment_status = $data['payment_status'];
        } else {
          $user->payment_status = 1;
        }

        $auto_order = Option::where('key', 'auto_order')->first();
        if ($auto_order->value == 'yes'  && $user->payment_status == 1) {
          $user->status = 1;
        }

        $user->save();

        if ($auto_order->value == 'yes' && $user->status == 1) {
          $dom = Domain::where('user_id', Auth::id())->first();
          $dom->data = $plan->data;
          $dom->subscription_id = $user->id;
          $dom->will_expire = $expiry_date;
          $dom->is_trial = 0;
          $dom->save();


          $dom->orderlog()->create(['subscription_id' => $user->id, 'domain_id' => $dom->id]);
        }
        Session::flash('success', trans('Thank You For Subscribe After Review The Order You Will Get A Notification Mail From Admin'));



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
        DB::rollback();
      }
      return redirect('merchant/plan');
    }
    abort(404);
  }

  public function fail()
  {
    Session::forget('payment_info');
    Session::flash('fail', trans('Transaction Failed'));
    return redirect('merchant/plan');
  }

  public function plans()
  {
    $posts = Plan::where('status', 1)->where('is_default', 0)->where('price', '>', 0)->get();
    return view('seller.plan.index', compact('posts'));
  }
  public function store_maintenance(Request $request)
  {
    return view('store-maintenance');
  }

}
