<?php

namespace App\Payments;

class PaymentCapture
{
    public function __construct(
        public bool $success = false,
        public string $message = ''
    ) {
        //
    }
}
