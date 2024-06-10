<?php

namespace App\Models;

use App\DataTypes\Price;
use App\Traits\LogsActivity;
use App\Traits\CachesProperties;
use App\Pipelines\Cart\Calculate;
use Illuminate\Pipeline\Pipeline;
use Illuminate\Support\Collection;
use App\Actions\Carts\CreateOrder;
use Illuminate\Support\Facades\DB;
use App\Actions\Carts\AssociateUser;
use Illuminate\Foundation\Auth\User;
use App\Actions\Carts\UpdateCartItem;
use App\Validation\Cart\ItemQuantity;
use App\Pipelines\Cart\CalculateItems;
use App\Actions\Carts\AddOrUpdateItem;
use Illuminate\Database\Eloquent\Model;
use App\Actions\Carts\SetShippingOption;
use Illuminate\Database\Eloquent\Builder;
use App\Base\ValueObjects\Cart\TaxBreakdown;
use App\Base\ValueObjects\Cart\DiscountBreakdown;
use App\Validation\Cart\ValidateCartForOrderCreation;

/**
 * @property int $id
 * @property ?int $user_id
 * @property ?int $merged_id
 * @property string $currency_code
 * @property int $channel_id
 * @property ?int $order_id
 * @property ?string $coupon_code
 * @property ?array $shipping_address
 * @property ?array $billing_address
 * @property ?\Illuminate\Support\Carbon $completed_at
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */

class Cart extends Model
{
    use LogsActivity;
    use CachesProperties;

    /**
     * The cart sub total.
     * Sum of cart item amounts, before tax, shipping and cart-level discounts.
     */
    public ?Price $subTotal = null;

    /**
     * The cart sub total.
     * Sum of cart item amounts, before tax, shipping minus discount totals.
     */
    public ?Price $subTotalDiscounted = null;

    /**
     * The shipping total for the cart.
     */
    public ?Price $shippingTotal = null;

    /**
     * The discount total.
     * Sum of all cart item discounts and cart-level discounts.
     */
    public ?Price $discountTotal = null;

    /**
     * All the discount breakdowns for the cart.
     *
     * @var null|Collection<DiscountBreakdown>
     */
    public ?Collection $discountBreakdown = null;

    /**
     * The cart total.
     * Sum of the cart-item amounts, shipping and tax, minus cart-level discount amount.
     */
    public ?Price $total = null;

    /**
     * The cart tax total.
     * Sum of all tax to pay across cart items and shipping.
     */
    public ?Price $taxTotal = null;

    /**
     * All the tax breakdowns for the cart.
     *
     * @var null|Collection<TaxBreakdown>
     */
    public ?Collection $taxBreakdown = null;

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * The attributes that should be cast.
     *
     * @var array
     */
    protected $casts = [
        'completed_at' => 'datetime',
        'meta' => 'object',
    ];

    public $cachableProperties = [
        'subTotal',
        'subTotalDiscounted',
        'shippingTotal',
        'discountTotal',
        'discountBreakdown',
        'taxTotal',
        'taxBreakdown',
        'total',
    ];

    /**
     * Return the cart items relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany
     */
    public function items()
    {
        return $this->hasMany(CartItem::class, 'cart_id', 'id');
    }

    /**
     * Return the currency relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function currency()
    {
        return $this->belongsTo(Currency::class, 'currency_code', 'code');
    }

    /**
     * Return the user relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
        return $this->morphTo();
    }

    public function scopeUnmerged($query)
    {
        return $query->whereNull('merged_id');
    }

    /**
     * Return the order relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    public function Address()
    {
        return $this->belongsTo(Address::class, 'shipping_address');
    }

    /**
     * Apply scope to get active cart.
     *
     * @return void
     */
    public function scopeActive(Builder $query)
    {
        return $query->whereDoesntHave('order');
    }

    /**
     * Calculate the cart totals and cache the result.
     */
    public function calculate(): Cart
    {
        $cart = app(Pipeline::class)
            ->send($this)
            ->through(
                [
                    CalculateItems::class,
                    Calculate::class,
                ]
            )->thenReturn();

        return $cart->cacheProperties();
    }

    /**
     * Add or update a item to the cart
     */
    public function add(ProductVariant $variant, int $quantity = 1, array $meta = [], bool $refresh = true): Cart
    {
        foreach ([ItemQuantity::class] as $action) {
            app($action)->using(
                cart: $this,
                quantity: $quantity,
                meta: $meta
            )->validate();
        }

        return app(AddOrUpdateItem::class)->execute($this, $variant, $quantity, $meta)
            ->then(fn () => $refresh ? $this->refresh()->calculate() : $this);
    }

    /**
     * Add cart items.
     *
     * @return bool
     */
    public function addItems(iterable $items)
    {
        DB::transaction(function () use ($items) {
            collect($items)->each(function ($item) {
                $this->add(
                    variant: $item['variant'],
                    quantity: $item['quantity'],
                    meta: (array) ($item['meta'] ?? null),
                    refresh: false
                );
            });
        });

        return $this->refresh()->calculate();
    }

    /**
     * Remove a cart item
     */
    public function remove(int $cartItemId, bool $refresh = true): Cart
    {
        foreach ([] as $action) {
            app($action)->using(
                cart: $this,
                cartItemId: $cartItemId,
            )->validate();
        }

        return app(RemoveCart::class)->execute($this, $cartItemId)
            ->then(fn () => $refresh ? $this->refresh()->calculate() : $this);
    }

    /**
     * Update cart item
     *
     * @param  array  $meta
     */
    public function updateItem(int $cartItemId, int $quantity, $meta = null, bool $refresh = true): Cart
    {
        foreach ([] as $action) {
            app($action)->using(
                cart: $this,
                cartItemId: $cartItemId,
                quantity: $quantity,
                meta: $meta
            )->validate();
        }

        return app(UpdateCartItem::class)->execute($cartItemId, $quantity, $meta)
            ->then(fn () => $refresh ? $this->refresh()->calculate() : $this);
    }

    /**
     * Update cart items.
     *
     * @return \App\Models\Cart
     */
    public function updateItems(Collection $items)
    {
        DB::transaction(function () use ($items) {
            $items->each(function ($item) {
                $this->updateItem(
                    cartItemId: $item['id'],
                    quantity: $item['quantity'],
                    meta: $item['meta'] ?? null,
                    refresh: false
                );
            });
        });

        return $this->refresh()->calculate();
    }

    /**
     * Deletes all cart items.
     */
    public function clear()
    {
        $this->items()->delete();

        return $this->refresh()->calculate();
    }

    public function applyCoupon($coupon)
    {
        DB::transaction(function () use ($coupon) {
            $this->update(['coupon_code' => $coupon->code]);
        });

        return $this->refresh()->calculate();
    }

    /**
     * Associate a user to the cart
     *
     * @param  string  $policy
     * @param  bool  $refresh
     * @return Cart
     */
    public function associate(User|Customer $user, $policy = 'merge', $refresh = true)
    {
        return app(AssociateUser::class)->execute($this, $user, $policy)
            ->then(fn () => $refresh ? $this->refresh()->calculate() : $this);
    }

    /**
     * Add an address to the Cart.
     */
    public function addAddress(int $address, string $type, bool $refresh = true): Cart
    {
        // foreach ([] as $action) {
        //     app($action)->using(
        //         cart: $this,
        //         address: $address,
        //         type: $type,
        //     )->validate();
        // }

        $this->update(['shipping_address' => $address]);

        return $this->refresh()->calculate();
    }

    /**
     * Set the shipping address.
     *
     * @return \App\Models\Cart
     */
    public function setShippingAddress(int $address)
    {
        return $this->addAddress($address, 'shipping');
    }

    /**
     * Set the billing address.
     *
     * @return self
     */
    public function setBillingAddress(int $address)
    {
        return $this->addAddress($address, 'billing');
    }

    /**
     * Set the shipping option to the shipping address.
     */
    public function setShippingOption($option, $refresh = true): Cart
    {
        foreach ([] as $action) {
            app($action)->using(
                cart: $this,
                shippingOption: $option,
            )->validate();
        }

        return app(SetShippingOption::class)->execute($this, $option)
            ->then(fn () => $refresh ? $this->refresh()->calculate() : $this);
    }

    /**
     * Returns whether the cart has shippable items.
     *
     * @return bool
     */
    public function isShippable()
    {
        return (bool) $this->items->filter(function ($item) {
            return $item->isShippable();
        })->count();
    }

    /**
     * Create an order from the Cart.
     *
     * @return Cart
     */
    public function createOrder(): Order
    {
        foreach ([ValidateCartForOrderCreation::class] as $action) {
            app($action)->using(
                cart: $this,
            )->validate();
        }

        return app(CreateOrder::class)->execute($this->refresh()->calculate())
            ->then(fn () => $this->order->refresh());
    }

    /**
     * Returns whether a cart has enough info to create an order.
     *
     * @return bool
     */
    public function canCreateOrder()
    {
        $passes = true;

        foreach ([ValidateCartForOrderCreation::class] as $action) {
            try {
                app($action)->using(
                    cart: $this,
                )->validate();
            } catch (\Exception $e) {
                $passes = false;
            }
        }

        return $passes;
    }
}
