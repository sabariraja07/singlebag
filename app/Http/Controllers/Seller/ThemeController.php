<?php

namespace App\Http\Controllers\Seller;

use App\Models\Shop;
use App\Models\Domain;
use App\Models\Template;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ThemeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Template::latest()->paginate(20);
        $active_theme = Shop::where('id', current_shop_id())->first();
        return view('seller.store.theme', compact('posts', 'active_theme'));
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
        Shop::where('id', $shop_id)->update(['template_id' => $id]);

        Cache::forget(get_host());
        \Session::flash('success', trans('Theme activated successfully'));
        return back();
    }
}
