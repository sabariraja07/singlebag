<?php

namespace App\Models;

use App\Casts\Price as CastsPrice;
use App\DataTypes\Money;
use Illuminate\Database\Eloquent\Model;

/**
 * @property int $id
 * @property ?int $currency_code
 * @property int $variant_id
 * @property int $price
 * @property ?int $compare_price
 * @property int $quantity
 * @property ?\Illuminate\Support\Carbon $start_at
 * @property ?\Illuminate\Support\Carbon $ends_at
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 */
class Price extends Model
{
    protected $guarded = [];

    protected $casts = [
        'price' => CastsPrice::class,
        'compare_price' => CastsPrice::class,
        'offer_price' => CastsPrice::class,
        'selling_price' => CastsPrice::class,
    ];

    public function currency()
    {
        return $this->belongsTo(Currency::class);
    }
}
