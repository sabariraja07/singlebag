<?php

namespace App\Pipelines\Cart;

use Closure;
use App\Models\Cart;
use App\DataTypes\Price;
use App\Models\Product;
use Illuminate\Pipeline\Pipeline;

class CalculateItems
{
    /**
     * Called just before cart totals are calculated.
     *
     * @return void
     */
    public function handle(Cart $cart, Closure $next)
    {
        foreach ($cart->items as $item) {
            $cartItem = app(Pipeline::class)
                ->send($item)
                ->through(
                    [GetUnitPrice::class]
                )->thenReturn(function ($cartItem) {
                    $cartItem->cacheProperties();

                    return $cartItem;
                });

            // $purchasable = $cartItem->purchasable;
            $unitQuantity = $cartItem->quantity ?? 1; //$purchasable->getUnitQuantity();

            $unitPrice = $cartItem->unitPrice->unitDecimal(false) * $cart->currency->factor;

            $subTotal = (int) round($unitPrice * $cartItem->quantity, $cart->currency->decimal_places);

            $product = Product::find($cartItem->variant_id);

            $cartItem->subTotal = new Price($subTotal, $cart->currency, $unitQuantity);
            $cartItem->taxAmount = new Price($product ? (int) ($product->tax * 0.01) : 0, $cart->currency, $unitQuantity);
            $cartItem->total = new Price($subTotal, $cart->currency, $unitQuantity);
            $cartItem->subTotalDiscounted = new Price($subTotal, $cart->currency, $unitQuantity);
            $cartItem->discountTotal = new Price(0, $cart->currency, $unitQuantity);
        }

        return $next($cart);
    }
}
