<?php

namespace Modules\Order\Http\Resources\Order;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CoreData\Http\Resources\Status\StatusResource;

class OrderRefundResource extends JsonResource
{
    /**
     * It returns the data in the form of an array.
     * 
     * param request The incoming request.
     * 
     * return array
     */
    public function toArray($request)
    {
        return [
            'id' => $this->id,
            'orderItem' =>  OrderRefundItemResource::collection($this->orderRefundItems),
            'status' => new StatusResource($this->status),
            'statusLog' => OrderStatusRefundResource::collection($this->orderRefund),
            'order_id' => $this->order_id,
            'reason' => $this->reason ?? null,
            'tracking_number' => $this->tracking_number ?? null,
            'pdf_label' => $this->pdf_label ?? null,
            'deliveryDate' => $this->deliveryDate ?? null,
            'orderDate' => Carbon::parse($this->created_at)->translatedFormat('l d F Y') . ' in ' . date("h:i", strtotime($this->created_at)) . ' ' . date("a", strtotime($this->created_at)),

        ];
    }
}
