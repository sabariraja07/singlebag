<?php

namespace App\Models;

use App\Casts\Price;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $order_id
 * @property string $purchasable_type
 * @property int $purchasable_id
 * @property string $type
 * @property string $description
 * @property ?string $option
 * @property string $identifier
 * @property int $unit_price
 * @property int $unit_quantity
 * @property int $quantity
 * @property int $sub_total
 * @property int $discount_total
 * @property array $tax_breakdown
 * @property int $tax_total
 * @property int $total
 * @property ?string $notes
 * @property ?array $meta
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class OrderItem extends Model
{
    use LogsActivity;

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
        'unit_quantity' => 'integer',
        'quantity' => 'integer',
        'meta' => 'object',
        'tax_breakdown' => 'array', TaxBreakdown::class,
        'unit_price' => Price::class,
        'sub_total' => Price::class,
        'tax_total' => Price::class,
        'discount_total' => Price::class,
        'total' => Price::class,
    ];

    /**
     * Return the order relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function order()
    {
        return $this->belongsTo(Order::class);
    }

    /**
     * Return the currency relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasOneThrough
     */
    public function currency()
    {
        return $this->hasOneThrough(
            Currency::class,
            Order::class,
            'id',
            'code',
            'order_id',
            'currency_code'
        );
    }
}
