<?php

namespace App\Models;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\Model;

class Page extends Model
{
    protected $table = 'pages';

    protected static function boot()
    {
        parent::boot();
        static::saving(function ($model) {
            if (!empty($model->title)) {
                $slug = Str::slug($model->title, '-');
                $i = 0;
                $temp = $slug;
                while (!$model->whereSlug($temp)->whereShopId($model->shop_id)->whereNotIn('id', [$model->id ?? 0])->count() == 0) {
                    $temp = $slug .  ($i == 0 ? '' : '-' . $i);
                    $i++;
                }
                $model->attributes['slug'] = $temp;
            }
        });
    }

    public $timestamps = true;

    protected $fillable = [
        'title',
        'slug',
        'content',
        'meta',
        'keywords',
        'type',
        'status',
        'user_id',
        'shop_id',
    ];

    protected $casts = [
        'meta' => 'json'
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

    public function scopeByShop($query)
    {
        return $query->where('shop_id', current_shop_id());
    }

    public function scopeIsActive($query, $status = 1)
    {
        return $query->where('status', $status);
    }

    public function seo()
    {
        return  $this->morphOne(Seo::class, 'page');
    }
}
