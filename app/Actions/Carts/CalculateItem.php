<?php

namespace App\Actions\Carts;

use App\Facades\Taxes;
use App\Models\CartItem;
use App\DataTypes\Price;
use App\Base\Addressable;
use Illuminate\Support\Collection;

class CalculateItem
{
    /**
     * Execute the action.
     *
     * @param  \Illuminate\Database\Eloquent\Collection  $customerGroups
     * @return \App\Models\CartItem
     */
    public function execute(
        CartItem $cartItem,
        Collection $customerGroups,
        Addressable $shippingAddress = null,
        Addressable $billingAddress = null
    ) {
        $purchasable = $cartItem->purchasable;
        $cart = $cartItem->cart;
        $unitQuantity = $purchasable->getUnitQuantity();

        $cartItem = app(CalculateItemSubtotal::class)->execute($cartItem, $customerGroups);

        if (!$cartItem->discountTotal) {
            $cartItem->discountTotal = new Price(0, $cart->currency, $unitQuantity);
        }

        $subTotal = $cartItem->subTotal->value - $cartItem->discountTotal->value;

        // $taxBreakDown = Taxes::setShippingAddress($shippingAddress)
        //     ->setBillingAddress($billingAddress)
        //     ->setCurrency($cart->currency)
        //     ->setPurchasable($purchasable)
        //     ->setCartItem($cartItem)
        //     ->getBreakdown($subTotal);

        $taxTotal = 0; //$taxBreakDown->amounts->sum('price.value');

        $cartItem->taxBreakdown = []; //$taxBreakDown;
        $cartItem->taxAmount = new Price($taxTotal, $cart->currency, $unitQuantity);
        $cartItem->total = new Price($subTotal + $taxTotal, $cart->currency, $unitQuantity);

        return $cartItem;
    }
}
