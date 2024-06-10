<?php

namespace App\Observers;

use App\Models\OrderItem;
use App\Base\Purchasable;
use App\Exceptions\NonPurchasableItemException;

class OrderItemObserver
{
    /**
     * Handle the OrderLine "creating" event.
     *
     * @return void
     */
    public function creating(OrderItem $orderItem)
    {
        if ($orderItem->type != 'shipping' && !$orderItem->purchasable instanceof Purchasable) {
            throw new NonPurchasableItemException($orderItem->purchasable_type);
        }
    }

    /**
     * Handle the OrderItem "updated" event.
     *
     * @return void
     */
    public function updating(OrderItem $orderItem)
    {
        if ($orderItem->type != 'shipping' && !$orderItem->purchasable instanceof Purchasable) {
            throw new NonPurchasableItemException($orderItem->purchasable_type);
        }
    }
}
