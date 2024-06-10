<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PostMedia extends Model
{
	public $timestamps = false;
    protected $table = 'post_media';

    public function media()
    {
    	return $this->belongsTo(Media::class)->select('id','url');
    }
}
