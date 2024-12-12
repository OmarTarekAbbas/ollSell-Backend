<?php

namespace Modules\Order\Http\Resources\Order;

use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Order\Http\Resources\Order\SimplifiedOrderItemResource;
use Modules\CoreData\Http\Resources\Status\SimplifiedStatusResource;

class SimplifiedOrderResource extends JsonResource
{
    /**
     * Transform the resource into an array.
     *
     * @param \Illuminate\Http\Request $request
     * @return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'customer_name' => $this->customerName ?? "",
            'customer_phone' => $this->customerPhone ?? "",
            'grand_total' => round($this->grandTotal, 2) ?? 0,
            'items' => SimplifiedOrderItemResource::collection($this->orderItems), // Simplified items
            'status' => new SimplifiedStatusResource($this->status), // Simplified status
            'created_at' => $this->created_at, // Simplified status
        ];
    }
}
