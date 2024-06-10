<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Base\StorefrontSessionInterface;

class StorefrontSession extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return StorefrontSessionInterface::class;
    }
}
