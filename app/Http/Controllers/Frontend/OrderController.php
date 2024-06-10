<?php

namespace App\Http\Controllers\Frontend;

use Cart;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Coupon;
use App\Models\Product;
use App\Models\Customer;
use App\Models\OrderItem;
use App\Models\OrderMeta;
use App\Models\ShopOption;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Helper\Order\Mollie;
use App\Helper\Order\Paypal;
use App\Mail\AdminOrderMail;
use App\Mail\SellerOrderMail;
use App\Models\OrderShipping;
use App\Models\ShippingMethod;
use App\Helper\Order\Instamojo;
use App\Helper\Order\Toyyibpay;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SellerNewOrderNotification;

class OrderController extends Controller
{

  public function store_validation(Request $request)
  {

    if (getCartCount() == 0) {

      if ($request->ajax()) {
        return response()->json(['message' => trans('Your cart is empty!.')], 422);
      }

      return back();
    } else {
      $cart = get_cart();
      foreach ($cart['items'] as $row) {

        $product = Product::find($row->attributes->product_id);
        if (!$product->isStockAvailable($row->quantity ?? 1)) {
          return response()->json(['message' => trans('Please remove Out of stock products from cart to place an order.')], 422);
        }
      }
    }

    $validator = Validator::make(
      $request->all(),
      [
        'first_name' => 'required|max:50',
        'last_name' => 'required|max:50',
        'delivery_address' => 'required|max:100',
        'phone' => 'required|integer|digits:10',
        'zip_code' => 'required|max:50',
        'email' => 'required|email_address|max:100|regex:/(.+)@([a-zA-Z0-9-]+)\.(.+)/i|',
        'location' => 'required',
        'password' => $request->create_account === '1' ? 'required|min:8|without_spaces' : 'nullable',
        'shipping_mode' => domain_info('shop_type') == 'reseller' ? '' : 'required',
        'payment_method' => domain_info('shop_type') == 'reseller' ? '' : 'required',
      ],
      [
        'phone.integer' => 'Invalid phone number',
        'phone.digits' => 'Invalid phone number'
      ]
    );

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors(), 'message' => trans('Invalid data.')], 422);
    }

    $shop_id = domain_info('shop_id');

    if ($request->create_account == 1) {
      $check_is_exist = Customer::where('email', $request->email)->where('shop_id', domain_info('shop_id'))->first();
      if (!empty($check_is_exist)) {
        Session::flash('user_limit', trans('Opps email address already exists'));

        return response()->json(['message' => trans('Opps email address already exists')], 403);
      }


      $user_limit = domain_info('customer_limit', 0);

      $total_customers = Customer::where('shop_id', $shop_id)->where('user_type', 'customer')->count();

      if ($user_limit >= 0 && $user_limit <= $total_customers) {
        Session::flash('user_limit', trans('Opps something wrong with registration but you can make order'));
        Session::put('registration', false);
        return response()->json(['message' => trans('Opps something wrong with registration but you can make order')], 403);
      } else {
        Session::forget('registration');
      }
    }
  }
  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {

    if (getCartCount() == 0) {

      if ($request->ajax()) {
        return response()->json(['message' => trans('Your cart is empty!.')], 422);
      }

      return back();
    }
    $validator = Validator::make($request->all(), [
      'first_name' => 'required|max:50',
      'last_name' => 'required|max:50',
      'email' => 'required|email_address|max:100',
      'phone' => 'required|min:10|max:10',
      'location' => 'required',
      'shipping_mode' => 'required',
      'payment_method' => 'required',
      'delivery_address' => 'required|max:100',
      'zip_code' => 'required|max:50',
      'password' => $request->create_account === '1' ? 'required|min:8|without_spaces' : 'nullable',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors(), 'message' => trans('Invalid data.')], 422);
    }

    $shop_id = domain_info('shop_id');

    if ($request->create_account == 1) {
      $check_is_exist = Customer::where('email', $request->email)->where('shop_id', domain_info('shop_id'))->first();
      if (!empty($check_is_exist)) {
        Session::flash('user_limit', trans('Opps email address already exists'));

        return response()->json(['message' => trans('Opps email address already exists')], 403);
      }


      $user_limit = domain_info('customer_limit', 0);

      $total_customers = Customer::where('shop_id', $shop_id)->where('user_type', 'customer')->count();

      if ($user_limit >= 0 && $user_limit <= $total_customers) {
        Session::flash('user_limit', trans('Opps something wrong with registration but you can make order'));
        Session::put('registration', false);
        return response()->json(['message' => trans('Opps something wrong with registration but you can make order')], 403);
      } else {
        Session::forget('registration');
      }

      $user = new Customer();
      $user->email = $request->email;
      $user->first_name = $request->first_name;
      $user->last_name = $request->last_name ?? "";
      $user->mobile = $request->phone;
      $user->password = Hash::make($request->password);
      $user->shop_id = $shop_id;
      $user->save();
      Auth::guard('customer')->loginUsingId($user->id);
    }

    $location = $request->location;
    $ShippingMethod = ShippingMethod::where('status', 1)
      ->where('shop_id', $shop_id)
      ->where('id', $request->shipping_mode)
      ->whereHas('locations',  function ($query) use ($location) {
        $query->where('city_id', $location)
          ->where('status', 1);
      })
      ->first();
    if ($request->payment_method == 'cod') {
      $payment_id = Str::random(10);
    } else {
      $payment_id = null;
    }

    DB::beginTransaction();
    try {


      $cart = get_cart();

      if (Session::has('coupon')) {
        $coupon = Coupon::where('code', Session::get('coupon'))->first();
      }

      $order = new Order();
      if (Auth::guard('customer')->check()) {
        $order->customer_id = Auth::guard('customer')->id();
      }

      $order->shop_id  = $shop_id;
      $order->order_type  = 1;
      $order->status = 'pending';
      $order->transaction_id = $payment_id;
      // $order->category_id = $request->payment_method;
      $order->payment_method = $request->payment_method;
      $order->payment_status = 2;
      $order->discount = $cart['discount'];
      $order->subtotal = $cart['subtotal'];
      $order->tax = amount_to_number($cart['tax']);
      $order->shipping = $this->calculateWeight(amount_to_number($cart['weight']), $ShippingMethod->cost ?? 0);
      $order->total = $this->calculateShipping(amount_to_number($cart['total']),  $ShippingMethod->cost ?? 0, amount_to_number($cart['weight']));
      $order->save();

      $info['first_name'] = $request->first_name;
      $info['last_name'] = $request->last_name;
      $info['name'] =  ucfirst($request->first_name) . " " . ucfirst($request->last_name);
      $info['email'] = $request->email;
      $info['phone'] = $request->phone;
      $info['comment'] = $request->comment;
      $info['address'] = $request->delivery_address;
      $info['zip_code'] = $request->zip_code;
      $info['coupon_discount'] = amount_to_number($cart['discount']);
      $info['sub_total'] = amount_to_number($cart['subtotal']);
      $info['tax'] = amount_to_number($cart['tax']);

      $meta = new OrderMeta();
      $meta->order_id = $order->id;
      $meta->key = 'content';
      $meta->value = json_encode($info);
      $meta->save();

      $items = [];
      foreach ($cart['items'] as $key => $row) {

        $product = Product::find($row->attributes->product_id);

        if (!$product->isStockAvailable($row->quantity ?? 1)) {
          return;
          // return response()->json(['message' => trans('Sorry, some product are currently not available.')], 422);
        }

        $discount = 0;
        $tax = 0;
        if (isset($coupon)) {
          $discount = $row->price *  ($coupon->amount * 0.01);
        }

        if ($product->tax > 0) {
          $tax = $row->price *  ($product->tax * 0.01);
        }

        $options['options'] = $row->attributes->options;
        $options['tax'] = $tax * $row->quantity;
        $options['discount'] = $discount * $row->quantity;
        $options['subTotal'] = $row->price * $row->quantity;
        $options['total'] = $options['subTotal'] + $options['tax'] + $options['discount'];
        $options['supplier'] = null;
        $options['reseller_price'] = 0;
        $options['reseller_tax'] = 0;

        $data['order_id'] = $order->id;
        $data['product_id'] = $row->attributes->product_id;
        $data['info'] = json_encode($options);
        $data['qty'] = $row->quantity;
        $data['amount'] = $row->price;

        array_push($items, $data);
      }

      OrderItem::insert($items);

      $order->reduce_stock();



      if ($request->location) {
        $ship['order_id'] = $order->id;
        $ship['location_id'] = $request->location;
        $ship['shipping_id'] = $request->shipping_mode;
        OrderShipping::insert($ship);
      }

      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
    }

    Session::put('order_no', $order->order_no);
    if ($request->payment_method != 'cod') {
      $payment_data['ref_id'] = $order->id;
      $payment_data['payment_method'] = $request->payment_method;
      $payment_data['amount'] = $order->total;
      $payment_data['email'] = $request->email;
      $payment_data['name'] = $request->name;
      $payment_data['phone'] = $request->phone;
      $payment_data['billName'] = 'Order No :' . $order->order_no;
      Session::put('customer_order_info', $payment_data);

      if ($request->payment_method == 'paypal') {
        try {
          return Paypal::make_payment($payment_data);
        } catch (\Exception $e) {
          Order::destroy($order->id);
          return $this->payment_fail();
        }
      }
      if ($request->payment_method == 'instamojo') {
        try {
          return Instamojo::make_payment($payment_data);
        } catch (\Exception $e) {
          Order::destroy($order->id);
          return $this->payment_fail();
        }
      }
      if ($request->payment_method == 'toyyibpay') {
        try {
          return Toyyibpay::make_payment($payment_data);
        } catch (\Exception $e) {
          Order::destroy($order->id);
          return $this->payment_fail();
        }
      }
      if ($request->payment_method == 'mollie') {
        try {
          return Mollie::make_payment($payment_data);
        } catch (\Exception $e) {
          Order::destroy($order->id);
          return $this->payment_fail();
        }
      }
      if ($request->payment_method == 'stripe') {
        Session::put('stripe_payment', true);
        return redirect('/payment-with-stripe');
      }
      if ($request->payment_method == 'razorpay') {
        Session::put('razorpay_payment', true);
        return redirect('/payment-with-razorpay');
      }
      if ($request->payment_method == 'paystack') {
        Session::put('paystack_payment', true);
        return redirect('/payment-with-paystack');
      }
    }


    try {
      if (Cache::has(domain_info('shop_id') . 'store_email')) {
        $store_email = Cache::get(domain_info('shop_id') . 'store_email');
      } else {
        $shop = Shop::find(domain_info('shop_id'));
        $store_email = $shop->email;
      }
      $get_order = Order::where('shop_id', $shop_id)->with('order_item_with_stock', 'order_item_with_file', 'customer', 'order_content', 'shipping_info', 'order_stock_meta', 'files', 'gateway')->findorFail($order->id);
      $order_content = json_decode($get_order->order_content->value ?? '');
      if (!empty($order_content->email)) {
        $location = ShopOption::where('key', 'location')->where('shop_id', $order->shop_id)->first();
        $location = json_decode($location->value ?? '');

        $mail_data['order'] = $get_order ?? '';
        $mail_data['location'] = $location;
        $mail_data['order_content'] = $order_content;
        $mail_data['store_email'] = $store_email;
        $mail_data['order_no'] = $order->order_no;
        $mail_data['base_url'] = url('/');
        $mail_data['site_name'] = Cache::get(domain_info('shop_id') . 'shop_name', env('APP_NAME'));
        $mail_data['order_url'] = url('/seller/order', $order->id);
        $mail_data['shop_id'] =  $order->shop_id;

        if (env('QUEUE_MAIL') == 'on') {

          dispatch(new \App\Jobs\Ordernotification($mail_data));
        } else {

          Mail::to($order_content->email)->send(new SellerOrderMail($mail_data));
        }
      }
      if (!empty($store_email)) {

        $mail_data['store_email'] = $store_email;
        $mail_data['order_no'] = $order->order_no;
        $mail_data['base_url'] = url('/');
        $mail_data['site_name'] = Cache::get(domain_info('shop_id') . 'shop_name', env('APP_NAME'));
        $mail_data['order_url'] = url('/seller/order', $order->id);
        $mail_data['shop_id'] =  $order->shop_id;


        Mail::to($store_email)->send(new AdminOrderMail($mail_data));
      }
    } catch (\Exception $e) {
    }

    cart_clear();

    if (Cache::has(domain_info('shop_id') . 'order_receive_method')) {
      $method = Cache::get(domain_info('shop_id') . 'order_receive_method');
    } else {
      $method = "email";
    }

    if ($method == 'whatsapp') {
      $option = ShopOption::where('shop_id',  $shop_id)->where('key', 'whatsapp')->first();
      if (is_whatsapp_enabled() == 1 && Cache::has(domain_info('shop_id') . 'whatsapp')) {
        $whatsapp = json_decode(Cache::get(domain_info('shop_id') . 'whatsapp'));
        $url = "https://wa.me/+" . $whatsapp->phone_number . "?text=My Order No Is " . str_replace('#', '', $order->order_no);
        // return redirect($url);
        return response()->json(['message' => trans('Order created'), 'url' => $url], 200);
      } else {
        $method = "email";
      }
    }

    $shop = Shop::with('user')->find(domain_info('shop_id'));

    if (isset($shop->user)) {
      $message = [
        'title' => 'New order created',
        'message' => $order->order_no . ' order created successfully.',
        'order_id' => $order->id,
        'url' => '',
      ];
      Notification::send($shop->user, new SellerNewOrderNotification($message));
    }

    //return response()->json(['message' => trans('Order created'), 'url' => url('/thanks')], 200);
    return redirect(url('/thanks'));
  }

  public function payment_success()
  {

    if (Session::has('customer_payment_info')) {

      $data = Session::get('customer_payment_info');

      $order = Order::findorFail($data['ref_id']);
      $order->transaction_id = $data['payment_id'];
      $order->payment_method = $data['payment_method'];
      if (isset($data['payment_status'])) {
        $order->payment_status = $data['payment_status'];
      } else {
        $order->payment_status = 1;
      }

      $order->save();
      Session::forget('customer_payment_info');
      cart_clear();

      if (domain_info('shop_type') == 'reseller') {
        $this->process_group_order($order->id);
        $order_ids = Order::where('group_order_id', $order->id)->get()->pluck('order_no')->toArray();
        session()->forget('key');
        ('order_no');
        Session::put('order_no', implode(", ", $order_ids ?? []));
        return redirect('/thanks');
      } else {
        $shop = Shop::with('user')->find(domain_info('shop_id'));

        if (Cache::has(domain_info('shop_id') . 'store_email')) {
          $store_email = Cache::get(domain_info('shop_id') . 'store_email');
        } else {
          $store_email = $shop->user ? $shop->user->email : '';
        }

        if (isset($shop->user)) {
          $message = [
            'title' => 'New order created',
            'message' => $order->order_no . ' order created successfully.',
            'order_id' => $order->id,
            'url' => '',
          ];
          Notification::send($shop->user, new SellerNewOrderNotification($message));
        }

        $get_order = Order::where('shop_id', $shop->id)->with('order_item_with_stock', 'order_item_with_file', 'customer', 'order_content', 'shipping_info', 'order_stock_meta', 'files', 'gateway')->findorFail($order->id);

        $location = ShopOption::where('key', 'location')->where('shop_id', $order->shop_id)->first();
        $location = json_decode($location->value ?? '');
        $order_content = json_decode($get_order->order_content->value ?? '');


        $mail_data['order'] = $get_order ?? '';
        $mail_data['location'] = $location;
        $mail_data['order_content'] = $order_content;


        $mail_data['store_email'] = $store_email;
        $mail_data['order_no'] = $order->order_no;
        $mail_data['base_url'] = url('/');
        $mail_data['site_name'] = Cache::get(domain_info('shop_id') . 'shop_name', null);
        $mail_data['order_url'] = url('/seller/order', $order->id);
        $mail_data['shop_id'] =  $order->shop_id;

        if (Cache::has(domain_info('shop_id') . 'order_receive_method')) {
          $method = Cache::get(domain_info('shop_id') . 'order_receive_method');
        } else {
          $method = "email";
        }

        if ($method == 'email') {
          if (env('QUEUE_MAIL') == 'on') {

            dispatch(new \App\Jobs\Ordernotification($mail_data));
          } else {

            Mail::to($order_content->email)->send(new SellerOrderMail($mail_data));
            Mail::to($store_email)->send(new AdminOrderMail($mail_data));
          }
          return redirect('/thanks');
        } else {
          if (Cache::has(domain_info('shop_id') . 'whatsapp')) {
            $whatsapp = json_decode(Cache::get(domain_info('shop_id') . 'whatsapp'));
            $url = "https://wa.me/+" . $whatsapp->phone_number . "?text=My Order No Is " . str_replace('#', '', $order->order_no);
            return redirect($url);
          }
          if (env('QUEUE_MAIL') == 'on') {

            dispatch(new \App\Jobs\Ordernotification($mail_data));
          } else {

            Mail::to($order_content->email)->send(new SellerOrderMail($mail_data));
            Mail::to($store_email)->send(new AdminOrderMail($mail_data));
          }
          return redirect('/thanks');
        }
      }
    }

    abort(404);
  }

  public function payment_fail()
  {
    Session::flash('payment_fail', trans('Sorry Transaction Failed'));

    return redirect('/checkout');
  }


  public function calculateShipping($total, $shipping_amount, $weight)
  {
    $shipping_amount = (float) $shipping_amount;
    $totalAmount = $total;

    $weight_amount = $this->calculateWeight($weight, $shipping_amount);
    $amount = $totalAmount + $weight_amount;

    return $amount;
  }

  public function calculateWeight($weight, $amount)
  {
    return $amount;
  }

  public function supplier_shipping_prices($location, $type = 0)
  {
    $items = [];
    $cart = get_cart();
    foreach ($cart['items'] as $key => $value) {
      $items[] = $value->attributes->product_id;
    }
    $shop_ids =  DB::table('products')->whereIn('id', $items)->groupBy('shop_id')->get()->pluck('shop_id');

    $shipping_methods = [];
    foreach ($shop_ids as $key => $shop_id) {
      $shipping_methods[$shop_id] = ShippingMethod::where('status', 1)
        ->where('shop_id', $shop_id)
        ->whereHas('shipping_locations',  function ($query) use ($location) {
          $query->where('city_id', $location)
            ->where('status', 1);
        })
        ->orderBy('cost', 'desc')
        ->first();
    }

    if ($type == 1) return $shipping_methods;

    return response()->json($shipping_methods, 200);
  }

  public function supplier_product_availability($location)
  {
    $items = [];
    $cart = get_cart();
    foreach ($cart['items'] as $key => $value) {
      $items[] = [
        'id' => $value->attributes->product_id,
        'estimated_delivery' => product_estimated_shipping($value->attributes->product_id, $location),
        'status' => product_shipping_availability($value->attributes->product_id, $location)
      ];
    }

    return response()->json($items, 200);
  }

  public function make_reseller_order(Request $request)
  {
    return response()->json(['message' => trans('Payment Mode on Hold: Due to technical issues, the payment option is currently unavailable. We are working on resolving the issue. Thank you for your understanding.')], 422);
    if (getCartCount() == 0) {

      if ($request->ajax()) {
        return response()->json(['message' => trans('Your cart is empty!.')], 422);
      }

      return back();
    }
    $validator = Validator::make($request->all(), [
      'first_name' => 'required|max:50',
      'last_name' => 'required|max:50',
      'email' => 'required|email_address|max:100',
      'phone' => 'required|min:10|max:10',
      'location' => 'required',
      'delivery_address' => 'required|max:100',
      'zip_code' => 'required|max:50',
      'password' => $request->create_account === '1' ? 'required|min:8|without_spaces' : 'nullable',
    ]);

    if ($validator->fails()) {
      return response()->json(['errors' => $validator->errors(), 'message' => trans('Invalid data.')], 422);
    }

    $shop_id = domain_info('shop_id');

    if (Session::has('coupon')) {
      $coupon = Coupon::where('code', Session::get('coupon'))->first();
    }

    if ($request->create_account == 1) {
      $check_is_exist = Customer::where('email', $request->email)->where('shop_id', domain_info('shop_id'))->first();
      if (!empty($check_is_exist)) {
        Session::flash('user_limit', trans('Opps email address already exists'));

        return response()->json(['message' => trans('Opps email address already exists')], 403);
      }


      $user_limit = domain_info('customer_limit', 0);

      $total_customers = Customer::where('shop_id', $shop_id)->where('user_type', 'customer')->count();

      if ($user_limit >= 0 && $user_limit <= $total_customers) {
        Session::flash('user_limit', trans('Opps something wrong with registration but you can make order'));
        Session::put('registration', false);
        return response()->json(['message' => trans('Opps something wrong with registration but you can make order')], 403);
      } else {
        Session::forget('registration');
      }

      $user = new Customer();
      $user->email = $request->email;
      $user->first_name = $request->first_name;
      $user->last_name = $request->last_name ?? "";
      $user->mobile = $request->phone;
      $user->password = Hash::make($request->password);
      $user->shop_id = $shop_id;
      $user->save();
      Auth::guard('customer')->loginUsingId($user->id);
    }



    $payment_id = null;

    DB::beginTransaction();
    try {
      $cart = get_cart();
      $order = new Order();
      if (Auth::guard('customer')->check()) {
        $order->customer_id = Auth::guard('customer')->id();
      }

      $shipping_prices = $this->supplier_shipping_prices($request->location, 1);

      if (!isset($shipping_prices)) {
        return;
        // return response()->json(['message' => trans('Sorry, some product are currently not available.')], 422);
      }

      $shipping_price = 0;

      foreach ($shipping_prices as $key => $price) {
        if (!isset($price)) {
          return;
          // return response()->json(['message' => trans('Sorry, some product are currently not available.')], 422);
        }
        $shipping_price += $price ? $price->cost : 0;
      }

      $order->shop_id  = $shop_id;
      $order->status = 'pending';
      $order->transaction_id = $payment_id;
      // $order->category_id = 2;
      $order->payment_method = $request->payment_method;
      $order->payment_status = 2;
      $order->group_order_id = null;
      $order->order_type = 2;
      $order->discount = $cart['discount'];
      $order->subtotal = $cart['subtotal'];
      $order->tax = $cart['tax'];
      $order->shipping = $shipping_price; // $this->calculateWeight(amount_to_number($cart['weight']), $shipping_price);
      $order->total = amount_to_number($cart['total']) + $shipping_price ?? 0; // $this->calculateShipping(amount_to_number($cart['total']), $shipping_price ?? 0, amount_to_number($cart['weight']));
      $order->save();

      $info['first_name'] = $request->first_name;
      $info['last_name'] = $request->last_name;
      $info['name'] =  ucfirst($request->first_name) . " " . ucfirst($request->last_name);
      $info['email'] = $request->email;
      $info['phone'] = $request->phone;
      $info['comment'] = $request->comment;
      $info['address'] = $request->delivery_address;
      $info['zip_code'] = $request->zip_code;
      $info['coupon_discount'] = amount_to_number($cart['discount']);
      $info['sub_total'] = amount_to_number($cart['subtotal']);

      $meta = new OrderMeta();
      $meta->order_id = $order->id;
      $meta->key = 'content';
      $meta->value = json_encode($info);
      $meta->save();

      $items = [];

      foreach ($cart['items'] as $key => $row) {
        $variants = [];
        foreach ($row->attributes->options as $key => $option) {
          $variants[] = $option->id;
        }
        $product = Product::with('price')->where('id', $row->attributes->product_id)->with(['variants' => function ($q) use ($variants) {
          $q->whereIn('id', $variants);
        }])->first();

        $discount = 0;
        $tax = 0;
        if (isset($coupon)) {
          $discount = $row->price *  ($coupon->amount * 0.01);
        }

        if ($product->tax > 0) {
          $tax = $row->price *  ($product->tax * 0.01);
        }

        $options['options'] = $row->attributes->options;
        $options['tax'] = $tax * $row->quantity;
        $options['discount'] = $discount * $row->quantity;
        $options['subTotal'] = $row->price * $row->quantity;
        $options['total'] = $options['subTotal'] + $options['tax'] - $options['discount'];

        $base_price = $product->price->price ?? 0;
        $supplier_price =  $base_price;
        foreach ($product->variants ?? [] as $option) {
          if ($option->amount_type == 1) {
            $supplier_price += $option->amount;
          } else {
            $supplier_price += $base_price * ($option->amount * 0.01);
          }
        }

        $supplier_tax = 0;
        if ($product->tax > 0) {
          $supplier_tax = $supplier_price *  ($product->tax * 0.01);
        }

        $supplier = [];
        $supplier['price'] = $supplier_price;
        $supplier['tax'] = $supplier_tax * $row->quantity;
        $supplier['discount'] = 0;
        $supplier['subTotal'] = $supplier_price * $row->quantity;
        $supplier['total'] = $supplier['subTotal'] + $supplier['tax'];
        $options['supplier'] = $supplier;
        $options['reseller_price'] = $options['total'] - $supplier['subTotal'] - $options['tax'];
        $options['reseller_tax'] = $options['tax'] - $supplier['tax'];

        $data['order_id'] = $order->id;
        $data['product_id'] = $row->attributes->product_id;
        $data['info'] = json_encode($options);
        $data['qty'] = $row->quantity;
        $data['amount'] = $row->price;

        array_push($items, $data);
      }
      OrderItem::insert($items);

      // $order->reduce_stock();

      if ($request->location) {
        foreach ($shipping_prices as $key => $price) {
          $ship['order_id'] = $order->id;
          $ship['location_id'] = $request->location;
          $ship['shipping_id'] = $price->id;
          $ship['amount'] = $price->cost ?? 0;
          OrderShipping::insert($ship);
        }
      }

      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
    }

    Session::put('order_no', $order->order_no);
    if ($request->payment_method != 'cod') {
      $payment_data['ref_id'] = $order->id;
      $payment_data['payment_method'] = $request->payment_method;
      $payment_data['amount'] = $order->total;
      $payment_data['email'] = $request->email;
      $payment_data['name'] = $request->name;
      $payment_data['phone'] = $request->phone;
      $payment_data['billName'] = 'Order Processing';
      Session::put('customer_order_info', $payment_data);
      if ($request->payment_method == 'razorpay') {
        Session::put('razorpay_payment', true);
        return redirect('/payment-with-razorpay');
      }
    }
  }

  public function process_group_order($order_id)
  {
    $order = Order::with('order_items')->find($order_id);
    $orders = [];

    $shop = Shop::with('user')->find($order->shop_id);

    foreach ($order->order_items as $key => $item) {
      $product = Product::find($item->product_id);
      $orders[$product->shop_id]['id'] = $product->shop_id;
      $orders[$product->shop_id]['items'][$key]['order_id'] = $order->id;
      $orders[$product->shop_id]['items'][$key]['product_id'] = $item->product_id;
      $orders[$product->shop_id]['items'][$key]['info'] = json_decode($item->info, true);
      $orders[$product->shop_id]['items'][$key]['qty'] = $item->qty;
      $orders[$product->shop_id]['items'][$key]['amount'] = $item->amount;
    }
    if (count($orders) == 0) return;
    $meta = OrderMeta::where('order_id', $order->id)->first();
    $order_meta = json_decode($meta->value);
    $discount = 0;
    $discount_percent = 0;

    if (isset($order_meta)) {
      $discount = $order_meta->coupon_discount ?? 0;
      if ($discount > 0 && $order_meta->sub_total > 0) {
        $discount_percent = $discount / $order_meta->sub_total;
      }
    }

    DB::beginTransaction();
    try {
      foreach ($orders as $item) {

        $new_order = new Order();
        $new_order->customer_id = $order->customer_id;
        $new_order->shop_id  = $item['id'];
        $new_order->status = 'pending';
        $new_order->transaction_id = $order->transaction_id;
        // $new_order->category_id = $order->category_id;
        $new_order->payment_method = $order->payment_method;
        $new_order->payment_status = $order->payment_status;
        $new_order->group_order_id = $order->id;
        $new_order->order_type = 1;
        $new_order->tax = 0;
        $new_order->shipping = 0;
        $new_order->total = 0;
        $new_order->save();

        // $items = [];

        // foreach ($item['items'] as $key => $value) {

        //   $supplier_amount = Product::supplier_price_calculation($value['product_id'], $value['qty'], $value['info']['options']);
        //   $value['info']['supplier'] = $supplier_amount;

        //   $items[$key]['order_id'] = $new_order->id;
        //   $items[$key]['product_id'] = $product->id; // $value['product_id'];
        //   $items[$key]['info'] = json_encode($value['info']);
        //   $items[$key]['qty'] = $value['qty'];
        //   $items[$key]['amount'] = $value['amount'];
        // }

        // OrderItem::insert($items);
        // unset($items);

        $sub_total = collect($item['items'])->sum(function ($value) {
          return $value['qty'] * $value['amount'];
        });

        $supplier_total = collect($item['items'])->sum(function ($value) {
          return $value['info']['supplier']['total'];
        });

        $new_meta = new OrderMeta();
        $new_meta->order_id = $new_order->id;
        $new_content = $order_meta;
        $new_content->coupon_discount = $sub_total * $discount_percent;
        $new_content->sub_total = $sub_total;
        $new_content->supplier_total = $supplier_total;
        $new_meta->key = 'content';
        $new_meta->value = json_encode($new_content);
        $new_meta->save();

        // $shipping_methods = OrderShipping::whereHas('method', function ($q) use ($item) {
        //   $q->where('shop_id', $item['id']);
        // })
        // ->where('order_id', $order_id)
        // ->get();

        // foreach ($shipping_methods as $key => $method) {
        //   $ship['order_id'] = $new_order->id;
        //   $ship['location_id'] = $method->location_id;
        //   $ship['shipping_id'] = $method->shipping_id;
        //   $ship['amount'] = $method->amount ?? 0;
        //   OrderShipping::insert($ship);
        // }

        OrderShipping::whereHas('method', function ($q) use ($item) {
          $q->where('shop_id', $item['id']);
        })
          ->where('order_id', $order_id)
          ->update(['order_id' => $new_order->id]);

        foreach ($item['items'] as $key => $value) {
          OrderItem::where('order_id',  $value['order_id'])->where('product_id', $value['product_id'])->update(['order_id' => $new_order->id]);
        }

        $tax = collect($item['items'])->sum(function ($value) {
          return $value['info']['tax'] ?? 0;
        });

        $total = collect($item['items'])->sum(function ($value) {
          return $value['info']['total'] ?? 0;
        });

        $shipping_cost = OrderShipping::where('order_id',  $new_order->id)->sum('amount');
        $new_order->discount = 0;
        $new_order->subtotal = $sub_total;
        $new_order->shipping = $shipping_cost;
        $new_order->tax = $tax;
        $new_order->total = $total + $shipping_cost; // $shipping_cost + $tax; //+ $new_content->sub_total - $new_content->coupon_discount;
        $new_order->save();

        $new_order->reduce_stock();
        $new_order->update_settlement();
        dispatch(new \App\Jobs\SupplierOrderEmail($new_order));
        dispatch(new \App\Jobs\ResellerOrderEmail($new_order));
      }

      DB::commit();
    } catch (\Exception $e) {
      DB::rollback();
    }
  }
}
