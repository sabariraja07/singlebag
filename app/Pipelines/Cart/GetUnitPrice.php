<?php

namespace App\Pipelines\Cart;

use Closure;
use App\DataTypes\Price;
use App\Facades\Pricing;
use App\Models\CartItem;
use App\Models\Price as ModelsPrice;
use Spatie\LaravelBlink\BlinkFacade as Blink;

class GetUnitPrice
{
    /**
     * Called just before cart totals are calculated.
     *
     * @return void
     */
    public function handle(CartItem $cartItem, Closure $next)
    {
        $variant = $cartItem->variant;
        $cart = $cartItem->cart;

        $currency = Blink::once('currency_' . $cart->currency_code, function () use ($cart) {
            return $cart->currency;
        });

        // $priceResponse = Pricing::currency($currency)
        //     ->qty($cartItem->quantity)
        //     ->currency($cart->currency)
        //     ->for($variant)
        //     ->get();

        $price = ModelsPrice::where('variant_id', $variant->id)->where('currency_code', $currency->code)->first();
        // dd($price->price);

        $cartItem->unitPrice = new Price(
            $price ? $price->price->value : 1, //$priceResponse->matched->price->value,
            $cart->currency,
            1, //$variant->getUnitQuantity()
        );

        return $next($cartItem);
    }
}
