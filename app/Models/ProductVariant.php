<?php

namespace App\Models;

use App\Traits\HasPrices;
use App\Traits\LogsActivity;
use App\Traits\HasDimensions;
use Illuminate\Support\Collection;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property int $product_id
 * @property int $tax_class_id
 * @property array $attributes
 * @property ?string $tax_ref
 * @property int $unit_quantity
 * @property ?string $sku
 * @property ?string $gtin
 * @property ?string $mpn
 * @property ?string $ean
 * @property ?float $length
 * @property ?float $width
 * @property ?float $height
 * @property ?string $package_unit
 * @property ?float $weight_value
 * @property ?string $weight_unit
 * @property ?float $volume_value
 * @property ?string $volume_unit
 * @property bool $shippable
 * @property int $backorder
 * @property string $purchasable
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 * @property ?\Illuminate\Support\Carbon $deleted_at
 */
class ProductVariant extends Model
{
    use HasPrices;
    use LogsActivity;
    use HasDimensions;

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (!empty($model->sku)) {
                $i = 0;
                $temp = $model->sku;
                while (!$model->whereProductId($model->product_id)->whereNotIn('id', [$model->id ?? 0])->count() == 0) {
                    $temp = $model->sku .  ($i == 0 ? '' : '-' . $i);
                    $i++;
                }
                $model->attributes['sku'] = $temp;
            }
        });
    }

    /**
     * Define the guarded attributes.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * {@inheritDoc}
     */
    protected $casts = [
        'requires_shipping' => 'bool',
        'options' => 'array', //AsAttributeData::class,
    ];

    /**
     * The related product.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function Product()
    {
        return $this->belongsTo(Product::class, 'product_id');
    }

    public function Stock()
    {
        return $this->hasOne(Stock::class, 'variant_id');
    }

    /**
     * Return the tax class relationship.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function taxClass()
    {
        return $this->belongsTo(TaxClass::class);
    }

    /**
     * Return the related product option values.
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsToMany
     */
    public function values()
    {
        return $this->belongsToMany(
            ProductOptionValue::class,
            "product_option_value_variants",
            'variant_id',
            'value_id'
        )->withTimestamps();
    }

    public function getPrices(): Collection
    {
        return $this->prices;
    }

    /**
     * Return the tax class.
     *
     * @return \App\Models\TaxClass
     */
    public function getTaxClass()
    {
        return $this->taxClass;
    }

    /**
     * {@inheritDoc}
     */
    public function getType()
    {
        return $this->shippable ? 'physical' : 'digital';
    }

    /**
     * {@inheritDoc}
     */
    public function isShippable()
    {
        return $this->shippable;
    }

    /**
     * {@inheritDoc}
     */
    public function getOption()
    {
        return $this->values->map(fn ($value) => $value->name)->join(', ');
    }

    /**
     * {@inheritDoc}
     */
    public function getOptions()
    {
        return $this->values->map(fn ($value) => $value->name);
    }
}
