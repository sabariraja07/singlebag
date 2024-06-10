<?php

namespace App\Observers;

use App\Models\CartItem;
use App\Base\Purchasable;
use App\Exceptions\NonPurchasableItemException;

class CartItemObserver
{
    /**
     * Handle the CartItem "creating" event.
     *
     * @return void
     */
    public function creating(CartItem $cartItem)
    {
        if (!$cartItem->purchasable instanceof Purchasable) {
            throw new NonPurchasableItemException($cartItem->purchasable_type);
        }
    }

    /**
     * Handle the CartItem "updated" event.
     *
     * @return void
     */
    public function updating(CartItem $cartItem)
    {
        if (!$cartItem->purchasable instanceof Purchasable) {
            throw new NonPurchasableItemException($cartItem->purchasable_type);
        }
    }
}
