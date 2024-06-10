<?php

namespace App\Http\Controllers\Admin;

use Carbon\Carbon;
use App\Models\Shop;
use App\Models\Settlement;
use App\Rules\IsValidDomain;
use App\Models\Subscription;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

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
        if (!Auth()->user()->can('shop.list')) {
            return abort(401);
        }
        $type = $request->type ?? 'all';

        $posts = Settlement::whereNull('shop_id')->with('subscriptions', 'user')->latest()->paginate(40);

        return view('admin.settlement.index', compact('posts'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        if (!Auth()->user()->can('shop.create')) {
            return abort(401);
        }

        return view('admin.shop.create');
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users|email_address|max:255',
            'password' => 'required|without_spaces',
            'plan' => 'required',
            'shop_name' => 'required',
            'shop_name' => [new IsValidDomain()],
            // 'domain_name' => 'required|max:100|unique:domains,domain',
            // 'full_domain' => 'required|max:100|unique:domains,full_domain',
        ]);



        return response()->json(['Customer Created Successfully']);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        if (!Auth()->user()->can('shop.view')) {
            return abort(401);
        }

        $info = Settlement::with('user')->findorFail($id);

        $amount = Settlement::where('status','paid')->where('user_id', $info->user_id)->sum('amount');
        $unpaid_amount = Settlement::where('status', 'unpaid')->where('user_id', $info->user_id)->sum('amount');

        $histories = Subscription::with('plan', 'PaymentMethod')->where('settlement_id', $info->id)->latest()->paginate(40);

        return view('admin.settlement.show', compact('info', 'histories', 'amount', 'unpaid_amount'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        if (!Auth()->user()->can('shop.edit')) {
            return abort(401);
        }

        $info = Shop::findorFail($id);
        return view('admin.shop.edit', compact('info'));
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

        $shop = Settlement::findorFail($id);
        $shop->status = $request->status ?? 'unpaid';
        if($request->status == 'paid')
        $shop->paid_at =Carbon::now();
        $shop->save();

        return back()->withSuccess('Settlement Updated Successfully');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        if (!Auth()->user()->can('shop.delete')) {
            return abort(401);
        }

        if ($request->type == "term_delete") {
            foreach ($request->ids ?? [] as $key => $id) {
                \App\Models\Product::destroy($id);
            }
        } elseif ($request->type == "user_delete") {
            foreach ($request->ids ?? [] as $key => $id) {
                \App\Models\Customer::destroy($id);
            }
        } else {
            if (!empty($request->method)) {
                if ($request->method == "delete") {
                    foreach ($request->ids ?? [] as $key => $id) {
                        \File::deleteDirectory('uploads/' . $id);
                        $user = Shop::destroy($id);
                    }
                } else {
                    foreach ($request->ids ?? [] as $key => $id) {
                        $user = Shop::find($id);
                        if ($request->method == "trash") {
                            $user->status = 0;
                        } else {
                            $user->status = $request->method;
                        }
                        $user->save();
                    }
                }
            }
        }

        return response()->json(['Success']);
    }
}
