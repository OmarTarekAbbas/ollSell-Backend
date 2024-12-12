<?php

namespace Modules\Order\Actions\Order;

use Modules\Order\Enums\OrderEnum;

use Carbon\Carbon;
use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderStatusAymakan;
use Modules\Order\Repositories\OrderRepository;

class WebhooksShippingStatusOrderAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function executes an order by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the order data.
     *
     * param Request request The  parameter is an instance of the Request class, which is
     * typically used to retrieve data from the HTTP request. It contains information such as the
     * request method, headers, and input data.
     *
     * @return a boolean value. If the data is successfully saved, it will return true. Otherwise, it
     * will return false.
     */
    public function execute($request, $getDataAymakanArray)
    {

        $order = Order::where('tracking_number', $getDataAymakanArray['tracking'])->first();

        if (!$order) {
            return false;
        }

        $trackShipment = App(TrackShipmentOrderAction::class)->execute($order);
        $trackingInfos = $trackShipment['data']['shipments'][0]['tracking_info'];
        $checkRequestedDeliveryAymakan=true;
        foreach (array_reverse($trackingInfos) as $key => $trackingInfo) {

            $checkOrderStatusAymakan =  OrderStatusAymakan::where('order_id', $order->id)
                ->where('status', $trackingInfo['status_code'])
                ->where('created_at', $trackingInfo['created_at'])
                ->first();
                  if($trackShipment['data']['shipments'][0]['requested_delivery_date']){
                    $checkRequestedDeliveryAymakan =  OrderStatusAymakan::where('order_id', $order->id)
                    ->where('requested_delivery_date',  $trackShipment['data']['shipments'][0]['requested_delivery_date'])
                    ->first();
                  }
        

            if ($checkOrderStatusAymakan) {
                continue;
            }

            $OrderStatusAymakan = new OrderStatusAymakan();
            $OrderStatusAymakan->status = $trackingInfo['status_code'];
            $OrderStatusAymakan->description = $trackingInfo['description'];
            $OrderStatusAymakan->reason_code = $trackingInfo['reason_code'];
            $OrderStatusAymakan->reason_description = $trackingInfo['reason_ar'];
            if($checkRequestedDeliveryAymakan  == null && strpos($trackingInfo['description_ar'],"تم تأجيل موعد إستلام الشحنة")){
                $OrderStatusAymakan->requested_delivery_date = $trackShipment['data']['shipments'][0]['requested_delivery_date'];
             }
            $OrderStatusAymakan->tracking = $trackShipment['data']['shipments'][0]['tracking_number'];
            $OrderStatusAymakan->reference = $trackShipment['data']['shipments'][0]['reference'];
            $OrderStatusAymakan->order_id = $order->id;
            $OrderStatusAymakan->created_at = $trackingInfo['created_at'];
            $OrderStatusAymakan->save();
            $newRequest = new Request();
            if ($trackingInfo['status_code'] === 'AY-0005') {
                $status_id = OrderEnum::COMPLETED_STATUS;
                $newRequest->merge([
                    'status_id' =>  $status_id,
                    'deliveryDate' =>  Carbon::parse($trackingInfo['created_at'])->format('Y-m-d'),
                ]);
            } elseif (in_array($trackingInfo['status_code'], ['AY-0026','AY-0009','AY-0030','AY-0069','AY-0004','AY-0056','AY-0003','AY-0080','AY-0086',
                'AY-0034','AY-0082','AY-0096','AY-0076','AY-0079'])) {
                $newRequest->merge([
                    'status_id' =>  OrderEnum::SHIPPING_STATUS,
                ]);
            }elseif ($trackingInfo['status_code'] === 'AY-0008') {
                $newRequest->merge([
                    'status_id' =>  OrderEnum::REJECTED_STATUS,
                ]);
            }elseif($trackingInfo['status_code'] === 'AY-0001')
            {
                $newRequest->merge([
                    'status_id' => OrderEnum::PREPARING_STATUS,
                    'tracking_number' => $trackShipment['data']['shipments'][0]['tracking_number'],
                    'pdf_label' => $trackShipment['data']['shipments'][0]['awb_url'] ?? null,
                ]);
            }elseif ($trackingInfo['status_code'] === 'AY-0029') {
                $newRequest->merge([
                    'status_id' =>  OrderEnum::CANCELED_STATUS,
                    'cancelDate' => Carbon::parse($trackingInfo['created_at'])->format('Y-m-d'),
                ]);
            }
            $this->repo->save($newRequest, $order->id);
        }

        return true;
    }
}
