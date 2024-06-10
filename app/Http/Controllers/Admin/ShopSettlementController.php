<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Order;
use App\Models\Settlement;
use App\Rules\IsValidDomain;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class ShopSettlementController extends Controller
{
    protected $request;

    public function index(Request $request)
    {
        // if (!Auth()->user()->can('shop.list')) {
        //     return abort(401);
        // }
        $type = $request->type ?? 'all';

        $posts = Settlement::whereNotNull('shop_id')->with('orders', 'user', 'shop')->latest()->paginate(40);

        return view('admin.shop.settlements.index', compact('posts'));
    }

    public function show($id)
    {
        if (!Auth()->user()->can('shop.view')) {
            return abort(401);
        }

        $info = Settlement::with(['user', 'shop'])->findorFail($id);

        $amount = Settlement::where('status', 'paid')->where('user_id', $info->user_id)->sum('amount');
        $unpaid_amount = Settlement::where('status', 'unpaid')->where('user_id', $info->user_id)->sum('amount');

        $histories = Order::with('shop')->withSum(['order_settlement' => function ($q) use ($info) {
            $q->where('shop_id', $info->shop_id)
            ->where('settlement_id', $info->id);
        }], 'total')
        ->whereHas('order_settlement', function ($q) use ($info) {
            $q->where('shop_id', $info->shop_id)
            ->where('settlement_id', $info->id);
        })
        ->latest()->paginate(40);

        return view('admin.shop.settlements.show', compact('info', 'histories', 'amount', 'unpaid_amount'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'status' => 'required',
        ]);

        $shop = Settlement::findorFail($id);
        $shop->status = $request->status ?? 'unpaid';
        if ($request->status == 'paid')
            $shop->paid_at = Carbon::now();
        $shop->save();

        return back()->withSuccess('Settlement Updated Successfully');
    }
}
