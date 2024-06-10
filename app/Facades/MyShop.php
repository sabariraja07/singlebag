<?php

namespace Statamic\Facades;

use App\Services\Shop;
use Illuminate\Support\Facades\Facade;

class MyShop extends Facade
{
    protected static function getFacadeAccessor()
    {
        return Shop::class;
    }
}
