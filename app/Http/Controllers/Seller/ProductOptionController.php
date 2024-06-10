<?php

namespace App\Http\Controllers\Seller;

use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;

class ProductOptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $posts = ProductOption::where('shop_id', current_shop_id())->with('value');

        if ($request->src) {
            $posts = $posts->where($request->search_type, 'LIKE', '%' . $request->src . '%');
        }
        $posts = $posts->latest()->paginate(30);

        $src = $request->src ?? '';

        return view('seller.options.index', compact('posts','src'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('seller.options.create');
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
            'title' => 'required',
        ]);

        $post = new ProductOption();
        $post->name = $request->title;
        $post->type = $request->type;
        // $post->status = $request->status ?? 1;
        $post->shop_id = current_shop_id();
        $post->save();

        return response()->json([trans('Option Created Successfully....!!!')]);
        // Session::flash('success', trans('Attribute Created Successfully....!!!'));
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
        $posts = ProductOptionValue::where('product_option_id', $id)->get();

        return view('seller.options.values.index', compact('posts', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = ProductOption::where('shop_id', current_shop_id())->findorFail($id);

        return view('seller.options.edit', compact('info'));
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
        ]);

        $option = ProductOption::where('id', $id)->first();
        if ($option) {
            $post = ProductOption::where('shop_id', current_shop_id())->findorFail($id);
            $post->name = $request->title;
            $post->type = $request->type;
            // $post->status = $request->status ?? 1;
            $post->save();

            return response()->json([trans('Option Updated')]);
        }
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    // public function destroy(Request $request)
    // {
    //     $request->validate([
    //         'method' => 'required'
    //     ],
    //     [
    //         'method.required' => 'Please Select Action'
    //     ]);
    //     if ($request->ids != '') {
    //         foreach ($request->ids as $id) {
    //             $product_attribute = ProductOption::where('parent_id', $id)->first();

    //             if (!empty($product_attribute)) {
    //                 $attribute = ProductAttribute::where('attribute_id', $product_attribute->id)->get();
    //             }
    //             if (!empty($product_attribute)) {
    //                 if (count($attribute) == 0) {
    //                     $options = ProductOption::findOrFail($id);
    //                     $options->status = $request->method;
    //                     $options->save();
    //                 }
    //                 if (count($attribute) > 0) {
    //                     if ($request->method != 0) {
    //                         $options = ProductOption::findOrFail($id);
    //                         $options->status = $request->method;
    //                         $options->save();
    //                     } else {

    //                         return response()->json(['errors' => ['Can not inactive attribute is mapped with products']], 400);
    //                     }
    //                 }
    //             } else {
    //                 $options = ProductOption::findOrFail($id);
    //                 $options->status = $request->method;
    //                 $options->save();
    //             }
    //         }
    //     } else {

    //         return response()->json(['errors' => ['Attribute id not selected']], 400);
    //     }

    //     return response()->json(['Success']);


    //     // $user_id=Auth::id();
    //     // if ($request->method=='delete') {
    //     //     foreach ($request->ids as $key => $id) {


    //     //       ProductOption::where([
    //     //         ['shop_id',current_shop_id()],
    //     //         ['p_id',$id],
    //     //        ])->delete();

    //     //        $post= ProductOption::where([
    //     //         ['shop_id',current_shop_id()]
    //     //        ])->findorFail($id);
    //     //        $post->delete();


    //     //     }
    //     // }

    //     // return response()->json([trans('Attribute Deleted')]);
    // }
}
