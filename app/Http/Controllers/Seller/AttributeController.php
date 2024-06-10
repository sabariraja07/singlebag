<?php

namespace App\Http\Controllers\Seller;

use App\Models\Attribute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\ProductAttribute;

class AttributeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $posts = Attribute::where('shop_id', current_shop_id())
            ->whereNull('parent_id')
            ->with('children')
            ->withCount('featured_with_product_count');

        if ($request->src) {
            $posts = $posts->where($request->search_type, 'LIKE', '%' . $request->src . '%');
        }
        $posts = $posts->latest()->paginate(30);

        $src = $request->src ?? '';

        return view('seller.attributes.index', compact('posts', 'src'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('seller.attributes.create');
    }

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
            Session::flash('error', trans('Maximum Attribute limit exceeded !!'));
            return back();
            // $error['errors']['error'] = trans('Maximum Attribute limit exceeded');
            // return response()->json($error, 401);
        }

        $request->validate([
            'title' => 'required',
        ]);

        $post = new Attribute();
        $post->name = $request->title;
        $post->slug = Str::slug($request->title);
        $post->featured = $request->featured ?? 0;
        $post->status = $request->status ?? 1;
        $post->user_id = auth()->id();
        $post->shop_id = current_shop_id();
        $post->save();

        return response()->json([trans('Attribute Created Successfully....!!!')]);
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
        $posts = Attribute::where('parent_id', $id)
            ->where('shop_id', current_shop_id())->with('parent')->withCount('attribute_with_product_count')->get();

        return view('seller.attributes.childAttributes.index', compact('posts', 'id'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = Attribute::where('shop_id', current_shop_id())->findorFail($id);

        return view('seller.attributes.edit', compact('info'));
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

        // $product = Attribute::where('id', $id)->first();

        $product = Attribute::where('parent_id', $id)->first();

        if (!empty($product)) {
            $attribute = ProductAttribute::where('attribute_id', $product->id)->first();
        }
        if (!empty($product)) {
            if (empty($attribute)) {
                $post = Attribute::where('shop_id', current_shop_id())->findorFail($id);
                $post->name = $request->title;
                $post->featured = $request->featured ?? 0;
                $post->status = $request->status ?? 1;
                $post->save();

                return response()->json([trans('Attribute Updated')]);
            }
            if (!empty($attribute)) {
                if ($request->status != 0) {
                    $post = Attribute::where('shop_id', current_shop_id())->findorFail($id);
                    $post->name = $request->title;
                    $post->featured = $request->featured ?? 0;
                    $post->status = $request->status ?? 1;
                    $post->save();

                    return response()->json([trans('Attribute Updated')]);
                } else {
                    $error['errors']['error'] = trans('Can not inactive attribute is mapped with products');
                    return response()->json($error, 401);
                }
            }
        } else {
            $post = Attribute::where('shop_id', current_shop_id())->findorFail($id);
            $post->name = $request->title;
            $post->featured = $request->featured ?? 0;
            $post->status = $request->status ?? 1;
            $post->save();
            return response()->json([trans('Attribute Updated')]);
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
                $product_attribute = Attribute::where('parent_id', $id)->first();

                if (!empty($product_attribute)) {
                    $attribute = ProductAttribute::where('attribute_id', $product_attribute->id)->get();
                }
                if (!empty($product_attribute)) {
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

                            return response()->json(['errors' => ['Can not inactive attribute is mapped with products']], 400);
                        }
                    }
                } else {
                    $attributes = Attribute::findOrFail($id);
                    $attributes->status = $request->method;
                    $attributes->save();
                }
            }
        } else {

            return response()->json(['errors' => ['Attribute id not selected']], 400);
        }

        return response()->json(['Success']);


        // $user_id=Auth::id();
        // if ($request->method=='delete') {
        //     foreach ($request->ids as $key => $id) {


        //       Attribute::where([
        //         ['shop_id',current_shop_id()],
        //         ['p_id',$id],
        //        ])->delete();

        //        $post= Attribute::where([
        //         ['shop_id',current_shop_id()]
        //        ])->findorFail($id);
        //        $post->delete();


        //     }
        // }

        // return response()->json([trans('Attribute Deleted')]);
    }
}
