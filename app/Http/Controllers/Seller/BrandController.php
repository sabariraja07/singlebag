<?php

namespace App\Http\Controllers\Seller;

use App\Models\Brand;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;
use App\Http\Controllers\Controller;
use App\Models\Product;

class BrandController extends Controller
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Brand::where('shop_id', current_shop_id())->where('status', '!=', 5);
        if ($request->src) {
            $posts = $posts->where($request->search_type, 'LIKE', '%' . $request->src . '%');
        }
        $posts = $posts->latest()->paginate(30);

        $src = $request->src ?? '';

        return view('seller.brand.index', compact('posts','src'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('seller.brand.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $posts_count = Brand::where('shop_id', current_shop_id())->count();
        if (user_plan_limit('brand_limit', $posts_count)) {
            //  Session::flash('error', trans('Maximum Brand limit exceeded !!'));
            //  return back();
            $error['errors']['error'] = trans('Maximum Brand limit exceeded');
            return response()->json($error, 401);
        }

        if ($request->has('file')) {
            $size = $request->file('file')->getSize();
            if (user_plan_limit('storage', storageToMB($size))) {
                // Session::flash('error', trans('Maximum storage limit exceeded !!'));
                // return back();
                $error['errors']['error'] = trans('Maximum storage limit exceeded');
                return response()->json($error, 401);
            }
        }

        $slug = Str::slug($request->name);

        $check = Brand::where('slug', $slug)->count();
        if ($check > 0) {
            $slug = $slug . '-' . rand(20, 100);
        }


        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = $slug;
        $brand->featured = $request->featured ?? 0;
        $brand->shop_id = current_shop_id();
        $brand->status = $request->status ?? 1;
        $brand->user_id = auth()->id();
        $brand->save();

        if ($request->has('file')) {
            $brand->uploadFile($request->file, $brand->shop_id  . '/brand/'. $brand->id . '/image', 'image');
        }

        $dta['redirect'] = '/seller/brand';
        $dta['message'] = 'Brand Created Successfully....!!!';
        return response()->json($dta);

        // return response()->json('Brand Created Successfully....!!!');
        // Session::flash('success', trans('Brand Created Successfully....!!!'));
        // return redirect('/seller/brand'); 
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $info = Brand::where('shop_id', current_shop_id())->findOrFail($id);
        return view('seller.brand.edit', compact('info'));
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

        // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:2048|dimensions:min_width=350,min_height=600',dimensions:ratio=3/2',,
        $brand = Brand::where('shop_id', current_shop_id())->where('id', $id)->firstOrFail();

        $brand_products = current_shop_type() == 'reseller' ? $brand->ResellerProducts()->count() : $brand->Products()->count();
        if($brand_products > 0 && $request->status == 0) {
            $error['errors']['error'] = trans('Can not inactive brand is mapped with products');
            return response()->json($error, 401);
        }

        $slug = Str::slug($request->name);
        $brand->name = $request->name;
        $brand->slug = $slug;
        $brand->featured = $request->featured ?? 0;
        $brand->status = $request->status ?? 1;
        $brand->save();

        if ($request->has('file')) {
            $size = $request->file('file')->getSize();
            if (user_plan_limit('storage', storageToMB($size - $brand->getFileSize('image')))) {
                $error['errors']['error'] = trans('Maximum storage limit exceeded');
                return response()->json($error, 401);
            }

            $brand->deleteFile('image');
            $brand->uploadFile($request->file, $brand->shop_id  . '/brand/'. $brand->id . '/image', 'image');
        }

        return response()->json('Brand Updated Successfully....!!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request)
    {
        $request->validate([
            'method' => 'required'
        ],
        [
            'method.required' => 'Please Select Action'
        ]);
        if ($request->ids != '') {
            foreach ($request->ids as $id) {
                $brand = Product::where('brand_id', $id)->where('status','!=','5')->get();

                if (count($brand) == 0) {
                    $brands = Brand::findOrFail($id);
                    $brands->status = $request->method;
                    $brands->save();
                } else {
                    if($request->method == '5') {
                        return response()->json(['errors' => ['Can not delete brand is mapped with products']], 400);
                    }
                    else if($request->method == '0') {
                        return response()->json(['errors' => ['Can not inactive brand is mapped with products']], 400);
                    }
                }

               
            }
        } else {

            return response()->json(['errors' => ['Brand id not selected']], 400);
        }

        return response()->json(['Success']);

        // if ($request->type == 'delete') {
        //     foreach ($request->ids as $key => $row) {
        //         $id = base64_decode($row);
        //         $brand = Brand::destroy($id);
        //     }
        // }

        // return response()->json([trans('Brand Deleted')]);


    }

    //product edit brand creation
    public function brand_creation(Request $request)
    {
        $posts_count = Brand::where('shop_id', current_shop_id())->count();
        if (user_plan_limit('brand_limit', $posts_count)) {
            $error['errors']['error'] = trans('Maximum Brand limit exceeded');
            return response()->json($error, 401);
        }

        if ($request->has('file')) {
            $size = $request->file('file')->getSize();
            if (user_plan_limit('storage', storageToMB($size))) {
                $error['errors']['error'] = trans('Maximum storage limit exceeded');
                return response()->json($error, 401);
            }
        }

        $slug = Str::slug($request->name);

        $check = Brand::where('slug', $slug)->count();
        if ($check > 0) {
            $slug = $slug . '-' . rand(20, 100);
        }


        $brand = new Brand();
        $brand->name = $request->name;
        $brand->slug = $slug;
        $brand->featured = $request->featured ?? 0;
        $brand->shop_id = current_shop_id();
        $brand->status = $request->status ?? 1;
        $brand->user_id = auth()->id();
        $brand->save();

        if ($request->has('file')) {
            $brand->uploadFile($request->file, $brand->shop_id  . '/brand/'. $brand->id . '/image', 'image');
        }

        return response()->json(['brand' =>  $brand->name, 'brand_id' =>  $brand->id, 'status' =>  $brand->status, 'message' => 'Brand Created']);
    }
}
