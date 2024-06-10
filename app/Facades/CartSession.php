<?php

namespace App\Facades;

use App\Base\CartSessionInterface;
use Illuminate\Support\Facades\Facade;

class CartSession extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return CartSessionInterface::class;
    }
}
