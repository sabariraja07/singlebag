<?php

namespace App\Pipelines\Cart;

use Closure;
use App\Models\Cart;
use App\Models\Coupon;
use App\DataTypes\Price;
use Illuminate\Support\Collection;

class Calculate
{
    /**
     * Called just before cart totals are calculated.
     *
     * @return void
     */
    public function handle(Cart $cart, Closure $next)
    {
        $discountTotal = $cart->items->sum('discountTotal.value');

        $subTotal = $cart->items->sum('subTotal.value');

        // $subTotalDiscounted =  $cart->items->sum(function ($item) {
        //     return $item->subTotalDiscounted ?
        //         $item->subTotalDiscounted->value :
        //         $item->subTotal->value;
        // });

        $subTotalDiscounted = 0;
        if (!empty($cart->coupon_code)) {
            $coupon = Coupon::byShop()->where('code', $cart->coupon_code)->first();
            $subTotalDiscounted = $coupon ? $subTotal * ($coupon->amount * 0.01)  : 0;
        }

        $total = $cart->items->sum('total.value');

        // Get the shipping address
        if ($shippingAddress = $cart->shippingAddress) {
            if ($shippingAddress->shippingSubTotal) {
                $subTotal += $shippingAddress->shippingSubTotal?->value;
                $total += $shippingAddress->shippingTotal?->value;
            }
        }

        $cart->subTotal = new Price($subTotal, $cart->currency, 1);
        $cart->subTotalDiscounted = new Price((int)  $subTotalDiscounted, $cart->currency, 1);
        $cart->subTotal = new Price($subTotal, $cart->currency, 1);
        $cart->discountTotal = new Price($discountTotal, $cart->currency, 1);
        $cart->total = new Price($total, $cart->currency, 1);

        return $next($cart);
    }
}
