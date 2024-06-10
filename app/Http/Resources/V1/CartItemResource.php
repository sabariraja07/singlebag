<?php

namespace App\Http\Resources\V1;

use Illuminate\Http\Resources\Json\JsonResource;

class CartItemResource extends JsonResource
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
            'quantity' => $this->quantity,
            'discount' => $this->discount,
            'shipping' => $this->shipping,
            'variant' => $this->variant,
            'meta' => $this->meta,
            'unitPrice' => $this->unitPrice,
            'subTotal' => $this->subTotal,
            'subTotalDiscounted' => $this->subTotalDiscounted,
            'shippingTotal' => $this->shippingTotal,
            'discountTotal' => $this->discountTotal,
            'discountBreakdown' => $this->discountBreakdown,
            'taxTotal' => $this->taxTotal,
            'taxBreakdown' => $this->taxBreakdown,
            'total' => $this->total,
            'created_at' => $this->created_at,
            'updated_at' => $this->updated_at,
        ];
    }
}
