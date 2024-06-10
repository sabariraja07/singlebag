<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;

/**
 * @property int $id
 * @property int $product_id
 * @property int $product_target_id
 * @property string $type
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class ProductAssociation extends Model
{
    /**
     * Define the cross sell type.
     */
    const CROSS_SELL = 'cross-sell';

    /**
     * Define the up sell type.
     */
    const UP_SELL = 'up-sell';

    /**
     * Define the alternate type.
     */
    const ALTERNATE = 'alternate';

    /**
     * Define the fillable attributes.
     *
     * @var array
     */
    protected $fillable = [
        'product_id',
        'product_target_id',
        'type',
    ];

    /**
     * Return the parent relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function parent()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    /**
     * Return the parent relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function target()
    {
        return $this->belongsTo(Product::class, 'product_target_id');
    }

    /**
     * Apply the cross sell scope.
     *
     * @return void
     */
    public function scopeCrossSell(Builder $query)
    {
        $query->type(self::CROSS_SELL);
    }

    /**
     * Apply the up sell scope.
     *
     * @return void
     */
    public function scopeUpSell(Builder $query)
    {
        $query->type(self::UP_SELL);
    }

    /**
     * Apply the up alternate scope.
     *
     * @return void
     */
    public function scopeAlternate(Builder $query)
    {
        $query->type(self::ALTERNATE);
    }

    /**
     * Apply the type scope.
     *
     * @param  string  $type
     * @return void
     */
    public function scopeType(Builder $query, $type)
    {
        return $query->whereType($type);
    }
}
