<?php

namespace App\Base;

use App\Models\Cart;
use Illuminate\Support\Collection;

interface DiscountManagerInterface
{
    /**
     * Add a discount type by classname
     *
     * @param  string  $classname
     */
    public function addType($classname): self;

    /**
     * Return the available discount types.
     */
    public function getTypes(): Collection;

    /**
     * Add an applied discount
     */
    public function addApplied(): self;

    /**
     * Return the applied discounts
     */
    public function getApplied(): Collection;

    /**
     * Apply discounts for a given cart.
     */
    public function apply(Cart $cart): Cart;

    /**
     * Validate a given coupon against all system discounts.
     */
    public function validateCoupon(string $coupon): bool;
}
