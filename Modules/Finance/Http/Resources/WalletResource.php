<?php

namespace Modules\Finance\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperPaymentResource;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperResource;
use Modules\Order\Http\Resources\Order\OrderResource;
use Modules\Order\Service\OrderService;

class WalletResource extends JsonResource
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
            'id' => $this->order_id,
            'amount' => $this->profitRatio,
            'charger_at' => $this->earning_date,
        ];
    }

}
