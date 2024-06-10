<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    /**
     * Define which attributes should be
     * protected from mass assignment.
     *
     * @var array
     */
    protected $guarded = [];

    public function method()
    {
        return $this->belongsTo(Category::class, 'category_id', 'id')->select('id', 'name');
    }
}
