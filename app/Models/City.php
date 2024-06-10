<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class City extends Model
{
    public $timestamps = true;

    public function state()
    {
        return $this->belongsTo(State::class,'state_id','id');
    }

}