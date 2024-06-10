<?php

namespace App\Casts;

use App\DataTypes\Money;
use App\Models\Currency;
use Illuminate\Support\Facades\Validator;
use App\DataTypes\Price as PriceDataType;
use Illuminate\Contracts\Database\Eloquent\CastsAttributes;

class Price implements CastsAttributes
{
    /**
     * Cast the given value.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed
     */
    public function get($model, string $key, $value, array $attributes)
    {
        $currency = $model->currency ?: Currency::getDefault();

        /**
         * Make it an integer based on currency requirements.
         */
        $value = preg_replace('/[^0-9]/', '', $value);

        Validator::make([
            $key => $value,
        ], [
            $key => 'nullable|numeric',
        ])->validate();

        // return new Money((int) $value, $currency->code);

        return new PriceDataType(
            (int) $value,
            $currency,
            $model->priceable->unit_quantity ?? $model->unit_quantity ?? 1,
        );
    }

    /**
     * Prepare the given value for storage.
     *
     * @param  \Illuminate\Database\Eloquent\Model  $model
     * @param  string  $key
     * @param  mixed  $value
     * @param  array  $attributes
     * @return mixed|array
     */
    public function set($model, string $key, $value, array $attributes)
    {
        // return $value;
        return [
            $key => $value,
        ];
    }
}
