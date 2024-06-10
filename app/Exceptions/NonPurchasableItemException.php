<?php

namespace App\Exceptions;

use Exception;

class NonPurchasableItemException extends Exception
{
    public function __construct(string $classname)
    {
        $this->message = __('non_purchasable_item', [
            'class' => $classname,
        ]);
    }
}
