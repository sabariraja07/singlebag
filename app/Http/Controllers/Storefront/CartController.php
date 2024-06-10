<?php

namespace App\Http\Controllers\Storefront;

use Carbon\Carbon;
use App\Models\Coupon;
use App\Facades\CartSession;
use Illuminate\Http\Request;
use App\Models\ProductVariant;
use App\Http\Controllers\Controller;
use App\Http\Resources\V1\CartResource;
use Illuminate\Database\Eloquent\Builder;

class CartController extends Controller
{
    public function index()
    {
        $cart = CartSession::current();
        return response()->json(new CartResource($cart));
    }

    public function store(Request $request)
    {
        $variant = ProductVariant::whereHas('product', function (Builder $query) {
            $query->whereShopId(current_shop_id());
        })->findOrFail($request->variant_id);
        $cart = CartSession::current();
        $meta = [];
        $cart->add($variant, $request->quantity ?? 1, $meta, true);
        return response()->json(new CartResource($cart));
    }

    public function update_item(Request $request, $id)
    {
        $cart = CartSession::current();
        $cart->updateItem($id, $request->quantity ?? 1);
        return response()->json(new CartResource($cart));
    }

    public function remove_item(Request $request, $id)
    {
        $cart = CartSession::current();
        $cart->remove($id);
        return response()->json(new CartResource($cart));
    }

    public function apply_coupon(Request $request)
    {
        $coupon = Coupon::byShop()->where('code', $request->code)->first();

        if (empty($coupon)) {
            return response()->json(['message' => trans('Coupon code not Found.')], 400);
        }

        $coupon = Coupon::byShop()->isActive()->where('code', $request->code)->first();
        $mydate = Carbon::now()->toDateString();

        if (empty($coupon)) {
            return response()->json(['message' => trans('Coupon is Inactive')], 400);
        }

        if ($coupon->expiry_date >= $mydate) {
            $cart = CartSession::current();
            $cart->applyCoupon($coupon);
            return response()->json(['message' => trans('Coupon Applied')]);
        }

        return response()->json(['message' => trans('Sorry, this coupon is expired')], 400);
    }

    public function clear($id)
    {
        $cart = CartSession::current();
        $cart->clear();
        return response()->json(new CartResource($cart));
    }

    public function update_address(Request $request)
    {
        $cart = CartSession::current();
        $cart->addAddress($request->shipping_address ?? null, 'shipping');
        return response()->json(new CartResource($cart));
    }
}
