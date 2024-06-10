<?php

namespace App\Facades;

use Illuminate\Support\Facades\Facade;
use App\Base\DiscountManagerInterface;

class Discounts extends Facade
{
    /**
     * {@inheritdoc}
     */
    protected static function getFacadeAccessor()
    {
        return DiscountManagerInterface::class;
    }
}
