<?php

namespace Modules\Order\Actions\OrderStatus;

use Modules\Order\Repositories\OrderStatusRepository;

class CreateOrderStatusAction
{
    protected $repo;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(OrderStatusRepository $repository)
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
    public function execute($order)
    {
        $request = request();
        $request->merge([
            'order_id' => $order->id,
            'status_id' => $order->status_id,
        ]);
        $data = $this->repo->save($request);
        if ($data) {

            return true;
        }
        
        return false;
    }
}
