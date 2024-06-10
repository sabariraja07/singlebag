<?php

namespace App\Models;

use Illuminate\Support\Facades\Cache;
use Illuminate\Database\Eloquent\Model;

class Currency extends Model
{
    public $timestamps = true;

    public static function booted()
    {
        static::saved(function ($model) {
            Cache::forget(md5("exchange_rate.{$model->code}"));
        });
    }

    public static function getDefault()
    {
        return cache()->rememberForever('default_Currency', function () {
            return Currency::where('code', 'INR')->first();
        });

        // return Currency::where('code', 'INR')->first();
    }

    public static function for($currency_code)
    {
        return Cache::rememberForever(md5("exchange_rate.{$currency_code}"), function () use ($currency_code) {
            return static::where('code', $currency_code)->value('exchange_rate');
        });
    }

    public function getFactorAttribute()
    {
        if ($this->decimal_places < 1) {
            return 1;
        }

        return sprintf("1%0{$this->decimal_places}d", 0);
    }
}
