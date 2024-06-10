<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property string $name
 * @property int $position
 * @property ?string $handle
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class ProductOption extends Model
{
    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Get the values.
     *
     * @return \Illuminate\Database\Eloquent\Relations\HasMany<ProductOptionValue>
     */
    public function values()
    {
        return $this->hasMany(ProductOptionValue::class)->orderBy('position');
    }
    public function value()
    {
        return $this->hasMany(ProductOptionValue::class, 'product_option_id');
    }
}
