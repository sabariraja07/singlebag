<?php

namespace App\Http\Controllers\API\V1\Seller;

use App\Models\Brand;
use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;

class BrandController extends Controller
{
    use ApiResponser;

    public function index(Request $request)
    {
        $brand = Brand::where('shop_id', current_shop_id())->where('status', '!=', 5);
        if ($request->src) {
            $brand = $brand->where($request->search_type, 'LIKE', '%' . $request->src . '%');
        }
        $brand = $brand->latest()->paginate(30)->toArray();

        return response()->json(array_merge($brand, ['status' => 'success']));
    }

    public function store(Request $request)
    {
        $brand_count = Brand::where('shop_id', current_shop_id())->count();
        if (user_plan_limit('brand_limit', $brand_count)) {
            return $this->error('Maximum Brand limit exceeded.', 401);
        }

        if ($request->has('file')) {
            $size = $request->file('file')->getSize();
            if (user_plan_limit('storage', storageToMB($size))) {
                return $this->error('Maximum storage limit exceeded.', 401);
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
            $brand->uploadFile($request->file, $brand->shop_id  . '/brand/' . $brand->id . '/image', 'image');
        }

        return $this->success($brand, 'Brand Created Successfully....!!!');
    }

    public function show($id)
    {
        $brand = Brand::where('shop_id', current_shop_id())->findOrFail($id);
        return $this->success($brand, 'Brand Created Successfully....!!!');
    }

    public function update(Request $request, $id)
    {
        $brand = Brand::where('shop_id', current_shop_id())->where('id', $id)->firstOrFail();

        $brand_products = current_shop_type() == 'reseller' ? $brand->ResellerProducts()->count() : $brand->Products()->count();
        if ($brand_products > 0 && $request->status == 0) {
            $error = trans('Can not inactive brand is mapped with products');
            return $this->error($error, 401);
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
            $brand->uploadFile($request->file, $brand->shop_id  . '/brand/' . $brand->id . '/image', 'image');
        }

        return $this->success($brand, 'Brand Updated Successfully....!!!');
    }

    public function destroy(Request $request, $id)
    {
        $brand = Brand::where('parent_id', $id)->first();

        if (!$brand) {
            return $this->error(trans('Attribute not found'), 404);
        }

        if ($request->status != 0 && Product::where('brand_id', $brand->id)->count() > 0) {
            return $this->error(trans('Can not delete brand is mapped with products'), 401);
        }
        $brand->status = 0;
        $brand->save();

        return $this->success(null, trans('Brand deleted'));
    }
}
