<?php

namespace Modules\Support\Eloquent;

use Illuminate\Support\Str;
use Illuminate\Database\Eloquent\SoftDeletes;
use Illuminate\Support\Arr;

trait Sluggable
{
    /**
     * Boot the trait.
     *
     * @return void
     */
    public static function bootSluggable()
    {
        static::creating(function ($entity) {
            $entity->setSlug();
        });
    }

    /**
     * Set the slug attribute.
     *
     * @param string $value
     * @return void
     */
    public function setSlug($value = null)
    {
        if (is_null($value)) {
            $value = $this->getAttribute($this->slugAttribute);
        }

        $this->attributes['slug'] = $this->generateSlug($value);
    }

    /**
     * Generate slug by the given value.
     *
     * @param string $value
     * @return string
     */
    private function generateSlug($value)
    {
        $slug = Str::slug($value) ?: \slugify($value);

        $query = $this->where('slug', $slug)->withoutGlobalScope('active');

        if (Arr::has(class_uses($this), SoftDeletes::class)) {
            $query->withTrashed();
        }

        if ($query->exists()) {
            $slug .= '-' . Str::random(8);
        }

        return $slug;
    }
}