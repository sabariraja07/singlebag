<?php

namespace App\Models;

use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;

class Plan extends Model
{
    use FileUploadTrait;

    // protected $appends = ['image'];

    // public function getImageAttribute()
    // {
    //     $attachment = $this->getFile('image');
    //     return $attachment->url ?? asset('uploads/default.png');
    // }
    
    public function users()
    {
    	return $this->hasMany(Subscriber::class);
    }

    public function active_users()
    {
    	return $this->hasMany(Subscription::class)->where('status',1);
    }

    public function Subscriptions()
    {
    	return $this->hasMany(Subscription::class)->where('status',1);
    }
   
}
