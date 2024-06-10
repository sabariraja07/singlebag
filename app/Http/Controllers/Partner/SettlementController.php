<?php

namespace App\Http\Controllers\Partner;

use App\Models\Shop;
use App\Models\Option;
use App\Models\Partner;
use App\Models\Settlement;
use Illuminate\Http\Request;
use App\Models\Subscription;
use App\Http\Controllers\Controller;
use Carbon\Carbon;
use Illuminate\Support\Facades\Auth;

class SettlementController extends Controller
{
    protected $request;
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
        $type = $request->type ?? 'all';

        $posts = Settlement::with('subscriptions', 'user')->where('user_id', Auth::id())->latest()->paginate(40);

        $partner_data = Partner::where('user_id', Auth::id())->first();

        $unsettlement_amount = Subscription::where('user_id', Auth::id())->whereNull('settlement_id')->sum('commission');

        return view('partner.settlement.index', compact('posts', 'partner_data', 'unsettlement_amount'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth()->user()->isPartner()) {
            return abort(401);
        }

        $info = Settlement::with('user')->where('user_id', Auth::id())->findorFail($id);

        $amount = Settlement::where('status', 'paid')->where('user_id', Auth::id())->sum('amount');
        $unpaid_amount = Settlement::where('status', 'unpaid')->where('user_id', Auth::id())->sum('amount');

        $histories = Subscription::with('plan', 'PaymentMethod')->where('settlement_id', $info->id)->latest()->paginate(40);

        return view('partner.settlement.show', compact('info', 'histories', 'amount', 'unpaid_amount'));
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

        $info = Shop::where('user_id', Auth::id())->findorFail($id);
        return view('partner.shop.edit', compact('info'));
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
        $request->validate([
            'status' => 'required',
        ]);

        // $shop = Settlement::where('user_id',Auth::id())->findorFail($id);
        // $shop->status = $request->status ?? 'unpaid';
        // if($request->status == 'paid')
        // $shop->paid_at =Carbon::now();
        // $shop->save();

        return back()->withSuccess('Settlement Updated Successfully');
    }

    public function create()
    {
        $settlement_amount = Subscription::where('user_id', Auth::id())->whereNull('settlement_id')->sum('commission');
        $partner_data = Partner::where('user_id', Auth::id())->first();

        if ($settlement_amount != 0) {
            if ($settlement_amount >= $partner_data->settlement_amount) {
                $first = Subscription::where('user_id', Auth::id())->whereNull('settlement_id')->first();
                $last = Subscription::where('user_id', Auth::id())->whereNull('settlement_id')->latest()->first();

                $end_date = $last ? Carbon::parse($last->created_at) : Carbon::now();
                $first_date = $first ? Carbon::parse($first->created_at) : Carbon::now();

                $bank_details = $partner_data ? $partner_data->bank_details : null;

                $tax = Option::where('key', 'partner_commission')->first();
                $commission_tax = $settlement_amount * (($tax->value ?? 0) * 0.01);
                $settlement = new Settlement();
                $settlement->amount = $settlement_amount - $commission_tax;
                $settlement->tax = $commission_tax;
                $settlement->total_amount = $settlement_amount;
                $settlement->settlement_rate = $tax->value ?? 0;
                $settlement->bank_details = $bank_details;
                $settlement->start_date = $first->created_at ?? '';
                $settlement->end_date = $last->created_at ?? '';
                $settlement->is_request = 0;
                $settlement->paid_at = null;
                $settlement->settlement_date = Carbon::now();
                $settlement->charge = 0;
                $settlement->interval = $end_date->diffInDays($first_date) . " Days";
                $settlement->status = 'unpaid';
                $settlement->user_id = Auth::id();
                $settlement->save();

                Subscription::where('user_id', Auth::id())->whereNull('settlement_id')->update(['settlement_id' => $settlement->id]);
                return back()->with(['message' => 'Settlement created Successfully']);
            } else {
                return back()->with(['message' => 'Please Check Your Threshold Value']);
            }
        } else {
            return back()->with(['message' => 'No new subscriptions found.']);
        }
    }
}
