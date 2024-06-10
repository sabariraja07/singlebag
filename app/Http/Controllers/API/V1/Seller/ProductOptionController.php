<?php

namespace App\Http\Controllers\API\V1\Seller;

use Illuminate\Support\Str;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Models\ProductOption;
use App\Models\ProductOptionValue;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;

class ProductOptionController extends Controller
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
        $options = ProductOption::where('shop_id', current_shop_id())->with('values');

        if ($request->src) {
            $options = $options->where($request->search_type, 'LIKE', '%' . $request->src . '%');
        }
        $options = $options->latest()->paginate(30)->toArray();

        return response()->json(array_merge($options, ['options' => true]));
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'title' => 'required',
        ]);

        $option = new ProductOption();
        $option->name = $request->title;
        $option->type = $request->type;
        // $option->status = $request->status ?? 1;
        $option->shop_id = current_shop_id();
        $option->save();

        return $this->success($option, trans('Option Created Successfully....!!!'));
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $option = ProductOption::with('values')->findOrFail($id);

        return $this->success($option);
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

        $option = ProductOption::where('shop_id', current_shop_id())->findorFail($id);
        $option->name = $request->title;
        $option->type = $request->type;
        // $option->status = $request->status ?? 1;
        $option->save();

        return $this->success($option, trans('Option Updated'));
    }

    public function store_value(Request $request)
    {
        $request->validate([
            'title' => 'required',
            'product_option' => 'required',
        ]);

        $value = new ProductOptionValue();
        $value->name = $request->title;
        $value->product_option_id = $request->product_option;
        // $value->status = $request->status ?? 1;
        $value->save();

        return $this->success($value, trans('Option Value Created Successfully....!!!'));
    }

    public function show_value($id)
    {
        $option = ProductOptionValue::where('product_option_id', $id)->get();

        return $this->success($option);
    }

    public function update_value(Request $request, $id)
    {
        $request->validate([
            'title' => 'required',
            'product_option' => 'required',
        ]);

        $value = ProductOptionValue::findorFail($id);
        $value->name = $request->title;
        $value->product_option_id = $request->product_option;
        // $value->status = $request->status ?? 1;
        $value->save();

        return $this->success($value, trans('Option Value updated Successfully....!!!'));
    }

    public function destroy_value(Request $request, $id)
    {
        $value = ProductOptionValue::where('shop_id', current_shop_id())->findorFail($id);
        // $value->status = 0;
        $value->save();

        return $this->success($value, 'Option value Deleted');
    }
}
