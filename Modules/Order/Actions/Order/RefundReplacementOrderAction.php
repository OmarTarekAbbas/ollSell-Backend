<?php

namespace Modules\Order\Actions\Order;

use Modules\Order\Service\OrderRefundService;
use Modules\Order\Repositories\OrderRepository;

class RefundReplacementOrderAction
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
    public function execute($request, $id)
    {
        if ($request->type === 'refundReplacement') {

            return $this->orderRefundService->actionRefundReplacement($request, $id);
        } else {

            return $this->orderRefundService->actionRefundBalance($request, $id);
        }
    }
}
