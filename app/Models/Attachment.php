<?php

namespace App\Models;

// use Spatie\MediaLibrary\HasMe dia;
use Illuminate\Database\Eloquent\Model;
// use Spatie\MediaLibrary\InteractsWithMedia;
use Spatie\MediaLibrary\MediaCollections\Models\Media;

class Attachment extends Model // implements HasMedia
{
    // use InteractsWithMedia;

    protected $table = 'attachments';

    // protected static function boot()
    // {
    //     parent::boot();
    //     static::creating(function ($model) {
    //     });
    // }

    public $timestamps = true;

    protected $fillable = [
        'model_type',
        'model_id',
        'name',
        'mime_type',
        'disk',
        'size',
        'path',
        'type',
        'responsive_images',
        'status',
        'shop_id',
        'user_id'
    ];
    
    protected $casts = [
        'responsive_images' => 'json'
    ];

    protected $attributes = [];

    public function Shop()
	{
		return $this->belongsTo(Shop::class, 'shop_id');
	}

    public function User()
	{
		return $this->belongsTo(User::class, 'user_id');
	}

    // public function registerMediaConversions(Media $media = null): void
    // {
    //     // $this->addMediaConversion('thumbnail')
    //     //     ->width(368)
    //     //     ->height(232)
    //     //     ->extractVideoFrameAtSecond(1)
    //     //     ->performOnCollections('videos');
    //     $this->addMediaConversion('thumbnail')
    //         ->width(368)
    //         ->height(232)
    //         ->nonQueued();
    // }
    
}