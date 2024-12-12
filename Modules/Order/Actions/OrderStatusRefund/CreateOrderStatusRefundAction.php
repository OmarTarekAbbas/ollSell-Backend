<?php

namespace Modules\Order\Actions\OrderStatusRefund;

use Modules\Order\Repositories\OrderStatusRefundRepository;

class CreateOrderStatusRefundAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OrderStatusRefundRepository $repository)
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
    public function execute($orderRefund)
    {
        $request = request();
        $request->merge([
            'order_refund_id' => $orderRefund->id,
            'status_id' => $orderRefund->status_id,
        ]);
        $data = $this->repo->save($request);
        if ($data) {

            return true;
        }
        
        return false;
    }
}
