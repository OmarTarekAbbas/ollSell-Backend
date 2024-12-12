<?php

namespace Modules\Order\Actions\Order;

use Illuminate\Http\Request;
use Modules\Order\Entities\Order;
use Modules\Order\Repositories\OrderRepository;

class AdminUpdateOrderAction
{
    protected $order;
    protected $request;

    /**
     * Create a new Repository instance.
     *
     * @return void
     */
    public function __construct(Order $order, Request $request)
    {
        $this->order = $order;
        $this->request = $request;
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
    public function execute()
    {
        App(OrderRepository::class)->save($this->request, $this->order->id);
    }
}
