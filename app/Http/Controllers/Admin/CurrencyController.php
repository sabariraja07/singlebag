<?php

namespace App\Http\Controllers\Admin;

use App\Models\Currency;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;

class CurrencyController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Currency::latest()->paginate(40);
        return view('admin.currency.index', compact('posts', 'request'));
    }
    public function Create()
    {
        return view('admin.currency.create');
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            // 'name' => 'required',
            // 'code' => 'required|unique:currencies|max:5',
            // 'symbol' => 'required',
            // 'decimal' => 'required',
            // 'position' => 'required',
            // 'exchange_rate' => 'required',
            // 'country_code' => 'required',
            'currency' => 'required|max:5|unique:currencies,code',
            'status' => 'required',
        ]);

        DB::beginTransaction();
        try {
            $currencies = get_currency_codes();
            $currency = new Currency();
            $currency->name = $request->name ?? $currencies[$request->currency]['name'];
            $currency->code = $request->currency;
            $currency->symbol = $request->symbol ?? $currencies[$request->currency]['symbol'];
            $currency->decimal = $request->decimal ?? $currencies[$request->currency]['precision'];
            $currency->position = $request->position ?? 0;
            $currency->exchange_rate = $request->exchange_rate ?? 1;
            $currency->country_code = $request->country_code ?? null;
            $currency->status = $request->status ?? 0;
            $currency->save();

            DB::commit();

            return response()->json(['Currency Created Successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([$e->getMessage()]);
        }

        return response()->json(['Currency created failed']);
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = Currency::find($id);
        return view('admin.currency.edit', compact('info'));
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
            // 'name' => 'required',
            // 'code' => 'required|max:5|unique:currencies,code' . $id,
            // 'symbol' => 'required',
            // 'decimal' => 'required',
            // 'position' => 'required',
            // 'exchange_rate' => 'required',
            // 'country_code' => 'required',
            'currency' => 'required|max:5|unique:currencies,code,' . $id,
            'status' => 'required',
        ]);


        DB::beginTransaction();
        try {
            // $currencies = get_currency_codes();
            $currency = Currency::findorFail($id);
            // $currency->name = $request->name ?? $currencies[$request->currency]['name'];
            // $currency->code = $request->currency;
            // $currency->symbol = $request->symbol ?? $currencies[$request->currency]['symbol'];
            // $currency->decimal = $request->decimal ?? $currencies[$request->currency]['precision'];
            // $currency->position = $request->position ?? 0;
            // $currency->exchange_rate = $request->exchange_rate ?? 1;
            // $currency->country_code = $request->country_code ?? null;
            $currency->status = $request->status ?? 0;
            $currency->save();

            DB::commit();
            return response()->json(['Currency Updated Successfully']);
        } catch (\Exception $e) {
            DB::rollback();
            return response()->json([$e->getMessage()]);
        }

        return response()->json(['Currency updated failed']);
    }
}
