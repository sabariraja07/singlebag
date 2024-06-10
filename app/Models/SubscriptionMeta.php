<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SubscriptionMeta extends Model
{
    
    public $timestamps = false;

    public function activeorder()
    {
        return $this->hasOne(Subscription::class, 'user_id', 'user_id')->where('status', 1);
    }
}
