<?php

namespace App\Payments;

class PaymentRefund
{
    public function __construct(
        public bool $success = false,
        public ?string $message = null
    ) {
        //
    }
}
