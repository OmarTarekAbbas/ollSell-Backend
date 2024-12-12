<?php

namespace Modules\Order\Http\Controllers\Api;

use Modules\Basic\Http\Controllers\BasicController;
use Modules\Order\Http\Requests\OrderStatusRequest;
use Modules\Order\Service\OrderStatusService;
use Modules\Order\Http\Resources\Order\OrderStatusResource;

class OrderStatusController extends BasicController
{
    /**
     * @var OrderStatusService
     */
    protected $orderStatusService;

    /**
     * @param OrderStatusService $orderStatusService
     */
    public function __construct(OrderStatusService $orderStatusService)
    {
        $this->orderStatusService = $orderStatusService;
    }

    /**
     * @param OrderStatusRequest $request
     * @return \Illuminate\Http\Resources\Json\AnonymousResourceCollection
     */
    public function getOrderStatuses(OrderStatusRequest $request)
    {
        $orderId = $request->query('id');
        $orderStatuses = $this->orderStatusService->getOrderStatuses($orderId);
        return OrderStatusResource::collection($orderStatuses);
    }
}
