<?php

namespace App\Http\Controllers\Seller;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Option;
use App\Models\OrderMeta;
use App\Models\ShopOption;
use App\Models\Settlement;
use Illuminate\Http\Request;
use App\Models\OrderSettlement;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class SettlementController extends Controller
{
    protected $request;

    public function index(Request $request)
    {
        $type = $request->status ?? 'all';
        $shop_id = current_shop_id();
        // $posts = Settlement::with('orders', 'user')->where('shop_id', $shop_id)->latest()->paginate(40);
        $prev_date = Carbon::now()->subDays(2);
        $unsettlement_amount = OrderSettlement::whereHas('order', function ($query) {
            $query->where('status', 'completed')
                ->where('payment_status', '1')
                ->where('order_type', 1);
        })
        ->where('shop_id', $shop_id)
        ->whereNull('settlement_id')
        ->sum('total');

        if (!empty($request->status)) {
            $posts = Settlement::with('orders', 'user')->where('shop_id', $shop_id)->where('status', $request->status)->latest()->paginate(40);
        }

        if( $type == "all"){
            $posts = Settlement::with('orders', 'user')->where('shop_id', $shop_id)->latest()->paginate(40);
        }

        if (!empty($request->start)  && !empty($request->end)) {

            $start = date("Y-m-d", strtotime($request->start));
            $end = date("Y-m-d", strtotime($request->end));
            
            if (!empty($request->status)) {
                $posts =  Settlement::with('orders', 'user')->whereDate('created_at', '>=' ,$start)->whereDate('created_at', '<=' ,$end)->where('status', $request->status)->where('shop_id', $shop_id)->latest()->paginate(40); 
            }
            else{
                $posts =  Settlement::with('orders', 'user')->whereDate('created_at', '>=' ,$start)->whereDate('created_at', '<=' ,$end)->where('shop_id', $shop_id)->latest()->paginate(40); 
                // $posts =  Settlement::with('orders', 'user')->whereDate('created_at', [$start, $end])->where('shop_id', $shop_id)->latest()->paginate(40); 
            }
            
        }
        

        $total_earn = Settlement::where('status', 'paid')->where('shop_id', current_shop_id())->sum('amount');
        $option = Option::where('key', 'reseller_threshold_amount')->first();
        $threshold = $option ? $option->value : 10000;
        return view('seller.settlements.index', compact('posts', 'total_earn', 'request', 'type', 'threshold', 'unsettlement_amount'));
    }

    public function show(Request $request, $id)
    {
        $info = Settlement::with('orders')->findorFail($id);

        $amount = Settlement::where('paid_at', 'paid')->sum('amount');
        $unpaid_amount = Settlement::where('paid_at', 'unpaid')->sum('amount');
        $shop_id = current_shop_id();
        $bank_details = ShopOption::where('shop_id', $shop_id)->where('key', 'bank_details')->first();
        if ($bank_details) {
            $accountInfo = json_decode($bank_details->value, true);
        } else {
            return back()->with('error', 'Please update your bank details.');
        }

        $orders = Order::with('shop')->withSum(['order_settlement' => function ($q) use ($info) {
            $q->where('shop_id', $info->shop_id)
            ->where('settlement_id', $info->id);
        }], 'total')
        ->whereHas('order_settlement', function ($q) use ($info) {
            $q->where('shop_id', $info->shop_id)
            ->where('settlement_id', $info->id);
        })
        ->with(['supplier_settlement', 'reseller_settlement'])
        ->withCount('order_items')
        ->latest()->paginate(10);

        return view('seller.settlements.show', compact('info', 'amount', 'unpaid_amount', 'accountInfo', 'orders', 'request'));
    }

    public function edit($id)
    {
        $info = Shop::where('user_id', Auth::id())->findorFail($id);
        return view('seller.shop.edit', compact('info'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        return back()->withSuccess('Settlement Updated Successfully');
    }

    public function create()
    {
        $shop_id = current_shop_id();
        $amount = 0;
        $start_date = Carbon::now();
        $end_date = Carbon::now();

        $amount = OrderSettlement::whereHas('order', function ($query) {
            $query->where('status', 'completed')
                ->where('payment_status', '1')
                ->where('order_type', 1);
        })
        ->where('shop_id', $shop_id)
        ->whereNull('settlement_id')
        ->sum('total');

        if ($amount <= 0) {
            return back()->with('error', 'No new settlements found.');
        }

        $option = Option::where('key', 'reseller_threshold_amount')->first();
        $threshold = $option ? $option->value : 10000;

        $shop_settlement = Settlement::where('status', 'unpaid')->where('shop_id', current_shop_id())->get();

        if ($amount <= 0 || $threshold > $amount) {
            return back()->with('error', 'Please Check Your Threshold Value.');
        }
        if ($shop_settlement->isEmpty()) {

            $shop = Shop::find($shop_id);

            if (isset($shop)) {

                $tax = Option::where('key', 'settlement_tax')->first();

                $accountInfo = [];
                $bank_details = ShopOption::where('shop_id', $shop->id)->where('key', 'bank_details')->first();
                $bank_details = json_decode($bank_details->value);
                if (empty($bank_details)) {
                    $accountInfo['account_holder_name'] = $bank_details->account_holder_name;
                    $accountInfo['ifsc_code'] = $bank_details->ifsc_code;
                    $accountInfo['account_no'] = $bank_details->account_no;
                }

                $commission_tax = $amount * (($tax->value ?? 0) * 0.01);
                $settlement = new Settlement();
                $settlement->amount = $amount - $commission_tax;
                $settlement->tax = $commission_tax;
                $settlement->total_amount = $amount;
                $settlement->charge = 0;
                $settlement->settlement_rate = 0;
                $settlement->start_date = $start_date;
                $settlement->end_date = $end_date;
                $settlement->status = 'unpaid';
                $settlement->bank_details = $accountInfo;
                $settlement->settlement_date = Carbon::now();
                $settlement->is_request = 0;
                $settlement->paid_at = null;
                $settlement->interval = "";
                $settlement->user_id = $shop->user_id;
                $settlement->shop_id = $shop->id;
                $settlement->save();

                OrderSettlement::whereHas('order', function ($query) {
                    $query->where('status', 'completed')
                        ->where('payment_status', '1')
                        ->where('order_type', 1);
                })
                ->where('shop_id', $shop->id)
                ->whereNull('settlement_id')
                ->update(['settlement_id' => $settlement->id]);

                return back()->with('success', 'Settlement created Successfully');
            }
        } else {
            return back()->with('error', 'Settlement already requested.');
        }
    }
}
