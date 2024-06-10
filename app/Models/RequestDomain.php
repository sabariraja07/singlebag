<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RequestDomain extends Model
{
    

    public function Shop()
    {
    	return $this->belongsTo(Shop::class);
    }

    public function parentdomain()
    {
    	return $this->belongsTo(Domain::class,'domain_id');
    }
}
