<?php

namespace App\Traits;

use App\Models\Price;
use App\Facades\Pricing;

trait HasPrices
{
    /**
     * Get all of the models prices.
     */
    public function prices()
    {
        return $this->hasMany(
            Price::class,
            'variant_id'
        );
    }

    /**
     * Return base prices query.
     *
     * @return \Illuminate\Database\Eloquent\Relations\MorphMany
     */
    public function basePrices()
    {
        return $this->prices()->whereQuantity(1);
    }

    /**
     * Return a PricingManager for this model.
     *
     * @return \App\Managers\PricingManager
     */
    public function pricing()
    {
        return Pricing::for($this);
    }
}
