<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Template extends Model
{
    

    public function installed()
    {
    	return $this->hasMany(Shop::class, 'template_id');
    }
}
