<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ResellerOption extends Model
{
    protected $table = 'reseller_options';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'price',
        'amount',
        'amount_type',
        'product_option_id',
        'status',
        'shop_id',
    ];

    protected $casts = [
        // 'meta' => 'json'
    ];

    protected $attributes = [];

}