<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settlement extends Model
{
	public $timestamps = true;
    protected $table = 'settlements';

    protected $casts = [
        'start_date' => 'datetime',
        'end_date' => 'datetime',
        'paid_at' => 'datetime',
        'settlement_date' => 'datetime',
        'bank_details' => 'json',
    ];

    protected static function boot()
    {
        parent::boot();

        static::created(function ($settlement) {
            $settlement->invoice_no = $settlement->generateInvoiceNo();
            $settlement->save();
        });
    }

    private function generateInvoiceNo()
    {
        $invoiceNo = static::latest('id')->skip(1)->value('invoice_no') ?? 'SETMT01';
        if (isset($invoiceNo[-1]) && is_numeric($invoiceNo[-1])) {
            $invoiceNo = preg_replace_callback('/(\d+)$/', function ($mathces) {
                return str_pad($mathces[1] + 1, 4, '0', STR_PAD_LEFT);
            }, $invoiceNo);
        }
        return $invoiceNo;
    }
    
    


    public function Subscriptions()
    {
       return $this->hasMany(Subscription::class, 'settlement_id');
    }

    public function User()
    {
        return $this->belongsTo(User::class, 'user_id');
    }

    public function Orders()
    {
        return $this->belongsToMany(Order::class, 'order_settlements', 'order_id', 'settlement_id');
    }

    public function Shop()
    {
        return $this->belongsTo(Shop::class, 'shop_id');
    }
}