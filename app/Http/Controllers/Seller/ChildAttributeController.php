<?php

namespace App\Http\Controllers\Seller;

use App\Models\Attribute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;

class ChildAttributeController extends Controller
{

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $posts_count = Attribute::where('shop_id', current_shop_id())->count();
        if (user_plan_limit('variation_limit', $posts_count)) {
            Session::flash('error', trans('Maximum Variation limit exceeded !!'));
            return back();
            // $error['errors']['error'] = trans('Maximum Attribute limit exceeded');
            // return response()->json($error, 401);
        }

        $request->validate([
            'title' => 'required',
            'parent_attribute' => 'required',
        ]);

        $info = Attribute::where('shop_id', current_shop_id())->findorFail($request->parent_attribute);

        $post = new Attribute();
        $post->name = $request->title;
        $post->slug = Str::slug($request->title);
        $post->parent_id = $info->id;
        $post->featured = $request->featured ?? 0;
        $post->status = $request->status ?? 1;
        $post->user_id = auth()->id();
        $post->shop_id = current_shop_id();
        $post->save();

        return response()->json([trans('Variation Created Successfully....!!!')]);
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
        $info = Attribute::where('shop_id', current_shop_id())->with('children')->findorFail($id);

        return view('seller.attributes.childAttributes.create', compact('info'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = Attribute::where('shop_id', current_shop_id())->find($id);

        return view('seller.attributes.childAttributes.edit', compact('info'));
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
            'parent_attribute' => 'required',
        ]);

        // $attribute = Attribute::where('id', $id)->first();
        $attribute = ProductAttribute::where('attribute_id', $id)->first();

        if (empty($attribute)) {
            $post = Attribute::where('shop_id', current_shop_id())->findorFail($id);
            $post->name = $request->title;
            $post->parent_id = $request->parent_attribute;
            $post->featured = $request->featured ?? 0;
            $post->status = $request->status ?? 1;
            $post->slug = Str::slug($request->title);
            $post->save();

            return response()->json([trans('Variation Updated')]);
        }
        if (!empty($attribute)) {
            if ($request->status != 0) {
                $post = Attribute::where('shop_id', current_shop_id())->findorFail($id);
                $post->name = $request->title;
                $post->parent_id = $request->parent_attribute;
                $post->featured = $request->featured ?? 0;
                $post->status = $request->status ?? 1;
                $post->slug = Str::slug($request->title);
                $post->save();

                return response()->json([trans('Variation Updated')]);
            } else {
                $error['errors']['error'] = trans('Can not inactive variation is mapped with products');
                return response()->json($error, 401);
            }
        }
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
                $attribute = ProductAttribute::where('attribute_id', $id)->get();

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
