<?php

namespace App\Http\Controllers\Frontend;

use App\Models\Order;
use App\Models\Customer;
use App\Models\ShopOption;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;
use App\Mail\CustomerRegisterMail;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Cache;
use Artesaos\SEOTools\Facades\SEOMeta;
use Artesaos\SEOTools\Facades\SEOTools;
use Illuminate\Support\Facades\Session;
use Artesaos\SEOTools\Facades\JsonLdMulti;


class UserController extends Controller
{
   public function __construct()
   {
      if (env('MULTILEVEL_CUSTOMER_REGISTER') != true || url('/') == env('APP_URL')) {
         abort(404);
      }
   }

   public function login()
   {

      if (Auth::check() == true) {
         Auth::logout();
      }
      if (Auth::guard('customer')->check() == true) {

         return redirect('/user/dashboard');
      }
      if (Cache::has(domain_info('shop_id') . 'seo')) {
         $seo = json_decode(Cache::get(domain_info('shop_id') . 'seo'));
      } else {
         $data = ShopOption::where('shop_id', domain_info('shop_id'))->where('key', 'seo')->first();
         $seo = json_decode($data->value ?? '');
      }
      $get_shop_name = \App\Models\Shop::where('id', domain_info('shop_id'))->first();
      if (!empty($seo)) {
         JsonLdMulti::setTitle('Login - ' . $seo->title ?? $get_shop_name->name ?? '');
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(current_shop_logo_url());

         SEOMeta::setTitle('Login - ' . $seo->title ?? $get_shop_name->name ?? '');
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Login - ' . $seo->title ?? $get_shop_name->name ?? '');
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', current_shop_logo_url());
         SEOTools::twitter()->setTitle('Login - ' . $seo->title ?? $get_shop_name->name ?? '');
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(current_shop_logo_url());
      }

      return view(base_view() . '::account.login');
   }

   public function register()
   {
      if (Auth::check()) {
         Auth::logout();
      }
      if (Auth::guard('customer')->check()) {
         return redirect('/user/dashboard');
      }

      if (Cache::has(domain_info('shop_id') . 'seo')) {
         $seo = json_decode(Cache::get(domain_info('shop_id') . 'seo'));
      } else {
         $data = ShopOption::where('shop_id', domain_info('shop_id'))->where('key', 'seo')->first();
         $seo = json_decode($data->value ?? '');
      }
      $get_shop_name = \App\Models\Shop::where('id', domain_info('shop_id'))->first();
      if (!empty($seo)) {
         JsonLdMulti::setTitle('Register - ' . $seo->title ?? $get_shop_name->name ?? '');
         JsonLdMulti::setDescription($seo->description ?? null);
         JsonLdMulti::addImage(current_shop_logo_url());

         SEOMeta::setTitle('Register - ' . $seo->title ?? $get_shop_name->name ?? '');
         SEOMeta::setDescription($seo->description ?? null);
         SEOMeta::addKeyword($seo->tags ?? null);

         SEOTools::setTitle('Register - ' . $seo->title ?? $get_shop_name->name ?? '');
         SEOTools::setDescription($seo->description ?? null);
         SEOTools::setCanonical($seo->canonical ?? url('/'));
         SEOTools::opengraph()->addProperty('keywords', $seo->tags ?? null);
         SEOTools::opengraph()->addProperty('image', current_shop_logo_url());
         SEOTools::twitter()->setTitle('Register - ' . $seo->title ?? $get_shop_name->name ?? '');
         SEOTools::twitter()->setSite($seo->twitterTitle ?? null);
         SEOTools::jsonLd()->addImage(current_shop_logo_url());
      }
      return view(base_view() . '::account.register');
   }

   public function settings()
   {
      SEOTools::setTitle('Settings');
      return view(base_view() . '::account.account');
   }

   public function settings_update(Request $request)
   {

      $user = Customer::find(Auth::guard('customer')->user()->id);
      $shop_id = domain_info('shop_id');

      $request->validate([
         'first_name' =>  'required|max:255',
         // 'phone' => 'sometimes|required|min:10|max:10|unique:customers,mobile,' . Auth::guard('customer')->user()->id . ',id,shop_id,' .domain_info('shop_id'),
         // 'email'  => 'required|email_address|unique:customers,email,' . Auth::guard('customer')->user()->id . ',id,shop_id,' .domain_info('shop_id')
         'mobile' => 'sometimes|required|min:10|max:10',
         'mobile' =>  [
            Rule::unique('customers')
               ->whereNotIn('id', [$user->id])
               ->where('mobile', $request->mobile)
               ->where('shop_id',  $shop_id)
               ->where('user_type',  'customer'),
         ],
         'email'  => 'required|email_address',
         'email' =>  [
            Rule::unique('customers')
               ->whereNotIn('id', [$user->id])
               ->where('email', $request->email)
               ->where('shop_id',  $shop_id)
               ->where('user_type',  'customer'),
         ],
      ]);

      if ($request->has('password') && !empty($request->get('password'))) {
         $request->validate([
            'password' => ['required', 'string', 'min:8', 'confirmed', 'without_spaces'],
         ]);


         if (!(Hash::check($request->get('password_current'), $user->password))) {
            return redirect()->back()->with("error", "Your current password does not matches with the password you provided. Please try again.");
         }

         if (strcmp($request->get('password_current'), $request->get('password')) == 0) {
            return redirect()->back()->with("error", "New Password cannot be same as your current password. Please choose a different password.");
         }
      }

      if (!isset($user)) {
         $user = Customer::find(Auth::guard('customer')->user()->id);
         if (!isset($user)) {
            return redirect()->back()->with("error", 'User Not found.');
         }
      }

      $user->first_name = $request->first_name ?? $user->first_name;
      $user->last_name = $request->last_name ?? $user->last_name;
      if ($request->has('mobile') && !empty($request->get('mobile'))) {
         $user->mobile = $request->mobile ?? $user->mobile;
      }
      $user->email = $request->email;
      if (!empty($request->get('password'))) {
         $user->password = Hash::make($request->password);
      }
      $user->save();

      return back()->withSuccess('Profile Updated Successfully');
   }

   public function orders()
   {
      SEOTools::setTitle('Orders');
      $orders = Order::where('customer_id', Auth::guard('customer')->user()->id)->where('order_type', 1)->shopFinder()->with('PaymentMethod','shipping_info')->latest()->paginate(10);
      return view(base_view() . '::account.orders', compact('orders'));
   }

   public function order_view($id)
   {
      $id = request()->route()->parameter('id');
      $info = Order::where('customer_id', Auth::guard('customer')->user()->id)->with('order_item_with_file', 'order_content', 'shipping_info', 'PaymentMethod')->findorFail($id);
      $order_content = json_decode($info->order_content->value ?? '');
      SEOTools::setTitle('Order No ' . $info->order_no);
      return view(base_view() . '::account.order_view', compact('info', 'order_content'));
   }


   public function register_user(Request $request)
   {
      $request->validate([
         'password' => 'required|confirmed|min:8|max:50|without_spaces',
         // 'email' => 'required|email_address|max:100|unique:customers,email,NULL,id,shop_id,' . domain_info('shop_id'),
         'email' => 'required|email_address|max:100',
         'mobile' => 'sometimes|required|min:10|max:10|unique:customers,mobile,NULL,id,shop_id,' . domain_info('shop_id'),
         'first_name' => 'required|max:100',
         'last_name' => 'required|max:100',
      ]);
      $shop_id = domain_info('shop_id');


      $user_limit = domain_info('customer_limit', 0);
      $total_customers = Customer::where('shop_id', $shop_id)->where('user_type', 'customer')->count();

      if ($user_limit >= 0 && $user_limit <= $total_customers) {
         Session::flash('user_limit', 'Opps something wrong please contact with us..!!');
         return back()->withInput();
      }

      $agent_email  = Customer::where([['shop_id', $shop_id], ['email', $request->email] , ['user_type', 'agent']])->first();

      if (!empty($agent_email)) {
         Session::flash('agent_error', trans('Already registered as an Agent,can use same credentials to login...!!'));
         return back()->withInput();
      }

      $check = Customer::where([['shop_id', $shop_id], ['email', $request->email]])->first();
      if (!empty($check)) {
         Session::flash('user_limit', trans('Opps the email address already exists...!!'));
         return back()->withInput();
      }

      $user = new Customer();
      $user->email = $request->email;
      $user->first_name = $request->first_name;
      $user->last_name = $request->last_name;
      $user->password = Hash::make($request->password);
      $user->shop_id = $shop_id;
      $user->save();
      Auth::guard('customer')->loginUsingId($user->id);

      $user = $user->load(['shop', 'shop.domain']);

      if (env('QUEUE_MAIL') == 'on') {
         dispatch(new \App\Jobs\CustomerRegisterMailJob($user));
      } else {
         Mail::to($user->email)->send(new CustomerRegisterMail($user));
      }

      return redirect('/user/dashboard');
   }

   public function dashboard()
   {
      if (Auth::guard('customer')->check()) {
         SEOTools::setTitle('Dashboard');
         return view(base_view() . '::account.dashboard');
      }
      return redirect('/user/login');
   }
   public function agent_dashboard()
   {
      if (!Session::has('locale')) {
         set_language();
      }
      if (Auth::guard('customer')->check()) {
         $user = Auth::guard('customer')->user();
         $shop_id = $user->shop_id;
         $pendings = Order::where('shop_id', $shop_id)->where('status', 'ready-for-pickup')->where('agent_id', $user->id)->count();
         $pickup = Order::where('shop_id', $shop_id)->where('status', 'picked_up')->where('agent_id', $user->id)->count();
         $completed = Order::where('shop_id', $shop_id)->whereIn('status', ['completed', 'delivered'])->where('agent_id', $user->id)->count();

         return view('agent_dashboard', compact('pendings',  'pickup', 'completed', 'user'));
      }
   }
   public function agent_order()
   {

      $user = Auth::guard('customer')->user();
      $types = [
         'ready-for-pickup',
         'completed',
         'picked_up',
         'delivered'
     ];

     $orders = Order::where('shop_id',  $user->shop_id);

     $orders = $orders->whereIn('status', $types)
     ->with('customer')
     ->withCount('order_items')->where('agent_id', $user->id)
     ->orderBy('id', 'DESC')
     ->paginate(40);

      return view('agent.orders.index',compact('orders'));
   }
   
}
