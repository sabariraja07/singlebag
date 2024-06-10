<?php

namespace App\Managers;

use App\Models\Cart;
use App\Models\Coupon;
use App\Models\Channel;
use App\Models\CustomerGroup;
use InvalidArgumentException;
use Illuminate\Support\Collection;
use App\Base\DiscountManagerInterface;
use App\Base\Validation\CouponValidator;
use App\Base\DataTransferObjects\CartDiscount;

class DiscountManager implements DiscountManagerInterface
{
    /**
     * The current channels.
     *
     * @var null|Collection<Channel>
     */
    protected ?Collection $channels = null;

    /**
     * The current customer groups
     *
     * @var null|Collection<CustomerGroup>
     */
    protected ?Collection $customerGroups = null;

    /**
     * The available discounts
     */
    protected ?Collection $discounts = null;

    /**
     * The applied discounts.
     */
    protected Collection $applied;

    /**
     * Instantiate the class.
     */
    public function __construct()
    {
        $this->applied = collect();
        $this->channels = collect();
        $this->customerGroups = collect();
    }

    /**
     * Set a single channel or a collection.
     */
    public function channel(Channel|iterable $channel): self
    {
        $channels = collect(
            !is_iterable($channel) ? [$channel] : $channel
        );

        if ($nonChannel = $channels->filter(fn ($channel) => !$channel instanceof Channel)->first()) {
            throw new InvalidArgumentException(
                __('lunar::exceptions.discounts.invalid_type', [
                    'expected' => Channel::class,
                    'actual' => $nonChannel->getMorphClass(),
                ])
            );
        }

        $this->channels = $channels;

        return $this;
    }

    /**
     * Return the applied channels.
     */
    public function getChannels(): Collection
    {
        return $this->channels;
    }

    /**
     * Returns the available discounts.
     */
    public function getDiscounts(Cart $cart = null): Collection
    {
        if ($this->channels->isEmpty() && $defaultChannel = Channel::getDefault()) {
            $this->channel($defaultChannel);
        }

        return Coupon::active()
            ->usable()
            ->channel($this->channels)
            ->when(
                $cart,
                fn ($query, $value) => $query->products(
                    $value->lines->pluck('purchasable.product_id')->filter()->values()
                )
            )->when(
                $cart?->coupon_code,
                fn ($query, $value) => $query->where('coupon', '=', $value)->orWhereNull('coupon'),
                fn ($query, $value) => $query->whereNull('coupon')
            )
            ->orderBy('id')
            ->get();
    }

    public function getTypes(): Collection
    {
        return collect($this->types)->map(function ($class) {
            return app($class);
        });
    }

    public function addApplied(CartDiscount $cartDiscount): self
    {
        $this->applied->push($cartDiscount);

        return $this;
    }

    public function getApplied(): Collection
    {
        return $this->applied;
    }

    public function apply(Cart $cart): Cart
    {
        if (!$this->discounts) {
            $this->discounts = $this->getDiscounts($cart);
        }

        foreach ($this->discounts as $discount) {
            $cart = $discount->getType()->apply($cart);
        }

        return $cart;
    }

    public function validateCoupon(string $coupon): bool
    {
        return app(
            config('lunar.discounts.coupon_validator', CouponValidator::class)
        )->validate($coupon);
    }
}
