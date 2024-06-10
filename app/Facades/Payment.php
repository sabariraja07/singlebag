<?php

namespace App\Facades;

use App\Base\PaymentManagerInterface;
use Illuminate\Support\Facades\Facade;

class Payment extends Facade
{
    public static function getFacadeAccessor()
    {
        return PaymentManagerInterface::class;
    }
}
