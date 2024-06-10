<?php

namespace App\Actions\Carts;

use App\DataTypes\Price;
use App\Facades\Pricing;
use App\Models\CartItem;
use App\Base\CartItemModifiers;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;

class CalculateItemSubtotal
{
    /**
     * Execute the action.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $customerGroups
     * @return \App\Models\CartItem
     */
    public function execute(
        CartItem $cartItem,
        Collection $customerGroups
    ) {
        $purchasable = $cartItem->purchasable;
        $cart = $cartItem->cart;
        $unitQuantity = $purchasable->getUnitQuantity();

        // we check if any cart item modifiers have already specified a unit price in their calculating() method
        if (!($price = $cartItem->unitPrice) instanceof Price) {
            $priceResponse = Pricing::currency($cart->currency)
                ->qty($cartItem->quantity)
                ->currency($cart->currency)
                ->customerGroups($customerGroups)
                ->for($purchasable)
                ->get();

            $price = new Price(
                $priceResponse->matched->price->value,
                $cart->currency,
                $purchasable->getUnitQuantity()
            );
        }

        $unitPrice = (int) round(
            (($price->decimal / $purchasable->getUnitQuantity())
                * $cart->currency->factor),
            $cart->currency->decimal_places
        );

        $cartItem->subTotal = new Price($unitPrice * $cartItem->quantity, $cart->currency, $unitQuantity);
        $cartItem->unitPrice = new Price($unitPrice, $cart->currency, $unitQuantity);

        $pipeline = app(Pipeline::class)
            ->through(
                $this->getModifiers()->toArray()
            );

        return $pipeline->send($cartItem)->via('subtotalled')->thenReturn();
    }

    /**
     * Return the cart item modifiers.
     *
     * @return \Illuminate\Support\Collection
     */
    private function getModifiers()
    {
        return app(CartItemModifiers::class)->getModifiers();
    }
}
