<?php

namespace App\Http\Controllers\Seller;

use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Models\Product;
use App\Models\ResellerProduct;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class CategoryController extends Controller
{
    /**     
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $posts = Category::where('shop_id', current_shop_id())->where('type', 'category')->where('status', '!=', 5);
        if ($request->src) {
            $posts = $posts->where($request->search_type, 'LIKE', '%' . $request->src . '%');
        }
        $posts = $posts->latest()->paginate(30);

        $src = $request->src ?? '';

        return view('seller.category.index', compact('posts','src'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('seller.category.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {   
        $posts_count = Category::where('shop_id', current_shop_id())->where('type', 'category')->count();

        if (user_plan_limit('category_limit', $posts_count)) {
            // Session::flash('error', trans('Maximum category limit exceeded'));
            // return back();
            $error['errors']['error'] = trans('Maximum category limit exceeded');
            return response()->json($error, 401);
        }

        if ($request->has('file') || $request->has('icon')) {
            $size = 0;
            if ($request->has('file'))  $size += $request->file('file')->getSize();
            if ($request->has('icon')) $size += $request->file('icon')->getSize();
            if (user_plan_limit('storage', storageToMB($size))) {
                // Session::flash('error', trans('Maximum storage limit exceeded !!'));
                // return back();
                $error['errors']['error'] = trans('Maximum storage limit exceeded');
                return response()->json($error, 401);
            }
        }

        $request->validate([
            'name' => 'required',
            'file' => 'image|max:500',
        ]);
        // [
        //     'name.max' => 'Name field must not exceed 20 characters',
        // ]);

        $slug = Str::slug($request->name);
        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        if ($request->p_id) {
            $category->p_id = $request->p_id;
        }

        $category->featured = $request->featured ?? 0;
        $category->menu_status = $request->menu_status ?? 0;
        $category->user_id = auth()->id();;
        $category->status = $request->status ?? 1;
        $category->shop_id = current_shop_id();
        $category->save();

        if ($request->has('file')) {
            $category->deleteFile('image');
            $category->uploadFile($request->file, $category->shop_id  . '/category/'. $category->id . '/image', 'image');
        }

        if ($request->has('icon')) {
            $category->deleteFile('icon');
            $category->uploadFile($request->file, $category->shop_id  . '/category/'. $category->id . '/icon', 'icon');
        }
        $dta['redirect'] = '/seller/category';
        $dta['message'] = 'Category Created Successfully....!!!';
        return response()->json($dta);
        // Session::flash('success', trans('Category Created Successfully....!!!'));
        // return redirect('/seller/category');
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
        $info = Category::where('shop_id', current_shop_id())->findOrFail($id);
        return view('seller.category.edit', compact('info'));
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
            'name' => 'required',
            'file' => 'image|max:500',
        ]);
        // [
        //     'name.max' => 'Name field must not exceed 20 characters',
        // ]);
        $brand = ProductCategory::where('category_id', $id)->first();
        if (empty($brand)) {
            $slug = Str::slug($request->name);

            $category = Category::where('shop_id', current_shop_id())->findOrFail($id);
            $category->name = $request->name;
            $category->slug = $slug;

            if ($request->p_id) {
                $category->p_id = $request->p_id;
            } else {
                $category->p_id = null;
            }

            $category->featured = $request->featured ?? 0;
            $category->menu_status = $request->menu_status ?? 0;
            $category->status = $request->status ?? 1;
            $category->save();

            if ($request->has('file')) {
                $size = $request->file('file')->getSize();
                if (user_plan_limit('storage', storageToMB($size - $category->getFileSize('image')))) {
                    $error['errors']['error'] = trans('Maximum storage limit exceeded');
                    return response()->json($error, 401);
                }
    
                $category->deleteFile('image');
                $category->uploadFile($request->file, $category->shop_id  . '/category/'. $category->id . '/image', 'image');
            }
    
            if ($request->has('icon')) {
                $size = $request->file('icon')->getSize();
                if (user_plan_limit('storage', storageToMB($size - $category->getFileSize('icon')))) {
                    $error['errors']['error'] = trans('Maximum storage limit exceeded');
                    return response()->json($error, 401);
                }
    
                $category->deleteFile('icon');
                $category->uploadFile($request->file, $category->shop_id  . '/category/'. $category->id . '/icon', 'icon');
            }

            // Session::flash('success', trans('Category Updated !!'));
            // return back();
            return response()->json('Category Updated Successfully....!!!');
        }
        if (!empty($brand)) {
            if ($request->status != 0) {
                $slug = Str::slug($request->name);

                $category = Category::where('shop_id', current_shop_id())->findOrFail($id);
                $category->name = $request->name;
                $category->slug = $slug;

                if ($request->p_id) {
                    $category->p_id = $request->p_id;
                } else {
                    $category->p_id = null;
                }

                $category->featured = $request->featured ?? 0;
                $category->menu_status = $request->menu_status ?? 0;
                $category->status = $request->status ?? 1;
                $category->save();

                if ($request->has('file')) {
                    $size = $request->file('file')->getSize();
                    if (user_plan_limit('storage', storageToMB($size - $category->getFileSize('image')))) {
                        $error['errors']['error'] = trans('Maximum storage limit exceeded');
                        return response()->json($error, 401);
                    }

                    $category->deleteFile('image');
                    $category->uploadFile($request->file, $category->shop_id  . '/category/'. $category->id . '/image', 'image');
                }

                // Session::flash('success', trans('Category Updated !!'));
                // return back();
                return response()->json('Category Updated Successfully....!!!');
            } else {
                $error['errors']['error'] = trans('Can not inactive category is mapped with products');
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
        $request->validate([
            'method' => 'required'
        ],
        [
            'method.required' => 'Please Select Action'
        ]); 
        if(current_shop_type() == 'reseller') {
        if ($request->ids != '') {
            foreach ($request->ids as $id) {
                $category = ResellerProduct::where('category_id', $id)->where('status','!=','5')->get();
            }
            if(isset($category)){
                if(count($category) == 0) {
                    $categorys = Category::findOrFail($id);
                    $categorys->status = $request->method;
                    $categorys->save();
                    return response()->json(['Success']);
                } else {
                    if($request->method == '5') {
                        return response()->json(['errors' => ['Can not delete category is mapped with products']], 400);
                    }
                    else if($request->method == '0') {
                        return response()->json(['errors' => ['Can not inactive category is mapped with products']], 400);
                    }
                }
            } else {
                $categorys = Category::findOrFail($id);
                $categorys->status = $request->method;
                $categorys->save();
                return response()->json(['Success']);
            }

        }
            else {

                return response()->json(['errors' => ['Category id not selected']], 400);
            }
        }
        if ($request->ids != '') {
            foreach ($request->ids as $id) {
                $category = ProductCategory::where('category_id', $id)->get();
                if($category){
                    foreach ($category as $category_details) {
                        $product = Product::where('id',$category_details->product_id)->where('status','!=','5')->get();
                    }
                    if(isset($product)){
                        if(count($product) == 0) {
                            $categorys = Category::findOrFail($id);
                            $categorys->status = $request->method;
                            $categorys->save();
                            return response()->json(['Success']);
                        } else {
                            if($request->method == '5') {
                                return response()->json(['errors' => ['Can not delete category is mapped with products']], 400);
                            }
                            else if($request->method == '0') {
                                return response()->json(['errors' => ['Can not inactive category is mapped with products']], 400);
                            }
                        }
                    } else {
                        $categorys = Category::findOrFail($id);
                        $categorys->status = $request->method;
                        $categorys->save();
                        return response()->json(['Success']);
                    }

                }
                if (count($category) == 0) {
                    $categorys = Category::findOrFail($id);
                    $categorys->status = $request->method;
                    $categorys->save();
                }

                if (count($category) < 0) {
                    if ($request->method != 5) {
                        $categorys = Category::findOrFail($id);
                        $categorys->status = $request->method;
                        $categorys->save();
                    }
                } else {
                    return response()->json(['errors' => ['Can not delete category is mapped with products']], 400);
                }
                
                if (count($category) > 0) {
                    if ($request->method != 0) {
                        $categorys = Category::findOrFail($id);
                        $categorys->status = $request->method;
                        $categorys->save();
                    } else {

                        return response()->json(['errors' => ['Can not inactive category is mapped with products']], 400);
                    }
                }
            }
        } else {

            return response()->json(['errors' => ['Category id not selected']], 400);
        }

        return response()->json(['Success']);
        // if ($request->type == 'delete') {
        //     foreach ($request->ids as $key => $row) {
        //         $id = base64_decode($row);
        //         $category = Category::where('shop_id', current_shop_id())->where('id', $id)->first();
        //         if (!empty($category->preview)) {
        //             if (!empty($category->preview->content)) {
        //                 if (file_exists($category->preview->content)) {
        //                     unlink($category->preview->content);
        //                 }
        //             }
        //         }
        //         $category->delete();
        //     }
        // }

        // return response()->json([trans('Category Deleted')]);
    }

    //product edit Category creation
    public function category_creation(Request $request)
    {
        $posts_count = Category::where('shop_id', current_shop_id())->where('type', 'category')->count();

        if (user_plan_limit('category_limit', $posts_count)) {
            $error['errors']['error'] = trans('Maximum category limit exceeded');
            return response()->json($error, 401);
        }

        if ($request->has('file') || $request->has('icon')) {
            $size = 0;
            if ($request->has('file'))  $size += $request->file('file')->getSize();
            if ($request->has('icon')) $size += $request->file('icon')->getSize();
            if (user_plan_limit('storage', storageToMB($size))) {
                $error['errors']['error'] = trans('Maximum storage limit exceeded');
                return response()->json($error, 401);
            }
        }

        $request->validate([
            'name' => 'required|max:255',
            'file' => 'image|max:500',
        ]);

        $slug = Str::slug($request->name);
        $category = new Category();
        $category->name = $request->name;
        $category->slug = $slug;
        if ($request->p_id) {
            $category->p_id = $request->p_id;
        }

        $category->featured = $request->featured ?? 0;
        $category->menu_status = $request->menu_status ?? 0;
        $category->user_id = auth()->id();
        $category->shop_id = current_shop_id();
        $category->status = $request->status ?? 1;
        $category->save();

        if ($request->has('file')) {
            $category->deleteFile('image');
            $category->uploadFile($request->file, $category->shop_id  . '/category/'. $category->id . '/image', 'image');
        }

        if ($request->has('icon')) {
            $category->deleteFile('icon');
            $category->uploadFile($request->icon, $category->shop_id  . '/category/'. $category->id . '/icon', 'icon');
        }

        return response()->json(['category' => $category->name, 'category_id' =>  $category->id, 'status' =>  $category->status, 'message' => 'Category Created']);
    }
}
