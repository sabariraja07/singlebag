<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSettlement extends Model
{
    protected $table = 'order_settlements';

    public $timestamps = true;

    protected $fillable = [
        'shop_id',
        'order_id',
        'group_order_id',
        'settlement_id',
        'amount',
        'total',
        'commission',
        'type',
        'status'
    ];

    public function Order()
    {
        return $this->belongsTo(Order::class, 'order_id');
    }

    public function group_order()
    {
        return $this->belongsTo(Order::class, 'group_order_id');
    }

    public function Settlement()
    {
        return $this->belongsTo(Settlement::class, 'settlement_id');
    }
}
