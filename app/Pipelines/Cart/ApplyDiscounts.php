<?php

namespace App\Pipelines\Cart;

use Closure;
use App\Models\Cart;
use App\Facades\Discounts;

final class ApplyDiscounts
{
    /**
     * Called just before cart totals are calculated.
     *
     * @return void
     */
    public function handle(Cart $cart, Closure $next)
    {
        $cart->discountBreakdown = collect([]);

        Discounts::apply($cart);

        return $next($cart);
    }
}
