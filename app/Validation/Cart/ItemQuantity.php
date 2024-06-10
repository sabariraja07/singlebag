<?php

namespace App\Validation\Cart;

use App\Validation\BaseValidator;

class ItemQuantity extends BaseValidator
{
    /**
     * {@inheritDoc}
     */
    public function validate(): bool
    {
        $quantity = $this->parameters['quantity'] ?? 0;

        if ($quantity < 1) {
            $this->fail(
                'cart',
                __('exceptions.invalid_cart_line_quantity', [
                    'quantity' => $quantity,
                ])
            );
        }

        if ($quantity > 1000000) {
            $this->fail(
                'cart',
                __('exceptions.maximum_cart_line_quantity', [
                    'quantity' => 1000000,
                ])
            );
        }

        return $this->pass();
    }
}
