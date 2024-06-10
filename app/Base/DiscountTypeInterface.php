<?php

namespace App\Base;

use App\Models\Cart;
use App\Models\CartItem;

interface DiscountTypeInterface
{
    /**
     * Return the name of the discount type.
     */
    public function getName(): string;

    /**
     * Execute and apply the discount if conditions are met.
     *
     * @param  CartItem  $cartItem
     * @return CartItem
     */
    public function apply(Cart $cart): Cart;
}
