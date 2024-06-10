<?php

namespace App\Http\Controllers\API\V1\Seller;

use App\Models\Attribute;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Traits\ApiResponser;
use App\Models\ProductAttribute;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class AttributeController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $user = Auth::user();
        $attributes = Attribute::where('shop_id', current_shop_id())
            ->whereNull('parent_id')
            ->with('children')
            ->withCount('featured_with_product_count');

        if ($request->src) {
            $attributes = $attributes->where($request->search_type, 'LIKE', '%' . $request->src . '%');
        }
        $attributes = $attributes->latest()->paginate(30)->toArray();

        return response()->json(array_merge($attributes, ['status' => 'success']));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $attributes_count = Attribute::where('shop_id', current_shop_id())->count();
        if (user_plan_limit('variation_limit', $attributes_count)) {
            return $this->error('Maximum Attribute limit exceeded.', 401);
        }

        $request->validate([
            'title' => 'required',
        ]);

        $attribute = new Attribute();
        $attribute->name = $request->title;
        $attribute->slug = Str::slug($request->title);
        $attribute->featured = $request->featured ?? 0;
        $attribute->status = $request->status ?? 1;
        $attribute->user_id = auth()->id();
        $attribute->shop_id = current_shop_id();
        $attribute->save();

        return $this->success($attribute, 'Attribute Created Successfully....!!!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $attribute = Attribute::where('parent_id', $id)
            ->where('shop_id', current_shop_id())->with('parent')->withCount('attribute_with_product_count')->get();

        return $this->success($attribute);
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

        $attribute = Attribute::where('parent_id', $id)->first();

        if (!$attribute) {
            return $this->error(trans('Attribute not found'), 404);
        }

        if ($request->status != 0 && ProductAttribute::where('attribute_id', $attribute->id)->count() != 0) {
            return $this->error(trans('Can not inactive attribute is mapped with products'), 401);
        }

        $attribute = Attribute::where('shop_id', current_shop_id())->findorFail($id);
        $attribute->name = $request->title;
        $attribute->featured = $request->featured ?? 0;
        $attribute->status = $request->status ?? 1;
        $attribute->save();

        return $this->success($attribute, trans('Attribute Updated'));
    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(Request $request, $id)
    {
        $attribute = Attribute::where('parent_id', $id)->first();

        if (!$attribute) {
            return $this->error(trans('Attribute not found'), 404);
        }

        if ($request->status != 0 && ProductAttribute::where('attribute_id', $attribute->id)->count() > 0) {
            return $this->error(trans('Can not delete attribute is mapped with products'), 401);
        }
        ProductAttribute::where('attribute_id', $attribute->id)->delete();
        $attribute->status = 0;
        $attribute->save();

        return $this->success(null, trans('Attribute deleted'));
    }
}
