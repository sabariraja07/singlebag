<?php

namespace App\Payments;

class PaymentAuthorize
{
    public function __construct(
        public bool $success = false,
        public ?string $message = null
    ) {
        //
    }
}
