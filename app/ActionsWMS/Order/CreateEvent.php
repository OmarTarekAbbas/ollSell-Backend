<?php

namespace App\ActionsWMS\Order;

use App\ActionsWMS\BaseAction;
use Carbon\Carbon;
use Modules\Order\Actions\Order\AymakanCreateShipmentOrderAction;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\WmsOrderStatus;

//todo move to integration module salla

/**
 * @property string merchant example "1029864349"
 * @property string created_at example "Wed Jun 30 2021 12:16:25 GMT+030"
 * @property string event example "product.created"
 * @property array data @see
 *     https://docs.salla.dev/docs/merchent/openapi.json/components/schemas/ProductsWebhookResponse
 */
class CreateEvent extends BaseAction
{
    public function handle()
    {
        $order = Order::where('id', $this->request['data']['order_id'])->first();
        if($order)
        {
            $check = WmsOrderStatus::where('order_id', $order->id)
                ->where('status', $this->request['data']['order_status'])->first();
            if(!$check)
            {
                WmsOrderStatus::create([
                    'order_id' => $order->id,
                    'status' => $this->request['data']['order_status'],
                    'created_at' => Carbon::parse($this->request['data']['order_created_at'])->format('Y-m-d H:i:s')
                ]);
            }
            App(AymakanCreateShipmentOrderAction::class)->getOrderByReference($order);
            return true;
        }
        return false;
    }
}
