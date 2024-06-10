<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class TaxClass extends Model
{

    const SHIPPING_ADDRESS = 'shipping';
    const BILLING_ADDRESS = 'billing';
    const SHOP_ADDRESS = 'shop';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['based_on'];

    /**
     * Perform any actions required after the model boots.
     *
     * @return void
     */
    // protected static function booted()
    // {
    //     static::saved(function ($taxClass) {
    //         $taxClass->saveRates(request('rates', []));
    //     });
    // }

    /**
     * Get tag list.
     *
     * @return \Illuminate\Database\Eloquent\Collection
     */
    public static function list()
    {
        return Cache::tags('tax_classes')->rememberForever(md5('tax_classes.list:' . locale()), function () {
            return self::all()->sortBy('label')->pluck('label', 'id');
        });
    }

    public function findTaxRate($addresses)
    {
        return $this->taxRates()
            ->findByAddress($this->determineAddress($addresses))
            ->first();
    }

    public function determineAddress($addresses)
    {
        if ($this->based_on === self::SHIPPING_ADDRESS) {
            return $addresses['shipping'] ?? [];
        }

        if ($this->based_on === self::BILLING_ADDRESS) {
            return $addresses['billing'] ?? [];
        }

        if ($this->based_on === self::SHOP_ADDRESS) {
            return [
                'address_1' => '',
                'address_2' => '',
                'city' => '',
                'state' => '',
                'zip' => '',
                'country' => '',
            ];
        }

        return [];
    }

    public function taxRates()
    {
        return $this->hasMany(TaxRate::class)->orderBy('position');
    }

    // public function saveRates($rates = [])
    // {
    //     $ids = $this->getDeleteCandidates($rates);

    //     if ($ids->isNotEmpty()) {
    //         $this->taxRates()->whereIn('id', $ids)->delete();
    //     }

    //     foreach (array_reset_index($rates) as $index => $rate) {
    //         $this->taxRates()->updateOrCreate(
    //             ['id' => $rate['id']],
    //             $rate + ['position' => $index]
    //         );
    //     }
    // }

    // private function getDeleteCandidates($rates = [])
    // {
    //     return $this->taxRates()
    //         ->pluck('id')
    //         ->diff(array_pluck($rates, 'id'));
    // }
}
