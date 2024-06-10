<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CartResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return array|\Illuminate\Contracts\Support\Arrayable|\JsonSerializable
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'shop_id' => $this->shop_id,
            'user_id' => $this->user_id,
            'merged_id' => $this->merged_id,
            'currency_code' => $this->currency_code,
            'currency' => $this->currency,
            'channel_id' => $this->channel_id,
            'order_id' => $this->order_id,
            'address' => $this->address,
            // 'billing_address' => $this->billing_address,
            'coupon_code' => $this->coupon_code,
            'completed_at' => $this->completed_at,
            'meta' => $this->meta,
            'subTotal' => $this->subTotal,
            'subTotalDiscounted' => $this->subTotalDiscounted,
            'shippingTotal' => $this->shippingTotal,
            'discountTotal' => $this->discountTotal,
            'discountBreakdown' => $this->discountBreakdown,
            'taxTotal' => $this->taxTotal,
            'taxBreakdown' => $this->taxBreakdown,
            'total' => $this->total,
            // 'currency' => $this->currency,
            'items' => CartItemResource::collection($this->items),
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
