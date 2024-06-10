<?php

namespace App\Models;

use App\Models\Order;
use App\DataTypes\Money;
use Illuminate\Database\Eloquent\Model;

class TaxRate extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['country', 'state', 'city', 'zip_code', 'position'];

    public function orders()
    {
        return $this->belongsToMany(Order::class, 'order_taxes')
            ->using(OrderTax::class)
            ->as('order_tax')
            ->withPivot('amount');
    }

    public function scopeFindByAddress($query, $address)
    {
        $address = $this->mergeDefaultAddress($address);

        $query->whereIn('country', [$address['country'], '*'])
            ->whereIn('state', [$address['state'], '*'])
            ->whereIn('city', [$address['city'], '*'])
            ->whereIn('zip', [$address['zip'], '*'])
            ->orderByRaw($this->rawOrderClause(), [
                $address['country'],
                $address['state'],
                $address['city'],
                $address['zip'],
            ])
            ->orderBy('position');
    }

    private function mergeDefaultAddress($address)
    {
        return $address += ['country' => null, 'state' => null, 'city' => null, 'zip' => null];
    }

    private function rawOrderClause()
    {
        return "(
            CASE WHEN country = ? THEN 1 ELSE 0 END +
            CASE WHEN state = ? THEN 1 ELSE 0 END +
            CASE WHEN city = ? THEN 1 ELSE 0 END +
            CASE WHEN zip = ? THEN 1 ELSE 0 END
        ) DESC";
    }

    public function getCountryAttribute($country)
    {
        return $country === '*' ? null : $country;
    }

    public function setCountryAttribute($country)
    {
        $this->attributes['country'] = $country ?: '*';
    }

    public function getStateAttribute($state)
    {
        return $state === '*' ? null : $state;
    }

    public function setStateAttribute($state)
    {
        $this->attributes['state'] = $state ?: '*';
    }

    public function getCityAttribute($city)
    {
        return $city === '*' ? null : $city;
    }

    public function setCityAttribute($city)
    {
        $this->attributes['city'] = $city ?: '*';
    }

    public function getZipAttribute($zip)
    {
        return $zip === '*' ? null : $zip;
    }

    public function setZipAttribute($zip)
    {
        $this->attributes['zip'] = $zip ?: '*';
    }

    public function getTotalAttribute($total)
    {
        return Money::inDefaultCurrency($total);
    }

    public function tax_class()
    {
        return $this->belongsTo(TaxClass::class, 'tax_class_id');
    }
}
