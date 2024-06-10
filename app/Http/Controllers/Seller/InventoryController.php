<?php

namespace App\Http\Controllers\Seller;

use App\Models\Stock;
use App\Models\Product;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;

class InventoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $shop_id = current_shop_id();
        if (user_plan_access('inventory') == false) {
            return back();
        }

        $inventories = ProductVariant::with(['stock', 'Product'])
            ->whereHas('product', function ($q) use ($shop_id) {
                return $q->where('shop_id', $shop_id);
            });

        if (!empty($request->src)) {
            $inventories = $inventories->where('sku', 'LIKE', '%' . $request->src . '%');
        }

        if (!empty($request->status)) {
            $inventories = $inventories->whereHas('stock', function ($q) use ($request) {
                return $q->where('status', $request->status == 'in' ? 1 : 0);
            });
        }



        // if (current_shop_type() != 'reseller') {
        //     if (!empty($request->src)) {
        //         $posts = Stock::where('sku', 'LIKE', '%' . $request->src . '%')->whereHas('product', function ($q) use ($shop_id) {
        //             return $q->where('shop_id', $shop_id);
        //         })->with('product')->paginate(50);
        //     } elseif (!empty($request->status)) {
        //         if ($request->status == 'in') {
        //             $posts = Stock::with('product')->where('stock_status', 1)->whereHas('product', function ($q) use ($shop_id) {
        //                 return $q->where('status', '!=', '5')->where('shop_id', $shop_id);
        //             })->paginate(50);
        //         } else {
        //             $posts = Stock::with('product')->where('stock_status', 0)->whereHas('product', function ($q) use ($shop_id) {
        //                 return $q->where('status', '!=', '5')->where('shop_id', $shop_id);
        //             })->paginate(50);
        //         }
        //     } else {
        //         $posts = Stock::with('product')->whereHas('product', function ($q) use ($shop_id) {
        //             return $q->where('status', '!=', '5')->where('shop_id', $shop_id);
        //         })->paginate(30);
        //     }


        //     $src = $request->src ?? '';
        //     $status = $request->status ?? '';
        //     $in_stock = Stock::where('stock_status', 1)->whereHas('product', function ($q) use ($shop_id) {
        //         return $q->where('status', '!=', '5')->where('shop_id', $shop_id);
        //     })->count();
        //     $out_stock = Stock::where('stock_status', 0)->whereHas('product', function ($q) use ($shop_id) {
        //         return $q->where('status', '!=', '5')->where('shop_id', $shop_id);
        //     })->count();
        //     $total = Stock::whereHas('product', function ($q) use ($shop_id) {
        //         return $q->where('status', '!=', '5')->where('shop_id', $shop_id);
        //     })->count();
        // } else {
        //     $in_stock = Stock::where('stock_status', 1)->whereHas('product', function ($q) use ($shop_id) {
        //         return $q->where('status', '!=', '5')->where('shop_id', $shop_id);
        //     })->count();
        //     $out_stock = Stock::where('stock_status', 0)->whereHas('product', function ($q) use ($shop_id) {
        //         return $q->where('status', '!=', '5')->where('shop_id', $shop_id);
        //     })->count();
        //     $total = Stock::whereHas('product', function ($q) use ($shop_id) {
        //         return $q->where('status', '!=', '5')->where('shop_id', $shop_id);
        //     })->count();
        //     $status = $request->status ?? '';
        //     $posts = Product::where('type', 'product')->with('stock');
        //     $posts = $posts->whereHas('ResellerProduct', function ($q) {
        //         $q->where('status', '!=', '5')->where('shop_id', current_shop_id());
        //     })->paginate(30);
        // }

        $inventories = $inventories->paginate(20);

        $in_stock = ProductVariant::whereHas('product', function ($q) use ($shop_id) {
            return $q->where('status', '!=', '5')->where('shop_id', $shop_id);
        })->whereHas('stock', function ($q) use ($request) {
            return $q->where('status', 1);
        })->count();

        $out_stock = ProductVariant::whereHas('product', function ($q) use ($shop_id) {
            return $q->where('status', '!=', '5')->where('shop_id', $shop_id);
        })->whereHas('stock', function ($q) use ($request) {
            return $q->where('status', 0);
        })->count();

        $total = ProductVariant::whereHas('product', function ($q) use ($shop_id) {
            return $q->where('status', '!=', '5')->where('shop_id', $shop_id);
        })->count();

        $src = $request->src ?? '';
        $status = $request->status ?? '';

        return view('seller.inventory.index', compact('inventories', 'total', 'in_stock', 'out_stock', 'status'));
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
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $id = base64_decode($id);
        $stock = Stock::with('variant')->find($id);
        if (empty($stock)) {
            $msg['errors']['shop'] = 'Invalid input.';
            return response()->json($msg, 401);
        }
        $product = Product::find($stock->variant->product_id);
        $shop_id = current_shop_id();
        if ($product->shop_id != $shop_id) {
            die();
        }
        if (!$request->has('stock_qty') || !$request->has('stock_status')) {
            $msg['errors']['shop'] = 'Invalid input.';
            return response()->json($msg, 401);
        } elseif ($request->stock_qty == 0 && $request->status == 1) {
            $msg['errors']['shop'] = 'In stock quantity must not be 0';
            return response()->json($msg, 401);
        } elseif ($request->stock_qty > 0 && $request->status == 0) {
            $msg['errors']['shop'] = 'Out of stock quantity allows only zero';
            return response()->json($msg, 401);
        }

        Stock::updateOrCreate([
            'variant_id' => $request->variant_id,
            'location_id' => $request->location_id ?? null,
        ], [
            'manage_stock' => $request->stock_manage,
            'status' => $request->status ?? 1,
            'quantity' => $request->stock_qty ?? 0,
            'alert_qty' => $request->alert_qty ?? 5,
        ]);

        return response()->json([trans('Stock Update Successfully')]);
    }
}
