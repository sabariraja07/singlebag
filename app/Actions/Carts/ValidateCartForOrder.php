<?php

namespace App\Actions\Carts;

use App\Models\Cart;
use Illuminate\Support\Facades\Validator;
use App\Exceptions\Carts\OrderExistsException;
use App\Exceptions\Carts\ShippingOptionMissingException;
use App\Exceptions\Carts\BillingAddressMissingException;
use App\Exceptions\Carts\ShippingAddressMissingException;
use App\Exceptions\Carts\BillingAddressIncompleteException;
use App\Exceptions\Carts\ShippingAddressIncompleteException;

class ValidateCartForOrder
{
    /**
     * Execute the action.
     *
     * @return void
     */
    public function execute(
        Cart $cart
    ) {
        // Does this cart already have an order?
        if ($cart->order) {
            throw new OrderExistsException('message');
        }

        // Do we have a billing address?
        if (!$cart->billingAddress) {
            throw new BillingAddressMissingException(
                __('billing_missing')
            );
        }

        $billingValidator = Validator::make(
            $cart->billingAddress->toArray(),
            $this->getAddressRules()
        );

        if ($billingValidator->fails()) {
            throw new BillingAddressIncompleteException();
        }

        // Is this cart shippable and if so, does it have a shipping address.
        if ($cart->isShippable()) {
            if (!$cart->shippingAddress) {
                throw new ShippingAddressMissingException(
                    __('shipping_missing')
                );
            }

            $shippingValidator = Validator::make(
                $cart->shippingAddress->toArray(),
                $this->getAddressRules()
            );

            if ($shippingValidator->fails()) {
                throw new ShippingAddressIncompleteException();
            }

            // Do we have a shipping option applied?
            if (!$cart->getShippingOption()) {
                throw new ShippingOptionMissingException();
            }
        }
    }

    /**
     * Return the address rules for validation.
     *
     * @return array
     */
    private function getAddressRules()
    {
        return [
            'country_id' => 'required',
            'first_name' => 'required',
            'line_one' => 'required',
            'city' => 'required',
            'postcode' => 'required',
        ];
    }
}
