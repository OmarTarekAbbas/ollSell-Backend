<?php

namespace Modules\Order\Http\Resources\Order;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CoreData\Http\Resources\Status\StatusResource;

class OrderStatusResource extends JsonResource
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
            'orderId' => $this->order_id,
            'status' => new StatusResource($this->status),
            'statusTitle' => getStatusTitle($this->status_id),
            'statusDescription' => getStatusDescription($this->status_id),
            'statusColor' => $this->statusColor($this->status_id),
            'created_at' => Carbon::parse($this->created_at)->translatedFormat('l d F Y') . ' in ' . date("h:i", strtotime($this->created_at)) . ' ' . date("a", strtotime($this->created_at)),
        ];
    }
}
