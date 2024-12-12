<?php

namespace Modules\Order\Http\Resources\Order;

use Carbon\Carbon;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\CoreData\Http\Resources\Status\StatusResource;
use Modules\Order\Traits\StatusText;

class OrderStatusRefundResource extends JsonResource
{
    use StatusText;
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
            'orderRefundId' => $this->order_refund_id,
            'status' => new StatusResource($this->status),
            'statusTitle' => $this->statusTitle($this->status_id),
            'statusDescription' => $this->statusDescription($this->status_id),
            'statusColor' => $this->statusColor($this->status_id),
            'created_at' => Carbon::parse($this->created_at)->translatedFormat('l d F Y') . ' in ' . date("h:i", strtotime($this->created_at)) . ' ' . date("a", strtotime($this->created_at)),

        ];
    }
}
