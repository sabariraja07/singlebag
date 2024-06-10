<?php

namespace App\Models;

use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;

class PaymentMethod extends Model
{
    use FileUploadTrait;
    
    protected $table = 'payment_methods';

    public $timestamps = true;

    protected $fillable = [
        'name',
        'slug',
        'description',
        'meta',
        'status',
        'user_id',
        'shop_id',
    ];

    protected $casts = [
        'meta' => 'json'
    ];

    protected $attributes = [];
    
    protected $appends = ['image'];

    public function getImageAttribute()
    {
        $attachment = $this->getFile('image');
        return $attachment->url ?? asset('uploads/default.png');
    }

    public function Gateways()
    {
        return $this->hasMany(Gateway::class, 'payment_method', 'slug');
    }

    public function Gateway()
    {
        return $this->hasOne(Gateway::class, 'payment_method', 'slug')->where('shop_id', current_shop_id());
    }

    public function scopeActiveGateways()
    {
        return $this->with(['gateway' => function($q) {
            $q->where('shop_id', current_shop_id());
        }]);
    }

}