<?php

namespace App\Http\Controllers\Seller;

use Cart;
use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Option;
use App\Models\Coupon;
use App\Models\Gateway;
use App\Models\Product;
use App\Models\Customer;
use App\Models\Category;
use App\Models\OrderMeta;
use App\Models\OrderItem;
use App\Models\ShopOption;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Models\OrderShipping;
use App\Models\ShippingMethod;
use App\Mail\OrderDeliveryMail;
use App\Mail\PickedUpOrderMail;
use App\Mail\CustomerOrderMail;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Mail;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Notification;
use App\Notifications\SellerNewOrderNotification;

class OrderController extends Controller
{
    protected $user_id;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request, $type)
    {
        $shop_id = current_shop_id();
        $orders = Order::where('order_type', 1)->shopFinder();

        if (isset($type) && $type != 'all') {
            $orders = $orders->where('status', $type);
        }

        if (!empty($request->src)) {
            $orders = $orders->where('order_no', $request->src);
        }

        if (!empty($request->payment_status)) {
            $orders = $orders->where('payment_status', $request->payment_status == 'cancel' ? 0 : $request->payment_status);
        }

        if (!empty($request->status)) {
            $orders = $orders->where('status', $request->status);
        }

        if (!empty($request->start)  && !empty($request->end)) {

            $start = date("Y-m-d", strtotime($request->start));
            $end = date("Y-m-d", strtotime($request->end));

            $orders = $orders->whereBetween('created_at', [$start, $end]);
        }

        if(current_shop_type() == 'supplier') {
            $orders = $orders->with(['supplier_settlement', 'reseller_settlement']);
        }else if(current_shop_type() == 'reseller') {
            $orders = $orders->with('reseller_settlement');
        }

        $orders = $orders->with(['customer', 'agent_details'])->withCount('order_items')->orderBy('id', 'DESC')->paginate(40);


        $pendings = Order::shopFinder()->where('order_type', 1)->where('status', 'pending')->count();
        $processing = Order::shopFinder()->where('order_type', 1)->where('status', 'processing')->count();
        $pickup = Order::shopFinder()->where('order_type', 1)->where('status', 'ready-for-pickup')->count();
        $da_pickup = Order::shopFinder()->where('order_type', 1)->where('status', 'picked_up')->count(); //delivery agent_picked up
        $da_delivery = Order::shopFinder()->where('order_type', 1)->where('status', 'delivered')->count(); //delivery agent_delivered
        $completed = Order::shopFinder()->where('order_type', 1)->where('status', 'completed')->count();
        $canceled = Order::shopFinder()->where('order_type', 1)->where('status', 'canceled')->count();
        $archived = Order::shopFinder()->where('order_type', 1)->where('status', 'archived')->count();

        $type = $type;
        $search_order_type = $request->status ?? '';
        return view('seller.orders.index', compact('orders', 'pendings', 'processing', 'pickup', 'completed', 'da_pickup', 'da_delivery', 'archived', 'canceled', 'request', 'type', 'search_order_type'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        $shop_id = current_shop_id();
        if (user_plan_access('pos') == false) {
            $msg = 'This module did not support your subscription.';
            Session::flash('error', $msg);
            return back();
        }

        if (!empty($request->src)) {
            $posts = Product::with('price', 'attributes', 'options', 'stock')->where('title', 'LIKE', '%' . $request->src . '%')->where('shop_id', $shop_id)->where('type', 'product')->where('status', 1)->latest()->paginate(20);
        } else {
            $posts = Product::with('price', 'attributes', 'options', 'stock')->where('shop_id', $shop_id)->where('type', 'product')->where('status', 1)->latest()->paginate(20);
        }
        $src = $request->src ?? '';
        return view('seller.orders.create', compact('posts', 'src'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id = Auth::id();
        $this->user_id = $user_id;
        $option = $request->option ?? [];
        $option = collect($option)->flatten()->sort()->values()->all();
        $shop_id = current_shop_id();

        $product = Product::where('shop_id', $shop_id)->with('price')->where('id', $request->product_id);
        $product = $product->with('variants', function ($q) use ($option) {
            return $q->whereIn('id', $option);
        });
        
        $product = $product->first();
        $rowId = md5($product->id . serialize($option));
        $cart = \Cart::session(getCartSessionKey());
        $cartItem = $cart->get($rowId);

        $cqty = $request->qty ?? 1;
        if (isset($cartItem)) {
            $cqty += $cartItem->quantity;
        }

        if (empty($product)) {
            $error['errors']['error'] = trans('Sorry, product not found.');
            return response()->json($error, 422);
        }

        if (!$product->isStockAvailable($cqty)) {
            $error['errors']['error'] = trans('Sorry, product stock limit is exceeded.');
            return response()->json($error, 422);
        }

        if (!empty($product)) {
            $price = $product->price->selling_price;
            $new_price = $price;
            if ($request->option != null) {
                foreach ($product->variants ?? [] as $row) {
                    if ($row->amount_type == 1) {
                        $new_price += $row->price;
                    } else if ($row->amount_type == 0) {
                        $new_price += ($price * $row->price * 0.01);
                    }
                }
                $options = $product->variants;
            } else {
                $options = [];
            }

            // if ($request->variation != null) {
            //     $attributes = $product->attributes ?? [];
            // } else {
            //     $attributes = [];
            // }

            $price = round($new_price, 2);
            $qty = $request->qty ?? 1;

            $data = [
                'product_id' => $product->id,
                // 'attribute' => $attributes, 
                'options' => $options,
                'image' => $product->image ?? asset('uploads/default.png'),
            ];

            $cart->add(array(
                'id' => $rowId,
                'name' => $product->title,
                'price' => $price,
                'quantity' => $qty,
                'attributes' => $data,
                'associatedModel' => $product
            ));

            if ($product->tax > 0) {
                $saleCondition = new \Darryldecode\Cart\CartCondition(array(
                    'name' => 'tax',
                    'type' => 'tax',
                    'value' => $product->tax_type == 0 ? $product->tax . '%' : $product->tax,
                ));

                $cart->addItemCondition($rowId, $saleCondition);
            }
        }

        return get_cart();
    }

    public function cartRemove($id)
    {
        $cart = \Cart::session(getCartSessionKey());
        $cart->remove($id);
        return back();
    }

    public function apply_coupon(Request $request)
    {
        $request->validate([
            'code' => 'required|max:50',
        ]);

        $coupon = Coupon::byShop()->where('code', $request->code)->first();
        if (empty($coupon)) {
            $error['errors']['error'] = 'Coupon Code Not Found.';
            return response()->json($error, 404);
        }
        $active_code = Coupon::byShop()->isActive()->where('code', $request->code)->first();
        $mydate = Carbon::now()->toDateString();
        if (!empty($coupon)) {
            if ($coupon->expiry_date >= $mydate) {
                $cart = \Cart::session(getCartSessionKey());
                if (Session::has('coupon')) {
                    $cart->removeCartCondition(Session::get('coupon'));
                    Session::forget($coupon->code);
                }
                $condition = new \Darryldecode\Cart\CartCondition(array(
                    'name' => $coupon->code,
                    'type' => 'coupon',
                    'target' => 'subtotal',
                    'value' => '-' . $coupon->amount . '%', // . ($coupon->discount_type == 0 ? $coupon->amount .'%' : $coupon->amount),
                ));

                $cart->condition($condition);

                Session::put('coupon', $coupon->code);

                return response()->json(['data' => view('seller.orders.cart_detail')->render(), 'message' => trans('Coupon Applied')]);
            }
        } else {
            $error['errors']['error'] = trans('Coupon Code Is Inactive');
            return response()->json($error, 401);
        }


        $error['errors']['error'] = trans('Sorry, this coupon is expired');
        return response()->json($error, 401);
    }

    public function make_order(Request $request)
    {
        $shop_id = current_shop_id();
        if ($request->customer_type == 1) {
            $user = Customer::where('shop_id', $shop_id)->where('email', $request->email)->first();
            if (empty($user)) {
                $error['errors']['error'] = trans('Sorry, Customer Not Exist');
                return response()->json($error, 401);
            }
            $customer_id = $user->id;
        } else {
            $customer_id = null;
        }

        if (getCartCount() == 0) {
            return response()->json('Cart empty');
        }

        if ($request->delivery_type == '1') {

            $request->validate([
                'email' => 'required|email_address|max:100|regex:/(.+)@([a-zA-Z0-9-]+)\.(.+)/i|',
                'name' => 'required|max:50',
                'phone' => 'required|integer|digits:10',
                'address' => 'required|max:100',
                'location' => 'required',
                'zip_code' => 'required',
                'shipping_mode' => 'required',
                'payment_method' => 'required',
                'payment_id' => 'required|max:100',
            ],
            [
                'phone.integer' => 'Invalid phone number',
                'phone.digits' => 'Invalid phone number'
            ]);

            $shipping_amount = Category::where('shop_id', $shop_id)->where('type', 'method')->find($request->shipping_mode);
            $location = $request->location;
            $ShippingMethod = ShippingMethod::where('status', 1)
                ->where('shop_id', $shop_id)
                ->where('id', $request->shipping_mode)
                ->whereHas('locations',  function ($query) use ($location) {
                    $query->where('city_id', $location)
						->where('status', 1);
                })
                ->first();

            $cart = get_cart();

            if(Session::has('coupon')) {
                $coupon = Coupon::where('code', Session::get('coupon'))->first();
            }
            $order = new Order;
            $order->transaction_id = $request->payment_id;
            // $order->category_id = $request->payment_method;
            $order->payment_method = $request->payment_method;
            $order->customer_id = $customer_id;
            $order->user_id  = Auth::id();
            $order->shop_id  = $shop_id;
            $order->order_type  = $request->delivery_type;
            $order->payment_status = $request->payment_status;
            $order->status = 'pending';
            $order->tax = $cart['tax'];
            $order->discount = $cart['discount'];
            $order->subtotal = $cart['subtotal'];
            $order->shipping = $this->calculateWeight(amount_to_number($cart['weight']), $ShippingMethod->cost ?? 0);
            $order->total = $this->calculateShipping(amount_to_number($cart['total']),  $ShippingMethod->cost ?? 0, amount_to_number($cart['weight']));
            $order->save();

            $info['name'] = $request->name;
            $info['email'] = $request->email;
            $info['phone'] = $request->phone;
            $info['comment'] = $request->comment;
            $info['address'] = $request->address;
            $info['zip_code'] = $request->zip_code;
            $info['coupon_discount'] = $cart['discount'];
            $info['sub_total'] = $cart['subtotal'];


            $meta = new OrderMeta;
            $meta->order_id = $order->id;
            $meta->key = 'content';
            $meta->value = json_encode($info);
            $meta->save();

            $items = [];

            foreach ($cart['items'] as $key => $row) {
                $options['options'] = $row->attributes->options;

                $variants = [];
                foreach ($row->attributes->options as $key => $option) {
                    $variants[] = $option->id;
                }
                $product = Product::with('price')->where('id', $row->attributes->product_id)->with(['variants' => function($q) use($variants) {
                    $q->whereIn('id', $variants);
                }])->first();

                $discount = 0;
                $tax = 0;
                if(isset($coupon)) {
                    $discount = $row->price *  ( $coupon->amount * 0.01);
                }

                if($product->tax > 0) {
                    $tax = $row->price *  ( $product->tax * 0.01);
                }

                $options['tax'] = $tax * $row->quantity;
                $options['discount'] = $discount * $row->quantity;
                $options['subTotal'] = $row->price * $row->quantity;
                $options['total'] = $options['subTotal'] + $options['tax'] - $options['discount'];
                $options['supplier'] = null;
                $options['reseller_price'] = 0;
                $options['reseller_tax'] = 0;

                $data['order_id'] = $order->id;
                $data['product_id'] = $row->attributes->product_id;
                $data['info'] = json_encode($options);
                $data['qty'] = $row->quantity;
                $data['amount'] = $row->price;

                array_push($items, $data);
                $product = Product::find($row->attributes->product_id);

                if (!$product->isStockAvailable($row->quantity ?? 1)) {
                    $error['errors']['error'] = trans('Sorry, some product are currently not available.');
                    return response()->json($error, 422);
                }
            }

            OrderItem::insert($items);
            $order->reduce_stock();

            $ship['order_id'] = $order->id;
            $ship['location_id'] = $request->location;
            $ship['shipping_id'] = $request->shipping_mode;

            OrderShipping::insert($ship);

            cart_clear();

            return response()->json(['redirect' => route('seller.order.show', $order->id), 'message' => 'Order Created']);
        } else {

            $request->validate([
                'email' => 'required|email_address|max:50',
                'name' => 'required|max:50',
                'payment_method' => 'required',
                'payment_id' => 'required|max:100',
            ]);
            $shop_id = current_shop_id();
            $user = Customer::where('shop_id', $shop_id)->where('email', $request->email)->first();
            if (empty($user)) {
                $error['errors']['error'] = trans('Sorry, Customer Not Exist');
                return response()->json($error, 401);
            }


            $cart = get_cart();
            $order = new Order();
            $order->transaction_id = $request->payment_id;
            // $order->category_id = $request->payment_method;
            $order->payment_method = $request->payment_method;
            $order->customer_id = $customer_id;
            $order->user_id  = Auth::id();
            $order->shop_id  = $shop_id;
            $order->order_type  = $request->delivery_type;
            $order->payment_status = $request->payment_status;
            $order->status = 'pending';
            $order->tax = $cart['tax'];
            $order->total = $cart['total'];
            $order->save();

            $info['name'] = $request->name;
            $info['email'] = $request->email;
            $info['comment'] = $request->comment;
            $info['coupon_discount'] = $cart['discount'];
            $info['sub_total'] = $cart['subtotal'];


            $meta = new OrderMeta;
            $meta->order_id = $order->id;
            $meta->key = 'content';
            $meta->value = json_encode($info);
            $meta->save();

            $items = [];

            foreach ($cart['items'] as $key => $row) {
                $options['options'] = $row->attributes->options;
                $options['itemTax'] = $row->attributes->itemTax;
                $options['taxTotal'] = $row->attributes->taxTotal;
                $options['discount'] = 0;
                $options['subTotal'] = $row->attributes->subTotal;
                $options['total'] = $row->attributes->total;
                $data['order_id'] = $order->id;
                $data['product_id'] = $row->attributes->product_id;
                $data['info'] = json_encode($options);
                $data['qty'] = $row->quantity;
                $data['amount'] = $row->price;
                array_push($items, $data);
            }

            OrderItem::insert($items);
            $order->reduce_stock();
            cart_clear();
            return response()->json(['redirect' => route('seller.order.show', $order->id), 'message' => 'Order Created']);
        }
    }


    public function calculateShipping($total, $shipping_amount, $weight)
    {
        $shipping_amount = (float) $shipping_amount;
        $totalAmount = (float) $total;

        $weight_amount = (float) $this->calculateWeight($weight, $shipping_amount);
        $amount = $totalAmount + $weight_amount;

        return $amount;
    }

    public function calculateWeight($weight, $amount)
    {
        return $amount;
    }
    public function checkout()
    {
        $shop_id = current_shop_id();
        $posts = Gateway::where('shop_id', $shop_id)->where('status', 1)->get();
        return view('seller.orders.checkout', compact('posts'));
    }
    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $shop_id = current_shop_id();
        $info = Order::shopFinder()->with('order_item', 'customer', 'order_content', 'shipping_info', 'gateway', 'agent_avathar_details', 'agent_details')->findorFail($id);
        $order_content = json_decode($info->order_content->value ?? '');
        $agent = Customer::where('id', $info->agent_id)
            ->withCount(['delivery_orders as orders' => function ($q) {
                $q->AgentCurrentOrders();
            }])->first();

        return view('seller.orders.show', compact('info', 'order_content', 'agent'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        $shop_id = current_shop_id();

        $order = Order::shopFinder()->with('order_item_with_stock', 'order_item_with_file', 'customer', 'order_content', 'shipping_info', 'order_stock_meta', 'files', 'gateway', 'agent_details')->findorFail($id);

        if ($request->status) {
            $order->status = $request->status;
        }
        if ($request->payment_status) {
            $order->payment_status = $request->payment_status;
        }

        $order->estimated_delivery_date = $request->estimated_delivery_date;
        // $order->payment_status = $request->payment_status ?? 0;
        $order->save();

        $reseller_order = Order::where('id', $order->group_order_id)->first();
        if (isset($reseller_order) && !empty($reseller_order)) {
            $reseller_order->status = $request->status;
            $reseller_order->save();
        }

        if (in_array($order->status, ['pending', 'processing', 'ready-for-pickup', 'picked_up', 'delivered', 'completed', 'canceled'])) {
            $order->update_stock();
        }

        if ($request->status == 'completed' && current_shop_type() == 'supplier') {
            $order->update_settlement();
        }

        if ($request->status == 'delivered') {
            $location = ShopOption::where('key', 'location')->where('shop_id', $shop_id)->first();
            $store_email = ShopOption::where('key', 'store_email')->where('shop_id', $shop_id)->first();
            $location = json_decode($location->value ?? '');
            $product_file_url = $order->files;

            $order_content = json_decode($order->order_content->value ?? '');
            $data['order'] = $order;
            $data['location'] = $location;
            $data['order_content'] = $order_content;
            $data['url'] = my_url();
            $data['customer_email'] = $order_content->email ?? '';
            $data['admin_email'] = $store_email->value ?? Auth::user()->email;
            $data['product_file_url'] = $product_file_url ?? '';

            Mail::to($data['customer_email'])->send(new OrderDeliveryMail($data));
        }


        if ($request->status == 'picked_up') {

            $location = ShopOption::where('key', 'location')->where('shop_id', $shop_id)->first();
            $store_email = ShopOption::where('key', 'store_email')->where('shop_id', $shop_id)->first();
            $location = json_decode($location->value ?? '');

            $order_content = json_decode($order->order_content->value ?? '');
            $data['order'] = $order;
            $data['location'] = $location;
            $data['order_content'] = $order_content;
            $data['url'] = my_url();
            $data['customer_email'] = $order_content->email ?? '';
            $data['admin_email'] = $store_email->value ?? Auth::user()->email;

            Mail::to($data['customer_email'])->send(new PickedUpOrderMail($data));
        }

        if (!empty($request->mail_notify) && ($request->status != 'delivered') && ($request->status != 'picked_up')) {


            $location = ShopOption::where('key', 'location')->where('shop_id', $shop_id)->first();
            $store_email = ShopOption::where('key', 'store_email')->where('shop_id', $shop_id)->first();
            $location = json_decode($location->value ?? '');
            $product_file_url = $order->files;

            $order_content = json_decode($order->order_content->value ?? '');
            $data['order'] = $order;
            $data['location'] = $location;
            $data['order_content'] = $order_content;
            $data['url'] = my_url();
            $data['customer_email'] = $order_content->email ?? '';
            $data['admin_email'] = $store_email->value ?? Auth::user()->email;
            $data['product_file_url'] = $product_file_url ?? '';
            if (!empty($order_content->email)) {
                if (env('QUEUE_MAIL') == 'on') {
                    dispatch(new \App\Jobs\SendInvoiceEmail($data));
                } else {
                    Mail::to($data['customer_email'])->send(new CustomerOrderMail($data));
                }
            }
        }

        return response()->json([trans('Order Updated')]);
    }

    public function invoice($id)
    {
        $shop_id = current_shop_id();
        $order = Order::shopFinder()->with('order_item', 'customer', 'order_content', 'shipping_info')->findorFail($id);

        $location = \App\Models\ShopOption::where('key', 'location')->where('shop_id', $shop_id)->first();
        $location = json_decode($location->value ?? '');

        $order_content = json_decode($order->order_content->value ?? '');

        $gst = \App\Models\ShopOption::where('key', 'gst')->where('shop_id', $shop_id)->first();
        $gst = $gst->value ?? '';

        $pdf = \PDF::loadView('email.invoice', compact('order', 'order_content', 'location', 'gst'));
        return $pdf->download('invoice.pdf');
    }
    public function invoice_receipt($id)
    {
        $shop_id = current_shop_id();
        $order = Order::shopFinder()->with('order_item', 'customer', 'order_content', 'shipping_info')->findorFail($id);

        $location = \App\Models\ShopOption::where('key', 'location')->where('shop_id', $shop_id)->first();
        $location = json_decode($location->value ?? '');

        $order_content = json_decode($order->order_content->value ?? '');

        $gst = \App\Models\ShopOption::where('key', 'gst')->where('shop_id', $shop_id)->first();
        $gst = $gst->value ?? '';

        // $pdf = \PDF::loadView('email.invoice_receipt', compact('order', 'order_content', 'location', 'gst'))->setPaper(array(0, 0, 396, 612), 'potrait');
        // return $pdf->download('receipt.pdf');
        return view('email.invoice_receipt', compact('order', 'order_content', 'location', 'gst'));
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $shop_id = current_shop_id();

        // Order Delivered Mail
        if (!empty($request->ids)) {

            if ($request->method == 'delivered') {

                foreach ($request->ids as $id) {
                    $find_order = Order::shopFinder()->findorFail($id);
                    $find_order->status = $request->method;
                    $find_order->save();

                    $order = Order::shopFinder()->with('order_item_with_stock', 'order_item_with_file', 'customer', 'order_content', 'shipping_info', 'order_stock_meta', 'files', 'gateway', 'agent_details')->findorFail($id);
                    $location = ShopOption::where('key', 'location')->where('shop_id', $shop_id)->first();
                    $store_email = ShopOption::where('key', 'store_email')->where('shop_id', $shop_id)->first();
                    $location = json_decode($location->value ?? '');
                    $product_file_url = $order->files;

                    $order_content = json_decode($order->order_content->value ?? '');
                    $data['order'] = $order;
                    $data['location'] = $location;
                    $data['order_content'] = $order_content;
                    $data['url'] = my_url();
                    $data['customer_email'] = $order_content->email ?? '';
                    $data['admin_email'] = $store_email->value ?? Auth::user()->email;
                    $data['product_file_url'] = $product_file_url ?? '';

                    Mail::to($data['customer_email'])->send(new OrderDeliveryMail($data));
                }
            }

            //Order Picked Up Mail
            if ($request->method == 'picked_up') {


                foreach ($request->ids as $id) {
                    $find_order = Order::shopFinder()->findorFail($id);
                    $find_order->status = $request->method;
                    $find_order->save();

                    $order = Order::shopFinder()->with('order_item_with_stock', 'order_item_with_file', 'customer', 'order_content', 'shipping_info', 'order_stock_meta', 'files', 'gateway', 'agent_details')->findorFail($id);
                    $location = ShopOption::where('key', 'location')->where('shop_id', $shop_id)->first();
                    $store_email = ShopOption::where('key', 'store_email')->where('shop_id', $shop_id)->first();
                    $location = json_decode($location->value ?? '');

                    $order_content = json_decode($order->order_content->value ?? '');
                    $data['order'] = $order;
                    $data['location'] = $location;
                    $data['order_content'] = $order_content;
                    $data['url'] = my_url();
                    $data['customer_email'] = $order_content->email ?? '';
                    $data['admin_email'] = $store_email->value ?? Auth::user()->email;

                    Mail::to($data['customer_email'])->send(new PickedUpOrderMail($data));
                }
            }

            //Order Common mail 
            if (($request->method != 'delivered') && ($request->method != 'picked_up')) {

                foreach ($request->ids as $id) {
                    $find_order = Order::shopFinder()->findorFail($id);
                    $find_order->status = $request->method;
                    $find_order->save();

                    $order = Order::shopFinder()->with('order_item_with_stock', 'order_item_with_file', 'customer', 'order_content', 'shipping_info', 'order_stock_meta', 'files', 'gateway', 'agent_details')->findorFail($id);
                    $location = ShopOption::where('key', 'location')->where('shop_id', $shop_id)->first();
                    $store_email = ShopOption::where('key', 'store_email')->where('shop_id', $shop_id)->first();
                    $location = json_decode($location->value ?? '');
                    $product_file_url = $order->files;

                    $order_content = json_decode($order->order_content->value ?? '');
                    $data['order'] = $order;
                    $data['location'] = $location;
                    $data['order_content'] = $order_content;
                    $data['url'] = my_url();
                    $data['customer_email'] = $order_content->email ?? '';
                    $data['admin_email'] = $store_email->value ?? Auth::user()->email;
                    $data['product_file_url'] = $product_file_url ?? '';
                    if (!empty($order_content->email)) {
                        if (env('QUEUE_MAIL') == 'on') {
                            dispatch(new \App\Jobs\SendInvoiceEmail($data));
                        } else {
                            Mail::to($data['customer_email'])->send(new CustomerOrderMail($data));
                        }
                    }
                }
            }


            if ($request->method == 'delete') {
                if ($request->ids) {
                    foreach ($request->ids as $id) {
                        $order = Order::shopFinder()->findorFail($id);
                        $order->delete();
                    }
                }
            }
        } else {

            return response()->json(['errors' => ['order id not selected']], 400);
        }


        // else {
        //     if ($request->ids) {
        //         foreach ($request->ids as $id) {
        //             $order = Order::shopFinder()->findorFail($id);
        //             $order->status = $request->method;
        //             $order->save();
        //         }
        //     } else {
        //         return response()->json(['errors' => ['order id not selected']], 400);
        //     }
        // }

        return response()->json([trans('Success')]);
    }

    public function delivery_agent($id)
    {
        $shop_id = current_shop_id();
        $info = Order::shopFinder()->with('order_item', 'customer', 'order_content', 'shipping_info', 'gateway')->findorFail($id);
        $order_content = json_decode($info->order_content->value ?? '');


        return view('seller.orders.delivery_agent', compact('info', 'order_content'));
    }

    public function search_delivery_agents(Request $request)
    {

        $shop_id = current_shop_id();


        $agents = Customer::where('shop_id', $shop_id)
            ->with('search_agent_avathar')
            ->whereHas('delivery_agent')
            ->withCount(['delivery_orders as orders' => function ($q) {
                $q->AgentCurrentOrders();
            }]);


        if ($request->has('q')) {
            $agents = $agents->where(function ($q) use ($request) {

                $q->where('first_name', 'LIKE', '%' . $request->q . '%')
                    ->orWhere('last_name', 'LIKE', '%' . $request->q . '%')
                    ->orWhere('email', 'LIKE', '%' . $request->q . '%');
            });
        }



        $agents = $agents->limit(10)->get();

        return response()->json($agents);
    }
    //Add Delivery Agent Module

    public function delivery_agent_update(Request $request, $id)
    {

        $validatedData = $request->validate([
            'agents' => 'required',


        ]);


        $shop_id = current_shop_id();

        $order = Order::shopFinder()->findorFail($id);
        $order->agent_id = $request->agents;
        $order->save();

        $customer = Customer::where('shop_id', $shop_id)->where('id', $order->agent_id)->first();

        if (isset($order->agent_id) && isset($customer) && $order->status == 'ready-for-pickup') {
            $message = [
                'title' => 'New order assigned',
                'message' => $order->order_no . ' order assigned to you.',
                'order_id' => $order->id,
                'url' => '',
            ];
            Notification::send($customer, new SellerNewOrderNotification($message));
        }


        return response()->json([trans('Delivery Agent Added Successfully')]);
        return back();
    }
    public function plan_invoice($id)
    {
        $info = Subscription::with('plan_info', 'PaymentMethod', 'user', 'shop')->findorFail($id);
        $shop = Shop::with(['domain', 'user'])->find($info->shop->id);
        $company_info = \App\Models\Option::where('key', 'company_info')->first();
        $company_info = json_decode($company_info->value);
        $gst = Option::where('key', 'gst')->first();
        $company_info->gst = $gst ? $gst->value : null;
        $pdf = \PDF::loadView('email.subscription_invoicepdf', compact('company_info', 'info', 'shop'));
        return $pdf->download('invoice.pdf');
    }
}
