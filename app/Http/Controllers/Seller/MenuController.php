<?php

namespace App\Http\Controllers\Seller;

use App\Models\Menu;
use App\Models\Page;
use App\Models\Brand;
use App\Models\Product;
use App\Models\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class MenuController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        return view('seller.store.menu.index');
    }




    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($slug)
    {
        if ($slug == 'left' || $slug == 'right' || $slug == 'center' || $slug == 'header') {
            $shop_id = current_shop_id();
            $info = Menu::where('shop_id', current_shop_id())->where('position', $slug)->first();
            if (empty($info)) {
                $info = new Menu;
                $info->shop_id = current_shop_id();
                $info->shop_id = $shop_id;
                $info->position = $slug;
                $info->name = $slug;
                $info->data = '[]';
                $info->save();
            }
            $pages = Page::where('shop_id', current_shop_id())->get();
            $products = Product::where('type', 'product')->where('status', 1)->byShop()->get();
            $brands = Brand::where('shop_id', current_shop_id())->where('status', 1)->get();
            $categories = Category::where('shop_id', current_shop_id())->where('type', 'category')->where('status', 1)->get();
            return view('seller.store.menu.edit', compact('info', 'pages', 'products', 'brands', 'categories'));
        } else {
            abort(404);
        }
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

        $shop_id = current_shop_id();
        $info = Menu::where('shop_id', $shop_id)->findorFail($id);
        $info->name = $request->name;
        $info->data = $request->data;
        $info->save();

        Cache::forget($info->position . 'menu' . Auth::id());
        return response()->json([trans('Menu Updated')]);
    }
}
