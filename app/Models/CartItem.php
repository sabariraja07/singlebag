<?php

namespace App\Models;

use App\DataTypes\Price;
use App\Traits\LogsActivity;
use App\Traits\CachesProperties;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $cart_id
 * @property int $variant_id
 * @property int $quantity
 * @property ?array $meta
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class CartItem extends Model
{
    use LogsActivity;
    use CachesProperties;

    public $cachableProperties = [
        'unitPrice',
        'subTotal',
        'shippingTotal',
        'subTotalDiscounted',
        'discountTotal',
        'discountBreakdown',
        'taxTotal',
        'taxBreakdown',
        'total',
    ];

    /**
     * The cart item unit price.
     */
    public ?Price $unitPrice = null;

    /**
     * The cart item sub total.
     */
    public ?Price $subTotal = null;

    /**
     * The discounted sub total
     */
    public ?Price $subTotalDiscounted = null;

    /**
     * The discount total.
     */
    public ?Price $discountTotal = null;

    /**
     * The discount breakdown total.
     */
    public ?Collection $discountBreakdown = null;

    /**
     * The cart item tax amount.
     */
    public ?Price $taxAmount = null;

    /**
     * The discount breakdown total.
     */
    public ?Collection $taxBreakdown = null;

    /**
     * The cart item total.
     */
    public ?Price $total = null;

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
        'quantity' => 'integer',
        'meta' => 'object',
    ];

    /**
     * Return the cart relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function cart()
    {
        return $this->belongsTo(Cart::class);
    }

    /**
     * Return the tax class relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function taxClass()
    {
        return $this->hasOneThrough(
            TaxClass::class,
            $this->purchasable_type,
            'tax_class_id',
            'id'
        );
    }

    public function Variant()
    {
        return $this->belongsTo(
            ProductVariant::class,
            'variant_id',
        );
    }
}
