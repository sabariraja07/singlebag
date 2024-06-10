<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OldProductOption extends Model
{
    protected $table = 'old_product_options';
    public $timestamps = false;

    protected $appends = ['price'];

    public function getPriceAttribute()
    {
        if (current_shop_type() == 'reseller') {
            $option = $this->ResellerOption()->where('shop_id', current_shop_id())->first();
            return $option->amount ?? $this->amount;
        }

        return $this->amount;
    }

    public function Options()
    {
        return $this->belongsTo(ProductValue::class, 'p_id', 'id');
    }

    public function Variants()
    {
        return $this->hasMany(ProductValue::class, 'p_id')->where('type', 0);
    }

    public function ResellerOption()
    {
        return $this->hasOne(ResellerOption::class, 'product_option_id')->where('shop_id', current_shop_id());
    }
}
