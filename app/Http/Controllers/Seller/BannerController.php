<?php

namespace App\Http\Controllers\Seller;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Session;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $posts = Banner::where('shop_id', current_shop_id())->where('type', 'banner')->latest()->paginate(30);

        return view('seller.ads.banner', compact('posts'));
    }

    /**
     * ~ a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $banner_count = Banner::where('shop_id', current_shop_id())->where('type', 'banner')->count();
        if (user_plan_limit('product_limit', $banner_count)) {
            Session::flash('error', trans('Maximum posts limit exceeded'));
            $error['errors']['error'] = trans('Maximum posts limit exceeded');
            return response()->json($error, 401);
        }

        $request->validate([
            'url' => 'required|max:50',
            'description' => 'max:100',
            'title' => 'max:100',
            'btn_text' => 'max:100',
            'file' => 'required|max:1000|image',
        ]);

        if ($request->hasFile('file')) {
            if (user_plan_limit('storage', storageToMB($request->file('file')->getSize()))) {
                Session::flash('error', trans('Maximum storage limit exceeded'));
                $error['errors']['error'] = trans('Maximum storage limit exceeded');
                return response()->json($error, 401);
            }
        }

        $banner = new Banner();
        $banner->title = $request->title;
        $banner->url = $request->url;
        $banner->btn_text = $request->btn_text;
        $banner->description = $request->description;
        $banner->position = $request->position;
        $banner->type = 'banner';
        $banner->shop_id = current_shop_id();
        $banner->user_id = auth()->id();
        $banner->status = $request->status ?? 1;
        $banner->text_color = $request->text_color;
        $banner->save();

        if ($request->hasFile('file')) {
            $banner->deleteFile('image');
            $banner->uploadFile($request->file, $banner->shop_id  . '/banner/' . $banner->id . '/image', 'image');
        }

        return response()->json([trans('Banner Created')]);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function edit($id)
    {
        $info = Banner::where('shop_id', current_shop_id())->where('type', 'banner')->findOrFail($id);
        return view('seller.ads.banner_edit', compact('info'));
    }

    public function update(Request $request, $id)
    {
        $banner = Banner::where('shop_id', current_shop_id())->findOrFail($id);
        $banner->title = $request->title;
        $banner->url = $request->url;
        $banner->btn_text = $request->btn_text;
        $banner->description = $request->description;
        $banner->position = $request->position;
        $banner->status = $request->status ?? 1;
        $banner->text_color = $request->text_color;
        $banner->save();

        if ($request->hasFile('file')) {
            $size = $request->file('file')->getSize();
            if (user_plan_limit('storage', storageToMB($size - $banner->getFileSize('image')))) {
                Session::flash('error', trans('Maximum storage limit exceeded'));
                $error['errors']['error'] = trans('Maximum storage limit exceeded');
                return response()->json($error, 401);
            }

            $banner->deleteFile('image');
            $banner->uploadFile($request->file, $banner->shop_id  . '/banner/' . $banner->id . '/image', 'image');
        }

        return response()->json([trans('Banner Updated')]);
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $slider = Banner::where('shop_id', current_shop_id())->where('type', 'banner')->findorFail($id);
        if (file_exists($slider->name)) {
            unlink($slider->name);
        }
        $slider->deleteFile('image');
        $slider->delete();

        // return back();
        Session::flash('success', trans('Banner Deleted !!'));
        return redirect('seller/banner-ads');
    }
}
