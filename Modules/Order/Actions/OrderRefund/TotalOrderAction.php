<?php

namespace Modules\Order\Actions\OrderRefund;

use Modules\Order\Enums\OrderEnum;
use Modules\Order\Entities\OrderItem;
use Modules\Order\Entities\OrderRefund;
use Modules\Order\Entities\OrderRefundItem;
use Modules\Order\Repositories\OrderRefundRepository;
use Modules\Order\Service\OrderStatusRefundService;

class TotalOrderAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OrderRefundRepository $repository)
    {
        $this->repo = $repository;
    }

    /**
     * This function executes an orderRefund by calculating the total quantity, total price, total VAT, cost
     * price, weight, and shipping fees, and then saves the orderRefund data.
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
        $orderItems = OrderItem::where('order_id', $request->order)->pluck('id')->toArray();
        $orderRefund = new OrderRefund();
        $orderRefund->order_id = $request->order;
        $orderRefund->status_id =  OrderEnum::REFUND_REQUESTED_STATUS;
        $orderRefund->reason =  $request->reason;
        $orderRefund->save();
        app()->make(OrderStatusRefundService::class)->store($orderRefund);

        foreach ($orderItems as  $orderItem) {
            $orderItemQuery = OrderItem::find($orderItem);
            $orderItemQuery->is_refund = 1;

            if ($orderItemQuery->save()) {
                $orderRefundItem = new OrderRefundItem();
                $orderRefundItem->order_refund_id = $orderRefund->id;
                $orderRefundItem->order_item_id = $orderItemQuery->id;
                $orderRefundItem->quantity = $orderItemQuery->quantity;
                $orderRefundItem->totalPrice = $orderItemQuery->totalPrice;
                $orderRefundItem->save();
            }
        }
        $arrayOrderRefundItem = OrderRefundItem::where('order_refund_id', $orderRefund->id)->get();
        $orderRefundUpdate = OrderRefund::find($orderRefund->id);
        $orderRefundUpdate->totalQuantity = collect($arrayOrderRefundItem)->sum('quantity');
        $orderRefundUpdate->countOrderItem = collect($arrayOrderRefundItem)->count();
        $orderRefundUpdate->grandTotal = collect($arrayOrderRefundItem)->sum('totalPrice');
        $orderRefundUpdate->save();

        return true;
    }
}
