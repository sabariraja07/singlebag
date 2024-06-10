<?php

namespace App\Base;

use App\Models\Currency;
use Illuminate\Contracts\Auth\Authenticatable;

interface PricingManagerInterface
{
    /**
     * Set the user property.
     *
     * @return self
     */
    public function user(Authenticatable $user);

    /**
     * Set the currency property.
     *
     * @return self
     */
    public function currency(Currency $currency);

    /**
     * Set the quantity property.
     *
     * @return self
     */
    public function qty(int $qty);
}
