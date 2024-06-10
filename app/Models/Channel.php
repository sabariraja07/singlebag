<?php

namespace App\Models;

use Illuminate\Support\Str;
use App\Traits\LogsActivity;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

/**
 * @property int $id
 * @property string $name
 * @property string $type
 * @property bool $default
 * @property ?string $url
 * @property ?\Illuminate\Support\Carbon $created_at
 * @property ?\Illuminate\Support\Carbon $updated_at
 * @property ?\Illuminate\Support\Carbon $deleted_at
 */
class Channel extends Model
{
    use SoftDeletes;
    use LogsActivity;

    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    /**
     * Mutator for formatting the type to a slug.
     *
     * @param  string  $val
     * @return void
     */
    public function setTypeAttribute($val)
    {
        $this->attributes['type'] = Str::slug($val);
    }

    /**
     * Get the parent channelable model.
     */
    public function channelable()
    {
        return $this->morphTo();
    }

    public static function getDefault()
    {
        return cache()->rememberForever('default_Channel', function () {
            return Channel::first();
        });
    }
}
