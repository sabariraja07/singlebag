<?php

namespace App\Http\Controllers\API\V1\Seller;

use App\Models\Banner;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Http\Controllers\Controller;

class BannerController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $banners = Banner::where('shop_id', current_shop_id())->where('type', 'banner');

        if ($request->src) {
            $banners = $banners->where($request->search_type, 'LIKE', '%' . $request->src . '%');
        }
        $banners = $banners->latest()->paginate(30)->toArray();

        return response()->json(array_merge($banners, ['status' => 'success']));
    }

    public function store(Request $request)
    {
        $banner_count = Banner::where('shop_id', current_shop_id())->where('type', 'banner')->count();
        if (user_plan_limit('product_limit', $banner_count)) {
            return $this->error(trans('Maximum posts limit exceeded'), 401);
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
                return $this->error(trans('Maximum storage limit exceeded'), 401);
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

        return $this->success($banner, 'Banner Created');
    }

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
                return $this->error(trans('Maximum storage limit exceeded'), 401);
            }

            $banner->deleteFile('image');
            $banner->uploadFile($request->file, $banner->shop_id  . '/banner/' . $banner->id . '/image', 'image');
        }

        return $this->success($banner, 'Banner Updated');
    }

    public function destroy(Request $request, $id)
    {
        $banner = Banner::where('shop_id', current_shop_id())->where('type', 'banner')->findorFail($id);
        $banner->deleteFile('image');
        $banner->status = 0;
        $banner->save();

        return $this->success($banner, 'Banner Deleted');
    }
}
