<?php

namespace Modules\Order\Actions\Order;

use Carbon\Carbon;
use Modules\Order\Entities\Order;
use Modules\Order\Entities\OrderStatus;
use Modules\Order\Entities\OrderStatusAymakan;
use Modules\Order\Enums\OrderEnum;
use Modules\Order\Service\OrderRefundService;
use Modules\Order\Repositories\OrderRepository;

class WebhooksShippingTestHendOrderAction
{
    protected $repo;
    protected $orderRefundService;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OrderRepository $repository, OrderRefundService $orderRefundService)
    {
        $this->repo = $repository;
        $this->orderRefundService = $orderRefundService;
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
    public function execute($request)
    {
        $getContent = $request->getContent();
        $getDataAymakanArray = json_decode($getContent, true); // Set second argument as TRUE
        $getId = $getDataAymakanArray['reference'];
        $reference = explode("-", $getId);

        $getDataAymakanStatus = strtolower($getDataAymakanArray['description']);

        $order = Order::where('tracking_number', $getDataAymakanArray['tracking'])->first();

        if (!$order) {
            return false;
        }


        if ($getDataAymakanArray['status'] === 'AY-0005') {
            $status_id = OrderEnum::COMPLETED_STATUS;
            $orderStatus = OrderStatus::where('order_id', $order->id)->latest()->first();
            $request->merge([
                'status_id' =>  $status_id,
                'deliveryDate' =>  Carbon::parse($orderStatus->created_at)->format('Y-m-d'),
            ]);
        } elseif (in_array($getDataAymakanArray['status_code'], ['AY-0026','AY-0009','AY-0030','AY-0069','AY-0004','AY-0056','AY-0003','AY-0080','AY-0086',
            'AY-0034','AY-0082','AY-0096','AY-0076','AY-0079']))
        {
            $request->merge([
                'status_id' =>  OrderEnum::SHIPPING_STATUS,
            ]);
        } elseif ($getDataAymakanArray['status'] === 'AY-0008') {
            $request->merge([
                'status_id' =>  OrderEnum::REJECTED_STATUS,
            ]);
        }

        $data = $this->repo->save($request, $order->id);
        $OrderStatusAymakan = new OrderStatusAymakan();
        $getOrderId = $getDataAymakanArray['reference'];
        $orderId = explode("-", $getOrderId);
        $OrderStatusAymakan->status = $getDataAymakanArray['status'];
        $OrderStatusAymakan->description = $getDataAymakanArray['description'];
        $OrderStatusAymakan->tracking = $getDataAymakanArray['tracking'];
        $OrderStatusAymakan->reference = $getDataAymakanArray['reference'];
        $OrderStatusAymakan->order_id = $orderId[0];
        $OrderStatusAymakan->save();

        return true;

    }
}
