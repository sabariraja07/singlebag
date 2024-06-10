<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DeliveryAgent extends Model
{
    

    
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'avathar',
        'agent_id',
        'image_id',
        'status',
        'customer_id',
    ];
}
