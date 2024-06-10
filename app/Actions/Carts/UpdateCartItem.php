<?php

namespace App\Actions\Carts;

use App\Models\CartItem;
use App\Actions\AbstractAction;
use Illuminate\Support\Facades\DB;

class UpdateCartItem extends AbstractAction
{
    /**
     * Execute the action.
     *
     * @param  \App\Models\CartItem  $cartItem
     * @param  \Illuminate\Database\Eloquent\Collection  $customerGroups
     * @return \App\Models\CartItem
     */
    public function execute(
        int $cartItemId,
        int $quantity,
        $meta = null
    ): self {
        DB::transaction(function () use ($cartItemId, $quantity, $meta) {
            $data = [
                'quantity' => $quantity,
            ];

            if ($meta) {
                if (is_object($meta)) {
                    $meta = (array) $meta;
                }
                $data['meta'] = $meta;
            }

            CartItem::whereId($cartItemId)->update($data);
        });

        return $this;
    }
}
