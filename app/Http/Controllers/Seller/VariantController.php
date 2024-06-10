<?php

namespace App\Http\Controllers\Seller;

use App\Models\Stock;
use App\Models\Attribute;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use App\Http\Controllers\Controller;

class VariantController extends Controller
{

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {

    $validatedData = $request->validate([
      'attribute' => 'required',
      'variation' => 'required',
      'sku' => 'required',
    ]);

    $attr = new Attribute;
    $attr->category_id = $request->attribute;
    $attr->variation_id = $request->variation;
    $attr->user_id = Auth::id();
    $attr->weight = $request->weight ?? 0;
    $attr->product_id = $request->id;
    $attr->price = $request->price;
    $attr->save();



    $stock = new Stock;
    $stock->attribute_id = $attr->id;
    $stock->stock_manage = $request->stock_status ?? 0;
    $stock->stock_qty = $request->stock_qty ?? 1;
    $stock->sku = $request->sku;
    $stock->save();

    return response()->json([trans('Attribute Added Successfully')]);
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
    $id = base64_decode($id);
    $attr =  Attribute::where('shop_id', current_shop_id())->findorFail($id);
    $attr->weight = $request->weight ?? 0;
    $attr->price = $request->price;
    $attr->save();



    $stock = Stock::where('attribute_id', $id)->first();;
    $stock->stock_manage = $request->stock_manage ?? 0;
    $stock->stock_qty = $request->stock_qty;
    $stock->stock_status = $request->stock_status;
    if ($request->has('sku')) {
      $stock->sku = $request->sku;
    }
    $stock->save();

    return response()->json([trans('Attribute Updated Successfully')]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Request $request)
  {
    $request->validate(
      [
        'method' => 'required'
      ],
      [
        'method.required' => 'Please Select Action'
      ]
    );
    $id = base64_decode($request->id);
    $attr = Attribute::where('shop_id', current_shop_id())->findorFail($id);
    $attr->delete();

    return response()->json([trans('Variation Deleted Successfully')]);
  }
}
