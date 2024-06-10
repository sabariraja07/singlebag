<?php

namespace App\Http\Controllers\Partner;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Models\Subscription;
use App\Models\SubscriptionMeta;
use App\Models\User;
use App\Models\Plan;
use App\Models\Transaction;
use App\Models\Domain;
use App\Mail\SubscriptionMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Models\Option;
use App\Models\PaymentMethod;
use App\Models\Shop;

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
        if (!Auth()->user()->isPartner()) {
            return abort(401);
        }

        $type = $request->status ?? 'all';
        if ($request->status == 'cancelled') {
            $type = 0;
        }

 

        if (!empty($request->src) && $request->term == 'email') {
            $this->user_email = $request->src;
            if ($type === 'all') {
                $posts = Subscription::whereHas('Shop', function ($q) {
                    return $q->where('email', $this->user_email);
                });
            } else {
                $posts = Subscription::whereHas('Shop', function ($q) {
                    return $q->where('email', $this->user_email);
                })->where('status', $type);
            }
        } elseif (!empty($request->src)) {
            if ($type === 'all') {
                $posts = Subscription::where($request->term, $request->src);
            } else {
                $posts = Subscription::where($request->term, $request->src)->where('status', $type);
            }
        } else {
            if ($type === 'all') {
                $posts = new Subscription();
            } else {
                $posts = Subscription::where('status', $type);
            }
        }

        $posts = $posts->with('user', 'plan_info', 'PaymentMethod', 'shop')->whereHas('shop', function($q) {
            $q->where('created_by' , Auth::id());
        })->latest()->paginate(40);

        return view('partner.order.index', compact('type', 'posts', 'request'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {
        if (!Auth()->user()->isPartner()) {
            return abort(401);
        }
        $payment_gateway = PaymentMethod::where('status', 1)->get();
        $posts = Plan::where('status', 1)->where('is_trial', 0)->get();
        $email = $request->email ?? '';
        $shop = Shop::where('created_by' , Auth::id())->findorFail($request->shop_id);
        return view('partner.order.create', compact('posts', 'email', 'shop', 'payment_gateway'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if (!Auth()->user()->isPartner()) {
            return abort(401);
        }

        $validatedData = $request->validate([
            'email' => 'required|email_address|max:255',
            'payment_method' => 'required',
            'transition_id' => 'required',
            'plan' => 'required',
            'notification_status' => 'required',

        ]);

        if ($request->notification_status == 'yes' && $request->content == null) {
            $msg['errors']['email_comment'] = 'Email Comment Is Required';
            return response()->json($msg, 401);
        }
        $shop = Shop::where('email', $request->email)->first();
        if (empty($shop)) {
            $msg['errors']['user'] = 'Shop Not Found';
            return response()->json($msg, 401);
        }


        $plan = Plan::findorFail($request->plan);
        $exp_days =  $plan->days;
        $expiry_date = \Carbon\Carbon::now()->addDays(($exp_days-1))->format('Y-m-d');

        $max_order = Subscription::max('id');
        $order_prefix = Option::where('key', 'order_prefix')->first();
        $tax = Option::where('key', 'tax')->first();
        $tax = ($plan->price * 0.01) * $tax->value;
        $order_no = $order_prefix->value . $max_order;

        $order = new Subscription();
        $order->order_no = $order_no;
        $order->amount = $plan->price;
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




        if ($request->notification_status == 'yes') {
            $data['info'] = Subscription::with('plan_info', 'PaymentMethod', 'user')->find($order->id);
            $data['comment'] = $request->content;
            $data['to_vendor'] = 'vendor';
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
    public function show_old($id)
    {
        if (!Auth()->user()->isPartner()) {
            return abort(401);
        }
        $info = Subscription::whereHas('shop', function($q) {
            $q->where('created_by' , Auth::id());
        })->with('plan_info', 'PaymentMethod', 'user', 'shop')->findorFail($id);
        $shop = Shop::with(['domain', 'user'])->where('created_by' , Auth::id())->findorFail($info->shop->id);

        return view('partner.order.show', compact('info', 'shop'));
    }

    public function show($id)
    {
        if (!Auth()->user()->isPartner()) {
            return abort(401);
        }
        $info = Subscription::whereHas('shop', function($q) {
            $q->where('created_by' , Auth::id());
        })->with('plan_info', 'PaymentMethod', 'user', 'shop')->findorFail($id);
        $shop = Shop::with(['domain', 'user'])->where('created_by' , Auth::id())->findorFail($info->shop->id);

        return view('partner.order.show', compact('info', 'shop'));
    }

    /**
     * print invoice the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function invoice($id)
    {
        $info = Subscription::whereHas('shop', function($q) {
            $q->where('created_by' , Auth::id());
        })->with('plan_info', 'PaymentMethod', 'user', 'shop')->findorFail($id);
        $shop = Shop::with(['domain', 'user'])->where('created_by' , Auth::id())->findorFail($info->shop->id);
        $company_info = \App\Models\Option::where('key', 'company_info')->first();
        $company_info = json_decode($company_info->value);


        $gst = Option::where('key', 'gst')->first();
        $company_info->gst = $gst ? $gst->value :null;
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
        if (!Auth()->user()->isPartner()) {
            return abort(401);
        }

        $info = Subscription::whereHas('shop', function($q) {
            $q->where('created_by' , Auth::id());
        })->where('user_id' , Auth::id())->findorFail($id);
        $payment_gateway = PaymentMethod::where('status', 1)->get();
        $posts = Plan::where('is_trial', 1)->get();

        return view('partner.order.edit', compact('posts', 'info', 'payment_gateway'));
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
                $expiry_date = \Carbon\Carbon::now()->addDays(($exp_days-1))->format('Y-m-d');

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
        if (!Auth()->user()->isPartner()) {
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
