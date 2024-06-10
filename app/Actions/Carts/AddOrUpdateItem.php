<?php

namespace App\Actions\Carts;

use App\Models\Cart;
use App\Models\CartItem;
use Illuminate\Support\Arr;
use App\Models\ProductVariant;
use App\Actions\AbstractAction;
use App\Exceptions\InvalidCartItemQuantityException;

class AddOrUpdateItem extends AbstractAction
{
    /**
     * Execute the action.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @param  \Illuminate\Database\Eloquent\Collection  $customerGroups
     * @return \App\Models\CartItem
     */
    public function execute(
        Cart $cart,
        ProductVariant $variant,
        int $quantity = 1,
        array $meta = []
    ): self {
        throw_if(!$quantity, InvalidCartItemQuantityException::class);

        $existing = $this->getCartItem(
            cart: $cart,
            variant: $variant,
            meta: $meta
        );

        if ($existing) {
            $existing->update([
                'quantity' => $existing->quantity + $quantity,
            ]);

            return $this;
        }

        $cart->items()->create([
            'variant_id' => $variant->id,
            'quantity' => $quantity,
            'meta' => $meta,
        ]);

        return $this;
    }

    public function getCartItem(
        Cart $cart,
        ProductVariant $variant,
        array $meta = []
    ): CartItem|null {
        $items = $cart->items()
            ->whereVariantId(
                $variant->id
            )
            ->get();

        return $items->first();
    }
}
