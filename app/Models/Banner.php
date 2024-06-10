<?php

namespace App\Models;

use App\Traits\FileUploadTrait;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use FileUploadTrait;

    protected $table = 'banners';

    public $timestamps = true;

    protected $fillable = [
        'title',
        'description',
        'btn_text',
        'url',
        'meta',
        'position',
        'type',
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

    public function Shop()
	{
		return $this->belongsTo(Shop::class, 'shop_id');
	}

    public function User()
	{
		return $this->belongsTo(User::class, 'user_id');
	}
    
    public function scopeByShop($query)
    {
        return $query->where('shop_id', current_shop_id());
    }

    public function scopeIsActive($query, $status = 1)
    {
        return $query->where('status', $status);
    }
}
