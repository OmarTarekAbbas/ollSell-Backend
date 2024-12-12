<?php

namespace Modules\Order\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;

class SimplifiedOrderItemResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        $productJson = json_decode($this->product_json);
        $variantJson = json_decode($this->variants_json);

        return [
            'product_name' => $productJson->product_name ?? null,  // Product name from JSON
            'quantity' => $this->quantity ?? 0,                    // Quantity of the item
            'unit_price' => round($this->unitPrice, 2) ?? 0,       // Unit price
            'total_price' => round($this->totalPrice, 2) ?? 0,     // Total price of the order item
            'variant_option' => $variantJson->option ?? null,      // Variant option
        ];
    }
}
