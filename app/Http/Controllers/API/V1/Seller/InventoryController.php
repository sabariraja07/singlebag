<?php

namespace App\Http\Controllers\API\V1\Seller;

use App\Models\Stock;
use App\Traits\ApiResponser;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use App\Models\Product;
use Illuminate\Support\Facades\Auth;

class InventoryController extends Controller
{
    use ApiResponser;
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $shop_id = current_shop_id();
        if (user_plan_access('inventory') == false) {
            $this->error('Stock Update Faild', 403);
        }

        $posts = Stock::whereHas('term', function ($q) use ($shop_id) {
            return $q->where('shop_id', $shop_id);
        });


        if (!empty($request->src)) {
            $posts = $posts->where('sku', 'LIKE', '%' . $request->src . '%');
        } elseif (!empty($request->status)) {
            if ($request->status == 'in') {
                $posts = $posts->where(function ($q) {
                    $q->where(function ($q) {
                        $q->where('stock_status', 1)
                            ->where('stock_qty', '>',  0);
                    })->orWhere('stock_manage', 0);
                });
            } else {
                $posts = $posts->where(function ($q) {
                    $q->where(function ($q) {
                        $q->where('stock_status', 0)
                            ->orWhere('stock_qty', '<=',  0);
                    })->where('stock_manage', 1);
                });
            }
        }
        $posts = $posts->with(['term', 'term.price', 'term.categories'])->paginate(30);


        $posts->getCollection()->transform(function ($post) {
            return [
                "shop_id" => $post->term->shop_id,
                "id" => $post->id,
                "stock_manage" => $post->stock_manage,
                "in_stock" => $post->term->inStock() ? 1 : 0,
                "stock_qty" => $post->stock_qty,
                "stock_status" => $post->stock_status,
                "sku" => $post->sku,
                "product_id" => $post->term->id,
                "title" => $post->term->title,
                "slug" => $post->term->slug,
                "status" => $post->term->status,
                "featured" => $post->term->featured,
                "created_at" => $post->term->created_at,
                "updated_at" => $post->term->updated_at,
                "img" => $post->term->preview ? $post->term->preview->media->url : "",
                "avg_rating" => $post->avg_rating ?? 0,
                "price" => $post->term->price,
                "categories" => $post->term->categories,
            ];
        });


        $src = $request->src ?? '';
        $status = $request->status ?? '';
        $in_stock = Stock::where('stock_status', 1)->whereHas('term', function ($q) use ($shop_id) {
            return $q->where('shop_id', $shop_id);
        })->count();
        $out_stock = Stock::where('stock_status', 0)->whereHas('term', function ($q) use ($shop_id) {
            return $q->where('shop_id', $shop_id);
        })->count();
        $total = Stock::whereHas('term', function ($q) use ($shop_id) {
            return $q->where('shop_id', $shop_id);
        })->count();

        // $paginate = $toArray;
        // $paginate['data'] = [];
        //$paginate['current_page'] = $posts['current_page'];
        //$paginate = $posts;

        return $this->success(compact('posts', 'total', 'in_stock', 'out_stock', 'status'));
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
        $stock = Stock::with('term')->find($id);
        if (empty($stock)) {
            return $this->error('Invalid Stock Information', 401);
        }
        $product = Product::find($stock->variant->product_id);
        $shop_id = current_shop_id();
        if ($product->shop_id != $shop_id) {
            return $this->error('Invalid Stock Information', 401);
        }

        if (!$request->has('stock_qty') || !$request->has('stock_status')) {
            $msg = 'Invalid input.';
            return $this->error($msg, 401);
        } elseif ($request->stock_qty == 0 && $request->status == 1) {
            $msg = 'In stock quantity must not be 0';
            return $this->error($msg, 401);
        } elseif ($request->stock_qty > 0 && $request->status == 0) {
            $msg = 'Out of stock quantity allows only zero';
            return $this->error($msg, 401);
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

        return $this->success([], 'Stock Update Successfully');
    }
}
