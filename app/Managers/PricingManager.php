<?php

namespace App\Managers;

use App\Models\Currency;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Auth;
use App\Base\PricingManagerInterface;
use Illuminate\Contracts\Auth\Authenticatable;
use App\Base\DataTransferObjects\PricingResponse;
use App\Exceptions\MissingCurrencyPriceException;
use App\Models\ProductVariant;

class PricingManager implements PricingManagerInterface
{
    /**
     * The DTO of the pricing.
     */
    public PricingResponse $pricing;

    /**
     * The instance of the product variant model.
     */
    public ProductVariant $variant;

    /**
     * The instance of the user.
     *
     * @var \Illuminate\Contracts\Auth\Authenticatable
     */
    public ?Authenticatable $user = null;

    /**
     * The instance of the currency.
     *
     * @var \App\Models\Currency
     */
    public ?Currency $currency = null;

    /**
     * The quantity value.
     */
    public int $qty = 1;

    /**
     * The customer groups to check against.
     *
     * @var \Illuminate\Support\Collection
     */
    public ?Collection $customerGroups = null;

    public function __construct()
    {
        if (Auth::check()) {
            $this->user = Auth::user();
        }
    }

    /**
     * Set the variant property.
     *
     * @return self
     */
    public function for(ProductVariant $variant)
    {
        $this->variant = $variant;

        return $this;
    }

    /**
     * Set the user property.
     *
     * @param  \Illuminate\Contracts\Auth\Authenticatable  $user
     * @return self
     */
    public function user(?Authenticatable $user)
    {
        $this->user = $user;

        return $this;
    }

    /**
     * Set the user property to NULL.
     *
     * @return self
     */
    public function guest()
    {
        $this->user = null;

        return $this;
    }

    /**
     * Set the currency property.
     *
     * @param  \App\Models\Currency  $currency
     * @return self
     */
    public function currency(?Currency $currency)
    {
        $this->currency = $currency;

        return $this;
    }

    /**
     * Set the quantity property.
     *
     * @return self
     */
    public function qty(int $qty)
    {
        $this->qty = $qty;

        return $this;
    }

    /**
     * Get the price for the purchasable.
     *
     * @return \App\Base\DataTransferObjects\PricingResponse
     */
    public function get()
    {
        if (!$this->variant) {
            throw new \ErrorException('No purchasable set.');
        }

        if (!$this->currency) {
            $this->currency = Currency::getDefault();
        }

        // Do we have a user?
        if ($this->user && $this->user->customers->count()) {
            $customers = $this->user->customers;
            $customerGroups = $customers->pluck('customerGroups')->flatten();

            if ($customerGroups->count()) {
                $this->customerGroups = $customerGroups;
            }
        }

        $currencyPrices = $this->variant->getPrices()->filter(function ($price) {
            return $price->currency_id == $this->currency->id;
        });

        if (!$currencyPrices->count()) {
            throw new MissingCurrencyPriceException();
        }

        $prices = $currencyPrices->filter(function ($price) {
            // Only fetch prices which have no customer group (available to all) or belong to the customer groups
            // that we are trying to check against.
            return !$price->customer_group_id ||
                $this->customerGroups->pluck('id')->contains($price->customer_group_id);
        })->sortBy('price');

        // Get our base price
        $basePrice = $prices->first(fn ($price) => $price->tier == 1 && !$price->customer_group_id);

        // To start, we'll set the matched price to the base price.
        $matched = $basePrice;

        // If we have customer group prices, we should find the cheapest one and send that back.
        $potentialGroupPrice = $prices->filter(function ($price) {
            return (bool) $price->customer_group_id && $price->tier == 1;
        })->sortBy('price');

        $matched = $potentialGroupPrice->first() ?: $matched;

        // Get all tiers that match for the given quantity. These take priority over the other steps
        // as we could be bulk purchasing.
        $tieredPricing = $prices->filter(function ($price) {
            return $price->tier > 1 && $this->qty >= $price->tier;
        })->sortBy('price');

        $matched = $tieredPricing->first() ?: $matched;

        $this->pricing = new PricingResponse(
            matched: $matched,
            base: $prices->first(fn ($price) => $price->tier == 1),
            tiered: $prices->filter(fn ($price) => $price->tier > 1),
            customerGroupPrices: $prices->filter(fn ($price) => (bool) $price->customer_group_id)
        );

        $response = app(Pipeline::class)
            ->send($this)
            ->through(
                []
            )->then(fn ($pricingManager) => $pricingManager->pricing);

        $this->reset();

        return $response;
    }

    /**
     * Reset the manager into a base instance.
     *
     * @return void
     */
    private function reset()
    {
        $this->qty = 1;
        $this->user = null;
    }
}
