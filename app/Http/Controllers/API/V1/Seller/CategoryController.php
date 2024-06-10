<?php

namespace App\Http\Controllers\API\V1\Seller;

use App\Models\Product;
use App\Models\Category;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\ProductCategory;
use App\Http\Controllers\Controller;

class CategoryController extends Controller
{
    public function index(Request $request)
    {
        $categories = Category::where('shop_id', current_shop_id())->where('type', 'category')->where('status', '!=', 5);
        if ($request->src) {
            $categories = $categories->where($request->search_type, 'LIKE', '%' . $request->src . '%');
        }
        $categories = $categories->whereNull('p_id')->with('childrens')->latest()->paginate(30)->toArray();

        return response()->json(array_merge($categories, ['status' => 'success']));
    }

    public function store(Request $request)
    {
        $category_count = Category::where('shop_id', current_shop_id())->where('type', 'category')->count();

        if (user_plan_limit('category_limit', $category_count)) {
            return $this->error(trans('Maximum category limit exceeded'), 401);
        }

        if ($request->has('file') || $request->has('icon')) {
            $size = 0;
            if ($request->has('file'))  $size += $request->file('file')->getSize();
            if ($request->has('icon')) $size += $request->file('icon')->getSize();
            if (user_plan_limit('storage', storageToMB($size))) {
                return $this->error(trans('Maximum storage limit exceeded'), 401);
            }
        }

        $request->validate([
            'name' => 'required',
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
        $category->user_id = auth()->id();;
        $category->status = $request->status ?? 1;
        $category->shop_id = current_shop_id();
        $category->save();

        if ($request->has('file')) {
            $category->deleteFile('image');
            $category->uploadFile($request->file, $category->shop_id  . '/category/' . $category->id . '/image', 'image');
        }

        if ($request->has('icon')) {
            $category->deleteFile('icon');
            $category->uploadFile($request->file, $category->shop_id  . '/category/' . $category->id . '/icon', 'icon');
        }

        return $this->success($category, 'Category Created Successfully....!!!');
    }

    public function show($id)
    {
        $category = Category::where('shop_id', current_shop_id())->findOrFail($id);
        return $this->success($category);
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'name' => 'required',
            'file' => 'image|max:500',
        ]);

        if (ProductCategory::where('category_id', $id)->count() > 0) {
            return response()->json(trans('Can not inactive category is mapped with products'), 401);
        }

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
                return $this->error(trans('Maximum storage limit exceeded'), 401);
            }

            $category->deleteFile('image');
            $category->uploadFile($request->file, $category->shop_id  . '/category/' . $category->id . '/image', 'image');
        }

        if ($request->has('icon')) {
            $size = $request->file('icon')->getSize();
            if (user_plan_limit('storage', storageToMB($size - $category->getFileSize('icon')))) {
                return $this->error(trans('Maximum storage limit exceeded'), 401);
            }

            $category->deleteFile('icon');
            $category->uploadFile($request->file, $category->shop_id  . '/category/' . $category->id . '/icon', 'icon');
        }

        return $this->success($category, 'Category Updated Successfully....!!!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $category = Category::findOrFail($id);

        if ($request->status != 0 && ProductCategory::where('category_id', $id)->count() > 0) {
            return $this->error(trans('Can not delete category is mapped with products'), 401);
        }

        $category->status = 0;
        $category->save();

        return $this->success(null, trans('Category deleted'));
    }
}
