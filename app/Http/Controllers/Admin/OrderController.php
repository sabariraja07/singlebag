<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Plan;
use App\Models\Order;
use App\Models\Option;
use App\Models\Product;
use App\Models\Customer;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Mail\SubscriptionMail;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;
use App\Models\PaymentMethod;

class OrderController extends Controller
{

    protected $user_email;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        if (!Auth()->user()->can('order.list')) {
            return abort(401);
        }


        $type = $request->status ?? 5;
        $posts = Subscription::whereNotNull('shop_id');

        if ($type != 5) {
            if ($type == 3) {
                $posts = $posts->whereDate('will_expire', '<', Carbon::now());
            } else {
                $posts = $posts->where('status', $type);
            }
        }

        if (!empty($request->src) && $request->term == 'email') {
            $this->user_email = $request->src;
            $posts = $posts->whereHas('user', function ($q) {
                return $q->where('email', $this->user_email);
            });
        }

        if (!empty($request->src) && $request->term != 'email') {
            $posts = $posts->where($request->term, $request->src);
        }

        $posts = $posts->with('user', 'plan_info', 'PaymentMethod', 'shop')->latest()->paginate(40);

        $all = Subscription::whereNotNull('shop_id')->count();
        $pending = Subscription::whereNotNull('shop_id')->where('status', '2')->count();
        $paid = Subscription::whereNotNull('shop_id')->where('status', '1')->count();
        $expired = Subscription::whereNotNull('shop_id')->whereDate('will_expire', '<', Carbon::now())->count();
        $cancelled = Subscription::whereNotNull('shop_id')->where('status', '0')->count();

        return view('admin.order.index', compact('type', 'posts', 'request', 'cancelled', 'all', 'pending', 'expired', 'paid'));
    }


    public function licences(Request $request)
    {
        if (!Auth()->user()->can('order.list')) {
            return abort(401);
        }

        $type = $request->status ?? 'all';
        if ($request->status == 'cancelled') {
            $type = 0;
        }


        // $posts = Plan::whereHas('subscriptions', function ($q) use ($request, $type) {
        //     $q->whereNull('shop_id');

        //     if (!empty($request->src) && $request->term == 'email') {
        //         $this->user_email = $request->src;
        //         $q->where('email', $this->user_email);
        //     }

        //     if (!empty($request->src)) {
        //         $q->where($request->term, $request->src);
        //     }

        //     if ($type != 'all') {
        //         $q->where('status', $type);
        //     }
        // })->withCount(['subscriptions' => function ($q) use ($request, $type){
        //     $q->whereNull('shop_id');

        //     if (!empty($request->src) && $request->term == 'email') {
        //         $this->user_email = $request->src;
        //         $q->where('email', $this->user_email);
        //     }

        //     if (!empty($request->src)) {
        //         $q->where($request->term, $request->src);
        //     }

        //     if ($type != 'all') {
        //         $q->where('status', $type);
        //     }

        // }])->with(['subscriptions'=> function ($q) use ($request, $type){
        //     $q->whereNull('shop_id');

        //     if (!empty($request->src) && $request->term == 'email') {
        //         $this->user_email = $request->src;
        //         $q->where('email', $this->user_email);
        //     }

        //     if (!empty($request->src)) {
        //         $q->where($request->term, $request->src);
        //     }

        //     if ($type != 'all') {
        //         $q->where('status', $type);
        //     }
        // }, 'subscriptions.user']);

        $posts = Plan::select(DB::raw('plans.id as id, plans.name as plan_name,count(subscriptions.id) as subscription_count, sum(subscriptions.amount) as amount, sum(subscriptions.tax) as tax, sum(subscriptions.discount) as discount , users.email as email, CONCAT(first_name, " " , last_name) AS fullname'))
            ->join("subscriptions", function ($join) use ($request) {
                $join->on('subscriptions.plan_id', '=', 'plans.id')
                    ->whereNull('shop_id');

                // if (!empty($request->src) && $request->term == 'email') {
                //     $this->user_email = $request->src;
                //     $join->where('users.email', $this->user_email);
                // }

                if (!empty($request->src)) {
                    if ($request->term == 'plans.name')
                        $join->where($request->term, 'like', '%' . $request->src . '%');
                }

                // if ($type != 'all') {
                //     $join->where('subscriptions.status', $type);
                // }
            })
            ->join("users", function ($join) use ($request) {
                $join->on("subscriptions.user_id", "=", "users.id");
                if (!empty($request->src)) {
                    if ($request->term == 'users.email' || $request->term == 'users.first_name'  || $request->term == 'users.last_name')
                        $join->where($request->term, 'like', '%' . $request->src . '%');
                }
            })
            ->orderBy('subscriptions.created_at', 'desc')
            ->groupBy(['subscriptions.plan_id', 'subscriptions.user_id'])
            ->paginate(40);

        // return $posts;

        return view('admin.order.licences', compact('type', 'posts', 'request'));
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Auth()->user()->can('order.create')) {
            return abort(401);
        }
        $payment_gateway = PaymentMethod::where('status', 1)->get();
        $posts = Plan::where('status', 1)->get();
        $email = $request->email ?? '';
        $shop = Shop::where('email', $email)->first();
        return view('admin.order.create', compact('posts', 'email', 'shop', 'payment_gateway'));
    }

    public function store_plans($id)
    {
        if (!Auth()->user()->can('order.create')) {
            return abort(401);
        }

        $payment_gateway = PaymentMethod::where('status', 1)->get();
        $posts = Plan::where('status', 1)->get();
        $shop = Shop::findorFail($id);
        return view('admin.order.create', compact('posts', 'shop', 'payment_gateway'));
    }

    public function get_plan_discount(Request $request, $id)
    {
        $shop = Shop::findorFail($id);
        $plan = Plan::findorFail($request->plan_id);
        $discount = $shop->plan_discount();

        $tax = Option::where('key', 'tax')->first();
        $tax = round((($plan->price - $discount ?? 0) * 0.01) * $tax->value, 2);
        $amount = round($plan->price + $tax - $discount, 2);
        return response()->json(['amount' => $amount, 'discount' => $discount, 'tax' => $tax, 'plan' => $plan]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth()->user()->can('order.create')) {
            return abort(401);
        }

        $validatedData = $request->validate([
            'shop_id' => 'required',
            'payment_method' => 'required',
            'transition_id' => 'required',
            'plan' => 'required',
            'notification_status' => 'required',

        ]);

        if ($request->notification_status == 'yes' && $request->content == null) {
            $msg['errors']['email_comment'] = 'Email Comment Is Required';
            return response()->json($msg, 401);
        }
        $shop = Shop::find($request->shop_id);
        if (empty($shop)) {
            $msg['errors']['user'] = 'Shop Not Found';
            return response()->json($msg, 401);
        }


        $plan = Plan::findorFail($request->plan);
        $exp_days =  $plan->days;
        $expiry_date = \Carbon\Carbon::now()->addDays(($exp_days - 1))->format('Y-m-d');

        $max_order = Subscription::max('id');
        $order_prefix = Option::where('key', 'order_prefix')->first();
        $tax = Option::where('key', 'tax')->first();

        $discount = $shop->plan_discount();
        $tax = round((($plan->price - $discount) * 0.01) * $tax->value, 2);
        $order_no = $order_prefix->value . $max_order;

        $amount = round($plan->price + $tax - $discount, 2);
        // $final_amount = round($amount + $tax , 2);

        $product_count = json_decode($plan->data);
        $current_plan_product_limit = $product_count->product_limit;
        $total_product_count = Product::where('shop_id', $shop->id)->where('type', 'product')->count();

        if (isset($current_plan_product_limit) && !empty($current_plan_product_limit)) {
            if ($current_plan_product_limit == -1) {
            } elseif ($current_plan_product_limit >= $total_product_count) {
            } else {
                $msg['errors']['shop'] = 'Your product limit is exceeding the selected plan. Check with other plans.';
                return response()->json($msg, 401);
            }
        }

        if ($amount <= 0) {
            $msg['errors']['shop'] = 'You are not able to downgrade existing plan.';
            return response()->json($msg, 401);
        } else {
            $order = new Subscription();
            $order->order_no = $order_no;
            $order->amount = $amount;
            $order->discount = $discount;
            $order->tax = $tax;
            $order->trx = $request->transition_id;
            $order->will_expire = $expiry_date;
            $order->shop_id = $shop->id;
            $order->user_id = Auth::id();
            $order->plan_id = $plan->id;
            // $order->category_id = $request->payment_method;
            $order->payment_method = $request->payment_method;
            $order->payment_status = 1;
            $order->status = 1;
            $order->save();

            $shop->data = $plan->data;
            $shop->subscription_id = $order->id;
            $shop->will_expire = $expiry_date;
            $shop->is_trial = 0;
            $shop->save();
        }





        if ($request->notification_status == 'yes') {
            $data['info'] = Subscription::with('plan_info', 'PaymentMethod', 'user')->find($order->id);
            $data['comment'] = $request->content;
            $data['to_vendor'] = 'vendor';
            $gst = Option::where('key', 'gst')->first();
            $data['gst'] = $gst ? $gst->value : null;
            if (env('QUEUE_MAIL') == 'on') {
                dispatch(new \App\Jobs\SendInvoiceEmail($data));
            } else {
                Mail::to($shop->email)->send(new SubscriptionMail($data));
            }
        }

        return response()->json(['Order Created Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth()->user()->can('order.view')) {
            return abort(401);
        }
        $info = Subscription::with('plan_info', 'PaymentMethod', 'user', 'shop')->findorFail($id);
        $shop = Shop::with(['domain', 'user'])->find($info->shop->id);

        return view('admin.order.show', compact('info', 'shop'));
    }

    public function shows($shop_id, $id)
    {
        $info = Order::where('shop_id', $shop_id)->with('order_item', 'customer', 'order_content', 'shipping_info', 'gateway', 'agent_avathar_details', 'agent_details')->findorFail($id);
        $order_content = json_decode($info->order_content->value ?? '');
        $agent = Customer::where('id', $info->agent_id)
            ->withCount(['delivery_orders as orders' => function ($q) {
                $q->AgentCurrentOrders();
            }])->first();
        return view('admin.orders.show', compact('info', 'order_content', 'agent'));
    }

    /**
     * print invoice the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function invoice($id)
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

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth()->user()->can('order.edit')) {
            return abort(401);
        }

        $info = Subscription::find($id);
        $payment_gateway = PaymentMethod::where('status', 1)->get();
        $posts = Plan::get();

        return view('admin.order.edit', compact('posts', 'info', 'payment_gateway'));
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
        if ($request->notification_status == 'yes' && $request->content == null) {
            $msg['errors']['email_comment'] = 'Email Comment Is Required';
            return response()->json($msg, 401);
        }

        DB::beginTransaction();
        try {

            $order = Subscription::findorFail($id);
            $order->plan_id = $request->plan;
            $order->order_no = $request->order_no;
            $order->amount = $request->amount;
            $order->discount = $request->discount;
            $order->tax = $request->tax;
            $order->trx = $request->trx;
            $order->status = $request->order_status;
            // $order->category_id = $request->category_id;
            $order->payment_method = $request->payment_method;
            $order->payment_status = $request->payment_status;
            $order->save();

            $shop = Shop::with('user')->find($order->shop_id);

            if ($request->subscription_status == 1) {
                $plan = Plan::find($order->plan_id);

                $exp_days =  $plan->days;
                $expiry_date = \Carbon\Carbon::now()->addDays(($exp_days - 1))->format('Y-m-d');

                $shop->data = $plan->data;
                $shop->subscription_id = $order->id;
                $shop->will_expire = $expiry_date;
                $shop->is_trial = 0;
                $shop->save();
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollback();
        }

        if ($request->notification_status == 'yes') {
            $data['info'] = Subscription::with('plan_info', 'PaymentMethod', 'shop')->find($order->id);
            $data['comment'] = $request->content;
            $data['to_vendor'] = 'vendor';
            if (env('QUEUE_MAIL') == 'on') {
                dispatch(new \App\Jobs\SendInvoiceEmail($data));
            } else {
                Mail::to($shop->email)->send(new SubscriptionMail($data));
            }
        }

        return response()->json(['Order Updated']);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!Auth()->user()->can('order.delete')) {
            return abort(401);
        }

        if ($request->ids && !empty($request->method)) {
            if ($request->method == 'delete') {
                foreach ($request->ids as $key => $id) {
                    $order = Subscription::find($id);
                    $order->delete();
                }
            } else {
                if ($request->method == 'cancelled') {
                    $status = 0;
                } else {
                    $status = $request->method;
                }
                foreach ($request->ids as $key => $id) {
                    $order = Subscription::find($id);
                    $order->status = $status;
                    $order->save();
                }
            }
        }

        return response()->json(['Success']);
    }
}
