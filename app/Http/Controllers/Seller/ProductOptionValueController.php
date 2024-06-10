<?php

namespace App\Http\Controllers\Seller;

use App\Models\Attribute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;

class ProductOptionValueController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'product_option_id' => 'required',
        ]);

        $post = new ProductOptionValue();
        $post->name = $request->title;
        $post->product_option_id = $request->product_option_id;
        // $post->status = $request->status ?? 1;
        $post->save();

        return response()->json([trans('Option Value Created Successfully....!!!')]);
        // Session::flash('success', trans('Variation Created Successfully....!!!'));
        // return redirect('/seller/attribute');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $info = ProductOption::where('shop_id', current_shop_id())->findorFail($id);

        return view('seller.options.values.create', compact('info'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = ProductOptionValue::find($id);
        return view('seller.options.values.edit', compact('info'));
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
            'title' => 'required',
            'product_option_id' => 'required',
        ]);

        $post = ProductOptionValue::findorFail($id);
        $post->name = $request->title;
        $post->product_option_id = $request->product_option_id;
        // $post->status = $request->status ?? 1;
        $post->save();

        return response()->json([trans('Option Value Updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate(
            [
                'method' => 'required'
            ],
            [
                'method.required' => 'Please Select Action'
            ]
        );
        if ($request->ids != '') {
            foreach ($request->ids as $id) {
                $attribute = ProductOption::where('attribute_id', $id)->get();

                if (count($attribute) == 0) {
                    $attributes = Attribute::findOrFail($id);
                    $attributes->status = $request->method;
                    $attributes->save();
                }
                if (count($attribute) > 0) {
                    if ($request->method != 0) {
                        $attributes = Attribute::findOrFail($id);
                        $attributes->status = $request->method;
                        $attributes->save();
                    } else {

                        return response()->json(['errors' => ['Can not inactive variation is mapped with products']], 400);
                    }
                }
            }
        } else {

            return response()->json(['errors' => ['Variation id not selected']], 400);
        }

        return response()->json(['Success']);
        // return response()->json([trans('Attribute Deleted')]);
    }
}
