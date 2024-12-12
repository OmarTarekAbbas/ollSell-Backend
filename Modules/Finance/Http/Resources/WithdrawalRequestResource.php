<?php

namespace Modules\Finance\Http\Resources;

use Illuminate\Http\Request;
use Illuminate\Http\Resources\Json\JsonResource;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperPaymentResource;
use Modules\Acl\Http\Resources\Dropshipper\DropshipperResource;
use Modules\Order\Http\Resources\Order\OrderResource;
use Modules\Order\Service\OrderService;

class WithdrawalRequestResource extends JsonResource
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
        $orders = json_decode($this->order_id,true);
        if($orders)
        {
            $orders = app(OrderService::Class)->findBy(new Request(['id' => $orders]));
        }else{
            $orders = [];
        }
        return [
            'id' => $this->id,
            'status' => $this->status,
            'amount' => $this->amount,
            'total_amount_dropshipper' => $this->total_amount_dropshipper,
            'withdraw_dropshipper' => $this->withdraw_dropshipper,
            'balance_dropshipper' => $this->balance_dropshipper,
            'reason' => $this->reason??'',
            'avatar' =>  $this->avatar ? getFile($this->avatar->file ?? null, pathType()['ip'], getFileNameServer($this->avatar)) : asset('dashboard') . '/assets/media/svg/avatars/blank.svg',
            'dropshipper' => new DropshipperResource($this->dropshipper),
            'payment' => new DropshipperPaymentResource($this->dropshipper_payment),
            'order' => OrderResource::collection($orders),
        ];
    }

}
