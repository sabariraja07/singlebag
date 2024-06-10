<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Partner extends Model
{
	public $timestamps = true;
    protected $table = 'partners';

    protected $casts = [
        'bank_details' => 'array',
        'data' => 'array'
    ]; 

    public function Partner()
    {
        return $this->belongsTo(User::class,'user_id','id');
    }

}